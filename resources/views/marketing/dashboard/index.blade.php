@extends('marketing.layouts.marketing')

@inject('util', 'App\Utils\Util')

@section('content')
<!--begin::Row-->
<div class="row g-5 gx-xl-10">
    <!--begin::Col-->
    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
        <!--begin::Card widget 20-->
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <!--begin::Amount-->
                    <span class="badge badge-primary">
                        @if (session(config('session.app.selected_marketing_tim')))
                        {{ str('Target' . session(config('session.app.selected_marketing_tim'))->getLabel())->upper() }}
                        @else
                        {{ str('Target')->upper() }}
                        @endif
                    </span>
                    <!--end::Amount-->
                </h3>
                <!--end::Title-->
                <!--end::Title-->
                <div class="card-toolbar">
                    <i class="ki-duotone ki-chart-simple fs-2qx text-dark">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body align-items-end pt-0">
                <div class="d-flex align-items-center">
                    <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($targetTeam) }}</span>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 20-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
        <!--begin::Card widget 20-->
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <!--begin::Amount-->
                    <span class="badge badge-warning">{{ str('Target Pencapaian')->upper() }}</span>
                    <!--end::Amount-->
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body align-items-end pt-0">
                <div class="d-flex align-items-center">
                    <span class="fs-3hx fw-bold me-6 text-dark">
                        {{ $util->format_currency(($targetAchieved / $targetTeam) * 100, 0, '') }} %

                        <i class="ki-duotone ki-black-up fs-2qx text-success"></i>
                    </span>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 20-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
        <!--begin::Card widget 20-->
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <!--begin::Amount-->
                    <span class="badge badge-success">{{ str('Tercapai')->upper() }}</span>
                    <!--end::Amount-->
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body align-items-end pt-0">
                <div class="d-flex align-items-center">
                    <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($targetAchieved) }}</span>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 20-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
        <!--begin::Card widget 20-->
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <!--begin::Amount-->
                    <span class="badge badge-danger">{{ str('Belum Tercapai')->upper() }}</span>
                    <!--end::Amount-->
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body align-items-end pt-0">
                <div class="d-flex align-items-center">
                    @php
                        $notAchieved = $targetTeam - $targetAchieved;
                        $notAchieved = $notAchieved < 0 ? 0 : $notAchieved;
                    @endphp
                    <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($notAchieved) }}</span>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 20-->
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10" style="display: none;">
    <!--begin::Col-->
    <div class="col-xxl-6">
        <!--begin::Card widget 4-->
        <div class="card card-flush h-xl-100 mb-xl-10">
            <div class="card-body text-center" id="widget-preorder-loader">
                <span class="spinner-border spinner-border-xxl align-middle ms-2"></span>
            </div>
            <!--begin::Header-->
            <div class="card-header pt-5 d-none" id="widget-preorder-header">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2" id="widget-preorder-total">
                            {{ $util->format_currency(0) }}
                        </span>
                        <!--end::Amount-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Preorder</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body pt-2 pb-4 d-flex align-items-center d-none" id="widget-preorder-body">
                <!--begin::Chart-->
                <div class="d-flex flex-center me-5 pt-2">
                    {{-- <div id="kt_card_widget_4_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div> --}}
                    <div class="min-h-auto ms-n3" id="kt_card_widget_4_chart" style="height: 100px" data-url="{{ route('marketing.dashboard.widget.preorder') }}"></div>
                </div>
                <!--end::Chart-->
                <!--begin::Labels-->
                <div class="d-flex flex-column content-justify-center w-100">
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Lunas</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end" id="widget-preorder-total-paid">0</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Belum Lunas</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end" id="widget-preorder-total-not_paid">0</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 me-3" style="background-color: #E4E6EF"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Sebagian Terbayar</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end" id="widget-preorder-total-dp">0</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                </div>
                <!--end::Labels-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 4-->
    </div>

    <div class="col-xxl-6">
        <!--begin::Card widget 4-->
        <div class="card card-flush h-xl-100 mb-xl-10">
            <div class="card-body text-center" id="widget-order-loader">
                <span class="spinner-border spinner-border-xxl align-middle ms-2"></span>
            </div>
            <!--begin::Header-->
            <div class="card-header pt-5 d-none" id="widget-order-header">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2" id="widget-order-total">
                            {{ $util->format_currency(0) }}
                        </span>
                        <!--end::Amount-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Pesanan</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body pt-2 pb-4 d-flex align-items-center d-none" id="widget-order-body">
                <!--begin::Chart-->
                <div class="d-flex flex-center me-5 pt-2">
                    {{-- <div id="kt_card_widget_4_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div> --}}
                    <div class="min-h-auto ms-n3" id="kt_card_widget_order" style="height: 100px" data-url="{{ route('marketing.dashboard.widget.order') }}"></div>
                </div>
                <!--end::Chart-->
                <!--begin::Labels-->
                <div class="d-flex flex-column content-justify-center w-100">
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Lunas</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end" id="widget-order-total-paid">0</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Belum Lunas</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end" id="widget-order-total-not_paid">0</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 me-3" style="background-color: #E4E6EF"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Sebagian Terbayar</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end" id="widget-order-total-dp">0</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                </div>
                <!--end::Labels-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 4-->
    </div>
</div>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
    <div class="col-xl-12 mb-5 sm-xl-12">
        <!--begin::Chart widget 8-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold d-flex align-items-center">
                        Grafik Penjualan
                    </span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        Tahun {{ date('Y') }}
                    </span>
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6 text-center">
                <span class="spinner-border spinner-border-xxl align-middle ms-2" id="widget-school-loader"></span>
                <!--begin::Chart-->
                <div id="kt_charts_sales_school" class="min-h-auto ps-4 pe-6" style="height: 350px" data-url="{{ route('marketing.dashboard.widget.school') }}"></div>
                <!--end::Chart-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart widget 8-->
    </div>
</div>

<!--begin::Row-->
<div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xxl-6 mb-5 mb-xl-10">
        <!--begin::Chart widget 8-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold d-flex align-items-center">
                        Grafik Penjualan
                    </span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        @if (session(config('session.app.selected_marketing_tim')))
                        {{ session(config('session.app.selected_marketing_tim'))->getLabel() }}
                        @else
                            All
                        @endif
                        {{ date('Y') }}
                    </span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                {{-- <div class="card-toolbar">
                    <!--begin::Filters-->
                    <div class="d-flex flex-stack flex-wrap gap-4">
                        <!--begin::Destination-->
                        <div class="d-flex align-items-center fw-bold">
                            <!--begin::Label-->
                            <div class="text-gray-400 fs-7 me-2">Periode</div>
                            <!--end::Label-->
                            <!--begin::Select-->
                            <select class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Pilih Periode">
                                <option value="" selected="selected">Semua</option>
                                @for ($i = 2024; 2023 <= $i; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <!--end::Select-->
                        </div>
                        <!--end::Destination-->
                    </div>
                </div> --}}
                <!--end::Toolbar-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6 text-center">
                <span class="spinner-border spinner-border-xxl align-middle ms-2" id="widget-zone-loader"></span>
                <!--begin::Chart-->
                <div id="kt_charts_sales_tim_a" class="min-h-auto ps-4 pe-6" style="height: 350px" data-url="{{ route('marketing.dashboard.widget.zone') }}"></div>
                <!--end::Chart-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart widget 8-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-xxl-6 mb-5 mb-xl-10">
        <!--begin::Chart widget 8-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold d-flex align-items-center">
                        Grafik Penjualan
                    </span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        @if (session(config('session.app.selected_marketing_tim')))
                        {{ session(config('session.app.selected_marketing_tim'))->getLabel() }}
                        @else
                            All
                        @endif
                        {{ date('Y') }}
                    </span>
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6 text-center">
                <span class="spinner-border spinner-border-xxl align-middle ms-2" id="widget-transaction-all-loader"></span>
                <!--begin::Chart-->
                <div id="kt_charts_transaction_all" class="min-h-auto ps-4 pe-6" style="height: 350px" data-url="{{ route('marketing.dashboard.widget.transaction_all') }}"></div>
                <!--end::Chart-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart widget 8-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-xxl-6 mb-5 mb-xl-10">
        <!--begin::Table widget 14-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold d-flex align-items-center">
                        Transaksi
                    </span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        5 Wilayah Terbanyak
                    </span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                {{-- <div class="card-toolbar">
                    <a href="{{ route('marketing.payment.transaction') }}" class="btn btn-sm btn-danger">
                        <i class="ki-duotone ki-eye fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Lihat Semua
                    </a>
                </div> --}}
                <!--end::Toolbar-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($rankingRegency as $regency)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="badge badge-light-success fs-base">
                                                <i class="ki-duotone ki-briefcase text-success fs-2hx">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark text-hover-primary fs-6 fw-bold">{{ str(optional($regency->area)->name ?? 'Non Area')->title() }} : {{ $util->format_currency($regency->preorders_total) }}</a>
                                            <span class="text-muted fw-bold">Transaksi : {{ $regency->preorders_count }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
                <!--end::Table-->
            </div>
            <!--end: Card Body-->
        </div>
        <!--end::Table widget 14-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-xxl-6 mb-5 mb-xl-10">
        <!--begin::Table widget 14-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold d-flex align-items-center">
                        Transaksi
                    </span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        5 Agen dengan transaksi terbanyak
                    </span>
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($rankingAgent as $agent)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="badge badge-light-success fs-base">
                                                <i class="ki-duotone ki-briefcase text-success fs-2hx">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark text-hover-primary fs-6 fw-bold">{{ str(optional($agent->user)->name ?? 'Non Area')->title() }} : {{ $util->format_currency($agent->preorders_sum_total_amount) }}</a>
                                            <span class="text-muted fw-bold">Transaksi : {{ $agent->preorders_count }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
                <!--end::Table-->
            </div>
            <!--end: Card Body-->
        </div>
        <!--end::Table widget 14-->
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<!--begin::Row-->
<div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xxl-6 mb-5 mb-xl-10">
        <!--begin::Chart widget 8-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold d-flex align-items-center">
                        Transaksi Tim A
                    </span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        5 Terbanyak
                    </span>
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6 text-center">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($rangkingByMarketingTeamA as $preorder)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="badge badge-light-success fs-base">
                                                <i class="ki-duotone ki-briefcase text-success fs-2hx">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark text-hover-primary fs-6 fw-bold">{{ str(optional($preorder->customer->user)->name)->title() }} : {{ $preorder->invoice_number }}</a>
                                            <span class="text-muted fw-bold">Total : {{ $util->format_currency($preorder->total_amount) }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
                <!--end::Table-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart widget 8-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-xxl-6 mb-5 mb-xl-10">
        <!--begin::Table widget 14-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold d-flex align-items-center">
                        Transaksi Tim B
                    </span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        5 Terbanyak
                    </span>
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($rangkingByMarketingTeamB as $preorder)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <span class="badge badge-light-success fs-base">
                                                <i class="ki-duotone ki-briefcase text-success fs-2hx">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark text-hover-primary fs-6 fw-bold">{{ str(optional($preorder->customer->user)->name)->title() }} : {{ $preorder->invoice_number }}</a>
                                            <span class="text-muted fw-bold">Total : {{ $util->format_currency($preorder->total_amount) }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
                <!--end::Table-->
            </div>
            <!--end: Card Body-->
        </div>
        <!--end::Table widget 14-->
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->
@endsection

@push('js')
    <script src="{{ mix('marketing/assets/js/custom/widgets.js') }}"></script>
@endpush
