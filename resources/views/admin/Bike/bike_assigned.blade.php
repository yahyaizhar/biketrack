@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $rider->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Bikes</span>

        <a href="{{ route('bike.bike_assignRiders', $rider->id) }}" class="btn btn-label-warning btn-bold btn-sm btn-icon-h kt-margin-l-10">
            Assign Another Bike
        </a>
        <a href="{{ route('Bike.assignedToRiders_History', $rider->id) }}" class="btn btn-label-danger btn-bold btn-sm btn-icon-h kt-margin-l-10">
        Bike_history.
        </a>

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
    
    @if($assign_bike > 0)
    
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
                                      <h4> {{$bike_id['brand']}}-{{ $bike_id['model'] }}</h4>
                                        <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Active</span></button>
                                        
                                    </a>
            
                                    <div class="kt-widget__action">
                                      
                                        <button onclick="deleteBike({{$rider->id}},{{$bike_id['id']}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                        {{-- <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Active</span></button> --}}
                                    </div>
                                </div>
            
                                <div class="kt-widget__subhead">
                                    
                                    <a><i class="flaticon2-calendar-3"></i>{{ $bike_id['availability'] }} </a>
                                    <a><i class="fa fa-motorcycle"></i>{{ $bike_id['bike_number'] }}</a>
                                    @php
                                      $mytimestamp = strtotime($bike_id['created_at']);
                                    @endphp
                                    <h5 style="    text-align: -webkit-right;">{{gmdate("d-m-Y", $mytimestamp)}}</h5>
                                </div>
            
                               
                            </div>
                        </div>
                        
                      
                    </div>
                </div>
            </div>
        </div>
        <!--end:: Widgets/Applications/User/Profile3-->    
    </div>

    @else
    <div class="kt-section__content">
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">No Bike assigned yet.</div>
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