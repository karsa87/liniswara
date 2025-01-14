<!--begin::Navbar-->
<div class="d-flex align-items-stretch" id="kt_header_nav">
    <!--begin::Menu wrapper-->
    <div class="header-menu align-items-stretch h-lg-75px" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
        <!--begin::Menu-->
        <div class="menu menu-rounded menu-sub-indention menu-column menu-lg-row menu-active-bg menu-title-gray-700 menu-arrow-gray-400 menu-state-primary fw-semibold my-5 my-lg-0 px-2 px-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
            <div class="menu-item me-0 me-lg-2 menu-lg-down-accordion {{ str(request()->route()->getName())->contains(['marketing.dashboard']) ? 'here show menu-here-bg' : '' }}">
                <!--begin:Menu link-->
                <a href="{{ route('marketing.dashboard') }}" class="menu-link py-3">
                    <span class="menu-title">Dashboard</span>
                    <span class="menu-arrow d-lg-none"></span>
                </a>
            </div>
            {{-- <div class="menu-item me-0 me-lg-2 menu-lg-down-accordion {{ str(request()->route()->getName())->contains(['marketing.payment']) ? 'here show menu-here-bg' : '' }}">
                <!--begin:Menu link-->
                <a href="{{ route('marketing.payment.transaction') }}" class="menu-link py-3">
                    <span class="menu-title">Pembayaran</span>
                    <span class="menu-arrow d-lg-none"></span>
                </a>
            </div> --}}
            <div class="menu-item me-0 me-lg-2 menu-lg-down-accordion {{ str(request()->route()->getName())->contains(['marketing.payment.region']) ? 'here show menu-here-bg' : '' }}">
                <!--begin:Menu link-->
                <a href="{{ route('marketing.payment.region') }}" class="menu-link py-3">
                    <span class="menu-title">List Area</span>
                    <span class="menu-arrow d-lg-none"></span>
                </a>
            </div>
            <div class="menu-item me-0 me-lg-2 menu-lg-down-accordion {{ str(request()->route()->getName())->contains(['marketing.payment.agent']) ? 'here show menu-here-bg' : '' }}">
                <!--begin:Menu link-->
                <a href="{{ route('marketing.payment.agent') }}" class="menu-link py-3">
                    <span class="menu-title">List Agent</span>
                    <span class="menu-arrow d-lg-none"></span>
                </a>
            </div>
            {{-- <div class="menu-item me-0 me-lg-2 menu-lg-down-accordion {{ str(request()->route()->getName())->contains(['marketing.transaction']) ? 'here show menu-here-bg' : '' }}">
                <!--begin:Menu link-->
                <a href="{{ route('marketing.transaction.index') }}" class="menu-link py-3">
                    <span class="menu-title">Status Transaksi</span>
                    <span class="menu-arrow d-lg-none"></span>
                </a>
            </div> --}}
            <div class="menu-item me-0 me-lg-2 menu-lg-down-accordion {{ str(request()->route()->getName())->contains(['marketing.stock']) ? 'here show menu-here-bg' : '' }}">
                <!--begin:Menu link-->
                <a href="{{ route('marketing.stock.index') }}" class="menu-link py-3">
                    <span class="menu-title">Stok Buku</span>
                    <span class="menu-arrow d-lg-none"></span>
                </a>
            </div>

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                <!--begin:Menu link-->
                <span class="menu-link py-3">
                    <span class="menu-title">Follow Up</span>
                    <span class="menu-arrow d-lg-none"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link py-3" href="{{ route('marketing.follow.transaction.index') }}" title="Menu setting pengingat pembayaran" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-title">Ingatkan Pembayaran</span>
                        </a>
                        <!--end:Menu link-->
                        <!--begin:Menu link-->
                        <a class="menu-link py-3" href="{{ route('marketing.maintanance') }}" title="Menu info progress agen" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-title">Progress Agen</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
            </div>
        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::Navbar-->
