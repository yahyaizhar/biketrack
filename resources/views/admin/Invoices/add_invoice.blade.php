@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<style>
    .custom-file-label::after{
           color: white;
           background-color: #5578eb;
        }
    .custom-file-label{
        overflow: hidden;
        }

        #invoice-table th.invoice__table-cell-sr{
            width: 6%;
        }
        #invoice-table th.invoice__table-cell-meta{
            width: 40%;
        }
        #invoice-table th.invoice__table-cell-rate{
            width: 13%;
        }
        #invoice-table th.invoice__table-cell-qty{
            width: 7%;
        }
        #invoice-table th.invoice__table-cell-total{
            padding-bottom:0;
            width: 16%;
        }
        #invoice-table th.invoice__table-cell-total .cell__text{
            display: block;
            text-align: center;         
        }
        #invoice-table th.invoice__table-cell-total .cell__sub-text{
            font-size: 10px;
            color: red;
            text-align: right;
            display: block;      
        }
        #invoice-table th.invoice__table-cell-tax{
            width: 5%;
        }
        #invoice-table th.invoice__table-cell-tax_amount{
            width: 13%;
        }
        #invoice-table .invoice__table-row_cell-sr{
            display: inline-flex;
            width: 100%;
            text-align: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            padding-top: 17px;
            position: relative;
        }
        .kt-checkbox > input:disabled ~ span {
            opacity: 0.3;
        }
        [data-name="qty"]{
            padding: 5px;
            text-align: center;
        }
        #invoice-table .invoice__remove{
            color: #ff8181;
            position: absolute;
            left: 2px;
            cursor: pointer;
        }
        #invoice-table .invoice__remove:hover{
            color: #f12626;
        }   
        .balance_due--wrapper h3{
            font-size: 14px;
            margin: 0;
            font-weight: 400;
        }
        .balance_due--wrapper .balance_due{
            font-size: 30px;
            color: #08976d;
            font-weight: 500;
            letter-spacing: 1px;
        }
        #invoice_number{
            font-weight: 600;
            font-size: 20px;
        }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Invoice <span id="invoice_number"></span>
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" action="" method="POST" enctype="multipart/form-data" id="invoices">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Select Month" value="">
                                @if ($errors->has('month'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('month') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please select Month</span>
                                @endif
                            </div>

                            <div class="form-group col-md-3">
                                <label>Customer:</label>
                                <select class="form-control bk-select2 kt-select2" id="kt_select2_3" data-name="client_id" name="client_id" required>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->name }}
                                    </option>     
                                @endforeach 
                                </select> 
                            </div>

                            <div class="col-md-6 balance_due--wrapper text-right">
                                <h3>BALANCE DUE</h3>    
                                <span class="balance_due">AED 0.00</span>
                            </div>
                            <div class="col-md-12">
                                <div class="messages">
    
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Billing Adress:</label>
                                <textarea type="text" cols="20" rows="5" class="form-control" data-name="billing_address" name="billing_address" placeholder="Enter Your Adress"></textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Invoice Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control @if($errors->has('invoice_date')) invalid-field @endif" data-name="invoice_date" name="invoice_date" placeholder="Enter Month" >
                            </div>
                            <div class="form-group col-md-4">
                                <label>Due Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->addDays(6)->format('M d, Y')}}"  readonly class="month_picker form-control @if($errors->has('due_date')) invalid-field @endif" data-name="due_date" name="due_date" placeholder="Enter Month" >
                            </div>
                        </div>
                        <table class="table table-striped- table-hover table-checkable table-condensed" id="invoice-table">
                            <thead>
                                <tr>
                                    <th class="text-center invoice__table-cell-sr">SR #</th>
                                    <th class="text-center invoice__table-cell-meta">Description</th>
                                    <th class="text-right invoice__table-cell-rate">Item Rate</th>
                                    <th class="text-right invoice__table-cell-qty">Qty</th>
                                    <th class="text-right invoice__table-cell-total">
                                        <span class="cell__text">Item Total</span>
                                        <span class="cell__sub-text">Is Deductable</span>
                                    </th>
                                    <th class="text-right invoice__table-cell-tax">TAX</th>
                                    <th class="text-right invoice__table-cell-tax_amount">Tax Amount</th>                 
                                </tr>
                                    <tbody>
                                    </tbody>
                            </thead>
                        </table>
                    </div>
                    <div class="kt-portlet__foot">
                            
                            <div class="kt-form__actions kt-form__actions--right">
                                <button style="float:left;padding: 5px;" class="btn btn-primary" onclick="append_row();return false">Add Rows</button>
                                <div class="row mt-3">
                                        <div class="col-md-5 text-right offset-md-7">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <h4>Subtotal</h4>
                                                </div>
                                                <div class="col-md-5">
                                                    <h4 class="subtotal_value">AED 0.00</h4>
                                                    <input type="hidden" data-name="invoice_subtotal" name="invoice_subtotal">
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                <div class="row mt-3">
                                        <div class="col-md-5 text-right offset-md-7">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <h6>Taxable subtotal </h6>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <h6 class="taxable_subtotal">AED 0.00</h6>
                                                            <input type="hidden" data-name="taxable_subtotal" name="taxable_subtotal">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                @php
                                    $tax_methods = \App\Tax_method::where('active_status', 'A')->get();
                                @endphp
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="message_on_invoice" class="d-block text-left">Message on invoice</label>
                                        <textarea class="form-control auto-expandable" data-name="message_on_invoice" name="message_on_invoice" rows="1" placeholder="This will show up on the invoice.">Thank you for your business and have a great day!</textarea>
                                    </div>
                                    <div class="col-md-4 text-right offset-md-2">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <select class="form-control" aria-placeholder="Select a Tax rate" data-name="tax_rate" name="tax_method_id" >
                                                    <option value=""> Select a Tax Method</option> 
                                                    @foreach ($tax_methods as $tax_method)
                                                <option value="{{$tax_method->id}}" data-type="{{$tax_method->type}}" data-value="{{$tax_method->value}}">{{$tax_method->name}} ({{$tax_method->type=='fixed'?'AED':''}} {{$tax_method->value}}{{$tax_method->type=='percentage'?'%':''}})</option> 
                                                    @endforeach
                                                     
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" data-name="tax_value" name="tax_value" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row mt-3">
                                    <div class="col-md-6 text-right offset-md-6">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <select class="form-control" data-name="discount" name="discount" >
                                                            <option value="">Select a discount type</option>
                                                            <option value="percent">Discount Percent</option>
                                                            <option value="value">Discount Value</option>  
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control text-center" data-name="discount_values" name="discount_values">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <h4 class="discount_amount" style="padding:10px 0px">AED 0.00</h4>
                                                <input type="hidden" data-name="discount_amount" name="discount_amount">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-5 text-right offset-md-7">
                                        <div class="row">
                                            <div class="col-md-7">
                                            <h4>Total</h4>
                                            </div>
                                            <div class="col-md-5">
                                                <h4 class="all_total_amount">AED 0.00</h4>
                                                <input type="hidden" data-name="invoice_total" name="invoice_total">
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                 
                            </div>
                        
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-hover-success invoice__print-btn">Print & Preview</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">Save & Send</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="invoice_status" value="drafted">
                </form>
            </div>
        </div>
    </div>
</div>


{{-- Print & Preview --}}
<div class="invoice_slip__wrapper" style="display:none;">
    <div style="display:grid;padding: 145px 20px 0px 20px;font-family: sans-serif;font-weight: normal;position: relative;" id="invoice_slip">
        <div style="height:50px"></div>
        <table style="">
            <tr>
                <div class="maintax" style="font-size:0;">
                    <div class="childa" style="display:inline-block;width:50%;font-size:12px;float:left;">
                    <ul style="padding-left:0px;margin-bottom: 0px;">
                        <li class="company_name" style="display:block;font-weight: bold; font-size: 14px;color: black; letter-spacing: 1px; font-family: monospace;" >{{$company_info->company_name}}</li>
                        <li style="display:block;color: #6f6e6e;">{{$company_info->company_address}}</li>
                        <li style="display:block;color: #6f6e6e;font-size: 12px; letter-spacing: 0.5px;">{{$company_info->company_email}}</li>
                        <li style="display: block; color: #6f6e6e; font-weight: 400;">{{$company_info->company_phone_no}}</li>
                        <li style="display: block; color: #6f6e6e; font-weight: 400;">TRN : {{$company_info->company_tax_return_no}}</li>
                    </ul>
                    {{-- <ul style="padding-left:0px">
                        
                    </ul> --}}
                    </div>
                    <div class="childb" style="display: inline-block; width: 50%; text-align: right; font-size: 24px; font-weight: 700; color: black;">
                    TAX INVOICE
                    </div>

                </div>
            </tr>
        </table>
        <p style="border-top: 1px solid #dddd;margin-top: -1px; margin-bottom: 1px;"></p>
        <table>
        <tr>
            
                    <td  style="float:left;font-weight: 500; text-align: left; color: black; text-transform: capitalize; font-size: 12px;">
                <ul style="padding-left:0px;">
                        <li style="display: block;color: black; font-size: 17px;font-weight:bold;">Bill To:</li>    
                        <li class="invoice_slip__client_name" style="display: block;font-size: 14px; letter-spacing: 1px; font-weight: bold; font-family: monospace; color: black;">Zomato Media Pvt Ltd</li>
                        <li class="invoice_slip__client_address" style="display: block;max-width: 350px; font-weight: 400;color: #6f6e6e; text-transform: capitalize;">Address</li>
                        <li class="invoice_slip__client_email" style="display:block;color: #6f6e6e;font-size: 12px; letter-spacing: 0.5px;font-weight: 300;">info@kindrider.net</li>
                        <li class="invoice_slip__client_no" style="display: block; color: #6f6e6e; font-weight: 400;">050-000000</li> 
                        <li style="display: block; color: #6f6e6e; font-weight: 400;">TRN : 000000000000</li>
                </ul>    
                </td>
                    <td  style="font-weight: 500; text-align: right; color: gray; text-transform: uppercase; font-size: 12px;">
                    <ul>
                    <li style="display:block">Invoice # : <span class="invoice_id"></span></li>
                    <li style="display:block">Invoice Month : <span class="invoice_month"></span></li>
                    <li style="display:block">Invoice date : <span class="invoice_date"></span></li>
                    <li style="display:block">Due date : <span class="invoice_due"></span> </li>
                    </ul>
                    <div style="display: inline-block;color: #6f6d6d; padding: 12px 30px; border: 1px solid #dddd; border-radius: 6px;text-align: center;">
                        BALANCE DUE:
                        <div style="font-size: 18px; font-weight: 800;color:black;" class="invoice_slip__total_amount">0.00</div>
                    </div>
                    </td>
                </tr>
        </table>
        <table class="print_class" style=" margin-top: 1px;border-collapse: collapse;font-family: sans-serif;font-size: 12px;">
            <tr style="    background: #E9F8FF;">
                <th style="width:50%;text-align:left;text-transform: uppercase;">Item Description</th>
                <th style="width:10%;text-align:left;text-transform: uppercase;">Rate</th>
                <th style="width:10%;text-align:left;text-transform: uppercase;">QTY</th>
                <th style="width:10%;text-align:left;text-transform: uppercase;">Total</th>
                <th style="width:10%;text-align:left;text-transform: uppercase;">5% VAT</th>
                <th style="width:10%;text-align:left;text-transform: uppercase;">INC. OF VAT</th>


            </tr>
        </table>
        <table class="invoice_slip__invoice_items" style=" margin-top: 0px;border-collapse: collapse;font-size: 12px; font-family: sans-serif;">
            {{-- <tr>
                <th style="border:1px solid #dddd;width:75%;text-align:left;">Item Description</th>
                <td style="border:1px solid #dddd;width:25%;text-align:left;">2000</td>
            </tr> --}}
        </table>
        <table class="invoice_slip__invoice_total" style=" margin-top: 0px;display:none;">  
            <tr style="background: #E9F8FF;">
                <th style="border:1px solid #dddd;border-top: unset;width:75%;text-align:right;">Total payable</th>
                <td style="border:1px solid #dddd;border-top: unset;width:25%;text-align:right;" class="invoice_slip__total_amount">Total</td>
            </tr>
        </table>

        <div style=""> 
            <p style="font-size:12px;line-height: 14px;margin: 5px;" class="invoice_slip__message"></p>
        </div>

        <div style="text-align:end;"> 
   <div class="invoice_footer" style="display: flow-root;">
    <div style="display:inline-block;float:left;width:50%;text-align:left;">
    <div style="color: black; font-size: 13px; font-weight: normal; padding-bottom: 0px; padding-top: 10px;">Invoice Notes:</div>
    <div class="custm_message_on_invoice" style="color: black; display: inline-block; border: 1px solid #dddd; padding: 4px 35px 41px 10px;font-size: 12px; border-radius: 5px;">Thank for your business and have a great day!</div>
    </div>
    <div style="display:inline-block;float:left;width:50%;font-weight: 500; color: black;">
        <ul style="display: inline-block; float: left;text-align: right;">
    <li style="display: block;">Subtotal</li>
    <li style="display: block;">Tax(5%)</li>
    <li style="display: block;" class="invoice_without_discount_total">Total </li>
    <li style="display: block;"><b>Balance due</b></li>
       </ul>

       <ul>
            <li style="display: block;" class="custm_subtotal">0.00</li>
            <li style="display: block;" class="custm_tax">0.00</li>
            <li style="display: block;" class="custm_totl invoice_without_discount_total">0.00 </li>
            <li style="display: block;font-weight:bold;" class="custm_total_price">0.00</li>
       </ul>
    </div>

   </div>
   <ul style="padding-left: 0px;position: absolute; top: 680px;">
   <li style="display: block;text-align: left; margin-top: 55px; color: #504e4e; font-weight: 800; text-transform: capitalize; font-size: 14px; font-family: sans-serif;">Make All Payment Through Cheque</li>
   <li style="display: block;font-weight: 500; text-align: left; color: #504e4e; font-size: 13px;    font-family: sans-serif;">Account Title: <span class="account_title"> {{$company_info->company_name}}</span></li>
   <li style="display: block;font-weight: 500; text-align: left; color: #504e4e; font-size: 13px;    font-family: sans-serif;">Account No: <span class="account_no">{{$company_info->company_account_no}}</span></li>
   <li style="display: block;font-weight: 500; text-align: left; color: #504e4e; font-size: 13px;    font-family: sans-serif;">Bank Name: <span class="bank_name"> {{$company_info->company_bank_name}}</span></li>
   </ul>
   <p style="font-weight: 600; text-align: right; color: #504e4e; font-size: 12px; padding-top: 3px; display: inline-block; border-top: 1px solid #dddd; margin-top: 70px;position: absolute; top: 800px;right:5px ">COMPANY STAMP & SIGN</p>
        </div>
    </div>
    </div>

@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src=" https://printjs-4de6.kxcdn.com/print.min.js" type="text/javascript"></script>
<link href=" https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<script>
var invoiceObj=null;
var basic_alert= '   <div><div class="alert alert-outline-danger fade show" role="alert">  '  + 
 '                                   <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>  '  + 
 '                                       <div class="alert-text">A simple danger alert—check it out!</div>  '  + 
 '                                       <div class="alert-close">  '  + 
 '                                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">  '  + 
 '                                           <span aria-hidden="true"><i class="la la-close"></i></span>  '  + 
 '                                       </button>  '  + 
 '                                   </div>  '  + 
 '                              </div> </div>  ' ; 
$(document).ready(function () {
    // append_row();
    $('#invoices [data-name="client_id"],#invoices [name="month"]').on("change input", function () {
        var client_id = $('#invoices [data-name="client_id"]').val();
        console.log('client_id', client_id);
        var _month = new Date("01-"+$('#invoices [name="month"]').val()+"-"+new Date(Date.now()).format('yyyy')).format('yyyy-mm-dd');
        var url_data = {
            edit:0,
            client_id: client_id,
            month:_month
        }
        biketrack.updateURL(url_data);
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{url('admin/invoice/tax/ajax/get_clients_details/')}}" + '/' + client_id + "/" + _month,
                method: "GET"
            })
            .done(function (resp) {
                console.log(resp);
                if (resp.status == 1) {
                    $("#invoice-table tbody").html('');
                    $('#invoice_number').removeAttr('data-invoice').html('');
                    _invoices.remove_msg();
                    invoiceObj=null;
                    $('#invoices [data-name="billing_address"]').val(resp.billing_address);
                    // $('[data-name="tax_rate"]').val(5);
                    var row_data = resp.items;
                    append_row(row_data);
                    
                    if(resp.is_edit){
                        _invoices.invoice=resp.invoice;
                        _invoices.reload_page(resp);
                    }
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
                    subtotal();
                    
                } else {
                    _invoices.show_msg(resp.message);
                }

            });
    });

    

    $(document).on("change input", '[data-name="rate"], [data-name="amount"], [data-name="tax_rate"], [data-name="discount_values"], [data-name="tax"], [data-name="discount"], [data-name="qty"], [data-name="deductable"]', function () {
        if($(this).attr('data-name')=="deductable"){
            var _taxBox = $(this).parents('tr').find('[data-name="tax"]');
            
            _taxBox.prop('disabled', false)
            if($(this).is(':checked')){
                _taxBox.prop('disabled', true);
            }
            
        }
        subtotal();
    });

    $(document).on("keydown", 'textarea.auto-expandable', autosize);

    $('#invoices').on('submit', function (e) {
        e.preventDefault();
        var _form = $(this);
        if(validate_invoice(_form)){
            save_invoice(_form, "generated");
        }
    });

    var validate_invoice=function(_form){
        var _subtotal = parseFloat($('[data-name="invoice_subtotal"]').val())||0;
        if(_subtotal==0){
            _invoices.remove_msg();
            _invoices.show_msg('Invoice subtotal cannot be Zero');
            return false;
        }
        return true;
    }

    var save_invoice =function(_form=null,invoice_status, callback=null){
        _form = _form||$('#invoices');
        $('[name="invoice_status"]').val('drafted');
        if(invoice_status && invoice_status != ""){
            $('[name="invoice_status"]').val(invoice_status);
        }
        //
        $.ajax({
            url: "{{route('tax.add_invoice_post')}}",
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
                if(data && data.invoice){
                    invoiceObj=data.invoice;
                    $('#invoice_number').attr('data-invoice',data.invoice.id).text('#'+(data.invoice.id));
                } 
                if(callback && typeof callback=="function"){
                    callback(data.invoice);
                }
                // swal.fire({
                //     position: 'center',
                //     type: 'success',
                //     title: 'Record updated successfully.',
                //     showConfirmButton: false,
                //     timer: 1500
                // });
                // clients_table.ajax.reload(null, false);
            },
            error: function (error) {
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
    }

    var print_invoice=function(invoice){
        var invoice = invoice||invoiceObj;
        console.log(invoice);
        if(invoice){
            $('.invoice_slip__client_address').text(invoice.client.address);
            $('.invoice_slip__client_email').text(invoice.client.email);
            $('.invoice_slip__client_no').text(invoice.client.phone);
            $('.invoice_slip__client_name').text(invoice.client.name);
            $('.invoice_slip__total_amount').text('AED '+Math.round(invoice.invoice_total));
            $('.invoice_date').text(invoice.invoice_date)
            $('.invc_no').text(invoice.id)
            $('.invoice_month').text(new Date(invoice.month).format('mmmm yyyy'));
            $('.invoice_slip__invoice_items').html('');
            $('.custm_subtotal').text('AED '+invoice.invoice_total)
            if(invoice.taxable_amount == null){
                        invoice.taxable_amount = '0.00'
                    }
            if(invoice.discount_amount == null){
                $('.invoice_without_discount_total').hide()
            }
            else{
                $('.invoice_without_discount_total').show()
            }        
            $('.custm_tax').text('AED '+invoice.taxable_amount);
            $('.custm_totl').text('AED '+invoice.invoice_subtotal);
            $('.custm_total_price').text('AED '+ Math.round(invoice.invoice_total));
            $('.custm_message_on_invoice').text()
            $('.custm_message_on_invoice').html(invoice.message_on_invoice);
            $('.invoice_id').text(invoice.id)
            $('.invoice_date').text(invoice.invoice_date)
            $('.invoice_due').text(invoice.invoice_due)
            var _addrow=true;
            var _total_inclusive_of_vat = 0;
                invoice.invoice_items.forEach(function(item, i){
                    var _txt_amount=0;
                    if(item.taxable_amount >0){
                        _txt_amount =item.taxable_amount ; 
                    }
                    else{
                        _txt_amount ='0.00';
                    }
                    var _inclusive_of_vat = parseFloat(item.subtotal)+parseFloat(_txt_amount);
                    _inclusive_of_vat = _inclusive_of_vat.toFixed(2);
                    if(item.deductable == 0){
                        _total_inclusive_of_vat =parseFloat(_total_inclusive_of_vat)+parseFloat( _inclusive_of_vat);
                        _total_inclusive_of_vat = _total_inclusive_of_vat.toFixed(2);
                        // console.log(_total_inclusive_of_vat);
                    }
                    var _itemRate=item.item_rate,
                        _itemQty=item.item_qty,
                        _itemSubtotal=item.subtotal;
                    if(item.deductable == 1){ 
                        _itemRate ='';
                        _itemQty ='';
                        _itemSubtotal ='';
                        _txt_amount='';
                        if(_addrow){
                            var _newrow  =  '   <tr>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:50%;text-align:left;padding: 9px;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                    '   </tr>  '  + 
                                    '   <tr style="display:none;">  '  + 
                                    '   	<td style="border:1px solid #dddd;width:50%;text-align:left;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;">Total</td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                    '   	<td style="border:1px solid #dddd;width:10%;text-align:left;">'+_total_inclusive_of_vat+'</td>  '  + 
                                    '  </tr>  ' ;  
                                $('.invoice_slip__invoice_items').append(_newrow);
                                _addrow=false;
                            }
                        }
        
                        var item_row = '    <tr>'+
                                '     <td style="border:1px solid #dddd;width:50%;text-align:left;">'+item.item_desc+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_itemRate+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_itemQty+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_itemSubtotal+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_txt_amount+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_inclusive_of_vat+'</td>'+
                                '    </tr>';
                        $('.invoice_slip__invoice_items').append(item_row);
                });

            printJS('invoice_slip', 'html');
        }
        else{
            var _form = $('#invoices');
            if(validate_invoice(_form)){
                save_invoice(_form, "drafted", function(invoice){
                    // $('.invoice_slip__number').text('Invoice #'+(invoice.id));
                    $('.invoice_slip__client_address').text(invoice.client.address);
                    $('.invoice_slip__client_email').text(invoice.client.email);
                    $('.invoice_slip__client_no').text(invoice.client.phone);
                    $('.invoice_slip__client_name').text(invoice.client.name);
                    $('.invoice_slip__total_amount').text('AED '+Math.round(invoice.invoice_total));
                    $('.invoice_date').text(invoice.invoice_date)
                    $('.invc_no').text(invoice.id)
                    $('.invoice_month').text(new Date(invoice.month).format('mmmm yyyy'));
                    $('.custm_subtotal').text('AED '+invoice.invoice_total)
                    if(invoice.taxable_amount == null){
                        invoice.taxable_amount = '0.00'
                    }
                    if(invoice.discount_amount == null){
                      $('.invoice_without_discount_total').hide();
                    } 
                    else{
                        $('.invoice_without_discount_total').show();
                    }
                    $('.custm_tax').text('AED '+invoice.taxable_amount);
                    $('.custm_totl').text('AED '+invoice.invoice_subtotal);
                    $('.custm_total_price').text('AED '+Math.round(invoice.invoice_total));
                    $('.custm_message_on_invoice').text(invoice.message_on_invoice);
                    $('.invoice_id').text(invoice.id)
                    $('.invoice_date').text(invoice.invoice_date)
                    $('.invoice_due').text(invoice.invoice_due)
                    var _addrow=true;
                    var _total_inclusive_of_vat = 0;
                    invoice.invoice_items.forEach(function(item, i){
                        var _txt_amount=0;
                        if(item.taxable_amount >0){
                            _txt_amount =item.taxable_amount ; 
                        }
                        else{
                            _txt_amount ='0.00';
                        }
                        var _inclusive_of_vat = parseFloat(item.subtotal)+parseFloat(_txt_amount);
                        _inclusive_of_vat = _inclusive_of_vat.toFixed(2);
                        if(item.deductable == 0){
                        _total_inclusive_of_vat =parseFloat(_total_inclusive_of_vat)+parseFloat( _inclusive_of_vat);
                        _total_inclusive_of_vat = _total_inclusive_of_vat.toFixed(2);
                        // console.log(_total_inclusive_of_vat);
                        }
                        var _itemRate=item.item_rate,
                        _itemQty=item.item_qty,
                        _itemSubtotal=item.subtotal;
                    if(item.deductable == 1){ 
                        _itemRate ='';
                        _itemQty ='';
                        _itemSubtotal ='';
                        _txt_amount='';
                        if(_addrow){
                        var _newrow  =  '   <tr>  '  + 
                                '   	<td style="border:1px solid #dddd;width:50%;text-align:left;padding: 9px;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                '   </tr>  '  + 
                                '   <tr style="display:none;">  '  + 
                                '   	<td style="border:1px solid #dddd;width:50%;text-align:left;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;">Total</td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;"> </td>  '  + 
                                '   	<td style="border:1px solid #dddd;width:10%;text-align:left;">'+_total_inclusive_of_vat+'</td>  '  + 
                                '  </tr>  ' ;    
                          $('.invoice_slip__invoice_items').append(_newrow);
                    _addrow=false;  
                        }
                    }

                     var item_row = '    <tr>'+
                                '     <td style="border:1px solid #dddd;width:50%;text-align:left;">'+item.item_desc+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_itemRate+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_itemQty+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_itemSubtotal+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_txt_amount+'</td>'+
                                '     <td style="border:1px solid #dddd;width:10%;text-align:left;">'+_inclusive_of_vat+'</td>'+
                                '    </tr>';
                        $('.invoice_slip__invoice_items').append(item_row);
                    });

                    printJS('invoice_slip', 'html')
                });
            }
        }
    }

    $('.invoice__print-btn').on('click', function(){
        print_invoice();
    })
    _invoices.fetch_url_data();
});



var total_amount = 0;
var taxable_amount = 0;

var _invoices={
    invoice:null,
    show_msg:function(msg=""){
        if(msg=="")return;
        var _msg = $(basic_alert);
        _msg.find('.alert-text').html(msg);
        $('.messages').html(_msg.html());
        $(document).scrollTop(0)
    },
    remove_msg:function(){
        $('.messages').html('');
    },
    reload_page:function(response){
        var invoice=_invoices.invoice;
        if(invoice==null){
            _invoices.remove_msg();
            _invoices.show_msg("Cannot find invoice.");
            return;
        }
        //updating url
        var url_data = {
            invoice_id:invoice.id,
            edit:1,
            client_id: invoice.client_id,
            month: invoice.month
        }
        biketrack.updateURL(url_data);
        //changing invoice id
        $('#invoice_number').attr('data-invoice',invoice.id).text('#'+(invoice.id));
        $('#invoices [data-name="invoice_date"]').attr('data-month', new Date(invoice.invoice_date).format('mmm dd, yyyy'));
        $('#invoices [data-name="due_date"]').attr('data-month', new Date(invoice.invoice_due).format('mmm dd, yyyy'));
        $('#invoices [name="month"]').attr('data-month', new Date(invoice.month).format('mmmm yyyy'));

        biketrack.refresh_global();
    },
    fetch_url_data:function(){
        var _clientId=biketrack.getUrlParameter('client_id');
        var _month=biketrack.getUrlParameter('month');
        if(_clientId!="" && _month!=""){
            $('#invoices [name="month"]').attr('data-month', new Date(_month).format('mmm dd, yyyy'));
            biketrack.refresh_global();
            $('#invoices [name="client_id"]').val(_clientId).trigger('change');

        }
    }
};
function subtotal() {
    total_amount = 0;
    taxable_amount = 0;
    var non_tax_amount = 0;
    var res_of_tax;
    var tax_rate = parseFloat($('[data-name="tax_rate"]').val()) || 0;
    var tax_type=$('[data-name="tax_rate"] :selected').attr('data-type')||"";
    var tax_value=parseFloat($('[data-name="tax_rate"] :selected').attr('data-value'))||0;

    console.log('yes');

    $('[data-name="tax_value"]').val("");
    $('[data-name="tax_amount"]').val(0);

    $("#invoice-table tbody tr").each(function (item, index) {
        // var is_payable = $(this).find('[data-name="payable"]').is(":checked");
        var is_deductable = $(this).find('[data-name="deductable"]').is(":checked");

        var rate = parseFloat($(this).find('[data-name="rate"]').val()) || 0;
        if(is_deductable){
            rate = rate*-1;
        }
        var qty = parseFloat($(this).find('[data-name="qty"]').val()) || 0;

        var amount = rate * qty;

        // if(is_deductable){
        //     total_amount -= amount;
        //     non_tax_amount -= amount;
        // }
        // else{
            total_amount += amount;
            non_tax_amount += amount;
        //}
        
        if ($(this).find('[data-name="tax"]').is(":checked") && !is_deductable) {
            taxable_amount += amount;
            if (tax_rate > 0) {
                var tax_amount=0;
                if(tax_type=="percentage"){
                    tax_amount = (amount * tax_value) / 100;
                }
                else{
                    tax_amount = tax_value/$("#invoice-table [data-name='tax']:checked").length;
                }
                
                amount += tax_amount;
                $(this).find('[data-name="tax_amount"]').val((tax_amount).toFixed(2));
            }
        }

        $(this).find('[data-name="amount"]').val((amount).toFixed(2));
    });



    if (tax_rate > 0) {
        var vat_val = parseFloat(tax_rate);
        if (taxable_amount > 0) {
            if(tax_type=="percentage"){
                res_of_tax = (taxable_amount * tax_value) / 100;
            }
            else{
                res_of_tax = tax_value;
            }
            $('[data-name="tax_value"]').val((res_of_tax).toFixed(2));
            total_amount += res_of_tax;
        }
    }
    var discount = $('[data-name="discount"]').val();
    if (discount == 'percent') {
        var discount_value_percent = parseFloat($('[data-name="discount_values"]').val()) || 0;
        res_of_discount = (discount_value_percent * non_tax_amount) / 100;
        $('.discount_amount').text('AED -' + (res_of_discount).toFixed(2));
        $('[data-name="discount_amount"]').val((res_of_discount).toFixed(2));
        total_amount -= res_of_discount;
    }
    if (discount == 'value') {
        var discount_value_value = parseFloat($('[data-name="discount_values"]').val()) || 0;
        var res_of_discount = discount_value_value;
        $('.discount_amount').text('AED -' + (res_of_discount).toFixed(2));
        total_amount -= res_of_discount;
    }

    $(".subtotal_value").text("AED " + (non_tax_amount).toFixed(2));
    
    $(".taxable_subtotal").text("AED " + (taxable_amount).toFixed(2));
    $('.all_total_amount').text("AED " + (total_amount).toFixed(2));
    $('.balance_due').text("AED " + (total_amount).toFixed(2));

    $("[data-name='invoice_subtotal']").val((non_tax_amount).toFixed(2));
    $("[data-name='taxable_subtotal']").val((taxable_amount).toFixed(2));
    $("[data-name='invoice_total']").val((total_amount).toFixed(2));
    // 
}



function append_row($row_data = null) {
    var markup = '';
    var total_rows = parseFloat($("#invoice-table tbody tr").length);
    if ($row_data != null) {
        console.log($row_data);
        $row_data.forEach(function (item, i) {
            var _isTaxable = item.is_taxable ? 'checked' : '';
            // var _isPaybale = item.is_payable?'checked':'';
            var _isDeductable = item.is_deductable ? 'checked' : '';
            var _isTaxDisabled = item.is_deductable ? 'disabled' : '';
            var tax = '';
            //if (!_isDeductable) {
                tax = '<div class="kt-checkbox-list"><label class="kt-checkbox"> <input data-name="tax" name="invoice_items['+i+'][tax]" type="checkbox" ' + _isTaxable +_isTaxDisabled+ '><span></span> </label></div>';
           // }
            var action = '<button type="button" onclick="delete_row(this);" class="delete-row btn btn-danger"><i class="fa fa-trash-alt"></i></button>';
            markup += '' +
                '   <tr>  ' +
                '       <td class="invoice__table-row_cell-sr"> <span class="flaticon2-trash invoice__remove" onclick="delete_row(this);"></span>' + (i + 1) + ' </td>  ' +
                '       <td> <textarea type="text" class="form-control auto-expandable" data-name="description" name="invoice_items['+i+'][description]" rows="1">' + item.desc + '</textarea> </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" data-name="rate" name="invoice_items['+i+'][rate]" min="0" value="' + item.rate + '"> </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" data-name="qty" name="invoice_items['+i+'][qty]" min="1" value="' + item.qty + '"> </td>  ' +
                '       <td> ' +
                '           <div class="input-group">   ' +
                '               <input type="text" class="form-control" placeholder="Amount" data-name="amount" name="invoice_items['+i+'][amount]" readonly aria-describedby="basic-addon2">   ' +
                '               <div class="input-group-append">  ' +
                '                   <span class="input-group-text" id="basic-addon2">' +
                '                       <label class="kt-checkbox kt-checkbox--single kt-checkbox--primary"> <input type="checkbox" data-name="deductable" name="invoice_items['+i+'][deductable]" ' + _isDeductable + '> <span></span> </label>' +
                '                   </span>  ' +
                '               </div>   ' +
                '           </div>  ' +
                '       </td>  ' +
                '       <td>   ' +
                tax +
                '       </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" data-name="tax_amount" name="invoice_items['+i+'][tax_amount]" min="0" value="0"> ' +
                '           '
            '       </td>  ' +
            '  </tr>  ';
        });
        $("#invoice-table tbody").append(markup);
        
        return;
    }

    markup = '' +
        '   <tr>  ' +
        '       <td class="invoice__table-row_cell-sr"> <span class="flaticon2-trash invoice__remove" onclick="delete_row(this);"></span>' + (total_rows + 1) + ' </td>  ' +
        '       <td> <textarea type="text" class="form-control auto-expandable" data-name="tax_amount" name="invoice_items['+total_rows+'][description]" rows="1"></textarea> </td>  ' +
        '       <td> <input data-input-type="float" class="form-control" data-name="rate" name="invoice_items['+total_rows+'][rate]" min="0" value="0"> </td>  ' +
        '       <td> <input data-input-type="float" class="form-control" data-name="qty" name="invoice_items['+total_rows+'][qty]" min="1" value="1"> </td>  ' +
        '       <td> ' +
        '           <div class="input-group">   ' +
        '               <input type="text" class="form-control" placeholder="Amount" data-name="amount" name="invoice_items['+total_rows+'][amount]" readonly aria-describedby="basic-addon2">   ' +
        '               <div class="input-group-append">  ' +
        '                   <span class="input-group-text" id="basic-addon2">' +
        '                       <label class="kt-checkbox kt-checkbox--single kt-checkbox--primary"> <input type="checkbox" data-name="deductable" name="invoice_items['+total_rows+'][deductable]"> <span></span> </label>' +
        '                   </span>  ' +
        '               </div>   ' +
        '           </div>  ' +
        '       </td>  ' +
        '       <td>   ' +
        '           <div class="kt-checkbox-list">  ' +
        '               <label class="kt-checkbox">   ' +
        '                   <input data-name="tax" name="invoice_items['+total_rows+'][tax]" type="checkbox">  ' +
        '                   <span></span>   ' +
        '               </label>  ' +
        '           </div>  ' +
        '       </td>  ' +
        '       <td> <input data-input-type="float" class="form-control" data-name="tax_amount" name="invoice_items['+total_rows+'][tax_amount]" min="0" value="0"> ' +
        '           '
    '       </td>  ' +
    '  </tr>  ';
    $("#invoice-table tbody").append(markup);
}

function delete_row(ctl) {
    $(ctl).parents("tr").remove();
    subtotal();
}


function autosize() {
    var el = this;
    console.log(el)
    setTimeout(function () {
        el.style.cssText = 'height:auto; padding:0';
        // for box-sizing other than "content-box" use:
        // el.style.cssText = '-moz-box-sizing:content-box';
        el.style.cssText = 'height:' + (el.scrollHeight + 23) + 'px';
    }, 0);
}


</script>
@endsection

