@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
    .highlighted{
        background-color: #FFFF88;
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

{{-- ////modal --}}


<div class="modal fade custom_approval_comer_model" id="quick_view" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" max-width: 70%">
    <div class="modal-content"  style=" padding: 5px 10px;">
        <div class="modal-header border-bottom-0">
            <h5 class="modal-title text-center" style=" margin: 0px auto; width: 100%;">New Commer Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body custm_body">
        <div class="row">
            <div class="col-md-8"> 
           <table class="new_commer_approval_table">
           <tr>
               <th>Name:</th>
               <td class="approval_full_name">Full Name</td>
               <th>Phone number:</th>
               <td class="approval_phone_no">Full Name</td>
           <tr>
           <tr>
                <th>Nationality:</th>
                <td class="approval_nationality">Full Name</td>
                <th>Experience:</th>
                <td class="approval_experience">Full Name</td>
           <tr>
            <tr>
                    <th>National id card:</th>
                    <td class="approval_id_card_no">Full Name</td>
                    <th>Education:</th>
                    <td class="approval_education">Full Name</td>
            <tr>
            <tr>
                    <th>License:</th>
                    <td class="approval_license">Full Name</td>
                    <th>License number:</th>
                    <td class="approval_license_no">Full Name</td>
            <tr>
            <tr>
                    <th>License issue date:</th>
                    <td class="approval_license_date">Full Name</td>
                    <th>Source:</th>
                    <td class="approval_source">Full Name</td>

            <tr>
            <tr>
                    <th>Watsapp number:</th>
                    <td class="approval_w_no">Full Name</td>
                    <th>Passport status:</th>
                    <td class="approval_passport_status">Full Name</td>
            </tr>
            <tr>
                    <th>Passport number:</th>
                    <td class="approval_passport_no">Full Name</td>
                    <th>Current residence:</th>
                    <td class="approval_residence">Full Name</td>
            </tr>
            <tr>
                    <th>Passport status:</th>
                    <td class="approval_passport_status">Full Name</td>
                    <th>Application status:</th>
                    <td class="approval_app_status">Full Name</td>
            </tr>
            <tr>
                    <th>Interview status:</th>
                    <td class="approval_interview_status">Full Name</td>
            </tr>
           </table>
        <div class="form-group">
            <label style="font-weight: 800;">Extra reviews:</label>
           <textarea readonly class="new_commer_reviews form-control"></textarea>
        </div>
            </div>
            <div class="col-md-4">
             <div class="approval_custom_images_section">
              <a href="" target="_blank"><img src="" style="width: 260px;"></a>
              <ul class="list-group list-group-horizontal" style="margin-top: 60px;">
                    <li class="list-group-item active profile" img_src="">Profile picture</li>
                    <li class="list-group-item license" img_src="">License picture</li>
                    <li class="list-group-item passport" img_src="">Passport picture</li>
                </ul>
             </div>
            </div>

        </div>
        </div>
       <div class="modal-footer">
           <div class="footer_f_form" style="display:none;width:100%;">
         <form style="width:100%;" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Approval status message</label>
                <textarea class="form-control" name="status_approval_message" id="status_approval_message"></textarea>
                <input name="new_commer_id" id="new_commer_id" type="hidden">
            </div>
            <div class="form-group">
                <label>Approval status</label>
                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="approval_status" name="approval_status" style="width: 2% !important;" value="approve"  required /><h6 style="margin-top:10px;margin-left:10px;">Approve</h6></div>
                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="approval_status" name="approval_status" style="width: 2% !important;" value="reject"  required /><h6 style="margin-top:10px;margin-left:10px;">Reject</h6></div>
            <input style="display:none;" type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker2 form-control @if($errors->has('given_date')) invalid-field @endif" name="interview_date" placeholder="Enter Given Date" value="">
            </div>
            <div class="form-group"> 
                <button class="btn btn-success" name="submit" type="submit">Submit</button>
            </div>
         </form>
        </div>
        <div class="footer_s_form" style="display:none;width:100%;">
                <form style="width:100%;" action="" method="POST" enctype="multipart/form-data">
                    <input name="new_commer_id" id="new_commer_id" type="hidden">

                   <div class="form-group">
                       <label>Interview by</label>
                       <input name="interview_by" id="interview_by" type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Interview status message</label>
                       <textarea name="interview_status_message" id="interview_status_message" type="text" class="form-control"></textarea>
                   </div>
                   <div class="form-group">
                      <label>Interview status</label>
                   <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="interview_status" name="interview_status" style="width: 2% !important;" value="approve"  required /><h6 style="margin-top:10px;margin-left:10px;">Approve</h6></div>
                   <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="interview_status" name="interview_status" style="width: 2% !important;" value="reject"  required /><h6 style="margin-top:10px;margin-left:10px;">Reject</h6></div>
                   </div>
                   <div class="form-group"> 
                       <button class="btn btn-success" name="submit" type="submit">Submit</button>
                   </div>
                </form>
               </div>
               <div class="footer_t_form" style="display:none;width:100%;">
                    <form style="width:100%;" action="" method="POST" enctype="multipart/form-data">
                        <input name="new_commer_id" id="new_commer_id" type="hidden">

                       <div class="form-group">
                           <label>Interview date</label>
                          <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker2 form-control @if($errors->has('given_date')) invalid-field @endif" name="interview_date" placeholder="Enter Given Date" value="">
                       </div>
                       <div class="form-group"> 
                           <button class="btn btn-success" name="submit" type="submit">Submit</button>
                       </div>
                    </form>
                   </div>
    </div>
    </div>
    </div>
</div>

{{-- ///End modal --}}

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
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Nationality</th>
                        <th>Experience</th>
                        <th>National id card number</th>
                        <th>Actions
                        <span class="dtr-data" style=" z-index: 999999999999999999;">
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                                </a>
                                  <div class="dropdown-menu dropdown-menu-right">
                                  <a class="dropdown-item" onclick="sort_by_status('all')"><i class="flaticon2-group"></i> All</a>
                                  <a class="dropdown-item" onclick="sort_by_status('approve')"><i class="flaticon2-checkmark"></i> Approved</a>
                                  <a class="dropdown-item" onclick="sort_by_status('reject')"><i class="flaticon2-cross"></i> Rejected</a>
                                  <a class="dropdown-item" onclick="sort_by_status('pending')"><i class="flaticon2-pen"></i> Pending</a>
                                  <a class="dropdown-item" onclick="sort_by_status('interview')"><i class="flaticon2-check-mark"></i> Interviewer's</a>
                                  </div>    
                           </span>
                        </th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>

                        
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
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
<script>
var newcomer_table;
var newcomer_data = [];
window._url = "https://"+window.location.host+"/admin/newApprovalComer/view/ajax/all";
$(function() { 
    var _settings =  {
        processing: true,
        lengthMenu: [[-1], ["All"]],
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
            data.ajax = window._url;
            console.log(data)
            var api = this.api();
            var _data = api.data();
            var keys = Object.keys(_data).filter(function(x){return !isNaN(parseInt(x))});
            keys.forEach(function(_d,_i) {
                var __data = JSON.parse(JSON.stringify(_data[_d]).toLowerCase());
                newcomer_data.push(__data);
            });
            $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
        mark_table();
        },
        ajax: window._url,
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
            
            { "data": 'full_name', "name": 'full_name' },
            {"data": 'phone_number', "name": 'phone_number' },
            { "data": 'nationality', "name": 'nationality' },
            { "data": 'experiance', "name": 'experiance' },
            { "data": 'national_id_card_number', "name": 'national_id_card_number' },
            { "data": 'actions', "name": 'actions' },
            { "data": 'newcommer_image', "name": 'newcommer_image' },
            { "data": 'whatsapp_number', "name": 'whatsapp_number' },
            { "data": 'education', "name": 'education' },
            { "data": 'license_check', "name": 'license_check' },
            { "data": 'license_number', "name": 'license_number' },
            { "data": 'licence_issue_date', "name": 'licence_issue_date' },
            { "data": 'license_image', "name": 'license_image' },
            { "data": 'passport_status', "name": 'passport_status'},
            { "data": 'passport_number', "name": 'passport_number'},
            { "data": 'passport_image', "name": 'passport_image'},
            { "data": 'current_residence', "name": 'current_residence'},
            { "data": 'current_residence_countries', "name": 'current_residence_countries'},
            { "data": 'source', "name": 'source'},
            { "data": 'overall_remarks', "name": 'overall_remarks'},
        ],
      
        _settings.responsive=false;
        _settings.columnDefs=[
            {
                "targets": [ 7,8,9,10,11,12,13,14,15,16,17,18,19,20 ],
                "visible": false,
                searchable: true,
            },
        ];
 }
    else{
        _settings.columns=[
            { "data": 'full_name', "name": 'full_name' },
            {"data": 'phone_number', "name": 'phone_number' },
            { "data": 'nationality', "name": 'nationality' },
            { "data": 'experiance', "name": 'experiance' },
            { "data": 'national_id_card_number', "name": 'national_id_card_number' },
            { "data": 'actions', "name": 'actions' },
            { "data": 'newcommer_image', "name": 'newcommer_image' },
            { "data": 'whatsapp_number', "name": 'whatsapp_number' },
            { "data": 'education', "name": 'education' },
            { "data": 'license_check', "name": 'license_check' },
            { "data": 'license_number', "name": 'license_number' },
            { "data": 'licence_issue_date', "name": 'licence_issue_date' },
            { "data": 'license_image', "name": 'license_image' },
            { "data": 'passport_status', "name": 'passport_status'},
            { "data": 'passport_number', "name": 'passport_number'},
            { "data": 'passport_image', "name": 'passport_image'},
            { "data": 'current_residence', "name": 'current_residence'},
            { "data": 'current_residence_countries', "name": 'current_residence_countries'},
            { "data": 'source', "name": 'source'},
            { "data": 'overall_remarks', "name": 'overall_remarks'},
        ];
     
    }
    newcomer_table = $('#newComer-table').DataTable(_settings);
    var mark_table = function(){
        var _val = newcomer_table.search(); 
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
        $('#newComer-table tbody > tr[role="row"]:visible').each(function() {
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
        $("#newComer-table tbody").unmark({
            done: function() {
                $("#newComer-table tbody").mark(_val, {
                    "element": "span",
                    "className": "highlighted"
                });
            }
        });
        
    }
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
       if(d.current_residence =='other'){
        d.current_residence = d.current_residence_countries
       }
        // `d` is the original data object for the row
        return '<table cellpadding="5" cellspacing="0" id="new_comertable" border="0" style="padding-left:50px;">'+
            '<tr class="row'+d.id+'">'+
                '<td style="font-weight:900;">Passport status:</td>'+
                '<td colspan="2";>'+d.passport_status+'</td>'+
                '<td style="font-weight:900;">Passport Number:</td>'+
                '<td>'+d.passport_number+'</td>'+
                '<td style="font-weight:900;">Current residence:</td>'+
                '<td>'+d.current_residence+'</td>'+
              
            '</tr>'+
            '<tr class="row'+d.id+'">'+
                '<td style="font-weight:900;">License:</td>'+
                '<td colspan="2";>'+d.license_check+'</td>'+
                '<td style="font-weight:900;">License Number:</td>'+
                '<td>'+d.license_number+'</td>'+
                '<td style="font-weight:900;">License issue date:</td>'+
                '<td>'+d.licence_issue_date+'</td>'+
              
            '</tr>'+
            '<tr class="row'+d.id+'">'+
                '<td style="font-weight:900;">Source:</td>'+
                '<td colspan="2";>'+d.source+'</td>'+
                '<td style="font-weight:900;">Education:</td>'+
                '<td>'+d.education+'</td>'+
                '<td style="font-weight:900;">Watsapp Number:</td>'+
                '<td>'+d.whatsapp_number+'</td>'+
              
            '</tr>'+
            '<tr class="row'+d.id+'" style="display:none;">'+
                '<td style="font-weight:900;">Comer image:</td>'+
                '<td colspan="2";>'+d.newcommer_image+'</td>'+
                '<td style="font-weight:900;">License image:</td>'+
                '<td>'+d.license_image+'</td>'+
                '<td style="font-weight:900;">Passport image:</td>'+
                '<td>'+d.passport_image+'</td>'+
            '</tr>'+
              
           '</table>';
    }
    if(window.outerWidth>=686){
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
    else if(window.outerWidth<686){
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

function show_waiting_comer(id,$this){
    $('.footer_f_form').hide();
    $('.footer_s_form').hide();
    $('.footer_t_form').hide();
    var _row = $($this).parents('.odd,.even');
    $('[name="new_commer_id"]').val(id);
    $('.custom_approval_comer_model').modal('show');
    var _newcommerdata = newcomer_table.row(_row).data();
    if(_newcommerdata.approval_status == 'reject' || _newcommerdata.approval_status == 'pending'){
        $('.footer_f_form').show();
    }
    if(_newcommerdata.interview_status == 'pending'){
        $('.footer_s_form').show();
    }
    if(_newcommerdata.approval_status == 'approve' && _newcommerdata.interview_status == null){
    $('.footer_t_form').show();
    }
    console.log(_newcommerdata);
    $('.approval_full_name').text(_newcommerdata.full_name);
    $('.approval_phone_no').text(_newcommerdata.phone_number);
    $('.approval_nationality').text(_newcommerdata.nationality);
    $('.approval_experience').text(_newcommerdata.experiance);
    $('.approval_id_card_no').text(_newcommerdata.national_id_card_number);
    $('.approval_education').text(_newcommerdata.education);
    $('.approval_license').text(_newcommerdata.license_check);
    $('.new_commer_reviews').text(_newcommerdata.overall_remarks);
    $('.approval_license_no').text(_newcommerdata.license_number);
    $('.approval_license_date').text(_newcommerdata.licence_issue_date);
    $('.approval_source').text(_newcommerdata.source);
    $('.approval_w_no').text(_newcommerdata.whatsapp_number);
    $('.approval_passport_status').text(_newcommerdata.passport_status);
    $('.approval_passport_no').text(_newcommerdata.passport_number);
    $('.approval_interview_status').text(_newcommerdata.interview_status);
    $('.approval_app_status').text(_newcommerdata.approval_status);
    if(_newcommerdata.current_residence == 'other'){
        _newcommerdata.current_residence = _newcommerdata.current_residence_countries
    }
    $('.approval_residence').text(_newcommerdata.current_residence);
    if(_newcommerdata.newcommer_image.indexOf('uploads') <= -1){
        var _host = window.location.host;
        _newcommerdata.newcommer_image = "https://"+_host+"/storage/uploads/no_image.png";
    }
    $('.approval_custom_images_section img').attr('src',_newcommerdata.newcommer_image);
    $('.approval_custom_images_section a').attr('href',_newcommerdata.newcommer_image);
    $('.profile').attr('img_src',_newcommerdata.newcommer_image);
    $('.license').attr('img_src',_newcommerdata.license_image);
    $('.passport').attr('img_src',_newcommerdata.passport_image);
    $('ul.list-group.list-group-horizontal li').removeClass('active');
    $('ul.list-group.list-group-horizontal li.profile').addClass('active');
}
$('ul.list-group.list-group-horizontal li').click(function(){
    $('ul.list-group.list-group-horizontal li').removeClass('active');
    $(this).addClass('active');
    var _newimgsrc = $(this).attr('img_src');
    if(_newimgsrc.indexOf('uploads') <= -1){
        var _host = window.location.host;
        _newimgsrc ="https://"+_host+"/storage/uploads/no_image.png";
    }
    $('.approval_custom_images_section img').attr('src',_newimgsrc);
    $('.approval_custom_images_section a').attr('href',_newimgsrc);

})

// first form

$('.custom_approval_comer_model .footer_f_form').find('form').off('submit').on('submit', function(e){
                        e.preventDefault();
                        $('.custom_approval_comer_model').modal('hide');
                        var _form = $(this);
                        var _url ="{{url('admin/newComer/approved')}}";
                        $.ajax({
                            url : _url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type : 'POST',
                            data: _form.serialize(),
                            success: function(data){
                                console.log(data);
                                
                                swal.fire({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Record updated successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                newcomer_table.ajax.reload(null, false);
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
                    });



                    // second form

                    $('.custom_approval_comer_model .footer_s_form').find('form').off('submit').on('submit', function(e){
                        e.preventDefault();
                        $('.custom_approval_comer_model').modal('hide');
                        var _form = $(this);
                        var _url ="{{url('admin/newComer/add_interview_status')}}";
                        $.ajax({
                            url : _url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type : 'POST',
                            data: _form.serialize(),
                            success: function(data){
                                console.log(data);
                                
                                swal.fire({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Record updated successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                newcomer_table.ajax.reload(null, false);
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
                    });



                    // Third form

                    $('.custom_approval_comer_model .footer_t_form').find('form').off('submit').on('submit', function(e){
                        e.preventDefault();
                        $('.custom_approval_comer_model').modal('hide');
                        var _form = $(this);
                        var _url ="{{url('admin/newComer/add_interview_date')}}";
                        $.ajax({
                            url : _url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type : 'POST',
                            data: _form.serialize(),
                            success: function(data){
                                console.log(data);
                                
                                swal.fire({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Record updated successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                newcomer_table.ajax.reload(null, false);
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
                    });



                    // end of forms

            function sort_by_status(status){
                window._url = "https://"+window.location.host+"/admin/newApprovalComer/view/ajax/"+status;
                setTimeout(function(){
                newcomer_table.ajax.reload();
                },1000)
            }
            $('[name="approval_status"]').change(function(){
                $('[name="interview_date"]').val('');
                if($(this).val()=='approve'){
                   $('[name="interview_date"]').show();
                }
                else{
                    $('[name="interview_date"]').hide();
                }
            })
        var _h6 = $('input[type="radio"]').siblings('h6');
            $(_h6).css('cursor','pointer');
            $(_h6).click(function(){
                $(this).siblings('input[type="radio"]').prop( "checked", true );
                $(this).siblings('input[type="radio"]').change();
            })
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