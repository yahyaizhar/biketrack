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
                            View Advance & Return
                            </h3>
                        </div>
                    </div>
                    
                    @include('admin.includes.message')
                        <div class="kt-portlet__body">
    
                            <div class="form-group">
                                <label>Advance & Return  Type:</label>
                                {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                                <select disabled  class="form-control @if($errors->has('type')) invalid-field @endif kt-select2-general" name="type">
                                    <option value="advance" @if ($edit_ar->type=="advance") selected @endif>Advance</option>
                                    <option value="return" @if ($edit_ar->type=="return") selected @endif>Return</option>
                                </select> 
                                @if ($errors->has('type'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('type')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
    
                            <div class="form-group">
                                <label>Rider:</label>
                                <select disabled class="form-control kt-select2-general" name="rider_id" >
                                    @foreach ($riders as $rider)
                                    <option value="{{ $rider->id }}" @if ($edit_ar->rider_id==$rider->id) selected @endif>
                                        {{ $rider->name }}
                                    </option>     
                                    @endforeach 
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label>Month:</label>
                                <input disabled type="text" data-month="{{Carbon\Carbon::parse($edit_ar->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                                @if ($errors->has('month'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('month') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter Month</span>
                                @endif
                            </div> --}}
                            <div class="form-group">
                                <label>Taken Advance Month:</label>
                                <input disabled type="text" data-month="{{Carbon\Carbon::parse($edit_ar->month)->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                                <label>Given Advance Date:</label>
                                <input disabled type="text" data-month="{{Carbon\Carbon::parse($edit_ar->given_date)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Given Date" value="">
                                @if ($errors->has('given_date'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('given_date') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter Given Advance Date</span>
                                @endif
                            </div>
        
    
                            <div class="form-group">
                                <label>Amount:</label>
                            <input disabled type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{ $edit_ar->amount }}">
                                @if ($errors->has('amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            
                            <input type="hidden" name="payment_status" value="pending">
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                    <input disabled data-switch="true" name="status" id="status" type="checkbox" {!! $edit_ar->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                </div>
                            </div>
                        </div>
                        
                       
    
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                            <a href="{{url('admin/accounts/AR/edit', $edit_ar->id)}}"><button  class="btn btn-primary">Edit</button></a>
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
                            Edit Advance & Return
                            </h3>
                        </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('admin.includes.message')
                    <form class="kt-form" action="{{ route('admin.AR_update',$edit_ar->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
    
                            <div class="form-group">
                                <label>Advance & Return  Type:</label>
                                {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                                <select required  class="form-control @if($errors->has('type')) invalid-field @endif kt-select2-general" name="type">
                                    <option value="advance" @if ($edit_ar->type=="advance") selected @endif>Advance</option>
                                    <option value="return" @if ($edit_ar->type=="return") selected @endif>Return</option>
                                </select> 
                                @if ($errors->has('type'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('type')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
    
                            <div class="form-group">
                                <label>Rider:</label>
                                <select required class="form-control kt-select2-general" name="rider_id" >
                                    @foreach ($riders as $rider)
                                    <option value="{{ $rider->id }}" @if ($edit_ar->rider_id==$rider->id) selected @endif>
                                        {{ $rider->name }}
                                    </option>     
                                    @endforeach 
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label>Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::parse($edit_ar->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                                @if ($errors->has('month'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('month') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter Month</span>
                                @endif
                            </div> --}}
                            <div class="form-group">
                                <label>Taken Advance Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::parse($edit_ar->month)->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                                <label>Given Advance Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::parse($edit_ar->given_date)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Given Date" value="">
                                @if ($errors->has('given_date'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('given_date') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter Given Advance Date</span>
                                @endif
                            </div>
        
    
                            <div class="form-group">
                                <label>Amount:</label>
                            <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{ $edit_ar->amount }}">
                                @if ($errors->has('amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            
                            <input type="hidden" name="payment_status" value="pending">
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                    <input data-switch="true" name="status" id="status" type="checkbox" {!! $edit_ar->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
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