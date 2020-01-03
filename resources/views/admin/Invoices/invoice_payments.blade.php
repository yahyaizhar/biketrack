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
                   View Invoice Payments
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                         {{-- <a href="{{ route('admin.CE_index') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="invoice_payment-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Amount</th>
                        <th>Payment Method</th>    
                        <th>Received By</th>                    
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
var invoice_payment_table;
$(function() {
    invoice_payment_table = $('#invoice_payment-table').DataTable({
        processing: true,
        serverSide: false,
        lengthMenu: [[-1], ["All"]],
        'language': { 
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
            $('.total_entries').remove();
            $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
        },
        ajax: "{!! route('admin.getInvoicePayments') !!}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'payment_date', name: 'payment_date' },
            { data: 'original_amount', name: 'original_amount' },            
            { data: 'payment', name: 'payment' },
            { data: 'due_balance', name: 'due_balance' },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'payment_received_by', name: 'payment_received_by' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
});
</script>
@endsection