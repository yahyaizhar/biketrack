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

            <!--
<button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
-->
        </div>
    </div>

    <!-- end:: Aside -->

    <!-- begin:: Aside Menu -->
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
            <ul class="kt-menu__nav ">
                <li class="kt-menu__item  @if(substr(Request::url(), -6) == "/admin") kt-menu__item--active @endif " aria-haspopup="true"><a href="{{ route('admin.home') }}" class="kt-menu__link "><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon id="Bound" points="0 0 24 0 24 24 0 24" />
                        <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" id="Shape" fill="#000000" fill-rule="nonzero" />
                        <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" id="Path" fill="#000000" opacity="0.3" />
                    </g>
                </svg></span><span class="kt-menu__link-text">Dashboard</span></a></li>
                <li class="kt-menu__item @if(strpos(Request::url(), "admin/livemap") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.livemap') }}" class="kt-menu__link "><span class="kt-menu__link-icon">
                <i class="fa fa-map-marker-alt"></i>
            </span><span class="kt-menu__link-text">Live Map</span></a></li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "admin/rider") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon"><i class="fa fa-users"></i>    
                </span><span class="kt-menu__link-text">Riders</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Riders</span></span></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/riders/create") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.riders.create') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">New Rider</span></a></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/rider") !== false && strpos(Request::url(), "admin/riders/create") == false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.riders.index') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Riders</span></a></li>
                            {{-- <li class="kt-menu__item @if(strpos(Request::url(), "admin/rider/assign-area") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.assignArea') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Rider Area</span></a></li> --}}
                            {{-- <li class="kt-menu__item @if(strpos(Request::url(), "admin/rider/detail") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.Rider_details') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Rider Detail</span></a></li> --}}
                            {{-- <li class="kt-menu__item " aria-haspopup="true"><a href="{{ route('') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Riders List</span></a></li> --}}
                        </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "admin/client") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon">
                <i class="fa fa-hotel"></i>    
                </span><span class="kt-menu__link-text">Clients</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Clients</span></span></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/clients/create") !== false) kt-menu__item--active @endif " aria-haspopup="true"><a href="{{ route('admin.clients.create') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">New Client</span></a></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "admin/client") !== false && strpos(Request::url(), "admin/clients/create") == false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.clients.index') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Clients</span></a></li>
                        </ul>
                    </div>
                </li> 
                  <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "admin/bike") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon"><i class="fa fa-motorcycle"></i>    
                </span><span class="kt-menu__link-text">Bikes</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Bikes</span></span></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "/bike_login") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('bike.bike_login') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">New Bike</span></a></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "/bike_view") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('bike.bike_view') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bikes</span></a></li>
                            {{-- <li class="kt-menu__item @if(strpos(Request::url(), "/bike_assigned") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('bike.bike_assigned') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bike List</span></a></li> --}}
                            
                         </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "Salary") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon"><i class="fa fa-file-invoice"></i>    
                </span><span class="kt-menu__link-text">Accounts</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                        <ul class="kt-menu__subnav">
                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Accounts</span></span></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Add/Salary") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('account.new_salary') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add New Salary</span></a></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Month/Salary") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('account.month_salary') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Salary By Month</span></a></li>
                            <li class="kt-menu__item @if(strpos(Request::url(), "/Developer/Salary") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('account.developer_salary') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Salary By Developer</span></a></li>
                            {{-- <li class="kt-menu__item @if(strpos(Request::url(), "/Developer/Salary") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Salary By Developer</span></a></li> --}}
                            
                         </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "admin/NewComer") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon">
                    <i class="fa fa-user-plus"></i>    
                    </span><span class="kt-menu__link-text">New Comer</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">New Comer</span></span></li>
                                <li class="kt-menu__item @if(strpos(Request::url(), "admin/newComer/add") !== false) kt-menu__item--active @endif " aria-haspopup="true"><a href="{{ route('NewComer.form') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">New Comer</span></a></li>
                                <li class="kt-menu__item @if(strpos(Request::url(), "admin/NewComer") !== false && strpos(Request::url(), "admin/newComer/view") == false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('NewComer.view') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">NewComer Table</span></a></li>
                            </ul>
                        </div>
                    </li> 

                    <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "Sim") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <span class="kt-menu__link-icon"><i class="fa fa-sim-card"></i>    
                        </span><span class="kt-menu__link-text">Sims</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                <ul class="kt-menu__subnav">
                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Sims</span></span></li>
                                    <li class="kt-menu__item @if(strpos(Request::url(), "/create/Sim") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('Sim.new_sim') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add New Sim</span></a></li>
                                    <li class="kt-menu__item @if(strpos(Request::url(), "/view/records/Sim") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('Sim.view_records') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Sim List</span></a></li>
                                    {{-- <li class="kt-menu__item @if(strpos(Request::url(), "/create/Transaction/Sim") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('SimTransaction.create_sim') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add Sim Transaction</span></a></li> --}}
                                    <li class="kt-menu__item @if(strpos(Request::url(), "/view/Transaction/Sim") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('SimTransaction.view_records') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Transaction Records</span></a></li>
                                    
                                 </ul>
                            </div>
                        </li>

                        <li class="kt-menu__item  kt-menu__item--submenu @if(strpos(Request::url(), "mobile") !== false) kt-menu__item--active kt-menu__item--open @endif " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                <span class="kt-menu__link-icon"><i class="fa fa-mobile-alt"></i>    
                            </span><span class="kt-menu__link-text">Mobile</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Sims</span></span></li>
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobile/create") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('mobile.create_mobile_GET') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add New Mobile</span></a></li>
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobiles") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('mobile.show') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Mobiles List</span></a></li>
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobile/installment/create") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('MobileInstallment.create') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Installment</span></a></li>
                                        <li class="kt-menu__item @if(strpos(Request::url(), "/mobile/installment/show") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('MobileInstallment.show') }}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Installments</span></a></li>
                                        
                                     </ul>
                                </div>
                            </li>

                <li class="kt-menu__item @if(strpos(Request::url(), "admin/emails") !== false) kt-menu__item--active @endif  " aria-haspopup="true"><a href="{{ route('admin.emails.index') }}" class="kt-menu__link "><span class="kt-menu__link-icon">
                    <i class="fa fa-envelope"></i>
                </span><span class="kt-menu__link-text">Support Emails</span></a></li>
            </ul>
        </div>
    </div>

    <!-- end:: Aside Menu -->
</div>