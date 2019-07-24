@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

    <!--Begin::Dashboard 1-->
    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

        <!--begin:: Widgets/Stats-->
        <div class="kt-portlet">
            <div class="kt-portlet__body  kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-xl">
                    
                    <div class="col-md-12 col-lg-3 col-xl-3">

                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <a href="{{ route('admin.livemap') }}" class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        Active Riders
                                    </h4>
                                    {{-- <span class="kt-widget24__desc">
                                        Rider currently online
                                    </span> --}}
                                    <span class="kt-widget24__stats kt-font-danger">
                                        {{ $online_riders }}
                                    </span>
                                </a>
                            </div>
                            
                        </div>

                        <!--end::New Orders-->
                    </div>
                    <div class="col-md-12 col-lg-3 col-xl-3">

                        <!--begin::New Users-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <a href="{{ route('admin.livemap') }}" class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        Active Clients 
                                    </h4>
                                    <span class="kt-widget24__stats kt-font-success">
                                        {{ $clients_online }}
                                    </span>
                                </a>
                            </div>
                        </div>

                        <!--end::New Users-->
                    </div>
                    <div class="col-md-12 col-lg-3 col-xl-3">

                        <!--begin::Total Profit-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <a href="{{ route('admin.riders.index') }}" class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        Total Riders
                                    </h4>
                                    <span class="kt-widget24__stats kt-font-brand">
                                        {{$riders}}
                                    </span>
                                </a>
                            </div>
                        </div>

                        <!--end::Total Profit-->
                    </div>
                    <div class="col-md-12 col-lg-3 col-xl-3">

                        <!--begin::New Feedbacks-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <a href="{{ route('admin.clients.index') }}" class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        Total Clients
                                    </h4>
                                    <span class="kt-widget24__stats kt-font-warning">
                                        {{ $clients }}
                                    </span>
                                </a>
                            </div>

                        </div>

                        <!--end::New Feedbacks-->
                    </div>
                </div>
            </div>
        </div>

        <!--end:: Widgets/Stats-->
    </div>


    <div class="row">
        <div class="col-md-6 col-lg-6 col-xl-6">
            <!--begin:: Widgets/New Users-->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Rider - Visa expiry
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget4">
                        @if(count($ve__riders) > 0)
                        @foreach ($ve__riders as $rider_detail)
                        @php
                            $rider = App\Model\Rider\Rider::find($rider_detail->rider_id);
                        @endphp
                            <div class="kt-widget4__item">
                                <div class="kt-widget4__pic kt-widget4__pic--pic">
                                    @if($rider->profile_picture)
                                        <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="">
                                    @else
                                        <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" alt="">
                                    @endif
                                </div>
                                <div class="kt-widget4__info">
                                    <a href="{{ route('admin.rider.profile', $rider->id) }}" class="kt-widget4__username">
                                        {{$rider->name}}
                                    </a>
                                    <p class="kt-widget4__text">
                                        {{$rider->email}}
                                        
                                    </p>
                                    <p>
                                        Expires: <strong>{{Carbon\Carbon::parse($rider_detail->visa_expiry)->format('F d, Y')}}</strong>
                                    </p>							 		 
                                </div>						 
                                <a href="{{ route('admin.rider.profile', $rider->id) }}" class="btn btn-sm btn-label-brand btn-bold">View</a>						 
                            </div> 
                        @endforeach	
                        @else
                            <div class="alert alert-info">No Riders Available</div>
                        @endif				 
                    </div>             
                </div>
            </div>
        <!--end:: Widgets/New Users-->	
        </div>

        <div class="col-md-6 col-lg-6 col-xl-6">
            <!--begin:: Widgets/New Users-->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Rider - Passport expiry
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget4">
                        @if(count($pe__riders) > 0)
                        @foreach ($pe__riders as $rider_detail)
                        @php
                            $rider = App\Model\Rider\Rider::find($rider_detail->rider_id);
                        @endphp
                            <div class="kt-widget4__item">
                                <div class="kt-widget4__pic kt-widget4__pic--pic">
                                    @if($rider->profile_picture)
                                        <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="">
                                    @else
                                        <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" alt="">
                                    @endif
                                </div>
                                <div class="kt-widget4__info">
                                    <a href="{{ route('admin.rider.profile', $rider->id) }}" class="kt-widget4__username">
                                        {{$rider->name}}
                                    </a>
                                    <p class="kt-widget4__text">
                                        {{$rider->email}}
                                        
                                    </p>
                                    <p>
                                        Expires: <strong>{{Carbon\Carbon::parse($rider_detail->passport_expiry)->format('F d, Y')}}</strong>
                                    </p>							 		 
                                </div>						 
                                <a href="{{ route('admin.rider.profile', $rider->id) }}" class="btn btn-sm btn-label-brand btn-bold">View</a>						 
                            </div> 
                        @endforeach	
                        @else
                            <div class="alert alert-info">No Riders Available</div>
                        @endif				 
                    </div>             
                </div>
            </div>
        <!--end:: Widgets/New Users-->	
        </div>

        <div class="col-md-6 col-lg-6 col-xl-6">
            <!--begin:: Widgets/New Users-->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Rider - Mulkia expiry
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget4">
                        @if(count($me__riders) > 0)
                        @foreach ($me__riders as $rider_detail)
                        @php
                            $rider = App\Model\Rider\Rider::find($rider_detail->rider_id);
                        @endphp
                            <div class="kt-widget4__item">
                                <div class="kt-widget4__pic kt-widget4__pic--pic">
                                    @if($rider->profile_picture)
                                        <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="">
                                    @else
                                        <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" alt="">
                                    @endif
                                </div>
                                <div class="kt-widget4__info">
                                    <a href="{{ route('admin.rider.profile', $rider->id) }}" class="kt-widget4__username">
                                        {{$rider->name}}
                                    </a>
                                    <p class="kt-widget4__text">
                                        {{$rider->email}}
                                        
                                    </p>
                                    <p>
                                        Expires: <strong>{{Carbon\Carbon::parse($rider_detail->mulkiya_expiry)->format('F d, Y')}}</strong>
                                    </p>							 		 
                                </div>						 
                                <a href="{{ route('admin.rider.profile', $rider->id) }}" class="btn btn-sm btn-label-brand btn-bold">View</a>						 
                            </div> 
                        @endforeach	
                        @else
                            <div class="alert alert-info">No Riders Available</div>
                        @endif				 
                    </div>             
                </div>
            </div>
        <!--end:: Widgets/New Users-->	
        </div>

        <div class="col-md-6 col-lg-6 col-xl-6">
            <!--begin:: Widgets/New Users-->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Rider - Licence expiry
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget4">
                        @if(count($le__riders) > 0)
                        @foreach ($le__riders as $rider_detail)
                        @php
                            $rider = App\Model\Rider\Rider::find($rider_detail->rider_id);
                        @endphp
                            <div class="kt-widget4__item">
                                <div class="kt-widget4__pic kt-widget4__pic--pic">
                                    @if($rider->profile_picture)
                                        <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="">
                                    @else
                                        <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" alt="">
                                    @endif
                                </div>
                                <div class="kt-widget4__info">
                                    <a href="{{ route('admin.rider.profile', $rider->id) }}" class="kt-widget4__username">
                                        {{$rider->name}}
                                    </a>
                                    <p class="kt-widget4__text">
                                        {{$rider->email}}
                                        
                                    </p>
                                    <p>
                                        Expires: <strong>{{Carbon\Carbon::parse($rider_detail->licence_expiry)->format('F d, Y')}}</strong>
                                    </p>							 		 
                                </div>						 
                                <a href="{{ route('admin.rider.profile', $rider->id) }}" class="btn btn-sm btn-label-brand btn-bold">View</a>						 
                            </div> 
                        @endforeach	
                        @else
                            <div class="alert alert-info">No Riders Available</div>
                        @endif				 
                    </div>             
                </div>
            </div>
        <!--end:: Widgets/New Users-->	
        </div>

    </div>


    

    <!--End::Dashboard 1-->
</div>

<!-- end:: Content -->
@endsection