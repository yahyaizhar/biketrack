@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="" style="padding-top:15px;">
                        <h3 class="kt-portlet__head-title">
                          Give Mobile to: <a href="{{route('admin.rider.profile', $rider->id)}}">{{$rider->name}}</a>
                        </h3>
                        @if ($mobile_history_count<=0)
                        @else
                            <span style="color: #5867e4;display: block;width: 100%;font-weight: bold;">This Rider has already Purchase Mobile from Kingriders.</span>
                        @endif
                    </div>
                </div>
            @include('client.includes.message')
            <form class="kt-form" action="{{ route('Mobile.mobile_is_assigned_to_rider', $rider->id) }}" method="POST" id="assign_mobile" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="kt-portlet__body">
                    <div class="form-group">
                        <label>Select Mobile:</label>
                        <select required class="form-control kt-select2 bk-select2" name="mobile_id" >
                            @foreach ($mobiles as $mobile)
                                <option value="{{ $mobile->id }}"
                                >{{ $mobile->brand }}&nbsp{{ $mobile->model }}</option>
                            @endforeach
                        </select>      
                    </div>
                    <div class="form-group">
                        <label>Sale Price:</label>
                        <input type="number" class="form-control @if($errors->has('sale_price')) invalid-field @endif" name="sale_price" placeholder="Enter sale price ">
                    </div>
                    <div class="form-group mt-4">
                        <label>Mobile Given Date:</label>
                        <input type="text" data-month="{{Carbon\Carbon::now()->format('Y-m-d')}}"  readonly class="month_picker form-control" name="mobile_assign_date" placeholder="Enter Expiry Date">
                    </div>
                    <div class="form-group">
                        <label>Select Payment Type:</label>
                        <select class="form-control kt-select2 bk-select2" name="payment_type" >
                            <option value="cash">Cash</option> 
                            <option value="installment">Installments</option>    
                        </select>      
                    </div>
                    <div class="row" id="installment_process">
                        <div class="form-group col-md-4">
                            <label>Installment Amount:</label>
                            <input type="number" id="installment_amount" class="form-control"  placeholder="Enter Installment Amount">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Installment Starting Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}"  readonly class="month_picker_only form-control" name="installment_starting_date">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Installment Ending Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}"  readonly class="month_picker_only form-control" name="installment_ending_date">
                        </div>
                    </div>
                    <div class="form-group" id="cash_process">
                        <label>Cash Amount:</label>
                        <input readonly type="number"  class="form-control" id="cash_paid_amount"  placeholder="Enter Cash Amount">
                    </div>
                </div>
                
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-primary">Assign Mobile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script>
    $(document).ready(function(){
        $("#installment_process").hide();
        $("#cash_process").hide();
        $('#assign_mobile [name="mobile_id"]').on("change",function(){
            var mobile_id=$(this).val();
            var _month=new Date($('#assign_mobile [name="mobile_assign_date"]').val()).format('yyyy-mm-dd');
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    url:"{{url('/admin/mobile/ajax/data/')}}"+"/"+mobile_id+"/"+_month,
                    method: "GET"
                })
                .done(function(data) {  
                    console.log(data); 
                    $('#assign_mobile [name="sale_price"]').val(data.sale_price);
                    $("#assign_mobile [name='payment_type']").trigger("change");
                });
        });
        $("#assign_mobile [name='payment_type']").on("change",function(){
            var _this=$(this).val();
            if (_this=="installment") {
                name="installment_amount"
                $("#installment_amount").attr("name","installment_amount");
                $("#cash_paid_amount").attr("name","");
                $("#installment_process").show();
                $("#cash_process").hide();
            }
            if (_this=="cash") {
                $("#installment_process").hide();
                $("#cash_process").show();
                $("#installment_amount").attr("name","");
                $("#cash_paid_amount").attr("name","installment_amount");
                var sale_price=$('#assign_mobile [name="sale_price"]').val();
                $("#cash_paid_amount").val(sale_price);
            }
        });
        $('#assign_mobile [name="sale_price"]').on("change input",function(){
            var _this=$(this).val();
            var payment_type=$("#assign_mobile [name='payment_type']").val();
            if (payment_type=="cash") {
                $("#cash_paid_amount").val(_this);
            }
        });
        $('#assign_mobile [name="mobile_id"]').trigger("change");
        
    });
</script>
@endsection