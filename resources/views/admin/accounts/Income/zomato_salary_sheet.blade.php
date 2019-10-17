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
                        Zomato Salary Sheet(Month) 
                        
                        </h3>
                    </div>
                </div>
 @include('client.includes.message')
                 <div class="kt-portlet__body">
                            <div>
                                 <select class="form-control bk-select2" id="kt_select2_3_5" name="month_id" >
                                <option >Select Month</option>
                                <option value="01">January</option>   
                                <option value="02">Febuary</option>   
                                <option value="03">March</option>   
                                <option value="04">April</option>   
                                <option value="05">May</option>   
                                <option value="06">June</option>   
                                <option value="07">July</option>   
                                <option value="08">August</option>   
                                <option value="09">September</option>   
                                <option value="10">October</option>   
                                <option value="11">November</option>   
                                <option value="12">December</option>    
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
                   Zomato Salary Sheet
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
                        
                        {{-- <a style="padding:8.45px 13px;" href="" data-toggle="modal" data-target="#import_data"  class="btn btn-label-success btn-sm btn-upper">Import Zomato Income</a>&nbsp; --}}
                        <input class="btn btn-primary" type="button" onclick="export_data();" value="Export Zomato Salary Sheet">
                        
                        {{-- <a href="{{ route('Sim.new_sim') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a> --}}
                        </div>
                </div>
            </div>
        </div>
        
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="zomato_salary_sheet">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>Rider Name</th>
                        <th>Bike Number</th>
                        <th>Advance</th> 
                        <th>Salik</th>
                        <th>Sim Charges</th>
                        <th>DC</th>
                        <th>COD</th>
                        <th>RTA Fine</th>
                        <th>Dicipline Fine</th>
                        <th>Total Deduction</th>
                        <th>AED Hours</th>
                        <th>Total</th>

                        <th>Poor Performance</th>
                        <th>visa</th>
                        <th>Mobile</th>
                        <th>Number of Hours</th>
                        <th>Number Of trips</th>
                        <th>AED_Trips</th>
                        <th>NCW</th>
                        <th>TIPS</th>
                        
                        <th>Net Salary</th>
                        <th>Gross Salary</th>
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
                        <a style="padding:8.45px 13px;" class="show-report btn btn-label-success btn-sm btn-upper">Show Report</a>&nbsp;
                  </div>
                </form>
                {{-- <button class="btn btn-danger"  onclick="delete_lastImport();return false;"><i class="fa fa-trash"></i> Delete Last Import</button> --}}
              </div>
            </div>
          </div>
       </div>
       {{-- import data --}}
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
            "Name":item.name,
            "Bike No": item.bike_number,
            "Advance":item.advance,
            "Salik":item.salik,
            "Sim Charges":item.sim_charges,
            "POOR PERFORMANCE":item.poor_performance,
            "DC":item.dc,
            "COD ":item.cod,
            "Visa Charges":item.visa,
            "RTA Fine":item.rta_fine,
            "mobile charges":item.mobile,
            "displan fine":item.dicipline_fine,
            "Total deduction":item.total_deduction,
            "No of hours ":item.number_of_hours,
            "No of Trip ":item.number_of_trips,
            "AED hours":item.aed_hours,
            "AED TRIP":item.aed_trips,
            "Total":item.total_salary,
            "NCW":item.ncw,
            "Tip":item.tips,
            "Bones":'',
            "Bike allowns":'',
            "Net Salary ":item.net_salary,
            "Gross Salary":item.gross_salary,
            "cash paid":'',
            "advance":'',
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
        ajax: "{{url('admin/zomato/salary/sheet/export/ajax')}}"+"/"+month,
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
            { "data": 'bike_number', "name": 'bike_number' },
            { "data": 'advance', "name": 'advance' },
            { "data": 'salik', "name": 'salik' },
            { "data": 'sim_charges', "name": 'sim_charges' },
            { "data": 'dc', "name": 'dc' },
            { "data": 'cod', "name": 'cod' },
            { "data": 'rta_fine', "name": 'rta_fine' },
            { "data": 'dicipline_fine', "name": 'dicipline_fine' },
            { "data": 'total_deduction', "name": 'total_deduction' },
            { "data": 'aed_hours', "name": 'aed_hours' },
            { "data": 'total_salary', "name": 'total_salary' },
            
            { "data": 'poor_performance', "name": 'poor_performance' },
            { "data": 'visa', "name": 'visa' },
            { "data": 'mobile', "name": 'mobile' },
            {"data": 'number_of_hours', "name": 'number_of_hours'},
            { "data": 'number_of_trips', "name": 'number_of_trips' },
            { "data": 'aed_trips', "name": 'aed_trips' },
            { "data": 'ncw', "name": 'ncw' },
            { "data": 'tips', "name": 'tips' },
            
            { "data": 'net_salary', "name": 'net_salary' },
            { "data": 'gross_salary', "name": 'gross_salary' },
        ];
        _settings.columnDefs=[
            {
                // "targets": [ 7,8,9, 10 ],
                "visible": false,
                searchable: true, 
            },
        ],
        
        _settings.responsive=false;
    }
    else{
        
        
        $('#zomato_salary_sheet thead tr th').eq(6).before('<th>Trip Date:</th>');
        $('#zomato_salary_sheet thead tr th').eq(7).before('<th>Trip Time:</th>');
        $('#zomato_salary_sheet thead tr th').eq(8).before('<th>Transaction Post Date:</th>');
        _settings.columns=[
            { "data": 'rider_name', "name": 'rider_name' },
            // { "data": 'total_to_be_paid_out', "name": 'total_to_be_paid_out' },
            // { "data": 'amount_for_login_hours', "name": 'amount_for_login_hours' },
            // { "data": 'amount_to_be_paid_against_orders_completed', "name": 'amount_to_be_paid_against_orders_completed' },
            // { "data": 'ncw_incentives', "name": 'ncw_incentives' },
            // // { "data": 'actions', "name": 'actions' }
            // { "data": 'tips_payouts', "name": 'tips_payouts' },
            // { "data": 'mcdonalds_deductions', "name": 'mcdonalds_deductions' },
            // { "data": 'dc_deductions', "name": 'dc_deductions' },
            // { "data": 'date', "name": 'date' },
            // { "data": 'feid', "name": 'feid' },
            
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
    if(window.outerWidth>=521){
        $('#zomato_salary_sheet tbody').on('click', 'td.details-control', function () {
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
    }

    function format ( data ) {
    // `d` is the original data object for the row
    // return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
    //         '<tr>'+
    //         '<td colspan="1"; style="font-weight:900;">DC Deductions:</td>'+
    //         '<td colspan="2";>'+data.dc_deductions+'</td>'+
    //         '<td colspan="1"; style="font-weight:900;" >Mcdonalds Deductions:</td>'+
    //         '<td colspan="2";>'+data.mcdonalds_deductions+'</td>'+
    //         '<td colspan="1"; style="font-weight:900;" >Date:</td>'+
    //         '<td colspan="2";>'+data.date+'</td>'+
    //         '</tr>'+
            
    //     '</table>';
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