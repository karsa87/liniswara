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
    @foreach ($preorders as $preorder)
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
                                            <a href="{{ route('marketing.transaction.detail', $preorder->id) }}" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ optional($preorder->collector)->name }}</a>
                                            <a href="{{ route('marketing.transaction.detail', $preorder->id) }}">
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
                                        <a href="{{ route('marketing.transaction.detail', $preorder->id) }}" class="btn btn-sm btn-light me-2">
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
                                                {{ optional($preorder->collector)->full_address }}
                                            </div>
                                            <!--end::Stat-->
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded  w-20 py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex justify-content-start flex-column">
                                                    <spam class="text-gray-800 fw-bold mb-1 fs-4">
                                                        {{ $carbon->parse($preorder->date)->isoFormat('dddd,') }}
                                                    </span>
                                                    <spam class="text-gray-800 fw-bold d-block mb-1 fs-4">
                                                        {{ $carbon->parse($preorder->date)->isoFormat('D MMMM Y') }}
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
                                                @if ($preorder->orders_count > 0)
                                                    <span class="badge badge-danger">{{ $preorder->orders_count }} Transaksi Belum Selesai</span>
                                                @else
                                                    <span class="badge badge-danger">Transaksi Selesai</span>
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
    @endforeach

    {{ $preorders->links() }}
@endsection

