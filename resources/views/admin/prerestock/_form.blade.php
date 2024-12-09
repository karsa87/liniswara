@extends('admin.layouts.admin')

@section('content')
<!--begin::Form-->
<form id="kt_ecommerce_add_prerestock_form" class="form d-flex flex-column flex-lg-row" action="{{ route('prerestock.store') }}" action-update="{{ route('prerestock.update') }}" data-kt-redirect="{{ route('prerestock.index') }}">
    <input type="hidden" name="prerestock_id" value="{{ $prerestock->id }}" />
    <!--begin::Aside column-->
    {{-- <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
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
                        <label class="required form-label">Gudang</label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select mb-2" data-placeholder="Select kategori" data-allow-clear="true" name="prerestock_branch_id"  data-url="{{ route('ajax.branch.list') }}" data-kt-ecommerce-catalog-add-prerestock="prerestock_option">
                            <option></option>
                            @if ($prerestock->branch)
                                <option value="{{ $prerestock->branch->id }}" selected>{{ $prerestock->branch->name }}</option>
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
                        <label class="required form-label">Catatan</label>
                        <!--end::Label-->
                        <div id="kt_ecommerce_add_prerestock_description" class="min-h-200px mb-2"></div>
                        <textarea value="{!! $prerestock->notes !!}" name="prerestock_notes" style="display: none;">{!! $prerestock->notes !!}</textarea>
                        <!--end::Editor-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Tuliskan catatan dalam prerestock.</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category & tags-->
    </div> --}}
    <!--end::Aside column-->
    <!--begin::Main column-->
    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
        <!--begin:::Tabs-->
        <!--begin::General options-->
        <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Produk</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="mb-10 fv-row">
                    <!--begin::Label-->
                    <label class="required form-label">Label</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" name="prerestock_label" class="form-control mb-2" placeholder="Label" value="{{ $prerestock->label }}" />
                    <!--end::Input-->
                    <!--begin::Description-->
                    {{-- <div class="text-muted fs-7">Kode produk wajib diisi dan disarankan agar unik.</div> --}}
                    <!--end::Description-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="" data-kt-ecommerce-catalog-add-product="auto-options">
                    <!--begin::Label-->
                    <label class="form-label">Add Product Variations</label>
                    <!--end::Label-->
                    <!--begin::Repeater-->
                    <div class="table-responsive" id="prerestock_details">
                        <table class="table">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th rowspan="2">Product</th>
                                    <th rowspan="2">Kode Produk</th>
                                    <th colspan="2" align="center">Stock</th>
                                    <th rowspan="2">Kuantitas</th>
                                    <th rowspan="2">Actions</th>
                                </tr>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th align="center">Sisa</th>
                                    <th align="center">Dibutuhkan</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="prerestock_details" id="table-detail-body">
                                @if ($prerestock->details->count() > 0)
                                    @foreach ($prerestock->details as $detail)
                                        <tr data-repeater-item="" data-id="1">
                                            <td>
                                                <select class="form-select mb-2 prerestock_details_select_product" data-placeholder="Pilih produk" data-allow-clear="true" data-url="{{ route('ajax.product.list') }}" data-kt-ecommerce-catalog-add-prerestock="product_option" name="prerestock_details[1][product_id]">
                                                    <option value="{{ $detail->product->id }}" selected>{{ $detail->product->name }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $detail->product->code }}
                                            </td>
                                            <td>
                                                {{ $detail->product->stock }}
                                            </td>
                                            <td>
                                                {{ $detail->product->total_stock_need ?? 0 }}
                                            </td>
                                            {{-- <td>
                                                <select class="form-select prerestock_details_select_type" name="prerestock_details[1][type]" data-placeholder="Pilih tipe">
                                                    <option value="1" {{ $detail->type == 1 ? 'selected' : '' }}>Penambahan</option>
                                                    <option value="2" {{ $detail->type == 2 ? 'selected' : '' }}>Pengurangan</option>
                                                </select>
                                            </td> --}}
                                            <td>
                                                <input type="number" class="form-control mw-100 w-200px prerestock_detail_qty" name="prerestock_details[1][qty]" value="{{ $detail->qty }}" min="1" />
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
                                            <select class="form-select mb-2 prerestock_details_select_product" data-placeholder="Pilih produk" data-allow-clear="true" data-url="{{ route('ajax.product.list') }}" data-kt-ecommerce-catalog-add-prerestock="product_option" name="prerestock_details[1][product_id]">
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
                                            -
                                        </td>
                                        {{-- <td>
                                            <select class="form-select prerestock_details_select_type" name="prerestock_details[1][type]" data-placeholder="Pilih tipe" disabled>
                                                <option value="1">Penambahan</option>
                                                <option value="2">Pengurangan</option>
                                            </select>
                                        </td> --}}
                                        <td>
                                            <input type="number" class="form-control mw-100 w-200px prerestock_detail_qty" name="prerestock_details[1][qty]" value="1" min="1" disabled />
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
        <div class="d-flex justify-content-end">
            <!--begin::Button-->
            <a href="{{ route('prerestock.index') }}" id="kt_ecommerce_add_prerestock_cancel" class="btn btn-light me-5">Cancel</a>
            <!--end::Button-->
            <!--begin::Button-->
            <button type="submit" id="kt_ecommerce_add_prerestock_submit" class="btn btn-primary">
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
    <script src="{{ mix('assets/js/custom/apps/transaction/prerestock/list/add.js') }}"></script>
@endpush
