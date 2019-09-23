@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
.highlighted{
    background-color: #FFFF88;
}

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
    <!--end::Page Vendors Styles -->
@endsection
@section('main-content')

@include('admin.includes.message')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-motorcycle"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Active Riders 
                </h3>

            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                       
                        &nbsp;
                        <div class="checkbox checkbox-danger btn btn-default btn-elevate btn-icon-sm">
                            <input id="check_id" class="checkbox checkbox-danger" type="checkbox">
                            <label for="check_id" >
                               Detailed View
                            </label>
                        </div>
                        <a href="{{ route('admin.riders.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                         </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped table-hover table-checkable table-condensed" id="riders-table">
                <thead>
                    <tr>
                        
                        <th>ID</th>
                        <th>KR-ID</th>
                        <th>Name</th>
                        <th>Assigned To</th>
                        <th>Sim Number</th>
                        <th>Passport Collected</th>
                        <th>Missing Fields</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>
<div>
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
                    <input type="hidden" name="client_id">
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
    <div class="modal fade" id="kingrider_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
            <h5 class="modal-title" id="exampleModalLabel">Assign Kingriders ID</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form id="kingrider_form" class="kt-form" enctype="multipart/form-data">
                <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="rider_id">
                </div>
                <div class="form-group">
                    <label>Enter Kingriders Id:</label>
                    <input required autocomplete="off" type="text" class="form-control @if($errors->has('kingriders_id')) invalid-field @endif" name="kingriders_id" placeholder="Kingriders ID"  >
                </div>
            </div>
                    
            <div class="modal-footer border-top-0 d-flex justify-content-center">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- end:: Content -->

@endsection
@section('foot')

<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>


<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->

<script>
    

var riders_table;
var riders_data = [];
$(function() {
    var kingrider_target=null;
    $("#kingrider_model").on('shown.bs.modal', function (event){
         console.log(event);
         kingrider_target=$(event.relatedTarget);
        var current_target = $(event.currentTarget);
        
        var rider_id_val=$(event.relatedTarget).attr("data-rider-id");
        var kingriders_id_val=$(event.relatedTarget).attr("current-target");
        current_target.find('[name="rider_id"]').val(rider_id_val);
        if (kingriders_id_val!=null) {
            current_target.find('[name="kingriders_id"]').val(kingriders_id_val);
        }
    });

    var current_open_target=null;
     $("#client_rider_model").on('shown.bs.modal', function (event){
         console.log(event);
        current_open_target=$(event.relatedTarget);
        var current_target = $(event.currentTarget);
        
        var client_rider_id_val=$(event.relatedTarget).attr("data-client-rider-id");
        var rider_id_val=$(event.relatedTarget).attr("data-rider-id");
        var client_id = $(event.relatedTarget).attr('data-client-id');
        current_target.find('[name="client_rider_id"]').val(client_rider_id_val);
        current_target.find('[name="rider_id"]').val(rider_id_val);
        current_target.find('[name="client_id"]').val(client_id);

    });
    $('#client_rider_form').submit(function(e){
        e.preventDefault();
        $('#client_rider_model').modal('hide');
        var form=$(this);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:"{{route('ClientRiders.admin.update')}}",
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
                current_open_target.attr("data-client-rider-id", data.client_rider_id);
            }
        });
        
    });
    $('#kingrider_form').submit(function(e){
        e.preventDefault();
        $('#kingrider_model').modal('hide');
        var form=$(this);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:"{{route('KingRiders.admin.update')}}",
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
            console.log(kingrider_target);
            if(kingrider_target){
                kingrider_target.html('<strong>'+data.kingriders_id+'</strong>');
            }
        });
        
    });
    var _settings = {
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
            var api = this.api();
            var _data = api.data();
            var keys = Object.keys(_data).filter(function(x){return !isNaN(parseInt(x))});
            keys.forEach(function(_d,_i) {
                var __data = JSON.parse(JSON.stringify(_data[_d]).toLowerCase());
                riders_data.push(__data);
            });
            // dataTables_info
            $('.total_entries').remove();
            $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
            mark_table();
        },
        ajax: '{!! route('admin.ajax_active_rider') !!}',
        columns: null,
        responsive:true, 
        order:[0,'desc']
    };
    if(window.outerWidth>=720){
        //visa_expiry
        $('#riders-table thead tr').prepend('<th></th>');
        _settings.columns=[
            {
            "className":      'details-control',
            "orderable":      false,
            "data":           null,
            "defaultContent": ''
        },
        { "data": 'new_id', "name": 'new_id' },
        { "data": 'kingriders_id', "name": 'kingriders_id' },
            { "data": 'new_name', "name": 'name' },
            { "data": 'client_name', "name": 'client_name' },
            { "data": 'sim_number', "name": 'sim_number' },
            { "data": 'passport_collected', "name": 'passport_collected' },
            { "data": 'missing_fields', "name": 'missing_fields' },
            { "data": 'status', "name": 'status' },
            { "data": 'actions', "name": 'actions' },
            { "data": 'date_of_joining', "name": 'date_of_joining' },
            { "data": 'bike_number', "name": 'bike_number' },
            { "data": 'phone', "name": 'phone' },
            { "data": 'emirate_id', "name": 'emirate_id' },
            { "data": 'new_email', "name": 'email' },
            { "data": 'passport_expiry', "name": 'passport_expiry' },
            { "data": 'visa_expiry', "name": 'visa_expiry' },
            { "data": 'licence_expiry', "name": 'licence_expiry' },
            { "data": 'mulkiya_expiry', "name": 'mulkiya_expiry' },
        ];
        _settings.responsive=false;
        _settings.columnDefs=[
            {
                "targets": [ 10,11,12,13,14,15,16,17,18 ],
                "visible": false,
                searchable: true,
            },
        ];
    }
    else{
        $('#riders-table thead tr th').eq(7).before('<th>Date Of Joining</th>');
        $('#riders-table thead tr th').eq(8).before('<th>Passport Expiry</th>');
        $('#riders-table thead tr th').eq(9).before('<th>Visa Expiry</th>');
        $('#riders-table thead tr th').eq(10).before('<th>Licence Expiry</th>');
        $('#riders-table thead tr th').eq(11).before('<th>Mulkiya Expiry</th>');
        _settings.columns=[
        { "data": 'new_id', "name": 'new_id' },
        { "data": 'kingriders_id', "name": 'kingriders_id' },
            { "data": 'new_name', "name": 'name' },
            { "data": 'new_email', "name": 'email' },
            { "data": 'sim_number', "name": 'sim_number' },
            { "data": 'passport_collected', "name": 'passport_collected' },
            { "data": 'address', "name": 'address' },
            { "data": 'status', "name": 'status' },
            { "data": 'date_of_joining', "name": 'date_of_joining' },
            { "data": 'passport_expiry', "name": 'passport_expiry' },
            { "data": 'visa_expiry', "name": 'visa_expiry' },
            { "data": 'licence_expiry', "name": 'licence_expiry' },
            { "data": 'mulkiya_expiry', "name": 'mulkiya_expiry' },
            { "data": 'actions', "name": 'actions' }
        ];
     
    }
    riders_table = $('#riders-table').DataTable(_settings);
    var mark_table = function(){
        var _val = riders_table.search();
        if(_val===''){
            $("#riders-table tbody").unmark();
            $("#riders-table tbody > tr:visible").each(function() {
                var tr = $(this);
                var row = riders_table.row( tr );
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.remove();
                    tr.removeClass('shown');
                }
            });
            return;
        }
        $('#riders-table tbody > tr[role="row"]:visible').each(function() {
            var tr = $(this);
            var row = riders_table.row( tr );
            // console.warn("isShon: ",row.child.isShown());
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.remove();
                tr.removeClass('shown');
            }
                // This row is already open - close it
                var _arow = row.child( format(row.data()) );
                _arow.show();
                tr.addClass('shown');
        });
        $("#riders-table tbody").unmark({
            done: function() {
                $("#riders-table tbody").mark(_val, {
                    "element": "span",
                    "className": "highlighted"
                });
            }
        });
        
    }


    if(window.outerWidth>=720){
        $('#riders-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = riders_table.row( tr );
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                var _arow = row.child( format(row.data()) );
                _arow.show();
                tr.addClass('shown');
            }
        });
    }
    // {
    //             "className":      'details-control',
    //             "orderable":      false,
    //             "data":           null,
    //             "defaultContent": ''
    //         },
    
function format ( data ) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr>'+
            '<td colspan="1"; style="font-weight:900;">Date Of Joining:</td>'+
            '<td colspan="2";>'+data.date_of_joining+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Assign Bike Number:</td>'+
            '<td colspan="2";>'+data.bike_number+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Personal Phone Number:</td>'+
            '<td colspan="1";><a>'+data.phone+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Emirate ID:</td>'+
            '<td colspan="1";>'+data.emirate_id+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Email:</td>'+
            '<td colspan="1";>'+data.email+'</td>'+
            
            '</tr>'+
            '<tr>'+
            '<td colspan="1"; style="font-weight:900;">Passport Expiry:</td>'+
            '<td colspan="2";>'+data.passport_expiry+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Visa Expiry:</td>'+
            '<td colspan="2"; >'+data.visa_expiry+'</td>'+
            '<td style="font-weight:900;">Licence Expiry:</td>'+
            '<td colspan="2";>'+data.licence_expiry+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Mulkiya Expiry:</td>'+
            '<td colspan="2";>'+data.mulkiya_expiry+'</td>'+
            '</tr>'+
        '</table>';
}
if(window.outerWidth>=720){
        $("#check_id").change(function(){

            if($("#check_id").prop("checked") == true){
                $("td.details-control").each(function(){
                    if (!$(this).parent().hasClass("shown")) {
                        $(this).trigger("click");
                    }  
                });
            }
            if($("#check_id"). prop("checked") == false){
                $("td.details-control").each(function(){
                    if ($(this).parent().hasClass("shown")) {
                        $(this).trigger("click");
                    }  
                });
            }
        });
    }
    else if(window.outerWidth<720){
        $("#check_id").change(function(){
            if($("#check_id").prop("checked") == true){
                $("td.sorting_1").each(function(){
                    if (!$(this).parent().hasClass("parent")) {
                        $(this).trigger("click");
                    }  
                });
            }
            if($("#check_id"). prop("checked") == false){
                $("td.sorting_1").each(function(){
                    if ($(this).parent().hasClass("parent")) {
                        $(this).trigger("click");
                    }  
                });
            }
        });
    }


});

function deleteRider(id){
    var url = "{{ url('admin/riders') }}"+ "/" + id;
    sendDeleteRequest(url, false, null, riders_table);
}

function updateStatus(rider_id)
{
    var url = "{{ url('admin/rider') }}" + "/" + rider_id + "/updateStatus";
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
                type : 'POST',
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    riders_table.ajax.reload(null, false);
                },
                error: function(error){
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
}
</script>
<style>
    td.details-control {
        background: url('https://biketrack-dev.solutionwin.net/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('https://biketrack-dev.solutionwin.net/details_close.png') no-repeat center center;
    }
</style>
@endsection