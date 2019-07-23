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
                    Riders
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <a href="{{ route('admin.riders.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="checkbox checkbox-danger">
                <input id="check_id" class="checkbox checkbox-danger" type="checkbox">
                <label for="check_id">
                   Detailed View
                </label>
        </div>
            <!--begin: Datatable -->
            <table class="table table-striped table-hover table-checkable table-condensed" id="riders-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Status</th>
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
var riders_table;
$(function() {
    riders_table = $('#riders-table').DataTable({
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        ajax: '{!! route('admin.riders.data') !!}',
        columns: [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": 'new_id', "name": 'new_id' },
            { "data": 'new_name', "name": 'name' },
            { "data": 'new_email', "name": 'email' },
            { "data": 'new_phone', "name": 'phone' },
            { "data": 'address', "name": 'address' },
            { "data": 'status', "name": 'status' },
            { "data": 'actions', "name": 'actions' }
        ],
        responsive:true,
        order:[0,'desc']
    });
    $('#riders-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = riders_table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
    function format ( data ) {
        // `d` is the original data object for the row
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                '<tr>'+
                '<td colspan="1"; style="font-weight:900;">Date Of Joining:</td>'+
                '<td colspan="2";>'+data.date_of_joining+'</td>'+
                '<td colspan="2"; style="font-weight:900;" >Official Sim Given Date:</td>'+
                '<td colspan="2";>'+data.official_sim_given_date+'</td>'+
                '<td colspan="2"; style="font-weight:900;">Official Given Number:</td>'+
                '<td colspan="4"; >'+data.official_given_number+'</td>'+
               '</tr>'+
               '<tr>'+
                '<td colspan="1"; style="font-weight:900;">Passport Expiry:</td>'+
                '<td colspan="2";>'+data.passport_expiry+'</td>'+
                '<td colspan="1"; style="font-weight:900;" >Visa Expiry:</td>'+
                '<td colspan="2"; >'+data.visa_expiry+'</td>'+
                '<td style="font-weight:900;">Licence Exiry:</td>'+
                '<td colspan="2";>'+data.licence_expiry+'</td>'+
                '<td colspan="1"; style="font-weight:900;" >Mulkiya Expiry:</td>'+
                '<td colspan="2";>'+data.mulkiya_expiry+'</td>'+
               '</tr>'+
             
                

            
           '</table>';
    }
});

function deleteRider(id){
    var url = "{{ url('admin/riders') }}"+ "/" + id;
    sendDeleteRequest(url, false, null, riders_table);
}

function updateStatus(rider_id)
{
    var url = "{{ url('admin/rider') }}" + "/" + rider_id + "/updateStatus";
    swal.fire({
        title: 'Are you sure?',
        text: "You want udpate status!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes!'
    }).then(function(result) {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    riders_table.ajax.reload(null, false);
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to udpate.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}
</script>
<script>
$(document).ready(function(){
$("#check_id").change(function(){
$("td.details-control").click();
});
});
</script>
<style>
   
        td.details-control {
            background: url('https://biketrack-dev.solutionwin.net/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('https://biketrack-dev.solutionwin.net/details_close.png') no-repeat center center;
        }
        </style>
@endsection