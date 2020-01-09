@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label" > 
                        <h3 class="kt-portlet__head-title">
                            Create Mobile Installment
                        </h3>
                    </div>
                </div>
                <div class="mobile__wrapper">
                    <form class="kt-form" action="{{route('MobileInstallment.store')}}" method="POST" id="mobile_intallment" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>Mobile Installment Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month" value="">
                                <span class="form-text text-muted">Please enter Month</span>
                            </div>
                            <div class="form-group">
                                <label>Installment Paid Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Given Date" value="">
                                <span class="form-text text-muted">Please enter Given Date</span>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Select Mobile:</label>
                                    <div>
                                        <select id="mobile_id" class="form-control kt-select2 bk-select2" name="mobile_id" >
                                            @foreach ($mobiles as $mobile)
                                                <option value='{{$mobile->id}}'>{{$mobile->model}}-{{$mobile->brand}} </option>
                                            @endforeach
                                        </select> 
                                        <span class="form-text text-muted">Like <strong>Samsung</strong>.</span>
                                    </div> 
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Rider:</label>
                                    <div>
                                        <select id="rider_id" class="form-control kt-select2 bk-select2" name="rider_id" >
                                            @foreach ($riders as $rider)
                                                <option value='{{$rider->id}}'>{{$rider->name}}</option>
                                            @endforeach
                                        </select> 
                                    </div> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Total Amount To be Paid:</label>
                                <input readonly type="number" class="form-control @if($errors->has('sale_price')) invalid-field @endif" name="sale_price" placeholder="Enter sale price ">
                            </div>
                            <div class="form-group">
                                <label>Amount Received:</label>
                                <input readonly id="amount_received" type="text" class="form-control " name="amount_received" placeholder="Enter received amount " >
                            </div>
                            <div class="form-group">
                                <label>Remaining Amount:</label>
                                <input readonly  id="remaining_amount" type="text" class=" form-control" autocomplete="off" name="remaining_amount">
                            </div>
                            <div class="form-group">
                                <label>Installment Amount:</label>
                                <input step="0.01" type="number" class="form-control " autocomplete="off" name="per_month_installment_amount" placeholder="Per month installments " >
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button type="submit" class="btn btn-primary">Pay Installment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot')
    <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
    <script data-ajax2 data-ajax>
    $(function(){
        $('.dp__custom').fdatepicker({ format: 'MM yyyy',startView:3,minView:3,maxView:4});
        
        $('#mobile_intallment [name="mobile_id"]').on("change",function(){
            var mobile_id=$(this).val();
            var _month=new Date($('#mobile_intallment [name="month_year"]').val()).format('yyyy-mm-dd');
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    url:"{{url('/admin/mobile/ajax/data/')}}"+"/"+mobile_id+"/"+_month,
                    method: "GET"
                })
                .done(function(data) {  
                    console.log(data); 
                    console.log(data.payment_type);
                    if (data.payment_type==null) {
                        $("#mobile_intallment [type='submit']").prop("disabled",true).html("Mobile is not assigned to any rider");
                    }
                    if (data.payment_type=="cash" ) {
                        $("#mobile_intallment [type='submit']").prop("disabled",true).html("Cash Paid");
                    }
                    if (data.payment_type=="installment") {
                        $("#mobile_intallment [type='submit']").prop("disabled",false).html("Pay Installment");
                        if (parseFloat(data.remaining_amount)==0) {
                            $("#mobile_intallment [type='submit']").prop("disabled",true).html("All Installments are paid");
                        }
                    }
                    $('#mobile_intallment [name="sale_price"]').val(data.sale_price).attr("data-sale",data.sale_price);
                    $('#mobile_intallment [name="amount_received"]').val(data.amount_received).attr("data-received",data.amount_received);
                    $('#mobile_intallment [name="remaining_amount"]').val(data.remaining_amount).attr("data-remaining",data.remaining_amount);
                    $('#mobile_intallment [name="per_month_installment_amount"]').attr("min",1);
                    $('#mobile_intallment [name="per_month_installment_amount"]').attr("max",data.remaining_amount);
                    

                });
        });
        $('#mobile_intallment [name="mobile_id"]').trigger("change");
        $('#mobile_intallment [name="per_month_installment_amount"]').on("change input",function(){
            var inst_amt=parseFloat($(this).val())||0;
            var sale_amt= parseFloat($('#mobile_intallment [name="sale_price"]').attr("data-sale"));
            var recieve_amt=parseFloat($('#mobile_intallment [name="amount_received"]').attr("data-received"));
            var remainning_amt=parseFloat($('#mobile_intallment [name="remaining_amount"]').attr("data-remaining"));
           
            console.log(recieve_amt+"-"+remainning_amt);

            var received=recieve_amt+inst_amt;
            var remain=remainning_amt-inst_amt;
            $('#mobile_intallment [name="amount_received"]').val(received);
            $('#mobile_intallment [name="remaining_amount"]').val(remain);
            
            
        });
    });
    </script>
@endsection