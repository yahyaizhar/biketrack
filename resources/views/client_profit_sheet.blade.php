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
<!-- begin:: Content -->
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        {{$client->name}} Profit Sheet(Month) 
                        
                        </h3>
                    </div>
                </div>
 @include('client.includes.message')
            <div class="kt-portlet__body">
            <div>
                <select class="form-control bk-select2" id="kt_select2_3_5" name="month_id" >
                    @for ($i = 0; $i <= 12; $i++)
                    @php
                        $_m =Carbon\Carbon::now()->startOfMonth()->addMonth(-$i);
                    @endphp
                    <option value="{{$_m->format('Y-m-d')}}">{{$_m->format('F-Y')}}</option>
                @endfor    
                </select> 
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content-b">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    {{$client->name}} Profit Sheet
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
                        {{-- <a style="padding:8.45px 13px;" href="" data-toggle="modal" data-target="#import_data"  class="btn btn-label-success btn-sm btn-upper">Import Zomato Income</a>&nbsp; --}}
                        <input class="btn btn-primary" type="button" onclick="export_data();" value="Export Profit Sheet">
                    </div>
                </div>
            </div>
        </div>
            <table class="table table-striped- table-hover table-checkable table-condensed" id="zomato_salary_sheet">
                <thead>
                    <tr>
                        <th>Rider Name</th>
                        {{-- <th>Bike Number</th> --}}
                        <th>Total Payout</th>
                        <th>Salary + Bonus</th>
                        <th>Expense & Bills</th>
                        <th>Profit</th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
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
                        <a style="padding:8.45px 13px;" class="show-report btn btn-label-success btn-sm btn-upper">Show Report</a>&nbsp;
                  </div>
                </form>
                {{-- <button class="btn btn-danger"  onclick="delete_lastImport();return false;"><i class="fa fa-trash"></i> Delete Last Import</button> --}}
              </div>
            </div>
          </div>
       </div>
       <input type="hidden" name="client_id" value="{{$client->id}}" >
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
@endphp
<script>
        function export_data(){
var export_details=[];
console.log(riders_data);
        riders_data.forEach(function(item,index) {
           export_details.push({
            "Client ID":item.client_id,
            "Rider":item.rider_name,

            "Bike Rent":item.bike_rent,
            "Fuel":item.fuel,
            "Salik":item.salik,
            "Sim Charges":item.sim_charges,
            "Expenses Bills":item.expenses_bills,

            "Zomato Hours AED":item.aed_hours_zomato,  
            "Zomato Trips AED":item.aed_trips_zomato,
            "Total Payout":item.payout_less,

            "Trips":item.number_of_trips,
            "AED Trips":item.aed_trips,
            "Hours":item.number_of_hours,
            "AED Hours":item.aed_hours,
            "Bonus":item.bonus,
            "Salary":item.net_salary,
            
            "Profit":item.profit,
            "Decipline Fine":item.decipline_fine,
           });
        });
        var export_data = new CSVExport(export_details, 'Zomato Profit Sheet '+$('[name="month_id"] option:selected').text());
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
    }).on('file-added', (file) => {
        console.log(file);
        var _fileName = file.name;
        $('.uppy_result').html('<span>'+_fileName+'</span>');
    });
  
    $('.show-report').on('click', function (e) {
        e.preventDefault();
        $('#import_data').modal('hide');
        var files = uppy.getFiles();
        if(files.length<=0){
            alert('Choose .csv file first');
            return;
        }
        $('#report_data').modal('show'); 
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
                var amount_for_login_hours=0;
                var trips_payable=0;
                var total_to_be_paid_out=0;
                var log_in_hours_payable=0;
                var amount_to_be_paid_against_orders_completed=0;
                var date=0;
                import_data.forEach(function( index, value ) {
                    amount_for_login_hours+=index['amount_for_login_hours'];
                    trips_payable+=index['trips_payable'];
                    total_to_be_paid_out+=index['total_to_be_paid_out'];
                    log_in_hours_payable+=index['log_in_hours_payable'];
                    amount_to_be_paid_against_orders_completed+=index['amount_to_be_paid_against_orders_completed'];
                    date=index['onboarding_date'];
                });
                var taxable_amount=(amount_for_login_hours / 100)*5;
                var total_amount_with_tax=total_to_be_paid_out+taxable_amount;
                $('[name="amount_for_login_hours"]').val(amount_for_login_hours.toFixed(2));
                $('[name="trips_payable"]').val(trips_payable.toFixed(2));
                $('[name="total_to_be_paid_out"]').val(total_to_be_paid_out.toFixed(2));
                $('[name="total_to_be_paid_out_with_tax"]').val(total_amount_with_tax.toFixed(2)); 
                $('[name="log_in_hours_payable"]').val(log_in_hours_payable.toFixed(2));
                $('[name="taxable_amount"]').val(taxable_amount.toFixed(2));
                $('[name="amount_to_be_paid_against_orders_completed"]').val(amount_to_be_paid_against_orders_completed.toFixed(2));

               $('.upload-button').off('click').on("click",function(e){
                   e.preventDefault(); 
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : "{{route('admin.accounts.income_zomato_import')}}",
                    type : 'POST',
                    data: {
                        data: import_data,
                        tax_data: {
                            amount_for_login_hours:amount_for_login_hours,
                            trips_payable:trips_payable,
                            total_to_be_paid_out:total_to_be_paid_out,
                            log_in_hours_payable:log_in_hours_payable,
                            amount_to_be_paid_against_orders_completed:amount_to_be_paid_against_orders_completed,
                            taxable_amount:taxable_amount,
                            total_amount_with_tax:total_amount_with_tax,
                            date:date,
                        }
                    },
                    beforeSend: function() {            
                        $('.loading').show();
                    },
                    complete: function(){
                        $('.loading').hide();
                    },
                    success: function(data){
                        $('#report_data').modal('hide');
                        // console.log(data);
                        swal.fire({
                            position: 'center',
                            type: 'success',
                            title: 'Record imported successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        salary_sheet.ajax.reload(null, false);
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
               });
            }
        });
    });

// table accordian start
var salary_sheet;
var riders_data = [];
$('#kt_content-b').hide(); 
$("#kt_select2_3_5").change(function(){
    $('th#remove_head').remove();
    $('#kt_content-b').show(); 
    var month=$(this).val();
    var month_url=new Date(month).format("yyyy-mm-dd");
   var client_id=$('[name="client_id"]').val();
   console.log(client_id);
   var data = {
                month:month_url, 
            }
    biketrack.updateURL(data);
$(function() {
    // salary_sheet = $('').DataTable({
        var _settings={   processing: true,
        serverSide: true,
        destroy:true,
        lengthMenu: [[-1], ["All"]],
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
        // ajax: '{!! route('admin.accounts.income_zomato_ajax') !!}',
        ajax: "{{url('admin/zomato/profit/sheet/export/ajax/')}}"+"/"+month+"/"+client_id,
        columns:null,
        responsive:true,
        
        order:[0,'desc'],
    };

        if(window.outerWidth>=521){
        //visa_expiry
        $('#zomato_salary_sheet thead tr').prepend('<th id="remove_head"></th>');
        _settings.columns=[
            {
            "className":      'details-control',
            "orderable":      false,
            "data":           null,
            "defaultContent": ''
            },
            { "data": 'rider_name', "name": 'rider_name' },
            // { "data": 'bike_number', "name": 'bike_number' },
            { "data": 'payout', "name": 'payout' },
            { "data": 'net_salary', "name": 'net_salary' },
            { "data": 'expenses_bills', "name": 'expenses_bills' },
            { "data": 'profit', "name": 'profit' },

            { "data": 'number_of_trips', "name": 'number_of_trips' },
            {"data": 'number_of_hours', "name": 'number_of_hours'},
            { "data": 'ncw', "name": 'ncw' },
            { "data": 'tips', "name": 'tips' },
            { "data": 'salik', "name": 'salik' },
            { "data": 'sim_charges', "name": 'sim_charges' },
            { "data": 'fuel', "name": 'fuel' },
            { "data": 'dc', "name": 'dc' },
            { "data": 'cod', "name": 'cod' },
            { "data": 'penalty', "name": 'penalty' },
            { "data": 'bike_rent', "name": 'bike_rent' },
            { "data": 'bonus', "name": 'bonus' },
            { "data": 'aed_trips', "name": 'aed_trips' },
            { "data": 'aed_hours', "name": 'aed_hours' },
            { "data": 'decipline_fine', "name": 'decipline_fine' },
            
        ];
        _settings.columnDefs=[
            {
                "targets": [6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],
                "visible": false,
                searchable: true, 
            },
        ],
        
        _settings.responsive=false;
    }
    else{
        
        
        // $('#zomato_salary_sheet thead tr th').eq(6).before('<th>Trip Date:</th>');
        // $('#zomato_salary_sheet thead tr th').eq(7).before('<th>Trip Time:</th>');
        // $('#zomato_salary_sheet thead tr th').eq(8).before('<th>Transaction Post Date:</th>');
        _settings.columns=[
            { "data": 'rider_name', "name": 'rider_name' },
            // { "data": 'bike_number', "name": 'bike_number' },
            { "data": 'payout', "name": 'payout' },
            { "data": 'net_salary', "name": 'net_salary' },
            { "data": 'expenses_bills', "name": 'expenses_bills' },
            { "data": 'profit', "name": 'profit' },

            { "data": 'number_of_trips', "name": 'number_of_trips' },
            {"data": 'number_of_hours', "name": 'number_of_hours'},
            { "data": 'ncw', "name": 'ncw' },
            { "data": 'tips', "name": 'tips' },
            { "data": 'salik', "name": 'salik' },
            { "data": 'sim_charges', "name": 'sim_charges' },
            { "data": 'fuel', "name": 'fuel' },
            { "data": 'dc', "name": 'dc' },
            { "data": 'cod', "name": 'cod' },
            { "data": 'penalty', "name": 'penalty' },
            { "data": 'bike_rent', "name": 'bike_rent' },
            { "data": 'bonus', "name": 'bonus' },
            { "data": 'aed_trips', "name": 'aed_trips' },
            { "data": 'aed_hours', "name": 'aed_hours' },
            { "data": 'decipline_fine', "name": 'decipline_fine' },
            
        ];
     
    }
    var mark_table = function(){
        var _val = salary_sheet.search();
        if(_val===''){
            $("#zomato_salary_sheet tbody").unmark();
            $("#zomato_salary_sheet tbody > tr:visible").each(function() {
                var tr = $(this);
                var row = salary_sheet.row( tr );
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.remove();
                    tr.removeClass('shown');
                }
            });
            return;
        }
        $('#zomato_salary_sheet tbody > tr[role="row"]:visible').each(function() {
            var tr = $(this);
            var row = salary_sheet.row( tr );
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
        $("#zomato_salary_sheet tbody").unmark({
            done: function() {
                $("#zomato_salary_sheet tbody").mark(_val, {
                    "element": "span",
                    "className": "highlighted" 
                });
            }
        }); 
        
    }
    salary_sheet = $('#zomato_salary_sheet').DataTable(_settings);
    salary_sheet.on( 'search.dt', function () {
        mark_table();
    });

    
});
});
function format ( data ) {
    // `d` is the original data object for the row

    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr>'+
                '<td colspan="1"; style="font-weight:900;">Total Payout:</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;" >AED Hours:</td>'+
                '<td colspan="1";>'+data.aed_hours_zomato+'</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;">AED Trips:</td>'+
                '<td colspan="1";>'+data.aed_trips_zomato+'</td>'+
                '<td colspan="1"; style="font-weight:900;">Total:</td>'+
                '<td colspan="1";>'+data.payout+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td colspan="1"; style="font-weight:900;">Salary + Bonus:</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;">No of Trips :</td>'+
                '<td colspan="1";>'+data.number_of_trips+'</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;" >No of Hours:</td>'+
                '<td colspan="1";>'+data.number_of_hours+'</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;" >AED Hours:</td>'+
                '<td colspan="1";>'+data.aed_hours+'</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;">AED Trips:</td>'+
                '<td colspan="1";>'+data.aed_trips+'</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;" >Bonus:</td>'+
                '<td colspan="1";>'+data.bonus+'</td>'+
                '<td colspan="1"; style="font-weight:900;" >Total:</td>'+
                '<td colspan="1";>'+data.net_salary+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td colspan="1"; style="font-weight:900;">Expense & Bills:</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;" >Bike Rent:</td>'+
                '<td colspan="1";>'+data.bike_rent+'</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;" >Fuel:</td>'+
                '<td colspan="1";>'+data.fuel+'</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;" >Salik:</td>'+
                '<td colspan="1";>'+data.salik+'</td>'+
                '<td colspan="1"; style="font-weight:500;font-size: 11px;">Sim:</td>'+
                '<td colspan="1";>'+data.sim_charges+'</td>'+
                '<td colspan="1"; style="font-weight:900;">Total:</td>'+
                '<td colspan="1";>'+data.expenses_bills+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td colspan="1"; style="font-weight:900;">Profit:</td>'+
                '<td colspan="2";>'+data.profit+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td colspan="1"; style="font-weight:900;">Decipline Fine:</td>'+
                '<td colspan="2";>'+data.decipline_fine+'</td>'+
            '</tr>'+
        '</table>';
}

    if(window.outerWidth>=521){
        $(document).on('click', 'td.details-control', function () {
            
            var tr = $(this).closest('tr');
            var row = salary_sheet.row( tr );
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
var mon=biketrack.getUrlParameter('month');
if (mon!="") {
    $('#kt_select2_3_5').val(biketrack.getUrlParameter('month')).trigger('change');
}
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
                    salary_sheet.ajax.reload(null, false);
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
                    salary_sheet.ajax.reload(null, false);
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