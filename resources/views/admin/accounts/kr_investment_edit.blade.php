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
                               View Company Investment
                            </h3>
                        </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('admin.includes.message')
                        <div class="kt-portlet__body">
                                
                            <div class="form-group">
                                <label>Description:</label> 
                            <textarea disabled type="text" class="form-control @if($errors->has('notes')) invalid-field @endif" rows="5" cols="12" name="notes" placeholder="Enter Description">{{$kr_investment->notes}}</textarea>
                                @if ($errors->has('notes'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('notes')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
    
                            <div class="form-group">
                                    <label>Month:</label>
                                    <input readonly type="text" data-month="{{Carbon\Carbon::parse($kr_investment->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                                <input disabled type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{$kr_investment->amount}}">
                                @if ($errors->has('amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                         
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                        <input disabled data-switch="true" name="status" id="status" type="checkbox" {!! $kr_investment->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                
                                </div> 
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                            <a href="{{url('admin/kr_investment/edit',$kr_investment->id)}}"><button class="btn btn-primary">Edit</button></a>
                            </div>
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
                               Edit Company Investment
                            </h3>
                        </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('admin.includes.message')
                    <form class="kt-form" action="{{ route('admin.kr_investment_update',$kr_investment->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                                
                            <div class="form-group">
                                <label>Description:</label> 
                            <textarea required type="text" class="form-control @if($errors->has('notes')) invalid-field @endif" rows="5" cols="12" name="notes" placeholder="Enter Description">{{$kr_investment->notes}}</textarea>
                                @if ($errors->has('notes'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('notes')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
    
                            <div class="form-group">
                                    <label>Month:</label>
                                    <input type="text" data-month="{{Carbon\Carbon::parse($kr_investment->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                                <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{$kr_investment->amount}}">
                                @if ($errors->has('amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                         
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                        <input data-switch="true" name="status" id="status" type="checkbox" {!! $kr_investment->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                
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
@endsection 