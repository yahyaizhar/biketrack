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
                    Sellers
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <a href="{{ route('MobileInstallment.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="Sellers-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Adress</th>
                        <th>Phone Number</th>
                        <th>Actions</th>                        
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
var seller_table;
$(function() {
    seller_table = $('#Sellers-table').DataTable({
        processing: true,
        lengthMenu: [[-1], ["All"]],
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
        $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
    },
        ajax: "{!! route('Mobile.getSellers') !!}",
        columns: [
            { data: 'id', name: 'id' }, 
            { data: 'name', name: 'name' }, 
            { data: 'address', name: 'address' },          
            { data: 'phone_number', name: 'phone_number' },
            { data: 'actions', name: 'actions' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
});

</script>
@endsection