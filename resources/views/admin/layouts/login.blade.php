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

		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ mix('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ mix('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->

		@stack('css')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="auth-bg">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Logo-->
				<a href="{{ url('/') }}" class="d-block d-lg-none mx-auto py-20">
					<img alt="Logo" src="{{ mix('assets/media/logos/default.svg') }}" class="theme-light-show h-25px" />
					<img alt="Logo" src="{{ mix('assets/media/logos/default-dark.svg') }}" class="theme-dark-show h-25px" />
				</a>
				<!--end::Logo-->
				<!--begin::Aside-->
				<div class="d-flex flex-column-fluid flex-center w-lg-50 p-10">
					@yield('content')
				</div>
				<!--end::Aside-->
				<!--begin::Body-->
				<div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat" style="background-image: url({{ mix('assets/media/auth/bg11.png') }})"></div>
				<!--begin::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
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
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ mix('assets/js/custom/authentication/sign-in/general.js') }}"></script>
		<script src="{{ mix('assets/js/custom/authentication/sign-in/i18n.js') }}"></script>

		@stack('js')
		<!--end::Custom Javascript-->
		<!--end::Javascript-->

        @include('admin.layouts.partials.flash_message')
	</body>
	<!--end::Body-->
</html>
