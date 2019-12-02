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
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Create Invoices 
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
                                <select class="form-control bk-select2 kt-select2" id="kt_select2_3" data-name="client_id" name="client_id" >
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
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control @if($errors->has('due_date')) invalid-field @endif" data-name="due_date" name="due_date" placeholder="Enter Month" >
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
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="message_on_invoice" class="d-block text-left">Message on invoice</label>
                                        <textarea class="form-control auto-expandable" data-name="message_on_invoice" name="message_on_invoice" rows="1" placeholder="This will show up on the invoice.">Thank you for your business and have a great day!</textarea>
                                    </div>
                                    <div class="col-md-4 text-right offset-md-2">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <select class="form-control" aria-placeholder="Select a Tax rate" data-name="tax_rate" name="tax_rate" >
                                                    <option value=""> Select a Tax Rate</option> 
                                                    <option value="5">VAT(5%)</option>  
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
                                <button type="button" class="btn btn-outline-hover-success">Print & Preview</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">Save & Send</button>
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
<script>
$(document).ready(function () {
    append_row();
    $('#invoices [data-name="client_id"],#invoices [name="month"]').on("change input", function () {
        var client_id = $('#invoices [data-name="client_id"]').val();
        console.log('client_id', client_id);
        var _month = new Date("01-"+$('#invoices [name="month"]').val()+"-"+new Date(Date.now()).format('yyyy')).format('yyyy-mm-dd');
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
                    $('#invoices [data-name="billing_address"]').val(resp.billing_address);
                    $('[data-name="tax_rate"]').val(5);
                    if (resp.payment_method == "trip_based") {
                        var row_data = [{
                                desc: 'Total Login Hours Payable Amount',
                                amount: resp.total_hours_payable,
                                rate: resp.per_hour_amount,
                                qty: resp.total_hours,
                                is_taxable: true,
                                is_payable: true,
                                is_deductable: false
                            },
                            {
                                desc: 'Total Orders Completed Payable Amount',
                                amount: resp.total_trips_payable,
                                rate: resp.per_trip_amount,
                                qty: resp.total_trips,
                                is_taxable: true,
                                is_payable: true,
                                is_deductable: false
                            },
                            {
                                desc: 'Incentives/Adhoc',
                                amount: resp.adhoc,
                                rate: resp.adhoc,
                                qty: 1,
                                is_taxable: false,
                                is_payable: true,
                                is_deductable: false
                            },
                            {
                                desc: 'Tips Earned',
                                amount: resp.tips,
                                rate: resp.tips,
                                qty: 1,
                                is_taxable: false,
                                is_payable: true,
                                is_deductable: false
                            },
                            {
                                desc: 'NCW',
                                amount: resp.ncw,
                                rate: resp.ncw,
                                qty: 1,
                                is_taxable: false,
                                is_payable: true,
                                is_deductable: false
                            },
                            {
                                desc: 'Penalties',
                                amount: resp.panalties,
                                rate: resp.panalties,
                                qty: 1,
                                is_taxable: false,
                                is_payable: true,
                                is_deductable: true
                            },
                            {
                                desc: 'Deductions for Cash on Delivary orders',
                                amount: resp.dc_deduction,
                                rate: resp.dc_deduction,
                                qty: 1,
                                is_taxable: false,
                                is_payable: true,
                                is_deductable: true
                            },
                            {
                                desc: 'Deductions for McDonald\'s orders',
                                amount: resp.mcdonald_deduction,
                                rate: resp.mcdonald_deduction,
                                qty: 1,
                                is_taxable: false,
                                is_payable: true,
                                is_deductable: true
                            }
                        ];
                        append_row(row_data)
                    } else if (resp.payment_method == "fixed_based") {
                        var row_data = [{
                            desc: 'Fixed Amount',
                            amount: resp.total_payable,
                            rate: resp.fixed_amount,
                            qty: resp.riders_count,
                            is_taxable: true,
                            is_payable: true,
                            is_deductable: false
                        }];
                        append_row(row_data)
                    } else if (resp.payment_method == "commission_based") {}
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
                    
                } else {
                    alert("Error: " + resp.message);
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
    });


});

var total_amount = 0;
var taxable_amount = 0;

function subtotal() {
    total_amount = 0;
    taxable_amount = 0;
    var non_tax_amount = 0;
    var res_of_tax;
    var vat = parseFloat($('[data-name="tax_rate"]').val()) || 0;
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
            if (vat > 0) {
                var tax_amount = (amount * vat) / 100;
                amount += tax_amount;
                $(this).find('[data-name="tax_amount"]').val((tax_amount).toFixed(2));
            }
        }

        $(this).find('[data-name="amount"]').val((amount).toFixed(2));
    });



    if (vat > 0) {
        var vat_val = parseFloat(vat);
        if (taxable_amount > 0) {
            res_of_tax = (taxable_amount * vat_val) / 100;
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
        subtotal();
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

