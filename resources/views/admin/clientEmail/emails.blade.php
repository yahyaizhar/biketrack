@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
		<link href="{{asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('main-content')
    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-envelope"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Support Emails
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">

                <!--begin: Datatable -->
                <table class="table table-striped table-hover table-checkable" id="emails_table">
                    <thead>
                        <tr>
                            <th>Serial No.</th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($emails as $email)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $email->from }}</td>
                                <td>{{ $email->subject }}</td>
                                <td>{{ $email->message }}</td>
                                <td><a href="{{ route('admin.emails.show', $email->id) }}"><span class="fa fa-eye"></span></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
    </div>

    <!-- end:: Content -->
@endsection
@section('foot')
    <!--begin::Page Vendors(used by this page) -->
    <script src="{{asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>

    <!--end::Page Vendors -->

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js')}}" type="text/javascript"></script>

    <!--end::Page Scripts -->
    <script>
    $('#emails_table').DataTable( {
        responsive: true
    } );
    </script>
@endsection