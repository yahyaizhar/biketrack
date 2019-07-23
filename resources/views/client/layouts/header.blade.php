<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

    <!-- begin: Header Menu -->
    <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-tab ">
            <ul class="kt-menu__nav ">
                <li class="kt-menu__item @if(strpos(Request::url(), "client") !== false) kt-menu__item--active @endif " aria-haspopup="true"><a href="{{ route('client.home') }}" class="kt-menu__link "><span class="kt-menu__link-text">Dashboard</span></a></li>
                
                {{-- <li class="kt-menu__item @if(strpos(Request::url(), "client/profile") !== false) kt-menu__item--active @endif " aria-haspopup="true"><a href="{{ route('client.profile') }}" class="kt-menu__link "><span class="kt-menu__link-text">Profile</span></a></li> --}}
            </ul>
        </div>
    </div>

    <!-- end: Header Menu -->

    <!-- begin:: Header Topbar -->
    <div class="kt-header__topbar">
        <!--begin: User Bar -->
        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                <div class="kt-header__topbar-user">
                    <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                    <span class="kt-header__topbar-username kt-hidden-mobile" style="color:black;">{{ Auth::user()->name }}</span>
                    @if(Auth::user()->logo)
                        <img class="" alt="Pic" src="{{ asset(Storage::url(Auth::user()->logo)) }}" />
                    @else
                        <img class="" alt="Pic" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                    @endif
                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                    <span class="kt-hidden kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bolder">{{ substr(Auth::user()->name, 0, 2) }}</span>
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

                <!--begin: Head -->
                <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(./assets/media/misc/bg-1.jpg)">
                    <div class="kt-user-card__avatar">
                        @if(Auth::user()->logo)
                            <img class="" alt="Pic" src="{{ asset(Storage::url(Auth::user()->logo)) }}" />
                        @else
                            <img class="" alt="Pic" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                        @endif
                        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                        <span class="kt-hidden kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">S</span>
                    </div>
                    <div class="kt-user-card__name" style="color:gray;">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="kt-notification__custom kt-space-between">
                        <a class="btn btn-label btn-label-brand btn-sm btn-bold" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Sign Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    {{-- <div class="kt-user-card__badge">
                        <span class="btn btn-success btn-sm btn-bold btn-font-md">23 messages</span>
                    </div> --}}
                </div>

                <!--end: Head -->

                <!--begin: Navigation -->
                <div class="kt-notification">
                    <a href="{{ route('client.profile') }}" class="kt-notification__item">
                        <div class="kt-notification__item-icon">
                            <i class="flaticon2-calendar-3 kt-font-success"></i>
                        </div>
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title kt-font-bold">
                                My Profile
                            </div>
                            <div class="kt-notification__item-time">
                                Edit profile information and more...
                            </div>
                        </div>
                    </a>
                    {{-- <div class="kt-notification__custom kt-space-between">
                        <a class="btn btn-label btn-label-brand btn-sm btn-bold" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Sign Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div> --}}
                </div>

                <!--end: Navigation -->
            </div>
        </div>

        <!--end: User Bar -->
    </div>

    <!-- end:: Header Topbar -->
</div>