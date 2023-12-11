@extends('admin.layouts.admin')

@section('content')
<!--begin::Form-->
<form id="kt_ecommerce_add_restock_form" class="form d-flex flex-column flex-lg-row" action="{{ route('restock.store') }}" action-update="{{ route('restock.update') }}" data-kt-redirect="{{ route('restock.index') }}">
    <input type="hidden" name="restock_id" value="{{ $restock->id }}" />
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
                        <input id="kt_ecommerce_edit_order_date" name="restock_date" placeholder="Select a date" class="form-control mb-2" value="" autofocus />
                        <!--end::Editor-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Pilih tanggal dari process restock.</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Gudang</label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select mb-2" data-placeholder="Select kategori" data-allow-clear="true" name="restock_branch_id"  data-url="{{ route('ajax.branch.list') }}" data-kt-ecommerce-catalog-add-restock="restock_option">
                            <option></option>
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
                        <div id="kt_ecommerce_add_restock_description" class="min-h-200px mb-2"></div>
                        <textarea value="{!! $restock->notes !!}" name="restock_notes" style="display: none;">{!! $restock->notes !!}</textarea>
                        <!--end::Editor-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Tuliskan catatan dalam restock.</div>
                        <!--end::Description-->
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
                <!--begin::Input group-->
                <div class="" data-kt-ecommerce-catalog-add-product="auto-options">
                    <!--begin::Label-->
                    <label class="form-label">Add Product Variations</label>
                    <!--end::Label-->
                    <!--begin::Repeater-->
                    <div class="table-responsive" id="restock_details">
                        <table class="table">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Product</th>
                                    <th>Kode Produk</th>
                                    <th>Stock</th>
                                    <th>Tipe</th>
                                    <th>Kuantitas</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody data-repeater-list="restock_details" id="table-detail-body">
                                <tr data-repeater-item="" data-id="1">
                                    <td>
                                        <select class="form-select mb-2 restock_details_select_product" data-placeholder="Pilih produk" data-allow-clear="true" data-url="{{ route('ajax.product.list') }}" data-kt-ecommerce-catalog-add-restock="product_option" name="restock_details[1][product_id]">
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
                                        <select class="form-select restock_details_select_type" name="restock_details[1][type]" data-placeholder="Pilih tipe" disabled>
                                            <option value="1">Penambahan</option>
                                            <option value="2">Pengurangan</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control mw-100 w-200px restock_detail_qty" name="restock_details[1][qty]" value="1" min="1" disabled />
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
            <a href="{{ route('restock.index') }}" id="kt_ecommerce_add_restock_cancel" class="btn btn-light me-5">Cancel</a>
            <!--end::Button-->
            <!--begin::Button-->
            <button type="submit" id="kt_ecommerce_add_restock_submit" class="btn btn-primary">
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
    <script src="{{ mix('assets/js/custom/apps/transaction/restock/list/add.js') }}"></script>
@endpush
