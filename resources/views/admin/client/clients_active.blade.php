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
                    Clients
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        &nbsp;
                        <a href="{{ route('admin.clients.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="clients-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Payout Method</th>
                        <th>Salary Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

<div class="modal fade" id="payout_method_pop" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Select Payout Method</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" id="payout_methodForm" enctype="multipart/form-data">
                <input type="hidden" name="client_id">
                <div class="container">
                    <div class="form-group">
                        <label>Payout Method:</label>
                        <select class="form-control kt-select2 bk-select2" id="payout_method" name="payout_method" >
                            <option value="">Select Payout Method</option>
                            <option value="trip_based">Based on Trips and Hours</option>   
                            <option value="fixed_based">Based on Fixed Amount</option> 
                        </select>
                    </div>

                    <div class="d-none" data-payout-types data-show="trip_based">
                        <div class="form-group">
                            <label>Amount per Trip:</label>
                            <input type="text" autocomplete="off" class="form-control" name="tb__trip_amount" placeholder="Enter per trip amount" />
                        </div>
                        <div class="form-group">
                            <label>Amount per Hour:</label>
                            <input type="text" autocomplete="off" class="form-control" name="tb__hour_amount" placeholder="Enter per hour amount" />
                        </div>
                    </div>

                    <div class="d-none" data-payout-types data-show="fixed_based">
                        <div class="form-group">
                            <label>Amount:</label>
                            <input type="text" autocomplete="off" class="form-control" name="fb__amount" placeholder="Enter fixed amount" />
                        </div>
                        <div class="form-group">
                            <label>Workable Hours:</label>
                            <input type="text" autocomplete="off" class="form-control" name="fb__workable_hours" placeholder="Enter workable hours" />
                        </div>
                    </div>

                    <div class="modal-footer border-top-0 d-flex justify-content-center">
                        <button class="upload-button btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 


<div class="modal fade" id="salary_method_pop" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">Select Salary Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" id="salary_methodForm" enctype="multipart/form-data">
                    <input type="hidden" name="client_id">
                    <div class="container">
                        <div class="form-group">
                            <label>Salary Method:</label>
                            <select class="form-control kt-select2 bk-select2" id="salary_method" name="salary_method" >
                                <option value="">Select Payout Method</option>
                                <option value="trip_based">Based on Trips and Hours</option>   
                                <option value="fixed_based">Based on Fixed Amount</option> 
                                <option value="commission_based">Based on Commission</option> 
                            </select>
                        </div>
    
                        <div class="d-none" data-salary-types data-show="trip_based">
                            <div class="form-group">
                                <label>Amount per Trip:</label>
                                <input type="text" autocomplete="off" class="form-control" name="tb_sm__trip_amount" placeholder="Enter per trip amount" />
                            </div>
                            <div class="form-group">
                                <label>Amount per Hour:</label>
                                <input type="text" autocomplete="off" class="form-control" name="tb_sm__hour_amount" placeholder="Enter per hour amount" />
                            </div>
                            <div class="form-group">
                                <label>Bonus trips (e.g. 400):</label>
                                <input type="text" autocomplete="off" class="form-control" name="tb_sm__bonus_trips" placeholder="Enter bonus trips" />
                            </div>
                            <div class="form-group">
                                <label>Bonus amount after bonus trips:</label>
                                <input type="text" autocomplete="off" class="form-control" name="tb_sm__bonus_amount" placeholder="Enter bonus amount when bonus (e.g. 400) trips reached" />
                            </div>
                            <div class="form-group">
                                <label>Amount per Trip after bonus trips:</label>
                                <input type="text" autocomplete="off" class="form-control" name="tb_sm__trips_bonus_amount" placeholder="Enter per trip after bonus (e.g. 400) trips reached" />
                            </div>
                        </div>
    
                        <div class="d-none" data-salary-types data-show="fixed_based">
                            <div class="form-group">
                                <label>Amount:</label>
                                <input type="text" autocomplete="off" class="form-control" name="fb_sm__amount" placeholder="Enter fixed amount" />
                            </div>
                            <div class="form-group">
                                <label>Extra Hours Rate:</label>
                                <input type="text" autocomplete="off" class="form-control" name="fb_sm__exrta_hours" placeholder="Enter extra hours rate" />
                            </div>
                        </div>
    
                        <div class="d-none" data-salary-types data-show="commission_based">
                            <div class="form-group">
                                <label>Amount:</label>
                                <div class="input-group">
                                    <input type="text" autocomplete="off" class="form-control" name="cb_sm__amount" placeholder="Enter commission amount" />
                                    <div class="input-group-append"><span class="input-group-text" id="cb_sm__amount_postfix"></span></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Type:</label>
                                <select class="form-control kt-select2 bk-select2" id="cb_sm__type" name="cb_sm__type" >
                                    <option value="percentage" selected>Percentage</option>   
                                    <option value="fixed">Fixed</option> 
                                </select>
                            </div>
                        </div>
    
                        <div class="modal-footer border-top-0 d-flex justify-content-center">
                            <button class="upload-button btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
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
var clients_table;

function show_salary_modal(salary_method, client_id){
    salary_method = JSON.parse(salary_method);
    console.log(salary_method);
    $('#salary_method_pop').find('[name="salary_method"]').val('').trigger('change');
    $('#salary_method_pop').find('input').val('').trigger('change');
    if(salary_method!= null){
        Object.keys(salary_method).forEach(function(obj_val, i){
            $('#salary_method_pop').find('[name="'+obj_val+'"]').val(salary_method[obj_val]).trigger('change');
        });
    }
    $('#salary_method_pop').find('[name="client_id"]').val(client_id);
    $('#salary_method_pop').modal('show');
}

function show_payout_modal(client_setting, client_id){
    client_setting = JSON.parse(client_setting)
    console.log(client_setting);
    $('#payout_method_pop').find('[name="payout_method"]').val('').trigger('change');
    $('#payout_method_pop').find('input').val('').trigger('change');
    if(client_setting!= null){
        Object.keys(client_setting).forEach(function(obj_val, i){
            $('#payout_method_pop').find('[name="'+obj_val+'"]').val(client_setting[obj_val]).trigger('change');
        });
    }
    $('#payout_method_pop').find('[name="client_id"]').val(client_id);
    $('#payout_method_pop').modal('show');
}
$('#payout_method').on('change', function(){
	var _val = $(this).val().trim();
    console.log(_val)
    $('[data-payout-types]').removeClass('d-none').addClass('d-none');
    if(_val == "") return;
    var _elem = $('[data-payout-types][data-show="'+_val+'"]');
    _elem.length && (_elem.removeClass('d-none'));
});
$('#salary_method').on('change', function(){
	var _val = $(this).val().trim();
    console.log(_val)
    $('[data-salary-types]').removeClass('d-none').addClass('d-none');
    if(_val == "") return;
    var _elem = $('[data-salary-types][data-show="'+_val+'"]');
    _elem.length && (_elem.removeClass('d-none'));
});
$('#cb_sm__type').on('change', function(){
	var _type = $(this).val().trim();
    var _sign = _type=="percentage"?'%':'AED';
    $('#cb_sm__amount_postfix').text(_sign);
}).trigger('change');

$('#payout_methodForm').on('submit', function(e){
    e.preventDefault();
    var url = "{{route('admin.add_payout_method')}}";
    var _form = $(this);
    $('#payout_method_pop').modal('hide');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url : url,
        type : 'POST',
        data: _form.serializeArray(),
        beforeSend: function() {            
            $('.bk_loading').show();
        },
        complete: function(){
            $('.bk_loading').hide();
        },
        success: function(data){
            console.warn(data);
            swal.fire({
                position: 'center',
                type: 'success',
                title: 'Record updated successfully.',
                showConfirmButton: false,
                timer: 1500
            });
            clients_table.ajax.reload(null, false);
        },
        error: function(error){
            console.warn(error);
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

})
$('#salary_methodForm').on('submit', function(e){
    e.preventDefault();
    var url = "{{route('admin.add_salary_method')}}";
    var _form = $(this);
    $('#salary_method_pop').modal('hide');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url : url,
        type : 'POST',
        data: _form.serializeArray(),
        beforeSend: function() {            
            $('.bk_loading').show();
        },
        complete: function(){
            $('.bk_loading').hide();
        },
        success: function(data){
            console.warn(data);
            swal.fire({
                position: 'center',
                type: 'success',
                title: 'Record updated successfully.',
                showConfirmButton: false,
                timer: 1500
            });
            clients_table.ajax.reload(null, false);
        },
        error: function(error){
            console.warn(error);
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

})

$(function() {
    clients_table = $('#clients-table').DataTable({
        lengthMenu: [[-1], ["All"]],
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
        ajax: '{!! route('admin.clients.data.active') !!}', 
        columns: [
            // { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'new_id', name: 'new_id' },
            { data: 'new_name', name: 'name' },
            { data: 'new_phone', name: 'phone' },
            { data: 'payout_method', name: 'payout_method' },
            { data: 'salary_method', name: 'salary_method' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        responsive:true,
        // 'columnDefs': [
        //     {
        //         'targets': 0,
        //         'checkboxes': {
        //         'selectRow': true
        //         }
        //     }
        // ],
        // 'select': {
        //     'style': 'multi'
        // },
        order:[0,'desc'],
    });
    clients_table.on( 'search.dt', function () {

    });
});
function deleteClient(id)
{
    var url = "{{ url('admin/clients') }}"+ "/" + id;
    sendDeleteRequest(url, false, null, clients_table);
}
function updateStatus(client_id)
{
    var url = "{{ url('admin/client') }}" + "/" + client_id + "/updateStatus";
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
                    clients_table.ajax.reload(null, false);
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
// $(document).on('click', '#bulk_delete', function(){
//     var id=[];
//     $('.client_checkbox:checked').each(function(){
//         id.push($(this).val());
//     });
//     if(id.length > 0)
//     {
//         var url = "{{ route('admin.client.mutlipleDelete') }}";
//         swal.fire({
//             title: 'Are you sure?',
//             text: "You want udpate status!",
//             type: 'warning',
//             showCancelButton: true,
//             confirmButtonText: 'Yes!'
//         }).then(function(result) {
//             if (result.value) {
//                 $.ajaxSetup({
//                     headers: {
//                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                     }
//                 });
//                 $.ajax({
//                     url : url,
//                     type : 'POST',
//                     data : {
//                         id : id
//                     },
//                     beforeSend: function() {            
//                         $('.loading').show();
//                     },
//                     complete: function(){
//                         $('.loading').hide();
//                     },
//                     success: function(data){
//                         swal.fire({
//                             position: 'center',
//                             type: 'success',
//                             title: 'Records deleted successfully.',
//                             showConfirmButton: false,
//                             timer: 1500
//                         });
//                         clients_table.ajax.reload(null, false);
//                     },
//                     error: function(error){
//                         swal.fire({
//                             position: 'center',
//                             type: 'error',
//                             title: 'Oops...',
//                             text: 'Unable to delete.',
//                             showConfirmButton: false,
//                             timer: 1500
//                         });
//                     }
//                 });
//             }
//         });
//     }
//     else
//     {
//         swal.fire("Nothing Selected!", "Please select atleast one record.", "warning");
//     }
// });
// $(document).ready(function () {
//     // $( "#select_all").prop('checked', true);
//     $('#select_all').change(function (){
//         if($('#select_all').prop("checked") == true)
//         {
//             var cb_array = document.getElementsByClassName('client_checkbox');
//             for(var i = 0; i < cb_array.length; i++)
//             {
//                 cb_array[i].checked = true;
//             }
//         }
//         else if($('#select_all').prop("checked") == false){
//             var cb_array = document.getElementsByClassName('client_checkbox');
//             for(var i = 0; i < cb_array.length; i++)
//             {
//                 cb_array[i].checked = false;
//             }
//         }
//     });
    
// });
</script>
@endsection