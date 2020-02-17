@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<style data-ajax>
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
        .__pm__content{
            width: 170px;
            display: grid;
            grid-template-columns: auto auto;
            justify-content: normal;
        }
        .__pm__heading{
            font-weight: 500;
            margin-bottom: 5px;
            font-size: 13px;
            color: #000;
        }
        .invoice__header-generated_by-text{
            font-size: 11px;
            color: #999;
        }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title page__title">
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
                                <input type="text" data-non-readonly data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Select Month" value="">
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
                                <select class="form-control bk-select2 kt-select2" id="kt_select2_3" data-non-readonly data-name="client_id" name="client_id" required>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" data-info='{!!json_encode($client)!!}'    >
                                        {{ $client->name }}
                                    </option>     
                                @endforeach 
                                </select> 
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Customer Details:</label>
                                    <textarea type="text" cols="20" data-non-readonly rows="5" class="form-control" data-name="company_details" name="company_details" placeholder="Enter Customer Details">{{$company_info->company_address}}&#13;&#10;{{$company_info->company_email}}&#13;&#10;{{$company_info->company_phone_no}}&#13;&#10;{{$company_info->company_tax_return_no}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-3 balance_due--wrapper text-right">
                                <h3>BALANCE DUE</h3>    
                                <span class="balance_due">AED 0.00</span>
                                <div class="receive_payment_btn" style="display:none;">
                                    <a href="" class="reveive_payment btn btn-secondary" onclick="receive_payment_popup(this);return false;">Receive Payment</a>
                                </div>
                                <div class="payments_made_container">
                                    <a href="" class="payments_made" onclick="return false;"></a>
                                </div>
                                
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
                            <div class="form-group col-md-2">
                                <div>
                                    <label>Invoice id:</label>
                                    <input required type="text" class="form-control @if($errors->has('invoice_id')) invalid-field @endif" data-name="invoice_id" name="invoice_id" placeholder="Enter Invoice Id" >
                                </div>
                                <div>
                                    <div class="kt-checkbox-inline">
										<label class="kt-checkbox">
				                           	<input type="checkbox"> Email 
				                            <span></span>
				                        </label>
				                    </div>
                                </div>

                            </div>
                            <div class="form-group col-md-3">
                                <label>Invoice Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control @if($errors->has('invoice_date')) invalid-field @endif" data-name="invoice_date" name="invoice_date" placeholder="Enter Month" >
                            </div>
                            <div class="form-group col-md-3">
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
                                <button style="float:left;padding: 5px;" class="btn btn-primary btn--addnewrow" onclick="append_row();return false">Add Rows</button>
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
                                                <option value="{{$tax_method->id}}" @if ($tax_method->is_default==1)selected @endif data-default="{{$tax_method->is_default}}" data-type="{{$tax_method->type}}" data-value="{{$tax_method->value}}">{{$tax_method->name}} ({{$tax_method->type=='fixed'?'AED':''}} {{$tax_method->value}}{{$tax_method->type=='percentage'?'%':''}})</option> 
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

                                <div class="edit_page_content">
                                    <div class="row amount_received-wrapper">
                                        <div class="col-md-5 text-right offset-md-7">
                                            <div class="row">
                                                <div class="col-md-7">
                                                <h4>Amount received</h4>
                                                </div>
                                                <div class="col-md-5">
                                                    <h4 class="all_amount_received">AED 0.00</h4>
                                                    <input type="hidden" data-name="amount_received" name="amount_received">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-5 text-right offset-md-7">
                                            <div class="row">
                                                <div class="col-md-7">
                                                <h4>Balance Due</h4>
                                                </div>
                                                <div class="col-md-5">
                                                    <h4 class="balance_due">AED 0.00</h4>
                                                </div>
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
                                    <button type="button" class="btn btn-warning btn-form-submit btn-save-invoice-drafted">Save As Draft</button>
                                <button type="button" class="btn btn-info btn-wide btn-form-submit btn-save-invoice-generate">Generate Invoice</button>
                                <button type="button" class="btn btn-info btn-wide btn-form-submit btn-edit-invoice" style="display:none">Edit Invoice</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="invoice_status" value="drafted">

                    {{-- Print & Preview --}}
                    <div class="invoice_slip__wrapper" style="display:none">
                        <div style="display:grid;padding: 145px 20px 0px 20px;font-family: sans-serif;font-weight: normal;position: relative;" id="invoice_slip">
                            <div style="height:50px"></div>
                            <table style="">
                                <tr>
                                    <div class="maintax" style="font-size:0;">
                                        <div class="childa " style="display:inline-block;width:50%;font-size:12px;float:left;">
                                            <ul style="padding-left:0px;margin-bottom: 0px;">
                                                <li class="company_name"
                                                    style="display:block;font-weight: bold; font-size: 14px;color: black; letter-spacing: 1px; font-family: monospace;">
                                                    {{$company_info->company_name}}</li>
                                                <li style="display:block;color: #6f6e6e;" class="company__details">
                                                    {{$company_info->company_address}}

                                                </li>
                                                {{-- <li style="display:block;color: #6f6e6e;font-size: 12px; letter-spacing: 0.5px;">
                                                    {{$company_info->company_email}}</li>
                                                <li style="display: block; color: #6f6e6e; font-weight: 400;">
                                                    {{$company_info->company_phone_no}}</li>
                                                <li style="display: block; color: #6f6e6e; font-weight: 400;">TRN :
                                                    {{$company_info->company_tax_return_no}}</li> --}}
                                            </ul>
                                            {{-- <ul style="padding-left:0px">
                                            
                                        </ul> --}}
                                        </div>
                                        <div class="childb"
                                            style="display: inline-block; width: 50%; text-align: right; font-size: 24px; font-weight: 700; color: black;">
                                            TAX INVOICE
                                        </div>

                                    </div>
                                </tr>
                            </table>
                            <p style="border-top: 1px solid #dddd;margin-top: -1px; margin-bottom: 1px;"></p>
                            <table>
                                <tr>

                                    <td
                                        style="float:left;font-weight: 500; text-align: left; color: black; text-transform: capitalize; font-size: 12px;">
                                        <ul style="padding-left:0px;">
                                            <li style="display: block;color: black; font-size: 17px;font-weight:bold;">Bill To:</li>
                                            <li class="invoice_slip__client_name"
                                                style="display: block;font-size: 14px; letter-spacing: 1px; font-weight: bold; font-family: monospace; color: black;">
                                                Zomato Media Pvt Ltd</li>
                                            <li class="invoice_slip__client_address"
                                                style="display: block;max-width: 350px; font-weight: 400;color: #6f6e6e; text-transform: capitalize;">
                                                Address</li>
                                            <li class="invoice_slip__client_email"
                                                style="display:block;color: #6f6e6e;font-size: 12px; letter-spacing: 0.5px;font-weight: 300;">
                                                info@kindrider.net</li>
                                            <li class="invoice_slip__client_no" style="display: block; color: #6f6e6e; font-weight: 400;">
                                                050-000000</li>
                                            <li class="invoice_slip__client_trn" style="display: block; color: #6f6e6e; font-weight: 400;">TRN : 000000000000</li>
                                        </ul>
                                    </td>
                                    <td
                                        style="font-weight: 500; text-align: right; color: gray; text-transform: uppercase; font-size: 12px;">
                                        <ul>
                                            <li style="display:block">Invoice # : <span class="invoice_id"></span></li>
                                            <li style="display:block">Invoice Month : <span class="invoice_month"></span></li>
                                            <li style="display:block">Invoice date : <span class="invoice_date"></span></li>
                                            <li style="display:block">Due date : <span class="invoice_due"></span> </li>
                                        </ul>
                                        <div
                                            style="display: inline-block;color: #6f6d6d; padding: 12px 30px; border: 1px solid #000; border-radius: 6px;text-align: center;">
                                            BALANCE DUE:
                                            <div style="font-size: 18px; font-weight: 800;color:black;" class="invoice_slip__total_amount">
                                                0.00</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table class="print_class"
                                style=" margin-top: 1px;border-collapse: collapse;font-family: sans-serif;font-size: 12px;">
                                <tr style="    background: #E9F8FF;">
                                    <th style="width:50%;text-align:left;text-transform: uppercase;">Item Description</th>
                                    <th style="width:10%;text-align:left;text-transform: uppercase;">Rate</th>
                                    <th style="width:10%;text-align:left;text-transform: uppercase;">QTY</th>
                                    <th style="width:10%;text-align:left;text-transform: uppercase;">Total</th>
                                    <th style="width:10%;text-align:left;text-transform: uppercase;">5% VAT</th>
                                    <th style="width:10%;text-align:left;text-transform: uppercase;">INC. OF VAT</th>


                                </tr>
                            </table>
                            <table class="invoice_slip__invoice_items"
                                style=" margin-top: 0px;border-collapse: collapse;font-size: 12px; font-family: sans-serif;">
                                {{-- <tr>
                                    <th style="border:1px solid #dddd;width:75%;text-align:left;">Item Description</th>
                                    <td style="border:1px solid #dddd;width:25%;text-align:left;">2000</td>
                                </tr> --}} 
                            </table>
                            <table class="invoice_slip__invoice_total" style=" margin-top: 0px;display:none;">
                                <tr style="background: #E9F8FF;">
                                    <th style="border:1px solid #dddd;border-top: unset;width:75%;text-align:right;">Total payable</th>
                                    <td style="border:1px solid #dddd;border-top: unset;width:25%;text-align:right;"
                                        class="invoice_slip__total_amount">Total</td>
                                </tr>
                            </table>

                            <div style="">
                                <p style="font-size:12px;line-height: 14px;margin: 5px;" class="invoice_slip__message"></p>
                            </div>

                            <div style="text-align:end;">
                                <div class="invoice_footer" style="display: flow-root;">
                                    <div style="display:inline-block;float:left;width:50%;text-align:left;">
                                        <div
                                            style="color: black; font-size: 13px; font-weight: normal; padding-bottom: 0px; padding-top: 10px;">
                                            Invoice Notes:</div>
                                        <div class="custm_message_on_invoice"
                                            style="color: black; display: inline-block; border: 1px solid #dddd; padding: 4px 35px 41px 10px;font-size: 12px; border-radius: 5px;">
                                            Thank for your business and have a great day!</div>
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
                                            <li style="display: block;" class="custm_total_price">0.00</li>
                                        </ul>
                                    </div>

                                </div>
                                <ul style="padding-left: 0px;position: absolute; top: 680px;">
                                    <li
                                        style="display: block;text-align: left; margin-top: 55px; color: #504e4e; font-weight: 800; text-transform: capitalize; font-size: 14px; font-family: sans-serif;">
                                        Make All Payment Through Cheque</li>
                                    <li
                                        style="display: block;font-weight: 500; text-align: left; color: #504e4e; font-size: 13px;    font-family: sans-serif;">
                                        Account Title: <span class="account_title"> {{$company_info->company_name}}</span></li>
                                    <li
                                        style="display: block;font-weight: 500; text-align: left; color: #504e4e; font-size: 13px;    font-family: sans-serif;">
                                        Account No: <span class="account_no">{{$company_info->company_account_no}}</span></li>
                                    <li
                                        style="display: block;font-weight: 500; text-align: left; color: #504e4e; font-size: 13px;    font-family: sans-serif;">
                                        Bank Name: <span class="bank_name"> {{$company_info->company_bank_name}}</span></li>
                                </ul>
                                <p
                                    style="font-weight: 600; text-align: right; color: #504e4e; font-size: 12px; padding-top: 3px; display: inline-block; border-top: 1px solid #dddd; margin-top: 70px;position: absolute; top: 800px;right:5px ">
                                    COMPANY STAMP & SIGN</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
<script data-ajax>
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
    $('#invoices [data-name="invoice_id"]').on("change", function () {
        var _invoiceId = parseFloat($('#invoices [name="invoice_id"]').val().trim());
        if(isNaN(_invoiceId)) return;
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{url('admin/invoice/get_invoice_by_id')}}" + '/' + _invoiceId,
                method: "GET",
                beforeSend: function () {
                    $('.bk_loading').show();
                },
                complete: function () {
                    $('.bk_loading').hide();
                }
            })
            .done(function (resp) {
                if (resp.status==1){
                    //invoice found with same id, just select it
                    var _invoice = resp.invoice;
                    $('#invoices [data-name="client_id"]').val(_invoice.client_id).trigger('change.select2');
                    $('#invoices [name="month"]').fdatepicker('update', new Date(_invoice.month)).trigger('change');
                }
            });
    });
    $('#invoices [data-name="client_id"],#invoices [name="month"]').on("change input", function () {
        typeof receive_payment !=="undefined" && (receive_payment.modal_confirmation_required=false);
        typeof receive_payment !=="undefined" && (receive_payment.reloadable_table=false);
        var client_id = $('#invoices [data-name="client_id"]').val();
        var client_name = $('#invoices [data-name="client_id"] option:selected').text().trim().replace(' ','').split(' ')[0].toLowerCase();
        var invoice_no = $('#invoice_number').attr('data-invoice');
        console.log('client_id', client_id);
        var _month = new Date("01-"+$('#invoices [name="month"]').val()).format('yyyy-mm-dd');
        var is_edit = biketrack.getUrlParameter('edit');

        // if(is_edit!=1){
            var url_data = {
                client_id: client_id,
                month:_month,
                edit:is_edit,
                client_name:client_name,
                box:true
            }
            biketrack.updateURL(url_data);
        // }
        
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{url('admin/invoice/tax/ajax/get_clients_details/')}}" + '/' + client_id + "/" + _month,
                method: "GET",
                beforeSend: function () {
                    $('.bk_loading').show();
                },
                complete: function () {
                    $('.bk_loading').hide();
                }
            })
            .done(function (resp) {
                console.log(resp);
                _invoices.remove_invoice_readonly();
                _invoices.edit=false;
                $('.btn-form-submit').prop('disabled',false);
                if (resp.status == 1) {
                    $("#invoice-table tbody").html('');
                    $('#invoices [data-name="tax_rate"]').find('[data-default="1"]').prop('selected', true);
                    $('#invoices [data-name="discount_values"]').val('');
                    $('#invoice_number').removeAttr('data-invoice').html('');
                    $('a.payments_made').html('');
                    //amount received
                    $('#invoices .all_amount_received').text('AED '+0);
                    $('#invoices [data-name="amount_received"]').val(0);
                    $('a.payments_made').popover('dispose')
                    _invoices.remove_msg();
                    invoiceObj=null;
                    _invoices.invoice=null;
                    $('#invoices [data-name="invoice_id"]').val(resp.next_id);
                    $('#invoices [data-name="billing_address"]').val(resp.billing_address);
                    // $('[data-name="tax_rate"]').val(5);
                    var row_data = resp.items;
                    append_row(row_data);
                    _invoices.invoice=null;
                    var _isedit = biketrack.getUrlParameter('edit');
                    $('#invoices .btn-save-invoice-drafted,#invoices .btn-save-invoice-generate').show();
                    $('noscript[data-receive-script]').remove();
                    $('#invoices .btn-edit-invoice, .receive_payment_btn, #invoices .edit_page_content').hide();

                    if(resp.is_edit){//&& _isedit==1
                        _invoices.invoice=resp.invoice;
                        $('#invoices .btn-save-invoice-drafted,#invoices .btn-save-invoice-generate').hide();
                        _invoices.make_invoice_readonly();
                        _invoices.is_allow=false;
                        _invoices.edit=true;
                        $('#invoices .btn-edit-invoice').text('Edit Invoice');
                        $('#invoices .btn-edit-invoice, .receive_payment_btn, #invoices .edit_page_content').show();
                        if(_invoices.invoice.payment_status=="paid"){
                            $('#invoices .receive_payment_btn').hide();
                        }
                        $('#invoices .receive_payment_btn').append('<noscript>'+JSON.stringify(resp.invoice)+'</noscript>');

                        
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
                    console.info("====================================================4");
                    subtotal();
                    
                } else {
                    _invoices.make_invoice_readonly();
                    $('.btn-form-submit').prop('disabled',true);
                    _invoices.show_msg(resp.message);
                }

            });
    });

    $(document).on("changeDate", '[data-name="invoice_date"], [data-name="due_date"]', function () {
        // typeof receive_payment !=="undefined" && (receive_payment.modal_confirmation_required=true);
    });

    $(document).on("change input", '[data-name="rate"], [data-name="amount"], [data-name="tax_rate"], [data-name="discount_values"], [data-name="tax"], [data-name="discount"], [data-name="qty"], [data-name="deductable"]', function () {
        // if($(this).attr('data-name')=="deductable"){
        //     var _taxBox = $(this).parents('tr').find('[data-name="tax"]');
            
        //     _taxBox.prop('disabled', false)
        //     if($(this).is(':checked')){
        //         _taxBox.prop('disabled', true);
        //     }
            
        // }
        if($(this).attr('data-name')=="discount"){
            var _taxBox = $(this).parents('tr').find('[data-name="tax"]');
            
            _taxBox.prop('disabled', false)
            if($(this).is(':checked')){
                _taxBox.prop('disabled', true);
            }
            
        }
        console.info("====================================================1");
        subtotal();
        typeof receive_payment !=="undefined" && (receive_payment.modal_confirmation_required=true);
    });

    $(document).on("keydown", 'textarea.auto-expandable', autosize);

    //generate invoice
    $('#invoices .btn-save-invoice-generate').on('click', function (e) {
        e.preventDefault();
        var _form = $(this).parents('form');
        if(validate_invoice(_form)){
            save_invoice(_form, "generated");
        }
    });
    //save as draft
    $('#invoices .btn-save-invoice-drafted').on('click', function (e) {
        e.preventDefault();
        var _form = $(this).parents('form');
        if(validate_invoice(_form)){
            save_invoice(_form, "drafted");
        }
    });

     //enable edit
     $('#invoices .btn-edit-invoice').on('click', function (e) {
        e.preventDefault();
        var _form = $(this).parents('form');
        var is_allowed=_invoices.is_allow;
        _invoices.edit=false;
        if(is_allowed){
            if(validate_invoice(_form)){
                save_invoice(_form, "generated");
            }
        }
        else{
            _invoices.remove_invoice_readonly();
            _invoices.is_allow=true;
            $(this).text('Save Invoice');
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
        if(_invoices.invoice&&_invoices.invoice.invoice_payment&&_invoices.invoice.invoice_payment.length>0){
            //some payment received
            Swal.fire({
                title: 'Are you sure?',
                position: 'center',
                type: 'warning',
                text: "The transaction you are editing is linked to others. Are you sure you want to modify it?",
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                showCancelButton: true,
                reverseButtons: true
            }).then((result) => {
                
                if (result.value) {
                    post_invoice(_form,invoice_status,callback);
                }
                console.log(result)
            })
        }
        else{
            post_invoice(_form,invoice_status,callback);
        }
        
    }
    var post_invoice=function(_form=null,invoice_status, callback=null){
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
                typeof receive_payment !=="undefined" && (receive_payment.modal_confirmation_required=false);
                typeof receive_payment !=="undefined" && (receive_payment.reloadable_table=true);
                if(data && data.invoice){
                    invoiceObj=data.invoice;
                    _invoices.invoice=data.invoice;
                    $('#invoice_number').attr('data-invoice',data.invoice.id).text('#'+(data.invoice.id));
                } 
                if(callback && typeof callback=="function"){
                    callback(data.invoice);
                }
                //reload invoice 
                $('#invoices [data-name="client_id"]').trigger('change');
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
        var invoice = _invoices.invoice;
        console.log(invoice);

        let generate_invoice_HTML=function(invoice){
            var company__details=$('[data-name="company_details"]').val().replace(/(\r\n|\n)/g, "<br/>");
            $('.company__details').html(company__details);
            $('.invoice_slip__client_address').text(invoice.client.address);
            $('.invoice_slip__client_email').text(invoice.client.email);
            $('.invoice_slip__client_no').text(invoice.client.phone);
            $('.invoice_slip__client_name').text(invoice.client.name);
            $('.invoice_slip__client_trn').text(invoice.client.trn_no);
            $('.invoice_slip__total_amount').text('AED '+(invoice.invoice_total).toRound(0));
            $('.invoice_date').text(invoice.invoice_date)
            $('.invc_no').text(invoice.invoice_id)
            $('.invoice_month').text(new Date(invoice.month).format('mmmm yyyy'));
            $('.invoice_slip__invoice_items').html('');
            $('.custm_subtotal').text('AED '+(invoice.invoice_subtotal).toRound(2))
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
            $('.custm_total_price').text('AED '+(invoice.invoice_total).toRound(2));
            $('.custm_message_on_invoice').html(invoice.message_on_invoice);
            $('.invoice_id').text(invoice.invoice_id);
            $('.invoice_date').text(invoice.invoice_date)
            $('.invoice_due').text(invoice.invoice_due)
            var _addrow=true;
            var _total_inclusive_of_vat = 0;
            var _invoiceItems = invoice.invoice_item||invoice.invoice_items;
            _invoiceItems.forEach(function(item, i){
                    var _txt_amount=0;
                    item.taxable_amount=item.taxable_amount!=null?item.taxable_amount.toRound(2):0;
                    _txt_amount =item.taxable_amount ;
                    
                    var _inclusive_of_vat = (item.subtotal).toRound(2);
                    if(item.deductable == 0){
                        _total_inclusive_of_vat += parseFloat(_inclusive_of_vat);
                        // console.log(_total_inclusive_of_vat);
                    }
                    var _itemRate=item.item_rate,
                        _itemQty=item.item_qty,
                        _itemSubtotal=item.item_amount;
                    if(item.deductable == 1){ 
                        _itemRate ='';
                        _itemQty ='';
                        if(item.taxable_amount==0){ //no taxable and deductable
                            _itemSubtotal ='';
                            _txt_amount='';
                        }
                        
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
        
        if(invoice && !_invoices.edit){
            generate_invoice_HTML(invoice);
        }
        else{
            var _form = $('#invoices');
            var client_id = $('#invoices [name="client_id"]').val();
            var client_data = JSON.parse($('#invoices  [name="client_id"] :selected').attr('data-info'));
            var _invoice_id = $('#invoices  [name="invoice_id"]').val();
            var _invoice_date = $('#invoices  [name="invoice_date"]').val();
            var invoice_due = $('#invoices  [name="due_date"]').val();
            var tax_method = $('#invoices  [name="tax_method_id"]').val();
            var tax_val = $('#invoices  [name="tax_value"]').val();
            var rec_amount = $('#invoices  [name="amount_received"]').val();
            var invoice_message = $('#invoices  [name="message_on_invoice"]').val();
            var billing_address = $('#invoices  [name="billing_address"]').val();
            var invoice_status = $('#invoices  [name="invoice_status"]').val();
            var dis_type = $('#invoices  [name="discount"]').val();
            var discount_values = $('#invoices  [name="discount_values"]').val();
            var _dis_amount = $('#invoices  h4.discount_amount').text().replace('AED','').trim().toRound(2);
            _dis_amount=_dis_amount==0?null:_dis_amount;
            var tax_subtotal = $('#invoices  h6.taxable_subtotal').text().replace('AED','').trim();
            var _month  = new Date($('#invoices [name="month"]').val()).format('yyyy-mm-dd');
            var _subtotal = $('#invoices  span.balance_due').text().replace('AED','').trim();
            var _s_total = $('#invoices  h4.subtotal_value').text().replace('AED','').trim();
            var _arr=[];
            $('#invoice-table tbody tr').each(function(){
                var _itm_des = $(this).find('[data-name="description"]').text();
                var _rate = $(this).find('[data-name=rate]').val();
                var _qty = $(this).find('[data-name=qty]').val();
                var _totl = $(this).find('[data-name=item_subtotal]').val();
                var tax_amount = $(this).find('[data-name=tax_amount]').val();
                var deductable = $(this).find('[data-name=deductable]').prop('checked');
                var item_subtotal = $(this).find('[data-name=amount]').val();
                _arr.push({
                    invoice_id:"0",
                    item_desc:_itm_des,
                    item_rate:_rate,
                    item_qty:_qty,
                    item_amount:_totl,
                    deductable:deductable,
                    tax_method_id:"0",
                    taxable_amount:tax_amount,
                    subtotal:item_subtotal,
                    active_status:"A",
                    created_at:null,
                    updated_at:null

                });
            });
            var runtime_invoice = {
                id:0,
                invoice_id:_invoice_id,
                client_id:client_id,
                invoice_total:_subtotal,
                invoice_subtotal:_s_total,
                month: _month,
                invoice_date: _invoice_date,
                invoice_due: invoice_due,
                payment_status: "pending",
                tax_method_id: tax_method,
                taxable_amount: tax_val,
                taxable_subtotal: tax_subtotal,
                amount_paid: rec_amount,
                due_balance: _subtotal,
                received_date: null,
                invoice_status: invoice_status,
                discount_type: dis_type,
                discount_value: discount_values,
                discount_amount: _dis_amount,
                attachment: null,
                message_on_invoice: invoice_message,
                billing_address: billing_address,
                status: "open",
                active_status: "A",
                created_at: null,
                updated_at: null,
                client:client_data,
                invoice_item:_arr
            }
            console.log(runtime_invoice);
            
            generate_invoice_HTML(runtime_invoice);
            // if(validate_invoice(_form)){
            //     save_invoice(_form, "drafted", function(invoice){
            //         generate_invoice_HTML(runtime_invoice);
            //     });
            // }
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
    is_allow:false,
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
        var is_edit = biketrack.getUrlParameter('edit');
        if(is_edit!=1){
            var url_data = {
                invoice_id:invoice.id,
                client_id: invoice.client_id,
                month: invoice.month
            }
            biketrack.updateURL(url_data);
        }
        //changing invoice id
        $('#invoice_number').attr('data-invoice',invoice.id).html('#'+(invoice.id+' <span class="invoice__header-generated_by-text">Generated by '+invoice.generated_by.name+' on '+new Date(invoice.invoice_date).format('dd/mmm/yyyy')+'</span>'));
        $('#invoices [data-name="invoice_date"]').attr('data-month', new Date(invoice.invoice_date).format('mmm dd, yyyy'));
        $('#invoices [data-name="due_date"]').attr('data-month', new Date(invoice.invoice_due).format('mmm dd, yyyy'));
        $('#invoices [name="month"]').attr('data-month', new Date(invoice.month).format('mmmm yyyy'));

        if(invoice.tax_method_id!=null){
            $('#invoices [data-name="tax_rate"]').val(invoice.tax_method_id);
        }
        $('#invoices [data-name="discount_values"]').val('');
        if(invoice.discount_type!=null){
            $('#invoices [data-name="discount"]').val(invoice.discount_type);
            $('#invoices [data-name="discount_values"]').val(invoice.discount_value);
        }
        $('#invoices [data-name="message_on_invoice"]').text(invoice.message_on_invoice);

        //payments_made
        
        if(_invoices.invoice.invoice_payment&&_invoices.invoice.invoice_payment.length>0){
            var payments = _invoices.invoice.invoice_payment;
            var _payment_len = payments.length;
            var _totalPayments = 0;
            var inner_html='<div class="__pm__content"><span class="__pm__heading">Date</span><span class="__pm__heading">Amount</span>';
            payments.forEach(function(payment, i){
                _totalPayments+=parseFloat(payment.payment);
                inner_html+='<a href="">'+new Date(payment.payment_date).format('dd/mmm/yyyy')+'</a><span>AED '+payment.payment+'</span>';
            });
            _totalPayments=_totalPayments.toFixed(2);
            var _html = _payment_len+' Payment made (AED '+_totalPayments+')';
            inner_html+='</div>';
            $('#invoices a.payments_made').html(_html);
            $('#invoices a.payments_made').popover({
                content:inner_html,
                html:true,
                placement:'left',
                trigger: "focus"
            });

            //amount received
            $('#invoices .all_amount_received').text('AED '+_totalPayments);
            $('#invoices [data-name="amount_received"]').val(_totalPayments);
        }
        

        biketrack.refresh_global();
        console.info("====================================================2");
        subtotal();
        
        // if(_invoices.invoice.payment_status=="paid"){
        //     $('#invoices .balance_due').text('PAID');
        // }
    },
    fetch_url_data:function(){
        var _clientId=biketrack.getUrlParameter('client_id');
        var _month=biketrack.getUrlParameter('month');
        if(_clientId!="" && _month!=""){
            $('#invoices [name="month"]').attr('data-month', new Date(_month).format('mmm dd, yyyy'));
            biketrack.refresh_global();
            $('#invoices [name="client_id"]').val(_clientId).trigger('change');

        }
    },
    make_invoice_readonly:function(){
        $('#invoices input:not([type="hidden"],[data-non-readonly]), #invoices select:not([data-non-readonly]), #invoices textarea:not([data-non-readonly]), #invoices .btn--addnewrow').prop('disabled', true);
        
    },
    remove_invoice_readonly:function(){
        $('#invoices input:not([type="hidden"],[data-non-readonly]), #invoices select:not([data-non-readonly]), #invoices textarea:not([data-non-readonly]), #invoices .btn--addnewrow').prop('disabled', false);
    }
};
function subtotal() {
    // return;
    
    total_amount = 0;
    taxable_amount = 0;
    var non_tax_amount = 0;
    var res_of_tax=0;
    var tax_rate = parseFloat($('[data-name="tax_rate"]').val()) || 0;
    var tax_type=$('[data-name="tax_rate"] :selected').attr('data-type')||"";
    var tax_value=parseFloat($('[data-name="tax_rate"] :selected').attr('data-value'))||0;
    if(_invoices.edit && _invoices.edit==true){
        $("#invoice-table tbody tr").each(function (item, index) {
            // var is_payable = $(this).find('[data-name="payable"]').is(":checked");
            var is_deductable = $(this).find('[data-name="deductable"]').is(":checked");

            var rate = parseFloat($(this).find('[data-name="rate"]').val()) || 0;
            var qty = parseFloat($(this).find('[data-name="qty"]').val()) || 0;

            var amount = parseFloat($(this).find('[data-name="amount"]').val())||0;
            if(is_deductable){
                amount= amount*-1;
            }
            var item_subtotal = parseFloat($(this).find('[data-name="item_subtotal"]').val())||0;
            $(this).find('[data-name="item_subtotal"]').val((item_subtotal).toFixed(2));
            total_amount += amount;
            non_tax_amount += amount;
            if ($(this).find('[data-name="tax"]').is(":checked")) {
                taxable_amount += amount;
                if (tax_rate > 0) {
                    var tax_amount=parseFloat($(this).find('[data-name="tax_amount"]').val())||0; 
                    res_of_tax+=tax_amount;
                }
            }
            var amount = parseFloat($(this).find('[data-name="amount"]').val())||0;
            $(this).find('[data-name="amount"]').val((amount).toFixed(2));
        });

        res_of_tax=_invoices.invoice.taxable_amount;

        if (tax_rate > 0) {
            $('[data-name="tax_value"]').val(res_of_tax);
        }
        var discount = $('[data-name="discount"]').val();
        if (discount == 'percent') {
            var discount_value_percent = parseFloat($('[data-name="discount_values"]').val()) || 0;
            res_of_discount = (discount_value_percent * non_tax_amount) / 100;
            $('.discount_amount').text('AED -' + (res_of_discount).toFixed(2));
            $('[data-name="discount_amount"]').val((res_of_discount).toFixed(2));
            //total_amount -= res_of_discount;
        }
        if (discount == 'value') {
            var discount_value_value = parseFloat($('[data-name="discount_values"]').val()) || 0;
            var res_of_discount = discount_value_value;
            $('.discount_amount').text('AED -' + (res_of_discount).toFixed(2));
            //total_amount -= res_of_discount;
        }
        var amount_received=parseFloat($('#invoices [data-name="amount_received"]').val())||0;

        non_tax_amount=_invoices.invoice.invoice_subtotal;
        taxable_amount=_invoices.invoice.taxable_subtotal;
        total_amount=_invoices.invoice.invoice_total;

        $("#invoices .subtotal_value").text("AED " + non_tax_amount);
        $("#invoices .taxable_subtotal").text("AED " + taxable_amount);
        $('#invoices .all_total_amount').text("AED " + total_amount);

        var balance_due = total_amount-amount_received;
        $('#invoices .balance_due').text("AED " + (balance_due).toFixed(2));

        $("#invoices [data-name='invoice_subtotal']").val(non_tax_amount);
        $("#invoices [data-name='taxable_subtotal']").val(taxable_amount);
        $("#invoices [data-name='invoice_total']").val(total_amount);
    }
    else{
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
            $(this).find('[data-name="item_subtotal"]').val((amount).toFixed(2));

            // if(is_deductable){
            //     total_amount -= amount;
            //     non_tax_amount -= amount;
            // }
            // else{
                total_amount += amount;
                non_tax_amount += amount;
            //}
            // debugger;
            if ($(this).find('[data-name="tax"]').is(":checked")) {
                
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
                    res_of_tax+=tax_amount;
                    // if(is_deductable){
                    //     tax_amount = Math.abs(tax_amount);
                    // }
                    $(this).find('[data-name="tax_amount"]').val((tax_amount).toFixed(2));
                }
            }
            $(this).find('[data-name="amount"]').val((amount).toFixed(2));
        });



        if (tax_rate > 0) {
            res_of_tax=parseFloat((res_of_tax).toFixed(2));
            var vat_val = parseFloat(tax_rate);
            if (taxable_amount > 0) {
                $('[data-name="tax_value"]').val(res_of_tax);
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
        var amount_received=parseFloat($('#invoices [data-name="amount_received"]').val())||0;

        $("#invoices .subtotal_value").text("AED " + (non_tax_amount).toFixed(2));
        
        $("#invoices .taxable_subtotal").text("AED " + (taxable_amount).toFixed(2));
        $('#invoices .all_total_amount').text("AED " + (total_amount).toFixed(2));

        var balance_due = total_amount-amount_received;
        $('#invoices .balance_due').text("AED " + (balance_due).toFixed(2));

        $("#invoices [data-name='invoice_subtotal']").val((non_tax_amount).toFixed(2));
        $("#invoices [data-name='taxable_subtotal']").val((taxable_amount).toFixed(2));
        $("#invoices [data-name='invoice_total']").val((total_amount).toFixed(2));
        // 
    }
}



function append_row($row_data = null) {
    var markup = '';
    var total_rows = parseFloat($("#invoice-table tbody tr").length);
    if ($row_data != null) {
        console.log($row_data);
        $row_data.forEach(function (item, i) {
            console.log(item+"sadhkasdgjdssdfsdafhdsaf")
            var _isTaxable = item.is_taxable ? 'checked' : '';
            // var _isPaybale = item.is_payable?'checked':'';
            var _isDeductable = item.is_deductable ? 'checked' : '';
            var _isTaxDisabled = item.is_deductable ? 'disabled' : '';
            var tax = '';
            //if (!_isDeductable) {
                tax = '<div class="kt-checkbox-list"><label class="kt-checkbox"> <input data-name="tax" name="invoice_items['+i+'][tax]" type="checkbox" ' + _isTaxable+ '><span></span> </label></div>';
           // }
            var _subtotal=typeof item.subtotal =="undefined"?0:item.subtotal;
            var _taxable_amount=typeof item.taxable_amount =="undefined"?0:item.taxable_amount;
            var action = '<button type="button" onclick="delete_row(this);" class="delete-row btn btn-danger"><i class="fa fa-trash-alt"></i></button>';
            markup += '' +
                '   <tr>  ' +
                '       <td class="invoice__table-row_cell-sr"> <span class="flaticon2-trash invoice__remove" onclick="delete_row(this);"></span>' + (i + 1) + ' </td>  ' +
                '       <td> <textarea type="text" class="form-control auto-expandable" data-name="description" name="invoice_items['+i+'][description]" rows="1">' + item.desc + '</textarea> </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" data-name="rate" name="invoice_items['+i+'][rate]" min="0" value="' + item.rate + '"> </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" data-name="qty" name="invoice_items['+i+'][qty]" min="1" value="' + item.qty + '"> </td>  ' +
                '       <td> ' +
                '           <input type="hidden" data-name="item_subtotal" name="invoice_items['+i+'][item_subtotal]" value="'+item.amount+'">'+
                '           <div class="input-group">   ' +
                '               <input type="text" class="form-control" placeholder="Amount" data-name="amount" name="invoice_items['+i+'][amount]" aria-describedby="basic-addon2" value="'+_subtotal+'">   ' +
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
                '       <td> <input data-input-type="float" class="form-control" data-name="tax_amount" name="invoice_items['+i+'][tax_amount]" min="0" value="'+_taxable_amount+'"> ' +
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
        '       <td> <textarea type="text" class="form-control auto-expandable" data-name="description" name="invoice_items['+total_rows+'][description]" rows="1"></textarea> </td>  ' +
        '       <td> <input data-input-type="float" class="form-control" data-name="rate" name="invoice_items['+total_rows+'][rate]" min="0" value="0"> </td>  ' +
        '       <td> <input data-input-type="float" class="form-control" data-name="qty" name="invoice_items['+total_rows+'][qty]" min="1" value="1"> </td>  ' +
        '       <td> ' +
        '           <div class="input-group">   ' +
        '           <input type="hidden" data-name="item_subtotal" name="invoice_items['+total_rows+'][item_subtotal]" value="">'+
        '               <input type="text" class="form-control" placeholder="Amount" data-name="amount" name="invoice_items['+total_rows+'][amount]"  aria-describedby="basic-addon2" value="0">   ' +
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
    console.info("====================================================3");
    subtotal();
    typeof receive_payment !=="undefined" && (receive_payment.modal_confirmation_required=true);
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

