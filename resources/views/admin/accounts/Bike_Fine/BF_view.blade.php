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
<!-- begin:: Content -->
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Bike Fine
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        &nbsp;
                        <a href="{{ route('admin.BF_index') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="Bike_fine">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>ID</th>
                        <th>KR ID</th>
                        <th>Rider</th>
                        <th>Month</th>
                        <th>Bike_id</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Actions</th>                        
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

<!-- end:: Content -->
@endsection
@section('foot')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
<script>
var bike_fine;
$(function() {
    bike_fine = $('#Bike_fine').DataTable({
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
        ajax: "{!! route('admin.accounts.getBikeFine') !!}",
        columns: [
            //  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'id', name: 'id' },
            { data: 'rider_id_id', name: 'rider_id_id' },
            { data: 'rider_id', name: 'rider_id' },
            { data: 'date', name: 'date' },
            { data: 'bike_id', name: 'bike_id' },            
            { data: 'desc', name: 'desc' },
            { data: 'amount', name: 'amount' },
            { data: 'actions', name: 'actions' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
});
function deleteBikeFine(id)
{
    var url = "{{ url('admin/accounts/BF/delete') }}"+ "/" + id  ;
    console.log(url,true);
    sendDeleteRequest(url, false, null, bike_fine);
}


</script>
@endsection