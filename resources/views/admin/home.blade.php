@extends('admin.layouts.app')
@section('head')

@endsection
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
  
      
    <div class="kt-portlet">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#kt_tabs_4_1">Upcoming Expiries</a>
            </li>
        </ul>                    

        <div class="tab-content">
            <div class="tab-pane active" id="kt_tabs_4_1" role="tabpanel">
                    <div class="row">
                            {{-- <div class="col-md-6 col-lg-6 col-xl-6">
                                <!--begin:: Widgets/New Users-->
                                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                                    <div class="kt-portlet__head">
                                        <div class="kt-portlet__head-label">
                                            <h3 class="kt-portlet__head-title">
                                                Rider - Not logged in yet
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                                        <div class="kt-widget4">
                                            @if(count($notlogged_rider) > 0)
                                            @foreach ($notlogged_rider as $rider)
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
                                                            Start time: <span data-local-format="hh:MM TT" data-utc-to-local="{{Carbon\Carbon::parse($rider->start_time)->format('H:i:s')}}"><span>
                                                            
                                                        </p>
                                                        <p>
                                                            {{$rider->phone}}    
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
                                                Rider - Logged in
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                                        <div class="kt-widget4">
                                            @if(count($logged_rider) > 0)
                                            @foreach ($logged_rider as $rider_data)
                                                @php
                                                    $rider = $rider_data['rider'];
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
                                                            {{$rider_data['online_time']}}
                                                            
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
                            </div> --}}
                    
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
                                                            @php
                                                                $remaining_days = Carbon\Carbon::parse($rider_detail->visa_expiry)->diffInDays(Carbon\Carbon::now());
                                                            @endphp
                                                            @if ($remaining_days==0)
                                                            <strong>Expires today</strong>
                                                            @else
                                                            Expires: <strong>{{$remaining_days}} {{Str::plural('day', $remaining_days)}} remaining</strong>
                                                            @endif
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
                                                            @php
                                                                $remaining_days = Carbon\Carbon::parse($rider_detail->passport_expiry)->diffInDays(Carbon\Carbon::now());
                                                            @endphp
                                                            @if ($remaining_days==0)
                                                            <strong>Expires today</strong>
                                                            @else
                                                            Expires: <strong>{{$remaining_days}} {{Str::plural('day', $remaining_days)}} remaining</strong>
                                                            @endif
                                                            
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
                                                Bike - Mulkia expiry
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                                        <div class="kt-widget4">
                                            @php
                                            $__me_count = 0;
                                                foreach ($me__bikes as $bike) {
                                                    $__assign_bike = $bike->Assign_bike()->where('status', 'active')->get()->count();
                                                    if($__assign_bike > 0){
                                                        $__me_count++;
                                                    }
                                                }
                                            @endphp
                                            @if(count($me__bikes) > 0)
                                            @foreach ($me__bikes as $bike)
                                            
                                            @php
                                            // $assign_bike = $bike->Assign_bike()->where('status', 'active')->get()->first();
                                                // $rider = App\Model\Rider\Rider::find($assign_bike->rider_id);
                                            @endphp
                                                <div class="kt-widget4__item">
                                                    <div class="kt-widget4__pic kt-widget4__pic--pic">
                                                        @if($bike->profile_picture)
                                                            <img src="{{ asset(Storage::url($bike->profile_picture)) }}" alt="">
                                                        @else
                                                            <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" alt="">
                                                        @endif
                                                    </div>
                                                    <div class="kt-widget4__info">
                                                        <a href="{{ route('admin.rider.profile', $bike->id) }}" class="kt-widget4__username">
                                                            {{$bike->brand}} - {{$bike->model}}
                                                        </a>
                                                        <p class="kt-widget4__text">
                                                            {{$bike->bike_number}}
                                                            
                                                        </p>
                                                        <p>
                                                            @php
                                                                $remaining_days = Carbon\Carbon::parse($bike->mulkiya_expiry)->diffInDays(Carbon\Carbon::now());
                                                            @endphp
                                                            @if ($remaining_days==0)
                                                            <strong>Expires today</strong>
                                                            @else
                                                            Expires: <strong>{{$remaining_days}} {{Str::plural('day', $remaining_days)}} remaining</strong>
                                                            @endif
                                                        </p>							 		 
                                                    </div>						 
                                                    <a href="{{ route('admin.rider.profile', $rider->id) }}" class="btn btn-sm btn-label-brand btn-bold">View</a>						 
                                                </div> 
                                            @endforeach	
                                            @else
                                                <div class="alert alert-info">No Bikes Available</div>
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
                                                            @php
                                                                $remaining_days = Carbon\Carbon::parse($rider_detail->licence_expiry)->diffInDays(Carbon\Carbon::now());
                                                            @endphp
                                                            @if ($remaining_days==0)
                                                            <strong>Expires today</strong>
                                                            @else
                                                            Expires: <strong>{{$remaining_days}} {{Str::plural('day', $remaining_days)}} remaining</strong>
                                                            @endif
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
            </div>
        </div>      
    </div>
    <div>
            <div class="modal fade" id="pay_cash" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                          <h5 class="modal-title" id="exampleModalLabel">Pay Cash to Rider</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form class="kt-form" id="pay_cash_rider"  enctype="multipart/form-data">
                        <div class="container">
                            <div class="form-group">
                                <label>Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                            </div>
                            <div class="form-group">
                                <label>Amount:</label>
                                <input type="text" required class="form-control" name="amount" placeholder="Enter Amount">
                                </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea type="text"  rows="6" autocomplete="off" class="form-control" name="description" placeholder="Enter Details" ></textarea>
                            </div>
                            <div class="modal-footer border-top-0 d-flex justify-content-center">
                                <button class="upload-button btn btn-success">Submit</button>
                          </div>
                        </div>
                        </form>
                       </div>
                    </div>
                  </div>
               </div>

    


    

    <!--End::Dashboard 1-->
</div>

<!-- end:: Content -->
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>

@endsection