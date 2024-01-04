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
                <div class="mb-7">
                    <!--begin::Title-->
                    <h5 class="mb-4">Tanggal</h5>
                    <!--end::Title-->
                    <!--begin::Details-->
                    <div class="mb-0">
                        <!--begin::Price-->
                        <span class="fw-semibold text-gray-600">
                            {{ $carbon->parse($restock->date)->locale('id')->format('l, j F Y H:i') }}
                        </span>
                        <!--end::Price-->
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
                    <h5 class="mb-4">Gudang</h5>
                    <!--end::Title-->
                    <!--begin::Details-->
                    <div class="mb-0">
                        <!--begin::Card expiry-->
                        <div class="fw-semibold text-gray-600">{{ optional($restock->branch)->name }}</div>
                        <!--end::Card expiry-->
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
                    <h5 class="mb-4">Catatan</h5>
                    <!--end::Title-->
                    <!--begin::Details-->
                    <div class="mb-0">
                        <!--begin::Card expiry-->
                        <div class="fw-semibold text-gray-600">{!! html_entity_decode($restock->notes) !!}</div>
                        <!--end::Card expiry-->
                    </div>
                    <!--end::Details-->
                </div>
                <!--end::Section-->

                <!--begin::Actions-->
                <div class="mb-0">
                    <a href="{{ route('restock.index') }}" class="btn btn-primary" id="kt_subscriptions_create_button">Back Index</a>
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
        <!--begin::Card-->
        <div class="card card-flush pt-3 mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="fw-bold">Produk Details</h2>
                </div>
                <!--begin::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-3">
                <!--begin::Section-->
                <div class="mb-0">
                    <!--begin::Product table-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed table-striped fs-6 gy-4 mb-0">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="border-bottom border-gray-200 text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">Kode</th>
                                    <th class="min-w-125px">Produk</th>
                                    <th class="min-w-75px">Tipe</th>
                                    <th class="min-w-50px">Kuantitas</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-800">
                                @foreach ($restock->details as $detail)
                                    <tr>
                                        <td>
                                            <label class="w-150px">{{ optional($detail->product)->code }}</label>
                                        </td>
                                        <td>
                                            <label class="w-150px">{{ optional($detail->product)->name }}</label>
                                        </td>
                                        <td>{{ \App\Enums\RestockTypeEnum::fromValue($detail->type)->getLabel() }}</td>
                                        <td>{{ $detail->qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Product table-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content-->
</div>
<!--end::Layout-->
@endsection
