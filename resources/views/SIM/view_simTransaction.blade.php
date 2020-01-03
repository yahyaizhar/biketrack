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
                   Sim Transaction
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        &nbsp;
                        <a href="{{ route('SimTransaction.create_sim') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filter by month</label>
                        <input id="month_picker" type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month" value="">
                    </div>
                
                </div>
            </div>
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="simTransaction-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th class="th_month">Month</th>
                        <th>Rider Name</th>
                        <th>Sim Number</th>
                        <th>Usage Limit</th>
                        <th >Bill Amount</th>
                        <th class="th_eum">Extra Usage Amount</th>
                        <th>Extra Usage Payment Status</th>
                        <th>Bill Status</th>
                        <th>Status</th>
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
<script src="{{ asset('js/dataTables.cellEdit.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<!--end::Page Scripts -->
<script>
var simTransaction_table;
$(function() {
    $('#datepicker').fdatepicker({ 
        format: 'MM yyyy', 
        startView:3,
        minView:3,
        maxView:4
    });
    // $('#datepicker').val("{{Carbon\Carbon::now()->format('F Y')}}");
    var isLoaded = false;
    var getData = function(){
        simTransaction_table = $('#simTransaction-table').DataTable({
            processing: true,
            lengthMenu: [[-1], ["All"]],
            destroy:true,
            serverSide: false,
            'language': {
                'loadingRecords': '&nbsp;',
                'processing': $('.loading').show()
            },
            drawCallback:function(data){
                $('.total_entries').remove();
                $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
            },
            ajax: "{{url('admin/get/ajax/Transaction/Sim/')}}"+"/"+new Date($('#month_picker').val()).format('yyyy-mm-dd'),
            columns: [
                // { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                { data: 'month', name: 'month' },
                { data: 'rider_id', name: 'rider_id' },
                { data: 'sim_number', name: 'sim_number' },
                { data: 'usage_limit', name: 'usage_limit' },
                { data: 'bill_amount', name: 'bill_amount' },
                { data: 'extra_usage_amount', name: 'extra_usage_amount' },
                { data: 'extra_usage_payment_status', name: 'extra_usage_payment_status' },
                { data: 'bill_status', name: 'bill_status' },
                { data: 'status', name: 'status' },
            ],
            responsive:true,
            columnDefs:[
                {
                    "targets": [ 0 ],
                    "visible": false,
                },
            ],
            order:[1,'desc'],
        });
        simTransaction_table.MakeCellsEditable("destroy"); 
        simTransaction_table.MakeCellsEditable({
            "onUpdate": myCallbackFunction,
            "columns": [4,6,7],
            "inputCss":'form-control',
            "inputTypes": [
                {
                    "column":3, 
                    "type":"text", 
                    "options":null 
                }, 
                {
                    "column":5, 
                    "type": "list",
                    "options":[
                        { "value": "Pending", "display": "Pending" },
                        { "value": "Paid", "display": "Paid" }
                    ]
                },
                {
                    "column":6, 
                    "type": "list",
                    "options":[
                        { "value": "Pending", "display": "Pending" },
                        { "value": "Paid", "display": "Paid" }
                    ]
                }
            ]
        });
    }
    
    function myCallbackFunction (updatedCell, updatedRow, oldValue) {
        console.log(updatedRow.data());
        var __data = updatedRow.data();
        var _filterMonth = new Date(Date.now()).format("mmmm yyyy");
        if($('#month_picker').val()!==""){
            _filterMonth =$('#month_picker').val();
        }
            // if(__data.month && __data.month !== ""){
            //     _filterMonth = __data.month;
            // }
        __data.filterMonth=new Date(_filterMonth).format('yyyy-mm-dd');
        __data.status=$(__data.status).text().toLowerCase();
        var _data = {
            action: "edit",
            data: __data
        };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('SimTransaction.edit_sim_inline')}}",
            data: _data,
            method: "POST"
        }).done(function(data){
            console.log(data);
        }).fail(function(xhr, status, error){
            console.log(xhr);
            console.log(error);
            console.log(status);
        });
    }

    

    $('#month_picker').on('change', function(){
        var _val = $(this).val();
        console.log('asdasd')
        getData();
    });
});
function deleteSimTransaction(id)
{
    var url = "{{ url('admin/simTransaction') }}"+ "/" + id;
    sendDeleteRequest(url, false, null, simTransaction_table);
}
function updateStatus(sim_id)
{
    var url = "{{ url('admin/simTransaction') }}" + "/" + sim_id + "/updateStatus";
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
                    simTransaction_table.ajax.reload(null, false);
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