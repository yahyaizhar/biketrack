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
                Expense Data
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
            <table class="table table-striped- table-hover table-checkable table-condensed" id="expense-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Source</th>
                        <th>Rider</th>
                        <th>Month</th>
                        <th>Given Date</th>
                        <th>Amount</th>
                        {{-- <th>Payment Status</th>                         --}}
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script>
var expense_table;
$(document).ready(function(){
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
    expense_table = $('#expense-table').DataTable({
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
       ajax: "{{ url('admin/account/expense_data/ajax') }}" + "/" + _month,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'source', name: 'source' },
            { data: 'rider_id', name: 'rider_id' },
            { data: 'month', name: 'month' },            
            { data: 'given_date', name: 'given_date' },
            { data: 'amount', name: 'amount' },
            // { data: 'payment_status', name: 'payment_status' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
}
</script>
@endsection