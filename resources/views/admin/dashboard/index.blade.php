@extends('admin.layouts.admin')

@inject('util', 'App\Utils\Util')

@section('content')
<div class="row g-5 g-xl-10">
    {{-- START WIDGET PRODUK --}}
    <div class="col-xl-4 mb-5 sm-xl-12">
        <div class="card" id="widget-product" data-url="{{ route('dashboard.widget.product') }}">
            <div class="card-body text-center" id="widget-product-loader">
                <span class="spinner-border spinner-border-xxl align-middle ms-2"></span>
            </div>

            <div class="card-header pt-5 d-none" id="widget-product-header">
                <!--begin::Title-->
                <h4 class="card-title d-flex align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">PRODUK</span>
                    <span class="text-gray-400 mt-1 fw-bold fs-7">TOTAL <span id="widget-product-total-count">0</span> PRODUK</span>
                </h4>
                <!--end::Title-->
            </div>
            <div class="card-body d-none" id="widget-product-body">
                <!--begin::Wrapper-->
                <div class="d-flex align-items-center mb-5">
                    <!--begin::Chart-->
                    <div class="w-80px flex-shrink-0 me-2">
                        <i class="ki-duotone ki-cube-3 fs-5x">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Chart-->
                    <!--begin::Info-->
                    <div class="m-0">
                        <!--begin::Items-->
                        <div class="d-flex d-grid gap-5">
                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                <!--begin::Section-->
                                <span class="d-flex align-items-center fs-7 fw-bold text-gray-400 mb-2">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-product-count-ready">0</span>&nbsp; Ready
                                </span>
                                <!--end::Section-->
                                <!--begin::Section-->
                                <span class="d-flex align-items-center text-gray-400 fw-bold fs-7">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-product-count-empty">0</span>&nbsp; Kosong
                                </span>
                                <!--end::Section-->
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-shrink-0">
                                <!--begin::Section-->
                                <span class="d-flex align-items-center fs-7 fw-bold text-gray-400 mb-2">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-product-count-limited">0</span>&nbsp; Terbatas
                                </span>
                                <!--end::Section-->
                                <!--begin::Section-->
                                <span class="d-flex align-items-center text-gray-400 fw-bold fs-7">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-product-count-printed">0</span>&nbsp; Segera Cetak
                                </span>
                                <!--end::Section-->
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--end::Items-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Action-->
                <div class="m-0">
                    <a href="{{ route('product.index') }}" class="btn btn-sm btn-light me-2 mb-2">Detail</a>
                    <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary mb-2">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Produk
                    </a>
                </div>
                <!--end::Action-->
            </div>
        </div>
    </div>
    {{-- END WIDGET PRODUK --}}

    {{-- START WIDGET PREORDER --}}
    <div class="col-xl-4 mb-5 sm-xl-12">
        <div class="card">
            <div class="card-body text-center" id="widget-preorder-detail-loader">
                <span class="spinner-border spinner-border-xxl align-middle ms-2"></span>
            </div>
            <div class="card-header pt-5 d-none" id="widget-preorder-detail-header">
                <!--begin::Title-->
                <h4 class="card-title d-flex align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">ITEM PREORDER</span>
                    <span class="text-gray-400 mt-1 fw-bold fs-7">TOTAL <span id="widget-preorder-total-count">0</span> FAKTUR</span>
                </h4>
                <!--end::Title-->
            </div>
            <div class="card-body d-none" id="widget-preorder-detail-body">
                <!--begin::Wrapper-->
                <div class="d-flex align-items-center mb-5">
                    <!--begin::Chart-->
                    <div class="w-80px flex-shrink-0 me-2">
                        <i class="ki-duotone ki-cube-3 fs-5x">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Chart-->
                    <!--begin::Info-->
                    <div class="m-0">
                        <!--begin::Items-->
                        <div class="d-flex d-grid gap-5">
                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                <!--begin::Section-->
                                <span class="d-flex align-items-center fs-7 fw-bold text-gray-400 mb-2">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-preorder-count-dp">0</span>&nbsp; Terbayar Sebagian
                                </span>
                                <!--end::Section-->
                                <!--begin::Section-->
                                <span class="d-flex align-items-center text-gray-400 fw-bold fs-7">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-preorder-count-not_paid">0</span>&nbsp; Belum Terbayar
                                </span>
                                <!--end::Section-->
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-shrink-0">
                                <!--begin::Section-->
                                <span class="d-flex align-items-center fs-7 fw-bold text-gray-400 mb-2">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-preorder-count-paid">0</span>&nbsp; Lunas
                                </span>
                                <!--end::Section-->
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--end::Items-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Action-->
                <div class="m-0">
                    <a href="{{ route('preorder.index') }}" class="btn btn-sm btn-light me-2 mb-2">Detail</a>
                    <a href="{{ route('preorder.create') }}" class="btn btn-sm btn-primary mb-2">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Preorder
                    </a>
                </div>
                <!--end::Action-->
            </div>
        </div>
    </div>
    {{-- END WIDGET PREORDER --}}

    {{-- START WIDGET PESANAN --}}
    <div class="col-xl-4 mb-5 sm-xl-12">
        <div class="card">
            <div class="card-body text-center" id="widget-order-detail-loader">
                <span class="spinner-border spinner-border-xxl align-middle ms-2"></span>
            </div>
            <div class="card-header pt-5 d-none" id="widget-order-detail-header">
                <!--begin::Title-->
                <h4 class="card-title d-flex align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">ITEM PESANAN</span>
                    <span class="text-gray-400 mt-1 fw-bold fs-7">TOTAL <span id="widget-order-total-count">0</span> FAKTUR</span>
                </h4>
                <!--end::Title-->
            </div>
            <div class="card-body d-none" id="widget-order-detail-body">
                <!--begin::Wrapper-->
                <div class="d-flex align-items-center mb-5">
                    <!--begin::Chart-->
                    <div class="w-80px flex-shrink-0 me-2">
                        <i class="ki-duotone ki-cube-3 fs-5x">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Chart-->
                    <!--begin::Info-->
                    <div class="m-0">
                        <!--begin::Items-->
                        <div class="d-flex d-grid gap-5">
                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-shrink-0 me-4">
                                <!--begin::Section-->
                                <span class="d-flex align-items-center fs-7 fw-bold text-gray-400 mb-2">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-order-count-not_paid">0</span>&nbsp; Terbayar Sebagian
                                </span>
                                <!--end::Section-->
                                <!--begin::Section-->
                                <span class="d-flex align-items-center text-gray-400 fw-bold fs-7">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-order-count-dp">0</span>&nbsp; Belum Terbayar
                                </span>
                                <!--end::Section-->
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-shrink-0">
                                <!--begin::Section-->
                                <span class="d-flex align-items-center fs-7 fw-bold text-gray-400 mb-2">
                                    <i class="ki-duotone ki-right-square fs-6 text-gray-600 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span id="widget-order-count-paid">0</span>&nbsp; Lunas
                                </span>
                                <!--end::Section-->
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--end::Items-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Action-->
                <div class="m-0">
                    <a href="{{ route('order.index') }}" class="btn btn-sm btn-light me-2 mb-2">Detail</a>
                </div>
                <!--end::Action-->
            </div>
        </div>
    </div>
    {{-- END WIDGET PESANAN --}}
</div>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xl-4 mb-5 sm-xl-12">
        <!--begin::Card widget 4-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
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
                    <div class="min-h-auto ms-n3" id="kt_card_widget_4_chart" style="height: 125px" data-url="{{ route('dashboard.widget.preorder') }}"></div>
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

        <!--begin::Card widget 4-->
        <div class="card card-flush h-md-30 mb-5 mb-xl-10">
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
                    <div class="min-h-auto ms-n3" id="kt_card_widget_order" style="height: 125px" data-url="{{ route('dashboard.widget.order') }}"></div>
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

    <div class="col-xl-8 mb-5 sm-xl-12">
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
                <span class="spinner-border spinner-border-xxl align-middle ms-2" id="widget-zone-loader"></span>
                <!--begin::Chart-->
                <div id="kt_charts_sales_zone" class="min-h-auto ps-4 pe-6" style="height: 350px" data-url="{{ route('dashboard.widget.zone') }}"></div>
                <!--end::Chart-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart widget 8-->
    </div>
</div>

<div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xl-4 mb-5 sm-xl-12">
        <!--begin::Table widget 14-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold d-flex align-items-center">
                        Faktur Terkirim
                    </span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        10 Terbaru
                    </span>
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-6">
                <!--begin::Table container-->
                <div class="table-responsive" style="height: 415px">
                    <!--begin::Table-->
                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($orderSents as $order)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            @if (optional(optional($order->shipping)->expedition)->logo)
                                            <img src="{{ $order->shipping->expedition->logo->full_url }}" class="w-75px ms-n1 me-1" alt="" onerror="this.src='{{ mix('assets/media/logos/expedition-default.png') }}'; this.classList.add('w-75px'); this.classList.remove('w-50px')">
                                            @else
                                            <img src="{{ mix('assets/media/logos/expedition-default.png') }}" class="w-75px ms-n1 me-1" alt="">
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <a href="#" class="text-dark text-hover-primary fs-6 fw-bold">{{ str(optional(optional($order->customer)->user)->name)->title() }}</a>
                                            <span class="text-muted fw-bold">{{ $order->invoice_number }} : {{ $util->format_currency($order->total_amount) }}</span>
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

    <div class="col-xl-8 mb-5 sm-xl-12">
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
                <span class="spinner-border spinner-border-xxl align-middle ms-2" id="widget-sales-loader"></span>
                <!--begin::Chart-->
                <div id="kt_charts_sales" class="min-h-auto ps-4 pe-6" style="height: 350px" data-url="{{ route('dashboard.widget.sales') }}"></div>
                <!--end::Chart-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart widget 8-->
    </div>
</div>
@endsection

@push('js')
    <script src="{{ mix('assets/js/custom/apps/dashboard/index.js') }}"></script>
@endpush
