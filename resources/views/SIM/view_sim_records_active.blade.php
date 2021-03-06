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
                   Active Sims
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ route('Sim.view_records') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            All Sims
                        </a>
                        &nbsp;
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        <div style="float:left;" class="filter_record_status">
                            <select class="form-control">
                                <option value="">All Sims</option>
                                <option value="free">Free Sims</option>
                            </select>
                        </div>
                        @php
                            $type_match=Auth::user()->type;
                        @endphp
                        @if ($type_match=="su")
                            <input class="btn btn-success" type="button" onclick="export_data()" value="Export Sim Data">
                        @endif

                        &nbsp;
                        <a href="{{ route('Sim.new_sim') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="sim-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>ID</th>
                        <th>Sim Number</th>
                        <th>Sim Company</th>
                        <th>Assigned to</th>
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
var sim_table;
function export_data(){
    var export_details=[];
    var _data=sim_table.ajax.json().data;
    console.log(_data);
    _data.forEach(function(item,index) {
        export_details.push({
        "ID":item.id,
        "Sim Number":item.sim_number,
        "Sim Company":item.sim_company,
        });
    });
        var export_data = new CSVExport(export_details);
    return false;
}
$(function() {
    sim_table = $('#sim-table').DataTable({
        processing: true,
        lengthMenu: [[-1], ["All"]],
        serverSide: false,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
        $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
    },
        ajax: '{!! route('Sim.ajax_sim.active') !!}',
        columns: [
            // { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'id', name: 'id' },
            { data: 'sim_number', name: 'sim_number' },
            { data: 'sim_company', name: 'sim_comapny' },
            { data: 'assigned_to', name: 'assigned_to' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        responsive:true,
       
        order:[0,'desc'],
    });

    $('.filter_record_status select').on('change', function(){
        var _val = $(this).val();
        $('#sim-table tbody tr').show();
        $('#sim-table tbody tr').each(function(){
            var _tr = $(this);
            var row=sim_table.row(_tr).data();
            if($(row.assigned_to).is('a') && _val=="free"){
                // assinged
                _tr.hide();
            }
        });
    });
});
function deleteSim(id)
{
    var url = "{{ url('admin/sim') }}"+ "/" + id;
    sendDeleteRequest(url, false, null, sim_table);
}
function updateStatus(sim_id)
{
    var url = "{{ url('admin/sim') }}" + "/" + sim_id + "/updateStatus";
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
                    sim_table.ajax.reload(null, false);
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