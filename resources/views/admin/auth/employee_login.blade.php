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
<!--begin::Portlet-->
        <div class="kt-portlet">
        <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Add Employee
            </h3>
        </div>
        </div>
@include('admin.includes.message')
<form class="kt-form" action="{{ route('Employee.insert_employee') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
        <div class="kt-portlet__body">
            <div class="form-group"> 
                <label>Name:</label>
                <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter Your Name" required autofocus value="{{ old('name') }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group"> 
                <label>Email:</label>
                <input type="text" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter Your Email" required value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group"> 
                <label>Password:</label>
                <input type="password" class="form-control @if($errors->has('password')) invalid-field @endif" name="password" placeholder="Enter Your Password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group"> 
                <label>Password:</label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm your password" required>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label for="address">Logo:</label>
                        <div class="custom-file">
                            <input type="file" name="logo" class="custom-file-input" id="logo" value="{{ old('logo') }}">
                            <label class="custom-file-label" for="logo">Choose logo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
        <div class="kt-form__actions kt-form__actions--right">
                <button type="submit" class="btn btn-primary">Submit</button>
                <span class="kt-margin-l-10">or <a href="{{ url('/admin') }}" class="kt-link kt-font-bold">Cancel</a></span>
        </div>
    </div>
</form>
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
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 

  });
</script>
@endsection