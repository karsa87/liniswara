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
		<link href="{{ mix('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ mix('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->

		@stack('css')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed">
        <!--begin::Page loading(append to body)-->
        <div class="page-loader flex-column bg-dark bg-opacity-25">
            <span class="spinner-border text-primary" role="status"></span>
            <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
        </div>
        <!--end::Page loading-->

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
					<div id="kt_header" class="header">
						<!--begin::Container-->
						<div class="container-fluid d-flex flex-stack">
							<!--begin::Brand-->
							<div class="d-flex align-items-center me-5">
								<!--begin::Aside toggle-->
								<div class="d-lg-none btn btn-icon btn-active-color-white w-30px h-30px ms-n2 me-3" id="kt_aside_toggle">
									<i class="ki-duotone ki-abstract-14 fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</div>
								<!--end::Aside  toggle-->
								<!--begin::Logo-->
								<a href="{{ route('dashboard') }}">
									<img alt="Logo" src="{{ mix('assets/media/logos/default-small.svg') }}" class="h-25px h-lg-30px" />
								</a>
								<!--end::Logo-->
								<!--begin::Nav-->
								<div class="ms-5 ms-md-10">
									<!--begin::Toggle-->
									<button type="button" class="btn btn-flex btn-active-color-white align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-white bg-opacity-10 btn-color-white px-0 ps-md-6 pe-md-5 h-30px w-30px h-md-35px w-md-200px" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
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
							</div>
							<!--end::Brand-->
							<!--begin::Topbar-->
							<div class="d-flex align-items-center flex-shrink-0">
								<!--begin::Activities-->
								<div class="d-flex align-items-center ms-1">
									<!--begin::Drawer toggle-->
									<div class="btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-30px h-30px h-40px w-40px" id="kt_activities_toggle">
										<i class="ki-duotone ki-chart-simple fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</div>
									<!--end::Drawer toggle-->
								</div>
								<!--end::Activities-->
								<!--begin::Quick links-->
								<div class="d-flex align-items-center ms-1">
									<!--begin::Menu wrapper-->
									<div class="btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-30px h-30px h-40px w-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-element-11 fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</div>
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column w-250px w-lg-325px" data-kt-menu="true">
										<!--begin::Heading-->
										<div class="d-flex flex-column flex-center bgi-no-repeat rounded-top px-9 py-10" style="background-image:url('{{ mix('assets/media/misc/menu-header-bg.jpg') }}')">
											<!--begin::Title-->
											<h3 class="text-white fw-semibold mb-3">Quick Links</h3>
											<!--end::Title-->
											<!--begin::Status-->
											<span class="badge bg-primary text-inverse-primary py-2 px-3">25 pending tasks</span>
											<!--end::Status-->
										</div>
										<!--end::Heading-->
										<!--begin:Nav-->
										<div class="row g-0">
											<!--begin:Item-->
											<div class="col-6">
												<a href="../../demo14/dist/apps/projects/budget.html" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-end border-bottom">
													<i class="ki-duotone ki-dollar fs-3x text-primary mb-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
													<span class="fs-5 fw-semibold text-gray-800 mb-0">Accounting</span>
													<span class="fs-7 text-gray-400">eCommerce</span>
												</a>
											</div>
											<!--end:Item-->
											<!--begin:Item-->
											<div class="col-6">
												<a href="../../demo14/dist/apps/projects/settings.html" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-bottom">
													<i class="ki-duotone ki-sms fs-3x text-primary mb-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													<span class="fs-5 fw-semibold text-gray-800 mb-0">Administration</span>
													<span class="fs-7 text-gray-400">Console</span>
												</a>
											</div>
											<!--end:Item-->
											<!--begin:Item-->
											<div class="col-6">
												<a href="../../demo14/dist/apps/projects/list.html" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-end">
													<i class="ki-duotone ki-abstract-41 fs-3x text-primary mb-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													<span class="fs-5 fw-semibold text-gray-800 mb-0">Projects</span>
													<span class="fs-7 text-gray-400">Pending Tasks</span>
												</a>
											</div>
											<!--end:Item-->
											<!--begin:Item-->
											<div class="col-6">
												<a href="../../demo14/dist/apps/projects/users.html" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light">
													<i class="ki-duotone ki-briefcase fs-3x text-primary mb-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													<span class="fs-5 fw-semibold text-gray-800 mb-0">Customers</span>
													<span class="fs-7 text-gray-400">Latest cases</span>
												</a>
											</div>
											<!--end:Item-->
										</div>
										<!--end:Nav-->
										<!--begin::View more-->
										<div class="py-2 text-center border-top">
											<a href="../../demo14/dist/pages/user-profile/activity.html" class="btn btn-color-gray-600 btn-active-color-primary">View All
											<i class="ki-duotone ki-arrow-right fs-5">
												<span class="path1"></span>
												<span class="path2"></span>
											</i></a>
										</div>
										<!--end::View more-->
									</div>
									<!--end::Menu-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::Quick links-->
								<!--begin::Theme mode-->
								<div class="d-flex align-items-center ms-1">
									<!--begin::Menu toggle-->
									<a href="#" class="btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-30px h-30px h-40px w-40px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
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
								<div class="d-flex align-items-center ms-1" id="kt_header_user_menu_toggle">
									<!--begin::User info-->
									<div class="btn btn-flex align-items-center bg-hover-white bg-hover-opacity-10 py-2 px-2 px-md-3" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<!--begin::Name-->
										<div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2 me-md-4">
											<span class="text-muted fs-8 fw-semibold lh-1 mb-1">
                                                {{ auth()->user()->name }}
                                            </span>
											<span class="text-white fs-8 fw-bold lh-1">
                                                {{ optional(optional(auth()->user()->role)->first())->name }}
                                            </span>
										</div>
										<!--end::Name-->
										<!--begin::Symbol-->
										<div class="symbol symbol-30px symbol-md-40px">
											<img src="{{ mix('assets/media/avatars/300-1.jpg') }}" alt="image" />
										</div>
										<!--end::Symbol-->
									</div>
									<!--end::User info-->
									<!--begin::User account menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<!--begin::Avatar-->
												<div class="symbol symbol-50px me-5">
													<img alt="Logo" src="{{ mix('assets/media/avatars/300-1.jpg') }}" />
												</div>
												<!--end::Avatar-->
												<!--begin::Username-->
												<div class="d-flex flex-column">
													<div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}</div>
                                                    <div class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</div>
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
										<div class="menu-item px-5">
											<a href="{{ route('auth.logout') }}" class="menu-link px-5">Keluar</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::User account menu-->
								</div>
								<!--end::User -->
							</div>
							<!--end::Topbar-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
					<!--begin::Content wrapper-->
					<div class="d-flex flex-column-fluid">
						@include('admin.layouts.partials.menus')
						<!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid">
                            @include('admin.layouts.partials.breadcrumb')

							<!--begin::Post-->
							<div class="content flex-column-fluid" id="kt_content">
								<!--begin::Row-->
                                @yield('content')
								<!--end::Row-->
							</div>
							<!--end::Post-->
							<!--begin::Footer-->
							<div class="footer py-4 d-flex flex-column flex-md-row flex-stack" id="kt_footer">
								<!--begin::Copyright-->
								<div class="text-dark order-2 order-md-1">
									<span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span>
								    <a href="{{ route('dashboard') }}" class="text-gray-800 text-hover-primary">PT Lini Suara Nusantara</a>
								</div>
								<!--end::Copyright-->
							</div>
							<!--end::Footer-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Content wrapper-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->

		<!--begin::Drawers-->
		<!--begin::Activities drawer-->
		<div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '900px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">
			<div class="card shadow-none border-0 rounded-0">
				<!--begin::Header-->
				<div class="card-header" id="kt_activities_header">
					<h3 class="card-title fw-bold text-dark">Activity Logs</h3>
					<div class="card-toolbar">
						<button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_activities_close">
							<i class="ki-duotone ki-cross fs-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</button>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body position-relative" id="kt_activities_body">
                    @php
                        $histories = [];
                        if (auth()->user()) {
                            $histories = \App\Models\LogHistory::where('user_id', auth()->user()->id)->orderBy('id','desc')->limit(10)->get();
                        }
                    @endphp
					<!--begin::Content-->
					<div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body" data-kt-scroll-dependencies="#kt_activities_header, #kt_activities_footer" data-kt-scroll-offset="5px">
						<!--begin::Timeline items-->
						<div class="timeline">
                            @foreach ($histories as $history)
                                <!--begin::Timeline item-->
                                <div class="timeline-item mt-5">
                                    <!--begin::Timeline line-->
                                    <div class="timeline-line w-40px"></div>
                                    <!--end::Timeline line-->
                                    <!--begin::Timeline icon-->
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light">
                                            @switch($history->transaction_type)
                                                @case(1)
                                                    <i class="ki-duotone ki-pencil fs-2 text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    @break

                                                @case(2)
                                                    <i class="ki-duotone ki-pencil fs-2 text-primary">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    @break

                                                @case(3)
                                                    <i class="ki-duotone ki-trash fs-2 text-danger">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    @break

                                                @default

                                            @endswitch
                                        </div>
                                    </div>
                                    <!--end::Timeline icon-->
                                    <!--begin::Timeline content-->
                                    <div class="timeline-content mt-n2">
                                        <!--begin::Timeline heading-->
                                        <div class="overflow-auto pe-3">
                                            <!--begin::Title-->
                                            <div class="fs-5 fw-semibold mb-2">{{ $history->information }}</div>
                                            <!--end::Title-->
                                            <!--begin::Description-->
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <!--begin::Info-->
                                                <div class="text-muted me-2 fs-7">at {{ \Carbon\Carbon::parse($history->log_datetime)->format('H:i') }} by </div>
                                                <!--end::Info-->
                                                <!--begin::User-->
                                                <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top" title="{{ optional($history->user)->name }}">
                                                    {{ optional($history->user)->name }}
                                                </div>
                                                <!--end::User-->
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
						<!--end::Timeline items-->
					</div>
					<!--end::Content-->
				</div>
				<!--end::Body-->
				<!--begin::Footer-->
				<div class="card-footer py-5 text-center" id="kt_activities_footer">
					<a href="{{ route('log.history.index') }}" class="btn btn-bg-body text-primary">Lihat Semua Aktifitas
					<i class="ki-duotone ki-arrow-right fs-3 text-primary">
						<span class="path1"></span>
						<span class="path2"></span>
					</i></a>
				</div>
				<!--end::Footer-->
			</div>
		</div>
		<!--end::Activities drawer-->
		<!--end::Drawers-->
		<!--end::Main-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ mix('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ mix('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
        @stack('js-plugin')
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ mix('assets/js/widgets.bundle.js') }}"></script>
		<script src="{{ mix('assets/js/custom/widgets.js') }}"></script>
		<script src="{{ mix('assets/js/custom/apps/chat/chat.js') }}"></script>
		<script src="{{ mix('assets/js/custom/utilities/modals/create-app.js') }}"></script>
		<script src="{{ mix('assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
		<script src="{{ mix('assets/js/custom/utilities/modals/users-search.js') }}"></script>

		@stack('js')
		<!--end::Custom Javascript-->
		<!--end::Javascript-->

        @include('admin.layouts.partials.flash_message')
	</body>
	<!--end::Body-->
</html>
