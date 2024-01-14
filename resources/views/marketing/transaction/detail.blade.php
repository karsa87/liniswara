@extends('marketing.layouts.marketing')

@inject('util', 'App\Utils\Util')
@inject('carbon', 'Carbon\Carbon')

@section('content')
<div class="row g-5 gx-xl-10">
    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-md-5 mb-xl-10">
        <div class="card">
            <div class="card-body pt-9 pb-5">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <!--begin: Pic-->
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ mix('marketing/assets/media/avatars/blank.png') }}" alt="image" />
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
                                    <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ optional($preorder->collector)->name }}</a>
                                    <a href="#">
                                        <i class="ki-duotone ki-verify fs-1 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                </div>
                                <!--end::Name-->
                            </div>
                            <!--end::User-->
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
                                        {{ optional($preorder->collector)->full_address }}
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded  w-20 py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex justify-content-start flex-column">
                                            <spam class="text-gray-800 fw-bold mb-1 fs-4">
                                                {{ $carbon->parse($preorder->date)->locale('id')->format('l,') }}
                                            </span>
                                            <spam class="text-gray-800 fw-bold d-block mb-1 fs-4">
                                                {{ $carbon->parse($preorder->date)->locale('id')->format('j F Y') }}
                                            </span>
                                            <span class="text-gray-700 fw-semibold d-block fs-6">{{ $preorder->invoice_number }}</span>
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded  w-25 py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex justify-content-start flex-column">
                                            <spam class="text-gray-800 fw-bold mb-1 fs-4">Total Transaksi</span>
                                            <span class="text-gray-700 fw-semibold d-block fs-6">{{ $util->format_currency($preorder->total_amount) }}</span>
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded  w-25 py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        @if ($preorder->count_order_done > 0)
                                            <span class="badge badge-success mb-2">{{ $preorder->count_order_done }} Transaksi Selesai</span>
                                        @endif
                                        @if ($preorder->count_order_not_done > 0)
                                            <span class="badge badge-danger">{{ $preorder->count_order_not_done }} Transaksi Belum Selesai</span>
                                        @endif
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
        <!--end::Navbar-->
    </div>
</div>
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
                            {{ $preorder->count_order_paid }}
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
                        <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($preorder->total_order_paid) }}</span>
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
                            {{ $preorder->count_order_not_done }}

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
                        <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($preorder->total_order_not_done) }}</span>
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
                            {{ $preorder->count_order_not_paid }}

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
                        <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($preorder->total_order_not_paid) }}</span>
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
                            {{ $preorder->count_order_dp }}

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
                        <span class="fs-2x fw-bold me-6 text-dark">{{ $util->format_currency($preorder->total_order_dp) }}</span>
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
        <div class="col-xl-12 mb-5 mb-xl-10">
            <!--begin::Table widget 14-->
            <div class="card card-flush h-md-100">
                <!--begin::Header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800 d-flex align-items-center">
                            <i class="ki-duotone ki-tablet-text-down fs-2hx">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                            Daftar Transaksi
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
                                <select class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Pilih Bulan" id="filter-month">
                                    <option value="" selected="selected">Semua</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">
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
                    <div class="hover-scroll-overlay-y pe-6 me-n6">
                        <!--begin::Table-->
                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0" id="kt_transaction_table" data-url="{{ route('marketing.transaction.detail', $preorder->id) }}">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No Faktur</th>
                                    <th>Status Order</th>
                                    <th>Status Terbayar</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <!--begin::Table body-->
                            <tbody>
                                {{-- @for ($i = 1; $i < 10; $i++)
                                <tr>
                                    <td>27 Oktober 2023</td>
                                    <td>INV-271023-0023</td>
                                    <td><span class="badge badge-light-success fs-base">Seleasi</span></td>
                                    <td><span class="badge badge-light-success fs-base">Lunas</span></td>
                                    <td>{{ $util->format_currency(mt_rand(10000000, 100000000)) }}</td>
                                </tr>
                                <tr>
                                    <td>27 Oktober 2023</td>
                                    <td>INV-271023-0023</td>
                                    <td><span class="badge badge-light-success fs-base">Seleasi</span></td>
                                    <td><span class="badge badge-light-danger fs-base">Belum Lunas</span></td>
                                    <td>{{ $util->format_currency(mt_rand(10000000, 100000000)) }}</td>
                                </tr>
                                <tr>
                                    <td>27 Oktober 2023</td>
                                    <td>INV-271023-0023</td>
                                    <td><span class="badge badge-light-success fs-base">Seleasi</span></td>
                                    <td><span class="badge badge-light-warning fs-base">Sebagian Terbayar</span></td>
                                    <td>{{ $util->format_currency(mt_rand(10000000, 100000000)) }}</td>
                                </tr>
                                @endfor --}}
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


@push('css-plugin')
<link href="{{ mix('marketing/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('marketing/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('marketing/assets/js/custom/pages/transaction/detail.js') }}"></script>
@endpush
