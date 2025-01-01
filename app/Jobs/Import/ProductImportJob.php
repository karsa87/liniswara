<?php

namespace App\Jobs\Import;

use App\Enums\Import\StatusEnum;
use App\Enums\ProductDiscountTypeEnum;
use App\Imports\ProductImport;
use App\Models\Category;
use App\Models\File;
use App\Models\ImportDetail;
use App\Models\Product;
use App\Models\School;
use App\Services\StockHistoryLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

                    $categories = explode('-', $row[0] ?? '');
                    $parent = trim($categories[0]);
                    $child = trim($categories[1] ?? '');

                    $category = null;
                    if ($parent) {
                        if ($child) {
                            $category = Category::whereRaw('LOWER(name) = ?', str($child)->lower())->first();
                        } else {
                            $category = Category::whereRaw('LOWER(name) = ?', str($parent)->lower())->first();
                        }

                        if (empty($category)) {
                            $parentCategory = Category::whereRaw('LOWER(name) = ?', str($parent)->lower())->first();
                            if (count($categories) == 2 && ! empty($child)) {
                                if (empty($parentCategory)) {
                                    $parentCategory = Category::create([
                                        'name' => $parent,
                                        'slug' => str($parent)->slug(),
                                    ]);
                                }

                                $category = Category::create([
                                    'name' => $child,
                                    'slug' => str($child)->slug(),
                                    'parent_category_id' => $parentCategory->id,
                                ]);
                            } else {
                                $category = $parentCategory;
                            }
                        }
                    }

                    $schools = explode(';', $row[4] ?? '');

                    $input = [
                        'category_id' => optional($category)->id,
                        'name' => $row[1],
                        'code' => $row[2],
                        'stock' => $row[3] ?? 0,
                        'price' => $row[5],
                        'price_zone_2' => $row[6],
                        'price_zone_3' => $row[7],
                        'price_zone_4' => $row[8],
                        'price_zone_5' => $row[9],
                        'price_zone_6' => $row[10],
                        'description' => htmlentities($row[11]),
                        'image_url' => 'files/20250101/'.$row[12],
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
                            // Rule::unique((new Product())->getTable(), 'code')->where(function ($query) {
                            //     $query->whereNull('deleted_at');

                            //     return $query;
                            // }),
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
                        'image_url' => [
                            'nullable',
                            // 'url',
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
                        'image_url' => 'Image URL',
                    ]);

                    $thumbnailUrl = $input['image_url'];
                    if ($thumbnailUrl && ! Str::endsWith($thumbnailUrl, ['.jpg', '.jpeg', '.png', '.gif'])) {
                        $logFailed[] = [
                            'import_id' => $this->import->id,
                            'description' => 'Gagal import '.$input['name'],
                            'reason' => json_encode([
                                'image_url' => 'Format gambar harus .jpg, .jpeg, .png, .gif',
                            ]),
                        ];

                        $totalFailed++;

                        continue;
                    }

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

                    if ($thumbnailUrl) {
                        $file = new File([
                            'name' => $input['name'],
                        ]);
                        if (Str::contains($thumbnailUrl, Storage::url('/'))) {
                            $file->path = Str::remove(Storage::url('/'), $thumbnailUrl);
                        } else {
                            $file->url = $thumbnailUrl;
                        }

                        $file->save();
                        $input['thumbnail_id'] = $file->id;
                    }

                    $product = new Product();
                    unset($input['category_id']);
                    unset($input['image_url']);
                    $input['slug'] = Str::slug($input['name']);
                    $product->fill($input);

                    if ($product->save()) {
                        if ($category) {
                            $product->categories()->sync([
                                $category->id,
                            ]);
                        }

                        if (count($schools) > 0) {
                            foreach ($schools as $school) {
                                $school = School::whereRaw('LOWER(name) = ?', str(trim($school))->lower())->first();
                                if (empty($school)) {
                                    $school = School::create([
                                        'name' => trim($school),
                                    ]);
                                }

                                $school->product()->syncWithoutDetaching([
                                    $product->id,
                                ]);
                            }
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
            dd($th);
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
