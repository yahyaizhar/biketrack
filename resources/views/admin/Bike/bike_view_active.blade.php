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
                    Active Bikes
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        <div style="float:left;" class="filter_record_status">
                            <select class="form-control">
                                <option value="">All Bikes</option>
                                <option value="free">Free Bikes</option>
                            </select>
                        </div>
                        <input class="btn btn-success" type="button" onclick="export_data()" value="Export Bike Data">
                        &nbsp;
                        <a href="{{ route('bike.bike_login') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="bike-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Owner</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Bike Number</th>
                        <th>Rent</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Actions</th>                        
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
function export_data(){
    var export_details=[]; 
    var _data=bike_table.ajax.json().data;
    _data.forEach(function(item,index) {
        export_details.push({
        "ID":item.id,
        "Owner":item.owner,
        "Brand":item.brand,
        "Model":item.model,
        "Bike Number":item.bike_number,
        "Rent":item.rent,
        });
    });
        var export_data = new CSVExport(export_details);
    return false;
}
$(function() {
    bike_table = $('#bike-table').DataTable({
        lengthMenu: [[-1], ["All"]],
        processing: true,
        serverSide: false,
        'language': { 
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
$('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
    },
        ajax: "{!! route('bike.bike_show.active') !!}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'owner', name: 'owner' }, 
            { data: 'brand', name: 'brand' },            
            { data: 'model', name: 'model' },
            { data: 'bike_number', name: 'bike_number' },
            { data: 'rent', name: 'rent' },
            { data: 'assigned_to', name: 'assigned_to' },
            { data: 'status', name: 'status' },
            { data: 'availability', name: 'availability' },
        ],
        responsive:true,
        order:[0,'desc'],
    });

    $('.filter_record_status select').on('change', function(){
        var _val = $(this).val();
        $('#bike-table tbody tr').show();
        $('#bike-table tbody tr').each(function(){
            var _tr = $(this);
            var row=bike_table.row(_tr).data();
            if($(row.assigned_to).is('a') && _val=="free"){
                // assinged
                _tr.hide();
            }
        });
    });
});
function deleteBike(bike_id)
{
    var url = "{{ url('admin/bike') }}"+ "/" + bike_id ;
    console.log(url,true);
    sendDeleteRequest(url, false, null, bike_table);
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
@endsection