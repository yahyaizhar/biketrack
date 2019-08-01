@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label" >
                            
                        <h3 class="kt-portlet__head-title">
                            Edit Mobile
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->

            <form class="kt-form" action="{{route('Mobile.update',$mobile_edit->id)}}" method="POST" enctype="multipart/form-data">
                    {{-- {{ method_field('PUT') }} --}}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Select Model:</label>
                          <div>
                            <select class="form-control kt-select2" id="kt_select2_3" name="model" >
                                <option value="{{$mobile_edit->model}}">{{$mobile_edit->model}}</option>
                                <option value="samsung">Samsung</option>
                                <option value="huawei">Huawei</option>
                                <option value="google">Google</option>
                                <option value="sony">Sony</option>
                                <option value="nokia">Nokia</option>
                                <option value="lg">LG</option>
                                <option value="oneplus">OnePlus</option>
                                <option value="doro">Doro</option>
                                <option value="motorola">Motorola</option>
                                <option value="blackberry">BlackBerry</option>
                                <option value="xiaomi">Xiaomi</option>
                                <option value="acer">Acer</option>
                                <option value="oppo">Oppo</option>
                                
                            </select> 
                             </div> 
                            </div>
                           
                        <div class="form-group">
                            <label>IMEI:</label>
                            <input type="text" class="form-control @if($errors->has('imei')) invalid-field @endif" name="imei" placeholder="Enter IMEI number " value="{{$mobile_edit->imei}}">
                            @if ($errors->has('imei'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('imei')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Purchase Price:</label>
                            <input type="number" class="form-control @if($errors->has('purchase_price')) invalid-field @endif" name="purchase_price" placeholder="Enter purchase price " value="{{$mobile_edit->purchase_price}}">
                            @if ($errors->has('purchase_price'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('purchase_price')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Sale Price:</label>
                            <input type="number" class="form-control @if($errors->has('sale_price')) invalid-field @endif" name="sale_price" placeholder="Enter sale price " value="{{$mobile_edit->sale_price}}">
                            @if ($errors->has('sale_price'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('sale_price')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Payment type:</label> 
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="payment_type" @if ($mobile_edit->payment_type==='cash') checked @endif value="cash"> Cash
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                        <input type="radio" name="payment_type" @if ($mobile_edit->payment_type==='installment') checked @endif value="installment"> Installment
                                    <span></span>
                                </label>
                            </div>
                            {{-- <span class="form-text text-muted">Some help text goes here</span> --}}
                        </div>
                        <div class="form-group">
                            <label>Amount received:</label>
                            <input type="text" class="form-control @if($errors->has('amount_received')) invalid-field @endif" name="amount_received" placeholder="Enter received amount " value="{{$mobile_edit->amount_received}}">
                            @if ($errors->has('amount_received'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('amount_received')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Installment Starting Month:</label>
                            <input type="text" class="dp__custom1 form-control @if($errors->has('installment_starting_month')) invalid-field @endif" autocomplete="off" name="installment_starting_month" placeholder="Installment start " value="{{$mobile_edit->installment_starting_month}}">
                            @if ($errors->has('installment_starting_month'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('installment_starting_month')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Installment Ending Month:</label>
                            <input type="text" class="dp__custom2 form-control @if($errors->has('installment_ending_month')) invalid-field @endif" autocomplete="off" name="installment_ending_month" placeholder="Installment ends " value="{{$mobile_edit->installment_ending_month}}">
                            @if ($errors->has('installment_ending_month'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('installment_ending_month')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Per Month Installment Amount:</label>
                            <input type="number" class="form-control @if($errors->has('per_month_installment_amount')) invalid-field @endif" autocomplete="off" name="per_month_installment_amount" placeholder="Per month installments " value="{{$mobile_edit->per_month_installment_amount}}">
                            @if ($errors->has('per_month_installment_amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('per_month_installment_amount')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <span class="kt-margin-l-10">or <a href="{{url('/admin/riders')}}" class="kt-link kt-font-bold">Cancel</a></span> --}}
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>
@endsection
@section('foot')
    <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
    <script>
    $(function(){
        $('.dp__custom1').fdatepicker({ format: 'MM yyyy',startView:3,minView:3,maxView:4});
        $('.dp__custom2').fdatepicker({ format: 'MM yyyy',startView:3,minView:3,maxView:4});
    });
    </script>
@endsection