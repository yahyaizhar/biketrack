@extends('client.layouts.app')
@section('main-content')

    
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

    <!--Begin::Dashboard 3-->

    <!--Begin::Section-->
    <div class="row mt-minus-60">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <!--Begin::Portlet-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head kt-portlet__head--noborder">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
            
                        </h3>
                    </div>
                    {{-- <div class="kt-portlet__head-toolbar">
                        <a href="#" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                            <i class="flaticon-more-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="kt-nav">
                                <li class="kt-nav__item">
                                    <a href="{{ route('client.profile.edit') }}" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-settings"></i>
                                        <span class="kt-nav__link-text">Settings</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="{{ route('client.profile.edit') }}" class="kt-nav__link">
                                        <i class="kt-nav__link-icon fa fa-headphones"></i>
                                        <span class="kt-nav__link-text">Contact Support</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                </div>
                <div class="kt-portlet__body">
                    <!--begin::Widget -->
                    <div class="kt-widget kt-widget--user-profile-2">
                        <div class="kt-widget__head">
                            <div class="kt-widget__media">
                                @if(Auth::user()->logo)
                                    <img class="kt-widget__img kt-hidden-" src="{{ asset(Storage::url(Auth::user()->logo)) }}" alt="image">
                                @else
                                    <img class="kt-widget__img kt-hidden-" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" alt="image">
                                @endif
                                {{-- <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                    MP
                                </div> --}}
                            </div>
                            <div class="kt-widget__info">
                                <a href="{{ route('client.profile') }}" class="kt-widget__username">
                                    {{ Auth::user()->name }}                                                
                                </a>
                                <span class="kt-widget__desc">
                                    {{ Auth::user()->address }}
                                </span>
                            </div>
                        </div>
            
                        <div class="kt-widget__body">
            
                            <div class="kt-widget__item">
                                <div class="kt-widget__contact" style="display:block;">
                                    <span class="kt-widget__label">Email:</span>
                                    <a href="mailto:{{ Auth::user()->email }}" class="kt-widget__data">{{ Auth::user()->email }}</a>
                                </div>
                                <div class="kt-widget__contact" style="display:block;">
                                    <span class="kt-widget__label">Phone:</span>
                                    <a class="kt-widget__data">{{ Auth::user()->phone }}</a>
                                </div>
                            </div>
                        </div>
            
                        <div class="kt-widget__footer">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <a href="{{ route('client.profile.edit') }}" class="btn btn-label-success btn-lg btn-upper">Edit Profile</a>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <a href="{{ route('client.messageToSupport') }}" class="btn btn-label-success btn-lg btn-upper">Contact Support</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Widget -->
                </div>
            </div>
            <!--End::Portlet--> 
            
        </div>
    </div>

    <!--End::Section-->

    <!--End::Dashboard 3-->
</div>
@endsection