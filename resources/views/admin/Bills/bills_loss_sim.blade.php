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
                    Sim Expense Sheet
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
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="display:none;">
                        <label>Filter by Bill</label>
                        <select class="form-control bk-select2" id="bill_detail" name="bill_detail" >
                            <option value="sim">Sim</option>
                            {{-- <option value="bike">Bike</option> --}}
                        </select> 
                    </div>
                </div>
            </div>
            <table class="table table-striped- table-hover table-checkable table-condensed" id="expense_loss_status">
                <thead>
                    <tr>
                        <th>Bill Source</th>
                        <th>Bill Amount</th>
                        <th>Company Account</th>
                        <th>Rider Account</th>
                        <th>Loss</th>                       
                    </tr>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
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
var salary_status_table;
$(document).ready(function(){
$(function() {
    $('[name="month_year"]').on("change",function(){
        var month=$(this).val();
        var _source=$('[name="bill_detail"]').val();
        var push_state={
            month:new Date(month).format("mmmm-yyyy"),
            source: _source,
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
    var _source=$('[name="bill_detail"]').val();
    expense_loss_table = $('#expense_loss_status').DataTable({
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
       ajax: "{{ url('admin/rider/sim/expense_loss/ajax/') }}" + "/" + _month+ "/" + _source,
        columns: [
            { data: 'bill_source', name: 'bill_source' },
            { data: 'bill_amount', name: 'bill_amount' },
            {data:  'company_account',  name: 'company_account'},
            { data: 'rider_account', name: 'rider_account' },            
            { data: 'loss', name: 'loss' },
        ],
        responsive:true,
        order:[0,'desc'],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            
            //source count
            var totalcount=0;
            var _temp = api
            .column( 2 )
            .data()
            .reduce( function (a, b) {
                return totalcount++;
            }, 0 );
            // Total over this page
            var bill_amount = api
            .column( 1, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return (intVal(a) + intVal(b)).toFixed(2);
            }, 0 );

                var loss_amount = api
            .column( 4, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return (intVal(a) + intVal(b)).toFixed(2);
            }, 0 );

                var company_paid = api
            .column( 2, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                var t_amount=0;
                var data=expense_loss_table.ajax.json().data;
                data.forEach(function(i,j){
                    t_amount+=parseFloat(i.company_bills_amount);
                })
                return t_amount.toFixed(2);
            }, 0 );
            var rider_paid = api
            .column( 3, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                var t_amount=0;
                var data=expense_loss_table.ajax.json().data;
                data.forEach(function(i,j){
                    t_amount+=parseFloat(i.rider_bills_amount);
                })
                return t_amount.toFixed(2);
            }, 0 );
            // Update footer
            $( api.column( 1 ).footer() ).html(
                bill_amount
            );
            $( api.column(4).footer()).html(
                loss_amount
            );
            $( api.column(2).footer()).html(
                company_paid
            );
            $( api.column(3).footer()).html(
                rider_paid
            );
        }
    });
}
$('[name="bill_detail"]').on("change",function(){
    $('[name="month_year"]').trigger("change");
})
</script>
@endsection