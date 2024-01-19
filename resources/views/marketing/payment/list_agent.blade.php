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
                        <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search user" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                {{-- <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
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
                            <div class="px-7 py-5" data-kt-customer-table-filter="form">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-semibold">Role:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-customer-table-filter="role" data-hide-search="true">
                                        <option></option>
                                        <option value="Administrator">Administrator</option>
                                        <option value="Analyst">Analyst</option>
                                        <option value="Developer">Developer</option>
                                        <option value="Support">Support</option>
                                        <option value="Trial">Trial</option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-semibold">Two Step Verification:</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-customer-table-filter="two-step" data-hide-search="true">
                                        <option></option>
                                        <option value="Enabled">Enabled</option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Reset</button>
                                    <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-customer-table-filter="filter">Apply</button>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Menu 1-->
                        <!--end::Filter-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card toolbar--> --}}
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_customers">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px" data-show="{{ route('marketing.payment.detail_agent', 99999999999) }}">Nama</th>
                            <th class="min-w-125px">Email</th>
                            <th class="min-w-125px">Phone</th>
                            <th class="min-w-125px">Target</th>
                            <th class="min-w-125px">Tercapai</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>


        {{-- <div class="card">
            <div class="card-body pt-9 pb-5">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <!--begin: Pic-->
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            @if (optional($agent->user)->profile_photo)
                                <img src="{{ optional($agent->user)->profile_photo->full_url }}" alt="{{ optional($agent->user)->name }}" />
                            @else
                                <img src="{{ mix('marketing/assets/media/avatars/blank.png') }}" alt="{{ optional($agent->user)->name }}" />
                            @endif
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a href="{{ route('marketing.payment.detail_agent', $agent->id) }}" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ optional($agent->user)->name }}</a>
                                    <a href="{{ route('marketing.payment.detail_agent', $agent->id) }}">
                                        <i class="ki-duotone ki-verify fs-1 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                </div>
                                <!--end::Name-->
                            </div>
                            <!--end::User-->

                            <div class="d-flex my-4">
                                <a href="{{ route('marketing.payment.detail_agent', $agent->id) }}" class="btn btn-sm btn-light me-2">
                                    <i class="ki-duotone ki-eye fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Lihat Detail</span>
                                    <!--end::Indicator label-->
                                </a>
                            </div>
                        </div>
                        <!--end::Title-->
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap">
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded w-25 py-3 px-4 me-6 mb-3">
                                        {{ optional($agent->address)->full_address }}
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded  w-20 py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-whatsapp fs-3 text-success me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            {{ optional($agent->user)->phone_number }}
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded  w-25 py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-sms fs-3 me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            {{ optional($agent->user)->email }}
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded  w-25 py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-40px me-3">
                                                <i class="ki-duotone ki-wallet fs-3x">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <spam class="text-gray-800 fw-bold mb-1 fs-4">Total Penjualan</span>
                                                <span class="text-gray-700 fw-semibold d-block fs-6">{{ $util->format_currency($agent->preorders_sum_total_amount) }}</span>
                                            </div>
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
            </div>
        </div>
        <!--end::Navbar--> --}}
    </div>
</div>
@endsection

@push('js')
    <script src="{{ mix('marketing/assets/js/custom/agent/table.js') }}"></script>
@endpush

@push('css-plugin')
<link href="{{ mix('marketing/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('marketing/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush
