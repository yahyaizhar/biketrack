@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
    <h3 class="kt-subheader__title">{{$sim->sim_number}}-{{$sim->sim_company}}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Rider History</span>
        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div>
</div> <!-- begin:: Content -->
 <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">
    
    @if(count($sim_history)>0)
    @foreach ($sim_history as $history)
        @if ($history->status=='active')
        @php
        $rider = App\Model\Rider\Rider::find($history->rider_id);
        @endphp
        <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    
            <div class="row">
                <div class="col-xl-12">
                <!--begin:: Widgets/Applications/User/Profile3-->
                <div class="kt-portlet kt-portlet--height-fluid" >
                        <div class="kt-portlet__body">
                            <div class="kt-widget kt-widget--user-profile-3">
                                <div class="kt-widget__top">
                                    <div class="kt-widget__content">
                                        <div class="kt-widget__head">
                                            
                                            <a href="{{route('admin.rider.profile',$rider->id)}}">{{ $rider->name }}</a>
                                            <a class="kt-widget__username">
                                                @if ($history->status==='active') 
                                                <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Active</span></button>
                                                @else
                                                <button class="btn btn-label-danger btn-sm btn-upper"><span class="label label-danger">Deactive</span></button>
                                                @endif
                                                @if ($rider->active_status==='D') 
                                                <button class="btn btn-label-warning btn-sm btn-upper"><span class="label label-warning">Deleted</span></button>
                                                @endif
                                               </a>
                    
                                            <div class="kt-widget__action">
                                                {{-- <a href="{{ route('admin.riders.edit', $rider->id) }}" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp; --}}
                                                <a href="{{ route('admin.rider.profile', $rider->id) }}" class="btn btn-label-danger btn-sm btn-upper">View Rider Profile</a>&nbsp;
                                                <button onclick="deleteSim({{$sim->id}},{{$rider->id}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                            </div>
                                        </div>
                    
                                        <div class="kt-widget__subhead">
                                            <a href="mailto:{{ $rider->email }}"><i class="flaticon2-new-email"></i>{{ $rider->email }}</a>
                                            <a><i class="flaticon2-calendar-3"></i>{{ $rider->phone }} </a>
                                            {{-- <a><i class="fa fa-motorcycle"></i>{{ $rider1->vehicle_number }}</a> --}}
                                        </div>
                    
                                        <div class="kt-widget__info">
                                            <i class="flaticon-location"></i>&nbsp;
                                            <div class="kt-widget__desc">
                                                {{ $rider->address }}
                                                @php
                                                    $mytimestamp = strtotime($history['given_date']);
                                                    $timestampupdated=strtotime($history['return_date']);
                                                    $created=Carbon\Carbon::parse($history['given_date'])->format('F d, Y');
                                                    $updated=Carbon\Carbon::parse($history['return_date'])->format('F d, Y');
                                                @endphp
                                                @if($history->status=='active')
                                                    <h6 class="rise-modal" onclick="updateDates({{$rider->id}},{{$history['id']}},'{{$created}}','{{$updated}}','{{$history->status}}')" style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6>
                                                @else
                                                    <h6 class="rise-modal" onclick="updateDates({{$rider->id}},{{$history['id']}},'{{$created}}','{{$updated}}','{{$history->status}}')" style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
                                                @endif
                                            </div>
                                        </div>
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
    @foreach ($sim_history as $history)
    @if ($history->status=='deactive')
        @php
        $rider = App\Model\Rider\Rider::find($history->rider_id);
        @endphp
    
    
        <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        
        <div class="row">
            <div class="col-xl-12">
            <!--begin:: Widgets/Applications/User/Profile3-->
            <div class="kt-portlet kt-portlet--height-fluid" >
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <a href="{{route('admin.rider.profile',$rider->id)}}">{{ $rider->name }}</a>
                                    <a class="kt-widget__username">
                                        @if ($history->status==='active') 
                                        <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Active</span></button>
                                        @else
                                        <button class="btn btn-label-danger btn-sm btn-upper"><span class="label label-danger">Deactive</span></button>
                                        @endif
                                        @if ($rider->active_status==='D') 
                                        <button class="btn btn-label-warning btn-sm btn-upper"><span class="label label-warning">Deleted</span></button>
                                        @endif
                                    </a>
                
                                        <div class="kt-widget__action">
                                            {{-- <a href="{{ route('admin.riders.edit', $rider->id) }}" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp; --}}
                                            <a href="{{ route('admin.rider.profile', $rider->id) }}" class="btn btn-label-danger btn-sm btn-upper">View Rider Profile</a>&nbsp;
                                            <button onclick="deleteSim({{$sim->id}},{{$rider->id}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                        </div>
                                    </div>
                
                                    <div class="kt-widget__subhead">
                                        <a href="mailto:{{ $rider->email }}"><i class="flaticon2-new-email"></i>{{ $rider->email }}</a>
                                        <a><i class="flaticon2-calendar-3"></i>{{ $rider->phone }} </a>
                                        {{-- <a><i class="fa fa-motorcycle"></i>{{ $rider1->vehicle_number }}</a> --}}
                                    </div>
                
                                    <div class="kt-widget__info">
                                        <i class="flaticon-location"></i>&nbsp;
                                        <div class="kt-widget__desc">
                                            {{ $rider->address }}
                                            @php
                                                $mytimestamp = strtotime($history['given_date']);
                                                $timestampupdated=strtotime($history['return_date']);
                                                $created=Carbon\Carbon::parse($history['given_date'])->format('F d, Y');
                                                $updated=Carbon\Carbon::parse($history['return_date'])->format('F d, Y');
                                            @endphp
                                            @if($history->status=='active')
                                                <h6 class="rise-modal" onclick="updateDates({{$rider->id}},{{$history['id']}},'{{$created}}','{{$updated}}','{{$history->status}}')" style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6>
                                            @else
                                                <h6 class="rise-modal" onclick="updateDates({{$rider->id}},{{$history['id']}},'{{$created}}','{{$updated}}','{{$history->status}}')" style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- <div class="kt-widget__bottom"> --}}
                                {{-- <div class="kt-widget__item col-md-10">
                                    <textarea class="form-control" id="message_{{ $rider->id }}" name="message_{{ $rider->id }}" placeholder="Enter message here"></textarea>
                                </div>
                                <div class="kt-widget__item">
                                    <button onclick="sendSMS({{$rider->id}})" class="btn btn-label-success btn-sm btn-upper">Send SMS</button>&nbsp;
                                </div>
                            </div> --}} 
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Applications/User/Profile3-->    
        </div>
    </div>
    @endif
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
<div>
    <div class="modal fade" id="update_dates_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">Change Created Or Updated Dates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" id="sim_dates_model"  enctype="multipart/form-data">
                    <div class="container">
                        <input type="hidden" name="rider_id" id="rider_id" >
                        <input type="hidden" name="sim_history_id" id="sim_history_id">
                        <div class="form-group">
                            <label>Started Month:</label>
                            <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="created_at" placeholder="Enter Month" value="">
                        </div>
                        <div class="form-group" id="unassign_date">
                            <label>Ended Month:</label>
                            <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="updated_at" placeholder="Enter Month" value="">
                        </div>
                        <div class="modal-footer border-top-0 d-flex justify-content-center">
                            <button class="upload-button btn btn-success">Update Dates</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
    <script>
        function updateDates(rider_id,sim_history_id,assign_date,unassign_date,status){
            $("#update_dates_model").modal("show");
            $('#sim_dates_model [name="rider_id"]').val(rider_id);
            $('#sim_dates_model [name="sim_history_id"]').val(sim_history_id);
            $('#sim_dates_model [name="created_at"]').attr('data-month', assign_date);
            $('#sim_dates_model [name="updated_at"]').attr('data-month', unassign_date);
            $("#unassign_date").show();
            if (status=="active") {
                $("#unassign_date").hide();
            }
            biketrack.refresh_global();
            $("form#sim_dates_model").on("submit",function(e){
                e.preventDefault();
                var _form = $(this);
                var rider_id=$('#sim_dates_model [name="rider_id"]').val();
                var sim_history_id=$('#sim_dates_model [name="sim_history_id"]').val();
                var _modal = _form.parents('.modal');
                var url = "{{ url('admin/change/sim/') }}" + "/" + rider_id + "/history" + "/" + sim_history_id+"/"+status;
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want update Dates!",
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
            // console.log(sim_id + ' , ' + rider_id);
            var url = "{{ url('admin/sim') }}" + "/" + rider_id + "/removeSim/"+sim_id ;
            console.log(url);
            sendDeleteRequest(url, true);
        }
    </script>
@endsection