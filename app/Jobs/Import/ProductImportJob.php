<?php

namespace App\Jobs\Import;

use App\Enums\Import\StatusEnum;
use App\Enums\ProductDiscountTypeEnum;
use App\Imports\ProductImport;
use App\Models\Category;
use App\Models\ImportDetail;
use App\Models\Product;
use App\Services\StockHistoryLogService;
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

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $stockLogService;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $import
    ) {
        $this->stockLogService = app()->make(StockHistoryLogService::class);
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
            $data = Excel::toCollection(ProductImport::class, $file->path);

            $this->import->total_record = $data->count();
            if ($this->import->total_record > 0) {
                foreach ($data->first() as $i => $row) {
                    if ($i == 0) {
                        continue;
                    }

                    $category = null;
                    if (
                        $row->has(0)
                        && $row[0]
                    ) {
                        $category = Category::whereRaw('LOWER(name) = ?', $row[0])->first();
                    }

                    $input = [
                        'category_id' => optional($category)->id,
                        'name' => $row[1],
                        'code' => $row[2],
                        'stock' => $row[3],
                        'price' => $row[4],
                        'price_zone_2' => $row[5],
                        'price_zone_3' => $row[6],
                        'price_zone_4' => $row[7],
                        'price_zone_5' => $row[8],
                        'price_zone_6' => $row[9],
                        'description' => htmlentities($row[10]),
                        'discount_type' => ProductDiscountTypeEnum::DISCOUNT_NO,
                        'discount_price' => null,
                        'discount_percentage' => null,
                    ];

                    $validator = Validator::make($input, [
                        'category_id' => [
                            'nullable',
                            'numeric',
                        ],
                        'code' => [
                            'required',
                            'string',
                            Rule::unique((new Product())->getTable(), 'code')->where(function ($query) {
                                $query->whereNull('deleted_at');

                                return $query;
                            }),
                        ],
                        'name' => [
                            'required',
                            'string',
                        ],
                        'description' => [
                            'nullable',
                            'not_regex:/<script[\s\S]*?>[\s\S]*?/i',
                        ],
                        'stock' => [
                            'nullable',
                            'numeric',
                            'min:0',
                        ],
                        'price' => [
                            'nullable',
                            'numeric',
                            'min:0',
                        ],
                        'price_zone_2' => [
                            'nullable',
                            'numeric',
                            'min:0',
                        ],
                        'price_zone_3' => [
                            'nullable',
                            'numeric',
                            'min:0',
                        ],
                        'price_zone_4' => [
                            'nullable',
                            'numeric',
                            'min:0',
                        ],
                        'price_zone_5' => [
                            'nullable',
                            'numeric',
                            'min:0',
                        ],
                        'price_zone_6' => [
                            'nullable',
                            'numeric',
                            'min:0',
                        ],
                    ], [], [
                        'category_id' => 'Kategori',
                        'code' => 'Nama Produk',
                        'name' => 'Kode Produk',
                        'stock' => 'Stok Produk',
                        'price' => 'Harga Zona 1',
                        'price_zone_2' => 'Harga Zona 2',
                        'price_zone_3' => 'Harga Zona 3',
                        'price_zone_4' => 'Harga Zona 4',
                        'price_zone_5' => 'Harga Zona 5',
                        'price_zone_6' => 'Harga Zona 6',
                        'description' => 'Deskripsi Produk',
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

                    switch ($input['discount_type']) {
                        case ProductDiscountTypeEnum::DISCOUNT_PERCENTAGE:
                            $input['discount_price'] = null;
                            break;

                        case ProductDiscountTypeEnum::DISCOUNT_PRICE:
                            $input['discount_percentage'] = null;
                            break;

                        default:
                            $input['discount_price'] = null;
                            $input['discount_percentage'] = null;
                            break;
                    }

                    $product = new Product();
                    unset($input['category_id']);
                    $input['slug'] = Str::slug($input['name']);
                    $product->fill($input);

                    if ($product->save()) {
                        if ($category) {
                            $product->categories()->sync([
                                $category->id,
                            ]);
                        }
                    }

                    $this->stockLogService->logStockIn(
                        $product,
                        $product->id,
                        0,
                        $product->stock
                    );

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
