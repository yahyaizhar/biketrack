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
                        <a href="{{ route('admin.riders.index') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            All Riders
                        </a>
                        @php
                            $type_match=Auth::user()->type;
                        @endphp
                        @if ($type_match=="su")
                            <input class="btn btn-success" type="button" onclick="export_data()" value="Export Riders Data">
                        @endif
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
                        <th>KR-ID</th>
                        <th>Name</th>
                        <th>Assigned To</th>
                        <th>Sim Number</th>
                        <th>Bike Number</th>
                        <th>Missing Fields</th>
                        <th>Date Of Joining</th>
                        <th>Actions</th>
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
</div>
<!-- end:: Content -->
<div>
        <div class="modal fade" id="inactive_reasons" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="exampleModalLabel">Inactive Reasons</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="kt-form" id="inactive"  enctype="multipart/form-data">
                        <div class="container">
                            <div class="form-group">
                                <label>Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('inactive_month')) invalid-field @endif" name="inactive_month" placeholder="Enter Month" value="">
                            </div>
                            <div class="form-group">
                                <label>Reason:</label>
                                <textarea type="text"  rows="6" autocomplete="off" class="form-control" name="inactive_reason" placeholder="Enter Details" ></textarea>
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
@endsection
@section('foot')

<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>


<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->

<script>
    

var riders_table;
var riders_data = [];
function export_data(){
    var export_details=[];
    var _data=riders_table.ajax.json().data;
    console.log(_data);
    _data.forEach(function(item,index) {
        export_details.push({
        "ID":item.id,
        "Name":item.name,
        "Date Of Joining":item.date_of_joining,
        "Emirate Id":item.emirate_id,
        "Email":item.email,
        "Passport Expiry":item.passport_expiry,
        "Visa Expiry":item.visa_expiry,
        "Licence Expiry":item.licence_expiry,
        "Mulkiya Expiry":item.mulkiya_expiry,
        
        });
    });
        var export_data = new CSVExport(export_details);
    return false;
}
$(function() {
    var _settings = {
        lengthMenu: [[-1], ["All"]],
        processing: true,
        serverSide: false,
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
            { "data": 'new_name', "name": 'name' },
            { "data": 'client_name', "name": 'client_name' },
            { "data": 'sim_number', "name": 'sim_number' },
            { "data": 'bike_number', "name": 'bike_number' },
            { "data": 'missing_fields', "name": 'missing_fields' },
            { "data": 'date_of_joining', "name": 'date_of_joining' },
            { "data": 'actions', "name": 'actions' },
            { "data": 'bike_number', "name": 'bike_number' },
            { "data": 'phone', "name": 'phone' },
            { "data": 'emirate_id', "name": 'emirate_id' },
            { "data": 'new_email', "name": 'email' },
            { "data": 'passport_expiry', "name": 'passport_expiry' },
            { "data": 'visa_expiry', "name": 'visa_expiry' },
            { "data": 'licence_expiry', "name": 'licence_expiry' },
            { "data": 'mulkiya_expiry', "name": 'mulkiya_expiry' },
            { "data": 'passport_collected', "name": 'passport_collected' },
        ];
        _settings.responsive=false;
        _settings.columnDefs=[
            {
                "targets": [ 9,10,11,12,13,14,15,16,17 ],
                "visible": false,
                searchable: true,
            },
        ];
    }
    else{
        $('#riders-table thead tr th').eq(7).before('<th>Passport Expiry</th>');
        $('#riders-table thead tr th').eq(8).before('<th>Visa Expiry</th>');
        $('#riders-table thead tr th').eq(9).before('<th>Licence Expiry</th>');
        $('#riders-table thead tr th').eq(10).before('<th>Mulkiya Expiry</th>');
        _settings.columns=[
        { "data": 'new_id', "name": 'new_id' },
            { "data": 'new_name', "name": 'name' },
            { "data": 'new_email', "name": 'email' },
            { "data": 'sim_number', "name": 'sim_number' },
            { "data": 'bike_number', "name": 'bike_number' },
            { "data": 'address', "name": 'address' },
            { "data": 'date_of_joining', "name": 'date_of_joining' },
            { "data": 'passport_expiry', "name": 'passport_expiry' },
            { "data": 'visa_expiry', "name": 'visa_expiry' },
            { "data": 'licence_expiry', "name": 'licence_expiry' },
            { "data": 'mulkiya_expiry', "name": 'mulkiya_expiry' },
            { "data": 'actions', "name": 'actions' },
            { "data": 'passport_collected', "name": 'passport_collected' },
        ];
     
    }

    var mark_table = function(){}
    riders_table = $('#riders-table').DataTable(_settings);
    mark_table = function(){
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
            '<td colspan="1"; style="font-weight:900;" >Passport Collected:</td>'+
            '<td colspan="2";>'+data.passport_collected+'</td>'+
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