@extends('marketing.layouts.marketing')

@inject('util', 'App\Utils\Util')
@inject('carbon', 'Carbon\Carbon')

@section('content')
    <!--begin::Row-->
    <div class="row g-5 gx-xl-10">
        <!--begin::Col-->
        <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
            <!--begin::Card widget 20-->
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <!--begin::Amount-->
                        <span class="badge badge-primary">Lunas</span>
                        <!--end::Amount-->
                    </h3>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body align-items-end pt-0">
                    <div class="d-flex align-items-center">
                        <span class="fs-3hx fw-bold me-6 text-dark">
                            {{ $count['paid'] }}
                            <i class="ki-duotone ki-directbox-default fs-2qx text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-1hx me-6 text-muted">Transaksi</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($total['paid']) }}</span>
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
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <!--begin::Amount-->
                        <span class="badge badge-warning">Proses</span>
                        <!--end::Amount-->
                    </h3>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body align-items-end pt-0">
                    <div class="d-flex align-items-center">
                        <span class="fs-3hx fw-bold me-6 text-dark">
                            {{ $count['process'] }}

                            <i class="ki-duotone ki-arrows-circle fs-2qx text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-1hx me-6 text-muted">Transaksi</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($total['process']) }}</span>
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
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <!--begin::Amount-->
                        <span class="badge badge-danger">Belum Terbayar</span>
                        <!--end::Amount-->
                    </h3>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body align-items-end pt-0">
                    <div class="d-flex align-items-center">
                        <span class="fs-3hx fw-bold me-6 text-dark">
                            {{ $count['not_paid'] }}

                            <i class="ki-duotone ki-information fs-2qx text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-1hx me-6 text-muted">Transaksi</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($total['not_paid']) }}</span>
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
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <!--begin::Amount-->
                        <span class="badge badge-secondary">Sebagian Terbayar</span>
                        <!--end::Amount-->
                    </h3>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body align-items-end pt-0">
                    <div class="d-flex align-items-center">
                        <span class="fs-3hx fw-bold me-6 text-dark">
                            {{ $count['dp'] }}

                            <i class="ki-duotone ki-wallet fs-2qx text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-1hx me-6 text-muted">Transaksi</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($total['dp']) }}</span>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card widget 20-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10">
        <!--begin::Col-->
        <div class="col-xxl-6 mb-5 mb-xl-10">
            <!--begin::List widget 5-->
            <div class="card card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800 d-flex align-items-center">
                            <i class="ki-duotone ki-book fs-2hx">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                            Rengking Agen (10 Besar)
                        </span>
                    </h3>
                    <!--end::Title-->
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <a href="{{ route('marketing.payment.agent') }}" class="btn btn-sm btn-danger">
                            <i class="ki-duotone ki-eye fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Tampilkan Semua Agen
                        </a>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Scroll-->
                    <div class="hover-scroll-overlay-y pe-6 me-n6" style="height: 415px">
                        @foreach ($rankingAgents as $agent)
                        <!--begin::Item-->
                        <div class="border border-dashed border-gray-300 rounded px-7 py-3 mb-6 bg-hover-light-primary">
                            <a href="{{ route('marketing.payment.detail_agent', $agent->id) }}">
                            <!--begin::Info-->
                            <div class="d-flex flex-stack mb-3">
                                <!--begin::Wrapper-->
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-circle symbol-40px me-3">
                                        @if (optional($agent->user)->profile_photo)
                                            <img src="{{ optional($agent->user)->profile_photo->full_url }}" alt="image" />
                                        @else
                                            <i class="ki-duotone ki-user-square fs-5x">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-start flex-column">
                                        <spam class="text-gray-800 fw-bold mb-1 fs-4">{{ optional($agent->user)->name }}</span>
                                        <span class="text-gray-400 fw-semibold d-block fs-7">Transaksi : {{ $agent->preorders_count }}</span>
                                        <span class="text-gray-700 fw-semibold d-block fs-6">Nilai : {{ $util->format_currency($agent->preorders_sum_total_amount) }}</span>
                                    </div>
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Info-->
                            </a>
                        </div>
                        <!--end::Item-->
                        @endforeach
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::List widget 5-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-6 mb-5 mb-xl-10">
            <!--begin::Table widget 14-->
            <div class="card card-flush h-md-100">
                <!--begin::Header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800 d-flex align-items-center">
                            <i class="ki-duotone ki-graph-up fs-2hx">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                            PO Kabupaten / Kota
                        </span>
                    </h3>
                    <!--end::Title-->
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Filters-->
                        <div class="d-flex flex-stack flex-wrap gap-4">
                            <!--begin::Destination-->
                            <div class="d-flex align-items-center fw-bold">
                                <!--begin::Label-->
                                <div class="text-gray-400 fs-7 me-2">Bulan</div>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Pilih Bulan" id="filter-month">
                                    <option value="" selected="selected">Semua</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('regency_month_id') == $i ? 'selected' : '' }}>
                                            {{ $carbon->createFromDate(null, $i)->locale('id')->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                <!--end::Select-->
                            </div>
                            <!--end::Destination-->
                        </div>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-6">
                    <!--begin::Table container-->
                    <div class="hover-scroll-overlay-y pe-6 me-n6" style="height: 415px">
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
                                                <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-1">{{ str($regency->name)->title() }} : {{ $util->format_currency($regency->preorders_sum_total_amount) }}</a>
                                                <span class="text-gray-400 fw-semibold d-block fs-3">Transaksi : {{ $regency->preorders_count }}</span>
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
    <script src="{{ mix('marketing/assets/js/custom/pages/payment/transaction.js') }}"></script>
@endpush
