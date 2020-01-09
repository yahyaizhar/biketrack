<style>
    span.kt-menu__link-text {
        color:#5d78ff !important;
        font-size: 20px;
}
.kt-menu__subnav{
    /* border-left: 2px solid black; */
    margin-left:25px !important;
   
}
.kt-menu__subnav .kt-menu__subnav .kt-menu__link-text {
        font-size: 11px;
    color: #52ea81 !important;
}
.kt-menu__link {
    background-color: transparent  !important;
    padding-left: 20px !important;  
    padding: 0 10px !important;
}   
.kt-menu__subnav .kt-menu__link-text {
    color: #aaafac !important;
}
.kt-menu__ver-arrow{
color: #5d78ff !important;
}
.kt-menu__nav li.kt-menu__item.kt-menu__item--submenu{
    display:none;
}

</style>     
<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

    <!-- begin:: Aside -->
    <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
        <div class="kt-aside__brand-logo">
            <a href="{{ route('admin.home') }}">
                {{-- <h1 style="font-weight:300; margin-top:5%;">DORBEAN</h1> --}}
                {{-- <img alt="Logo" style="width:75%" src="{{ asset('dashboard/assets/media/logos/dorbean-web-logo.png') }}" /> --}}
                <img alt="Logo" style="width:35%; margin-left: 30%;" src="{{ asset('dashboard/assets/media/logos/company-logo.png') }}" />
            </a>
        </div>
        <div class="kt-aside__brand-tools">
            <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                            <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                            <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                        </g>
                    </svg></span>
                <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                            <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" />
                            <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                        </g>
                    </svg></span>
            </button>
        </div>
    </div>
@php
   $admin_type = Auth::user()->type;
   $is_admin = false;
   if($admin_type == "su"){
       $is_admin = true;
   }
   $users=Auth::user()->Role()->get()->toArray();
@endphp 
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu kt-aside-menu--dropdown" data-ktmenu-vertical="1" data-ktmenu-dropdown-timeout="500">
            <ul class="kt-menu__nav">
                    <li class="kt-menu__item" aria-haspopup="true">
                        <div class="form-group px-3">
                            <div class="input-group">
                                <input type="text" id="nav__rider_search_input" onkeyup="if(event.keyCode==13) {nav__search_rider_by_id()}" class="form-control" placeholder="Enter Rider id..">
                                <div class="input-group-append">
                                    <button class="btn btn-warning" onclick="nav__search_rider_by_id()" type="button">Find!</button>
                                </div>
                            </div>
                        </div>
                    </li>
                    @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.home';   }); @endphp
                    @if($is_in_array || $is_admin)
                <li class="kt-menu__item  @if(substr(Request::url(), -6) == "/admin") kt-menu__item--active @endif " aria-haspopup="true"><a href="{{ route('admin.home') }}" class="kt-menu__link "><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon id="Bound" points="0 0 24 0 24 24 0 24" />
                        <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" id="Shape" fill="#000000" fill-rule="nonzero" />
                        <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" id="Path" fill="#000000" opacity="0.3" />
                    </g>
                </svg></span><span class="kt-menu__link-text">Dashboard</span>
                     </a>
               </li>
               @endif
               
                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.livemap';   }); @endphp
                @if($is_in_array || $is_admin)
                <li class="kt-menu__item @if(strpos(Request::url(), "admin/livemap") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.livemap') }}" class="kt-menu__link "><span class="kt-menu__link-icon">
                <i class="fa fa-map-marker-alt"></i>
                </span><span class="kt-menu__link-text">Live Map</span>
                 </a>
                </li>
                @endif
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "admin/active_riders") !== false || strpos(Request::url(), "admin/rider/complete/detail/view") !== false || strpos(Request::url(), "admin/Salary/accounts/rider/account") !== false || strpos(Request::url(), "admin/Salary/accounts/rider/expense") !== false) kt-menu__item--active kt-menu__item--open @endif @if(strpos(Request::url(), "admin/rider") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon"><i class="fa fa-users"></i>    
                </span><span class="kt-menu__link-text">Riders</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Riders</span></span></li>
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.riders.create';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/riders/create") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('admin.riders.create') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">New Rider</span>
                                </a>
                            </li>
                            @endif

                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.riders.active';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/active_riders") !== false && strpos(Request::url(), "admin/riders/create") == false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a route_status="{{$is_in_array['action_name']}}" href="{{ route('admin.riders.active') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Active Riders</span>
                                </a>
                            </li>
                            @endif
                            
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.riders.index';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/rider") !== false && strpos(Request::url(), "admin/riders/create") == false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a route_status="{{$is_in_array['action_name']}}" href="{{ route('admin.riders.index') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">All Riders</span>
                                </a>
                            </li>
                            @endif
                           
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.rider_account';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Salary/accounts") !== false && strpos(Request::url(), "admin/Salary/accounts/rider/account") != false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a route_status="{{$is_in_array['action_name']}}" href="{{ route('admin.accounts.rider_account') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Riders Account</span>
                                </a>
                            </li>
                            @endif
                            
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.rider_expense_get';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Salary/accounts/rider/expense") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a route_status="{{$is_in_array['action_name']}}" href="{{ route('admin.accounts.rider_expense_get') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add Rider Expense</span>
                                </a>
                            </li>
                            @endif
                            
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.view_detail';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/rider/complete/detail/view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a route_status="{{$is_in_array['action_name']}}" href="{{ route('rider.view_detail') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Rider Detailed View</span>
                                </a>
                            </li>
                            @endif
                         </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if((strpos(Request::url(), "admin/client") !== false || strpos(Request::url(), "client_income") !== false) && strpos(Request::url(), "admin/client/ranges") === false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon">
                <i class="fa fa-hotel"></i>    
                </span><span class="kt-menu__link-text">Clients</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Clients</span></span></li>
                            
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.clients.create';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/clients/create") !== false) kt-menu__item--active @endif " aria-haspopup="true">
                                <a href="{{ route('admin.clients.create') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">New Client</span>
                                </a>
                            </li>
                            @endif

                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.clients.index';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/client") !== false && strpos(Request::url(), "admin/clients/create") == false && strpos(Request::url(), "client_income") == false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('admin.clients.index') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Clients</span>
                                </a>
                            </li>
                            @endif
                            
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.get_active_clients';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "active/client") !== false && strpos(Request::url(), "admin/clients/create") == false && strpos(Request::url(), "client_income") == false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('admin.get_active_clients') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Active Clients</span>
                                </a>
                            </li>
                            @endif
                            
                            <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "client_income") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                        <span></span>
                                    </i>
                                    <span class="kt-menu__link-text">Client Income</span>
                                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "client_income") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                       
                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.client_income_index';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "client_income/index") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                            <a href="{{ route('admin.client_income_index') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">Add Client Income</span>
                                            </a>
                                        </li>
                                        @endif

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.client_income_view';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "client_income/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                            <a href="{{ route('admin.client_income_view') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">View Client Income</span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div> 
                </li> 
                  <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "admin/bike") !== false || strpos(Request::url(), "/Salary/accounts/bike/account") !== false || strpos(Request::url(), "/workshop") !== false ||  strpos(Request::url(), "/maintenance") !== false)  kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon"><i class="fa fa-motorcycle"></i>    
                </span><span class="kt-menu__link-text">Bikes</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='bike.bike_login';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/bike_login") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('bike.bike_login') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">New Bike</span>
                                </a>
                            </li>
                            @endif
                            
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='bike.bike_view';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/bike_view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('bike.bike_view') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bikes</span>
                                </a>
                            </li>
                            @endif
                           
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='bike.bike_view_active';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/active/bike_view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('bike.bike_view_active') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Active Bikes</span>
                                </a>
                            </li>
                            @endif
                            
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.create_bike_rent';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/bike/rent/view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('admin.create_bike_rent') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add Bike Rent</span>
                                </a>
                            </li>
                            @endif
                            
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.bike_account';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Salary/accounts/bike/account") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('admin.accounts.bike_account') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bike Accounts</span>
                                </a>
                            </li>
                            @endif

                            <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/workshop") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                        <span></span>
                                    </i>
                                    <span class="kt-menu__link-text">Workshop</span>
                                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/workshop") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                       
                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.workshop_index';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/workshop/add") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                            <a href="{{ route('admin.accounts.workshop_index') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">Add Workshop</span>
                                            </a>
                                        </li>
                                        @endif

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.workshop_view';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/workshop/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                            <a href="{{ route('admin.accounts.workshop_view') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">View Workshop</span>
                                            </a>
                                        </li>
                                        @endif

                                    </ul>
                                </div>
                            </li>
                            
                            <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/maintenance") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                        <span></span>
                                    </i>
                                    <span class="kt-menu__link-text">Maintenance</span>
                                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/maintenance") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                        
                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.maintenance_index';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/maintenance/add") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                            <a href="{{ route('admin.accounts.maintenance_index') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">Add Maintenance</span>
                                            </a>
                                        </li>
                                        @endif

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.maintenance_view';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/maintenance/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                            <a href="{{ route('admin.accounts.maintenance_view') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">View Maintenance</span>
                                            </a>
                                        </li>
                                        @endif

                                    </ul>
                                </div>
                            </li>

                         </ul>
                    </div> 
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "zomato") !== false || strpos(Request::url(), "adt") !== false || strpos(Request::url(), "performance") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <i class="la la-cutlery"></i>    
                        </span>
                        <span class="kt-menu__link-text">Zomato</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.riderPerformance';   }); @endphp
                            @if($is_in_array || $is_admin)
                        <li class="kt-menu__item @if(strpos(Request::url(), "/client/rider/performance") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                            <a href="{{ route('admin.riderPerformance') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Rider Performance</span>
                            </a>
                        </li>
                        @endif

                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.ranges.adt';   }); @endphp
                        @if($is_in_array || $is_admin)
                        <li class="kt-menu__item @if(strpos(Request::url(), "/client/ranges/adt") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                            <a href="{{ route('admin.ranges.adt') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Rider ADT Performance</span>
                            </a>
                        </li>
                        @endif

                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.income_zomato_index';   }); @endphp
                        @if($is_in_array || $is_admin)
                        <li class="kt-menu__item @if(strpos(Request::url(), "/Salary/accounts/income/zomato/index") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                            <a href="{{ route('admin.accounts.income_zomato_index') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Income</span>
                            </a>
                        </li>
                        @endif

                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='zomato.view_riders_payouts_days';   }); @endphp
                        @if($is_in_array || $is_admin)
                        <li class="kt-menu__item @if(strpos(Request::url(), "/zomato/riders/payout/by/days") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                            <a href="{{ route('zomato.view_riders_payouts_days') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Riders Payouts by Days</span>
                            </a>
                        </li>
                        @endif

                    </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "CE") !== false || strpos(Request::url(), "company") !== false || strpos(Request::url(), "kr_investment") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <i class="flaticon2-shelter"></i>    
                        </span>
                        <span class="kt-menu__link-text">Company</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.company_account';   }); @endphp
                                @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Salary/accounts") !== false && strpos(Request::url(), "admin/generated/month/bills") != false&& strpos(Request::url(), "admin/Salary/accounts/company") != false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('admin.accounts.company_account') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Company-Rider Account</span>
                                </a>
                            </li>
                            @endif

                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.company_overall_report';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/company") !== false && strpos(Request::url(), "admin/company/overall/report") != false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('admin.accounts.company_overall_report') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Company Overall Reports</span>
                                </a>
                            </li>
                            @endif

                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='bills.rider_generated_bills';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/generated/month/bills") !== false && strpos(Request::url(), "admin/generated/month/bills") != false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('bills.rider_generated_bills') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bills Details</span>
                                </a>
                            </li>
                            @endif

                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='invoice.company_info';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/companyinfo") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                <a href="{{ route('invoice.company_info') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add Company Info</span>
                                </a>
                            </li>
                            @endif


                            <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/kr_investment") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                        <span></span>
                                    </i>
                                    <span class="kt-menu__link-text">Company Investment</span>
                                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/kr_investment") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.kr_investment_index';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/kr_investment/add") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                            <a href="{{ route('admin.accounts.kr_investment_index') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">Add Company Investment</span>
                                            </a>
                                        </li>
                                        @endif

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.kr_investment_view';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/kr_investment/view") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                            <a href="{{ route('admin.accounts.kr_investment_view') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">View Company Investment</span>
                                            </a>
                                        </li>
                                        @endif

                                    </ul>
                                </div>
                            </li>

                            <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/CE") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                        <span></span>
                                    </i>
                                    <span class="kt-menu__link-text">Company Expense</span>
                                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/CE") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.CE_index';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/CE/index") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                            <a href="{{ route('admin.CE_index') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">Add Company Expense</span>
                                            </a>
                                        </li>
                                        @endif

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.CE_view';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/CE/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                            <a href="{{ route('admin.CE_view') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">View Company Expense</span>
                                            </a>
                                        </li>
                                        @endif

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.CE_report';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/CE/report") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                            <a href="{{ route('admin.CE_report') }}" class="kt-menu__link ">
                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                <span class="kt-menu__link-text">View Company Expense Report</span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "/Add/Salary") !== false || strpos(Request::url(), "/Month/Salary") !== false || strpos(Request::url(), "/Developer/Salary") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <i class="la la-money"></i>    
                        </span>
                        <span class="kt-menu__link-text">Salary</span>
                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='account.new_salary';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Add/Salary") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                <a href="{{ route('account.new_salary') }}" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">Create Salary</span>
                                </a>
                            </li>
                            @endif

                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='account.month_salary';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Month/Salary") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                <a href="{{ route('account.month_salary') }}" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">View Salary By Month</span>
                                </a>
                            </li>
                            @endif

                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='account.developer_salary';   }); @endphp
                            @if($is_in_array || $is_admin)
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Developer/Salary") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                <a href="{{ route('account.developer_salary') }}" class="kt-menu__link ">
                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                    <span class="kt-menu__link-text">View Salary By Rider</span>
                                </a>
                            </li>
                        @endif
                            
                        </ul> 
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "accounts") !== false && strpos(Request::url(), "/Salary/accounts/bike/account") == false && strpos(Request::url(), "zomato") === false && strpos(Request::url(), "/admin/Salary/accounts/rider/expense") === false && strpos(Request::url(), "/admin/Salary/accounts/rider/account") === false && strpos(Request::url(), "CE") === false && strpos(Request::url(), "company") === false && strpos(Request::url(), "workshop") === false && strpos(Request::url(), "fuel_expense") === false  && strpos(Request::url(), "maintenance") === false || strpos(Request::url(), "/fuel_expense") !== false || strpos(Request::url(), "/salik") !== false ) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon"><i class="la la-credit-card"></i>    
                    </span><span class="kt-menu__link-text">Expense</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Accounts</span></span></li>
                                
                                
                                

                                <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/id-charges") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">Id Charges</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/id-charges") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.id_charges_index';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/id-charges") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                                <a href="{{ route('admin.accounts.id_charges_index') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Add Charges</span>
                                                </a>
                                            </li>
                                            @endif

                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.id_charges_view';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/id-charges/view") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                                <a href="{{ route('admin.accounts.id_charges_view') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">View Charges</span>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>

                                

                                <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/edirham") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">Edirham</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/edirham") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.edirham_index';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/edirham/add") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                                <a href="{{ route('admin.accounts.edirham_index') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Add Edirham</span>
                                                </a>
                                            </li>
                                            @endif

                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.accounts.edirham_view';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/edirham/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                                <a href="{{ route('admin.accounts.edirham_view') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">View Edirham</span>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>

                                
 
                                <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/wps") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">WPS</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/wps") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.wps_index';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/wps/index") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                                <a href="{{ route('admin.wps_index') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Add WPS</span>
                                                </a>
                                            </li>
                                            @endif

                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.wps_view';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/wps/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                                <a href="{{ route('admin.wps_view') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">View WPS</span>
                                                </a>
                                            </li>
                                            @endif

                                        </ul>
                                    </div>
                                </li>

                                <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/AR") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">Advance & Return</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/AR") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                            
                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.AR_index';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/AR/index") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                                <a href="{{ route('admin.AR_index') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Add Advance & Return</span>
                                                </a>
                                            </li>
                                            @endif

                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.AR_view';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/AR/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                                <a href="{{ route('admin.AR_view') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">View Advance & Return</span>
                                                </a>
                                            </li>
                                            @endif

                                        </ul>
                                    </div>
                                </li>
                                <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "salik") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                <span></span>
                                            </i>
                                            <span class="kt-menu__link-text">Salik</span>
                                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                        </a>
                                        <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "salik") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='salik.add_salik';   }); @endphp
                                                @if($is_in_array || $is_admin)
                                                <li class="kt-menu__item @if(strpos(Request::url(), "admin/add/salik") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                                    <a href="{{ route('salik.add_salik') }}" class="kt-menu__link ">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                        <span class="kt-menu__link-text">Add Salik</span>
                                                    </a>
                                                </li>
                                                @endif

                                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.salik';   }); @endphp
                                                @if($is_in_array || $is_admin)
                                                <li class="kt-menu__item @if(strpos(Request::url(), "admin/salik") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                                    <a href="{{ route('admin.salik') }}" class="kt-menu__link ">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                        <span class="kt-menu__link-text">View Salik</span>
                                                    </a>
                                                </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/fuel_expense") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                            <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                <span></span>
                                            </i>
                                            <span class="kt-menu__link-text">Fuel Expense</span>
                                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                        </a>
                                        <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/fuel_expense") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                            <ul class="kt-menu__subnav">
                                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                                
                                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.fuel_expense_create';   }); @endphp
                                                @if($is_in_array || $is_admin)
                                                <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/fuel_expense/create") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                                    <a href="{{ route('admin.fuel_expense_create') }}" class="kt-menu__link ">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                        <span class="kt-menu__link-text">Add Fuel Expense</span>
                                                    </a>
                                                </li>
                                                @endif
                                               
                                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.fuel_expense_view';   }); @endphp
                                                @if($is_in_array || $is_admin)
                                                <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/fuel_expense/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                                    <a href="{{ route('admin.fuel_expense_view') }}" class="kt-menu__link ">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                        <span class="kt-menu__link-text">View Fuel Expense</span>
                                                    </a>
                                                </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </li>
                                    <li class="kt-menu__item kt-menu__item--submenu @if(strpos(Request::url(), "/accounts/BF") !== false) kt-menu__item--active kt-menu__item--open @endif "  aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="kt-menu__link-text">Bike Fine</span>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="kt-menu__submenu " kt-hidden-height="160" @if(strpos(Request::url(), "/accounts/BF/index") === false)style="display: none; overflow: hidden;"@endif><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"></span></span></li>
                                            
                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.BF_index';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/BF/index") !== false) kt-menu__item--active @endif" aria-haspopup="true">
                                                <a href="{{ route('admin.BF_index') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">Add Bike Fine</span>
                                                </a>
                                            </li>
                                            @endif

                                            @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.BF_view';   }); @endphp
                                            @if($is_in_array || $is_admin)
                                            <li class="kt-menu__item @if(strpos(Request::url(), "/accounts/BF/view") !== false) kt-menu__item--active @endif   " aria-haspopup="true">
                                                <a href="{{ route('admin.BF_view') }}" class="kt-menu__link ">
                                                    <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                                    <span class="kt-menu__link-text">View Bike Fine</span>
                                                </a>
                                            </li>
                                            @endif

                                        </ul>
                                    </div>
                                </li>
                             </ul> 
                        </div>
                    </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "admin/newComer") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon">
                    <i class="fa fa-user-plus"></i>    
                    </span><span class="kt-menu__link-text">New Comer</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">New Comer</span></span></li>
                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='NewComer.form';   }); @endphp
                                @if($is_in_array || $is_admin)
                                <li class="kt-menu__item @if(strpos(Request::url(), "admin/newComer/add") !== false) kt-menu__item--active @endif " aria-haspopup="true">
                                    <a href="{{ route('NewComer.form') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">New Comer</span>
                                    </a>
                                </li>
                                @endif
                               
                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='NewComer.approval';   }); @endphp
                                @if($is_in_array || $is_admin)
                                <li class="kt-menu__item @if(strpos(Request::url(), "admin/newComer/approval") !== false && strpos(Request::url(), "admin/newComer/approval") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                    <a href="{{ route('NewComer.approval') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Approval Pending Comer</span>
                                    </a>
                                </li>
                                @endif
                                
                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='NewComer.view';   }); @endphp
                                @if($is_in_array || $is_admin)
                                <li class="kt-menu__item @if(strpos(Request::url(), "admin/newComer/view") !== false && strpos(Request::url(), "admin/newComer/view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                    <a href="{{ route('NewComer.view') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">NewComer Table</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "Sim") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon"><i class="fa fa-sim-card"></i>    
                        </span><span class="kt-menu__link-text">Sims</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Sims</span></span></li>
                                    @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='Sim.new_sim';   }); @endphp
                                     @if($is_in_array || $is_admin)
                                    <li class="kt-menu__item @if(strpos(Request::url(), "/create/Sim") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                        <a href="{{ route('Sim.new_sim') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add New Sim</span>
                                        </a>
                                    </li>
                                    @endif

                                    @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='Sim.view_records';   }); @endphp
                                    @if($is_in_array || $is_admin)
                                    <li class="kt-menu__item @if(strpos(Request::url(), "/view/records/Sim") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                        <a href="{{ route('Sim.view_records') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Sim List</span>
                                        </a>
                                    </li>
                                    @endif

                                    @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='Sim.view_records_sim_active';   }); @endphp
                                    @if($is_in_array || $is_admin)
                                    <li class="kt-menu__item @if(strpos(Request::url(), "/view/records/Sim/active") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                        <a href="{{ route('Sim.view_records_sim_active') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Active Sim List</span>
                                        </a>
                                    </li>
                                    @endif

                                    @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='SimTransaction.create_sim';   }); @endphp
                                    @if($is_in_array || $is_admin)
                                    <li class="kt-menu__item @if(strpos(Request::url(), "/create/Transaction/Sim") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                        <a href="{{ route('SimTransaction.create_sim') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add Sim Transaction</span>
                                        </a>
                                    </li>
                                    @endif

                                    @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='SimTransaction.view_records';   }); @endphp
                                    @if($is_in_array || $is_admin)
                                    <li class="kt-menu__item @if(strpos(Request::url(), "/view/Transaction/Sim") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                        <a href="{{ route('SimTransaction.view_records') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Transaction Records</span>
                                        </a>
                                    </li>
                                    @endif
                                    
                                 </ul>
                            </div>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "mobile") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-mobile-alt"></i>    
                            </span><span class="kt-menu__link-text">Mobile</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Sims</span></span></li>
                                        

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='mobile.show';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobiles") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                            <a href="{{ route('mobile.show') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Mobiles List</span>
                                            </a>
                                        </li>
                                        @endif
                                       
                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='MobileInstallment.create';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobile/installment/create") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                            <a href="{{ route('MobileInstallment.create') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Installment</span>
                                            </a>
                                        </li>
                                        @endif

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='mobile.sellers_view';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobile/sellers/view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                            <a href="{{ route('mobile.sellers_view') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Sellers</span>
                                            </a>
                                        </li>
                                        @endif

                                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='mobile.accessory_view';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobile/accessory/view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                            <a href="{{ route('mobile.accessory_view') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Accessory</span>
                                            </a>
                                        </li>
                                        @endif

                                        {{-- @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='Mobile.profit_loss_view';   }); @endphp
                                        @if($is_in_array || $is_admin)
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobile/profit_loss/sheet") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                            <a href="{{ route('Mobile.profit_loss_view') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Profit & Loss sheet</span>
                                            </a>
                                        </li>
                                        @endif --}}
                                       
                                        
                                     </ul>
                                </div>
                            </li>

                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "tax") !== false || strpos(Request::url(), "invoice") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">
                                <i class="fa fa-file-invoice"></i>    
                            </span>
                            <span class="kt-menu__link-text">Invoices</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">

                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='tax.view_invoices';   }); @endphp
                                @if($is_in_array || $is_admin)
                                <li class="kt-menu__item @if(strpos(Request::url(), "/invoice/view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                    <a href="{{ route('tax.view_invoices') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Invoices</span>
                                    </a>
                                </li>
                                @endif
                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='invoice.add_tax_method';   }); @endphp
                                @if($is_in_array || $is_admin)
                                <li class="kt-menu__item @if(strpos(Request::url(), "/invoice/tax_method/add") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                    <a href="{{ route('invoice.add_tax_method') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add Tax Method</span>
                                    </a>
                                </li>
                                @endif

                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='invoice.add_bank_account';   }); @endphp
                                @if($is_in_array || $is_admin)
                                <li class="kt-menu__item @if(strpos(Request::url(), "/invoice/bank_account/add") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                    <a href="{{ route('invoice.add_bank_account') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bank Account</span>
                                    </a>
                                </li>
                                @endif

                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='invoice.invoive_payments';   }); @endphp
                                @if($is_in_array || $is_admin)
                                <li class="kt-menu__item @if(strpos(Request::url(), "/invoice/payments/view") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                    <a href="{{ route('invoice.invoive_payments') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Invoice Payments</span>
                                    </a>
                                </li>
                                @endif

                            </ul> 
                        </div>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "employee") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon">
                                <i class="fa fa-file-invoice"></i>    
                            </span>
                            <span class="kt-menu__link-text">Employees</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='employee.salary_generated';   }); @endphp
                                @if($is_in_array || $is_admin)
                                <li class="kt-menu__item @if(strpos(Request::url(), "/employee/salary_generate") !== false) kt-menu__item--active @endif  " aria-haspopup="true">
                                    <a href="{{ route('employee.salary_generated') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Employees Accounts</span>
                                    </a>
                                </li>
                                @endif
                            </ul> 
                        </div>
                    </li>
                    @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.emails.index';   }); @endphp
                    @if($is_in_array || $is_admin)
                    <li class="kt-menu__item @if(strpos(Request::url(), "admin/emails") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.emails.index') }}" class="kt-menu__link "><span class="kt-menu__link-icon">
                            <i class="fa fa-envelope"></i>
                        </span><span class="kt-menu__link-text">Support Emails</span>
                      </a>
                    </li>
                        @endif

                        @php $is_in_array = Arr::first($users, function ($item) { return $item['action_name']=='admin.activity.view';   }); @endphp
                        @if($is_in_array || $is_admin)
                        <li class="kt-menu__item @if(strpos(Request::url(), "admin/activity") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.activity.view') }}" class="kt-menu__link "><span class="kt-menu__link-icon">
                            <i class="fa fa-file-invoice"></i>
                        </span><span class="kt-menu__link-text">Activity Log View</span>
                           </a>
                       </li>
                        @endif

            </ul>
        </div>
    </div>
  
    <!-- end:: Aside Menu -->
</div>
