<?php

namespace App\Jobs\Import;

use App\Enums\Import\StatusEnum;
use App\Imports\CustomerImport;
use App\Models\Customer;
use App\Models\CustomerAddress;
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

        $this->import->loadMissing('file');
        $file = $this->import->file;
        $data = Excel::toCollection(CustomerImport::class, $file->path);

        $this->import->total_record = $data->count();

        DB::beginTransaction();
        try {
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
                        'name_address' => $row[4],
                        'address' => $row[5],
                        'phone_address' => $row[6] ? Phone::normalize($row[6]) : '',
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
                        'phone_number' => [
                            'nullable',
                            'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                            'not_regex:/[a-zA-Z]/',
                            'max:16',
                        ],
                        'company' => [
                            'nullable',
                            'string',
                        ],
                        'name_address' => [
                            'nullable',
                            'string',
                        ],
                        'address' => [
                            'nullable',
                            'string',
                        ],
                        'phone_address' => [
                            'nullable',
                            'regex:/\+?([ -]?\d+)+|\(\d+\)([ -]\d+)/',
                            'not_regex:/[a-zA-Z]/',
                            'max:16',
                        ],
                    ], [], [
                        'name' => 'Nama',
                        'email' => 'Email',
                        'company' => 'Perusahaan',
                        'phone' => 'Phone',
                        'name_address' => 'Nama Alamat',
                        'address' => 'Alamat',
                        'phone_address' => 'Phone Alamat',
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
                    $user->fill([
                        'name' => $input['name'],
                        'email' => $input['email'],
                        'company' => $input['company'],
                        'phone_number' => $input['phone_number'],
                    ]);
                    $user->password = Str::slug($input['name'], '.').'@'.Str::substr($input['phone_number'], (Str::length($input['phone_number']) - 4), 4);

                    if ($user->save()) {
                        $customer = new Customer();
                        $customer->fill([
                            'user_id' => $user->id,
                        ]);

                        if (
                            $customer->save()
                            && $input['address']
                        ) {
                            $address = new CustomerAddress();
                            $nameAddress = $input['name_address'];
                            if (empty($nameAddress)) {
                                $nameAddress = $user->name;
                            }

                            $address->fill([
                                'customer_id' => $customer->id,
                                'name' => $nameAddress,
                                'address' => $input['address'],
                                'phone_number' => $input['phone_address'],
                                'is_default' => true,
                            ]);
                            $address->save();
                        }
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
