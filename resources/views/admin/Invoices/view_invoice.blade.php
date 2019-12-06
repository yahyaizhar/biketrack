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
        #open_invoice_table th.invoice__table-cell-sr{
            width: 6%;
        }
        #open_invoice_table th.invoice__table-cell-meta{
            width: 45%;
        }
        #open_invoice_table th.invoice__table-cell-due_date{
            width: 15%;
        }
        #open_invoice_table th.invoice__table-cell-org_amt{
            width: 10%;
        }
        #open_invoice_table th.invoice__table-cell-due_amt{
            width: 10%;
        }
        #open_invoice_table th.invoice__table-cell-payment{
            width: 14%;
        }
        #open_invoice_table .invoice__table-row_cell-sr{
            display: inline-flex;
            width: 100%;
            text-align: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            padding-top: 17px;
            position: relative;
        }
        #receive_payment_inner{
            padding-right: 10px;
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
            <h5 class="modal-title">Receive Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form class="kt-form" enctype="multipart/form-data" id="receive_payment_form">
            <div class="modal-body">
                <div data-ktmenu-scroll="1" id="receive_payment_inner">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Customer:</label>
                                <select class="form-control bk-select2 kt-select2" data-name="client_id" name="client_id" required>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->name }}
                                    </option>     
                                @endforeach 
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-9 balance_due--wrapper text-right">
                            <h3>Amount Recevied</h3>    
                            <span class="amount_recevied">AED 0.00</span>
                        </div>
                        <div class="col-md-12">
                            <div class="messages">

                            </div>
                            
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Payment Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('F d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="payment_date" placeholder="Select Payment Date" value="">
                                
                                <span class="form-text text-muted">Please select Payment Date</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Payment Method:</label>
                                <select class="form-control bk-select2 kt-select2" data-name="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option> 
                                    <option value="cash">Cash</option>
                                    <option value="bank">Bank</option>
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-3 bank_id-wrapper">
                            <div class="form-group">
                                <label>Deposit To:</label>
                                <select class="form-control bk-select2 kt-select2" data-name="bank_id" name="bank_id">
                                    <option value="">Select Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">
                                            {{ $bank->account_number }}
                                        </option>     
                                    @endforeach 
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h3>Open Invoices</h3>
                            <div class="receive_payment--invoices_wrapper">
                                <table class="table table-striped table-hover table-checkable table-condensed" id="open_invoice_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center invoice__table-cell-sr">#</th>
                                            <th class="invoice__table-cell-meta">Description</th>
                                            <th class="invoice__table-cell-due_date">Due Date</th>
                                            <th class="invoice__table-cell-org_amt">Original Amount</th>
                                            
                                            <th class="invoice__table-cell-due_amt">Due Amount</th>
                                            <th class="invoice__table-cell-tax_amount">Payment</th>                 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
                
                <div class="kt-form__actions kt-form__actions--right">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>

<!-- end:: Content -->
@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>  
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
<script>
var bike_table;
var basic_alert= '   <div><div class="alert alert-outline-danger fade show" role="alert">  '  + 
 '                                   <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>  '  + 
 '                                       <div class="alert-text">A simple danger alert—check it out!</div>  '  + 
 '                                       <div class="alert-close">  '  + 
 '                                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">  '  + 
 '                                           <span aria-hidden="true"><i class="la la-close"></i></span>  '  + 
 '                                       </button>  '  + 
 '                                   </div>  '  + 
 '                              </div> </div>  ' ; 
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


    $('#receive_payment [data-name="client_id"]').on('change', function(){
        getOpenInvoice()
    });
    $('#receive_payment_form').on('submit', function (e) {
        e.preventDefault();
        var _form = $(this);
        receive_payment.remove_msg();
        if(receive_payment.validatePayment(_form)){
            receive_payment.savePayment(_form);
        }
    });
    $('#receive_payment [data-name="payment_method"]').on('change', function(){
        var _val = $(this).val().trim();
        $('#receive_payment .bank_id-wrapper').show().find('[data-name="bank_id"]').prop('required', true);
        if(_val=="cash")$('#receive_payment .bank_id-wrapper').hide().find('[data-name="bank_id"]').prop('required', false); 
    });
});

var receive_payment={
    updateReceivedAmount:function(){
        var _receivedAmt =0;
        if($('#open_invoice_table [data-name="payment"]').length==0) {
            $('#receive_payment .amount_recevied').attr('data-amount', 0).text('AED 0.00');
         return;   
        }
        $('#open_invoice_table [data-name="payment"]').each(function(i,amountElem){
            _receivedAmt +=parseFloat($(this).val())||0;
        });
        $('#receive_payment .amount_recevied').attr('data-amount', _receivedAmt).text('AED '+(_receivedAmt).toFixed(2));
    },
    show_msg:function(msg=""){
        if(msg=="")return;
        var _msg = $(basic_alert);
        _msg.find('.alert-text').html(msg);
        $('#receive_payment .messages').html(_msg.html());
        $('#receive_payment_inner').scrollTop(0);
    },
    remove_msg:function(){
        $('#receive_payment .messages').html('');
    },
    savePayment:function(_form){
        // return;
        $.ajax({
            url: "{{route('invoice.save_payment')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: _form.serializeArray(),
            beforeSend: function () {
                $('.bk_loading').show();
            },
            complete: function () {
                $('.bk_loading').hide();
            },
            success: function (data) {
                console.warn(data);
                getOpenInvoice();
            },
            error: function (error) {
                console.warn(error);
                swal.fire({
                    position: 'center',
                    type: 'error',
                    title: 'Oops...',
                    text: 'Unable to save.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    },
    validatePayment:function(_form){
        var _totalreceived = parseFloat($('#receive_payment .amount_recevied').attr('data-amount'))||0;
        if(_totalreceived==0){
            receive_payment.show_msg('Payment must be greater than Zero.');
            return false;
        }
        return true;
    }
};
function getOpenInvoice() {
    var client_id = $('#receive_payment [data-name="client_id"]').val();
    $.ajax({
        url: "{{url('admin/invoice/get/open/')}}"+"/"+client_id,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'GET',
        beforeSend: function () {
            $('.bk_loading').show();
        },
        complete: function () {
            $('.bk_loading').hide();
        },
        success: function (data) {
            console.warn(data);
            if(data && data.open_invoices){
                var invoices=data.open_invoices;
                receive_payment.remove_msg();
                if(invoices.length == 0){
                    $("#open_invoice_table tbody").html('');
                    receive_payment.show_msg('No open invoices found against this customer.');
                    return;
                }
                
                var  markup='';
                invoices.forEach(function(invoice, i){
                    var total_rows = parseFloat($("#open_invoice_table tbody tr").length);
                    markup += '' +
                            '   <tr>  ' +
                            '       <td class="invoice__table-row_cell-sr">'+ (i + 1) + ' </td>  ' +
                            '       <td> <a href="">Invoice #'+invoice.id+'</a> ('+new Date(invoice.invoice_date).format('mmmm dd, yyyy')+') </td>  ' +
                            '       <td> '+new Date(invoice.invoice_due).format('mmmm dd, yyyy')+' </td>  ' +
                            '       <td> AED '+invoice.invoice_total+' </td>  ' +
                            '       <td> AED '+invoice.due_balance+' </td>   ' +
                            '       <td>' +
                            '           <input type="hidden" name="payments['+i+'][invoice_id]" value="'+invoice.id+'">'+
                            '           <input data-input-type="float" type="text" class="form-control" data-name="payment" oninput="receive_payment.updateReceivedAmount()" name="payments['+i+'][amount]" min="0" value="'+invoice.due_balance+'">'+
                            '       </td>'+
                            '  </tr>  ';
                });
                $("#open_invoice_table tbody").html(markup);
                $('[data-input-type]').each(function(i,elem){
                    var _type = $(this).attr('data-input-type');
                    switch (_type) {
                        case "float":
                            biketrack.setInputFilter(this, function(value) {
                                return /^-?\d*[.,]?\d*$/.test(value); 
                            });
                            break;
                        case "int":
                            biketrack.setInputFilter(this, function(value) {
                                return /^-?\d*$/.test(value); 
                            });
                            break;
                    
                        default:
                            break;
                    }
                });
                receive_payment.updateReceivedAmount();
            } 
        },
        error: function (error) {
            console.warn(error);
            swal.fire({
                position: 'center',
                type: 'error',
                title: 'Oops...',
                text: 'Unable to get invoices.',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}
function setScrollBkModal() {
    KTUtil.scrollInit(document.getElementById('receive_payment_inner'), {
        mobileNativeScroll: true, 
        resetHeightOnDestroy: true, 
        handleWindowResize: true, 
        height: function() {
            var height;

            height = KTUtil.getViewPort().height;

            if (KTUtil.getByID('kt_header')) {
                height = height - KTUtil.actualHeight('kt_header');
            }

            if (KTUtil.getByID('kt_subheader')) {
                height = height - KTUtil.actualHeight('kt_subheader');
            }

            if (KTUtil.getByID('kt_footer')) {
                height = height - parseInt(KTUtil.css('kt_footer', 'height'));
            }

            if (KTUtil.getByID('kt_content')) {
                height = height - parseInt(KTUtil.css('kt_content', 'padding-top')) - parseInt(KTUtil.css('kt_content', 'padding-bottom'));
            }

            return height;
        }
    });
}
setScrollBkModal();
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
    $('#receive_payment').modal('show');
    $('#receive_payment [data-name="client_id"]').val(invoice.client_id).trigger('change');
    return false;
        
}
</script>

<style>
.balance_due--wrapper h3{
    font-size: 14px;
    margin: 0;
    font-weight: 400;
}
.balance_due--wrapper .amount_recevied{
    font-size: 30px;
    color: #08976d;
    font-weight: 500;
    letter-spacing: 1px;
}
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