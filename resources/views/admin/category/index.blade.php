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
                <input type="text" data-kt-category-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Kategori" />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-category-table-toolbar="base">
                <!--begin::Add category-->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_category">
                <i class="ki-duotone ki-plus fs-2"></i>Tambah Kategori</button>
                <!--end::Add category-->
            </div>
            <!--end::Toolbar-->

            <!--begin::Modal - Add task-->
            <div class="modal fade" id="kt_modal_add_category" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_category_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Tambah Kategori</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-categorys-modal-action="close">
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
                            <form id="kt_modal_add_category_form" class="form" action="{{ route('category.store') }}" action-update="{{ route('category.update') }}">
                                @csrf

                                <input type="hidden" name="category_id" />
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_category_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_category_header" data-kt-scroll-wrappers="#kt_modal_add_category_scroll" data-kt-scroll-offset="300px">

                                    <div class="d-flex flex-column flex-md-row gap-5 mb-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Dropzone-->
                                            <div class="dropzone" id="kt_modal_add_update_category_image">
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
                                            <input type="hidden" name="category_image_id" value="" />
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="category_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                                Sub dari Kategori
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Select2-->
                                            <select class="form-select form-select-solid" data-hide-search="false" data-allow-clear="true" data-placeholder="Pilih sub kategori" name="category_parent_id">
                                                <option value="" selected></option>

                                                @foreach ($categories as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            <!--end::Select2-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                                <!--end::Scroll-->
                                <!--begin::Actions-->
                                <div class="text-center pt-10">
                                    <button type="reset" class="btn btn-light me-3" data-kt-categorys-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-categorys-modal-action="submit">
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
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_categorys" data-url="{{ route('category.index.list') }}" data-url-delete="{{ route('category.delete') }}" data-url-edit="{{ route('category.show') }}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Nama</th>
                    <th class="min-w-125px">Sub Kategori dari</th>
                    <th class="min-w-125px">Logo</th>
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
    <script src="{{ mix('assets/js/custom/apps/master/category/list/table.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/master/category/list/add.js') }}"></script>

    <script src="{{ mix('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ mix('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ mix('assets/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ mix('assets/js/custom/utilities/modals/users-search.js') }}"></script>
@endpush
