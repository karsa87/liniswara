@extends('admin.layouts.admin')

@inject('carbon', '\Carbon\Carbon')
@inject('util', 'App\Utils\Util')

@section('content')
<!-- begin::Invoice 3-->
<div class="card">
    <!-- begin::Body-->
    <div class="card-body py-20">
        <!-- begin::Wrapper-->
        <div class="mw-lg-950px mx-auto w-100">
            <!-- begin::Header-->
            <div class="d-flex justify-content-between flex-column flex-sm-row border p-5">
                <span class="d-block ms-sm-auto w-75">
                    <img alt="Liniswara" src="{{ mix('assets/media/logos/logo-liniswara.png') }}" class="mw-25">
                </span>
                <!--end::Logo-->
                <div class="text-sm-end w-50">
                    <!--begin::Text-->
                    <span class="d-block ms-sm-auto">
                        @if (
                            $order->shipping
                            && $order->shipping->expedition
                            && $order->shipping->expedition->logo
                            && $order->shipping->expedition->logo->full_url
                        )
                            <img alt="{{ $order->shipping->expedition->name }}" src="{{ $order->shipping->expedition->logo->full_url }}" class="w-50">
                        @else
                            <img alt="Ekspedisi" src="{{ mix('assets/media/logos/expedition-default.png') }}" class="w-25">
                        @endif
                    </span>
                    <!--end::Text-->
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="pb-12">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column p-5 border">
                    <!--begin::Order details-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        <div class="flex-root d-flex flex-column text-center">
                            <span class="fs-2">No Faktur : {{ $order->invoice_number }}</span>
                            <span class="fs-7 text-muted">
                                {{ $carbon->now()->isoFormat('dddd, D MMMM Y H:m') }}
                            </span>
                        </div>
                    </div>
                    <!--end::Order details-->
                    <!--begin::Separator-->
                    <div class="separator mt-5 mb-5"></div>
                    <!--begin::Separator-->
                    <!--begin::Billing & shipping-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        <div class="flex-root d-flex flex-column">
                            <span class="text-muted">Dari</span>
                            <span class="fs-2">CV. SUARA PENDIDIKAN NUSANTARA</span>
                            <span class="fs-8 text-gray-600 fw-medium">
                                <br />{{ str('Jl. Kyai Raden Santri, RT/RW, 02/01, Dukuhan Gunungpring')->upper() }}
                                <br />{{ str('Kec. Muntilan Magelang')->upper() }}
                                <br />JAWA TENGAH
                                <br />T: 085171694758
                                <br />E: cvsuarapendidikannusantara@gmail.com
                            </span>
                        </div>
                        <div class="flex-root d-flex flex-column text-end">
                            @php
                                $customerAddress = $order->customer_address;
                            @endphp
                            <span class="text-muted">Kepada</span>
                            <span class="fs-2">{{ optional($customerAddress)->name }}</span>
                            <span class="fs-8 text-gray-600 fw-medium">
                                <br />{{ str(optional($customerAddress)->address ?? '')->upper() }}, {{ str(optional($customerAddress)->village->name ?? '')->upper() }}
                                <br />Kec. {{ str(optional($customerAddress)->district->name ?? '')->upper() }}, {{ str(optional($customerAddress)->regency->name ?? '')->upper() }}
                                <br />{{ str(optional($customerAddress)->province->name ?? '')->upper() }},
                                <br />T: {{ optional($customerAddress)->phone_number }}
                                <br />E: {{ $order->customer->user->email }}
                            </span>
                        </div>
                    </div>
                    <!--end::Billing & shipping-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Body-->

            <!-- begin::Footer-->
            <div class="d-flex flex-stack flex-wrap mt-lg-20 pt-13">
                <!-- begin::Actions-->
                <div class="my-1 me-5">
                    <!-- begin::Pint-->
                    <a href="{{ route('preorder.index') }}" class="btn btn-danger my-1 me-12">Back</a>
                    <button type="button" class="btn btn-success my-1 me-12 float-right" onclick="printArticle();">Print Invoice</button>
                    <!-- end::Pint-->
                </div>
                <!-- end::Actions-->
            </div>
            <!-- end::Footer-->
        </div>
        <!-- end::Wrapper-->
    </div>
    <!-- end::Body-->
</div>
<!-- end::Invoice 1-->
@endsection

@push('css-plugin')
<style>
    @page { margin: 15px 20px 10px 20px; }
    @media print {
        @page { margin: 15px 20px 10px 20px; }
        body { margin: 1.6cm; }
        .header-fixed .wrapper{
            padding-top: 0px;
        }
    }
</style>
@endpush

@push('js')
<script>
    function printArticle() {
        document.querySelectorAll('button')
            .forEach(button => button.remove());
        document.querySelectorAll('a')
            .forEach(button => button.remove());
        document.getElementById("kt_header").remove();
        document.getElementById("kt_footer").remove();
        document.getElementById("kt_aside").remove();
        document.getElementById("kt_wrapper").style.paddingTop = '0px';
        document.getElementById("kt_wrapper").style.paddingLeft = '0px';

        var afterPrint = function() {
            window.location.reload();
        };

        window.onafterprint = afterPrint;

        window.print();
    }
</script>
@endpush
