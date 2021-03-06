@extends('admin.layouts.app')
@section('main-content')
<script>
    var deleteRecord =function(rider_id,history_id){
        var url = "{{ url('admin/sim_history') }}" + "/" + history_id;
        swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this record?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url : url,
                    type : 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {            
                        $('.loading').show();
                    },
                    complete: function(){
                        $('.loading').hide();
                    },
                    success: function(data){
                        console.log(data);
                        swal.fire({
                            position: 'center',
                            type: 'success',
                            title: 'Record deleted successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    },
                    error: function(error){
                        swal.fire({
                            position: 'center',
                            type: 'error',
                            title: 'Oops...',
                            text: 'Unable to deleted.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    }
                });
            }
    });
    }
function updateDates(rider_id,assign_sim_id,created,updated,status){  
    $("#sim_status").modal("show");
    $('input[name="rider_id"]').val(rider_id);
    $('input[name="assign_sim_id"]').val(assign_sim_id);
    $('#sim_status input[name="created_at"]').attr('data-month', created);
    $('#sim_status input[name="updated_at"]').attr('data-month', updated);
    $("#unassign_date").show();
    if (status=="active") {
        $("#unassign_date").hide();
    }
    biketrack.refresh_global();
    $("form#active_sim_status").on("submit",function(e){
        e.preventDefault();
        var _form = $(this);
        var rider_id=$("#rider_id").val();
        var assign_sim_id=$("#assign_sim_id").val();
        var _modal = _form.parents('.modal');
        var url = "{{ url('admin/change/sim') }}" + "/" + rider_id + "/history" + "/" + assign_sim_id +"/"+status ;
        swal.fire({
            title: 'Are you sure?',
            text: "You want update status!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!'
        }).then(function(result) {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                url : url,
                type : 'GET',
                data: _form.serializeArray(),
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    console.log(data);
                    _modal.modal('hide');
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(error){
                    _modal.modal('hide');
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to update.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}); 
}
</script>
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
                                  <h4><a href="{{route('Sim.edit_sim_view',$hasSim['id'])}}">{{$hasSim['sim_company']}}-{{ $hasSim['sim_number'] }}</a></h4>
                                  <a class="kt-widget__username">
                                  <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">{{$history['status']}}</span></button>
                                {{-- <button class="btn btn-label-info btn-sm btn-upper"><span class="label label-info">{{$history->allowed_balance}}</span></button> --}}
                                </a> 
                            </div>
                                <div class="kt-widget__action">
                                  
                                    <button onclick="deleteSim({{$rider->id}},{{$hasSim['id']}})" class="btn btn-label-info btn-sm btn-upper">Unassign Sim</button>&nbsp;
                                {{-- <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">sohaib</span></button> --}}
                                 </div>
                            <div class="kt-widget__subhead">
                                <a onclick="updateAllowedBalance({{$history->allowed_balance}},{{$rider->id}},{{$history->id}},this)"><i class="flaticon-coins"></i>Allowed Balance:&nbsp;<span class="balance_allowed">{{$history->allowed_balance}}</span></a>
                                <a>
                                    <i class="flaticon2-calendar-3"></i>
                                    Sim Id: <strong>{{$hasSim['id']}}</strong>
                                </a>
                                {{-- <a><i class="fa fa-motorcycle"></i>{{ $bike1['bike_number'] }}</a> --}}
                                @php
                                $mytimestamp = strtotime($history['given_date']);
                                $timestampupdated=strtotime($history['return_date']);
                                $created=Carbon\Carbon::parse($history['given_date'])->format('F d, Y');
                                $updated=Carbon\Carbon::parse($history['return_date'])->format('F d, Y');
                              @endphp
                                @if($history->status=='active')
                            <h6 style="float:right;color:green;" onclick="updateDates({{$rider->id}},{{$history['id']}},'{{$created}}','{{$updated}}','{{$history->status}}')">{{gmdate("d-m-Y", $mytimestamp)}}</h6> 
                            
                            @else
                            <h6 style="float:right;color:green;" onclick="updateDates({{$rider->id}},{{$history['id']}},'{{$created}}','{{$updated}}','{{$history->status}}')">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
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
<div>
        <div class="modal fade" id="deactive_date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="exampleModalLabel">Unassign Sim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="kt-form" id="dective_sim_date"  enctype="multipart/form-data">
                        <div class="container">
                            <input type="hidden" id="sim_id" >
                            {{-- <div class="form-group">
                                <label>Started Month:</label>
                                <input data-rider="{{$rider->id}}" type="text" data-month="{{Carbon\Carbon::parse($history['created_at'])->format('M d, Y')}}" required readonly class="month_picker form-control" name="created_at" id="2nd_created" placeholder="Enter Month" value="">
                            </div> --}}
                            <div class="form-group">
                                <label>When Sim is unassigned:</label>
                                <input data-rider="{{$rider->id}}" type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="updated_at" placeholder="Enter Month" value="">
                            </div>
                            <div class="modal-footer border-top-0 d-flex justify-content-center">
                                <button class="upload-button btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                                <h4><a href="{{route('Sim.edit_sim_view',$hasSim['id'])}}">{{$hasSim['sim_company']}}-{{ $hasSim['sim_number'] }}</a></h4>
                                <a class="kt-widget__username">
                                  <button class="btn btn-label-danger btn-sm btn-upper"><span class="label label-danger">{{$history['status']}}</span></button>
                                  {{-- <button class="btn btn-label-info btn-sm btn-upper"><span class="label label-info"></span></button> --}}
                                  @if($hasSim['active_status']=="D")
                                    <button class="btn btn-label-warning btsssn-sm btn-upper"><span class="label label-warning">Deleted</span></button>
                                 @endif
                                  </a>
                                </div>
                                <div class="kt-widget__action">
                                    @if (Auth::user()->type=='su')
                                    <button onclick="deleteRecord({{$rider->id}},{{$history->id}})" class="btn btn-label-danger btn-sm btn-upper">Delete Record</button>&nbsp;
                                    @endif
                                {{-- <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">{{$bike_id['status']}}</span></button> --}}
                                 </div>
                            
        
                            <div class="kt-widget__subhead">
                                
                                <a onclick="updateAllowedBalance({{$history->allowed_balance}},{{$rider->id}},{{$history->id}},this)"><i class="flaticon2-calendar-3"></i>Allowed Balance:&nbsp;{{$history->allowed_balance}} </a>
                                {{-- <a><i class="fa fa-motorcycle"></i>{{ $bike1['bike_number'] }}</a> --}}
                                <a>
                                    <i class="flaticon2-calendar-3"></i>
                                    Sim Id: <strong>{{$hasSim['id']}}</strong>
                                </a>
                                @php
                                $mytimestamp = strtotime($history['given_date']);
                                $timestampupdated=strtotime($history['return_date']);
                                $created=Carbon\Carbon::parse($history['given_date'])->format('F d, Y');
                                $updated=Carbon\Carbon::parse($history['return_date'])->format('F d, Y');
                              @endphp
                                @if($history->status=='active')
                            <h6 style="float:right;color:green;" onclick="updateDates({{$rider->id}},{{$history['id']}},'{{$created}}','{{$updated}}','{{$history->status}}')">{{gmdate("d-m-Y", $mytimestamp)}}</h6> 
                            
                            @else
                            <h6 style="float:right;color:green;" onclick="updateDates({{$rider->id}},{{$history['id']}},'{{$created}}','{{$updated}}','{{$history->status}}')">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
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
<div>
    <div class="modal fade" id="sim_status" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">Change Created Or Updated Dates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" id="active_sim_status"  enctype="multipart/form-data">
                    <div class="container">
                        <input type="hidden" name="rider_id" id="rider_id" >
                        <input type="hidden" name="assign_sim_id" id="assign_sim_id">
                        <div class="form-group">
                            <label>Started Month:</label>
                            <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="created_at" placeholder="Enter Month" value="">
                        </div>
                        <div class="form-group" id="unassign_date">
                            <label>Ended Month:</label>
                            <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="updated_at" placeholder="Enter Month" value="">
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

<div>
        <div class="modal fade" id="allowed_balance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="exampleModalLabel">Change Created Or Updated Dates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="kt-form" id="sim_allowed_balance"  enctype="multipart/form-data">
                        <div class="container">
                            <input type="hidden" name="rider_id" id="rider_id" >
                            <input type="hidden" name="assign_sim_id" id="assign_sim_id">
                            <div class="form-group">
                                <label>Allowed Balance:</label>
                                <input  type="number" step="0.01" required class="form-control" name="allowed_balance" placeholder="Enter Balance" value="">
                            </div>
                            <div class="modal-footer border-top-0 d-flex justify-content-center">
                                <button class="upload-button btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" name="rider_id"  >
@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>

<script>
    function updateAllowedBalance(allowed_balance,rider_id,sim_history_id,_this){
        $("#allowed_balance").modal("show");
        $('[name="allowed_balance"]').val(allowed_balance);
        console.log(_this);
        $("form#sim_allowed_balance").on("submit",function(e){
        e.preventDefault();
        var _form = $(this);
        var _modal = _form.parents('.modal');
        var url = "{{ url('admin/sim/allowed/balance') }}" + "/" + rider_id + "/update" + "/" + sim_history_id;
        swal.fire({
            title: 'Are you sure?',
            text: "You want update Allowed Balance!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!'
        }).then(function(result) {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                url : url,
                type : 'GET',
                data: _form.serializeArray(),
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    $(_this).find(".balance_allowed").text(data.status.allowed_balance);
                    _modal.modal('hide');
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // window.location.reload(); 
                },
                error: function(error){
                    _modal.modal('hide');
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to update.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});
    }
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
function deleteSim(rider_id, sim_id,url, enablePageReload = false, reloadLocation = null, ajaxReloadId){
    $("#deactive_date").modal("show");
    $("form#dective_sim_date").on("submit",function(e){
        e.preventDefault();
        var _form = $(this);
        var _modal = _form.parents('.modal');
        var url = "{{ url('admin/sim/deactive') }}" + "/" + rider_id + "/date" + "/" + sim_id;
        console.log(url);
        swal.fire({
            title: 'Are you sure?',
            text: "You want unassign Sim!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!'
        }).then(function(result) {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                url : url,
                type : 'GET',
                data: _form.serializeArray(),
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    console.log(data);
                    _modal.modal('hide');
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                },
                error: function(error){
                    _modal.modal('hide');
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to update.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});
}

</script>
@endsection