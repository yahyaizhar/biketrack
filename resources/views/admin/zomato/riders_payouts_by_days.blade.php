@extends('admin.layouts.app')
@section('head')
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
                   Rider Payouts By Days
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <div class="checkbox checkbox-danger btn btn-default btn-elevate btn-icon-sm">
                            <input id="check_id" class="checkbox checkbox-danger" type="checkbox">
                            <label for="check_id" >
                               Detailed View
                            </label>
                        </div>
                        &nbsp;
                        <a style="padding:8.45px 13px;" href="" data-toggle="modal" data-target="#import_data"  class="btn btn-label-success btn-sm btn-upper">Import Zomato Time Sheet</a>&nbsp;
                        <input class="btn btn-primary" type="button" onclick="export_data()" value="Export Zomato Time Sheet">
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="ridePerformance-table">
                <thead>
                    <tr>
                        <th>FIED</th>
                        <th>Rider Name</th>
                        <th>Date</th> 
                        <th>Payout For Login Hours</th>
                        <th>Payout For Trips</th>
                        <th>Grand Total</th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
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
<link href="https://transloadit.edgly.net/releases/uppy/v1.3.0/uppy.min.css" rel="stylesheet">
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="https://transloadit.edgly.net/releases/uppy/v1.3.0/uppy.min.js"></script>
<script src="{{ asset('js/papaparse.js') }}" type="text/javascript"></script>
@php
    $client_riders=App\Model\Client\Client_Rider::all();
    $performance_data=App\Model\Rider\Rider_Performance_Zomato::all();
    $riders=App\Model\Rider\Rider::all();
@endphp
<script> 
var _perData = {!! json_encode($performance_data) !!};
var _Riders = {!! json_encode($riders) !!};
var client_riders = {!! json_encode($client_riders) !!};
console.log(  _Riders  );
_perData.forEach(function(x, i){
	if(x.feid){
		var client_rider = client_riders.find(function(z){return x.feid==z.client_rider_id});
		if(client_rider){
			var rider = _Riders.find(function(y){return y.id === parseInt(client_rider.rider_id)});
			x.rider_id = rider.name;
        }
	}
});
function export_data(){
    var export_details=[];
    _perData.forEach(function(item,index) {
        export_details.push({
        "FIED":item.feid,
        "Date":item.date,
        "Area":item.area,
        "Trips":item.trips,
        "ADT":item.adt,
        "Total Logedin Hours":item.total_loged_in_hours,
        "Pickup Time":item.average_pickup_time,
        "Shift Time":item.loged_in_during_shift_time,
        "Drop Time":item.average_drop_time,
        "COD Orders":item.cod_orders,
        "COD Amount":item.cod_amount,
        });
    });
        var export_data = new CSVExport(export_details);
    return false;
}

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
    // console.log(error);
    alert(error);
    
    }).on('file-added', (file) => {
        console.log(file);
        var _fileName = file.name;
        $('.uppy_result').html('<span>'+_fileName+'</span>');
    });
    var _ImportHeading=[],_ImportData=[];
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
                console.log('rows',rows);
                
                var headings = rows[0].split( ',' );console.warn(headings);
                var _lastDate='';
                var _temp=0;
                headings.forEach(function(_d, _i){
                    var _hh = _d.trim();
                    
                    if(_hh==""){
                        //same date
                        
                        headings[_i]= new Date(_lastDate).format('yyyy_mm_dd')+"@"+_temp;
                    }
                    else{
                        //new date came
                        _temp=0;
                        _temp = _i==0?-1:_temp;
                        headings[_i]= new Date(_d.trim()).format('yyyy_mm_dd')+"@"+_temp;
                        _lastDate=_d.trim();
                    }
                    
                     _temp++;
                });
                rows[0] = headings.join();

                headings = rows[1].split( ',' );console.warn(headings);
                headings.forEach(function(_d, _i){
                headings[_i]=_d.trim().replace(/ /g, '_').replace(/[0-9]/g, '').toLowerCase();
                });
                rows[1] = headings.join();
                return rows.join( '\n' );
            },
            // chunk: function(chunk, e, d) {
            //     console.log("Row data:", chunk, e, d);
            // },
            error: function(err, file, inputElem, reason){ console.log(err); },
            complete: function(results, file){ 
                // console.log( results);
                // ajax to import data
               var import_data = results.data;
               console.log(import_data);
               _ImportHeading=[],_ImportData=[];
               import_data.forEach(function(first_chunk, i){
                    var _feid='';   
                    var _firstKey = Object.keys(first_chunk)[0];
                    _feid=first_chunk[_firstKey];
                    
                    Object.keys(first_chunk).forEach(function(second_chunk_key, j){
                        if(j==0) return true;
                        var second_chunk = first_chunk[second_chunk_key];
                        var _date = (second_chunk_key.split('@')[0]).replace(/[_]/g, '-');
                        var _date_index=parseInt(second_chunk_key.split('@')[1]);
                        // var _firstObj=_ImportData.find(function(x){return x.date==_date});
                        // if(typeof _firstObj == "undefined") _ImportData.push({date:_date,rows:[]}),_firstObj=_ImportData.find(function(x){return x.date==_date});
                        if(i==0){
                            _ImportHeading.includes(second_chunk) || _ImportHeading.push(second_chunk);   
                        }
                        else{
                            var _secondObj=_ImportData.find(function(x){return x.feid==_feid && x.date==_date});
			                if(typeof _secondObj == "undefined") _ImportData.push({feid:_feid, date:_date}),_secondObj=_ImportData.find(function(x){return x.feid==_feid&& x.date==_date});
                            _secondObj[_ImportHeading[_date_index]]=second_chunk;
                        }
                    })
                })
                import_data=_ImportData;
                import_data.forEach(function(data0, i){
                    var client_rider=client_riders.find(function(x){return x.client_rider_id===data0.feid});
                    var _riderID = null;
                    if(typeof client_rider !== "undefined"){
                        _riderID=client_rider.rider_id;
                    }
                    import_data[i]['rider_id']=_riderID;
                    
                });
                console.log('import_data',import_data);
                var __data=chunk(import_data,1000);
                moveAlong(__data);
               return;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : "{{route('import.import_rider_daysPayouts')}}",
                    type : 'POST',
                    data: {data: import_data},
                    beforeSend: function() {            
                        $('.bk_loading').show();
                    },
                    complete: function(){
                        $('.bk_loading').hide();
                    },
                    success: function(data){
                        console.warn(data);
                        if(data.status==0){
                            swal.fire({
                                position: 'center',
                                type: 'error',
                                title: 'Oops...',
                                text: data.message,
                                showConfirmButton: true  
                            });
                            return;
                        }
                        
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
        // uppy.upload()
    });
function chunk(array, size) {
    const chunked_arr = [];
    for (let i = 0; i < array.length; i++) {
      const last = chunked_arr[chunked_arr.length - 1];
      if (!last || last.length === size) {
        chunked_arr.push([array[i]]);
      } else {
        last.push(array[i]);
      }
    }
    return chunked_arr;
}
var moveAlong=function(queue){
	if(queue.length>0){
		var _chunk = queue.pop();
		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : "{{route('import.import_rider_daysPayouts')}}",
            type : 'POST',
            data: {data: _chunk},
            beforeSend: function() {            
                $('.bk_loading').show();
            },
            complete: function(){
                $('.bk_loading').hide();
            },
            success: function(data){
                console.warn(data);
                if(data.status==0){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: data.message,
                        showConfirmButton: true  
                    });
                    return;
                }
				moveAlong(queue);
                
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
	else{
		//all data is sended
		swal.fire({
            position: 'center',
            type: 'success',
            title: 'Record imported successfully.',
            showConfirmButton: false,
            timer: 1500
        });
        performance_table.ajax.reload(null, false);
	}
}

var performance_table;
var riders_data = JSON.parse(JSON.stringify(_perData));
$(function() {
    // performance_table = $('').DataTable({
        var _settings={   processing: true,
        serverSide: true,
        lengthMenu: [[100,-1], [100,"All"]],
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
            $('.total_entries').remove();
            $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
            mark_table();
             
        },
        ajax: '{!! route('admin.getRiderPayoutsByDays') !!}',
        columns:null,
        responsive:true,
       
        order:[0,'desc'],
    };

        if(window.outerWidth>=521){
        $('#ridePerformance-table thead tr').prepend('<th></th>');
        _settings.columns=[
            {
            "className":      'details-control',
            "orderable":      false,
            "data":           null,
            "defaultContent": ''
        },
            { "data": 'feid', "name": 'feid' },
            { "data": 'rider_name', "name": 'rider_name' },
            { "data": 'date', "name": 'date' },
            { "data": 'payout_for_login_hours', "name": 'payout_for_login_hours' },
            { "data": 'payout_for_trips', "name": 'payout_for_trips' },
            { "data": 'grand_total', "name": 'grand_total' },
            { "data": 'login_hours', "name": 'login_hours' },
            { "data": 'trips', "name": 'trips' },
        ];
        _settings.responsive=false;
        _settings.columnDefs=[
            {
                "targets": [ 7,8],
                "visible": false,
                searchable: true,
            },
        ];
    }
    else{
        $('#ridePerformance-table thead tr th').eq(5).before('<th>Hours:</th>');
        $('#ridePerformance-table thead tr th').eq(6).before('<th>Trips:</th>');
       
        _settings.columns=[
            { "data": 'feid', "name": 'feid' },
            { "data": 'rider_name', "name": 'rider_name' },
            { "data": 'date', "name": 'date' },
            { "data": 'payout_for_login_hours', "name": 'payout_for_login_hours' },
            { "data": 'payout_for_trips', "name": 'payout_for_trips' },
            { "data": 'grand_total', "name": 'grand_total' },
            { "data": 'login_hours', "name": 'login_hours' },
            { "data": 'trips', "name": 'trips' },
        ];
     
    }
    performance_table = $('#ridePerformance-table').DataTable(_settings);
    var mark_table = function(){
        var _val = performance_table.search();
        if(_val===''){
            $("#ridePerformance-table tbody").unmark();
            $("#ridePerformance-table tbody > tr:visible").each(function() {
                var tr = $(this);
                var row = performance_table.row( tr );
                if ( row.child.isShown() ) {
                    row.child.remove();
                    tr.removeClass('shown');
                }
            });
            return;
        }
        $('#ridePerformance-table tbody > tr[role="row"]:visible').each(function() {
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
        $("#ridePerformance-table tbody").unmark({
            done: function() {
                $("#ridePerformance-table tbody").mark(_val, {
                    "element": "span",
                    "className": "highlighted"
                });
            }
        });
        
    }
    if(window.outerWidth>=521){
        $('#ridePerformance-table tbody').on('click', 'td.details-control', function () {
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
            '<td colspan="1"; style="font-weight:900;">Hours:</td>'+
            '<td colspan="2";>'+data.login_hours+'</td>'+
            '<td colspan="1"; style="font-weight:900;" >Trips:</td>'+
            '<td colspan="2";>'+data.trips+'</td>'+
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

function delete_lastImport()
{
    var url = "{{ url('admin/delete/last/import') }}";
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