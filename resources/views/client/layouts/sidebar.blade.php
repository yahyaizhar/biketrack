<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

    <!-- begin:: Aside -->
    <div class="kt-aside__brand kt-grid__item  " id="kt_aside_brand">
        <div class="kt-aside__brand-logo">
            <a href="{{ route('client.home') }}">
                {{-- <h2 style="font-weight:300; margin-top:5%;">DORBEAN</h2> --}}
                {{-- <img alt="Logo" style="width:100%" src="{{ asset('dashboard/assets/media/logos/dorbean-web-logo.png') }}" /> --}}
                <img alt="Logo" style="width:80%; margin-left: 10%;" src="{{ asset('dashboard/assets/media/logos/company-logo.png') }}" />
            </a>
        </div>
    </div>

    <!-- end:: Aside -->

    <!-- begin:: Aside Menu -->
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu  kt-aside-menu--dropdown " data-ktmenu-vertical="1" data-ktmenu-dropdown="1" data-ktmenu-scroll="0">
            <ul class="kt-menu__nav ">
                <li class="kt-menu__item @if(substr(Request::url(), -7) == "/client") kt-menu__item--active @endif" aria-haspopup="true"><a href="{{ route('client.home') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-dashboard"></i><span class="kt-menu__link-text">Dashboard</span></a></li>
                <li class="kt-menu__item @if(strpos(Request::url(), "client/rider") !== false) kt-menu__item--active @endif " aria-haspopup="true"><a href="{{ route('client.riders') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon2-list-2"></i><span class="kt-menu__link-text">Riders</span></a></li>
                <li class="kt-menu__item @if(strpos(Request::url(), "client/profile") !== false || strpos(Request::url(), "client/messageToSupport") !== false) kt-menu__item--active @endif " aria-haspopup="true"><a href="{{ route('client.profile') }}" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-profile"></i><span class="kt-menu__link-text">Profile</span></a></li>
            </ul>
        </div>
    </div>

    <!-- end:: Aside Menu -->
</div>