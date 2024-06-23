@extends('marketing.layouts.marketing')

@inject('util', 'App\Utils\Util')

@section('content')
<!--begin::Row-->
<div class="row g-5 gx-xl-10">
    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-md-5 mb-xl-10">
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
                        <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search area" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_customers" data-url="{{ route('marketing.payment.region') }}" data-rank-agent="{{ route('marketing.payment.transaction_rank_agent', 'REPLACE') }}">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-25px"></th>
                            <th class="min-w-125px">Area</th>
                            <th class="min-w-50px">Total Transaksi</th>
                            <th class="min-w-100px">Target</th>
                            <th class="min-w-100px">Pencapaian</th>
                            <th class="min-w-100px">Tercapai</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ mix('marketing/assets/js/custom/region/table.js') }}"></script>
@endpush

@push('css-plugin')
<link href="{{ mix('marketing/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('marketing/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush
