@extends('admin.layouts.app')
@section('main-content')

<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

    <!--Begin::Dashboard 3-->

    <!--Begin::Section-->
    <div class="col-sm-12 col-md-12 col-lg-12">
        <!--Begin::Portlet-->
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-portlet__head kt-portlet__head--noborder">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
        
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="{{ route('admin.clients.riders', $client->id) }}" class="btn btn-primary">
                        <i class="fa fa-motorcycle"></i> View Riders
                    </a>&nbsp;
                    <a href="#" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                        <i class="flaticon-more-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a href="{{ route('admin.clients.edit', $client->id) }}" class="kt-nav__link">
                                    <i class="kt-nav__link-icon flaticon2-settings"></i>
                                    <span class="kt-nav__link-text">Edit</span>
                                </a>
                                <a href="{{ route('admin.clients.riders', $client->id) }}" class="kt-nav__link">
                                    <i class="kt-nav__link-icon fa fa-motorcycle"></i>
                                    <span class="kt-nav__link-text">View Riders</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin::Widget -->
                <div class="kt-widget kt-widget--user-profile-2">
                    <div class="kt-widget__head">
                        <div class="kt-widget__media">
                            @if($client->logo)
                                <img class="kt-widget__img kt-hidden-" src="{{ asset(Storage::url($client->logo)) }}" />
                            @else
                                <img class="kt-widget__img kt-hidden-" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                            @endif
                            {{-- <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                MP
                            </div> --}}
                        </div>
                        <div class="kt-widget__info">
                            <a href="{{ route('client.profile') }}" class="kt-widget__username">
                                {{ $client->name }}                                                
                            </a>
                            <span class="kt-widget__desc">
                                {{ $client->address }}
                            </span>
                        </div>
                    </div>
        
                    <div class="kt-widget__body">
        
                        {{-- <div class="kt-widget__content">
                            <div class="kt-widget__stats kt-margin-r-20">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-piggy-bank"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Earnings</span>
                                    <span class="kt-widget__value"><span>$</span>249,500</span>
                                </div>
                            </div>
        
                            <div class="kt-widget__stats">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-pie-chart"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Net</span>
                                    <span class="kt-widget__value"><span>$</span>84,060</span>
                                </div>
                            </div>
                        </div> --}}
        
                        <div class="kt-widget__item">
                            <div class="kt-widget__contact" style="display:block;">
                                <span class="kt-widget__label">Email:</span>
                                <a href="mailto:{{ $client->email }}" class="kt-widget__data">{{ $client->email }}</a>
                            </div>
                            <div class="kt-widget__contact" style="display:block;">
                                <span class="kt-widget__label">Phone:</span>
                                <a class="kt-widget__data">{{ $client->phone }}</a>
                            </div>
                        </div>
                    </div>
        
                    <div class="kt-widget__footer">
                        <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-label-success btn-lg btn-upper">Edit Profile</a>
                    </div>
                </div>
                <!--end::Widget -->
            </div>
        </div>
        <!--End::Portlet--> 
        
    </div>

    <!--End::Section-->

    <!--End::Dashboard 3-->
</div>
<!-- end:: Content -->
@endsection
@section('foot')
    <script>
        function sendSMS(id)
        {
            var rider_id = id;
            var textbox_id = "#message_"+id;
            var url = "{{ url('admin/rider') }}" + "/" + id + "/sendMessage";
            var method = 'POST';
            // console.log(textbox_id);
            // console.log($(textbox_id).val());
            var data = {
                'message' : $(textbox_id).val()
            }
            // console.log(data.message);
            sendRequest(url, method, data, true, false, null, true);
            $(textbox_id).val('');
        }
    </script>
@endsection