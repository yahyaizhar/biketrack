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
                    Invoices.
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        &nbsp;
                        <a href="{{ route('tax.add_invoice') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="bike-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>Invoice</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th>Balance</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>                        
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

{{-- pay cash --}}
<div class="modal fade bk-modal-lg" id="receive_payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header border-bottom-0">
            <h5 class="modal-title">Cash Paid to Rider</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form class="kt-form" enctype="multipart/form-data" id="cash_paid">
            <div class="modal-body">
                <div class="kt-scroll" data-ktmenu-scroll="1" data-scroll="true"></div>
                <h2>asdasdasd</h2>
                <div class="kt-form__actions kt-form__actions--right">
                    <button type="submit" class="btn btn-primary">Pay Cash To Rider</button>
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
var bike_table;
$(function() {
    bike_table = $('#bike-table').DataTable({
        lengthMenu: [[50,-1], [50,"All"]],
        processing: true,
        serverSide: false,
        'language': { 
            // 'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
            $('.total_entries').remove();
            $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
        },
        ajax: "{!! route('invoice.get_invoices') !!}",
        columns: [
            //  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'invoice', name: 'invoice' },
            { data: 'client_name', name: 'client_name' }, 
            { data: 'date', name: 'date' },            
            { data: 'due_date', name: 'due_date' },
            { data: 'balance', name: 'balance' },
            { data: 'total', name: 'total' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions' },
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
function receive_payment_popup($this) {
    var invoice = JSON.parse($this.nextElementSibling.innerHTML);  
    console.log(invoice);
    return false;
        
}
</script>

<style>
.steps {
  list-style: none;
  margin: 0;
  padding: 0;
  display: table;
  table-layout: fixed;
  width: 100%;
  color: #929292;
  height: 4rem;
}
.steps > .step {
  position: relative;
  display: table-cell;
  text-align: center;
  font-size: 0.875rem;
  color: #6D6875;
}
.steps > .step:before {
  content: attr(data-step);
  display: block;
  margin: 0 auto;
  background: #ffffff;
  border: 2px solid #e6e6e6;
  color: #e6e6e6;
  width: 2rem;
  height: 2rem;
  text-align: center;
  margin-bottom: -4.2rem;
  line-height: 1.9rem;
  border-radius: 100%;
  position: relative;
  z-index: 1;
  font-weight: 700;
  font-size: 1rem;
}
.steps > .step:after {
  content: '';
  position: absolute;
  display: block;
  background: #e6e6e6;
  width: 100%;
  height: 0.125rem;
  top: 1rem;
  left: 50%;
}
.steps > .step:last-child:after {
  display: none;
}
.steps > .step.is-complete {
  color: #6D6875;
}
.steps > .step.is-complete:before {
  content: '\2713';
  color: #f68e20;
  background: #fef0e2;
  border: 2px solid #f68e20;
}
.steps > .step.is-complete:after {
  background: #f68e20;
}
.steps > .step.is-active {
  font-size: 1.3rem;
}
.steps > .step.is-active:before {
  color: #FFF;
  border: 2px solid #f68e20;
  background: #f68e20;
  margin-bottom: -4.9rem;
}
.step__wrapper{
    margin: 40px 0 0 0;
}
</style>
@endsection