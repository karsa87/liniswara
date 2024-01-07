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
            <div class="d-flex justify-content-end" data-kt-order-table-toolbar="base">
                <!--begin::Filter-->
                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-filter fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Filter</button>
                <!--begin::Menu 1-->
                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="data-kt-menu-filter-order">
                    <!--begin::Header-->
                    <div class="px-7 py-5">
                        <div class="fs-5 text-dark fw-bold">Filter Options</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Separator-->
                    <div class="separator border-gray-200"></div>
                    <!--end::Separator-->
                    <!--begin::Content-->
                    <div class="px-7 py-5" data-kt-order-table-filter="form">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Agen</label>
                            <select class="form-select form-select-solid fw-bold select-filter-order" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-order-table-filter="order" name="search_customer_id" id="filter-search_customer_id" data-url="{{ route('ajax.customer.list') }}">
                                <option value="" selected></option>
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Status Order</label>
                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-order-table-filter="order" name="search_status" id="filter-search_status">
                                <option value="" selected></option>
                                @foreach (\App\Enums\Order\StatusEnum::MAP_LABEL as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Status Pembayaran</label>
                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-order-table-filter="order" name="search_status_payment" id="filter-search_status_payment">
                                <option value="" selected></option>
                                @foreach (\App\Enums\Preorder\StatusPaymentEnum::MAP_LABEL as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Method Pembayaran</label>
                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-order-table-filter="order" name="search_method_payment" id="filter-search_method_payment">
                                <option value="" selected></option>
                                @foreach (\App\Enums\Preorder\MethodPaymentEnum::MAP_LABEL as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Marketing</label>
                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-order-table-filter="order" name="search_marketing_id" id="filter-search_marketing_id">
                                <option value="" selected></option>
                                @foreach (\App\Enums\Preorder\MarketingEnum::MAP_LABEL as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-order-table-filter="reset">Reset</button>
                            <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-order-table-filter="filter">Apply</button>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Menu 1-->
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-4">
        <!--begin::Table-->
        <table class="table align-middle table-row-dashed table-striped fs-6 gy-5" id="kt_table_orders" data-url="{{ route('order_sent.index.list') }}" data-url-delete="{{ route('order.delete') }}" data-url-edit="{{ route('order.detail') }}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-50px">No. Faktur</th>
                    <th class="min-w-125px">Customer</th>
                    <th class="min-w-125px">Ekspedisi</th>
                    <th class="min-w-50px">Input <br> Marketing</th>
                    <th class="min-w-75px">Status Order</th>
                    <th class="min-w-75px">Method <br> Pembayaran</th>
                    <th class="min-w-100px">Total</th>
                    <th class="text-end min-w-50px">Actions</th>
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
<div class="modal fade" id="kt_modal_update_discount_order" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_update_discount_order_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Edit Diskon / Ongkir</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-orders-modal-action="close">
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
                <form id="kt_modal_update_discount_order_form" class="form" action="{{ route('order.update_discount') }}">
                    @csrf
                    <input type="hidden" name="order_id" />

                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_update_discount_order_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_discount_order_header" data-kt-scroll-wrappers="#kt_modal_update_discount_order_scroll" data-kt-scroll-offset="300px">

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="form-label">Tipe Diskon</label>
                                <!--end::Label-->
                                <!--begin::Editor-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_discount_type" data-kt-ecommerce-catalog-add-order="order_option" id="form-select-discount-type">
                                    @foreach (\App\Enums\Preorder\DiscountTypeEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Editor-->
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2 d-none" id="div-discount-percentage">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Diskon (%)</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="order_discount_percentage" class="form-control mb-2" placeholder="Harga Produk" value="" max="100" min="0" />
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tetapkan diskon (%).</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2 d-none" id="div-discount-price">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Diskon (Rp.)</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="order_discount_price" class="form-control mb-2" placeholder="Harga Produk" value="" min="0" />
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
                                <input type="number" name="order_shipping_price" class="form-control mb-2" placeholder="Harga Produk" value="" min="0" />
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
                        <button type="reset" class="btn btn-light me-3" data-kt-orders-modal-action="cancel">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-orders-modal-action="submit">
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
<div class="modal fade" id="kt_modal_update_status_order" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_update_status_order_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Edit Diskon / Ongkir</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-orders-modal-action="close">
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
                <form id="kt_modal_update_status_order_form" class="form" action="{{ route('order.update_status') }}">
                    @csrf
                    <input type="hidden" name="order_id" />

                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_update_status_order_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_status_order_header" data-kt-scroll-wrappers="#kt_modal_update_status_order_scroll" data-kt-scroll-offset="300px">

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Status Order</label>
                                <!--end::Label-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_status" data-kt-ecommerce-catalog-update-status-order="order_option" id="form-select-status-status">
                                    @foreach (\App\Enums\Order\StatusEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-none" id="div-expedition">
                            <div class="separator mb-5 mt-5"></div>

                            <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                                <!--begin::Input group-->
                                <div class="fv-row flex-row-fluid">
                                    <!--begin::Label-->
                                    <label class="required form-label">Resi</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="order_resi" class="form-control mb-2" placeholder="Resi" value="" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                                <!--begin::Input group-->
                                <div class="fv-row flex-row-fluid">
                                    <!--begin::Label-->
                                    <label class="required form-label">Expedition</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select form-select-solid fw-bold" data-placeholder="Select option" data-allow-clear="true" data-kt-ecommerce-catalog-update-status-order="order_option" name="order_expedition_id" data-url="{{ route('ajax.expedition.list') }}" id="form-select-status-expedition">
                                        <option value="" selected></option>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>

                            <div class="separator mb-5 mt-5"></div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Status Terbayar</label>
                                <!--end::Label-->
                                <!--begin::Select2-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_status_payment" data-kt-ecommerce-catalog-update-status-order="order_option" id="form-select-status-status_payment">
                                    @foreach (\App\Enums\Preorder\StatusPaymentEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Select2-->
                            </div>
                            <!--end::Input group-->
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-5 mb-2 d-none" id="div-paid-at">
                            <!--begin::Input group-->
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Tanggal Pelunasan</label>
                                <!--end::Label-->
                                <!--begin::Select2-->
                                <input id="kt_ecommerce_edit_order_paid_at" name="order_paid_at" placeholder="Select a date" class="form-control mb-2" value="" />
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
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_method_payment" data-kt-ecommerce-catalog-update-status-order="order_option" id="form-select-status-method_payment">
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
                                <select class="form-select mb-2" data-placeholder="Select Alamat" data-allow-clear="true" name="order_marketing" data-kt-ecommerce-catalog-update-status-order="order_option" id="form-select-status-marketing">
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
                        <button type="reset" class="btn btn-light me-3" data-kt-orders-modal-action="cancel">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-orders-modal-action="submit">
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
    <script src="{{ mix('assets/js/custom/apps/transaction/order_sent/list/table.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/transaction/order_sent/list/update_discount.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/transaction/order_sent/list/update_status.js') }}"></script>
@endpush
