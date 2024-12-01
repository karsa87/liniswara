@extends('admin.layouts.admin')

@inject('util', 'App\Utils\Util')

@section('content')
<!--begin::Retur details page-->
<div class="d-flex flex-column gap-7 gap-lg-10">
    <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
        <!--begin:::Tabs-->
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_sales_order_summary">Retur Summary</a>
            </li>
            <!--end:::Tab item-->
        </ul>
        <!--end:::Tabs-->
        <!--begin::Button-->
        <a href="{{ route('return_order.index') }}" class="btn btn-icon btn-light btn-active-secondary btn-sm ms-auto me-lg-n7">
            <i class="ki-duotone ki-left fs-2"></i>
        </a>
        <!--end::Button-->
        <!--begin::Button-->
        <a href="{{ route('return_order.edit', $returnOrder->id) }}" class="btn btn-success btn-sm me-lg-n7">Edit Retur</a>
        <!--end::Button-->
    </div>
    <!--begin::Retur summary-->
    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
        <!--begin::Retur details-->
        <div class="card card-flush py-4 flex-row-fluid col-md-4 col-sm-12">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>Retur Detail</h2>
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
                                <td class="fw-bold text-end">{{ $returnOrder->date }}</td>
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
                                <td class="fw-bold text-end">{{ optional($returnOrder->branch)->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-note fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Catatan
                                </td>
                                <td class="fw-bold text-end">{!! html_entity_decode($returnOrder->notes) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Retur details-->
        <!--begin::Customer details-->
        <div class="card card-flush py-4 flex-row-fluid col-md-4 col-sm-12">
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
                                $order = $returnOrder->order;
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
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-geolocation-home fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Area</div>
                                </td>
                                <td class="fw-bold text-end">{{ optional($order->area)->name }}</td>
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
        <div class="card card-flush py-4 flex-row-fluid col-md-4 col-sm-12">
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
                                    </i>No Retur
                                    <span class="ms-1" data-bs-toggle="tooltip" title="View the invoice generated by this order.">
                                        <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span></div>
                                </td>
                                <td class="fw-bold text-end">{{ $returnOrder->no_return }}</td>
                            </tr>
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
                                <td class="fw-bold text-end">{{ $returnOrder->order->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-wallet fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>Status Retur</div>
                                </td>
                                <td class="fw-bold text-end">{{ \App\Enums\ReturnOrder\StatusEnum::getLabels()[$returnOrder->status] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-discount fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Total Retur
                                </td>
                                <td class="fw-bold text-end">{{ $util->format_currency($returnOrder->total_amount, 0 , 'Rp. ') }}</td>
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
    <!--end::Retur summary-->
    <!--begin::Tab content-->
    <div class="tab-content">
        <!--begin::Tab pane-->
        <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
            <!--begin::Returs-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <!--begin::Product List-->
                <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Produk</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed table-striped fs-6 gy-5 mb-0">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-10px text-center">No</th>
                                        <th class="min-w-175px">Nama</th>
                                        <th class="min-w-100px text-center">Kode</th>
                                        <th class="min-w-70px text-end">Jumlah</th>
                                        <th class="min-w-100px text-end">Harga</th>
                                        <th class="min-w-100px text-end">Diskon</th>
                                        <th class="min-w-100px text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($returnOrder->details->sortBy('product.code') as $detail)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (optional($detail->product)->thumbnail)
                                                        <!--begin::Thumbnail-->
                                                        <a href="javascript:void(0)" class="symbol symbol-50px mr-5">
                                                            <span class="symbol-label" style="background-image:url({{ optional($detail->product->thumbnail)->full_url }});"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->
                                                    @endif
                                                    <!--begin::Title-->
                                                    <div>
                                                        <a href="javascript:void(0)" class="fw-bold text-gray-600 text-hover-primary">{{ optional($detail->product)->name }}</a>
                                                        {{-- <div class="fs-7 text-muted">Delivery Date: 19/07/2023</div> --}}
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                            </td>
                                            <td class="text-center">{{ optional($detail->product)->code }}</td>
                                            <td class="text-end">{{ $detail->qty }}</td>
                                            <td class="text-end">{{ $util->format_currency($detail->price, 0, 'Rp. ') }}</td>
                                            <td class="text-end">
                                                {{ $util->format_currency($detail->discount, 0, 'Rp. ') }}
                                                <div class="fs-7 text-muted">{{ $detail->discount_description }}</div>
                                            </td>
                                            <td class="text-end">{{ $util->format_currency($detail->total, 0, 'Rp. ') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="text-end">Subtotal</td>
                                        <td class="text-end">{{ $util->format_currency($returnOrder->subtotal, 0, 'Rp. ') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end">Diskon</td>
                                        <td class="text-end">
                                            @php
                                                $discountPrice = $returnOrder->discount_price;
                                                if ($returnOrder->discount_type == \App\Enums\Preorder\DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                                                    $discountPrice = $returnOrder->subtotal * ($returnOrder->discount_percentage / 100);
                                                    echo $returnOrder->discount_percentage . '% ';
                                                }
                                            @endphp
                                            <span class="text-danger">(-{{ $util->format_currency($discountPrice, 0, 'Rp. ') }})</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="fs-3 text-dark text-end">Total</td>
                                        <td class="text-dark fs-3 fw-bolder text-end">{{ $util->format_currency($returnOrder->total_amount, 0, 'Rp. ') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end">Ongkir</td>
                                        <td class="text-end">{{ $util->format_currency($returnOrder->shipping_price, 0, 'Rp. ') }}</td>
                                    </tr>
                                    @if ($returnOrder->tax)
                                    <tr>
                                        <td colspan="6" class="text-end">{{ \App\Enums\Preorder\TaxEnum::MAP_LABEL[$returnOrder->tax] }}</td>
                                        <td class="text-end">{{ $util->format_currency($returnOrder->tax_amount, 0, 'Rp. ') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="6" class="fs-3 text-dark text-end">Grand Total</td>
                                        <td class="text-dark fs-3 fw-bolder text-end">
                                            {{ $util->format_currency($returnOrder->total_amount + $returnOrder->tax_amount + $returnOrder->shipping_price, 0, 'Rp. ') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Product List-->
            </div>
            <!--end::Returs-->
        </div>
        <!--end::Tab pane-->
    </div>
    <!--end::Tab content-->
</div>
<!--end::Retur details page-->
@endsection
