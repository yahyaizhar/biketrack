@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $rider->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Bikes History</span>
    <a href="{{route('bike.bike_assignRiders',$rider->id)}}" class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Assign Bike</span></a>
        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div>
</div> <!-- begin:: Content -->
 <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">
    @if($assign_bike_count > 0)
    
        @foreach ($assign_bikes as $assign_bike)
        @php
            $bike=$assign_bike->bike;
        @endphp
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
                                        <h4><a href="{{route('Bike.bike_edit_view',$bike->id)}}">{{$bike->brand}}-{{ $bike->model }}</a></h4>
                                        <a class="kt-widget__username">
                                            @if ($assign_bike->status==='active')
                                                <button onclick="deleteBike({{$rider->id}},{{$assign_bike->id}})" class="btn btn-label-info btn-sm btn-upper">Unassign Bike</button>&nbsp;
                                            @endif
                                        </a>
                                    </div>
                                        <div class="kt-widget__action">
                                            @if ($assign_bike->status==='active') 
                                                <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Active</span></button>
                                            @else
                                                <button class="btn btn-label-danger btn-sm btn-upper"><span class="label label-danger">Deactive</span></button>
                                            @endif
                                            @if ($bike->active_status==='D') 
                                             <button class="btn btn-label-warning btn-sm btn-upper"><span class="label label-warning">Deleted</span></button>
                                            @endif
                                            @if (Auth::user()->type=='su')
                                                <button onclick="deleteRecord({{$rider->id}},{{$history->id}})" class="btn btn-label-danger btn-sm btn-upper">Delete Record</button>&nbsp;
                                            @endif
                                        </div>
                                    
                
                                    <div class="kt-widget__subhead">
                                        <a><i class="flaticon2-calendar-3"></i>{{ $bike->availability }} </a>
                                        <a><i class="fa fa-motorcycle"></i>{{ $bike->bike_number }}</a>
                                        @php
                                        $mytimestamp = strtotime($assign_bike->bike_assign_date);
                                        $timestampupdated=strtotime($assign_bike->bike_unassign_date);
                                        $created=Carbon\Carbon::parse($assign_bike->bike_assign_date)->format('F d, Y');
                                        $updated=Carbon\Carbon::parse($assign_bike->bike_unassign_date)->format('F d, Y');
                                        
                                    @endphp 
                                        @if($assign_bike->status=='active')
                                    <h6  class="rise-modal" onclick="updateDates({{$rider->id}},{{$assign_bike->id}},'{{$created}}','{{$updated}}','{{$assign_bike->status}}')" style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6>
                                    @else
                                    <h6 class="rise-modal" onclick="updateDates({{$rider->id}},{{$assign_bike->id}},'{{$created}}','{{$updated}}','{{$assign_bike->status}}')"  style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}} {{'to'}} {{gmdate("d-m-Y", $timestampupdated)}}</h6>
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
                    <div class="modal fade" id="bike_status" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title" id="exampleModalLabel">Change Created Or Updated Dates</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form class="kt-form" id="active_bike_status"  enctype="multipart/form-data">
                                    <div class="container">
                                        <input type="hidden" id="bike_id" >
                                        <div class="form-group">
                                            <label>Started Month:</label>
                                            <input data-rider="{{$rider->id}}" type="text" data-month="{{Carbon\Carbon::parse($assign_bike->bike_assign_date)->format('M d, Y')}}" required readonly class="month_picker form-control" name="bike_assign_date" placeholder="Enter Month" value="">
                                        </div>
                                        <div class="form-group" id="unassign_date">
                                            <label>Ended Month:</label>
                                            <input data-rider="{{$rider->id}}" type="text" data-month="{{Carbon\Carbon::parse($assign_bike->bike_unassign_date)->format('M d, Y')}}" required readonly class="month_picker form-control" name="bike_unassign_date" placeholder="Enter Month" value="">
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
                    <div class="modal fade" id="deactive_date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title" id="exampleModalLabel">Unassign Bike</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form class="kt-form" id="dective_bike_date"  enctype="multipart/form-data">
                                    <div class="container">
                                        <input type="hidden" id="bike_id" >
                                        {{-- <div class="form-group">
                                            <label>Started Month:</label>
                                            <input data-rider="{{$rider->id}}" type="text" data-month="{{Carbon\Carbon::parse($assign_bike->created_at)->format('M d, Y')}}" required readonly class="month_picker form-control" name="created_at" id="2nd_created" placeholder="Enter Month" value="">
                                        </div> --}}
                                        <div class="form-group">
                                            <label>When bike is unassigned:</label>
                                            <input data-rider="{{$rider->id}}" type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="bike_unassign_date" placeholder="Enter Month" value="">
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
        @endforeach
    @else
    <div class="kt-section__content">
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">No Bike history yet.</div>
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
<input type="hidden" id="rider_id" value="{{$rider->id}}">


@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>

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
        function updateDates(rider_id, assign_bike_id, created ,updated,bike_status) {
            $("#bike_id").val(assign_bike_id);
            $("#bike_status").find('[name="bike_assign_date"]').attr('data-month', created);
            $("#bike_status").find('[name="bike_unassign_date"]').attr('data-month', updated);
            $("#bike_status").find('#unassign_date').show();
            if (bike_status=="active") {
                $("#bike_status").find('#unassign_date').hide();
            }
            biketrack.refresh_global();
            $("#bike_status").modal("show");
        }
        $("form#active_bike_status").on("submit",function(e){
            e.preventDefault();
            var _form = $(this);
            var rider_id=$("#rider_id").val();
            var assign_bike_id=$("#bike_id").val();
            var _modal = _form.parents('.modal');
            var url = "{{ url('admin/change') }}" + "/" + rider_id + "/history" + "/" + assign_bike_id +"/"+bike_status;
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

function deleteBike(rider_id, bike_id,url, enablePageReload = false, reloadLocation = null, ajaxReloadId)
{
    $("#deactive_date").modal("show");
    $("form#dective_bike_date").on("submit",function(e){
        e.preventDefault();
        var _form = $(this);
        var _modal = _form.parents('.modal');
        var url = "{{ url('admin/bike/deactive') }}" + "/" + rider_id + "/date" + "/" + bike_id;
        console.log(url);
        swal.fire({
            title: 'Are you sure?',
            text: "You want unassign bike!",
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