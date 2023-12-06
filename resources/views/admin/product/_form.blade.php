@extends('admin.layouts.admin')

@section('content')
<!--begin::Form-->
<form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" action="{{ route('product.store') }}" action-update="{{ route('product.update') }}" data-kt-redirect="{{ route('product.index') }}">
    <input type="hidden" name="product_id" value="{{ $product->id }}" />
    <!--begin::Aside column-->
    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
        <!--begin::Thumbnail settings-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>Thumbnail</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body text-center pt-0">
                <!--begin::Image input-->
                <!--begin::Dropzone-->
                <div class="dropzone" id="kt_modal_add_update_product_thumbnail">
                    <!--begin::Message-->
                    <div class="dz-message needsclick">
                        <!--begin::Icon-->
                        <i class="ki-duotone ki-file-up fs-3hx text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <!--end::Icon-->
                        <!--begin::Info-->
                        <div class="ms-4">
                            <h3 class="dfs-3 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                            {{-- <span class="fw-semibold fs-4 text-muted">Upload 1 file, only jpg, jpeg and png with max size 2Mb</span> --}}
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
                <!--end::Dropzone-->
                <!--end::Image input-->
                <!--begin::Description-->
                <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted with max 2Mb</div>
                <!--end::Description-->
                <input type="hidden" name="product_thumbnail_id" value="{{ $product->thumbnail_id }}">
                <input type="hidden" id="thumbnail_name" value="{{ optional($product->thumbnail)->name }}">
                <input type="hidden" id="thumbnail_full_url" value="{{ optional($product->thumbnail)->full_url }}">
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Thumbnail settings-->
        <!--begin::Category & tags-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>Produk Detail</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <!--begin::Label-->
                <label class="form-label">Kategori</label>
                <!--end::Label-->
                <!--begin::Select2-->
                <select class="form-select mb-2" data-placeholder="Select kategori" data-allow-clear="true" multiple="multiple" name="product_category_id[]"  data-url="{{ route('ajax.category.list') }}" data-kt-ecommerce-catalog-add-product="product_option">
                    <option></option>
                    @foreach ($product->categories->pluck('name', 'id') as $id => $name)
                        <option value="{{ $id }}" selected>{{ $name }}</option>
                    @endforeach
                </select>
                <!--end::Select2-->
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category & tags-->
        <!--begin::Category & tags-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>Stok</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <!--begin::Label-->
                <label class="form-label">Jumlah</label>
                <!--end::Label-->
                <input type="text" name="product_stock" class="form-control mb-2" placeholder="Jumlah produk" value="{{ $product->stock }}" />
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category & tags-->
        <!--begin::Status-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>Status</h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                </div>
                <!--begin::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="fv-row">
                    <!--begin::Input-->
                    <div class="form-check form-check-custom form-check-solid mb-2">
                        <input class="form-check-input" type="checkbox" id="kt_ecommerce_add_product_is_best_seller_checkbox" value="1" name="product_is_best_seller" {{ $product->is_best_seller ? 'checked' : '' }} />
                        <label class="form-check-label">Best Seller</label>
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row">
                    <!--begin::Input-->
                    <div class="form-check form-check-custom form-check-solid mb-2">
                        <input class="form-check-input" type="checkbox" id="kt_ecommerce_add_product_is_recommendation_checkbox" value="1" name="product_is_recommendation" {{ $product->is_recommendation ? 'checked' : '' }} />
                        <label class="form-check-label">Rekomendasi</label>
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row">
                    <!--begin::Input-->
                    <div class="form-check form-check-custom form-check-solid mb-2">
                        <input class="form-check-input" type="checkbox" id="kt_ecommerce_add_product_is_new_checkbox" value="1" name="product_is_new" {{ $product->is_new ? 'checked' : '' }} />
                        <label class="form-check-label">Baru</label>
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row">
                    <!--begin::Input-->
                    <div class="form-check form-check-custom form-check-solid mb-2">
                        <input class="form-check-input" type="checkbox" id="kt_ecommerce_add_product_is_special_discount_checkbox" value="1" name="product_is_special_discount" {{ $product->is_special_discount ? 'checked' : '' }} />
                        <label class="form-check-label">Diskon Istimewa</label>
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row">
                    <!--begin::Input-->
                    <div class="form-check form-check-custom form-check-solid mb-2">
                        <input class="form-check-input" type="checkbox" id="kt_ecommerce_add_product_is_active_checkbox" value="1" name="product_is_active" {{ $product->id ? ($product->is_active ? 'checked' : '') : 'checked' }} />
                        <label class="form-check-label">Aktif</label>
                    </div>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Status-->
    </div>
    <!--end::Aside column-->
    <!--begin::Main column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <!--begin:::Tabs-->
        <!--begin::General options-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Umum</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="required form-label">Kode Produk</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" name="product_code" class="form-control mb-2 text-uppercase" placeholder="Kode produk" value="{{ $product->code }}" />
                    <!--end::Input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Kode produk wajib diisi dan disarankan agar unik.</div>
                    <!--end::Description-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="required form-label">Nama Produk</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" name="product_name" class="form-control mb-2" placeholder="Nama produk" value="{{ $product->name }}" />
                    <!--end::Input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Nama produk wajib diisi dan disarankan agar unik.</div>
                    <!--end::Description-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div>
                    <!--begin::Label-->
                    <label class="form-label">Deskripsi</label>
                    <!--end::Label-->
                    <!--begin::Editor-->
                    <div id="kt_ecommerce_add_product_description" class="min-h-200px mb-2"></div>
                    <textarea value="{!! $product->description !!}" name="product_description" style="display: none;">{!! $product->description !!}</textarea>
                    <!--end::Editor-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Tetapkan deskripsi produk untuk visibilitas yang lebih baik.</div>
                    <!--end::Description-->
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card header-->
        </div>
        <!--end::General options-->

        <!--begin::Pricing-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Harga</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="required form-label">Harga</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" name="product_price" class="form-control mb-2" placeholder="Harga Produk" value="{{ $product->price }}" />
                    <!--end::Input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Tetapkan harga produk.</div>
                    <!--end::Description-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold mb-2">Tipe Diskon
                    <span class="ms-1" data-bs-toggle="tooltip" title="Pilih jenis diskon yang akan diterapkan pada produk ini">
                        <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span></label>
                    <!--End::Label-->
                    <!--begin::Row-->
                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-1 row-cols-xl-3 g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                        <!--begin::Col-->
                        <div class="col">
                            <!--begin::Option-->
                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary {{ $product->id ? ($product->discount_type == '1' ? 'active' : '') : 'active' }} d-flex text-start p-6" data-kt-button="true">
                                <!--begin::Radio-->
                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                    <input class="form-check-input" type="radio" name="product_discount_type" value="1" checked="checked"  {{ $product->id ? ($product->discount_type == '1' ? 'checked' : '') : 'checked' }} />
                                </span>
                                <!--end::Radio-->
                                <!--begin::Info-->
                                <span class="ms-5">
                                    <span class="fs-4 fw-bold text-gray-800 d-block">Tidak ada Diskon</span>
                                </span>
                                <!--end::Info-->
                            </label>
                            <!--end::Option-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col">
                            <!--begin::Option-->
                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary {{ $product->discount_type == '2' ? 'active' : '' }} d-flex text-start p-6" data-kt-button="true">
                                <!--begin::Radio-->
                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                    <input class="form-check-input" type="radio" name="product_discount_type" value="2" {{ $product->discount_type == '2' ? 'checked' : '' }} />
                                </span>
                                <!--end::Radio-->
                                <!--begin::Info-->
                                <span class="ms-5">
                                    <span class="fs-4 fw-bold text-gray-800 d-block">Persentase %</span>
                                </span>
                                <!--end::Info-->
                            </label>
                            <!--end::Option-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col">
                            <!--begin::Option-->
                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary {{ $product->discount_type == '3' ? 'active' : '' }} d-flex text-start p-6" data-kt-button="true">
                                <!--begin::Radio-->
                                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                    <input class="form-check-input" type="radio" name="product_discount_type" value="3" {{ $product->discount_type == '3' ? 'checked' : '' }} />
                                </span>
                                <!--end::Radio-->
                                <!--begin::Info-->
                                <span class="ms-5">
                                    <span class="fs-4 fw-bold text-gray-800 d-block">Potongan Harga</span>
                                </span>
                                <!--end::Info-->
                            </label>
                            <!--end::Option-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-10 fv-row {{ $product->discount_type == '2' ? '' : 'd-none' }}" id="kt_ecommerce_add_product_discount_percentage">
                    <!--begin::Label-->
                    <label class="form-label">Tetapkan Persentase Diskon</label>
                    <!--end::Label-->
                    <!--begin::Slider-->
                    <div class="d-flex flex-column text-center mb-5">
                        <div class="d-flex align-items-start justify-content-center mb-7">
                            <span class="fw-bold fs-3x" id="kt_ecommerce_add_product_discount_label">{{ $product->discount_percentage ?: 0 }}</span>
                            <span class="fw-bold fs-4 mt-1 ms-2">%</span>
                        </div>
                        <div id="kt_ecommerce_add_product_discount_slider" class="noUi-sm"></div>
                        <input type="hidden" name="product_discount_percentage" value="{{ $product->discount_percentage }}" />
                    </div>
                    <!--end::Slider-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Tetapkan persentase diskon untuk diterapkan pada produk ini.</div>
                    <!--end::Description-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="{{ $product->discount_type == '3' ? '' : 'd-none' }} mb-10 fv-row" id="kt_ecommerce_add_product_discount_fixed">
                    <!--begin::Label-->
                    <label class="form-label">Potongan harga</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" name="product_discount_price" class="form-control mb-2" placeholder="Discounted price" name="product_discount_price" value="{{ $product->discount_price }}" />
                    <!--end::Input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Tetapkan harga produk yang didiskon. Produk akan dikurangi dengan harga tetap yang ditentukan</div>
                    <!--end::Description-->
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card header-->
        </div>
        <!--end::Pricing-->
        <!--end::Tab content-->
        <div class="d-flex justify-content-end">
            <!--begin::Button-->
            <a href="{{ route('product.index') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancel</a>
            <!--end::Button-->
            <!--begin::Button-->
            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                <span class="indicator-label">Save Changes</span>
                <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
            <!--end::Button-->
        </div>
    </div>
    <!--end::Main column-->
</form>
<!--end::Form-->
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ mix('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('assets/js/custom/apps/master/product/list/add.js') }}"></script>
@endpush
