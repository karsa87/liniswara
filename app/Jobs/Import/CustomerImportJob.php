<?php

namespace App\Jobs\Import;

use App\Enums\Import\StatusEnum;
use App\Imports\CustomerImport;
use App\Models\Customer;
use App\Models\ImportDetail;
use App\Models\User;
use App\Utils\Phone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class CustomerImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $import)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->import->status = StatusEnum::PROCESS;
        $this->import->save();
        $totalSuccess = 0;
        $totalFailed = 0;
        $logFailed = [];

        DB::beginTransaction();
        try {
            $this->import->loadMissing('file');
            $file = $this->import->file;
            $data = Excel::toCollection(CustomerImport::class, $file->path);

            $this->import->total_record = $data->count();
            if ($this->import->total_record > 0) {
                foreach ($data->first() as $i => $row) {
                    if ($i == 0) {
                        continue;
                    }

                    $input = [
                        'name' => $row[0],
                        'email' => $row[1],
                        'company' => $row[2],
                        'phone_number' => Phone::normalize($row[3]),
                    ];

                    $validator = Validator::make($input, [
                        'name' => [
                            'required',
                            'string',
                        ],
                        'email' => [
                            'required',
                            Rule::unique((new User())->getTable(), 'email')->where(function ($query) {
                                $query->whereNull('deleted_at');

                                return $query;
                            }),
                        ],
                        'phone' => [
                            'nullable',
                            'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                            'not_regex:/[a-zA-Z]/',
                            'max:16',
                        ],
                        'company' => [
                            'nullable',
                            'string',
                        ],
                    ], [], [
                        'name' => 'Nama',
                        'email' => 'Email',
                        'company' => 'Perusahaan',
                        'phone' => 'Phone',
                    ]);

                    if ($validator->fails()) {
                        $reason = collect($validator->errors()->toArray() ?? [])->collapse();

                        $logFailed[] = [
                            'import_id' => $this->import->id,
                            'description' => 'Gagal import '.$input['name'],
                            'reason' => json_encode($reason),
                        ];

                        $totalFailed++;

                        continue;
                    }

                    $user = new User();
                    $user->fill($input);
                    $user->password = Str::slug($input['name'], '.').'@'.Str::substr($input['phone_number'], (Str::length($input['phone_number']) - 4), 4);

                    if ($user->save()) {
                        $customer = new Customer();
                        $customer->fill([
                            'user_id' => $user->id,
                        ]);
                        $customer->save();
                    }

                    $totalSuccess++;
                }
            }

            DB::commit();
            $this->import->status = StatusEnum::DONE;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->import->status = StatusEnum::FAILED;

            $logFailed[] = [
                'import_id' => $this->import->id,
                'description' => 'Error 500',
                'reason' => json_encode([
                    $th->getMessage().' Line : '.$th->getLine(),
                ]),
            ];
        }

        $this->import->total_failed = $totalFailed;
        $this->import->total_success = $totalSuccess;
        $this->import->save();

        if (count($logFailed)) {
            ImportDetail::insert($logFailed);
        }
    }
}
