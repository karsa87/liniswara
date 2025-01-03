@extends('marketing.layouts.marketing')

@inject('util', 'App\Utils\Util')

@section('content')
<!--begin::Row-->
<div class="row g-5 gx-xl-10">
    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-md-5 mb-xl-10">
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
                        <input type="text" data-kt-follow-transaction-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search user" />
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
                            <div class="px-7 py-5" data-kt-follow-transaction-table-filter="form">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-semibold">Agen</label>
                                    <select class="form-select form-select-solid fw-bold select-filter-order" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-follow-transaction-table-filter="order" name="search_customer_id" id="filter-search_customer_id" data-url="{{ route('ajax.customer.list') }}">
                                        <option value="" selected></option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-semibold">Status Pembayaran</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-follow-transaction-table-filter="order" name="search_status_payment" id="filter-search_status_payment">
                                        <option value="" selected></option>
                                        @foreach (\App\Enums\Preorder\StatusPaymentEnum::MAP_LABEL as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-semibold">Marketing</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Pilih" data-allow-clear="true" data-kt-follow-transaction-table-filter="order" name="search_marketing_id" id="filter-search_marketing_id">
                                        <option value="" selected></option>
                                        @foreach (\App\Enums\Preorder\MarketingEnum::MAP_LABEL as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-follow-transaction-table-filter="reset">Reset</button>
                                    <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-follow-transaction-table-filter="filter">Apply</button>
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
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_follow_transactions" data-rank-regency="{{ route('marketing.follow.transaction.index', 'REPLACE') }}">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">No. Faktur</th>
                            <th class="min-w-125px">Customer</th>
                            <th class="min-w-125px">Ekspedisi</th>
                            <th class="min-w-50px">Input <br> Marketing</th>
                            <th class="min-w-75px">Status Order</th>
                            <th class="min-w-75px">Method <br> Pembayaran</th>
                            <th class="min-w-100px">Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ mix('marketing/assets/js/custom/follow/list_transaction.js') }}"></script>
@endpush

@push('css-plugin')
<link href="{{ mix('marketing/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('marketing/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush
