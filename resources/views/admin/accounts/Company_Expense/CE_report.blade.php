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
    <select class="form-control kt-select2" id="kt_select2_3_5" name="month_id" >
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
    <a href="https://kingridersapp.solutionwin.net/admin/livemap" class="kt-widget24__info">
        <h4 class="kt-widget24__title">
          Expense Balance
        </h4>
        <span class="kt-widget24__stats kt-font-success" id="closing_balance">
        @php
            $expense=App\Model\Accounts\Company_Expense::where("active_status","A")->sum("amount");
        @endphp
            {{$expense}}
        </span>
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
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    View Company Expense Report
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

            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="CE_report">
                <thead>
                    <tr>
                        
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount(dr)</th>
                                               
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

{{-- end Month OPTIONS --}}
@endsection
@section('foot')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script>
    var month=null;
    var rider=null;
$(document).ready(function(){
    
    $("#kt_select2_3_5").change(function(){
    $('#kt_content-b').show();
    if(rider !== null){
        rider.destroy();
     }
     rider =$('#CE_report').DataTable({
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
    	},
     ajax: "{{url('admin/CE/Report/ajax')}}"+"/"+$(this).val(),
     columns: [
            //  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'date', name: 'date' },
            { data: 'description', name: 'description' },
            {data:'amount',name:'amount'},

        ],
        responsive:true,
        // order:[0,'desc'], 
    });
  });

});





</script>
    @endsection