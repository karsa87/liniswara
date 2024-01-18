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
                    <h4 class="fw-bolder fw-bold fs-2x pe-5 text-center">SURAT JALAN</h4>
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
                                        <th class="min-w-70px text-end pb-1 pt-1">Jumlah</th>
                                        <th class="min-w-100px pb-1 pt-1">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($order->details as $i => $detail)
                                        <tr>
                                            <td class="fs-8 pb-1 pt-1 text-center">{{ $i+1 }}</td>
                                            <td class="fs-8 pb-1 pt-1">{{ $detail->product->code }}</td>
                                            <td class="fs-8 pb-1 pt-1">{{ $detail->product->name }}</td>
                                            <td class="text-end fs-8 pb-1 pt-1 text-center">{{ $detail->qty }} Exp</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end:Order summary-->

                    <!--begin::Order details-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        {{-- <div class="flex-root d-flex flex-column">
                            <h2>Catatan</h2>
                            <span class="fs-5">
                                {!! html_entity_decode(optional($order->collector)->billing_notes ?? '') !!}
                            </span>
                        </div>
                         --}}
                        <div class="fs-8 flex-root d-flex flex-column">
                            <h4>Perhatian</h4>
                            <ul>
                                <ol type="1">1. Surat Jalan ini merupakan bukti resmi penerimaan barang.</ol>
                                <ol type="1">2. Surat Jalan ini bukan bukti penjualan.</ol>
                                <ol type="1">3. Surat Jalan ini akan dilengkapi dengan invoice sebagai bukti
                                    penjualan.</ol>
                            </ul>
                        </div>
                    </div>
                    <!--end::Order details-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Body-->


            <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                <div class="flex-root d-flex flex-column">
                    <!--begin::Text-->
                    <div class="text-center fw-semibold fs-7 text-muted mt-7">
                        Disetujui Oleh:
                        <!--begin::Logo-->
                        <span class="d-block mw-150px ms-sm-auto mt-4 mb-4">
                            @if (optional($order->collector)->signin_file)
                                <img alt="Logo" src="{{ optional($order->collector)->signin_file->full_url }}" class="w-100" />
                            @else
                                <br>
                                <br>
                                <br>
                            @endif
                        </span>
                        <!--end::Logo-->
                        <div>
                            {{ $order->collector ? $order->collector->name : '.......................' }}
                        </div>
                    </div>
                    <!--end::Text-->
                </div>
                <div class="flex-root d-flex flex-column">
                    <!--begin::Text-->
                    <div class="text-center fw-semibold fs-7 text-muted mt-7">
                        Dikeluarkan:
                        <!--begin::Logo-->
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <!--end::Logo-->
                        <div>Admin</div>
                    </div>
                    <!--end::Text-->
                </div>
                <div class="flex-root d-flex flex-column">
                    <!--begin::Text-->
                    <div class="text-center fw-semibold fs-7 text-muted mt-7">
                        Dikirim:
                        <!--begin::Logo-->
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <!--end::Logo-->
                        <div>Driver</div>
                    </div>
                    <!--end::Text-->
                </div>
                <div class="flex-root d-flex flex-column">
                    <!--begin::Text-->
                    <div class="text-sm-start fw-semibold fs-7 text-muted mt-7">
                        Diterima:
                        <!--begin::Logo-->
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <!--end::Logo-->
                        <div>Nama : </div>
                        <div>Tanggal : </div>
                    </div>
                    <!--end::Text-->
                </div>
            </div>
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
