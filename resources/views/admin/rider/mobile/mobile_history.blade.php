@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">{{ $rider->name }}</h3>
        <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        <span class="kt-subheader__desc">Mobile History</span>
        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div>
</div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">
    @if($mobile_history_count > 0)
        @foreach ($mobile_histories as $mobile_history)
            @php
                $mobile=$mobile_history->mobile;
            @endphp
            <div class="row">
                <div class="col-xl-12">
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
                                                <h4>{{$mobile->brand}}-{{ $mobile->model }}</h4>
                                                @if ($mobile_history->active_status==='A') 
                                                    <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">Active</span></button>
                                                @else
                                                    <button class="btn btn-label-danger btn-sm btn-upper"><span class="label label-danger">Deactive</span></button>
                                                @endif
                                                @if ($mobile_history->payment_type=="cash")
                                                    <button class="btn btn-label-warning btn-sm btn-upper"><span class="label label-warning">Cash</span></button>  
                                                @else
                                                    <button class="btn btn-label-warning btn-sm btn-upper"><span class="label label-warning">Installment</span></button>
                                                @endif
                                            </a>
                    
                                            <div class="kt-widget__action">
                                                {{-- @if ($assign_bike->status==='active')
                                                <button onclick="deleteBike({{$rider->id}},{{$assign_bike->id}})" class="btn btn-label-info btn-sm btn-upper">Unassign Bike</button>&nbsp;
                                                @endif --}}
                                                {{-- <button class="btn btn-label-success btn-sm btn-upper"><span class="label label-success">{{$bike_id['status']}}</span></button> --}}
                                            </div>
                                        </div>
                                        <div class="kt-widget__subhead">
                                            <a><i class="flaticon2-calendar-3"></i>{{ $mobile->imei_1 }} </a>
                                            <a><i class="flaticon2-calendar-3"></i>{{ $mobile->imei_2 }}</a>
                                            @php
                                            $mytimestamp = strtotime($mobile_history->mobile_assign_date);
                                            $timestampupdated=strtotime($mobile_history->mobile_unassign_date);
                                            $created=Carbon\Carbon::parse($mobile_history->mobile_assign_date)->format('F d, Y');
                                            $updated=Carbon\Carbon::parse($mobile_history->mobile_unassign_date)->format('F d, Y');
                                            @endphp 
                                            @if($mobile_history->active_status=='A')
                                                <h6  class="rise-modal" id="updates_date_link" onclick="updateMobileDates({{$rider->id}},{{$mobile_history->id}},'{{$created}}','{{$updated}}')" style="float:right;color:green;">{{gmdate("d-m-Y", $mytimestamp)}}</h6>
                                            @endif   
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
            <div>
                <div class="modal fade" id="mobile_purchase_date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title" id="exampleModalLabel">Change Mobile Purchase Dates</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form class="kt-form" id="mobile_given_date"  enctype="multipart/form-data">
                                <div class="container">
                                    <input type="hidden" id="mobile_history_id" >
                                    <div class="form-group">
                                        <label>Monbile Purchase Month:</label>
                                        <input data-rider="{{$rider->id}}" type="text" data-month="{{Carbon\Carbon::parse($mobile_history->mobile_assign_date)->format('M d, Y')}}" required readonly class="month_picker form-control" name="mobile_assign_date" placeholder="Enter Month" value="">
                                    </div>
                                    <div class="modal-footer border-top-0 d-flex justify-content-center" style="float:right;">
                                        <button class="upload-button btn btn-success">Update Mobile Given Date</button>
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
<input type="hidden" id="rider_id" value="{{$rider->id}}">
@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script>
    function updateMobileDates(rider_id, mobile_history_id, created ,updated) {
        $("#mobile_history_id").val(mobile_history_id);
        $("#mobile_given_date").find('[name="mobile_assign_date"]').attr('data-month', created);
        biketrack.refresh_global();
        $("#mobile_purchase_date").modal("show");
    }
    $("form#mobile_given_date").on("submit",function(e){
        e.preventDefault();
        var _form = $(this);
        var rider_id=$("#rider_id").val();
        var mobile_history_id=$("#mobile_history_id").val();
        var _modal = _form.parents('.modal');
        var url = "{{ url('admin/mobile/change_given_date/history') }}" + "/" + rider_id + "/" + mobile_history_id;
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
                        window.location.reload();
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
</script>
@endsection