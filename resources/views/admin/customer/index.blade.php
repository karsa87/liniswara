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
                <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Agen" />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end me-2" data-kt-customer-table-toolbar="base">
                <!--begin::Add customer-->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_import_customer">
                    <i class="ki-duotone ki-file-up fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Import
                </button>
                <!--end::Add customer-->
            </div>
            <!--end::Toolbar-->

            @hasPermission('customer-tambah')
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                <!--begin::Add customer-->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">
                <i class="ki-duotone ki-plus fs-2"></i>Tambah Agen</button>
                <!--end::Add customer-->
            </div>
            <!--end::Toolbar-->
            @endhasPermission

            <!--begin::Modal - Add task-->
            <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_customer_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Form Agen</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-customers-modal-action="close">
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
                            <form id="kt_modal_add_customer_form" class="form" action="{{ route('customer.store') }}" action-update="{{ route('customer.update') }}">
                                @csrf

                                <input type="hidden" name="customer_id" />
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="customer_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">Perusahaan</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="customer_company" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Perusahaan" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Email</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="email" name="customer_email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Phone</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="customer_phone_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="08988xxxxxx" max="16" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Marketing</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select class="form-select form-control form-control-solid" data-placeholder="Select Marketing" data-allow-clear="true" name="customer_marketing" data-kt-ecommerce-catalog-add-customer="customer_option" style="width: 100%;">
                                                @foreach (\App\Enums\Preorder\MarketingEnum::MAP_LABEL as $key => $name)
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->

                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Area</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select class="form-select mb-2" data-placeholder="Select area" data-allow-clear="true" multiple="multiple" name="customer_area_id[]"  data-url="{{ route('ajax.area.list') }}" data-kt-ecommerce-catalog-add-customer="customer_option" id="add-customer_area_id">
                                                <option></option>
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    @foreach ($schools->chunk(2) as $schoolChunks)
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        @foreach ($schoolChunks as $id => $name)
                                            <!--begin::Input group-->
                                            <div class="fv-row flex-row-fluid">
                                                <!--begin::Label-->
                                                <label class="required fw-semibold fs-6 mb-2">{!! $name !!}</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="number" name="customer_schools[{{ $id }}]" class="form-control form-control-solid mb-3 mb-lg-0" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                        @endforeach
                                    </div>
                                    @endforeach

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <!--begin::Main wrapper-->
                                        <div class="fv-row flex-row-fluid" data-kt-password-meter="true">
                                            <!--begin::Wrapper-->
                                            <div class="mb-1">
                                                <!--begin::Label-->
                                                <label class="required form-label fw-semibold fs-6 mb-2">
                                                    Password
                                                </label>
                                                <!--end::Label-->

                                                <!--begin::Input wrapper-->
                                                <div class="position-relative mb-3">
                                                    <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="customer_password" autocomplete="off" />

                                                    <!--begin::Visibility toggle-->
                                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                                        data-kt-password-meter-control="visibility">
                                                            <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                            <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </span>
                                                    <!--end::Visibility toggle-->
                                                </div>
                                                <!--end::Input wrapper-->

                                                <!--begin::Highlight meter-->
                                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                                </div>
                                                <!--end::Highlight meter-->
                                            </div>
                                            <!--end::Wrapper-->

                                            <!--begin::Hint-->
                                            <div class="text-muted">
                                                Use 5 or more characters with a mix of letters, numbers & symbols.
                                            </div>
                                            <!--end::Hint-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">
                                                Alamat
                                                <button type="button" class="btn btn-icon btn-light-primary w-100px h-30px me-3" data-bs-stacked-modal="#kt_modal_search_region">
                                                    <i class="ki-duotone ki-geolocation fs-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    Cari
                                                </button>
                                            </label>
                                            <!--end::Label-->

                                            <textarea class="form-control form-control-solid" rows="3" data-kt-region="customer_region_description" disabled></textarea>

                                            <input type="hidden" name="customer_province_id" data-kt-region="customer_province_id" />
                                            <input type="hidden" name="customer_regency_id" data-kt-region="customer_regency_id" />
                                            <input type="hidden" name="customer_district_id" data-kt-region="customer_district_id" />
                                            <input type="hidden" name="customer_village_id" data-kt-region="customer_village_id" />
                                        </div>
                                        <!--end::Input group-->

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fs-5 fw-bold form-label mb-2">
                                                <span class="required">Alamat Detail</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea class="form-control form-control-solid" rows="3" name="customer_address" placeholder="Masukkan alamat"></textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                                <!--end::Scroll-->
                                <!--begin::Actions-->
                                <div class="text-center pt-10">
                                    <button type="reset" class="btn btn-light me-3" data-kt-customers-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-customers-modal-action="submit">
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

            <!--begin::Modal - Add task-->
            <div class="modal fade" id="kt_modal_import_customer" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_import_customer_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Tambah Agen</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-customers-modal-import-action="close">
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
                            <form id="kt_modal_import_customer_form" class="form" action="{{ route('customer.import') }}">
                                @csrf

                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_import_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_import_customer_header" data-kt-scroll-wrappers="#kt_modal_import_customer_scroll" data-kt-scroll-offset="300px">

                                    <div class="d-flex flex-column flex-md-row gap-5 mb-5">
                                        <a href="{{ route('customer.export.template_import') }}" class="btn btn-success" target="_blank">
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
                                            <div class="dropzone" id="kt_modal_import_customer_file">
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
                                            <input type="hidden" name="customer_file" value="" id="input_customer_file" />
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                                <!--end::Scroll-->
                                <!--begin::Actions-->
                                <div class="text-center pt-10">
                                    <button type="reset" class="btn btn-light me-3" data-kt-customers-modal-import-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-customers-modal-import-action="submit">
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
        <table class="table align-middle table-row-dashed table-striped fs-6 gy-5" id="kt_table_customers" data-url="{{ route('customer.index.list') }}" data-url-delete="{{ route('customer.delete') }}" data-url-edit="{{ route('customer.show') }}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Perusahaan</th>
                    <th class="min-w-125px">Nama</th>
                    <th class="min-w-125px">Email</th>
                    <th class="min-w-125px">Phone</th>
                    <th class="min-w-125px">Target</th>
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

@include('admin.components.search_region', [
    'target_element' => 'customer_',
    'prefix' => 'customer',
])
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('assets/js/custom/apps/master/customer/list/table.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/master/customer/list/add.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/master/customer/list/import.js') }}"></script>
@endpush
