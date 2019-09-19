@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
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
        .watermark{
            background: transparent;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 146%;
            pointer-events: none; 
        }
        #watermark-text
        {
            color: #f5f5f5;
            font-size: 25rem;
            opacity: 0.5;
            background: transparent;
        }
        .table th, .table td{
            padding:0 !important;
        }
    </style>
    <!--end::Page Vendors Styles -->
@endsection 
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        Months
                        </h3>
                    </div>
                </div>
 @include('client.includes.message')
<div class="kt-portlet__body">
<div>
    <select class="form-control kt-select2" id="kt_select2_3_5" name="month_id">
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
<div class="kt-widget24">
<div class="kt-widget24__details">
    <a class="kt-widget24__info">
        <h4 class="kt-widget24__title">Overall Balance</h4>
        <span class="kt-widget24__stats kt-font-success" id="overall_balnce">0</span>
    </a>
    <a class="kt-widget24__info">
        <h4 class="kt-widget24__title">Total Profit</h4>
        <span class="kt-widget24__stats kt-font-primary" id="total_profit">0</span>
    </a>
    <a class="kt-widget24__info">
        <h4 class="kt-widget24__title">Overall Balance Monthly</h4>
        <span class="kt-widget24__stats kt-font-warning" id="overall_balnce_monthly">0</span>
    </a>
    <a class="kt-widget24__info">
        <h4 class="kt-widget24__title">Payable To Riders</h4>
        <span class="kt-widget24__stats kt-font-danger" id="payable_to_riders">0</span>
    </a>
</div>
</div>
</div>
</div>
</div>
</div>
{{-- Month OPTIONS --}}
        
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content-b">
    <div class="kt-portlet kt-portlet--mobile" style="position: relative;">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    View Company overall Report
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
            <table class="table table-striped- table-hover table-checkable table-condensed" id="CO_report">
                <thead>
                    <tr> 
                        <th>Date</th>
                        <th>Description</th>
                        <th>Credit</th>
                        <th>Debit</th>                    
                    </tr>
                </thead>
            </table>
        </div>
        <div class="watermark"></div> 
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script>
    var month=null;
    var rider=null;
$(document).ready(function(){
    $('.kt-select2').select2({
        placeholder: "Select a month",
        width:'100%'    
    });
    
    $("#kt_select2_3_5").change(function(){
    $('#kt_content-b').show();
    if(rider !== null){
        rider.destroy();
     }
     rider =$('#CO_report').DataTable({
        lengthMenu: [[-1], ["All"]],
        destroy: true,
        ordering: false,
        processing: true,
        serverSide: true,
        'language': { 
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
	    $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
        $('.watermark').html('<p id="watermark-text">C</p>');

        var response = rider.ajax.json(); 
        var _overall_balnce = response.overall_balnce;
        var _total_profit = response.total_profit;
        var _overall_balnce_monthly = response.overall_balnce_monthly;
        var _payable_to_riders = response.payable_to_riders;

        $('#overall_balnce').text(_overall_balnce);
        $('#total_profit').text(_total_profit);
        $('#overall_balnce_monthly').text(_overall_balnce_monthly);
        $('#payable_to_riders').text(_payable_to_riders);
    	},
     ajax: "{{url('admin/Company/Overall/Report/ajax')}}"+"/"+$(this).val(),
     columns: [
            { data: 'month', name: 'month' },
            { data: 'description', name: 'description' },
            {data:'cr',name:'cr'},
            {data:'dr',name:'dr'}, 

        ],
        responsive:true,
        // order:[0,'desc'], 
    });
  });
  $("#kt_select2_3_5").val('{{carbon\carbon::now()->format('m')}}').trigger('change');

});
</script>
    @endsection