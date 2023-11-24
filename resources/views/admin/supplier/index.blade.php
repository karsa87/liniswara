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
                <input type="text" data-kt-supplier-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search supplier" />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-supplier-table-toolbar="base">
                <!--begin::Filter-->
                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-filter fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Filter</button>
                <!--begin::Menu 1-->
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-5 text-dark fw-bold">Filter Options</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Separator-->
                    <div class="separator border-gray-200"></div>
                    <!--end::Separator-->
                    <!--begin::Content-->
                    <div class="px-7 py-5" data-kt-supplier-table-filter="form">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            {{-- <label class="form-label fs-6 fw-semibold">Role:</label>
                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-supplier-table-filter="role" data-hide-search="true">
                                <option value="" selected></option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{!! $role->name !!}</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-supplier-table-filter="reset">Reset</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-supplier-table-filter="filter">Apply</button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Menu 1-->
                <!--end::Filter-->
                <!--begin::Add supplier-->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_supplier">
                <i class="ki-duotone ki-plus fs-2"></i>Tambah Pemasok</button>
                <!--end::Add supplier-->
            </div>
            <!--end::Toolbar-->

            <!--begin::Modal - Add task-->
            <div class="modal fade" id="kt_modal_add_supplier" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_supplier_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Tambah Pemasok</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-suppliers-modal-action="close">
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
                            <form id="kt_modal_add_supplier_form" class="form" action="{{ route('supplier.store') }}" action-update="{{ route('supplier.update') }}">
                                @csrf

                                <input type="hidden" name="supplier_id" />
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_supplier_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_supplier_header" data-kt-scroll-wrappers="#kt_modal_add_supplier_scroll" data-kt-scroll-offset="300px">
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="supplier_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">Perusahaan</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="supplier_company" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Perusahaan" />
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
                                            <input type="email" name="supplier_email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Phone</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="supplier_phone_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="08988xxxxxx" max="16" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">
                                                Provinsi
                                                <button type="button" class="btn btn-icon btn-light-primary w-100px h-30px me-3" data-bs-stacked-modal="#kt_modal_search_region">
                                                    <i class="ki-duotone ki-geolocation fs-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    Cari
                                                </button>
                                            </label>
                                            <!--end::Label-->

                                            <div class="fw-semibold text-gray-600">
                                                <span data-kt-region="supplier_region_description"></span>
                                            </div>

                                            <input type="hidden" name="supplier_province_id" data-kt-region="supplier_province_id" />
                                            <input type="hidden" name="supplier_regency_id" data-kt-region="supplier_regency_id" />
                                            <input type="hidden" name="supplier_district_id" data-kt-region="supplier_district_id" />
                                            <input type="hidden" name="supplier_village_id" data-kt-region="supplier_village_id" />
                                        </div>
                                        <!--end::Input group-->

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fs-5 fw-bold form-label mb-2">
                                                <span class="required">Alamat</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea class="form-control form-control-solid" rows="3" name="supplier_address" placeholder="Masukkan alamat"></textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                                <!--end::Scroll-->
                                <!--begin::Actions-->
                                <div class="text-center pt-10">
                                    <button type="reset" class="btn btn-light me-3" data-kt-suppliers-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-suppliers-modal-action="submit">
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

            {{-- <!--begin::Modal - Edit task-->
            <div class="modal fade" id="kt_modal_update_supplier" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_update_supplier_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Edit Supplier</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-suppliers-modal-action="close">
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
                            <form id="kt_modal_update_supplier_form" class="form" action="{{ route('supplier.update') }}">
                                @csrf

                                <input type="hidden" name="supplier_id" />

                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_update_supplier_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_supplier_header" data-kt-scroll-wrappers="#kt_modal_update_supplier_scroll" data-kt-scroll-offset="300px">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">Name</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" name="supplier_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fw-semibold fs-6 mb-2">Perusahaan</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" name="supplier_company" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="company" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">Email</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="email" name="supplier_email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">Phone</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" name="supplier_phone_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="08988xxxxxx" max="16" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Scroll-->
                                <!--begin::Actions-->
                                <div class="text-center pt-10">
                                    <button type="reset" class="btn btn-light me-3" data-kt-suppliers-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-suppliers-modal-action="submit">
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
            <!--end::Modal - Edit task--> --}}

        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-4">
        <!--begin::Table-->
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_suppliers" data-url="{{ route('supplier.index.list') }}" data-url-delete="{{ route('supplier.delete') }}" data-url-edit="{{ route('supplier.show') }}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Perusahaan</th>
                    <th class="min-w-125px">Nama</th>
                    <th class="min-w-125px">Email</th>
                    <th class="min-w-125px">Phone</th>
                    <th class="min-w-125px">Alamat</th>
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
    'target_element' => 'supplier_',
    'prefix' => 'supplier',
])
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('assets/js/custom/apps/master/supplier/list/table.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/master/supplier/list/add.js') }}"></script>
    {{-- <script src="{{ mix('assets/js/custom/apps/master/supplier/list/update.js') }}"></script> --}}

    <script src="{{ mix('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ mix('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ mix('assets/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ mix('assets/js/custom/utilities/modals/users-search.js') }}"></script>
@endpush
