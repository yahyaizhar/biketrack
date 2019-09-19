@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    @if ($readonly==true)
    <div class="row">
            <div class="col-md-12">
            <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                            View WPS
                            </h3>
                        </div>
                    </div>
                    @include('admin.includes.message')
                        <div class="kt-portlet__body">
    
                            <div class="form-group">
                                <label>Bank Name:</label>
                                <input readonly type="text" class="form-control @if($errors->has('bank_name')) invalid-field @endif" name="bank_name" placeholder="Enter Bank Name" value="{{ $edit_wps->bank_name }}">
                                @if ($errors->has('bank_name'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('bank_name')}}
                                        </strong>
                                    </span>
                                @endif
                            </div> 
    
                            <div class="form-group">
                                <label>Rider:</label>
                                <select disabled class="form-control kt-select2-general" name="rider_id" >
                                    @foreach ($riders as $rider)
                                    <option value="{{ $rider->id }}" @if ($edit_wps->rider_id==$rider->id) selected @endif>
                                        {{ $rider->name }}
                                    </option>     
                                    @endforeach 
                                </select>
                            </div>
                            <div class="form-group">
                                    <label>Month:</label>
                                    <input disabled type="text" data-month="{{Carbon\Carbon::parse($edit_wps->month)->format('M d, Y')}}" class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                                    @if ($errors->has('month'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('month') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter Month</span>
                                    @endif
                                </div>
                            <div class="form-group">
                                <label>Amount:</label>
                            <input disabled type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{ $edit_wps->amount }}">
                                @if ($errors->has('amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label>Payment Status:</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio">
                                        <input disabled type="radio" name="payment_status" @if ($edit_wps->payment_status==='deposit') checked @endif value="deposit"> Deposit To Bank
                                        <span></span>
                                    </label>
                                    {{-- <label class="kt-radio">
                                            <input type="radio" name="payment_status"@if ($edit_wps->payment_status==='withdraw') checked @endif value="withdraw"> Withdrawn From Bank
                                        <span></span>
                                    </label> --}} 
                                    <label class="kt-radio">
                                        <input disabled type="radio" name="payment_status"@if ($edit_wps->payment_status==='kingrider') checked @endif value="kingrider"> Deposit To Kingrider
                                    <span></span>
                                </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                    <input disabled data-switch="true" name="status" id="status" type="checkbox" {!! $edit_wps->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                </div>
                            </div>
                        </div>
                        
                       
    
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                            <a href="{{url('admin/accounts/wps/edit',$edit_wps->id)}}"><button class="btn btn-primary">Edit</button></a>
                            </div>
                        </div>
        </div>  
    @else
    <div class="row">
            <div class="col-md-12">
            <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                            Edit WPS
                            </h3>
                        </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('admin.includes.message')
                    <form class="kt-form" action="{{ route('admin.wps_update',$edit_wps->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
    
                            <div class="form-group">
                                <label>Bank Name:</label>
                                <input type="text" class="form-control @if($errors->has('bank_name')) invalid-field @endif" name="bank_name" placeholder="Enter Bank Name" value="{{ $edit_wps->bank_name }}">
                                @if ($errors->has('bank_name'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('bank_name')}}
                                        </strong>
                                    </span>
                                @endif
                            </div> 
    
                            <div class="form-group">
                                <label>Rider:</label>
                                <select required class="form-control kt-select2-general" name="rider_id" >
                                    @foreach ($riders as $rider)
                                    <option value="{{ $rider->id }}" @if ($edit_wps->rider_id==$rider->id) selected @endif>
                                        {{ $rider->name }}
                                    </option>     
                                    @endforeach 
                                </select>
                            </div>
                            <div class="form-group">
                                    <label>Month:</label>
                                    <input type="text" data-month="{{Carbon\Carbon::parse($edit_wps->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                                    @if ($errors->has('month'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('month') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter Month</span>
                                    @endif
                                </div>
                            <div class="form-group">
                                <label>Amount:</label>
                            <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{ $edit_wps->amount }}">
                                @if ($errors->has('amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label>Payment Status:</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio">
                                        <input type="radio" name="payment_status" @if ($edit_wps->payment_status==='deposit') checked @endif value="deposit"> Deposit To Bank
                                        <span></span>
                                    </label>
                                    {{-- <label class="kt-radio">
                                            <input type="radio" name="payment_status"@if ($edit_wps->payment_status==='withdraw') checked @endif value="withdraw"> Withdrawn From Bank
                                        <span></span>
                                    </label> --}} 
                                    <label class="kt-radio">
                                        <input type="radio" name="payment_status"@if ($edit_wps->payment_status==='kingrider') checked @endif value="kingrider"> Deposit To Kingrider
                                    <span></span>
                                </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                    <input data-switch="true" name="status" id="status" type="checkbox" {!! $edit_wps->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                </div>
                            </div>
                        </div>
                        
                       
    
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                
                            </div>
                        </div>
                    </form>
    
                    <!--end::Form-->
                </div>
    
            <!--end::Portlet-->
        </div>
    @endif
   
</div>

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(document).ready(function(){
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 

  });

</script>
@endsection