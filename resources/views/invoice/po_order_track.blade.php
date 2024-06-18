@extends('invoice.layout_invoice')

@inject('carbon', '\Carbon\Carbon')
@inject('util', 'App\Utils\Util')

@section('content')
<div class="d-flex flex-column gap-7 gap-lg-10">
    <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
        <!--begin::Button-->
        <a href="{{ route('customer.po_order', [$key]) }}" class="btn btn-icon btn-light btn-active-secondary btn-sm ms-auto me-lg-n7">
            <i class="ki-duotone ki-left fs-2"></i>
        </a>
        <!--end::Button-->
    </div>
    <!--begin::Order summary-->
    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
        <!--begin::Order details-->
        <div class="card card-flush py-4 flex-row-fluid">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Preorder Detail</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                        <tbody class="fw-semibold text-gray-600">
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-calendar fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Tanggal</div>
                                </td>
                                <td class="fw-bold text-end">{{ $order->date }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-profile-circle fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>Penagih</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional($order->collector)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-profile-circle fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>Gudang</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional($order->branch)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-profile-circle fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>Marketing</div>
                                </td>
                                <td class="fw-bold text-end">{{ \App\Enums\Preorder\MarketingEnum::MAP_LABEL[$order->marketing] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-note-2 fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>Catatan Staff
                                </td>
                                <td class="fw-bold text-end">{!! html_entity_decode($order->notes_staff) !!}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-note fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Catatan
                                </td>
                                <td class="fw-bold text-end">{!! html_entity_decode($order->notes) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Order details-->
        <!--begin::Customer details-->
        <div class="card card-flush py-4 flex-row-fluid">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Pelanggan Detail</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                        <tbody class="fw-semibold text-gray-600">
                            @php
                                $customer = $order->customer;
                            @endphp
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-profile-circle fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>Pelanggan/Agen</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional($customer->user)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-sms fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Email</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional($customer->user)->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-phone fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Phone</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional($customer->user)->phone_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-geolocation-home fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Alamat</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional($order->customer_address)->address }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-geolocation-home fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Kelurahan / Desa</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional(optional($order->customer_address)->village)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-geolocation-home fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Kecamatan</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional(optional($order->customer_address)->district)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-geolocation-home fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Kabupaten / Kota</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional(optional($order->customer_address)->regency)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-geolocation-home fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Provinsi</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional(optional($order->customer_address)->province)->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Customer details-->
        <!--begin::Documents-->
        <div class="card card-flush py-4 flex-row-fluid">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Documents</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                        <tbody class="fw-semibold text-gray-600">
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-devices fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>Invoice
                                    <span class="ms-1" data-bs-toggle="tooltip" title="View the invoice generated by this order.">
                                        <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span></div>
                                </td>
                                <td class="fw-bold text-end">{{ $order->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-delivery-3 fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>Resi
                                    <span class="ms-1" data-bs-toggle="tooltip" title="View the shipping manifest generated by this order.">
                                        <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span></div>
                                </td>
                                <td class="fw-bold text-end">{{ optional($order->shipping)->resi }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-truck fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>Ekspedisi
                                </td>
                                <td class="fw-bold text-end">{{ optional(optional($order->shipping)->expedition)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-wallet fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>Status Order</div>
                                </td>
                                <td class="fw-bold text-end">{{ \App\Enums\Order\StatusEnum::MAP_LABEL[$order->status] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-wallet fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>Status Terbayar</div>
                                </td>
                                <td class="fw-bold text-end">{{ \App\Enums\Preorder\StatusPaymentEnum::MAP_LABEL[$order->status_payment] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-wallet fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>Status Pembayaran</div>
                                </td>
                                <td class="fw-bold text-end">{{ \App\Enums\Preorder\MethodPaymentEnum::MAP_LABEL[$order->method_payment] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-discount fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Total Preorder
                                </td>
                                <td class="fw-bold text-end">{{ $util->format_currency($order->total_amount, 0 , 'Rp. ') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Documents-->
    </div>
    <!--end::Order summary-->

    @if ($detailTrack)
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
            <!--begin::Payment address-->
            <div class="card card-flush py-4 flex-row-fluid position-relative">
                <!--begin::Background-->
                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                    <i class="ki-solid ki-two-credit-cart" style="font-size: 14em"></i>
                </div>
                <!--end::Background-->
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Pengirim</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">{!! $detailTrack['detail']['shipper'] !!},
                <br />{!! $detailTrack['detail']['origin'] !!}</div>
                <!--end::Card body-->
            </div>
            <!--end::Payment address-->
            <!--begin::Shipping address-->
            <div class="card card-flush py-4 flex-row-fluid position-relative">
                <!--begin::Background-->
                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                    <i class="ki-solid ki-delivery" style="font-size: 13em"></i>
                </div>
                <!--end::Background-->
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Shipping Address</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">{!! $detailTrack['detail']['receiver'] !!},
                    <br />{!! $detailTrack['detail']['destination'] !!}</div>
                <!--end::Card body-->
            </div>
            <!--end::Shipping address-->
        </div>
    </div>

    <!--begin::Timeline-->
    <div class="card">
        <!--begin::Card head-->
        <div class="card-header card-header-stretch">
            <!--begin::Title-->
            <div class="card-title d-flex align-items-center">
                <i class="ki-duotone ki-calendar-8 fs-1 text-primary me-3 lh-0">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                    <span class="path6"></span>
                </i>
                <h3 class="fw-bold m-0 text-gray-800">{{ date('d-m-Y') }}</h3>
            </div>
            <!--end::Title-->
            <!--begin::Toolbar-->
            <div class="card-toolbar m-0">
                <!--begin::Tab nav-->
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a id="kt_activity_today_tab" class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#kt_activity_today">Today</a>
                    </li>
                </ul>
                <!--end::Tab nav-->
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Card head-->
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Tab Content-->
            <div class="tab-content">
                <!--begin::Tab panel-->
                <div id="kt_activity_today" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="kt_activity_today_tab">
                    <!--begin::Timeline-->
                    <div class="timeline">
                        @foreach ($detailTrack['history'] as $track)
                            <!--begin::Timeline item-->
                            <div class="timeline-item">
                                <!--begin::Timeline line-->
                                <div class="timeline-line w-40px"></div>
                                <!--end::Timeline line-->
                                <!--begin::Timeline icon-->
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light">
                                        <i class="ki-duotone ki-flag fs-2 text-gray-500">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <!--end::Timeline icon-->
                                <!--begin::Timeline content-->
                                <div class="timeline-content mb-10 mt-n2">
                                    <!--begin::Timeline heading-->
                                    <div class="overflow-auto pe-3">
                                        <!--begin::Title-->
                                        <div class="fs-5 fw-semibold mb-2">
                                            {!! $track['desc'] !!}
                                            @if ($track['location'])
                                                @if ($track['desc'])
                                                    -
                                                @endif
                                                {!! $track['location'] !!}
                                            @endif
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Description-->
                                        <div class="d-flex align-items-center mt-1 fs-6">
                                            <!--begin::Info-->
                                            <div class="text-muted me-2 fs-7">
                                                {{ $carbon->parse($track['date'])->isoFormat('dddd, D MMMM Y H:m') }}
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Timeline heading-->
                                </div>
                                <!--end::Timeline content-->
                            </div>
                            <!--end::Timeline item-->
                        @endforeach
                    </div>
                    <!--end::Timeline-->
                </div>
                <!--end::Tab panel-->
            </div>
            <!--end::Tab Content-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Timeline-->
    @else
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
            <!--begin::Payment address-->
            <div class="card card-flush py-4 flex-row-fluid position-relative">
                <div class="card-body">
                    Belum ada catatan pengiriman dari expedisi
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!--end::Order details page-->
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
        // document.getElementById("kt_aside").remove();
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
