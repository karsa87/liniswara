@extends('admin.layouts.admin')

@section('content')
<!--begin::Card-->
<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" data-kt-collector-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Penagih" />
            </div>
            <!--end::Search-->
        </div>
        <!--begin::Card title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-collector-table-toolbar="base">
                @hasPermission('collector-tambah')
                <!--begin::Add collector-->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_collector">
                <i class="ki-duotone ki-plus fs-2"></i>Tambah Penagih</button>
                <!--end::Add collector-->
                @endhasPermission
            </div>
            <!--end::Toolbar-->

            <!--begin::Modal - Add task-->
            <div class="modal fade" id="kt_modal_add_collector" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_add_collector_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Tambah Penagih</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-collectors-modal-action="close">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body px-5 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_add_collector_form" class="form" action="{{ route('collector.store') }}" action-update="{{ route('collector.update') }}">
                                @csrf

                                <input type="hidden" name="collector_id" />
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_collector_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_collector_header" data-kt-scroll-wrappers="#kt_modal_add_collector_scroll" data-kt-scroll-offset="300px">
                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="collector_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">Perusahaan</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="collector_company" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Perusahaan" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">NPWP</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="collector_npwp" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="NPWP" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">GST Number</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="collector_gst" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="GST Number" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Email</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="email" name="collector_email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">Phone</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="collector_phone_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="08988xxxxxx" max="16" />
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fw-semibold fs-6 mb-2">
                                                Alamat
                                                <button type="button" class="btn btn-icon btn-light-primary w-100px h-30px me-3" data-bs-stacked-modal="#kt_modal_search_region">
                                                    <i class="ki-duotone ki-geolocation fs-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    Cari
                                                </button>
                                            </label>
                                            <!--end::Label-->

                                            <textarea class="form-control form-control-solid" rows="3" data-kt-region="collector_region_description" disabled></textarea>

                                            <input type="hidden" name="collector_province_id" data-kt-region="collector_province_id" />
                                            <input type="hidden" name="collector_regency_id" data-kt-region="collector_regency_id" />
                                            <input type="hidden" name="collector_district_id" data-kt-region="collector_district_id" />
                                            <input type="hidden" name="collector_village_id" data-kt-region="collector_village_id" />
                                        </div>
                                        <!--end::Input group-->

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fs-5 fw-bold form-label mb-2">
                                                <span class="required">Alamat Detail</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea class="form-control form-control-solid" rows="3" name="collector_address" placeholder="Masukkan alamat"></textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fs-5 fw-bold form-label mb-2">
                                                Footer Invoice
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea class="form-control form-control-solid" rows="3" name="collector_footer" placeholder="Masukkan alamat"></textarea>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->

                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fs-5 fw-bold form-label mb-2">
                                                Catatan Tagihan
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea class="form-control form-control-solid" rows="3" name="collector_billing_notes" placeholder="Masukkan alamat"></textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row gap-5 mb-5">
                                        <!--begin::Input group-->
                                        <div class="fv-row flex-row-fluid">
                                            <!--begin::Label-->
                                            <label class="fs-5 fw-bold form-label mb-2">
                                                <span class="required">Tanda Tangan/Cap</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Dropzone-->
                                            <div class="dropzone" id="kt_modal_add_update_collector_signin">
                                                <!--begin::Message-->
                                                <div class="dz-message needsclick">
                                                    <!--begin::Icon-->
                                                    <i class="ki-duotone ki-file-up fs-3hx text-primary">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <!--end::Icon-->
                                                    <!--begin::Info-->
                                                    <div class="ms-4">
                                                        <h3 class="dfs-3 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                                        <span class="fw-semibold fs-4 text-muted">Upload 1 file, only jpg, jpeg and png with max size 2Mb</span>
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                            </div>
                                            <!--end::Dropzone-->
                                            <input type="hidden" name="collector_signin_file_id" value="" />
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                                <!--end::Scroll-->
                                <!--begin::Actions-->
                                <div class="text-center pt-10">
                                    <button type="reset" class="btn btn-light me-3" data-kt-collectors-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-collectors-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - Add task-->

        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body py-4">
        <!--begin::Table-->
        <table class="table align-middle table-row-dashed table-striped fs-6 gy-5" id="kt_table_collectors" data-url="{{ route('collector.index.list') }}" data-url-delete="{{ route('collector.delete') }}" data-url-edit="{{ route('collector.show') }}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">Perusahaan</th>
                    <th class="min-w-125px">Nama</th>
                    <th class="min-w-125px">Email</th>
                    <th class="min-w-125px">Phone</th>
                    <th class="min-w-125px">Alamat</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
        </table>
        <!--end::Table-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->

@include('admin.components.search_region', [
    'target_element' => 'collector_',
    'prefix' => 'collector',
])
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('assets/js/custom/apps/master/collector/list/table.js') }}"></script>
    <script src="{{ mix('assets/js/custom/apps/master/collector/list/add.js') }}"></script>
@endpush
