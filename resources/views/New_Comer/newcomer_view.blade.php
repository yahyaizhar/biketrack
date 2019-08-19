@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
    .highlighted{
        background-color: #FFFF88;
    }
    .dataTables_filter{
        display:none;
    }
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
                    New Comers
                </h3>
                
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <div class="checkbox checkbox-danger btn btn-default btn-elevate btn-icon-sm">
                            <input id="check_id" class="checkbox checkbox-danger" type="checkbox">
                            <label for="check_id" >
                               Detailed View
                            </label>
                        </div>
                        <input type="text" class="form-control" placeholder="Search" id="search_details" style="display: inline-block;width: auto;">
                        <a href="{{ route('NewComer.form') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="newComer-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                      
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
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
<script>
var newcomer_table;
var newcomer_data = [];
$(function() { 
    var _settings =  {
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
            var api = this.api();
            var _data = api.data();
            var keys = Object.keys(_data).filter(function(x){return !isNaN(parseInt(x))});
            keys.forEach(function(_d,_i) {
                var __data = JSON.parse(JSON.stringify(_data[_d]).toLowerCase());
                newcomer_data.push(__data);
            });
            $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
   
        },
        ajax: "{!! route('NewComer.view_ajax') !!}",
        columns:null, 
        responsive:true,
        order:[0,'desc'],
    };
    if(window.outerWidth>=686){
        //visa_expiry
        $('#newComer-table thead tr').prepend('<th></th>');
        _settings.columns=[
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
      
        _settings.responsive=false;

        
    }
    else{
        $('#newComer-table thead tr th').eq(5).after('<th>Source Of Contact</th>');
        $('#newComer-table thead tr th').eq(6).after('<th>Experiance Input</th>');
        $('#newComer-table thead tr th').eq(7).after('<th>Passport Status</th>');
        $('#newComer-table thead tr th').eq(8).after('<th>Passport Reason</th>');
        $('#newComer-table thead tr th').eq(9).after('<th>Kingriders Interview</th>');
        $('#newComer-table thead tr th').eq(10).after('<th>Interview</th>');
        $('#newComer-table thead tr th').eq(11).after('<th>Overall Remarks</th>');
        
        _settings.columns=[
            { "data": 'name', "name": 'name' },
            { "data": 'phone_number', "name": 'phone_number' },
            { "data": 'nationality', "name": 'nationality' },
            { "data": 'experience', "name": 'experience' },
            { "data": 'interview_status', "name": 'interview_status' },
            { "data": 'actions', "name": 'actions' },
            { "data": 'source_of_contact', "name": 'source_of_contact' },
            { "data": 'experience_input', "name": 'experience_input' },
            { "data": 'passport_status', "name": 'passport_status' },
            { "data": 'passport_reason', "name": 'passport_reason' },
            { "data": 'kingriders_interview', "name": 'kingriders_interview' },
            { "data": 'interview', "name": 'interview' },
            { "data": 'overall_remarks', "name": 'overall_remarks'},
        ];
     
    }
    newcomer_table = $('#newComer-table').DataTable(_settings);
    if(window.outerWidth>=686){
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
}
    function format ( d ) {
        // `d` is the original data object for the row
        return '<table cellpadding="5" cellspacing="0" id="new_comertable" border="0" style="padding-left:50px;">'+
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
                '<td style="font-weight:900;">Whatsapp Number:</td>'+
                '<td colspan="2"; style="width:50%;">'+((d.whatsapp_number==null)?'Not given':d.whatsapp_number)+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td style="font-weight:900;">Education:</td>'+
                '<td colspan="2"; style="width:50%;">'+((d.education==null)?'Unfilled':d.education)+'</td>'+
                '<td style="font-weight:900;">Licence Issue Date:</td>'+
                '<td colspan="2"; style="width:50%;">'+((d.licence_issue_date==null) ? 'No Date is issued':d.licence_issue_date)+'</td>'+
                '</tr>'+
           '</table>';
    }

    $("#search_details").on("keyup", function() {
        var _val = $(this).val().trim().toLowerCase();
        $('#newComer-table tbody > tr').show();
        if(_val===''){
            $("#newComer-table tbody").unmark();
            $("#newComer-table tbody > tr:visible").each(function() {
                var tr = $(this);
                var row = newcomer_table.row( tr );
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.remove();
                    tr.removeClass('shown');
                }
            });
            return;
        }
        // $("#riders-table tbody > tr:visible").each(function() {
        //     $(this).removeClass("shown");
        // });
        
        if (newcomer_data.length > 0) {
            
            var _res = newcomer_data.filter(function(x) {
            
                return JSON.stringify(x).indexOf(_val) !== -1;
            });
            
            if (_res.length > 0) {
                $("#newComer-table tbody > tr").filter(function(index) {

                    var _name = $(this).find("td").eq(1).text().trim().toLowerCase();
                    if (_res.findIndex(function(x) {
                            return x.name == _name
                        }) === -1) {
                        $(this).hide();
                    }
                });
                if(_val !== ''){
                    $("#newComer-table tbody > tr:visible").each(function() {
                        var tr = $(this);
                        var row = newcomer_table.row( tr );
                        // console.warn("isShon: ",row.child.isShown());
                        if ( row.child.isShown() ) {
                            // This row is already open - close it
                            row.child.remove();
                            tr.removeClass('shown');
                        }
                            // This row is already open - close it
                            var _arow = row.child( format(row.data()) ); 
                            _arow.show();
                            tr.addClass('shown');
                    });
                }
                $("#newComer-table tbody").unmark({
                    done: function() {
                        $("#newComer-table tbody").mark(_val, {
                            "element": "span",
                            "className": "highlighted"
                        });
                    }
                });
            } else {
                $("#newComer-table tbody > tr").hide();
            }
        }
    }); 
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
                    bike_table.ajax.reload(null, false);
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
@endsection