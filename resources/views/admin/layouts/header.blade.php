<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

    <!-- begin:: Header Menu -->
    <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
            <ul class="kt-menu__nav ">
                <li class="kt-menu__item  kt-menu__item--open kt-menu__item--here kt-menu__item--submenu kt-menu__item--rel kt-menu__item--open kt-menu__item--here kt-menu__item--active" aria-haspopup="true"><a href="{{ route('admin.home') }}" class="kt-menu__link"><span class="kt-menu__link-text">Dashboard</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                </li>
            </ul>
        </div>
    </div>

    <!-- end:: Header Menu -->

    <!-- begin:: Header Topbar -->
    <div class="kt-header__topbar">
        <div class="kt-header__topbar-item dropdown">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="false">
                <span class="kt-header__topbar-icon">
                    <i class="flaticon2-bell-alarm-symbol"></i>
                    @if (count($notifications)>=1)
                    <span class="kt-badge kt-badge--dot kt-badge--notify kt-badge--sm kt-badge--brand"></span>
                    @endif
                </span>
            </div>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg" style="">
                    <div class="kt-head" style="background-image: url({{ asset('dashboard/assets/media/misc/bg-1.jpg') }})">
                        <h3 class="kt-head__title"style="color:white;">User Notifications</h3>
                        <div class="kt-head__sub" style="background-color:white; marin-top:12px;padding:4px;">
                            <span class="kt-head__desc">{{count($notifications)}} unread notifications</span>
                        </div>
                    </div>
                    <div class="kt-notification kt-margin-t-30 kt-margin-b-20 kt-scroll ps ps--active-y" data-scroll="true" data-height="270" data-mobile-height="220" style="height: 270px; overflow: hidden;">
                        @foreach ($notifications as $item)
                        @if ($item->desc=='skip_this_notification')
                            @continue
                        @endif
                            <a  class="kt-notification__item">
                                {{-- <div class="kt-notification__item-icon">
                                    <i class="flaticon2-line-chart kt-font-success"></i>
                                </div> --}}
                                <div class="kt-notification__item-details">
                                    <div class="kt-notification__item-title">
                                        {{-- {{$item->status}} --}}
                                        <span style="float:right;" onclick="notificationEnd({{$item->id}})"><i class="flaticon-cancel"></i></span>
                                    </div>
                                    <div class="kt-notification__item-title">
                                        {{$item->desc}}
                                    </div>
                                    <div class="kt-notification__item-time">
                                        {{carbon\carbon::parse($item->date_time)->diffForHumans(carbon\carbon::now()->format("Y-m-d"))}}
                                        @if ($item->action!=null && $item->status=="unread")
                                            <div style="float:right;" class="action__wrapper" data-id="{{$item->id}}">
                                                @php
                                                $button='';
                                                    foreach($item->action as $v){
                                                        $btn=$v['type'];
                                                        if ($btn=="button") {
                                                            $button=$v['value'];
                                                        }
                                                    }
                                                @endphp 
                                                {!!$button!!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__rail-y" style="top: 0px; right: 0px; height: 270px;">
                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 81px;"></div>
                        </div>
                    </div>
            </div>
        </div>
    {{-- </div> --}}


        <!--begin: User Bar -->
        <div class="kt-header__topbar-item kt-header__topbar-item--user">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                <div class="kt-header__topbar-user">
                <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                    <span class="kt-header__topbar-username kt-hidden-mobile">{{ Auth::user()->name }}</span>
                    <img class="kt-hidden" alt="Pic" src="{{ asset('dashboard/assets/media/users/300_25.jpg') }}" />

                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                    <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">{{ substr(Auth::user()->name, 0,1) }}</span>
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

                <!--begin: Head -->
                <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url({{ asset('dashboard/assets/media/misc/bg-1.jpg') }})">
                    <div class="kt-user-card__avatar">
                        <img class="kt-hidden" alt="Pic" src="{{ asset('dashboard/assets/media/users/300_25.jpg') }}" />

                        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                        <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ substr(Auth::user()->name, 0,1) }}</span>
                    </div>
                    <div class="kt-user-card__name">
                            {{ Auth::user()->name }}
                    </div>
                    <div class="kt-user-card__badge">
                        <a href="{{ route('admin.profile') }}" class="btn btn-success btn-sm btn-bold btn-font-md">Settings</a>
                    </div>
                </div>

                <!--end: Head -->

                <!--begin: Navigation -->
                <div class="kt-notification">
                    <div class="kt-notification__custom kt-space-between">
                        @php
                            $admins=Auth::user();
                        @endphp
                        <a href="{{ route('admin.logout') }}" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
                        @if ($admins->type=="su")
                        <a href="{{ route('Employee.showloginform') }}" class="btn btn-label btn-primary btn-sm btn-bold">Add Employee</a>      
                        <a href="{{ route('Employee.viewEmployee') }}" class="btn btn-label btn-primary btn-sm btn-bold">View Employee</a> 
                        @endif
                        {{-- <a href="demo1/custom/user/login-v2.html" target="_blank" class="btn btn-clean btn-sm btn-bold">Upgrade Plan</a> --}}
                    </div>
                </div>

                <!--end: Navigation -->
            </div>
        </div>

        <!--end: User Bar -->
    </div>

    <!-- end:: Header Topbar -->
</div>