<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href=""/>
        <title>{{ config('app.name', 'Laravel') }}</title>
		<meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name=description content="Sistem Manajemen Warehouse" />
        <meta name=keywords content="sistem, manajemen, proyek" />
        <meta http-equiv="Expires" content="{{ now() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="asrakit.com">

        <meta property="og:title" content="Sistem Manajemen Warehouse" />
        <meta property="og:site_name" content="{{ url('/') }}" />
        <meta property="og:url" content="{{ url('/') }}" />
        <meta property="og:description" content="Sistem Manajemen Warehouse" />
        <meta property="og:type" content="article" />
        <meta property="og:image" content="{{ asset('images/logo.png') }}" />
        <meta property="og:image:secure_url" content="{{ asset('images/logo.png') }}" />
        <meta property="og:image:type" content="image/png" />
        <meta property="og:image:width" content="750" />
        <meta property="og:image:height" content="420" />

        <!--SOCMED PROPERTY TWITTER-->
        <meta name="twitter:title" content="SIMPRO" />
        <meta name="twitter:description" content="Sistem manajemen proyek" />
        <meta name="twitter:image" content="{{ asset('images/logo.png') }}" />
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@asrakit">
        <meta name="twitter:creator" content="@asrakit">
        <meta name="twitter:domain" content="https://twitter.com/asrakit">


        <meta name="path" content="{{ explode('/',Request::path())[0] }}"/>
        <meta name="full" content="{{ Request::fullUrl() }}"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base" content="{{ url('') }}"/>
        <meta name="referrer" content="no-referrer"/>
        <meta name="storage_url" content="{{ \Storage::url('') }}"/>

		<link rel="canonical" href="https://www.asrakit.com" />
		<link rel="shortcut icon" href="{{ mix('assets/media/logos/favicon.png') }}" />

		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->

		<!--begin::Vendor Stylesheets(used for this page only)-->
        @stack('css-plugin')
		<!--end::Vendor Stylesheets-->

		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ mix('marketing/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ mix('marketing/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->

		@stack('css')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" style="background-image: url('{{ mix('marketing/assets/media/misc/page-bg.jpg') }}')" class="page-bg header-fixed header-tablet-and-mobile-fixed aside-enabled">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" class="header align-items-stretch" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
						<!--begin::Container-->
						<div class="header-container container-xxl d-flex align-items-center">
							<!--begin::Heaeder menu toggle-->
							<div class="d-flex topbar align-items-center d-lg-none ms-n2 me-3" title="Show aside menu">
								<div class="btn btn-icon btn-color-gray-900 w-30px h-30px" id="kt_header_menu_mobile_toggle">
									<i class="ki-duotone ki-abstract-14 fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</div>
							</div>
							<!--end::Heaeder menu toggle-->
							<!--begin::Header Logo-->
							<div class="header-logo me-5 me-md-10 flex-grow-1 flex-lg-grow-0">
								<a href="{{ route('dashboard') }}">
									<img alt="Logo" src="{{ mix('assets/media/logos/default.svg') }}" class="theme-light-show d-none d-lg-block h-30px" />
									<img alt="Logo" src="{{ mix('assets/media/logos/default-dark.svg') }}" class="theme-dark-show d-none d-lg-block h-30px" />
									<img alt="Logo" src="{{ mix('assets/media/logos/default-small.svg') }}" class="d-lg-none h-25px" />
								</a>
							</div>
							<!--end::Header Logo-->
                            <!--begin::Nav-->
                            <div class="ms-5 ms-md-10">
                                <!--begin::Toggle-->
                                <button type="button" class="btn btn-flex btn-secondary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between px-0 ps-md-6 pe-md-5 h-30px w-30px h-md-35px w-md-200px" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <span class="d-none d-md-inline">Transaksi 2024</span>
                                    <i class="ki-duotone ki-down fs-4 ms-2 ms-md-3 me-0"></i>
                                </button>
                                <!--end::Toggle-->
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-200px pb-3" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content fs-7 text-dark fw-bold px-3 py-4">Pilih Tahun Transaksi:</div>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator mb-3 opacity-75"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('dashboard') }}" class="menu-link px-3">
                                            <i class="ki-duotone ki-delivery-3 fs-4 ms-2 ms-md-3 me-0">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Transaksi 2024
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="https://store.liniswara.com/admin/1wire_rty/login" class="menu-link px-3">
                                            <i class="ki-duotone ki-delivery-3 fs-4 ms-2 ms-md-3 me-0">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Transaksi 2023
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Nav-->
							<!--begin::Wrapper-->
							<div class="d-flex align-items-stretch justify-content-end flex-lg-grow-1">
								@include('marketing.layouts.partials.menus')
								<!--begin::Toolbar wrapper-->
								<div class="topbar d-flex align-items-stretch flex-shrink-0">
									<!--begin::Theme mode-->
									<div class="d-flex align-items-center ms-3 ms-lg-5">
										<!--begin::Menu toggle-->
										<a href="#" class="btn btn-icon btn-topbar w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
											<i class="ki-duotone ki-night-day theme-light-show fs-1">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
												<span class="path4"></span>
												<span class="path5"></span>
												<span class="path6"></span>
												<span class="path7"></span>
												<span class="path8"></span>
												<span class="path9"></span>
												<span class="path10"></span>
											</i>
											<i class="ki-duotone ki-moon theme-dark-show fs-1">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</a>
										<!--begin::Menu toggle-->
										<!--begin::Menu-->
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-duotone ki-night-day fs-2">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
															<span class="path4"></span>
															<span class="path5"></span>
															<span class="path6"></span>
															<span class="path7"></span>
															<span class="path8"></span>
															<span class="path9"></span>
															<span class="path10"></span>
														</i>
													</span>
													<span class="menu-title">Light</span>
												</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-duotone ki-moon fs-2">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
													</span>
													<span class="menu-title">Dark</span>
												</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-duotone ki-screen fs-2">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
															<span class="path4"></span>
														</i>
													</span>
													<span class="menu-title">System</span>
												</a>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu-->
									</div>
									<!--end::Theme mode-->
									<!--begin::User-->
									<div class="d-flex align-items-center ms-3 ms-lg-5" id="kt_header_user_menu_toggle">
										<!--begin::Menu wrapper-->
										<div class="btn btn-icon w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                            <i class="ki-duotone ki-profile-circle fs-3x text-dark">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
										</div>
										<!--begin::User account menu-->
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<div class="menu-content d-flex align-items-center px-3">
													<!--begin::Avatar-->
													<div class="symbol symbol-50px me-5">
														<i class="ki-duotone ki-profile-circle fs-3x text-dark">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
													</div>
													<!--end::Avatar-->
													<!--begin::Username-->
													<div class="d-flex flex-column">
														<div class="fw-bold d-flex align-items-center fs-5">{{ auth('marketing')->user()->name }}</div>
														<div class="fw-semibold text-muted text-hover-primary fs-7">{{ auth('marketing')->user()->email }}</div>
													</div>
													<!--end::Username-->
												</div>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
											<!--begin::Menu item-->
											<div class="menu-item px-5">
												<a href="#" class="menu-link px-5">My Profile</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
											<!--begin::Menu item-->

                                            @if (session(config('session.app.selected_marketing_tim')))
                                                @php
                                                    $team = \App\Enums\Preorder\MarketingEnum::TEAM_A;
                                                @endphp
                                                <div class="menu-item px-5">
                                                    <a href="{{ route('marketing.switch_marketing', $team) }}" class="menu-link px-5">
                                                        Switch {{ \App\Enums\Preorder\MarketingEnum::fromValue($team)->getLabel() }}
                                                    </a>
                                                </div>
                                            @else
                                                @foreach ([
                                                    \App\Enums\Preorder\MarketingEnum::TEAM_A,
                                                    \App\Enums\Preorder\MarketingEnum::TEAM_B,
                                                ] as $team)
                                                    @php
                                                        if ($team == session(config('session.app.selected_marketing_tim'))->value) {
                                                            continue;
                                                        }
                                                    @endphp
                                                    <div class="menu-item px-5">
                                                        <a href="{{ route('marketing.switch_marketing', $team) }}" class="menu-link px-5">
                                                            Switch {{ \App\Enums\Preorder\MarketingEnum::fromValue($team)->getLabel() }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif

											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
											<!--begin::Menu item-->
											<div class="menu-item px-5">
												<a href="{{ route('auth.logout') }}" class="menu-link px-5">Keluar</a>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::User account menu-->
										<!--end::Menu wrapper-->
									</div>
									<!--end::User -->
								</div>
								<!--end::Toolbar wrapper-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
					<!--begin::Container-->
					<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
						<!--begin::Post-->
						<div class="content flex-row-fluid" id="kt_content">
							@yield('content')
						</div>
						<!--end::Post-->
					</div>
					<!--end::Container-->
					<!--begin::Footer-->
					<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
						<!--begin::Container-->
						<div class="container-xxl d-flex flex-column flex-md-row align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1">
								<span class="text-gray-700 fw-semibold me-1">{{ date('Y') }}&copy;</span>
								<a href="{{ route('marketing.dashboard') }}" class="text-gray-800 text-hover-primary">PT Lini Suara Nusantara</a>
							</div>
							<!--end::Copyright-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->

		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ mix('marketing/assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ mix('marketing/assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
        @stack('js-plugin')
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
        @stack('js')
        <!--end::Custom Javascript-->
        <!--end::Javascript-->

        @include('admin.layouts.partials.flash_message')
	</body>
	<!--end::Body-->
</html>
