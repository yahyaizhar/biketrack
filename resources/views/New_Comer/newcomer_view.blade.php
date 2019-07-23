@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

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
                    New Comers
                </h3>
                
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        &nbsp;
                        <a href="{{ route('NewComer.form') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
<h1 class="jhgjhg" style="display:none;" >a</h1>
<div class="checkbox checkbox-danger">
        <input id="check_id" class="checkbox checkbox-danger" type="checkbox">
        <label for="check_id">
           Detailed View
        </label>
</div>
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="newComer-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th></th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Nationality</th>
                        <th>Experience</th>
                        <th>Inteview Status</th>
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
var newcomer_table;
$(function() {
    newcomer_table = $('#newComer-table').DataTable({
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        ajax: "{!! route('NewComer.view_ajax') !!}",
        columns: [
            //  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": 'name', "name": 'name' },
            { "data": 'phone_number', "name": 'phone_number' },
            { "data": 'nationality', "name": 'nationality' },
            { "data": 'experience', "name": 'experience' },
            { "data": 'interview_status', "name": 'interview_status' },
            { "data": 'actions', "name": 'actions' },
        ],
        responsive:true,
        order:[0,'desc'],
    });

    $('#newComer-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = newcomer_table.row( tr );
 
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
    function format ( d ) {
        // `d` is the original data object for the row
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr>'+
                '<td style="font-weight:900;">Source of Contact:</td>'+
                '<td colspan="2";>'+d.source_of_contact+'</td>'+
                '<td style="font-weight:900;">Experiance Input:</td>'+
                '<td>'+d.experience_input+'</td>'+
                
              
            '</tr>'+
            '<tr>'+
                '<td style="font-weight:900;">Passport Status:</td>'+
                '<td colspan="2";>'+d.passport_status+'</td>'+
                '<td style="font-weight:900;">Passport Reason:</td>'+
                '<td>'+d.passport_reason+'</td>'+
               
                
            '</tr>'+
            '<tr>'+
                '<td style="font-weight:900;">Kingriders Interview:</td>'+
                '<td colspan="2";>'+d.kingriders_interview+'</td>'+
                '<td style="font-weight:900;" >Interview:</td>'+
                '<td>'+d.interview+'</td>'+
               
                '</tr>'+
                '<tr>'+
                '<td style="font-weight:900;">Overall Remarks:</td>'+
                '<td colspan="2"; style="width:50%;">'+d.overall_remarks+'</td>'+
                '<td style="font-weight:900;"></td>'+
                '<td colspan="2"; style="width:50%;"></td>'+
                '</tr>'+
           '</table>';
    }
});

function deleteNewComer(newComer_id)
{
    var url = "{{ url('admin/newComer/delete') }}"+ "/" + newComer_id ;
    console.log(url,true);
    sendDeleteRequest(url, false, null, newcomer_table);
}
function updateStatus(bike_id)
{
    var url = "{{ url('admin/bike') }}" + "/" + bike_id + "/updateStatus";
    console.log(url,true);
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
                    bike_table.ajax.reload(null, false);
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
<style>
   
td.details-control {
    background: url('https://biketrack-dev.solutionwin.net/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('https://biketrack-dev.solutionwin.net/details_close.png') no-repeat center center;
}
</style>
<script>
        $(document).ready(function(){
        $("#check_id").change(function(){
        $("td.details-control").click();
        });
        });
        </script>
@endsection