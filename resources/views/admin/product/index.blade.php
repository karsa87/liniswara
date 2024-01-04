@extends('admin.layouts.admin')

@section('content')
<!--begin::Card-->
<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" data-kt-product-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Produk" />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end me-2" data-kt-product-table-toolbar="base">
                <!--begin::Add product-->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_import_product">
                    <i class="ki-duotone ki-file-up fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Import
                </button>
                <!--end::Add product-->
            </div>
            <!--end::Toolbar-->

            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end me-1" data-kt-product-table-toolbar="base">
                <!--begin::Filter-->
                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-file-down fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Export</button>
                <!--begin::Menu 1-->
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="data-kt-menu-export-product">
                    <form action="{{ route('product.export') }}" id="kt_ecommerce_export_product_form">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">Export</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Separator-->
                        <!--begin::Content-->
                        <div class="px-7 py-5" data-kt-product-table-export="form">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-semibold">Kategori</label>
                                <select class="form-select form-select-solid fw-bold select-export-product" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-product-table-filter="product" name="search_category_id" data-url="{{ route('ajax.category.list') }}">
                                    <option value="" selected></option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-product-table-export="export">Download</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
                    </form>
                </div>
                <!--end::Menu 1-->
                <!--end::Filter-->
            </div>
            <!--end::Toolbar-->

            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                <!--begin::Filter-->
                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-filter fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Filter</button>
                <!--begin::Menu 1-->
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="data-kt-menu-filter-product">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-5 text-dark fw-bold">Filter Options</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Separator-->
                    <div class="separator border-gray-200"></div>
                    <!--end::Separator-->
                    <!--begin::Content-->
                    <div class="px-7 py-5" data-kt-product-table-filter="form">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Kategori</label>
                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-product-table-filter="product" name="search_category_id" data-url="{{ route('ajax.category.list') }}">
                                <option value="" selected></option>
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-product-table-filter="reset">Reset</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-product-table-filter="filter">Apply</button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Menu 1-->
                <!--end::Filter-->
                <!--begin::Add product-->
                <a href="{{ route('product.create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>Tambah Produk
                </a>
                <!--end::Add product-->
            </div>
            <!--end::Toolbar-->


            <!--begin::Modal - Add task-->
            <div class="modal fade" id="kt_modal_import_product" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_import_product_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Tambah Agen</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-products-modal-import-action="close">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body px-5 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_import_product_form" class="form" action="{{ route('product.import') }}">
                                @csrf

                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_import_product_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_import_product_header" data-kt-scroll-wrappers="#kt_modal_import_product_scroll" data-kt-scroll-offset="300px">

                                    <div class="d-flex flex-column flex-md-row gap-5 mb-5">
                                        <a href="{{ route('product.export.template_import') }}" class="btn btn-success" target="_blank">
                                            <i class="ki-duotone ki-file-down fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Download Template
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-5 mb-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Dropzone-->
                                            <div class="dropzone" id="kt_modal_import_product_file">
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
                                                        <span class="fw-semibold fs-4 text-muted">Upload 1 file, only jpg, jpeg and png with max size 2Mb</span>
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                            </div>
                                            <!--end::Dropzone-->
                                            <input type="hidden" name="product_file" value="" id="input_product_file" />
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                                <!--end::Scroll-->
                                <!--begin::Actions-->
                                <div class="text-center pt-10">
                                    <button type="reset" class="btn btn-light me-3" data-kt-products-modal-import-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-products-modal-import-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - Add task-->

        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-4">
        <!--begin::Table-->
        <table class="table align-middle table-row-dashed table-striped fs-6 gy-5" id="kt_table_products" data-url="{{ route('product.index.list') }}" data-url-delete="{{ route('product.delete') }}" data-url-edit="{{ route('product.show') }}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-25px">Gambar</th>
                    <th class="min-w-125px">Produk</th>
                    <th class="min-w-75px">Kode</th>
                    <th class="min-w-125px">Stok</th>
                    <th class="min-w-125px">Kategori</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
        </table>
        <!--end::Table-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('assets/js/custom/apps/master/product/list/table.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/master/product/import.js') }}"></script>
@endpush
