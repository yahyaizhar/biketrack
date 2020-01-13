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
    <!--end::Page Vendors Styles -->
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
                    View Profit & loss 
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
            <table class="table table-striped- table-hover table-checkable table-condensed" id="profit_loss-table">
                <thead>
                    <tr>
                        <th>ID</th> 
                        <th>Model</th> 
                        <th>Purchased Month</th> 
                        <th>Purchased Price</th> 
                        <th>Received Amount</th>
                        <th>Profit & Loss</th>
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
<script>
var profit_loss_table;
$(function() {
    profit_loss_table = $('#profit_loss-table').DataTable({
        lengthMenu: [[-1,50], [50,"All"]],
        processing: true,
        serverSide: false,
        'language': { 
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
        $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
    },
        ajax: "{!! route('Mobile.getMobileProfitLoss') !!}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'model', name: 'model' },
            { data: 'date', name: 'date' },
            { data: 'purchase_price', name: 'purchase_price' },
            { data: 'received', name: 'received' },
            { data: 'profit_loss', name: 'profit_loss' },
        ],
        responsive:true,
        order:[0,'asc'],
    });
});
</script>
@endsection