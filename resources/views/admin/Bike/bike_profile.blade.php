@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $rider->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Bike Profile</span>

        {{-- <a href="{{ route('bike.bike_assignRiders', $rider->id) }}" class="btn btn-label-warning btn-bold btn-sm btn-icon-h kt-margin-l-10">
            Assign Another Bike
        </a> --}}
        {{-- <a href="{{ route('Bike.assignedToRiders_History', $rider->id) }}" class="btn btn-label-danger btn-bold btn-sm btn-icon-h kt-margin-l-10">
        Bike_history.
        </a> --}}

        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div>
</div>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">
 <div class="row">
        <div class="col-xl-12">
        <!--begin:: Widgets/Applications/User/Profile3-->
        <div class="kt-portlet kt-portlet--height-fluid" >
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--user-profile-3">
                        <div class="kt-widget__top">
                            <div class="kt-widget__media kt-hidden-">
                                
                            </div>
                            <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                JM
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <a class="kt-widget__username">
                                      <h4> {{$bike_profile['brand']}}-{{ $bike_profile['model'] }}</h4>
                                        {{-- <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Active</span></button> --}}
                                        
                                    </a>
            
                                    <div class="kt-widget__action">
                                      
                                        <button onclick="deleteBike({{$rider->id}},{{$bike->id}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                        <a href="{{route('Bike.edit_bike',$bike_profile->id)}}"> <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Edit</span></button></a>
                                    </div>
                                </div>
            
                                <div class="kt-widget__subhead">
                                    
                                    <a><i class="flaticon2-calendar-3"></i>{{ $bike_profile['availability'] }} </a>
                                    <a><i class="fa fa-motorcycle"></i>{{ $bike_profile['bike_number'] }}</a>
                                    @php
                                      $mytimestamp = strtotime($bike_profile['created_at']);
                                    @endphp
                                    <h5 style="    text-align: -webkit-right;">{{gmdate("d-m-Y", $mytimestamp)}}</h5>
                                </div>
                        </div>
                        </div>
                         </div>
                </div>
            </div>
        </div>   
    </div>

   
</div>
<!-- end:: Content -->
@endsection
@section('foot')
    <script>
      
        function deleteBike(rider_id, bike_id)
        {
            // console.log(rider_id + ' , ' + bike_id);
            var url = "{{ url('admin/rider') }}" + "/" + rider_id + "/removeBike/"+bike_id ;
            console.log(url);
            sendDeleteRequest(url, true);
        }
    </script>
@endsection