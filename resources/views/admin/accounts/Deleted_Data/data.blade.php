@extends('admin.layouts.app')
@section('head')
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
@endsection
@section('main-content')
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Deleted Rows
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="deleted_data-table">
                <thead>
                    <tr>
                        {{-- <th>ID</th> --}}
                        <th>Date</th>
                        <th>Deleted By</th>
                        <th>Feed</th>
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
var deleted_data_table;
$(function() {
    $('[data-toggle="popover"]').popover('dispose');
    deleted_data_table = $('#deleted_data-table').DataTable({
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
        $('[data-toggle="popover"]').popover();
    },
        ajax: "{!! route('account.getDeletedData') !!}",
        columns: [
            // { data: 'id', name: 'id' },         
            { data: 'date', name: 'date' },
            { data: 'deleted_by', name: 'deleted_by' },
            { data: 'feed', name: 'feed' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
});
function retreive_data(id){
    // var url ="{{ url('admin/retreive_data/ajax/') }}"+ "/" + id;
    var url ="{{ url('admin/send_notification/retreive_data/ajax') }}"+ "/" + id;
    $.ajax({
        url : url,
        type : 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
            swal.fire({
                position: 'center',
                type: 'success',
                title: 'Data is retreived successfully.',
                showConfirmButton: false,
                timer: 1500
            });
            
            window.location.reload();
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
</script>
@endsection