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
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        Riders
                        
                        </h3>
                    </div>
                </div>
 @include('client.includes.message')
                 <div class="kt-portlet__body">
                       <div>
                                 <select class="form-control kt-select2" id="kt_select2_3_4" name="rider_id" >
                                <option value="Select Rider">Select Rider</option>
                                    @foreach ($riders as $rider)
                               <option value="{{ $rider->id }}" 
                                    >{{ $rider->name }}</option>    
                              @endforeach
                                </select> 
                            </div>
                    </div>
            </div>
    </div>
</div>
</div>
{{-- Rider OPTIONS --}}
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content-a">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Salaries
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        &nbsp;
                        {{-- <a href="{{ route('bike.bike_login') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="RiderToMonth-table">
                <thead>
                    <tr>
                        
                        <th>Month/Year</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Paid By</th>
                        <th>Payment Status</th>
                        <th>Status</th>
                        <th>Actions</th>                        
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>
{{-- End Rider OPTIONS --}}

@endsection
@section('foot')

<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script>
    var month=null;
    var rider=null;
$(document).ready(function(){
    
    $('#kt_content-a').hide(); 
    $("#kt_select2_3_4").change(function(){
    $('#kt_content-a').show();
    if(month !== null){
        month.destroy();
     }
    
     month =$('#RiderToMonth-table').DataTable({
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
     ajax: "{{url('admin/Rider/To/Month/ajax')}}"+"/"+$(this).val(),
     columns: [
            //  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'month', name: 'month' },
            { data: 'salary', name: 'salary' },
            { data: 'payment_date', name: 'payment_date' },
            { data: 'paid_by', name: 'paid_by' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions' },
        ],
        responsive:true,
        order:[0,'desc'],
    });

  });



});
function deleteDeveloper(developer_id)
{
    var url = "{{ url('admin/developer') }}"+ "/" + developer_id ;
    console.log(url,true);
    sendDeleteRequest(url, false, null, month);
}
function updateStatus(developer_id)
{
    var url = "{{ url('admin/developer') }}" + "/" + developer_id + "/updateStatus";
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
                    month.ajax.reload(null, false);
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
    @endsection