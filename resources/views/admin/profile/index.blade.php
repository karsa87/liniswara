@extends('admin.layouts.admin')

@section('content')
<!--begin::Navbar-->
<div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">
        <!--begin::Details-->
        <div class="d-flex flex-wrap flex-sm-nowrap">
            <!--begin: Pic-->
            <div class="me-7 mb-4">
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                    @if ($user->profile_photo)
                    <!--begin::Avatar-->
                    <img src="{{ $user->profile_photo->full_url }}" alt="{{ $user->name }}" />
                    <!--end::Avatar-->
                    @else
                    <img src="{{ mix('assets/media/avatars/blank.png') }}" alt="{{ $user->name }}" />
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
                            <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $user->name }}</a>
                            <a href="#">
                                <i class="ki-duotone ki-verify fs-1 text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                        </div>
                        <!--end::Name-->
                        <!--begin::Info-->
                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-profile-circle fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>{{ optional($user->role->first())->name }}
                            </a>
                            @if ($user->company)
                            <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-geolocation fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>{{ $user->company }}
                            </a>
                            @endif
                            <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                <i class="ki-duotone ki-sms fs-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>{{ $user->email }}
                            </a>
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::User-->
                </div>
                <!--end::Title-->
                <!--begin::Stats-->
                <div class="d-flex flex-wrap flex-stack">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column flex-grow-1 pe-8">
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap">
                            <!--begin::Stat-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $total['preorder'] ?? 0 }}">{{ $total['preorder'] ?? 0 }}</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 text-gray-400">Preorder</div>
                                <!--end::Label-->
                            </div>
                            <!--end::Stat-->
                            <!--begin::Stat-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $total['preorder'] ?? 0 }}">{{ $total['order'] ?? 0 }}</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 text-gray-400">Pesanan</div>
                                <!--end::Label-->
                            </div>
                            <!--end::Stat-->
                            <!--begin::Stat-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $total['order_sent'] ?? 0 }}">{{ $total['order_sent'] ?? 0 }}</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 text-gray-400">Pengiriman</div>
                                <!--end::Label-->
                            </div>
                            <!--end::Stat-->
                            <!--begin::Stat-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $total['order_arsip'] ?? 0 }}">{{ $total['order_arsip'] ?? 0 }}</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 text-gray-400">Arsip</div>
                                <!--end::Label-->
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
        <!--begin::Navs-->
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
            <!--begin::Nav item-->
            <li class="nav-item mt-2">
                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="../../demo14/dist/account/settings.html">Settings</a>
            </li>
            <!--end::Nav item-->
            <!--begin::Nav item-->
            {{-- <li class="nav-item mt-2">
                <a class="nav-link text-active-primary ms-0 me-10 py-5" href="../../demo14/dist/account/logs.html">Logs</a>
            </li> --}}
            <!--end::Nav item-->
        </ul>
        <!--begin::Navs-->
    </div>
</div>
<!--end::Navbar-->
<!--begin::Basic info-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Profile Details</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
        <!--begin::Form-->
        <form id="kt_account_profile_details_form" class="form" action="{{ route('profile.update') }}">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Dropzone-->
                        <div class="dropzone" id="kt_modal_add_update_user_profile_photo">
                            <!--begin::Message-->
                            <div class="dz-message needsclick">
                                <!--begin::Icon-->
                                <i class="ki-duotone ki-file-up fs-3hx text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <!--end::Icon-->
                                <!--begin::Info-->
                                <div class="ms-4">
                                    <h3 class="dfs-3 fw-bold text-gray-900 mb-1">Letakkan file di sini atau klik untuk mengunggah.</h3>
                                    {{-- <span class="fw-semibold fs-4 text-muted">Upload 1 file, only jpg, jpeg and png with max size 2Mb</span> --}}
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                        <!--end::Dropzone-->
                        <!--end::Image input-->
                        <!--begin::Description-->
                        <div class="text-muted fs-7">Atur gambar mini pengguna. Hanya file gambar *.png, *.jpg dan *.jpeg yang diterima dengan ukuran maksimal 2Mb</div>
                        <!--end::Description-->
                        <input type="hidden" name="profile_photo_id" value="{{ $user->profile_photo_id }}">
                        <input type="hidden" id="profile_photo_name" value="{{ optional($user->profile_photo)->name }}">
                        <input type="hidden" id="profile_photo_full_url" value="{{ optional($user->profile_photo)->full_url }}">
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nama</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row">
                                <input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="First name" value="{{ $user->name }}" />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Perusahaan</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="company" class="form-control form-control-lg form-control-solid" placeholder="Perusahaan" value="{{ $user->company }}" />
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">
                        <span class="required">Phone</span>
                        <span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active">
                            <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                        <input type="tel" name="phone" class="form-control form-control-lg form-control-solid" placeholder="Phone number" value="{{ $user->phone_number }}" />
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
</div>
<!--end::Basic info-->
<!--begin::Sign-in Method-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Email dan Password</h3>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_signin_method" class="collapse show">
        <!--begin::Card body-->
        <div class="card-body border-top p-9">
            <!--begin::Email Address-->
            <div class="d-flex flex-wrap align-items-center">
                <!--begin::Label-->
                <div id="kt_signin_email">
                    <div class="fs-6 fw-bold mb-1">Email Address</div>
                    <div class="fw-semibold text-gray-600">{{ $user->email }}</div>
                </div>
                <!--end::Label-->
                <!--begin::Edit-->
                <div id="kt_signin_email_edit" class="flex-row-fluid d-none">
                    <!--begin::Form-->
                    <form id="kt_signin_change_email" class="form" novalidate="novalidate" action="{{ route('profile.update.email') }}">
                        <div class="row mb-6">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <div class="fv-row mb-0">
                                    <label for="emailaddress" class="form-label fs-6 fw-bold mb-3">Email baru</label>
                                    <input type="email" class="form-control form-control-lg form-control-solid" id="emailaddress" placeholder="Email Address" name="emailaddress" value="{{ $user->email }}" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="fv-row mb-0">
                                    <label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3">Konfirmasi password</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid" name="confirmemailpassword" id="confirmemailpassword" />
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button id="kt_signin_submit" type="button" class="btn btn-primary me-2 px-6">Update Email</button>
                            <button id="kt_signin_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Edit-->
                <!--begin::Action-->
                <div id="kt_signin_email_button" class="ms-auto">
                    <button class="btn btn-light btn-active-light-primary">Ganti Email</button>
                </div>
                <!--end::Action-->
            </div>
            <!--end::Email Address-->
            <!--begin::Separator-->
            <div class="separator separator-dashed my-6"></div>
            <!--end::Separator-->
            <!--begin::Password-->
            <div class="d-flex flex-wrap align-items-center mb-10">
                <!--begin::Label-->
                <div id="kt_signin_password">
                    <div class="fs-6 fw-bold mb-1">Password</div>
                    <div class="fw-semibold text-gray-600">************</div>
                </div>
                <!--end::Label-->
                <!--begin::Edit-->
                <div id="kt_signin_password_edit" class="flex-row-fluid d-none">
                    <!--begin::Form-->
                    <form id="kt_signin_change_password" class="form" novalidate="novalidate" action="{{ route('profile.update.password') }}">
                        <div class="row mb-1">
                            <div class="col-lg-4">
                                <div class="fv-row mb-0">
                                    <label for="currentpassword" class="form-label fs-6 fw-bold mb-3">Password saat ini</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid" name="currentpassword" id="currentpassword" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="fv-row mb-0">
                                    <label for="password" class="form-label fs-6 fw-bold mb-3">Password baru</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid" name="password" id="password" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="fv-row mb-0">
                                    <label for="password_confirmation" class="form-label fs-6 fw-bold mb-3">Konfirmasi password baru</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid" name="password_confirmation" id="password_confirmation" />
                                </div>
                            </div>
                        </div>
                        <div class="form-text mb-5">Gunakan 8 karakter atau lebih dengan campuran huruf, angka & simbol.</div>
                        <div class="d-flex">
                            <button id="kt_password_submit" type="button" class="btn btn-primary me-2 px-6">Update Password</button>
                            <button id="kt_password_cancel" type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Edit-->
                <!--begin::Action-->
                <div id="kt_signin_password_button" class="ms-auto">
                    <button class="btn btn-light btn-active-light-primary">Reset Password</button>
                </div>
                <!--end::Action-->
            </div>
            <!--end::Password-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Content-->
</div>
<!--end::Sign-in Method-->
@endsection

@push('css-plugin')
<link href="{{ mix('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js-plugin')
<script src="{{ mix('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ mix('assets/js/custom/account/settings/signin-methods.js') }}"></script>
    <script src="{{ mix('assets/js/custom/account/settings/profile-details.js') }}"></script>
    <script src="{{ mix('assets/js/custom/account/settings/deactivate-account.js') }}"></script>
@endpush
