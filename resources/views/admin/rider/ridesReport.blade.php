@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Page Vendors Styles -->
@endsection
@section('main-content')

@include('admin.includes.message')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-motorcycle"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Rides Reports
                </h3>
            </div>
            {{-- <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <a href="{{ route('admin.riders.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped table-hover table-checkable table-condensed" id="rides-report-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rider Name</th>
                        <th>Online Hours</th>
                        <th>No of Trips</th>
                        <th>No of Hours</th>
                        <th>Mileage</th>
                        <th>Created at</th>
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
var rides_report_table;
$(function() {
    rides_report_table = $('#rides-report-table').DataTable({
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        ajax: '{!! route('admin.ridesReport.data', $rider->id) !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'rider_name', name: 'rider_name' },
            { data: 'online_hours', name: 'online_hours' },
            { data: 'no_of_trips', name: 'no_of_trips' },
            { data: 'no_of_hours', name: 'no_of_hours' },
            { data: 'mileage', name: 'mileage' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions' }
        ],
        responsive:true,
        order:[0,'desc']
    });
});
function deleteRecord(id){
    var url = "{{ url('admin/ridesReportRecord') }}"+ "/" + id;
    sendDeleteRequest(url, false, null, rides_report_table);
}

</script>
@endsection