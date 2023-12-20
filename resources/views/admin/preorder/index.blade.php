@extends('admin.layouts.admin')

@section('content')
<!--begin::Card-->
<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title"></div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-preorder-table-toolbar="base">
                <!--begin::Filter-->
                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-filter fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Filter</button>
                <!--begin::Menu 1-->
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="data-kt-menu-filter-preorder">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-5 text-dark fw-bold">Filter Options</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Separator-->
                    <div class="separator border-gray-200"></div>
                    <!--end::Separator-->
                    <!--begin::Content-->
                    <div class="px-7 py-5" data-kt-preorder-table-filter="form">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Gudang</label>
                            <select class="form-select form-select-solid fw-bold select-filter-preorder" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-preorder-table-filter="preorder" name="search_branch_id" id="filter-search_branch_id" data-url="{{ route('ajax.branch.list') }}">
                                <option value="" selected></option>
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Penagih</label>
                            <select class="form-select form-select-solid fw-bold select-filter-preorder" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-preorder-table-filter="preorder" name="search_collector_id" id="filter-search_collector_id" data-url="{{ route('ajax.collector.list') }}">
                                <option value="" selected></option>
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Agen</label>
                            <select class="form-select form-select-solid fw-bold select-filter-preorder" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-preorder-table-filter="preorder" name="search_customer_id" id="filter-search_customer_id" data-url="{{ route('ajax.customer.list') }}">
                                <option value="" selected></option>
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Marketing</label>
                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-preorder-table-filter="preorder" name="search_marketing_id" id="filter-search_marketing_id">
                                <option value="" selected></option>
                                @foreach (\App\Enums\Preorder\MarketingEnum::MAP_LABEL as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-preorder-table-filter="reset">Reset</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-preorder-table-filter="filter">Apply</button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Menu 1-->
                <!--end::Filter-->
                <!--begin::Add preorder-->
                <a href="{{ route('preorder.create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>Tambah Penyesuaian
                </a>
                <!--end::Add preorder-->
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-4">
        <!--begin::Table-->
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_preorders" data-url="{{ route('preorder.index.list') }}" data-url-delete="{{ route('preorder.delete') }}" data-url-edit="{{ route('preorder.detail') }}" >
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">No. Faktur</th>
                    <th class="min-w-125px">Customer</th>
                    <th class="min-w-125px">Total</th>
                    <th class="min-w-125px">Ekspedisi</th>
                    <th class="min-w-125px">Input <br> Marketing</th>
                    <th class="min-w-125px">Status Order</th>
                    <th class="min-w-125px">Status <br> Pembayaran</th>
                    <th class="min-w-125px">Method <br> Pembayaran</th>
                    <th class="text-end min-w-100px">Actions</ th>
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

<!--begin::Modal - Add task-->
<div class="modal fade" id="kt_modal_update_discount_preorder" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_update_discount_preorder_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Edit Diskon / Ongkir</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-preorders-modal-action="close">
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
                <form id="kt_modal_update_discount_preorder_form" class="form" action="{{ route('preorder.update_discount') }}">
                    @csrf
                    <input type="hidden" name="preorder_id" />

                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_update_discount_preorder_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_discount_preorder_header" data-kt-scroll-wrappers="#kt_modal_update_discount_preorder_scroll" data-kt-scroll-offset="300px">

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="form-label">Tipe Diskon</label>
                                <!--end::Label-->
                                <!--begin::Editor-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_discount_type" data-kt-ecommerce-catalog-add-preorder="preorder_option" id="form-select-discount-type">
                                    @foreach (\App\Enums\Preorder\DiscountTypeEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Editor-->
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2" style="display: none !important;" id="div-discount-percentage">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Diskon (%)</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="preorder_discount_percentage" class="form-control mb-2" placeholder="Harga Produk" value="" max="100" min="0" />
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tetapkan diskon (%).</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2" style="display: none !important;" id="div-discount-price">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Diskon (Rp.)</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="preorder_discount_price" class="form-control mb-2" placeholder="Harga Produk" value="" min="0" />
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tetapkan diskon (Rp.).</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Ongkir</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="preorder_shipping_price" class="form-control mb-2" placeholder="Harga Produk" value="" min="0" />
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tetapkan ongkos kirim (Rp.).</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-kt-preorders-modal-action="cancel">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-preorders-modal-action="submit">
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
<div class="modal fade" id="kt_modal_update_status_preorder" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_update_status_preorder_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Edit Diskon / Ongkir</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-preorders-modal-action="close">
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
                <form id="kt_modal_update_status_preorder_form" class="form" action="{{ route('preorder.update_status') }}">
                    @csrf
                    <input type="hidden" name="preorder_id" />

                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_update_status_preorder_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_status_preorder_header" data-kt-scroll-wrappers="#kt_modal_update_status_preorder_scroll" data-kt-scroll-offset="300px">

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Status Order</label>
                                <!--end::Label-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_status" data-kt-ecommerce-catalog-add-preorder="preorder_option" id="form-select-status-status">
                                    @foreach (\App\Enums\Preorder\StatusEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Status Terbayar</label>
                                <!--end::Label-->
                                <!--begin::Select2-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_status_payment" data-kt-ecommerce-catalog-add-preorder="preorder_option" id="form-select-status-status_payment">
                                    @foreach (\App\Enums\Preorder\StatusPaymentEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Select2-->
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Status Pembayaran</label>
                                <!--end::Label-->
                                <!--begin::Select2-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_method_payment" data-kt-ecommerce-catalog-add-preorder="preorder_option" id="form-select-status-method_payment">
                                    @foreach (\App\Enums\Preorder\MethodPaymentEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Select2-->
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="form-label">Marketing</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select mb-2" data-placeholder="Select Alamat" data-allow-clear="true" name="preorder_marketing" data-kt-ecommerce-catalog-add-preorder="preorder_option" id="form-select-status-marketing">
                                    @foreach (\App\Enums\Preorder\MarketingEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-kt-preorders-modal-action="cancel">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-preorders-modal-action="submit">
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
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('assets/js/custom/apps/transaction/preorder/list/table.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/transaction/preorder/list/update_discount.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/transaction/preorder/list/update_status.js') }}"></script>
@endpush
