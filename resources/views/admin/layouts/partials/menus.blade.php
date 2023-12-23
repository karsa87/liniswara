<!--begin::Aside-->
<div id="kt_aside" class="aside card" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid px-4">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y mh-100 my-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="{default: '#kt_aside_footer', lg: '#kt_header, #kt_aside_footer'}" data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="{default: '5px', lg: '75px'}">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_aside_menu" data-kt-menu="true">
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="{{ url('/') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-home fs-2 text-info"></i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-dark">ADMINISTRASI</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->

                @if (auth()->user()->has('preorder-view'))
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-purchase fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Transaksi</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion {{ str(request()->route()->getName())->contains(['preorder.']) ? 'hover show' : '' }}">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['preorder.']) ? 'active here' : '' }}" href="{{ route('preorder.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Preorder</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link" href="#">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Pesanan</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link" href="#">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Pengiriman</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link" href="#">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Arsip</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                @endif

                <!--begin:Menu item-->
                {{-- <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-tag fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">Pembayaran</span>
                    </a>
                    <!--end:Menu link-->
                </div> --}}
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-delivery-3 fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">Retur</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-dark">DATA REFERENSI</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->

                @if (auth()->user()->has(['product-view', 'preorder_book-view', 'restock-view', 'category-view']))
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ str(request()->route()->getName())->contains(['category.','product.','restock.']) ? 'hover show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-book-open fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Katalog Produk</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @hasPermission('product-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['product.']) ? 'active here' : '' }}" href="{{ route('product.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Daftar Produk</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                        @hasPermission('preorder_book-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['preorder_book.']) ? 'active here' : '' }}" href="{{ route('preorder_book.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Buku Preorder</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                        @hasPermission('restock-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['restock.']) ? 'active here' : '' }}" href="{{ route('restock.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Re-Stok</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                        @hasPermission('category-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['category.']) ? 'active here' : '' }}" href="{{ route('category.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Kategori</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                @endif

                @if (auth()->user()->has(['branch-view', 'supplier-view', 'collector-view', 'expedition-view']))
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ str(request()->route()->getName())->contains(['branch.', 'supplier.', 'expedition.', 'collector.']) ? 'hover show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-category fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Master Data</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @hasPermission('expedition-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['expedition.']) ? 'active here' : '' }}" href="{{ route('expedition.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Ekspedisi</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                        @hasPermission('collector-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['collector.']) ? 'active here' : '' }}" href="{{ route('collector.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Daftar Penagih</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                        @hasPermission('supplier-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['supplier.']) ? 'active here' : '' }}" href="{{ route('supplier.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Daftar Pemasok</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                        @hasPermission('branch-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['branch.']) ? 'active here' : '' }}" href="{{ route('branch.index') }}"">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Gudang</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                @endif

                @hasPermission('customer-view')
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ str(request()->route()->getName())->contains(['customer.', 'customer.customer_address.']) ? 'active here' : '' }}" href="{{ route('customer.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-profile-user fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Data Agen</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @endhasPermission

                @if (auth()->user()->has(['permission-view', 'user-view', 'role-view']))
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ str(request()->route()->getName())->contains(['permission.', 'user.', 'role.']) ? 'hover show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-setting-2 fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">User & Hak Akses</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        @hasPermission('user-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['user.']) ? 'active here' : '' }}" href="{{ route('user.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">User</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                        @hasPermission('role-view')
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ str(request()->route()->getName())->contains(['role.']) ? 'active here' : '' }}" href="{{ route('role.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Hak Akses</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        @endhasPermission
                        @if (auth()->user()->isDeveloper())
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ str(request()->route()->getName())->contains(['permission.']) ? 'active here' : '' }}" href="{{ route('permission.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Permission</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        @endif
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                @endif

                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-dark">LAPORAN</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-chart-line fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Laporan</span>
                    </a>
                    <!--end:Menu link-->
                    @if (auth()->guard('marketing')->check())
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('marketing.dashboard') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-shop fs-2 text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                            </span>
                            <span class="menu-title">Marketing</span>
                        </a>
                        <!--end:Menu link-->
                    @endif
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-dark">PUSAT BANTUAN</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->

                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-book fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Aktifitas</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->

                {{-- <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ str(request()->route()->getName())->contains(['log.stock_product.']) ? 'active here' : '' }}" href="{{ route('log.stock_product.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-pointers fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">Log Stok Produk</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item--> --}}

                @if (auth()->user()->isDeveloper())
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ str(request()->route()->getName())->contains(['setting.']) ? 'active here' : '' }}" href="{{ route('setting.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-setting-2 fs-2 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Setting</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @endif
            </div>
            <!--end::Menu-->
        </div>
    </div>
    <!--end::Aside menu-->
</div>
<!--end::Aside-->
