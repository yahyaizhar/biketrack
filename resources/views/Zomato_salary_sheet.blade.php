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
                    Zomato Salary Sheet September
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="bike-table">
                <thead>
                    <tr>
                        <th>Riders</th>
                        <th>Hours</th>
                        <th>Trips</th>
                        <th>Total Payout</th>
                        {{-- <th>Bike Rent</th> --}}
                        <th>Bike Salik </th>
                        <th>Bike Fuel </th>
                        <th>Sim Charges</th>
                        <th>Kingrider Salaries</th>
                        {{-- <th>Company Profit</th>                      --}}
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
var bike_table;
$(function() {
    bike_table = $('#bike-table').DataTable({
        lengthMenu: [[-1], ["All"]],
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
        ajax: "{!! route('ajax.zomato_septemmber_sheet') !!}",
        columns: [
            { data: 'rider_id', name: 'rider_id' },
            { data: 'no_of_hours', name: 'no_of_hours' },
            { data: 'no_of_trips', name: 'no_of_trips' },
            { data: 'payouts', name: 'payouts' },
            // { data: '', name: '' },
            { data: 'salik', name: 'salik' },
            { data: 'fuel', name: 'fuel' },
            { data: 'sim_charges', name: 'sim_charges' },
            { data: 'kingrider_salaries', name: 'kingrider_salaries' },
            // { data: '', name: '' },
            
        ],
        responsive:true,
        order:[0,'desc'],
    });
});
</script>
@endsection