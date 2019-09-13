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
<form class="kt-form" action="{{ route('Employee.update_employee',$edit_employee->id) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
        <div class="kt-portlet__body">
            <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12"> 
            <div class="form-group"> 
                <label>Name:</label>
                <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter Your Name" required autofocus value="{{ $edit_employee->name }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="text" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter Your Email" required value="{{ $edit_employee->email }}">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group col-md-6 pull-right mtr-15">
                            <div class="custom-file">
                                <input type="file" name="logo" class="custom-file-input" id="logo">
                                <label class="custom-file-label" for="logo">Choose Profile Picture</label>
                            </div>
                    </div>    
                @if($edit_employee->logo)
                        <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($edit_employee->logo)) }}" alt="image">
                    @else
                        <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                    @endif
                   
            </div>
        </div>
            <label class="kt-checkbox">
                    <input id="change-password" name="change_password" type="checkbox" {{ old('change_password') ? 'checked' : '' }}> Change Password
                    <span></span>
                </label>
                <div id="password-fields" style="display:none;">
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
                </div>
            <h5>User Rights</h5>
            
            <div class="row">
            <div class="col-md-4">

            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($Dashboard['action_name']=="dashboard") checked @endif value="dashboard"> Dashboard
                        <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($Accounts['action_name']=="accounts") checked @endif value="accounts"> Accounts
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($Expense['action_name']=="expense") checked @endif value="expense"> Expense
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($NewComer['action_name']=="new_comer") checked @endif value="new_comer"> New Comer
                        <span></span>
                    </label>
                </div>
            </div>
            </div>
            <div class="col-md-4">
            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($Riders['action_name']=="riders") checked @endif value="riders"> Riders
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($Bikes['action_name']=="bikes") checked @endif value="bikes"> Bikes
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($Clients['action_name']=="clients") checked @endif value="clients" > Clients
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($Sim['action_name']=="sim") checked @endif value="sim"> Sim
                        <span></span>
                    </label>
                </div>
            </div>
            </div>
            <div class="col-md-4">
            <div class="form-group">
                <div class="kt-checkbox-inline">
                    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                        <input type="checkbox" name="action_name[]" @if ($Mobile['action_name']=="mobile") checked @endif value="mobile"> Mobile
                        <span></span>
                    </label>
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
<script>
        $(document).ready(function () {
            if($('#change-password').prop('checked') == true)
            {
                $('#password-fields').show();
            }
            $('#change-password').change(function () {
                $('#password-fields').fadeToggle();
            });
        });
    </script>
@endsection