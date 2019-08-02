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
        <div class="kt-portlet__body">
            <div class="row">
                
            <div class="checkbox checkbox-danger btn btn-default btn-elevate btn-icon-sm" id="hover_Checkbox">
                <input id="check_id" class="checkbox checkbox-danger" type="checkbox">
                <label for="check_id" >
                   Detailed View
                </label>
        </div>
    </div>
    {{-- <div class="row">
                
            <div  id="hover_Checkbox">
                    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for further details.." >
        </div>
    </div> --}}
            <!--begin: Datatable -->
            <table class="table table-striped table-hover table-checkable table-condensed" id="riders-table">
                <thead>
                    <tr>
                    
                        <th>ID</th>
                        <th>Name</th>
                        <th>Assigned To</th>
                        <th>Sim Number</th>
                        <th>Missing Fields</th>
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
    var _settings = {
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        ajax: '{!! route('admin.riders.data') !!}',
        columns: null,
        responsive:true,
        order:[0,'desc']
    };
    if(window.outerWidth>=720){
        //visa_expiry
        $('#riders-table thead tr').prepend('<th></th>');
        _settings.columns=[
            {
            "className":      'details-control',
            "orderable":      false,
            "data":           null,
            "defaultContent": ''
        },
        { "data": 'new_id', "name": 'new_id' },
            { "data": 'new_name', "name": 'name' },
            { "data": 'client_name', "name": 'client_name' },
            { "data": 'sim_number', "name": 'sim_number' },
            { "data": 'missing_fields', "name": 'missing_fields' },
            { "data": 'status', "name": 'status' },
            { "data": 'actions', "name": 'actions' }
        ];
      
        _settings.responsive=false;

        
    }
    else{
        $('#riders-table thead tr th').eq(6).before('<th>Date Of Joining</th>');
        $('#riders-table thead tr th').eq(7).before('<th>Passport Expiry</th>');
        $('#riders-table thead tr th').eq(8).before('<th>Visa Expiry</th>');
        $('#riders-table thead tr th').eq(9).before('<th>Licence Expiry</th>');
        $('#riders-table thead tr th').eq(10).before('<th>Mulkiya Expiry</th>');
       
        
        
        
        _settings.columns=[
        { "data": 'new_id', "name": 'new_id' },
            { "data": 'new_name', "name": 'name' },
            { "data": 'new_email', "name": 'email' },
            { "data": 'sim_number', "name": 'sim_number' },
            { "data": 'address', "name": 'address' },
            { "data": 'status', "name": 'status' },
            { "data": 'date_of_joining', "name": 'date_of_joining' },
            { "data": 'passport_expiry', "name": 'passport_expiry' },
            { "data": 'visa_expiry', "name": 'visa_expiry' },
            { "data": 'licence_expiry', "name": 'licence_expiry' },
            { "data": 'mulkiya_expiry', "name": 'mulkiya_expiry' },
            { "data": 'actions', "name": 'actions' }
        ];
     
    }
    riders_table = $('#riders-table').DataTable(_settings);

    if(window.outerWidth>=720){
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
        });
    }
    // {
    //             "className":      'details-control',
    //             "orderable":      false,
    //             "data":           null,
    //             "defaultContent": ''
    //         },
    
    function format ( data ) {
        // `d` is the original data object for the row
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                '<tr>'+
                '<td colspan="1"; style="font-weight:900;">Date Of Joining:</td>'+
                '<td colspan="2";>'+data.date_of_joining+'</td>'+
                '<td colspan="1"; style="font-weight:900;" >Assign Bike Number:</td>'+
                '<td colspan="2";>'+data.bike_number+'</td>'+
                '<td colspan="1"; style="font-weight:900;" >Phone Number:</td>'+
                '<td colspan="1";>'+data.phone+'</td>'+
                '<td colspan="1"; style="font-weight:900;" >Emerate ID:</td>'+
                '<td colspan="1";>'+data.emirate_id+'</td>'+
                '<td colspan="1"; style="font-weight:900;" >Email:</td>'+
                '<td colspan="1";>'+data.email+'</td>'+
               
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
        text: "You want update status!",
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
                        text: 'Unable to update.',
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
    if(window.outerWidth>=720){
        $("#check_id").change(function(){
            
            if($("#check_id").prop("checked") == true){
                           
                           $("td.details-control").each(function(){
                            if (!$(this).parent().hasClass("shown")) {
                                $(this).trigger("click");
                            }  
                           });
                          
                            }
            if($("#check_id"). prop("checked") == false){
                           
                           $("td.details-control").each(function(){
                            if ($(this).parent().hasClass("shown")) {
                                $(this).trigger("click");
                            }  
                           });
                          
                       }
                     });
               }
                  else if(window.outerWidth<720){
                  $("#check_id").change(function(){
                   if($("#check_id").prop("checked") == true){
                           
                           $("td.sorting_1").each(function(){
                            if (!$(this).parent().hasClass("parent")) {
                                $(this).trigger("click");
                            }  
                           });
                          
                            }
            if($("#check_id"). prop("checked") == false){
                           
                           $("td.sorting_1").each(function(){
                            if ($(this).parent().hasClass("parent")) {
                                $(this).trigger("click");
                            }  
                           });
                          
                       }
               
                   });
                  }
});
</script>
<script>
        function myFunction() {
            
          
        }
        </script>
@endsection