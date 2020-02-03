@extends('admin.layouts.app')
@section('head')
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .dataTables_length{
           display: block;   
        }
        .total_entries{
        display: inline-block;
        margin-left: 10px;
        }
        .dataTables_info{
            display:none;
        }
        </style>
@endsection
@section('main-content')
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                Rider Salary List
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filter by month</label>
                        <input id="month_picker" type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month" value="">
                    </div>
                </div>
            </div>
            <table class="table table-striped- table-hover table-checkable table-condensed" id="salary_status-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rider</th>
                        <th>Payable Salary</th>
                        <th>Remaining Blance</th>
                        <th>Payment Status</th>
                        <th>Salary Image</th>                        
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="salary_paid_image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">VIEW OR UPLOAD SLIP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" enctype="multipart/form-data" id="upload_slip_view">
                <div class="modal-body">
                    <div class="show_salary_slip_image" style="display:none; text-align:center;"></div>
                    <div class="form-group select_salary_slip" style="margin-top:10px;">
                        <div class="custom-file">
                            <div class="custom-file" style="">
                                <input type="file" name="slip_image" class="custom-file-input" id="slip_image">
                                <label class="custom-file-label" for="slip_image">Choose Slip Picture</label>
                                <span class="form-text text-muted">Select Rider Salary Slip</span>
                            </div>
                        </div>
                    </div>
                    <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-primary">Upload Slip</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script>
var salary_status_table;
$(document).ready(function(){
    $(document).on("click",".show_image",function(){
        $("#salary_paid_image").modal("show"); 
        $(".show_salary_slip_image").html("");
        var _attr_image=$(this).attr("data-image");
        var salary_paid=$(this).attr("data-paid");
        $('form#upload_slip_view').find('[type="submit"]').prop('disabled',false).html("Upload Slip");
        if (salary_paid==1) {
            $('form#upload_slip_view').find('[type="submit"]').prop('disabled',false);
        }
        if (salary_paid==0) {
            $('form#upload_slip_view').find('[type="submit"]').prop('disabled',true).html("Salary is not paid");
        }
        var _showImage=' <img class="profile-logo img img-thumbnail" data-featherlight="'+_attr_image+'" src="'+_attr_image+'" alt="image"><div></div>'
        if (_attr_image!=0 || _attr_image==null) {
            $(".show_salary_slip_image").show();
           $(".show_salary_slip_image").html(_showImage);
           $('form#upload_slip_view').find('[type="submit"]').html("Update Salary Slip");
        }
        if (_attr_image==0) {
            $(".show_salary_slip_image").html("");
        }
    });
    $('form#upload_slip_view').on('submit', function(e){
        var _self = $(this);
        var rider_id=$(".show_image").attr("data-rider");
        var month=$('[name="month_year"]').val();
        _self.find('[type="submit"]').prop('disabled',true);
        e.preventDefault();
        var _form = $(this);
        var _Url = "{{url('admin/view/upload/salary_slip')}}"+"/"+month+"/"+rider_id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : _Url,
            type : 'POST',
            data:new FormData(_form[0]),
            contentType: false,
            cache: false,
            processData:false,
            success: function(data){
                $('#view_upload_slip').modal('hide');
                _self.find('[type="submit"]').prop('disabled',false);
                // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
                swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'Record updated successfully.',
                    showConfirmButton: false,
                    timer: 1500
                });
                salary_status_table.ajax.reload(null, false);
            },
            error: function(error){
                _self.find('[type="submit"]').prop('disabled',false);
                // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
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
$(function() {
    $('[name="month_year"]').on("change",function(){
        var month=$(this).val();
        var push_state={
            month:new Date(month).format("mmmm-yyyy"),
        }
        biketrack.updateURL(push_state);
        init_table();
    });
    var is_check_month=biketrack.getUrlParameter('month');
    if (is_check_month!='') {
        $('[name="month_year"]').fdatepicker('update', new Date(is_check_month));
    }
    $('[name="month_year"]').trigger("change");
});
});
var init_table=function(){
    var _month=$('[name="month_year"]').val();
    salary_status_table = $('#salary_status-table').DataTable({
        lengthMenu: [[-1], ["All"]],
        processing: true,
        serverSide: false,
        destroy:true,
        'language': { 
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
        $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
    },
       ajax: "{{ url('admin/rider/salarystatus/') }}" + "/" + _month,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'rider_id', name: 'rider_id' },
            { data: 'salary', name: 'salary' },            
            { data: 'remaining_salary', name: 'remaining_salary' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'image', name: 'image' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
}
</script>
@endsection