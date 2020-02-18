@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $rider->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Clients</span>
        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div>
</div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">

    @if(count($clients) > 0)
        @foreach ($clients as $client_history)
        @php
            $client = App\Model\Client\Client::find($client_history->client_id);
        @endphp
        <div class="row">
            <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                    <a class="kt-widget__username" href="{{route('admin.clients.riders',$client->id)}}">
                                            {{ $client->name }}
                                            @if ($client_history->status=="active")
                                                <i class="flaticon2-correct"></i>                                            
                                            @endif
                                        </a>
                                        <div class="kt-widget__action">
                                            <button onclick="deleteRider({{$client->id}}, {{$rider->id}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                        </div>
                                    </div>
                
                                    <div class="kt-widget__subhead">
                                        <a href="mailto:{{ $client->email }}"><i class="flaticon2-new-email"></i>{{ $client->email }}</a>
                                        <a><i class="flaticon2-calendar-3"></i>{{ $client->phone }} </a>
                                        @if($client_history->client_rider_id)
                                                <a  class="text-success p-0">{{$client_history->client_rider_id}}</a>&nbsp;
                                            @else
                                                <a  class="text-danger p-0">No Feid Is assigned</a>&nbsp;
                                            @endif
                                        @php
                                            $mytimestamp = strtotime($client_history->assign_date);
                                            $timestampupdated=strtotime($client_history->deassign_date);
                                            $created=Carbon\Carbon::parse($client_history->assign_date)->format('F d, Y');
                                            $updated=Carbon\Carbon::parse($client_history->deassign_date)->format('F d, Y');
                                        @endphp 
                                        @if($client_history->status=='active')
                                            <h6  class="rise-modal" onclick="updateDates({{$rider->id}},{{$client_history->id}},'{{$created}}','{{$updated}}')" style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6>
                                        @else
                                            <h6 class="rise-modal" onclick="updateDates({{$rider->id}},{{$client_history->id}},'{{$created}}','{{$updated}}')"  style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
                                        @endif  
                                    </div>
                
                                    <div class="kt-widget__info">
                                        <i class="flaticon-location"></i>&nbsp;
                                        <div class="kt-widget__desc">
                                            {{ $client->address }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        @endforeach
    @else
    <div class="kt-section__content">
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">No client assigned yet.</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
<div>
    <div class="modal fade" id="client_duration" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">Change Created Or Updated Dates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" id="client_dates"  enctype="multipart/form-data">
                    <div class="container">
                        <input type="hidden" name="rider_id" id="rider_id" >
                        <input type="hidden" name="client_history_id" id="client_history_id">
                        <div class="form-group">
                            <label>Started Month:</label>
                            <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="assign_date" placeholder="Enter Month" value="">
                        </div>
                        <div class="form-group">
                            <label>Ended Month:</label>
                            <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="unassign_date" placeholder="Enter Month" value="">
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

<div>
    <div class="modal fade" id="unassign_date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">Unassign Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" id="client_unassign_dates"  enctype="multipart/form-data">
                    <div class="container">
                        <div class="form-group">
                            <label>Unassign Date:</label>
                            <input  type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="unassign_date" placeholder="Enter Month" value="">
                        </div>
                        <div class="modal-footer border-top-0 d-flex justify-content-center">
                            <button class="upload-button btn btn-success">Unassign Client</button>
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
        function deleteRider(client_id, rider_id)
        {
            $("#unassign_date").modal("show");
            $("form#client_unassign_dates").on("submit",function(e){
                e.preventDefault();
                var _form = $(this);
                var _modal = _form.parents('.modal');
                var url = "{{ url('admin/client') }}" + "/" + client_id + "/removeRider/" + rider_id;
               $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url : url,
                    type : 'DELETE',
                    data: _form.serializeArray(),
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
            });
        // }); 
        }
        function updateDates(rider_id,client_history_id,assign_date,unassign_date){
            $("#client_duration").modal("show");
            $('#client_dates [name="rider_id"]').val(rider_id);
            $('#client_dates [name="client_history_id"]').val(client_history_id);
            $('#client_dates [name="assign_date"]').attr('data-month', assign_date);
            $('#client_dates [name="unassign_date"]').attr('data-month', unassign_date);
            biketrack.refresh_global();
            $("form#client_dates").on("submit",function(e){
                e.preventDefault();
                var _form = $(this);
                var rider_id=$('#client_dates [name="rider_id"]').val();
                var client_history_id=$('#client_dates [name="client_history_id"]').val();
                var _modal = _form.parents('.modal');
                var url = "{{ url('admin/change_client_dates') }}" + "/" + rider_id + "/history" + "/" + client_history_id;
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
    </script>
@endsection