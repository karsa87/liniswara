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
                <input type="text" data-kt-customer-address-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Alamat Agen" />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-customer-address-table-toolbar="base">
                <!--begin::Add customer-->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer_address">
                <i class="ki-duotone ki-plus fs-2"></i>Tambah Alamat Agen</button>
                <!--end::Add customer-->
            </div>
            <!--end::Toolbar-->

            <!--begin::Modal - Add task-->
            <div class="modal fade" id="kt_modal_add_customer_address" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_customer_address_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Tambah Alamat Agen</h2>
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
                            <form id="kt_modal_add_customer_address_form" class="form" action="{{ route('customer.customer_address.store', ['customerId'=>$customerId]) }}" action-update="{{ route('customer.customer_address.update', ['customerId'=>$customerId]) }}">
                                @csrf

                                <input type="hidden" name="customer_address_id" />
                                <input type="hidden" name="customer_address_customer_id" value="{{ $customerId }}" />
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_customer_address_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_address_header" data-kt-scroll-wrappers="#kt_modal_add_customer_address_scroll" data-kt-scroll-offset="300px">
                                    <div class="d-flex flex-column flex-md-row gap-5 mb-3">
                                        <div class="fv-row flex-row-fluid">
                                            <div class="d-flex flex-stack">
                                                <div class="d-flex">
                                                    <div class="d-flex flex-column">
                                                        <a href="#" class="fs-5 text-dark text-hover-primary fw-bold">Default</a>
                                                        <div class="fs-6 fw-semibold text-gray-400">Alamat default pengiriman</div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <div class="form-check form-check-solid form-check-custom form-switch">
                                                        <input class="form-check-input w-45px h-30px" type="checkbox" id="customer_address_is_default" name="customer_address_is_default">
                                                        <label class="form-check-label" for="customer_address_is_default"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="customer_address_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Phone</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="customer_address_phone_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="08988xxxxxx" max="16" />
                                            <!--end::Input-->
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

                                            <textarea class="form-control form-control-solid" rows="3" data-kt-region="customer_address_region_description" disabled></textarea>

                                            <input type="hidden" name="customer_address_province_id" data-kt-region="customer_address_province_id" />
                                            <input type="hidden" name="customer_address_regency_id" data-kt-region="customer_address_regency_id" />
                                            <input type="hidden" name="customer_address_district_id" data-kt-region="customer_address_district_id" />
                                            <input type="hidden" name="customer_address_village_id" data-kt-region="customer_address_village_id" />
                                        </div>
                                        <!--end::Input group-->

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fs-5 fw-bold form-label mb-2">
                                                <span class="required">Alamat Detail</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea class="form-control form-control-solid" rows="3" name="customer_address_address" placeholder="Masukkan alamat"></textarea>
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

        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-4">
        <!--begin::Table-->
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_customer_addresss" data-url="{{ route('customer.customer_address.index.list', ['customerId'=>$customerId]) }}" data-url-delete="{{ route('customer.customer_address.delete', ['customerId'=>$customerId]) }}" data-url-edit="{{ route('customer.customer_address.show', ['customerId'=>$customerId]) }}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Nama</th>
                    <th class="min-w-125px">Alamat</th>
                    <th class="min-w-125px">Desa / Kelurahan</th>
                    <th class="min-w-125px">Kecamatan</th>
                    <th class="min-w-125px">Kota / Kabupaten</th>
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
    'target_element' => 'customer_address_',
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
    <script src="{{ mix('assets/js/custom/apps/master/customer_address/list/table.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/master/customer_address/list/add.js') }}"></script>

    <script src="{{ mix('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ mix('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ mix('assets/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ mix('assets/js/custom/utilities/modals/users-search.js') }}"></script>
@endpush
