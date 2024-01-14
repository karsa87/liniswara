@extends('marketing.layouts.marketing')

@inject('util', 'App\Utils\Util')

@section('content')
    <!--begin::Row-->
    @foreach ($agents as $agent)
        <div class="row g-5 gx-xl-10">
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-md-5 mb-xl-10">
                <div class="card">
                    <div class="card-body pt-9 pb-5">
                        <!--begin::Details-->
                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <!--begin: Pic-->
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    @if (optional($agent->user)->profile_photo)
                                        <img src="{{ optional($agent->user)->profile_photo->full_url }}" alt="{{ optional($agent->user)->name }}" />
                                    @else
                                        <img src="{{ mix('marketing/assets/media/avatars/blank.png') }}" alt="{{ optional($agent->user)->name }}" />
                                    @endif
                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                </div>
                            </div>
                            <!--end::Pic-->
                            <!--begin::Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <!--begin::User-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Name-->
                                        <div class="d-flex align-items-center mb-2">
                                            <a href="{{ route('marketing.payment.detail_agent', $agent->id) }}" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ optional($agent->user)->name }}</a>
                                            <a href="{{ route('marketing.payment.detail_agent', $agent->id) }}">
                                                <i class="ki-duotone ki-verify fs-1 text-primary">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </a>
                                        </div>
                                        <!--end::Name-->
                                    </div>
                                    <!--end::User-->

                                    <div class="d-flex my-4">
                                        <a href="{{ route('marketing.payment.detail_agent', $agent->id) }}" class="btn btn-sm btn-light me-2">
                                            <i class="ki-duotone ki-eye fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            <!--begin::Indicator label-->
                                            <span class="indicator-label">Lihat Detail</span>
                                            <!--end::Indicator label-->
                                        </a>
                                    </div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column flex-grow-1 pe-8">
                                        <!--begin::Stats-->
                                        <div class="d-flex flex-wrap">
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded w-25 py-3 px-4 me-6 mb-3">
                                                {{ optional($agent->address)->full_address }}
                                            </div>
                                            <!--end::Stat-->
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded  w-20 py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-whatsapp fs-3 text-success me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ optional($agent->user)->phone_number }}
                                                </div>
                                                <!--end::Number-->
                                            </div>
                                            <!--end::Stat-->
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded  w-25 py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-duotone ki-sms fs-3 me-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    {{ optional($agent->user)->email }}
                                                </div>
                                                <!--end::Number-->
                                            </div>
                                            <!--end::Stat-->
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded  w-25 py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-40px me-3">
                                                        <i class="ki-duotone ki-wallet fs-3x">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </div>
                                                    <div class="d-flex justify-content-start flex-column">
                                                        <spam class="text-gray-800 fw-bold mb-1 fs-4">Total Penjualan</span>
                                                        <span class="text-gray-700 fw-semibold d-block fs-6">{{ $util->format_currency($agent->preorders_sum_total_amount) }}</span>
                                                    </div>
                                                </div>
                                                <!--end::Number-->
                                            </div>
                                            <!--end::Stat-->
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                    </div>
                </div>
                <!--end::Navbar-->
            </div>
        </div>
    @endforeach

    {{ $agents->links() }}
@endsection

