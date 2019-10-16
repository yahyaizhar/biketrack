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
                                <label>Customer:</label>
                                <select class="form-control bk-select2 kt-select2" id="kt_select2_3" name="client_id" >
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->name }}
                                    </option>     
                                @endforeach 
                                </select> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Billing Adress:</label>
                                <textarea type="text" cols="20" rows="5" class="form-control" name="billing_address" placeholder="Enter Your Adress"></textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Invoice Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control @if($errors->has('invoice_date')) invalid-field @endif" name="invoice_date" placeholder="Enter Month" >
                            </div>
                            <div class="form-group col-md-4">
                                <label>Due Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control @if($errors->has('due_date')) invalid-field @endif" name="due_date" placeholder="Enter Month" >
                            </div>
                        </div>
                        <table class="table table-striped- table-hover table-checkable table-condensed" id="invoice-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th></th>
                                    <th>Amount</th>
                                    <th>TAX</th>
                                    <th>Action</th>                  
                                </tr>
                                    <tbody></tbody>
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
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                
                                <div class="row mt-3">
                                    <div class="col-md-5 text-right offset-md-7">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <select class="form-control" aria-placeholder="Select a Tax rate" name="tax_rate" >
                                                    <option value=""> Select a Tax Rate</option> 
                                                    <option value="5">VAT(5%)</option>  
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="tax_value">
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
                                                        <select class="form-control" aria-placeholder="Select a Tax rate" name="discount" >
                                                            <option value="">Select a discount rate.</option>
                                                            <option value="percent">Discount Percent</option>
                                                            <option value="value">Discount Value</option>  
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="discount_values">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <h4 class="discount_amount" style="padding:10px 0px">AED 0.00</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-5 text-right offset-md-7">
                                        <div class="row">
                                            <div class="col-md-7">
                                               <h4>Total</h4>
                                            </div>
                                            <div class="col-md-5">
                                                <h4 class="all_total_amount">AED 0.00</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row mt-3">
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
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Save & Send</button>
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
$(document).ready(function(){
    append_row();
    $('[name="tax_value"]').prop("disabled", true);
  $('#invoices [name="client_id"]').on("change input",function(){
      var client_id=$('#invoices [name="client_id"]').val();
      $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/invoice/tax/ajax/get_clients_details/')}}"+'/'+client_id,
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                $('#invoices [name="billing_address"]').val(data.billing_address);
            });
  });

$(document).on("change input",'[name="amount"]',function(){ 
    subtotal();
});
$(document).on("change",'[name="tax"]',function(){
    subtotal();
});
$(document).on("change input",'[name="tax_rate"]',function(){
subtotal();
});   
$(document).on("change",'[name="discount"]',function(){
    subtotal();
});  
$('[name="discount_values"]').on("change input",function(){
subtotal();
});   
});

var total_amount=0;
var taxable_amount=0;
function subtotal(){
     total_amount=0;
     taxable_amount=0;
     var non_tax_amount=0;
     var res_of_tax;
    $("#invoice-table tbody tr").each(function(item,index){
        var amt=$(this).find('[name="amount"]').val();
        var amount=parseFloat(amt);
        total_amount+=amount;
        non_tax_amount+=amount; 
        if ($(this).find('[name="tax"]').is(":checked")) { 
            taxable_amount+=amount; 
        }
    });
    var vat=$('[name="tax_rate"]').val();
    if (taxable_amount) {
        
    }
    $('[name="tax_value"]').val("");
     if(vat!==""){
         var vat_val=parseFloat(vat);
         if ($("#invoice-table tbody tr").find('[name="tax"]').is(":checked")) {
            res_of_tax=(taxable_amount*vat_val)/100;
            $('[name="tax_value"]').val(res_of_tax);
            total_amount+=res_of_tax;
        }}
       var discount=$('[name="discount"]').val();
       if(discount=='percent'){
          var discount_value_percent=$('[name="discount_values"]').val();
          res_of_discount=(discount_value_percent*non_tax_amount)/100;
          $('.discount_amount').text('AED -'+res_of_discount);
          total_amount-=res_of_discount;
       }
       if(discount=='value'){
        var discount_value_value=$('[name="discount_values"]').val();
        var res_of_discount=discount_value_value;
        $('.discount_amount').text('AED -'+res_of_discount);
        total_amount-=res_of_discount;
       }

    $(".subtotal_value").text("AED "+non_tax_amount);
    $(".taxable_subtotal").text("AED "+taxable_amount);
    $('.all_total_amount').text("AED "+total_amount);
    $('.balance_due').text("AED "+total_amount);
    // 
}
function append_row(){
    var products ='<input type="text" name="products">';
    var description ='<input class="form-control" type="text" name="description">';
    var amount ='<input class="form-control" type="number" name="amount" min="0" value="0" id="amount">';
    var tax='<div class="kt-checkbox-list"><label class="kt-checkbox"> <input name="tax" type="checkbox"><span></span> </label></div>';
    var action='<button type="button" onclick="delete_row(this);" class="delete-row btn btn-danger"><i class="fa fa-trash-alt"></i></button>';
    var markup = "<tr><td colspan='2'>"+ description + "</td><td>" + amount + "</td><td>" + tax + "</td><td>" + action + "</td></tr>";
    $("table tbody").append(markup);
        }
function delete_row(ctl){
   $(ctl).parents("tr").remove();
   subtotal(); 
}
</script>
@endsection

