@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $rider->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Sim History</span>
        {{-- <a href="/biketrack/public/admin/riders" class="btn btn-label-warning btn-bold btn-sm btn-icon-h kt-margin-l-10">
            View Rider Table
        </a> --}}
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
    @if($simHistory_count > 0)
{{-- active sim --}}
@foreach ($sim_history as $history)
@php
    $hasSim = App\Model\Sim\Sim::find($history->sim_id);
@endphp
@if ($history->status=='active')
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
                                  <h4>{{$hasSim['sim_company']}}-{{ $hasSim['sim_number'] }}</h4>
                                  <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">{{$history['status']}}</span></button>
                                {{-- <button class="btn btn-label-info btn-sm btn-upper"><span class="label label-info">{{$history->allowed_balance}}</span></button> --}}
                                </a>
        
                                <div class="kt-widget__action">
                                  
                                    <button onclick="deleteSim({{$rider->id}},{{$hasSim['id']}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                {{-- <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">sohaib</span></button> --}}
                                 </div>
                            </div>
        
                            <div class="kt-widget__subhead">
                                <a><i class="flaticon2-calendar-3"></i>Allowed Balance:&nbsp;{{$history->allowed_balance}} </a>
                                {{-- <a><i class="flaticon2-calendar-3"></i>{{ $bike1['availability'] }} </a> --}}
                                {{-- <a><i class="fa fa-motorcycle"></i>{{ $bike1['bike_number'] }}</a> --}}
                                @php
                                $mytimestamp = strtotime($history['created_at']);
                                $timestampupdated=strtotime($history['updated_at']);
                              @endphp
                                @if($history->status=='active')
                                 <h6 style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6> 
                            
                            @else
                            <h6 style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
                            @endif
                                
                            </div>
        
                           
                        </div>
                    </div>
                    
                  
                </div>
            </div>
        </div>
    </div>
    <!--end:: Widgets/Applications/User/Profile3-->    
</div>
@endif
@endforeach
{{-- End active sim --}}
{{-- Deactive sim --}}
@foreach ($sim_history as $history)
@php
    $hasSim = App\Model\Sim\Sim::find($history->sim_id);
@endphp
@if ($history->status=='deactive')
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
                                  <h4>{{$hasSim['sim_company']}}-{{ $hasSim['sim_number'] }}</h4>
                                  <button class="btn btn-label-danger btn-sm btn-upper"><span class="label label-danger">{{$history['status']}}</span></button>
                                  {{-- <button class="btn btn-label-info btn-sm btn-upper"><span class="label label-info"></span></button> --}}
                                  @if($hasSim['active_status']=="D")
                                    <button class="btn btn-label-warning btsssn-sm btn-upper"><span class="label label-warning">Deleted</span></button>
                                 @endif
                                  </a>
        
                                <div class="kt-widget__action">
                                  
                                    {{-- <button onclick="deleteBike({{$rider->id}},{{$hasSim['id']}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp; --}}
                                {{-- <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">{{$bike_id['status']}}</span></button> --}}
                                 </div>
                            </div>
        
                            <div class="kt-widget__subhead">
                                
                                <a><i class="flaticon2-calendar-3"></i>Allowed Balance:&nbsp;{{$history->allowed_balance}} </a>
                                {{-- <a><i class="fa fa-motorcycle"></i>{{ $bike1['bike_number'] }}</a> --}}
                                @php
                                $mytimestamp = strtotime($history['created_at']);
                                $timestampupdated=strtotime($history['updated_at']);
                              @endphp
                                @if($history->status=='active')
                                 <h6 style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6> 
                            
                            @else
                            <h6 style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
                            @endif
                                
                            </div>
        
                           
                        </div>
                    </div>
                    
                  
                </div>
            </div>
        </div>
    </div>
    <!--end:: Widgets/Applications/User/Profile3-->    
</div>
@endif
@endforeach
{{--End deactive sim --}}
@else
<div class="kt-section__content">
    <div class="alert alert-danger fade show" role="alert">
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text">No Sim history yet.</div>
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
    function deleteSim(sim_id, rider_id)
        {
            // console.log(client_id + ' , ' + rider_id);
            var url = "{{ url('admin/sim') }}" + "/" + rider_id + "/removeSim/"+sim_id ;
            console.log(url);
            sendDeleteRequest(url, true);
        }
</script>
@endsection