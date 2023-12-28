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
            <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">SURAT JALAN</h4>
                <!--end::Logo-->
                <div class="text-sm-end">
                    <!--begin::Text-->
                    <div class="text-sm-end fw-semibold fs-4 text-muted mt-7">
                        <div>
                            {{ $carbon->now()->locale('id')->format('l, j F Y H:i:s') }}
                        </div>
                    </div>
                    <!--end::Text-->
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="pb-12">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column gap-7 gap-md-10">
                    <!--begin::Separator-->
                    <div class="separator"></div>
                    <!--begin::Separator-->
                    <!--begin::Billing & shipping-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        <div class="flex-root d-flex flex-column">
                            <span class="text-muted">Dari</span>
                            <span class="fs-6">CV. SUARA PENDIDIKAN NUSANTARA
                            <br />Jl. Kyai Raden Santri, RT/RW, 02/01, Dukuhan Gunungpring
                            <br />Kec. Muntilan Magelang,
                            <br />Jawa Tengah,
                            <br />Indonesia.
                            <br />Telepon: 085171694758
                            <br />Email: cvsuarapendidikannusantara@gmail.com</span>
                        </div>
                        <div class="flex-root d-flex flex-column">
                            @php
                                $customerAddress = $order->customer_address;
                            @endphp
                            <span class="text-muted">Kepada</span>
                            <span class="fs-6">{{ optional($customerAddress)->name }},
                            <br />{{ optional($customerAddress)->address }}, {{ optional($customerAddress)->village->name }}
                            <br />{{ optional($customerAddress)->district->name }},
                            <br />{{ optional($customerAddress)->regency->name }},
                            <br />{{ optional($customerAddress)->province->name }},
                            <br />Telepon: {{ optional($customerAddress)->phone_number }}
                            <br />Email: {{ $order->customer->user->email }}</span>
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
                                        <th class="min-w-100px text-end pb-2">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($order->details as $i => $detail)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $detail->product->code }}</td>
                                            <td>{{ $detail->product->name }}</td>
                                            <td class="text-end">{{ $detail->qty }} Exp</td>
                                            <td class="text-end"></td>
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
                        <div class="flex-root d-flex flex-column">
                            <h2>Catatan</h2>
                            <span class="fs-5">
                                {!! html_entity_decode(optional($order->collector)->billing_notes ?? '') !!}
                            </span>
                        </div>
                        <div class="flex-root d-flex flex-column">
                            <h2>Perhatian</h2>
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
                    <div class="text-center fw-semibold fs-4 text-muted mt-7">
                        Disetujui Oleh:
                        <!--begin::Logo-->
                        <span class="d-block mw-150px ms-sm-auto mt-4 mb-4">
                            <img alt="Logo" src="{{ mix('assets/media/liniswara_sign_in.png') }}" class="w-100" />
                        </span>
                        <!--end::Logo-->
                        <div>CV. SUARA PENDIDIKAN NUSANTARA</div>
                    </div>
                    <!--end::Text-->
                </div>
                <div class="flex-root d-flex flex-column">
                    <!--begin::Text-->
                    <div class="text-center fw-semibold fs-4 text-muted mt-7">
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
                    <div class="text-center fw-semibold fs-4 text-muted mt-7">
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
                    <div class="text-sm-start fw-semibold fs-4 text-muted mt-7">
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
        const originalHTML = document.body.innerHTML;
        document.body.innerHTML = document.getElementById('kt_content').innerHTML;
        document.querySelectorAll('button')
            .forEach(button => button.remove());
        document.querySelectorAll('a')
            .forEach(button => button.remove());

        var afterPrint = function() {
            // document.body.innerHTML = originalHtml;
            window.location.reload();
        };

        window.onafterprint = afterPrint;

        window.print();
    }
</script>
@endpush
