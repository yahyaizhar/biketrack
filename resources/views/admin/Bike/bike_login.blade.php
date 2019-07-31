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
                            Create Bike
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('bike.bike_create') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        
                        <div class="form-group">
                            <label>Model(2015):</label>
                            {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                            <select class="form-control @if($errors->has('model')) invalid-field @endif kt-select2" id="kt_select2_3" name="model" placeholder="Enter model" value="{{ old('model') }}">
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    </select> 
                            @if ($errors->has('model'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('model')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Bike Number(etc K-3102):</label>
                            <input type="text" class="form-control @if($errors->has('bike_number')) invalid-field @endif" name="bike_number" placeholder="Enter Bike_Number (etc K-3102)" value="{{ old('bike_number') }}">
                            @if ($errors->has('bike_number'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('bike_number')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                                <label>Brand(etc Honda):</label>
                                {{-- <input type="text" class="form-control @if($errors->has('brand')) invalid-field @endif" name="brand" placeholder="Enter Brand (etc Honda)" value="{{ old('brand') }}"> --}}
                                <select class="form-control @if($errors->has('brand')) invalid-field @endif kt-select2" id="kt_select2_3" name="brand" placeholder="Enter Brand (etc Honda)" value="{{ old('brand') }}" >
                                        <option value="Honda Unicorn">Honda Unicorn</option>
                                        <option value="Pulsar">Pulsar</option>
                                        </select> 
                                @if ($errors->has('brand'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('brand')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                     
                       
                      
                        <div class="form-group">
                                <label>Mulkiya Number:</label>
                                <input type="text" class="form-control @if($errors->has('mulkiya_number')) invalid-field @endif" name="mulkiya_number" placeholder="Enter Mulkiya Number" value="{{ old('mulkiya_number') }}">
                                @if ($errors->has('mulkiya_number'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('mulkiya_number')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                    <label>Mulkiya Expiry:</label>
                                    <input type="text" id="datepicker" autocomplete="off" class="form-control @if($errors->has('mulkiya_expiry')) invalid-field @endif" name="mulkiya_expiry" placeholder="Enter Mulkiya Expiry" value="{{ old('mulkiya_expiry') }}">
                                    @if ($errors->has('mulkiya_expiry'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{$errors->first('mulkiya_expiry')}}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                            <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" name="mulkiya_picture" class="custom-file-input" id="mulkiya_picture">
                                        <label class="custom-file-label" for="mulkiya_picture">Choose Mulkiya Picture</label>
                                    </div>
                                </div>
                         
                     
                        
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" checked="checked" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="kt-portlet__body">
                            <h1>Bike Registration Detail</h1>
                            <div class="form-group">
                                    <label>Registration Number:</label>
                                    <input type="text" class="form-control @if($errors->has('registration_number')) invalid-field @endif" name="registration_number" placeholder="Registration Number" value="{{ old('registration_number') }}">
                                    @if ($errors->has('registration_number'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{$errors->first('registration_number')}}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                               
                    </div> --}}

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>

        <!--end::Portlet-->
    </div>
</div>

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(document).ready(function(){
      $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 

  });

</script>
@endsection