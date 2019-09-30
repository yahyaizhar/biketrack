@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/print.min.css') }}" rel="stylesheet" type="text/css" />
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
                        Months
                        
                        </h3>
                    </div>
                </div>
 @include('client.includes.message')
                 <div class="kt-portlet__body">
                            <div>
                                 <select class="form-control kt-select2" id="kt_select2_3_5" name="month_id" >
                                <option >Select Month</option>
                                <option value="01">January</option>   
                                <option value="02">Febuary</option>   
                                <option value="03">March</option>   
                                <option value="04">April</option>   
                                <option value="05">May</option>   
                                <option value="06">June</option>   
                                <option value="07">July</option>   
                                <option value="08">August</option>   
                                <option value="09">September</option>   
                                <option value="10">October</option>   
                                <option value="11">November</option>   
                                <option value="12">December</option>    
                               </select> 
                                </div>
                            
                      
                    </div>
            </div>
    </div>
</div>
</div>

{{-- Month OPTIONS --}}
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content-b">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Balance By Month
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
            <table class="table table-striped- table-hover table-checkable table-condensed" id="MonthToRider-table">
                <thead>
                    <tr>
                        
                        <th>Rider Name</th>
                        {{-- <th>Amount</th> --}}
                        <th>Closing Balnce</th>
                        {{-- <th>Payment Date</th>
                        <th>Paid By</th>
                        <th>Payment Status</th>
                        <th>Status</th>
                        <th>Actions</th>                         --}}
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>
<div class="modal fade" id="invoice_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
            <h5 class="modal-title" id="exampleModalLabel">Salary Detail</h5>
             
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                            <label><strong>Total Salary</strong></label>
                    </div>
                    <div class="col-md-4">
                            <label><strong id="t_salary"></strong></label>
                    </div>
                </div>
                <h5>Debits</h5>
                <div class="form-group" id="append_row">
                   
                </div>
            
                <div class="row" id="gross">
                    <div class="col-md-8">
                            <label><strong >Gross Salary</strong></label>
                    </div>
                    <div class="col-md-4">
                            <label><strong id="gross_salary"></strong></label>
                    </div>
                </div>    
            </div> 
            <div class="modal-footer border-top-0 d-flex justify-content-center">
                    <button type="button" onclick="printJS({
                         printable: 'invoice_model', 
                         type: 'html' ,
                         style: '#gross{ margin-top:10px; }',
                         })">
                            Print Invoice
                        </button> 
            </div>
            
        </div>
        </div>
    </div>
{{-- end Month OPTIONS --}}
@endsection
@section('foot')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/print.min.js') }}" type="text/javascript"></script>

<script>
    var month=null;
    var rider=null;
$(document).ready(function(){
    var current_open_target=null;
     $("#invoice_model").on('shown.bs.modal', function (event){
        current_open_target=$(event.relatedTarget);
        var current_target = $(event.currentTarget);
        
        var data_rider_debits=$(event.relatedTarget).attr("data-view");
        var total_salary=$(event.relatedTarget).attr("data-salary");
        var gross_salary=$(event.relatedTarget).attr("data-gross_salary");
      
        var data_json=JSON.parse(data_rider_debits);
        console.log(data_json);
        current_target.find('#append_row').html('');
        $.each(data_json,function(index,item){
            current_target.find('#append_row').append(' <div class="row"><div class="col-md-8"><input type="text" readonly id="source_print" value="'+  item.source+'" class="form-control " name="source"  ></div><div class="col-md-4"><input type="text" readonly class="form-control " id="amount_print" value="'+  item.amount+'" name="amount"></div></div>');
        })
        current_target.find('#t_salary').text(total_salary); 
        current_target.find('#gross_salary').text(gross_salary);
        $('#t_salary_form').text(total_salary); 
        $('#gross_salary_form').text(gross_salary);  
    });
    
    

  $('#kt_content-b').hide(); 
    $("#kt_select2_3_5").change(function(){
    $('#kt_content-b').show();
    if(rider !== null){
        rider.destroy();
     }
     rider =$('#MonthToRider-table').DataTable({
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
     ajax: "{{url('admin/Month/To/Rider/ajax')}}"+"/"+$(this).val(),
     columns: [
            //  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            // { data: 'salary', name: 'salary' },
            { data: 'balance', name: 'balance' },
            // {data:'updated_at',name:'updated_at'},
            // { data: 'paid_by', name: 'paid_by' },
            // { data: 'payment_status', name: 'payment_status' },
            // { data: 'status', name: 'status' },
            // { data: 'actions', name: 'actions' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
  });

});
function deleteMonth(month_id)
{
    var url = "{{ url('admin/month') }}"+ "/" + month_id ;
    console.log(url,true);
    sendDeleteRequest(url, false, null, rider);
}
function updateStatus(month_id)
{
    var url = "{{ url('admin/month') }}" + "/" + month_id + "/updateStatus";
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
                    rider.ajax.reload(null, false);
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