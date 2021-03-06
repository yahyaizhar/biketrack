@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    
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
<!-- begin:: Content -->
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                   Trip Details
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        &nbsp;
                        <div class="checkbox checkbox-danger btn btn-default btn-elevate btn-icon-sm">
                            <input id="check_id" class="checkbox checkbox-danger" type="checkbox">
                            <label for="check_id" >
                               Detailed View
                            </label>
                        </div>
                        &nbsp;
                        
                        <a style="padding:8.45px 13px;" href="" data-toggle="modal" data-target="#import_data"  class="btn btn-label-success btn-sm btn-upper">Import Trip Detail</a>&nbsp;
                        <input class="btn btn-primary" type="button" onclick="export_data();" value="Export Trip Detail">
                        {{-- <a href="{{ route('Sim.new_sim') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a> --}}
                        </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="trip_details">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>Transaction ID</th>
                        <th>Toll Gate</th>
                        <th>Direction</th> 
                        <th>Tag Number</th>
                        <th>Plate</th>
                        <th>Amount(AED)</th>
                        {{-- <th>Actions</th> --}}
                        <th class="d-none">h1</th>
                        <th class="d-none">h2</th>
                        <th class="d-none">h3</th>
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
                        <div class="card card-body bg-light py-1 mt-1 uppy_result">
                            <span></span>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 d-flex justify-content-center">
                        <button class="upload-button btn btn-success">Import</button>
                        </div>
                </form>
                <button class="btn btn-danger"  onclick="delete_lastImport();return false;"><i class="fa fa-trash"></i> Delete Last Import</button>
              </div>
            </div>
          </div>
       </div>
@endsection
@section('foot')
<!--begin::Page Vendors(used by this page) -->
<link href="https://transloadit.edgly.net/releases/uppy/v1.3.0/uppy.min.css" rel="stylesheet">
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>


<!--end::Page Vendors -->


<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="https://transloadit.edgly.net/releases/uppy/v1.3.0/uppy.min.js"></script>
<script src="{{ asset('js/papaparse.js') }}" type="text/javascript"></script>
{{-- <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script> --}}

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> --}}
<!--end::Page Scripts -->
@php
    $client_riders=App\Model\Client\Client_Rider::all();
@endphp
<script>

    function export_data(){
var export_details=[];
        riders_data.forEach(function(item,index) {
           export_details.push({
            "Transaction ID":item.transaction_id,
            "Toll Gate":item.toll_gate,
            "Direction":item.direction,
            "Tag Number":item.tag_number,
            "Plate":item.plate,
            "Amount(AED)":item.amount_aed,
            "Trip Date":item.trip_date,
            "Trip Time":item.trip_time,
            "Transaction_post_date":item.transaction_post_date,
           });
        });
        var export_data = new CSVExport(export_details);
        return false;
    }

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
    alert(error);
    })
    .on('file-added', (file) => {
        console.log(file);
        var _fileName = file.name;
        $('.uppy_result').html('<span>'+_fileName+'</span>');
    });
  
    $('.upload-button').on('click', function (e) {
        e.preventDefault();
        $('#import_data').modal('hide');
        var files = uppy.getFiles();
        if(files.length<=0){
            alert('Choose .csv file first');
            return;
        }
        Papa.parse(files[files.length-1].data, {
            header:true,
            dynamicTyping: true,
            beforeFirstChunk: function( chunk ) {
                var rows = chunk.split( /\r\n|\r|\n/ );
                var headings = rows[0].split( ',' );console.warn(headings);
                headings.forEach(function(_d, _i){
                headings[_i]=_d.trim().replace(/ /g, '_').replace(/[0-9]/g, '').replace('(AED)', '_aed').toLowerCase();
                });
                rows[0] = headings.join();
                return rows.join( '\n' );
            }, 
            error: function(err, file, inputElem, reason){ console.log(err); },
            complete: function(results, file){ 
               var import_data = results.data;
               console.log(import_data);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : "{{route('import.salik')}}",
                    type : 'POST',
                    data: {data: import_data},
                    beforeSend: function() {            
                        $('.loading').show();
                    },
                    complete: function(){
                        $('.loading').hide();
                    },
                    success: function(data){
                        // console.log(data);
                        swal.fire({
                            position: 'center',
                            type: 'success',
                            title: 'Record imported successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        performance_table.ajax.reload(null, false);
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
    });

// table accordian start

var performance_table;
var riders_data = [];

$(function() {
    // performance_table = $('').DataTable({
        var _settings={   processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        // dom: 'lrBtip',
        // buttons: [
        //     { 
        //       extend: 'csv',
        //       className: 'btn btn-primary mx-3 float-right',
        //       text:'Export Trip Detail' 
        //     },
        // ],
        drawCallback:function(data){
            var api = this.api();
            var _data = api.data();
            var keys = Object.keys(_data).filter(function(x){return !isNaN(parseInt(x))});
            keys.forEach(function(_d,_i) {
                var __data = JSON.parse(JSON.stringify(_data[_d]).toLowerCase());
                riders_data.push(__data);
            });
            $('.total_entries').remove();
            $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');  
       mark_table();
        },
        ajax: '{!! route('bike.ajax_salik_bike',$bike->id) !!}',
        columns:null,
        responsive:true,
       
        order:[0,'desc'],
    };

        if(window.outerWidth>=521){
        //visa_expiry
        $('#trip_details thead tr').prepend('<th></th>');
        _settings.columns=[
            {
            "className":      'details-control',
            "orderable":      false,
            "data":           null,
            "defaultContent": ''
        },
        // { "data": 'new_id', "name": 'new_id' },
            { "data": 'transaction_id', "name": 'transaction_id' },
            { "data": 'toll_gate', "name": 'toll_gate' },
            { "data": 'direction', "name": 'direction' },
            { "data": 'tag_number', "name": 'tag_number' },
            { "data": 'plate', "name": 'plate' },
            { "data": 'amount_aed', "name": 'amount_aed' },
            // { "data": 'actions', "name": 'actions' }
            { "data": 'trip_date', "name": 'trip_date' },
            { "data": 'trip_time', "name": 'trip_time' },
            { "data": 'transaction_post_date', "name": 'transaction_post_date' },
        ];
        _settings.columnDefs=[
            {
                "targets": [ 7,8,9 ],
                "visible": false,
                searchable: true,
            },
        ],
        _settings.responsive=false;
    }
    else{
        
        
        $('#trip_details thead tr th').eq(6).before('<th>Trip Date:</th>');
        $('#trip_details thead tr th').eq(7).before('<th>Trip Time:</th>');
        $('#trip_details thead tr th').eq(8).before('<th>Transaction Post Date:</th>');
        _settings.columns=[
            { "data": 'transaction_id', "name": 'transaction_id' },
            { "data": 'toll_gate', "name": 'toll_gate' },
            { "data": 'direction', "name": 'direction' },
            { "data": 'tag_number', "name": 'tag_number' },
            { "data": 'plate', "name": 'plate' },
            { "data": 'amount_aed', "name": 'amount_aed' },
            { "data": 'trip_date', "name": 'trip_date' },
            { "data": 'trip_time', "name": 'trip_time' },
            { "data": 'transaction_post_date', "name": 'transaction_post_date' },
            
        ];
     
    }
    performance_table = $('#trip_details').DataTable(_settings);
    var mark_table = function(){
        var _val = performance_table.search();
        if(_val===''){
            $("#trip_details tbody").unmark();
            $("#trip_details tbody > tr:visible").each(function() {
                var tr = $(this);
                var row = performance_table.row( tr );
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.remove();
                    tr.removeClass('shown');
                }
            });
            return;
        }
        $('#trip_details tbody > tr[role="row"]:visible').each(function() {
            var tr = $(this);
            var row = performance_table.row( tr );
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
        $("#trip_details tbody").unmark({
            done: function() {
                $("#trip_details tbody").mark(_val, {
                    "element": "span",
                    "className": "highlighted"
                });
            }
        });
        
    }
    if(window.outerWidth>=521){
        $('#trip_details tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = performance_table.row( tr );
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

    function format ( data ) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr>'+
            '<td colspan="1"; style="font-weight:900;">Trip Date:</td>'+
            '<td colspan="2";>'+data.trip_date+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Trip time:</td>'+
            '<td colspan="2";>'+data.trip_time+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Transaction_post_date:</td>'+
            '<td colspan="1";>'+data.transaction_post_date+'</td>'+
            '</tr>'+
            
        '</table>';
}

    
    if(window.outerWidth>=521){
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
    else if(window.outerWidth<521){
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



    



// table accordian end
function delete_lastImport()
{
    var url = "{{ url('admin/delete/last/import/salik') }}";
    console.log(url);
    swal.fire({
        title: 'Are you sure?',
        text: "You want delete last record!",
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
                type : 'delete',
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
                        title: 'Record deleted successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    performance_table.ajax.reload(null, false);
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to delete.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}
function updateStatus(sim_id)
{
    var url = "{{ url('admin/sim') }}" + "/" + sim_id + "/updateStatus";
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
                    performance_table.ajax.reload(null, false);
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