@extends('admin.layouts.admin')

@inject('util', 'App\Utils\Util')

@section('content')
<!--begin::Form-->
<form id="kt_ecommerce_add_return_order_form" class="form d-flex flex-column flex-lg-row" action="{{ route('return_order.store', $returnOrder->order_id) }}" action-update="{{ route('return_order.update') }}" data-kt-redirect="{{ route('return_order.index') }}">
    <input type="hidden" name="return_id" value="{{ $returnOrder->id }}" />
    <input type="hidden" name="return_order_id" value="{{ $returnOrder->order_id }}" />
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
                        <input id="kt_ecommerce_edit_return_order_date" name="return_date" placeholder="Select a date" class="form-control mb-2" value="{{ $returnOrder->date }}" autofocus />
                        <!--end::Editor-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Pilih tanggal dari process retur order.</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Gudang</label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select mb-2" data-placeholder="Select kategori" data-allow-clear="true" name="return_branch_id"  data-url="{{ route('ajax.branch.list') }}" data-kt-ecommerce-catalog-add-order="return_order_option">
                            <option></option>
                            @if ($returnOrder->branch)
                                <option value="{{ $returnOrder->branch->id }}" selected>{{ $returnOrder->branch->name }}</option>
                            @endif
                        </select>
                        <!--end::Select2-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Pilih gudang dari proses re-stock.</div>
                        <!--end::Description-->
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
                        <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="return_status" data-kt-ecommerce-catalog-add-order="return_order_option">
                            @foreach (\App\Enums\ReturnOrder\StatusEnum::getLabels() as $key => $name)
                                <option value="{{ $key }}" {{ $returnOrder->status == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
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
                    <h2>Details Produk</h2>
                    <br>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="" data-kt-ecommerce-catalog-add-product="auto-options">
                    <!--end::Label-->
                    <!--begin::Repeater-->
                    <div class="table-responsive" id="return_details">
                        <table class="table">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Product</th>
                                    <th>Kode Produk</th>
                                    <th>Stock Order</th>
                                    <th>Harga</th>
                                    <th>Diskon</th>
                                    <th>Retur</th>
                                    <th class="text-sm-end">Total</th>
                                    <th class="text-sm-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="return_details" id="table-detail-body">
                                @foreach ($returnOrder->details as $detail)
                                <tr data-repeater-item="" data-id="1">
                                    <td>
                                        <span class="return_details_select_product"
                                            data-code="{{ $detail->product->code }}"
                                            data-stock="{{ $detail->product->stock }}"
                                            data-price="{{ $detail->product->price }}"
                                            data-price_zone_2="{{ $detail->product->price_zone_2 }}"
                                            data-price_zone_3="{{ $detail->product->price_zone_3 }}"
                                            data-price_zone_4="{{ $detail->product->price_zone_4 }}"
                                            data-price_zone_5="{{ $detail->product->price_zone_5 }}"
                                            data-price_zone_6="{{ $detail->product->price_zone_6 }}"
                                            data-discount="{{ $detail->product->discount }}"
                                            data-discount_zone_2="{{ $detail->product->discount_zone_2 }}"
                                            data-discount_zone_3="{{ $detail->product->discount_zone_3 }}"
                                            data-discount_zone_4="{{ $detail->product->discount_zone_4 }}"
                                            data-discount_zone_5="{{ $detail->product->discount_zone_5 }}"
                                            data-discount_zone_6="{{ $detail->product->discount_zone_6 }}"
                                            data-discount_description="{{ $detail->product->discount_description }}">{{ $detail->product->name }}</span>
                                    </td>
                                    <td>
                                        {{ $detail->product->code }}
                                    </td>
                                    <td>
                                        {{ $detail->order_detail->qty - $detail->order_detail->qty_return }}
                                    </td>
                                    <td>
                                        <input type="hidden" name="return_details[1][price]" class="return_details_price" value="{{ $detail->price }}">
                                        <span>{{ $util->format_currency($detail->price, 0, 'Rp. ') }}</span>
                                    </td>
                                    <td>
                                        <input type="hidden" name="return_details[1][discount_description]" class="return_details_discount_description" value="{{ $detail->discount_description }}">
                                        <input type="hidden" name="return_details[1][discount]" class="return_details_discount" value="{{ $detail->discount }}">
                                        <span>{{ $detail->discount ? $util->format_currency($detail->discount, 0, 'Rp. ') : 0 }}</span>
                                        <br>
                                        <span class="text-muted">{{ $detail->discount_description }}</span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control mw-100 w-200px return_detail_qty" name="return_details[1][qty]" value="{{ $detail->qty }}" min="1" max="{{ ($detail->order_detail->qty - $detail->order_detail->qty_order) }}" />
                                    </td>
                                    <td class="text-sm-end amount_detail">
                                        {{ $util->format_currency($detail->total, 0, 'Rp. ') }}
                                    </td>
                                    <td class="text-sm-center">
                                        <input type="hidden" name="return_details[1][product_id]" value="{{ $detail->product->id }}" />
                                        <input type="hidden" name="return_details[1][order_detail_id]" value="{{ $detail->order_detail_id }}" />
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
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-sm-end">
                                        <h1>Total</h1>
                                    </th>
                                    <th class="text-sm-end">
                                        <h1 id="total-amount-detail">
                                            {{ $util->format_currency($returnOrder->details->sum('total'), 0, 'Rp. ') }}
                                        </h1>
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
                                <div id="kt_ecommerce_add_return_order_notes" class="min-h-200px mb-2"></div>
                                <textarea value="{!! $returnOrder->notes !!}" name="return_notes" style="display: none;">{!! $returnOrder->notes !!}</textarea>
                                <!--end::Editor-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Tuliskan catatan dalam retur order.</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                </div>
                {{-- <div class="fv-row w-100 flex-md-root">
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
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_tax" data-kt-ecommerce-catalog-add-order="return_order_option">
                                    @foreach (\App\Enums\Preorder\TaxEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}" {{ $returnOrder->tax == $key ? 'selected' : '' }}>{{ $name }}</option>
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
                                <select class="form-select mb-2" data-placeholder="Select Status" data-allow-clear="true" name="order_discount_type" data-kt-ecommerce-catalog-add-order="return_order_option" id="form-select-discount-type">
                                    @foreach (\App\Enums\Preorder\DiscountTypeEnum::MAP_LABEL as $key => $name)
                                        <option value="{{ $key }}" {{ $returnOrder->discount_type == $key ? 'selected' : '' }}>{{ $name }}</option>
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
                                <input type="number" name="order_discount_percentage" class="form-control mb-2" placeholder="Harga Produk" value="{{ $returnOrder->discount_percentage }}" max="100" min="0" />
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
                                <input type="number" name="order_discount_price" class="form-control mb-2" placeholder="Harga Produk" value="{{ $returnOrder->discount_price }}" min="0" />
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
                </div> --}}
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <!--begin::Button-->
            <a href="{{ route('return_order.index') }}" id="kt_ecommerce_add_return_order_cancel" class="btn btn-light me-5">Cancel</a>
            <!--end::Button-->
            <!--begin::Button-->
            <button type="submit" id="kt_ecommerce_add_return_order_submit" class="btn btn-primary">
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
    <script src="{{ mix('assets/js/custom/apps/transaction/return_order/list/add.js') }}"></script>
@endpush
