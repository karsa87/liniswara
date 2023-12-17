@extends('admin.layouts.admin')

@inject('util', 'App\Utils\Util')

@section('content')
<!--begin::Form-->
<form id="kt_ecommerce_add_preorder_form" class="form d-flex flex-column flex-lg-row" action="{{ route('preorder.store') }}" action-update="{{ route('preorder.update') }}" data-kt-redirect="{{ route('preorder.index') }}">
    <input type="hidden" name="preorder_id" value="{{ $preorder->id }}" />
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
                        <input id="kt_ecommerce_edit_order_date" name="preorder_date" placeholder="Select a date" class="form-control mb-2" value="{{ $preorder->date }}" autofocus />
                        <!--end::Editor-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Pilih tanggal dari process preorder.</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Penagih</label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select mb-2" data-placeholder="Select Penagih" data-allow-clear="true" name="preorder_collector_id"  data-url="{{ route('ajax.collector.list') }}" data-kt-ecommerce-catalog-add-preorder="preorder_option">
                            <option></option>
                            @if ($preorder->collector)
                                <option value="{{ $preorder->collector->id }}" selected>{{ $preorder->collector->name }}</option>
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
                        <select class="form-select mb-2" data-placeholder="Select kategori" data-allow-clear="true" name="preorder_branch_id"  data-url="{{ route('ajax.branch.list') }}" data-kt-ecommerce-catalog-add-preorder="preorder_option">
                            <option></option>
                            @if ($preorder->branch)
                                <option value="{{ $preorder->branch->id }}" selected>{{ $preorder->branch->name }}</option>
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
                            <select class="form-select mb-2" data-placeholder="Select Alamat" data-allow-clear="true" name="preorder_marketing" data-kt-ecommerce-catalog-add-preorder="preorder_option">
                                @foreach (\App\Enums\Preorder\MarketingEnum::MAP_LABEL as $key => $name)
                                    <option value="{{ $key }}" {{ $preorder->marketing == $key ? 'selected' : '' }}>{{ $name }}</option>
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
                        <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_status" data-kt-ecommerce-catalog-add-preorder="preorder_option">
                            @foreach (\App\Enums\Preorder\StatusEnum::MAP_LABEL as $key => $name)
                                <option value="{{ $key }}" {{ $preorder->status == $key ? 'selected' : '' }}>{{ $name }}</option>
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
                        <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_status_payment" data-kt-ecommerce-catalog-add-preorder="preorder_option">
                            @foreach (\App\Enums\Preorder\StatusPaymentEnum::MAP_LABEL as $key => $name)
                                <option value="{{ $key }}" {{ $preorder->status_payment == $key ? 'selected' : '' }}>{{ $name }}</option>
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
                        <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_method_payment" data-kt-ecommerce-catalog-add-preorder="preorder_option">
                            @foreach (\App\Enums\Preorder\MethodPaymentEnum::MAP_LABEL as $key => $name)
                                <option value="{{ $key }}" {{ $preorder->method_payment == $key ? 'selected' : '' }}>{{ $name }}</option>
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
                            <select class="form-select mb-2" data-placeholder="Select Agen" data-allow-clear="true" name="preorder_customer_id"  data-url="{{ route('ajax.customer.list') }}" data-kt-ecommerce-catalog-add-preorder="preorder_option" id="form-select-customer">
                                <option></option>
                                @if ($preorder->customer)
                                    <option value="{{ $preorder->customer->id }}" selected>{{ optional($preorder->customer->user)->name }}</option>
                                @endif
                            </select>
                            <!--end::Input-->
                        </div>
                        <div class="fv-row w-100 flex-md-root">
                            <!--begin::Label-->
                            <label class="form-label">Alamat</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select mb-2" data-placeholder="Select Alamat" data-allow-clear="true" name="preorder_customer_address_id" data-kt-ecommerce-catalog-add-preorder="preorder_option" id="form-select-customer-address">
                                <option></option>
                                @if ($preorder->customer_address)
                                    <option value="{{ $preorder->customer_address->id }}" selected>{{ $preorder->customer_address->full_address }}</option>

                                    @foreach ($preorder->customer->addresses->where('id', '!=', $preorder->customer_address->id) as $address)
                                        <option value="{{ $address->id }}">{{ $address->full_address }}</option>
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
                        <select class="form-select mb-2" data-placeholder="Select Zona" data-allow-clear="true" name="preorder_zone" style="width: 200px;" id="form-select-zone">
                            @foreach (\App\Enums\Preorder\ZoneEnum::MAP_LABEL as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
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
                    <div class="table-responsive" id="preorder_details">
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
                            <tbody data-repeater-list="preorder_details" id="table-detail-body">
                                @if ($preorder->details)
                                    @foreach ($preorder->details as $detail)
                                    <tr data-repeater-item="" data-id="1">
                                        <td>
                                            <select class="form-select mb-2 preorder_details_select_product" data-placeholder="Pilih produk" data-allow-clear="true" data-url="{{ route('ajax.product.list') }}" data-kt-ecommerce-catalog-add-preorder="product_option" name="preorder_details[1][product_id]">
                                                <option></option>
                                                @if ($detail->product)
                                                    <option
                                                    value="{{ $detail->product->id }}"
                                                    data-code="{{ $detail->product->code }}"
                                                    data-stock="{{ $detail->product->stock }}"
                                                    data-price="{{ $detail->product->price }}"
                                                    data-price_zone_2="{{ $detail->product->price_zone_2 }}"
                                                    data-discount="{{ $detail->product->discount }}"
                                                    data-discount_zone_2="{{ $detail->product->discount_zone_2 }}"
                                                    data-discount_description="{{ $detail->product->discount_description }}"
                                                    selected>{{ $detail->product->name }}</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            {{ $detail->product->code }}
                                        </td>
                                        <td>
                                            {{ $detail->product->stock }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="preorder_details[1][price]" class="preorder_details_price" value="{{ $detail->price }}">
                                            <span>{{ $util->format_currency($detail->price, 0, 'Rp. ') }}</span>
                                        </td>
                                        <td>
                                            <input type="hidden" name="preorder_details[1][discount_description]" class="preorder_details_discount_description" value="{{ $detail->discount_description }}">
                                            <input type="hidden" name="preorder_details[1][discount]" class="preorder_details_discount" value="{{ $detail->discount }}">
                                            <span>{{ $detail->discount ? $util->format_currency($detail->discount, 0, 'Rp. ') : 0 }}</span>
                                            <br>
                                            <span class="text-muted">{{ $detail->discount_description }}</span>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control mw-100 w-200px preorder_detail_qty" name="preorder_details[1][qty]" value="{{ $detail->qty }}" min="1" />
                                        </td>
                                        <td>
                                            {{ $util->format_currency($detail->total, 0, 'Rp. ') }}
                                        </td>
                                        <td>
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
                                        <select class="form-select mb-2 preorder_details_select_product" data-placeholder="Pilih produk" data-allow-clear="true" data-url="{{ route('ajax.product.list') }}" data-kt-ecommerce-catalog-add-preorder="product_option" name="preorder_details[1][product_id]">
                                            <option></option>
                                        </select>
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        <input type="hidden" name="preorder_details[1][price]" class="preorder_details_price">
                                        <span>-</span>
                                    </td>
                                    <td>
                                        <input type="hidden" name="preorder_details[1][discount_description]" class="preorder_details_discount_description">
                                        <input type="hidden" name="preorder_details[1][discount]" class="preorder_details_discount">
                                        <span>-</span>
                                        <br>
                                        <span class="text-muted">-</span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control mw-100 w-200px preorder_detail_qty" name="preorder_details[1][qty]" value="1" min="1" disabled />
                                    </td>
                                    <td>
                                        0
                                    </td>
                                    <td>
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
                            <tfoot>
                                <tr>
                                    <th colspan="5">
                                        <!--begin::Form group-->
                                        <div class="form-group mt-5">
                                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary">
                                            <i class="ki-duotone ki-plus fs-2"></i>Add another variation</button>
                                        </div>
                                        <!--end::Form group-->
                                    </th>
                                </tr>
                            </tfoot>
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
                                <div id="kt_ecommerce_add_preorder_notes" class="min-h-200px mb-2"></div>
                                <textarea value="{!! $preorder->notes !!}" name="preorder_notes" style="display: none;">{!! $preorder->notes !!}</textarea>
                                <!--end::Editor-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tuliskan catatan dalam preorder.</div>
                                <!--end::Description-->
                            </div>
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="form-label">Catatan Staff</label>
                                <!--end::Label-->
                                <!--start::Editor-->
                                <div id="kt_ecommerce_add_preorder_notes_staff" class="min-h-200px mb-2"></div>
                                <textarea value="{!! $preorder->notes_staff !!}" name="preorder_notes_staff" style="display: none;">{!! $preorder->notes_staff !!}</textarea>
                                <!--end::Editor-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tuliskan catatan dalam preorder.</div>
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
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_tax" data-kt-ecommerce-catalog-add-preorder="preorder_option">
                                    @foreach (\App\Enums\Preorder\TaxEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}" {{ $preorder->tax == $key ? 'selected' : '' }}>{{ $name }}</option>
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
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="preorder_discount_type" data-kt-ecommerce-catalog-add-preorder="preorder_option" id="form-select-discount-type">
                                    @foreach (\App\Enums\Preorder\DiscountTypeEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}" {{ $preorder->discount_type == $key ? 'selected' : '' }}>{{ $name }}</option>
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
                                <input type="number" name="preorder_discount_percentage" class="form-control mb-2" placeholder="Harga Produk" value="{{ $preorder->discount_percentage }}" max="100" min="0" />
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
                                <input type="number" name="preorder_discount_price" class="form-control mb-2" placeholder="Harga Produk" value="{{ $preorder->discount_price }}" min="0" />
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
                                <input type="text" name="preorder_resi" class="form-control mb-2" placeholder="Resi" value="{{ $preorder->shipping ? $preorder->shipping->resi : '' }}" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-2">
                                <!--begin::Label-->
                                <label class="form-label">Expedition</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select mb-2" data-placeholder="Select Agen" data-allow-clear="true" name="preorder_expedition_id"  data-url="{{ route('ajax.expedition.list') }}" data-kt-ecommerce-catalog-add-preorder="preorder_option">
                                    <option></option>
                                    @if ($preorder->shipping && $preorder->shipping->expedition)
                                        <option value="{{ $preorder->shipping->expedition->id }}" selected>{{ $preorder->shipping->expedition->name }}</option>
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
                                <input type="number" name="preorder_shipping_price" class="form-control mb-2" placeholder="Ongkir" value="{{ $preorder->shipping_price }}" min="0" />
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
            <a href="{{ route('preorder.index') }}" id="kt_ecommerce_add_preorder_cancel" class="btn btn-light me-5">Cancel</a>
            <!--end::Button-->
            <!--begin::Button-->
            <button type="submit" id="kt_ecommerce_add_preorder_submit" class="btn btn-primary">
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
    <script src="{{ mix('assets/js/custom/apps/transaction/preorder/list/add.js') }}"></script>
@endpush
