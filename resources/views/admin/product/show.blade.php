@extends('admin.layouts.admin')

@inject('util', 'App\Utils\Util')
@inject('carbon', 'Carbon\Carbon')

@section('content')
<!--begin::Layout-->
<div class="d-flex flex-column flex-lg-row">
    <!--begin::Sidebar-->
    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
        <!--begin::Card-->
        <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="subscription-summary" data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>General</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0 fs-6">
                <!--begin::Section-->
                <div class="d-flex flex-center flex-column mb-5">
                    @if ($product->thumbnail)
                    <!--begin::Avatar-->
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <img alt="{{ $product->name }}" src="{{ $product->thumbnail->full_url }}" />
                    </div>
                    <!--end::Avatar-->
                    @endif
                    <!--begin::Name-->
                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">{{ $product->name }}</a>
                    <!--end::Name-->
                    <!--begin::Position-->
                    <div class="fs-5 fw-semibold text-muted mb-6">{{ $product->code }}</div>
                    {{-- <div class="fs-5 fw-semibold text-muted mb-6">{{ optional($product->categories)->first()->name }}</div> --}}
                    <!--end::Position-->
                </div>

                <div class="mb-7">
                    <!--begin::Title-->
                    <h5 class="mb-4">Kategori</h5>
                    <!--end::Title-->
                    <!--begin::Details-->
                    <div class="mb-0">
                        @foreach ($product->categories as $category)
                            <span class="badge badge-outline badge-primary">{{$category->full_name}}</span>
                        @endforeach
                    </div>
                    <!--end::Details-->
                </div>
                <!--end::Section-->
                <!--begin::Seperator-->
                <div class="separator separator-dashed mb-7"></div>
                <!--end::Seperator-->
                <!--begin::Section-->
                <div class="mb-10">
                    <!--begin::Title-->
                    <h5 class="mb-4">Status</h5>
                    <!--end::Title-->
                    <!--begin::Details-->
                    <div class="mb-0">
                        @if ($product->is_best_seller)
                            <span class="badge badge-outline badge-primary">Best Seller</span>
                        @endif
                        @if ($product->is_recommendation)
                            <span class="badge badge-outline badge-primary">Rekomendasi</span>
                        @endif
                        @if ($product->is_new)
                            <span class="badge badge-outline badge-primary">Baru</span>
                        @endif
                        @if ($product->is_discount_special)
                            <span class="badge badge-outline badge-primary">Diskon Istimewa</span>
                        @endif
                        @if ($product->is_active)
                            <span class="badge badge-outline badge-primary">Aktif</span>
                        @endif
                    </div>
                    <!--end::Details-->
                </div>
                <!--end::Section-->

                <!--begin::Actions-->
                <div class="mb-0">
                    <a href="{{ route('product.index') }}" class="btn btn-primary" id="kt_subscriptions_create_button">Back Index</a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Sidebar-->

    <!--begin::Content-->
    <div class="flex-lg-row-fluid ms-lg-15">
        <!--begin:::Tabs-->
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_customer_view_overview_tab">Overview</a>
            </li>
            <!--end:::Tab item-->
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_customer_view_overview_events_and_logs_tab">Logs</a>
            </li>
            <!--end:::Tab item-->
        </ul>
        <!--end:::Tabs-->
        <!--begin:::Tab content-->
        <div class="tab-content" id="myTabContent">
            <!--begin:::Tab pane-->
            <div class="tab-pane fade show active" id="kt_customer_view_overview_tab" role="tabpanel">
                {{-- <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Section-->
                        <div class="mb-10">
                            <!--begin::Details-->
                            <div class="d-flex flex-wrap py-5">
                                <!--begin::Row-->
                                <div class="flex-equal me-5">
                                    <!--begin::Details-->
                                    <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400 min-w-175px w-175px">Bill to:</td>
                                            <td class="text-gray-800 min-w-200px">
                                                <a href="../../demo14/dist/pages/apps/customers/view.html" class="text-gray-800 text-hover-primary">smith@kpmg.com</a>
                                            </td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Customer Name:</td>
                                            <td class="text-gray-800">Emma Smith</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Address:</td>
                                            <td class="text-gray-800">Floor 10, 101 Avenue of the Light Square, New York, NY, 10050.</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Phone:</td>
                                            <td class="text-gray-800">(555) 555-1234</td>
                                        </tr>
                                        <!--end::Row-->
                                    </table>
                                    <!--end::Details-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="flex-equal">
                                    <!--begin::Details-->
                                    <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400 min-w-175px w-175px">Subscribed Product:</td>
                                            <td class="text-gray-800 min-w-200px">
                                                <a href="#" class="text-gray-800 text-hover-primary">Basic Bundle</a>
                                            </td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Subscription Fees:</td>
                                            <td class="text-gray-800">$149.99 / Year</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Billing method:</td>
                                            <td class="text-gray-800">Annually</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-400">Currency:</td>
                                            <td class="text-gray-800">USD - US Dollar</td>
                                        </tr>
                                        <!--end::Row-->
                                    </table>
                                    <!--end::Details-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card--> --}}

                <!--begin::Order details-->
                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Harga</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-price-tag fs-2 me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>Harga Zona 1</div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $util->format_currency($product->price, 0, 'Rp. ') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-price-tag fs-2 me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>Harga Zona 2</div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $util->format_currency($product->price_zone_2, 0, 'Rp. ') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-percentage fs-2 me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>Diskon Persentase</div>
                                            </td>
                                            <td class="fw-bold text-end">{{ $product->discount_percentage ?: '0' }}%</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-discount fs-2 me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>Diskon Nominal
                                            </td>
                                            <td class="fw-bold text-end">{{ $util->format_currency($product->discount_price, 0, 'Rp. ') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Order details-->
                    <!--begin::Customer details-->
                    <div class="card card-flush py-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Deskripsi</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            {!! html_entity_decode($product->description) !!}
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Customer details-->
                </div>
            </div>
            <!--end:::Tab pane-->
            <!--begin:::Tab pane-->
            <div class="tab-pane fade" id="kt_customer_view_overview_events_and_logs_tab" role="tabpanel">
                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card body-->
                    <div class="card-body py-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_stock_product_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Tanggal</th>
                                    <th class="min-w-125px">Lama</th>
                                    <th class="min-w-125px">Masuk</th>
                                    <th class="min-w-125px">Keluar</th>
                                    <th class="min-w-125px">Sekarang</th>
                                    <th class="min-w-125px">User</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($product->stock_histories as $history)
                                    <tr>
                                        <td>{{ $carbon->parse($history->created_at)->locale('id')->format('l, j F Y H:i') }}</td>
                                        <td>{{ $history->stock_old }}</td>
                                        <td>{{ $history->stock_in }}</td>
                                        <td>{{ $history->stock_out }}</td>
                                        <td>{{ $history->stock_new }}</td>
                                        <td>{{ optional($history->user)->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end:::Tab pane-->
        </div>
        <!--end:::Tab content-->
    </div>
    <!--end::Content-->
</div>
<!--end::Layout-->
@endsection
