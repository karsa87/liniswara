@extends('marketing.layouts.marketing')

@section('content')
<div class="d-flex flex-column flex-center text-center p-10">
    <!--begin::Wrapper-->
    <div class="card card-flush w-lg-650px py-5">
        <div class="card-body py-15 py-lg-20">
            <!--begin::Logo-->
            <div class="mb-13">
                <a href="{{ route('dashboard') }}" class="">
                    <img alt="Logo" src="{{ mix('assets/media/logos/default-small.svg') }}" class="h-40px">
                </a>
            </div>
            <!--end::Logo-->
            <!--begin::Title-->
            <h1 class="fw-bolder text-gray-900 mb-7">We're Maintanance</h1>
            <!--end::Title-->
            <!--begin::Text-->
            <div class="fw-semibold fs-6 text-gray-500 mb-7">This is your opportunity to get creative amazing opportunaties
            <br>that gives readers an idea</div>
            <!--end::Text-->
            <!--begin::Illustration-->
            <div class="mb-n5">
                <img src="{{ mix('assets/media/auth/chart-graph.png') }}" class="mw-100 mh-300px theme-light-show" alt="">
                <img src="{{ mix('assets/media/auth/chart-graph-dark.png') }}" class="mw-100 mh-300px theme-dark-show" alt="">
            </div>
            <!--end::Illustration-->
        </div>
    </div>
    <!--end::Wrapper-->
</div>
@endsection
