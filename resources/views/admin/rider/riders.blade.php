@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
.highlighted{
    background-color: #FFFF88;
}
.dataTables_filter{
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
                    Riders
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
                        <input type="text" class="form-control" placeholder="Search" id="search_details" style="display: inline-block;width: auto;">
                        <a style="padding:8.45px 13px;" href="" data-toggle="modal" data-target="#import_data"  class="btn btn-label-success btn-sm btn-upper">Import Zomato Data</a>&nbsp;
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
                        <th>Name</th>
                        <th>Assigned To</th>
                        <th>Sim Number</th>
                        <th>Missing Fields</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

<!-- end:: Content -->
<div>
<div class="modal fade" id="import_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" id="form_dates"  enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="UppyDragDrop"></div>   
                </div> 
                <div class="modal-footer border-top-0 d-flex justify-content-center">
                    <button class="upload-button btn btn-success">Import</button>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
@endsection
@section('foot')
<link href="https://transloadit.edgly.net/releases/uppy/v1.3.0/uppy.min.css" rel="stylesheet">
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>
<script src="https://transloadit.edgly.net/releases/uppy/v1.3.0/uppy.min.js"></script>
<script src="{{ asset('js/papaparse.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
@php
    $client_riders=App\Model\Client\Client_Rider::all();
@endphp
<script>
    
    var client_riders = {!! json_encode($client_riders) !!};
     var uppy = Uppy.Core({
    debug: true,
    autoProceed: false,
    allowMultipleUploads: false,
    restrictions: {
        allowedFileTypes: ['.csv']
    }
});
  uppy.use(Uppy.DragDrop, { 
      target: '.UppyDragDrop',
        
   });
   uppy.on('restriction-failed', (file, error) => {
    // do some customized logic like showing system notice to users
    console.log(error);
    alert(error);
    
    })
  
    $('.upload-button').on('click', function (e) {
        e.preventDefault();

        var files = uppy.getFiles();
        if(files.length<=0){
            alert('Choose .csv file first');
            return;
        }
        Papa.parse(files[0].data, {
            header:true,
            dynamicTyping: true,
            beforeFirstChunk: function( chunk ) {
                var rows = chunk.split( /\r\n|\r|\n/ );
                var headings = rows[0].split( ',' );console.warn(headings);
                headings.forEach(function(_d, _i){
                headings[_i]=_d.trim().replace(/ /g, '_').replace(/[0-9]/g, '').toLowerCase();
                });
                rows[0] = headings.join();
                return rows.join( '\n' );
            },
            error: function(err, file, inputElem, reason){ console.log(err); },
            complete: function(results, file){ 
                console.log( results);
                // ajax to import data
               var import_data = results.data;
               import_data.forEach(function(data, i){
                    var client_rider=client_riders.find(function(x){return x.client_rider_id===data.feid});
                    // delete import_data[i].pl;
                    // delete import_data[i].area;
                    // delete import_data[i].driver_id;
                    // delete import_data[i].driver_name;
                    // delete import_data[i].status;
                    var _riderID = null;
                    if(typeof client_rider !== "undefined"){
                        _riderID=client_rider.rider_id;
                    }
                    import_data[i].rider_id=_riderID;
                });
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : "{{route('import.zomato')}}",
                    type : 'POST',
                    data: {data: import_data},
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
                            title: 'Record imported successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // riders_table.ajax.reload(null, false);
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
        // uppy.upload()
    });

var riders_table;
var riders_data = [];
$(function() {
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
            
        },
        ajax: '{!! route('admin.riders.data') !!}',
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
            { "data": 'missing_fields', "name": 'missing_fields' },
            { "data": 'status', "name": 'status' },
            { "data": 'actions', "name": 'actions' }
        ];
        _settings.responsive=false;
    }
    else{
        $('#riders-table thead tr th').eq(6).before('<th>Date Of Joining</th>');
        $('#riders-table thead tr th').eq(7).before('<th>Passport Expiry</th>');
        $('#riders-table thead tr th').eq(8).before('<th>Visa Expiry</th>');
        $('#riders-table thead tr th').eq(9).before('<th>Licence Expiry</th>');
        $('#riders-table thead tr th').eq(10).before('<th>Mulkiya Expiry</th>');
        _settings.columns=[
        { "data": 'new_id', "name": 'new_id' },
            { "data": 'new_name', "name": 'name' },
            { "data": 'new_email', "name": 'email' },
            { "data": 'sim_number', "name": 'sim_number' },
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
            '<td colspan="1"; style="font-weight:900;" >Phone Number:</td>'+
            '<td colspan="1";>'+data.phone+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Emerate ID:</td>'+
            '<td colspan="1";>'+data.emirate_id+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Email:</td>'+
            '<td colspan="1";>'+data.email+'</td>'+
            
            '</tr>'+
            '<tr>'+
            '<td colspan="1"; style="font-weight:900;">Passport Expiry:</td>'+
            '<td colspan="2";>'+data.passport_expiry+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Visa Expiry:</td>'+
            '<td colspan="2"; >'+data.visa_expiry+'</td>'+
            '<td style="font-weight:900;">Licence Exiry:</td>'+
            '<td colspan="2";>'+data.licence_expiry+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Mulkiya Expiry:</td>'+
            '<td colspan="2";>'+data.mulkiya_expiry+'</td>'+
            '</tr>'+
        '</table>';
}

$("#search_details").on("keyup", function() {
    var _val = $(this).val().trim().toLowerCase();
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
    // $("#riders-table tbody > tr:visible").each(function() {
    //     $(this).removeClass("shown");
    // });
    $('#riders-table tbody > tr').show();
    if (riders_data.length > 0) {
        
        var _res = riders_data.filter(function(x) {
          
            return JSON.stringify(x).indexOf(_val) !== -1;
        });
        
        if (_res.length > 0) {
            $("#riders-table tbody > tr").filter(function(index) {

                var _id = $(this).find("td").eq(1).text().trim().toLowerCase();
                if (_res.findIndex(function(x) {
                        return "1000" + x.id == _id
                    }) === -1) {
                    $(this).hide();
                }
            });
            if(_val !== ''){
                $("#riders-table tbody > tr:visible").each(function() {
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
            }
            $("#riders-table tbody").unmark({
                done: function() {
                    $("#riders-table tbody").mark(_val, {
                        "element": "span",
                        "className": "highlighted"
                    });
                }
            });
        } else {
            $("#riders-table tbody > tr").hide();
        }
    }
    }); 
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