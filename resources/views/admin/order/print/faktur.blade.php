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
            <div class="d-flex justify-content-between flex-column flex-sm-row">
                <!--end::Logo-->
                <div class="text-sm-start w-75">
                    <h4 class="fw-bolder fw-bold fs-3x pe-5" style="color: #6e87a7 !important;">FAKTUR PENJUALAN</h4>
                    <h5 class="fw-bolder fw-semibold fs-5 pe-5">
                        CV. SUARA PENDIDIKAN NUSANTARA
                    </h5>
                    <!--begin::Text-->
                    <span class="text-sm-end fs-7 text-muted mt-7">
                        JL. KIYAI RADEN SANTRI RT 01 RW 01 KEC. MUNTILAN KAB. MAGELANG JAWA TIMUR
                    </span>
                    <br>
                    <span class="text-sm-end fs-7 text-muted mt-7">
                        P : 0812-3333-4444 E: CVSUARAPENDIDIKANNUSATRA@GMAIL.COM
                    </span>
                    <!--end::Text-->
                </div>
                <div class="text-sm-end w-50">
                    <!--begin::Text-->
                    <span class="d-block ms-sm-auto">
                        <img alt="Liniswara" src="{{ mix('assets/media/logos/logo-liniswara.png') }}" class="mw-75">
                    </span>
                    <!--end::Text-->
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="pb-12">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column gap-7 gap-md-10">
                    <!--begin::Separator-->
                    <div class="mb-2 mt-2" style="display: block; height: 0; border-bottom: 4px solid #000000;"></div>
                    <!--begin::Separator-->
                    <!--begin::Billing & shipping-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        <div class="flex-root d-flex flex-column border p-5">
                            @php
                                $customerAddress = $order->customer_address;
                            @endphp
                            <span class="text-muted">Kepada</span>
                            <span class="fs-2x">{{ optional($customerAddress)->name }}</span>
                            <span class="fs-6 text-gray-600 fw-medium">
                                {{ str(optional($customerAddress)->address ?? '')->upper() }}, {{ str(optional($customerAddress)->village->name ?? '')->upper() }}
                                <br />Kec. {{ str(optional($customerAddress)->district->name ?? '')->upper() }}, {{ str(optional($customerAddress)->regency->name ?? '')->upper() }}
                                <br />{{ str(optional($customerAddress)->province->name ?? '')->upper() }},
                                <br />T: {{ optional($customerAddress)->phone_number }}
                                <br />E: {{ $order->customer->user->email }}
                            </span>
                        </div>
                        <div class="flex-root d-flex flex-column border p-5 pt-12">
                            <span class="fs-1">NO FAKTUR: {{ $order->invoice_number }}</span>
                            <span class="fs-6 text-gray-600 fw-medium">ADMIN: {{ auth()->user()->name }}</span>
                            <span class="fs-6 text-gray-600 fw-medium">TANGGAL CETAK: {{ $carbon->now()->locale('id')->format('d-m-Y H:i') }}</span>
                        </div>
                    </div>
                    <!--end::Billing & shipping-->
                    <!--begin::Separator-->
                    <div class="separator"></div>
                    <!--begin::Separator-->
                    <!--begin::Order details-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        <div class="flex-root d-flex flex-column">
                            <span class="text-muted">Invoice Number</span>
                            <span class="fs-5">{{ $order->invoice_number }}</span>
                        </div>
                        <div class="flex-root d-flex flex-column">
                            <span class="text-muted">Order ID</span>
                            <span class="fs-5">{{ $order->id }}</span>
                        </div>
                    </div>
                    <!--end::Order details-->
                    <!--begin:Order summary-->
                    <div class="d-flex justify-content-between flex-column">
                        <!--begin::Table-->
                        <div class="table-responsive border-bottom mb-9">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                <thead>
                                    <tr class="border-bottom fs-6 fw-bold text-muted">
                                        <th class="min-w-10px pb-2">#</th>
                                        <th class="min-w-70px pb-2">Kode Produk</th>
                                        <th class="min-w-175px pb-2">Nama Produk</th>
                                        <th class="min-w-70px text-end pb-2">Jumlah</th>
                                        <th class="min-w-100px text-end pb-2">Harga</th>
                                        <th class="min-w-100px text-end pb-2">Diskon</th>
                                        <th class="min-w-100px text-end pb-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($order->details as $i => $detail)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $detail->product->code }}</td>
                                            <td>{{ $detail->product->name }}</td>
                                            <td class="text-end">{{ $detail->qty }}</td>
                                            <td class="text-end">{{ $util->format_currency($detail->price, 0, 'Rp. ') }}</td>
                                            <td class="text-end">
                                                {{ $util->format_currency($detail->discount, 0, 'Rp. ') }}
                                                <div class="fs-7 text-muted">{{ $detail->discount_description }}</div>
                                            </td>
                                            <td class="text-end">{{ $util->format_currency($detail->total, 0, 'Rp. ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end">Subtotal</td>
                                        <td class="text-end">{{ $util->format_currency($order->subtotal, 0, 'Rp. ') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end">Diskon</td>
                                        <td class="text-end">
                                            @php
                                                $discountPrice = $order->discount_price;
                                                if ($order->discount_type == \App\Enums\Preorder\DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                                                    $discountPrice = $order->subtotal * ($order->discount_percentage / 100);
                                                }
                                            @endphp
                                            {{ $util->format_currency($discountPrice, 0, 'Rp. ') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="fs-3 text-dark text-end">Total</td>
                                        <td class="text-dark fs-3 fw-bolder text-end">{{ $util->format_currency($order->total_amount, 0, 'Rp. ') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end">Ongkir</td>
                                        <td class="text-end">{{ $util->format_currency($order->shipping_price, 0, 'Rp. ') }}</td>
                                    </tr>
                                    @if ($order->tax)
                                    <tr>
                                        <td colspan="6" class="text-end">{{ \App\Enums\Preorder\TaxEnum::MAP_LABEL[$order->tax] }}</td>
                                        <td class="text-end">{{ $util->format_currency($order->tax_amount, 0, 'Rp. ') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="6" class="fs-3 text-dark text-end">Grand Total</td>
                                        <td class="text-dark fs-3 fw-bolder text-end">
                                            {{ $util->format_currency($order->total_amount + $order->tax_amount + $order->shipping_price, 0, 'Rp. ') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end:Order summary-->

                    <!--begin::Order details-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        <div class="flex-root d-flex flex-column">
                            <h2>Catatan</h2>
                            <span class="fs-5">
                                {!! html_entity_decode(optional($order->collector)->billing_notes ?? '') !!}
                            </span>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        <div class="flex-root d-flex flex-column">
                            <h2>Catatan Penjualan</h2>
                            <span class="fs-5">
                                {!! html_entity_decode($order->notes) !!}
                            </span>
                        </div>
                    </div>
                    <!--end::Order details-->
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
    }
</style>
@endpush

@push('js-plugin')
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
