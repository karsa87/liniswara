@extends('marketing.layouts.marketing')

@inject('util', 'App\Utils\Util')

@section('content')
<form id="kt_ecommerce_stock_form" class="form" action="{{ route('marketing.stock.store') }}" data-kt-redirect="{{ route('marketing.stock.index') }}">
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
                        <span class="badge badge-primary">Buku Tersedia</span>
                        <!--end::Amount-->
                    </h3>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body align-items-end pt-0">
                    <div class="d-flex align-items-center">
                        <span class="fs-3hx fw-bold me-6 text-dark">
                            {{ $count['ready'] }}
                            <i class="ki-duotone ki-directbox-default fs-2qx text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-1hx me-6 text-muted">Judul Buku</span>
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
                        <span class="badge badge-warning">Buku Limit Stok</span>
                        <!--end::Amount-->
                    </h3>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body align-items-end pt-0">
                    <div class="d-flex align-items-center">
                        <span class="fs-3hx fw-bold me-6 text-dark">
                            {{ $count['limit'] }}

                            <i class="ki-duotone ki-arrows-circle fs-2qx text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-1hx me-6 text-muted">Judul Buku</span>
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
                        <span class="badge badge-danger">Buku Habis</span>
                        <!--end::Amount-->
                    </h3>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body align-items-end pt-0">
                    <div class="d-flex align-items-center">
                        <span class="fs-3hx fw-bold me-6 text-dark">
                            {{ $count['empty'] }}

                            <i class="ki-duotone ki-information fs-2qx text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-1hx me-6 text-muted">Judul Buku</span>
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
                        <span class="badge badge-secondary">Buku Preorder</span>
                        <!--end::Amount-->
                    </h3>
                    <!--end::Title-->
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body align-items-end pt-0">
                    <div class="d-flex align-items-center">
                        <span class="fs-3hx fw-bold me-6 text-dark">
                            {{ $count['preorder'] }}

                            <i class="ki-duotone ki-wallet fs-2qx text-gray-500">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fs-1hx me-6 text-muted">Judul Buku</span>
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
                            <a href="{{ route('marketing.stock.clear') }}" class="btn btn-danger">
                                <i class="ki-duotone ki-trash fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>Clear
                            </a>
                            <a href="javascript:void(0)" class="btn btn-primary" id="kt_ecommerce_stock_submit_excel">
                                <i class="ki-duotone ki-tablet-text-up fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>Export Excel
                            </a>
                            <!--begin::Destination-->
                            <div class="d-flex align-items-center fw-bold">
                                <!--begin::Label-->
                                <div class="text-gray-400 fs-7 me-2">Zona</div>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select class="form-select form-select-transparent text-graY-800 fs-base lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Pilih Zona" id="form-select-zone" name="zone">
                                    @foreach (\App\Enums\Preorder\ZoneEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}" {{ optional($cache)['zone'] == $key ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
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
                    <div class="hover-scroll-overlay-y pe-6 me-n6 table-responsive" style="height: 415px">
                        <!--begin::Table-->
                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0" id="product_details">
                            <thead>
                                <tr>
                                    <th>Judul Buku</th>
                                    <th>Kode Buku</th>
                                    <th>Jumlah</th>
                                    <th class="text-sm-center">Stock Buku</th>
                                    <th class="text-sm-end">Satuan</th>
                                    <th class="text-sm-end">Total</th>
                                    <th>Estimasi Ready</th>
                                    <th class="text-sm-center">Actions</th>
                                </tr>
                            </thead>
                            <!--begin::Table body-->
                            <tbody data-repeater-list="product_details" id="table-detail-body">
                                @if ($cache && isset($cache['details']) && $cache['details']->count() > 0)
                                    @foreach ($cache['details'] as $detail)
                                    <tr data-repeater-item="" data-id="1">
                                        <td>
                                            <select class="form-select mb-2 stock_details_select_product" data-placeholder="Pilih produk" data-allow-clear="true" data-url="{{ route('ajax.product.list') }}" data-kt-ecommerce-catalog-add-product="product_option" name="stock_details[1][product_id]">
                                                <option></option>
                                                @if ($detail['product'])
                                                    <option
                                                    value="{{ $detail['product']->id }}"
                                                    data-code="{{ $detail['product']->code }}"
                                                    data-stock="{{ $detail['product']->stock }}"
                                                    data-price="{{ $detail['product']->price }}"
                                                    data-price_zone_2="{{ $detail['product']->price_zone_2 }}"
                                                    data-discount="{{ $detail['product']->discount }}"
                                                    data-discount_zone_2="{{ $detail['product']->discount_zone_2 }}"
                                                    data-discount_description="{{ $detail['product']->discount_description }}"
                                                    selected>{{ $detail['product']->name }}</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>{{ $detail['code'] }}</td>
                                        <td>
                                            <input type="number" class="form-control mw-100 w-200px stock_detail_qty" name="stock_details[1][qty]" min="1" value="{{ $detail['qty'] }}" />
                                        </td>
                                        <td class="text-sm-center">
                                            <span class="badge badge-light-success fs-base">{{ $detail['stock'] }}</span>
                                        </td>
                                        <td class="text-sm-end">
                                            <input type="hidden" name="stock_details[1][price]" class="stock_details_price">
                                            <span>{{ $util->format_currency($detail['price'], 0, 'Rp. ') }}</span>
                                        </td>
                                        <td class="text-sm-end amount_detail">
                                            {{ $util->format_currency($detail['total'], 0, 'Rp. ') }}
                                        </td>
                                        <td>
                                            <input name="stock_details[1][estimation_date]" placeholder="Select a date" class="form-control mb-2 kt_ecommerce_stock_estimation_date" value="{{ $detail['estimation_date'] }}" />
                                        </td>
                                        <td class="text-sm-center">
                                            <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr data-repeater-item="" data-id="1">
                                    <td>
                                        <select class="form-select mb-2 stock_details_select_product" data-placeholder="Pilih produk" data-allow-clear="true" data-url="{{ route('ajax.product.list') }}" data-kt-ecommerce-catalog-add-product="product_stock_option" name="stock_details[1][product_id]">
                                            <option></option>
                                        </select>
                                    </td>
                                    <td>-</td>
                                    <td>
                                        <input type="number" class="form-control mw-100 w-200px stock_detail_qty" name="stock_details[1][qty]" min="1" value="1" disabled />
                                    </td>
                                    <td class="text-sm-center">
                                        <span class="badge badge-light-success fs-base">-</span>
                                    </td>
                                    <td class="text-sm-end">
                                        <input type="hidden" name="stock_details[1][price]" class="stock_details_price">
                                        <span>-</span>
                                    </td>
                                    <td class="text-sm-end amount_detail">
                                        -
                                    </td>
                                    <td>
                                        <input name="stock_details[1][estimation_date]" placeholder="Select a date" class="form-control mb-2 kt_ecommerce_stock_estimation_date" disabled />
                                    </td>
                                    <td class="text-sm-center">
                                        <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger">
                                            <i class="ki-duotone ki-cross fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <!--end::Table body-->
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-sm-end">
                                        <h1>Total</h1>
                                    </th>
                                    <th class="text-sm-end">
                                        <h1 id="total-amount-detail">
                                            {{ $util->format_currency(0, 0, 'Rp. ') }}
                                        </h1>
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="8">
                                        <!--begin::Form group-->
                                        <div class="form-group mt-5">
                                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary">
                                            <i class="ki-duotone ki-plus fs-2"></i>Add Produk</button>
                                        </div>
                                        <!--end::Form group-->
                                    </th>
                                </tr>
                            </tfoot>
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
</form>
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ mix('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('marketing/assets/js/custom/pages/stock/table.js') }}"></script>
@endpush
