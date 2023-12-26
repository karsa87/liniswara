@extends('admin.layouts.admin')

@inject('util', 'App\Utils\Util')

@section('content')
<!--begin::Form-->
<form id="kt_ecommerce_add_order_form" class="form d-flex flex-column flex-lg-row" action="{{ route('order.store', $order->preorder_id) }}" action-update="{{ route('order.update') }}" data-kt-redirect="{{ route('order.index') }}">
    <input type="hidden" name="order_id" value="{{ $order->id }}" />
    <input type="hidden" name="order_preorder_id" value="{{ $order->preorder_id }}" />
    <!--begin::Aside column-->
    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
        <!--begin::Category & tags-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>Umum</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="d-flex flex-column gap-10">
                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Tanggal</label>
                        <!--end::Label-->
                        <!--begin::Editor-->
                        <input id="kt_ecommerce_edit_order_date" name="order_date" placeholder="Select a date" class="form-control mb-2" value="{{ $order->date }}" autofocus />
                        <!--end::Editor-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Pilih tanggal dari process order.</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Penagih</label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select mb-2" data-placeholder="Select Penagih" data-allow-clear="true" name="order_collector_id"  data-url="{{ route('ajax.collector.list') }}" data-kt-ecommerce-catalog-add-order="order_option">
                            <option></option>
                            @if ($order->collector)
                                <option value="{{ $order->collector->id }}" selected>{{ $order->collector->name }}</option>
                            @endif
                        </select>
                        <!--end::Select2-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Pilih gudang dari proses re-stock.</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Gudang</label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select mb-2" data-placeholder="Select kategori" data-allow-clear="true" name="order_branch_id"  data-url="{{ route('ajax.branch.list') }}" data-kt-ecommerce-catalog-add-order="order_option">
                            <option></option>
                            @if ($order->branch)
                                <option value="{{ $order->branch->id }}" selected>{{ $order->branch->name }}</option>
                            @endif
                        </select>
                        <!--end::Select2-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Pilih gudang dari proses re-stock.</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="d-flex flex-column flex-md-row gap-5">
                        <div class="flex-row-fluid">
                            <!--begin::Label-->
                            <label class="form-label">Marketing</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select mb-2" data-placeholder="Select Alamat" data-allow-clear="true" name="order_marketing" data-kt-ecommerce-catalog-add-order="order_option">
                                @foreach (\App\Enums\Preorder\MarketingEnum::MAP_LABEL as $key => $name)
                                    <option value="{{ $key }}" {{ $order->marketing == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category & tags-->
        <!--begin::Category & tags-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>Status</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="d-flex flex-column gap-10">
                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Status Order</label>
                        <!--end::Label-->
                        <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_status" data-kt-ecommerce-catalog-add-order="order_option">
                            @foreach (\App\Enums\Preorder\StatusEnum::MAP_LABEL as $key => $name)
                                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Status Terbayar</label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_status_payment" data-kt-ecommerce-catalog-add-order="order_option">
                            @foreach (\App\Enums\Preorder\StatusPaymentEnum::MAP_LABEL as $key => $name)
                                <option value="{{ $key }}" {{ $order->status_payment == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <!--end::Select2-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Status Pembayaran</label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_method_payment" data-kt-ecommerce-catalog-add-order="order_option">
                            @foreach (\App\Enums\Preorder\MethodPaymentEnum::MAP_LABEL as $key => $name)
                                <option value="{{ $key }}" {{ $order->method_payment == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <!--end::Select2-->
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category & tags-->
    </div>
    <!--end::Aside column-->
    <!--begin::Main column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <!--begin:::Tabs-->
        <!--begin::General options-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Details</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="d-flex flex-column gap-5 gap-md-7">
                <!--begin::Title-->
                    <div class="fs-3 fw-bold mb-n2">Billing Address</div>
                    <!--end::Title-->
                    <!--begin::Input group-->
                    <div class="d-flex flex-wrap gap-5">
                        <div class="fv-row w-100 flex-md-root">
                            <!--begin::Label-->
                            <label class="required form-label">Pelanggan / Agen</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select mb-2" data-placeholder="Select Agen" data-allow-clear="true" name="order_customer_id"  data-url="{{ route('ajax.customer.list') }}" data-kt-ecommerce-catalog-add-order="order_option" id="form-select-customer">
                                <option></option>
                                @if ($order->customer)
                                    <option value="{{ $order->customer->id }}" selected>{{ optional($order->customer->user)->name }}</option>
                                @endif
                            </select>
                            <!--end::Input-->
                        </div>
                        <div class="fv-row w-100 flex-md-root">
                            <!--begin::Label-->
                            <label class="form-label">Alamat</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select mb-2" data-placeholder="Select Alamat" data-allow-clear="true" name="order_customer_address_id" data-kt-ecommerce-catalog-add-order="order_option" id="form-select-customer-address">
                                <option></option>
                                @if ($order->customer_address)
                                    <option value="{{ $order->customer_address->id }}" selected>{{ $order->customer_address->summary_address }}</option>

                                    @foreach ($order->customer->addresses->where('id', '!=', $order->customer_address->id) as $address)
                                        <option value="{{ $address->id }}">{{ $address->summary_address }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <!--end::Input-->
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Card header-->
        </div>
        <!--end::General options-->
        <!--end::Tab content-->

        <!--begin:::Tabs-->
        <!--begin::General options-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Details Produk</h2>
                    <br>
                </div>
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <select class="form-select mb-2" data-placeholder="Select Zona" data-allow-clear="true" name="order_zone" style="width: 200px;" id="form-select-zone">
                            @foreach (\App\Enums\Preorder\ZoneEnum::MAP_LABEL as $key => $name)
                                <option value="{{ $key }}" {{ $order->zone == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="" data-kt-ecommerce-catalog-add-product="auto-options">
                    <!--end::Label-->
                    <!--begin::Repeater-->
                    <div class="table-responsive" id="order_details">
                        <table class="table">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Product</th>
                                    <th>Kode Produk</th>
                                    <th>Stock</th>
                                    <th>Harga</th>
                                    <th>Diskon</th>
                                    <th>Kuantitas</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="order_details" id="table-detail-body">
                                @foreach ($order->details as $detail)
                                <tr data-repeater-item="" data-id="1">
                                    <td>
                                        <span class="order_details_select_product"
                                            data-code="{{ $detail->product->code }}"
                                            data-stock="{{ $detail->product->stock }}"
                                            data-price="{{ $detail->product->price }}"
                                            data-price_zone_2="{{ $detail->product->price_zone_2 }}"
                                            data-discount="{{ $detail->product->discount }}"
                                            data-discount_zone_2="{{ $detail->product->discount_zone_2 }}"
                                            data-discount_description="{{ $detail->product->discount_description }}">{{ $detail->product->name }}</span>
                                    </td>
                                    <td>
                                        {{ $detail->product->code }}
                                    </td>
                                    <td>
                                        {{ $detail->product->stock }}
                                    </td>
                                    <td>
                                        <input type="hidden" name="order_details[1][price]" class="order_details_price" value="{{ $detail->price }}">
                                        <span>{{ $util->format_currency($detail->price, 0, 'Rp. ') }}</span>
                                    </td>
                                    <td>
                                        <input type="hidden" name="order_details[1][discount_description]" class="order_details_discount_description" value="{{ $detail->discount_description }}">
                                        <input type="hidden" name="order_details[1][discount]" class="order_details_discount" value="{{ $detail->discount }}">
                                        <span>{{ $detail->discount ? $util->format_currency($detail->discount, 0, 'Rp. ') : 0 }}</span>
                                        <br>
                                        <span class="text-muted">{{ $detail->discount_description }}</span>
                                    </td>
                                    <td>
                                        <input type="hidden" class="form-control mw-100 w-200px order_detail_qty" name="order_details[1][qty]" value="{{ $detail->qty }}" min="1" />
                                        {{ $detail->qty }}
                                    </td>
                                    <td>
                                        {{ $util->format_currency($detail->total, 0, 'Rp. ') }}
                                    </td>
                                    <td>
                                        <input type="hidden" name="order_details[1][product_id]" value="{{ $detail->product->id }}" />
                                        <input type="hidden" name="order_details[1][preorder_detail_id]" value="{{ $detail->preorder_detail_id }}" />
                                        <button type="button" data-repeater-delete="" class="btn btn-sm btn-icon btn-light-danger">
                                            <i class="ki-duotone ki-cross fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Repeater-->
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card header-->
        </div>
        <!--end::General options-->
        <!--end::Tab content-->

        <div class="d-flex flex-column gap-5 gap-md-7">
            <!--begin::Input group-->
            <div class="d-flex flex-wrap gap-5">
                <div class="fv-row w-100 flex-md-root">
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Catatan</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="required form-label">Catatan</label>
                                <!--end::Label-->
                                <!--begin::Editor-->
                                <div id="kt_ecommerce_add_order_notes" class="min-h-200px mb-2"></div>
                                <textarea value="{!! $order->notes !!}" name="order_notes" style="display: none;">{!! $order->notes !!}</textarea>
                                <!--end::Editor-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tuliskan catatan dalam order.</div>
                                <!--end::Description-->
                            </div>
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="form-label">Catatan Staff</label>
                                <!--end::Label-->
                                <!--start::Editor-->
                                <div id="kt_ecommerce_add_order_notes_staff" class="min-h-200px mb-2"></div>
                                <textarea value="{!! $order->notes_staff !!}" name="order_notes_staff" style="display: none;">{!! $order->notes_staff !!}</textarea>
                                <!--end::Editor-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tuliskan catatan dalam order.</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                </div>
                <div class="fv-row w-100 flex-md-root">
                    <!--begin::General options-->
                    <div class="card card-flush py-4 mb-5">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Pajak & Diskon</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="form-label">Pajak penjualan</label>
                                <!--end::Label-->
                                <!--begin::Editor-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_tax" data-kt-ecommerce-catalog-add-order="order_option">
                                    @foreach (\App\Enums\Preorder\TaxEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}" {{ $order->tax == $key ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Editor-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="form-label">Tipe Diskon</label>
                                <!--end::Label-->
                                <!--begin::Editor-->
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_discount_type" data-kt-ecommerce-catalog-add-order="order_option" id="form-select-discount-type">
                                    @foreach (\App\Enums\Preorder\DiscountTypeEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}" {{ $order->discount_type == $key ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Editor-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-2" style="display: none;" id="div-discount-percentage">
                                <!--begin::Label-->
                                <label class="required form-label">Diskon (%)</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="order_discount_percentage" class="form-control mb-2" placeholder="Harga Produk" value="{{ $order->discount_percentage }}" max="100" min="0" />
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tetapkan diskon (%).</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-2" style="display: none;" id="div-discount-price">
                                <!--begin::Label-->
                                <label class="required form-label">Diskon (Rp.)</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="order_discount_price" class="form-control mb-2" placeholder="Harga Produk" value="{{ $order->discount_price }}" min="0" />
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tetapkan diskon (Rp.).</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Pengiriman</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="form-label">Resi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="order_resi" class="form-control mb-2" placeholder="Resi" value="{{ $order->shipping ? $order->shipping->resi : '' }}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="form-label">Expedition</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select mb-2" data-placeholder="Select Agen" data-allow-clear="true" name="order_expedition_id"  data-url="{{ route('ajax.expedition.list') }}" data-kt-ecommerce-catalog-add-order="order_option">
                                    <option></option>
                                    @if ($order->shipping && $order->shipping->expedition)
                                        <option value="{{ $order->shipping->expedition->id }}" selected>{{ $order->shipping->expedition->name }}</option>
                                    @endif
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="form-label">Ongkir</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="order_shipping_price" class="form-control mb-2" placeholder="Ongkir" value="{{ $order->shipping_price }}" min="0" />
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Biaya pengiriman (Rp.).</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <!--begin::Button-->
            <a href="{{ route('order.index') }}" id="kt_ecommerce_add_order_cancel" class="btn btn-light me-5">Cancel</a>
            <!--end::Button-->
            <!--begin::Button-->
            <button type="submit" id="kt_ecommerce_add_order_submit" class="btn btn-primary">
                <span class="indicator-label">Save Changes</span>
                <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
            <!--end::Button-->
        </div>
    </div>
    <!--end::Main column-->
</form>
<!--end::Form-->
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ mix('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('assets/js/custom/apps/transaction/order/list/add.js') }}"></script>
@endpush
