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
        <meta name="file_upload_url" content="{{ route('file.upload') }}"/>
        <meta name="file_delete_url" content="{{ route('file.delete') }}"/>

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
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper" style="padding-left: 0px;">
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
							</div>
							<!--end::Brand-->
							<!--begin::Topbar-->
							<div class="d-flex align-items-center flex-shrink-0">
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
							</div>
							<!--end::Topbar-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
					<!--begin::Content wrapper-->
					<div class="d-flex flex-column-fluid">
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
		@stack('js')
		<!--end::Custom Javascript-->
		<!--end::Javascript-->

        <script>
            window.userPermissions = {};
        </script>
        @if (session('user-permission'))
            <script>
                window.userPermissions = $.parseJSON('{!! session('user-permission')->toJson() !!}');
            </script>
        @endif


        @include('admin.layouts.partials.flash_message')
	</body>
	<!--end::Body-->
</html>
