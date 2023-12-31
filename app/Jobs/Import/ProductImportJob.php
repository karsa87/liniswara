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

        $this->import->loadMissing('file');
        $file = $this->import->file;
        $data = Excel::toCollection(ProductImport::class, $file->path);

        $this->import->total_record = $data->count();
        $totalSuccess = 0;
        $totalFailed = 0;
        $logFailed = [];
        if ($this->import->total_record > 0) {
            foreach ($data->first() as $i => $row) {
                if ($i == 0) {
                    continue;
                }

                $category = Category::whereRaw('LOWER(name) = ?', $row[0])->first();
                if (is_null($category)) {
                    $logFailed[] = [
                        'import_id' => $this->import->id,
                        'description' => 'Gagal import '.$row[1],
                        'reason' => json_encode([
                            'Kategori tidak ditemukan '.$row[0],
                        ]),
                    ];

                    $totalFailed++;

                    continue;
                }

                $input = [
                    'category_id' => optional($category)->id,
                    'name' => $row[1],
                    'code' => $row[2],
                    'stock' => $row[3],
                    'price' => $row[4],
                    'price_zone_2' => $row[5],
                    'description' => htmlentities($row[6]),
                    'discount_type' => ProductDiscountTypeEnum::DISCOUNT_NO,
                    'discount_price' => null,
                    'discount_percentage' => null,
                ];

                $validator = Validator::make($input, [
                    'category_id' => [
                        'required',
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
                ], [], [
                    'category_id' => 'Kategori',
                    'code' => 'Nama Produk',
                    'name' => 'Kode Produk',
                    'description' => 'Stok Produk',
                    'stock' => 'Harga Zona 1',
                    'price' => 'Harga Zona 2',
                    'price_zone_2' => 'Deskripsi Produk',
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
                    $product->categories()->sync([
                        $category->id,
                    ]);
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

        $this->import->total_failed = $totalFailed;
        $this->import->total_success = $totalSuccess;
        $this->import->status = StatusEnum::DONE;
        $this->import->save();

        if (count($logFailed)) {
            ImportDetail::insert($logFailed);
        }
    }
}
