@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="progress-bar sticky-top" id="myBar" style="height: 10px;background: #4caf50;width: 0%;"></div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label" >
                            
                        <h3 class="kt-portlet__head-title">
                            Create Rider
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.riders.store') }}" method="POST" enctype="multipart/form-data" >
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                                
                        <div class="form-group">
                            <label>Full Name:</label>
                            <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter full name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('name') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your full name</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Email address:</label>
                            <input type="email" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('email') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">We'll never share your email with anyone else</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="text" class="form-control @if($errors->has('phone')) invalid-field @endif" name="phone" placeholder="Enter phone number" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('phone') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your phone number</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" class="form-control @if($errors->has('passsword')) invalid-field @endif" name="password" placeholder="Enter password">
                            @if ($errors->has('password'))
                                <span class="invalid-response" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your password</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Confirm Password:</label>
                            <input type="password" class="form-control @if($errors->has('passsword')) invalid-field @endif" name="password_confirmation" placeholder="Enter confirm password">
                        </div>
                        <div class="form-group">
                            <label for="vehicle_number">Vehicle Registration Number:</label>
                            <input class="form-control @if($errors->has('vehicle_number')) invalid-field @endif" id="vehicle_number" name="vehicle_number" rows="3" placeholder="Enter vehicle registration number"  value="{{ old('vehicle_number') }}" />
                            @if ($errors->has('vehicle_number'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('vehicle_number') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your vehicle registration number</span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" class="form-control @if($errors->has('address')) invalid-field @endif" name="address" placeholder="Enter city" value="{{ old('address') }}">
                            @if ($errors->has('address'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('address')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" name="profile_picture" class="custom-file-input" id="profile_picture">
                                <label class="custom-file-label" for="profile_picture">Choose Picture</label>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" checked="checked" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                        
                        <h3>Riders Detail</h3>
                        
                        
                       
<div class="form-group">
    <label>Date of Joining:</label>
    <input type="text" id="datepicker1" autocomplete="off" class="form-control @if($errors->has('date_of_joining')) invalid-field @endif" name="date_of_joining" placeholder="Enter joining Date">
    @if ($errors->has('date_of_joining'))
        <span class="invalid-response" role="alert">
            <strong>{{ $errors->first('date_of_joining') }}</strong>
        </span>
    @else
        <span class="form-text text-muted">Please enter your Joining Date</span>
    @endif
</div>
<div class="form-group">
    <label>Official Given Number:</label>
    <input type="text" class="form-control @if($errors->has('official_given_number')) invalid-field @endif" name="official_given_number" placeholder="Enter official number" value="{{ old('official_given_number') }}">
    @if ($errors->has('official_given_number'))
        <span class="invalid-response" role="alert">
            <strong>
                {{ $errors->first('official_given_number') }}
            </strong>
        </span>
    @else
        <span class="form-text text-muted">Please enter your official phone number</span>
    @endif
</div>
<div class="form-group">
    <label>Official Sim Given Date:</label>
    <input type="text" id="datepicker2" autocomplete="off" class="form-control @if($errors->has('official_sim_given_date')) invalid-field @endif" name="official_sim_given_date" placeholder="Enter official sim given Date">
    @if ($errors->has('official_sim_given_date'))
        <span class="invalid-response" role="alert">
            <strong>{{ $errors->first('official_sim_given_date') }}</strong>
        </span>
    @else
        <span class="form-text text-muted">Please enter your Official Sim Date</span>
    @endif
</div>
<div class="form-group">
        <label>Is Passport Collected:</label>
        <div>
            <input data-switch="true" name="passport_collected" id="passport_collected" type="checkbox" checked="checked" data-on-text="Yes" data-handle-width="70" data-off-text="No" data-on-color="brand">
        </div>
    </div>
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12">
<div class="form-group">
    <label>Passport Expiry:</label>
    <input type="text" id="datepicker3" autocomplete="off" class="form-control @if($errors->has('passport_expiry')) invalid-field @endif" name="passport_expiry" placeholder="Enter Passport Expiry">
    @if ($errors->has('passport_expiry'))
        <span class="invalid-response" role="alert">
            <strong>{{ $errors->first('passport_expiry') }}</strong>
        </span>
    @else
        <span class="form-text text-muted">Please enter your Passport Expiry Date</span>
    @endif
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12">
<div class="form-group">
    <div class="custom-file" style="    margin-top: 26px;">
        <input type="file" name="passport_image" class="custom-file-input" id="passport_image">
        <label class="custom-file-label" for="passport_image">Choose Passport Picture</label>
    </div>
    </div>
</div>
</div>
        
<div class="row">

<div class="col-lg-6 col-md-6 col-sm-12">
<div class="form-group">
    <label>Visa Expiry:</label>
    <input type="text" id="datepicker4" autocomplete="off" class="form-control @if($errors->has('visa_expiry')) invalid-field @endif" name="visa_expiry" placeholder="Enter Visa Expiry">
    @if ($errors->has('visa_expiry'))
        <span class="invalid-response" role="alert">
            <strong>{{ $errors->first('visa_expiry') }}</strong>
        </span>
    @else
        <span class="form-text text-muted">Please enter your Visa Expiry Date</span>
    @endif
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12">
    <div class="form-group">
        <div class="custom-file" style="    margin-top: 26px;">
            <input type="file" name="visa_image" class="custom-file-input" id="visa_image">
            <label class="custom-file-label" for="visa_image">Choose Visa Picture</label>
        </div>
    </div>
    </div>
 </div>
<div class="row">

<div class="col-lg-6 col-md-6 col-sm-12">
<div class="form-group">
    <label>Licence Expiry:</label>
    <input type="text" id="datepicker5" autocomplete="off" class="form-control @if($errors->has('licence_expiry')) invalid-field @endif" name="licence_expiry" placeholder="Enter Licence Expiry">
    @if ($errors->has('licence_expiry'))
        <span class="invalid-response" role="alert">
            <strong>{{ $errors->first('licence_expiry') }}</strong>
        </span>
    @else
        <span class="form-text text-muted">Please enter your Licence Expiry Date</span>
    @endif
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12">
    <div class="form-group">
        <div class="custom-file" style="    margin-top: 26px;">
            <input type="file" name="licence_image" class="custom-file-input" id="licence_image">
            <label class="custom-file-label" for="licence_image">Choose Licence Picture</label>
        </div>
    </div>
    </div>
  </div>
<div class="row">

<div class="col-lg-6 col-md-6 col-sm-12">

<div class="form-group">
    <label>Mulkiya Expiry:</label>
    <input type="text" id="datepicker6" autocomplete="off" class="form-control @if($errors->has('mulkiya_expiry')) invalid-field @endif" name="mulkiya_expiry" placeholder="Enter Mulkiya Expiry" >
    @if ($errors->has('mulkiya_expiry'))
        <span class="invalid-response" role="alert">
            <strong>{{ $errors->first('mulkiya_expiry') }}</strong>
        </span>
    @else
        <span class="form-text text-muted">Please enter your Mulkiya Expiry Date</span>
    @endif
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12">
    
    <div class="form-group" style="    margin-top: 26px;">
        <div class="custom-file">
            <input type="file" name="mulkiya_image" class="custom-file-input" id="mulkiya_image">
            <label class="custom-file-label" for="mulkiya_image">Choose Mulkiya Picture</label>
        </div>
    </div>
    </div>

    </div>
    <div class="form-group">
            <label>Other Details:</label>
            <textarea type="text"  rows="8" autocomplete="off" class="form-control @if($errors->has('other_details')) invalid-field @endif" name="other_details" placeholder="Enter Further Details" ></textarea>
        </div>
 </div>
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
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
        $('#datepicker1').datepicker({dateFormat: 'yy-mm-dd'}); 
        $('#datepicker2').datepicker({dateFormat: 'yy-mm-dd'}); 
        $('#datepicker3').datepicker({dateFormat: 'yy-mm-dd'}); 
        $('#datepicker4').datepicker({dateFormat: 'yy-mm-dd'}); 
        $('#datepicker5').datepicker({dateFormat: 'yy-mm-dd'}); 
        $('#datepicker6').datepicker({dateFormat: 'yy-mm-dd'}); 
          
    });

</script>  
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function(){
window.onscroll = function() {myFunction()};
function myFunction() {
  var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var scrolled = (winScroll / height) * 100;
  document.getElementById("myBar").style.width = scrolled + "%";
}

});
</script>

@endsection