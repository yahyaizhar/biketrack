@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Create Sim Transaction
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('SimTransaction.store_simTransaction') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                            <div class="form-group">
                                    <label>Month_Year:</label>
                                    <input type="text" id="datepicker" class="form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month_Year" value="{{ old('month_year') }}">
                                    @if ($errors->has('month_year'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{$errors->first('month_year')}}
                                            </strong>
                                        </span>
                                    @endif
                                </div>

                            <div class="form-group">
                                    <label>Bill Amount:</label>
                                    <input type="text" class="form-control @if($errors->has('bill_amount')) invalid-field @endif" name="bill_amount" placeholder="Enter Bill Amount" value="{{ old('bill_amount') }}">
                                    @if ($errors->has('bill_amount'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{$errors->first('bill_amount')}}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                        <label>Extra Usage Amount:</label>
                                        <input type="text" class="form-control @if($errors->has('extra_usage_amount')) invalid-field @endif" name="extra_usage_amount" placeholder="Enter extra usage amount " value="{{ old('extra_usage_amount') }}">
                                        @if ($errors->has('extra_usage_amount'))
                                            <span class="invalid-response" role="alert">
                                                <strong>
                                                    {{$errors->first('extra_usage_amount')}}
                                                </strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                            <label>Extra Usage Payment Status:</label>
                                            <select class="form-control @if($errors->has('extra_usage_payment_status')) invalid-field @endif kt-select2" id="kt_select2_3" name="extra_usage_payment_status" placeholder="Enter extra usage payment status" value="{{ old('extra_usage_payment_status') }}">
                                                    <option style="font-weight:bold;">Select Payment Status:</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="paid">Paid</option>
                                            </select> 
                                            @if ($errors->has('extra_usage_payment_status'))
                                                <span class="invalid-response" role="alert">
                                                    <strong>
                                                        {{$errors->first('extra_usage_payment_status')}}
                                                    </strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                                <label>Bill Status:</label>
                                                <select class="form-control @if($errors->has('bill_status')) invalid-field @endif kt-select2" id="kt_select2_3" name="bill_status" placeholder="Enter bill status" value="{{ old('bill_status') }}">
                                                        <option style="font-weight:bold;">Select Bill Status:</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                </select> 
                                                @if ($errors->has('bill_status'))
                                                    <span class="invalid-response" role="alert">
                                                        <strong>
                                                            {{$errors->first('bill_status')}}
                                                        </strong>
                                                    </span>
                                                @endif
                                            </div>
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" checked="checked" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span> --}}
                        </div>
                    </div>
                </form></div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 <script>

    $(document).ready(function(){
        $('#datepicker').fdatepicker({ format: 'MM_yyyy',startView:3,minView:3,maxView:4});
          }); 


</script>
@endsection