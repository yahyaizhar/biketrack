@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $bike_id->brand }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Rider History</span>

        {{-- <a href="{{ route('bike.bike_assignRiders', $rider->id) }}" class="btn btn-label-warning btn-bold btn-sm btn-icon-h kt-margin-l-10">
            Assign bike
        </a> --}}
        {{-- <a href="{{ route('bike.bike_assigned', $rider->id) }}" class="btn btn-label-success btn-bold btn-sm btn-icon-h kt-margin-l-10">
            Show Active Bike
        </a> --}}
        

        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div>
</div> <!-- begin:: Content -->
 <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">
    
    @if($assign_rider_id_count > 0)
    @foreach ($assign_rider as $riders_id)
        
   
    @php
       $rider1 = App\Model\Rider\Rider::find($riders_id->rider_id);
    
   @endphp
   
   @if ($riders_id->status=='active')
   <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    
    <div class="row">
        <div class="col-xl-12">
        <!--begin:: Widgets/Applications/User/Profile3-->
        <div class="kt-portlet kt-portlet--height-fluid" >
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--user-profile-3">
                        <div class="kt-widget__top">
                            {{-- <div class="kt-widget__media kt-hidden-">
                                @if($rider->profile_picture)
                                    <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="image">
                                @else
                                    <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                            </div> --}}
                            {{-- <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                JM
                            </div> --}}
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <a class="kt-widget__username">
                                        {{ $rider1->name }}
                                        @if ($riders_id->status=='active')
                                            <i class="flaticon2-correct"></i>                                            
                                        @endif
                                       </a>
            
                                    <div class="kt-widget__action">
                                        <a href="{{ route('admin.riders.edit', $rider1->id) }}" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp;
                                        <a href="{{ route('admin.rider.location', $rider1->id) }}" class="btn btn-label-danger btn-sm btn-upper">View Location</a>&nbsp;
                                        {{-- <button class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp; --}}
                                    </div>
                                </div>
            
                                <div class="kt-widget__subhead">
                                    <a href="mailto:{{ $rider1->email }}"><i class="flaticon2-new-email"></i>{{ $rider1->email }}</a>
                                    <a><i class="flaticon2-calendar-3"></i>{{ $rider1->phone }} </a>
                                    <a><i class="fa fa-motorcycle"></i>{{ $rider1->vehicle_number }}</a>
                                </div>
            
                                <div class="kt-widget__info">
                                    <i class="flaticon-location"></i>&nbsp;
                                    <div class="kt-widget__desc">
                                        {{ $rider1->address }}
                                        @php
                                  $mytimestamp = strtotime($riders_id['created_at']);
                                  $timestampupdated=strtotime($riders_id['updated_at']);
                                @endphp
                                        @if($riders_id->status=='active')
                                            <h6 style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6>
                                    @else
                                    <h6 style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="kt-widget__bottom">
                            <div class="kt-widget__item col-md-10">
                                <textarea class="form-control" id="message_{{ $rider1->id }}" name="message_{{ $rider1->id }}" placeholder="Enter message here"></textarea>
                            </div>
                            <div class="kt-widget__item">
                                <button onclick="sendSMS({{$rider1->id}})" class="btn btn-label-success btn-sm btn-upper">Send SMS</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end:: Widgets/Applications/User/Profile3-->    
    </div>
</div>

   @endif
     @endforeach
 
    @foreach ($assign_rider as $rider_id)
   @php
       $rider = App\Model\Rider\Rider::find($rider_id->rider_id);
    
   @endphp
   @if ($rider_id->status=='active')
   
   @else
   <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    
    <div class="row">
        <div class="col-xl-12">
        <!--begin:: Widgets/Applications/User/Profile3-->
        <div class="kt-portlet kt-portlet--height-fluid" >
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--user-profile-3">
                        <div class="kt-widget__top">
                            {{-- <div class="kt-widget__media kt-hidden-">
                                @if($rider->profile_picture)
                                    <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="image">
                                @else
                                    <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                            </div> --}}
                            {{-- <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                JM
                            </div> --}}
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <a class="kt-widget__username">
                                        {{ $rider->name }}
                                        @if ($rider_id->status=='active')
                                            <i class="flaticon2-correct"></i>                                            
                                        @endif
                                       </a>
            
                                    <div class="kt-widget__action">
                                        <a href="{{ route('admin.riders.edit', $rider->id) }}" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp;
                                        <a href="{{ route('admin.rider.location', $rider->id) }}" class="btn btn-label-danger btn-sm btn-upper">View Location</a>&nbsp;
                                        {{-- <button class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp; --}}
                                    </div>
                                </div>
            
                                <div class="kt-widget__subhead">
                                    <a href="mailto:{{ $rider->email }}"><i class="flaticon2-new-email"></i>{{ $rider->email }}</a>
                                    <a><i class="flaticon2-calendar-3"></i>{{ $rider->phone }} </a>
                                    <a><i class="fa fa-motorcycle"></i>{{ $rider->vehicle_number }}</a>
                                </div>
            
                                <div class="kt-widget__info">
                                    <i class="flaticon-location"></i>&nbsp;
                                    <div class="kt-widget__desc">
                                        {{ $rider->address }}
                                        @php
                                  $mytimestamp = strtotime($rider_id['created_at']);
                                  $timestampupdated=strtotime($rider_id['updated_at']);
                                @endphp
                                        @if($rider_id->status=='active')
                                            <h6 style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6>
                                    @else
                                    <h6 style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="kt-widget__bottom">
                            <div class="kt-widget__item col-md-10">
                                <textarea class="form-control" id="message_{{ $rider->id }}" name="message_{{ $rider->id }}" placeholder="Enter message here"></textarea>
                            </div>
                            <div class="kt-widget__item">
                                <button onclick="sendSMS({{$rider->id}})" class="btn btn-label-success btn-sm btn-upper">Send SMS</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end:: Widgets/Applications/User/Profile3-->    
    </div>
</div>

   @endif
<!-- begin:: Content -->
        
     @endforeach
    @else 
    <div class="kt-section__content">
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">No Rider history yet.</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    </div>
     @endif
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
            if(data.message == '')
            {
                swal.fire("Message Empty!", "Please enter some message.", "error");
            }
            else
            {
                // console.log(data.message);
                sendRequest(url, method, data, true, false, null, true);
                $(textbox_id).val('');
            }
        }
        function deleteBike(rider_id, bike_id)
        {
            // console.log(client_id + ' , ' + rider_id);
            var url = "{{ url('admin/rider') }}" + "/" + rider_id + "/removeBike/"+bike_id ;
            console.log(url);
            sendDeleteRequest(url, true);
        }
    </script>
@endsection