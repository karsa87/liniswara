@extends('admin.layouts.admin')

@inject('carbon', '\Carbon\Carbon')
@inject('util', 'App\Utils\Util')

@section('content')
<!-- begin::Invoice 3-->
<div class="card">
    <!-- begin::Body-->
    <div class="card-body py-10">
        <!-- begin::Wrapper-->
        <div class="mw-lg-950px mx-auto w-100">
            <!-- begin::Header-->
            <div class="d-flex justify-content-between flex-column flex-sm-row">
                <!--end::Logo-->
                <div class="text-sm-start w-100">
                    <h4 class="fw-bolder fw-bold fs-2x pe-5 text-center">PURCHASING ORDER</h4>
                    <h5 class="fw-medium fs-6 pe-5 text-center">
                        No PO: {{ $order->invoice_number }}
                    </h5>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div>
                <!--begin::Wrapper-->
                <div class="d-flex flex-column gap-2">
                    <!--begin::Separator-->
                    <div class="mb-2 mt-2" style="display: block; height: 0; border-bottom: 4px solid #000000;"></div>
                    <!--begin::Separator-->
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-medium fs-6 pe-5 text-end">
                            {{ $carbon->now()->locale('id')->format('j F Y') }}
                        </span>
                    </div>
                    <!--begin::Billing & shipping-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold mt-5">
                        <div class="flex-root d-flex flex-column border p-5">
                            <span class="text-muted">Penyedia</span>
                            <span class="fs-2">{{ optional($order->collector)->name }}</span>
                            <span class="fs-8 text-gray-600 fw-medium">
                                {{ str(optional($order->collector)->address ?? '')->upper() }}, {{ str(optional($order->collector)->village->name ?? '')->upper() }}
                                @if (optional($order->collector)->district)
                                    <br />Kec. {{ str(optional($order->collector)->district->name ?? '')->upper() }}, {{ str(optional($order->collector)->regency->name ?? '')->upper() }}
                                @endif

                                @if (optional($order->collector)->province)
                                    <br />{{ str(optional($order->collector)->province->name ?? '')->upper() }},
                                @endif
                                <br />T: {{ optional($order->collector)->phone_number }}
                            </span>
                        </div>
                        <div class="flex-root d-flex flex-column border p-5">
                            @php
                                $customerAddress = $order->customer_address;
                            @endphp
                            <span class="text-muted">Pemesan</span>
                            <span class="fs-2">{{ optional($customerAddress)->name }}</span>
                            <span class="fs-8 text-gray-600 fw-medium">
                                {{ str(optional($customerAddress)->address ?? '')->upper() }}, {{ str(optional($customerAddress)->village->name ?? '')->upper() }}
                                @if (optional($customerAddress)->district)
                                    <br />Kec. {{ str(optional($customerAddress)->district->name ?? '')->upper() }}, {{ str(optional($customerAddress)->regency->name ?? '')->upper() }}
                                @endif

                                @if (optional($customerAddress)->province)
                                    <br />{{ str(optional($customerAddress)->province->name ?? '')->upper() }},
                                @endif
                                <br />T: {{ optional($customerAddress)->phone_number }}
                            </span>
                        </div>
                    </div>
                    <!--end::Billing & shipping-->
                    <!--begin:Order summary-->
                    <div class="d-flex justify-content-between flex-column mt-5">
                        <!--begin::Table-->
                        <div class="table-responsive mb-9">
                            <table class="table align-middle table-row-dashed table-striped fs-6 gy-5 mb-0 table-bordered">
                                <thead>
                                    <tr class="fs-6 fw-bold text-muted">
                                        <th class="min-w-10px pb-1 pt-1 text-center">#</th>
                                        <th class="min-w-70px pb-1 pt-1">Kode Produk</th>
                                        <th class="min-w-175px pb-1 pt-1">Nama Produk</th>
                                        <th class="min-w-70px text-end pb-1 pt-1 text-center">Jumlah</th>
                                        <th class="min-w-100px text-end pb-1 pt-1">Harga</th>
                                        {{-- <th class="min-w-100px text-end pb-1 pt-1">Diskon</th> --}}
                                        <th class="min-w-100px text-end pb-1 pt-1">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($order->details as $i => $detail)
                                        <tr>
                                            <td class="fs-8 pb-1 pt-1 text-center">{{ $i+1 }}</td>
                                            <td class="fs-8 pb-1 pt-1">{{ $detail->product->code }}</td>
                                            <td class="fs-8 pb-1 pt-1">{{ $detail->product->name }}</td>
                                            <td class="text-end fs-8 pb-1 pt-1 text-center">{{ $detail->qty }}</td>
                                            <td class="text-end fs-8 pb-1 pt-1">{{ $util->format_currency($detail->price, 0, 'Rp. ') }}</td>
                                            {{-- <td class="text-end fs-8 pb-1 pt-1">
                                                {{ $util->format_currency($detail->discount, 0, 'Rp. ') }}
                                                <div class="fs-7 text-muted">{{ $detail->discount_description }}</div>
                                            </td> --}}
                                            <td class="text-end fs-8 pb-1 pt-1">{{ $util->format_currency($detail->total, 0, 'Rp. ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end fs-7 pb-1 pt-1">Subtotal</td>
                                        <td class="text-end fs-7 pb-1 pt-1">{{ $util->format_currency($order->subtotal, 0, 'Rp. ') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fs-7 pb-1 pt-1">
                                            Diskon
                                        </td>
                                        <td class="text-end fs-7 pb-1 pt-1">
                                            @php
                                                $discountPrice = $order->discount_price;
                                                if ($order->discount_type == \App\Enums\Preorder\DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                                                    $discountPrice = $order->subtotal * ($order->discount_percentage / 100);
                                                    echo ($order->discount_percentage / 100) . '% ';
                                                }
                                            @endphp
                                            <span class="text-danger">
                                                (-{{ $util->format_currency($discountPrice, 0, 'Rp. ') }})
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fs-7 pb-1 pt-1">Biaya Kirim</td>
                                        <td class="text-end fs-7 pb-1 pt-1">{{ $util->format_currency($order->shipping_price, 0, 'Rp. ') }}</td>
                                    </tr>
                                    @if ($order->tax)
                                    <tr>
                                        <td colspan="5" class="text-end fs-7 pb-1 pt-1">{{ \App\Enums\Preorder\TaxEnum::MAP_LABEL[$order->tax] }}</td>
                                        <td class="text-end fs-7 pb-1 pt-1">{{ $util->format_currency($order->tax_amount, 0, 'Rp. ') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="5" class="text-dark fw-bolder text-end fs-5 pb-1 pt-1">Total</td>
                                        <td class="text-dark fw-bolder text-end fs-5 pb-1 pt-1">
                                            {{ $util->format_currency($order->total_amount + $order->tax_amount + $order->shipping_price, 0, 'Rp. ') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end:Order summary-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Body-->

            <!-- begin::Footer-->
            <div class="d-flex justify-content-between flex-column flex-sm-row">
                <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7"></h4>
                <!--end::Logo-->
                <div class="text-sm-end">
                    <!--begin::Text-->
                    <div class="text-sm-end fw-semibold fs-4 text-muted mt-2">
                        Disetujui Oleh:
                        <!--begin::Logo-->
                        <span class="d-block mw-150px ms-sm-auto">
                            <img alt="Logo" src="{{ mix('assets/media/liniswara_sign_in.png') }}" class="w-100" />
                        </span>
                        <!--end::Logo-->
                        <div>CV. SUARA PENDIDIKAN NUSANTARA</div>
                    </div>
                    <!--end::Text-->
                </div>
            </div>
            <!--end::Footer-->
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
