@extends('admin.layouts.app')
@section('main-content')
<script>
function updateClientHistoryDates(rider_id,client_history_id,assign_date,deassign_date){  
            $("#client_update_dates").modal("show");
            $('input[name="rider_id"]').val(rider_id);
            $('input[name="client_history_id"]').val(client_history_id);
            $('input[name="assign_date"]').attr('data-month',assign_date);
            $('input[name="deassign_date"]').attr('data-month',deassign_date);

            biketrack.refresh_global();
            $("form#client_dates").on("submit",function(e){
                e.preventDefault();
                var _form = $(this);
                var rider_id=$("#rider_id").val();
                var client_history_id=$("#client_history_id").val();
                var _modal = _form.parents('.modal');
                var url = "{{ url('admin/change/clients') }}" + "/" + rider_id +"/"+client_history_id+ "/history/dates";
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
<input type="hidden" id="active_clients" value="{{$client_active_count}}">
<input type="hidden" id="deactive_clients" value="{{$client_deactive_count}}">
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $client->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

    <span class="kt-subheader__desc">Riders: <span class="number_of_riders"></span></span>

        <a href="{{ route('admin.clients.assignRiders', $client->id) }}" class="btn btn-label-warning btn-bold btn-sm btn-icon-h kt-margin-l-10">
            Assign Riders
        </a>

        <a class="btn btn-label-success btn-bold btn-sm btn-icon-h kt-margin-l-10" id="deactive_Riders">
           <span class="change_riders">Deactive Riders</span> 
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
@if (strpos($client->email, "zomato") !== false)
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">
                <div class="col-md-12 col-lg-4 col-xl-4">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <a href="{{ route('admin.riderPerformance') }}" class="kt-widget24__info">
                                <span class="kt-widget24__stats kt-font-danger">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fa fa-tachometer-alt"></i>
                                        <p class="p-0 m-0">Rider Performance</p>
                                    </button>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 col-xl-4">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <a href="{{ route('admin.ranges.adt') }}" class="kt-widget24__info">
                                <span class="kt-widget24__stats kt-font-success">
                                    <button type="button" class="btn btn-success">
                                        <i class="flaticon2-percentage"></i>
                                        <p class="p-0 m-0">ADT Performance</p>
                                    </button>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 col-xl-4">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <a href="{{ route('admin.accounts.income_zomato_index') }}" class="kt-widget24__info">
                                <span class="kt-widget24__stats kt-font-brand">
                                    <button type="button" class="btn btn-danger">
                                        <i class="fa fa-dollar-sign"></i>
                                        <p class="p-0 m-0">Income Report</p>
                                    </button>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
    @if(count($riders) > 0)
    <div id="active_riders">
        @foreach ($client_history_active as $client_H_A)
        @php
            $rider=App\Model\Rider\Rider::find($client_H_A->rider_id);
        @endphp
        @isset($rider)
            @else
            @continue
        @endisset
        <div class="row" >
            <div class="col-xl-12">
            <!--begin:: Widgets/Applications/User/Profile3-->
            <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__media kt-hidden-">
                                    @if($rider->profile_picture)
                                        <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="image">
                                    @else
                                        <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                    @endif
                                </div>
                                {{-- <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                    JM
                                </div> --}}
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                        <a class="kt-widget__username" href="{{route('admin.rider.profile',$rider->id)}}">
                                            {{ $rider->name }}
                                            @if ($rider->online)
                                                <i class="flaticon2-correct"></i>                                            
                                            @endif
                                        </a>
                                        
                
                                        <div class="kt-widget__action">
                                            <a href="{{ route('admin.rider.location', $rider->id) }}" class="btn btn-label-danger btn-sm btn-upper">View Location</a>&nbsp;
                                            <button onclick="deleteRider({{$client->id}}, {{$rider->id}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                        </div>
                                    </div>
                
                                    <div class="kt-widget__subhead">
                                        @php
                                        $client_rider = App\Model\Client\Client_Rider::where('client_id',$client->id)->where('rider_id',$rider->id)->get()->first();
                                        $assign_bike=$rider->Assign_bike()->where('status','active')->get()->first();
                                        $bike_number='No bike assigned';
                                        if(isset($assign_bike)){
                                            $bike=App\Model\Bikes\bike::find($assign_bike->bike_id);
                                            $bike_number=$bike->bike_number;
                                        } 
                                        $client_setting = json_decode($client->setting, true);
                                        $pm = $client_setting['payout_method'];
                                        @endphp
                                        @if (isset($client_rider->client_rider_id))
                                            
                                            @if ($pm=="commission_based")
                                                <a>Captain ID: {{$client_rider->client_rider_id}}</a>;
                                            @else 
                                                <a>FEID: {{$client_rider->client_rider_id}}</a>; 
                                            @endif
                                        @else
                                            @if ($pm=="commission_based")
                                                <a onclick="updatefied({{$rider->id}})" ><i class="fa fa-user-plus"></i>No Captain ID</a> 
                                            @else
                                                <a onclick="updatefied({{$rider->id}})" ><i class="fa fa-user-plus"></i>No FEID</a> 
                                            @endif
                                        @endif
                                        <a href="mailto:{{ $rider->email }}"><i class="flaticon2-new-email"></i>{{ $rider->email }}</a>
                                        <a><i class="flaticon2-calendar-3"></i>{{ $rider->phone }} </a>
                                        <a><i class="fa fa-motorcycle"></i>{{ $bike_number }}</a>
                                    </div>
                
                                    <div class="kt-widget__info">
                                        <i class="flaticon-location"></i>&nbsp;
                                        <div class="kt-widget__desc">
                                            {{ $rider->address }}
                                            @php
                                                $mytimestamp = strtotime($client_H_A['assign_date']);
                                                $timestampupdated=strtotime($client_H_A['deassign_date']);
                                                $created=Carbon\Carbon::parse($client_H_A['assign_date'])->format('F d, Y');
                                                $updated=Carbon\Carbon::parse($client_H_A['deassign_date'])->format('F d, Y');
                                            @endphp 
                                           <h6 style="float:right;color:green;" onclick="updateClientHistoryDates({{$rider->id}},{{$client_H_A['id']}},'{{$created}}','{{$updated}}')">{{gmdate("d-m-Y", $mytimestamp)}}</h6>
                                       
                                        </div>
                                    </div>
                                    @if ($pm=="commission_based")
                                    <div class="kt-widget__info">
                                        &nbsp;
                                        @php
                                            $client_comission = json_decode($client_H_A['comission'], true);
                                            $type = $client_comission['com_type'];
                                            $amount=$client_comission['com_amount'];
                                            if ($type=="fixed") {
                                                $postfix="AED";
                                            } else {
                                                $postfix="%";
                                            }
                                        @endphp
                                        <div class="kt-widget__desc">
                                            @if ($type!=null || $amount!=null)
                                                <a onclick="update_Comission({{$rider->id}},{{$client_H_A['id']}},{{$client->id}},'{{$type}}',{{$amount}})"  class=""><span><strong>Comission Type</strong> : {{$type}}</span><br><span style=""><strong>Comission Amount</strong> : {{$amount}} {{$postfix}}</span></a>&nbsp; 
                                            @else
                                                <a onclick="update_Comission({{$rider->id}},{{$client_H_A['id']}},{{$client->id}},'',0)"  class="btn btn-label-danger btn-sm btn-upper">Set Comission</a>&nbsp;
                                            @endif
                                        </div>
                                    </div>
                                    @endif
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
        @endforeach
    </div>
    <div id="deactive_riders">
        @foreach ($client_history as $client_H)
        @php
            $rider=App\Model\Rider\Rider::find($client_H->rider_id);
        @endphp
        @isset($rider)
            @else
            @continue
        @endisset
        <div class="row" >
                <div class="col-xl-12">
                <!--begin:: Widgets/Applications/User/Profile3-->
                <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-portlet__body">
                            <div class="kt-widget kt-widget--user-profile-3">
                                <div class="kt-widget__top">
                                    <div class="kt-widget__media kt-hidden-">
                                        @if($rider->profile_picture)
                                            <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="image">
                                        @else
                                            <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                        @endif
                                    </div>
                                    <div class="kt-widget__content">
                                        <div class="kt-widget__head">
                                            <a class="kt-widget__username" href="{{route('admin.rider.profile',$rider->id)}}">
                                                {{ $rider->name }}
                                            </a>
                                            <div class="kt-widget__action">
                                            </div>
                                        </div>
                    
                                        <div class="kt-widget__subhead">
                                            @php
                                            $client_rider = App\Model\Client\Client_Rider::where('client_id',$client->id)->where('rider_id',$rider->id)->get()->first();
                                            $assign_bike=$rider->Assign_bike()->where('status','active')->get()->first();
                                            $bike_number='No bike assigned';
                                            if(isset($assign_bike)){
                                                $bike=App\Model\Bikes\bike::find($assign_bike->bike_id);
                                                $bike_number=$bike->bike_number;
                                            } 
                                            $client_setting = json_decode($client->setting, true);
                                            $pm = $client_setting['payout_method'];
                                            @endphp
                                            @if (isset($client_H->client_rider_id))
                                                @if ($pm=="commission_based")
                                                    <a>Captain ID: {{$client_H->client_rider_id}}</a>;
                                                @else 
                                                    <a>FEID: {{$client_H->client_rider_id}}</a>;
                                                @endif
                                            @else
                                                @if ($pm=="commission_based")
                                                    <a onclick="updatefied({{$rider->id}})" ><i class="fa fa-user-plus"></i>No Captain ID</a> 
                                                @else
                                                    <a onclick="updatefied({{$rider->id}})" ><i class="fa fa-user-plus"></i>No FEID</a> 
                                                @endif
                                            @endif
                                            <a href="mailto:{{ $rider->email }}"><i class="flaticon2-new-email"></i>{{ $rider->email }}</a>
                                            <a><i class="flaticon2-calendar-3"></i>{{ $rider->phone }} </a>
                                            <a><i class="fa fa-motorcycle"></i>{{ $bike_number }}</a>
                                        </div>
                                        <div class="kt-widget__info">
                                            <i class="flaticon-location"></i>&nbsp;
                                            <div class="kt-widget__desc">
                                                {{ $rider->address }}
                                            @php
                                                $mytimestamp = strtotime($client_H['assign_date']);
                                                $timestampupdated=strtotime($client_H['deassign_date']);
                                                $created=Carbon\Carbon::parse($client_H['assign_date'])->format('F d, Y');
                                                $updated=Carbon\Carbon::parse($client_H['deassign_date'])->format('F d, Y');
                                            @endphp
                                                <h6 style="float:right;color:green;" onclick="updateClientHistoryDates({{$rider->id}},{{$client_H['id']}},'{{$created}}','{{$updated}}')">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
                                            </div>
                                        </div>
                                        @if ($pm=="commission_based")
                                        <div class="kt-widget__info">
                                            @php
                                            $client_comission = json_decode($client_H['comission'], true);
                                            $type = $client_comission['com_type'];
                                            $amount=$client_comission['com_amount'];
                                            if ($type=="fixed") {
                                                $postfix="AED";
                                            } else {
                                                $postfix="%";
                                            }
                                        @endphp
                                            &nbsp;
                                            <div class="kt-widget__desc">
                                                @if ($type!=null || $amount!=null)
                                                    <a onclick="update_Comission({{$rider->id}},{{$client_H['id']}},{{$client->id}},'{{$type}}',{{$amount}})"  class=""><span><strong>Comission Type</strong> : {{$type}}</span><br><span style=""><strong>Comission Amount</strong> : {{$amount}} {{$postfix}}</span></a>&nbsp; 
                                                @else
                                                    <a onclick="update_Comission({{$rider->id}},{{$client_H['id']}},{{$client->id}},'',0)"  class="btn btn-label-danger btn-sm btn-upper">Set Comission</a>&nbsp;
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
            
        @endforeach                                                                                                                                                                                                                           
    </div>
    @else
    <div class="kt-section__content">
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">No rider assigned yet.</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="modal fade" id="client_rider_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
            <h5 class="modal-title" id="exampleModalLabel">Assign Client's Rider ID</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form id="client_rider_form" class="kt-form" enctype="multipart/form-data">
                <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="rider_id">
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                </div>
                <div class="form-group">
                    <label>Enter Rider Id:</label>
                    <input type="text" class="form-control @if($errors->has('client_rider_id')) invalid-field @endif" name="client_rider_id" placeholder="Client's Rider ID"  >
                </div>
            </div>
                    
            <div class="modal-footer border-top-0 d-flex justify-content-center">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    <div>
        <div class="modal fade" id="client_update_dates" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="exampleModalLabel">Change Assign Or Deassign Dates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="kt-form" id="client_dates"  enctype="multipart/form-data">
                        <div class="container">
                            <input type="hidden" name="rider_id" id="rider_id" >
                            <input type="hidden" name="client_history_id" id="client_history_id">
                            <div class="form-group">
                                <label>Assigned Month:</label>
                                <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="assign_date" placeholder="Enter Month" value="">
                            </div>
                            <div class="form-group">
                                <label>Deassigned Month:</label>
                                <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="deassign_date" placeholder="Enter Month" value="">
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
    <div class="modal fade" id="salary_method_pop" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">Set Comission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" id="set_comission_Form" enctype="multipart/form-data">
                    <input type="hidden" name="client_id">
                    <input type="hidden" name="rider_id">
                    <input type="hidden" name="client_history_id">
                    <div class="container">
                        <div class="form-group">
                            <label>Amount:</label>
                            <div class="input-group">
                                <input type="text" autocomplete="off" class="form-control" name="com_amount" placeholder="Enter commission amount" />
                                <div class="input-group-append"><span class="input-group-text" id="cb_sm__amount_postfix"></span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Type:</label>
                            <select class="form-control kt-select2 bk-select2" id="cb_sm__type" name="com_type" >
                                <option value="percentage" selected>Percentage</option>   
                                <option value="fixed">Fixed</option> 
                            </select>
                        </div>
                        <div class="modal-footer border-top-0 d-flex justify-content-center">
                            <button class="upload-button btn btn-success">Save Comission</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
 $("#deactive_riders").hide();
 $('.number_of_riders').html("<a class='text-success'>{{$client_active_count}}</a>");       
$("#deactive_Riders").on("click",function(){
    var rider_val=$(".change_riders").text();
    if (rider_val=="Active Riders") {
        $(".change_riders").text("Deactive Riders");
        $("#active_riders").show();
        $("#deactive_riders").hide(); 
        $('.number_of_riders').html("<a class='text-success'>{{$client_active_count}}</a>");
    }
    if (rider_val=="Deactive Riders") {
        $(".change_riders").text("Active Riders");
        $("#active_riders").hide();
        $("#deactive_riders").show(); 
        $('.number_of_riders').html("<a class='text-danger'>{{$client_deactive_count}}</a>");
    }
    
});
 var current_open_target=null;
 function updatefied(rider_id){
    $("#client_rider_model").modal("show");
   var client_id=$('input[name="client_id"]').val();
   var rider_id=$('input[name="rider_id"]').val(rider_id);
   $('#client_rider_form').submit(function(e){
        e.preventDefault();
        $('#client_rider_model').modal('hide');
        var form=$(this);
        var rider_id=$('input[name="rider_id"]').val();
        var url="{{ url('admin/update/client/riders') }}" + "/" + rider_id;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:  url,
            data: form.serializeArray(),
            method: "POST"
        })
        .done(function(data) {  
            console.log(data);
            swal.fire({
                position: 'center',
                type: 'success',
                title: 'Record updated successfully.',
                showConfirmButton: false,
                timer: 1500
            });
            if(current_open_target){
                current_open_target.text(data.rider_id);
            }
            window.location.reload();
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
        function deleteRider(client_id, rider_id)
        {
            // console.log(client_id + ' , ' + rider_id);
            var url = "{{ url('admin/client') }}" + "/" + client_id + "/removeRider/" + rider_id;
            console.log(url);
            sendDeleteRequest(url, true);
        }
        function update_Comission(rider_id,client_history_id,client_id,type,amount){
            $("#salary_method_pop").modal("show");
            $('#cb_sm__type').on('change', function(){
                var _type = $(this).val().trim();
                var _sign = _type=="percentage"?'%':'AED';
                $('#cb_sm__amount_postfix').text(_sign);
            }).trigger('change');
            $('#set_comission_Form [name="client_id"]').val(client_id);
            $('#set_comission_Form [name="rider_id"]').val(rider_id);
            $('#set_comission_Form [name="client_history_id"]').val(client_history_id);
            $('#set_comission_Form [name="com_type"]').val(type).trigger("change");
            $('#set_comission_Form [name="com_amount"]').val(amount);
        }
        $('#set_comission_Form').on('submit', function(e){
            e.preventDefault();
            var url = "{{route('admin.update_rider_comission')}}";
            var _form = $(this);
            $('#salary_method_pop').modal('hide');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                data: _form.serializeArray(),
                beforeSend: function() {            
                    $('.bk_loading').show();
                },
                complete: function(){
                    $('.bk_loading').hide();
                },
                success: function(data){
                    console.warn(data);
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
                    console.warn(error);
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
        });
    </script>
@endsection