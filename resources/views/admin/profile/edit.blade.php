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
                            Edit Profile Information
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('client.includes.message')
                <form class="kt-form" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Full Name:</label>
                            <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter full name" value="{{ $user->name }}">
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
                            <input type="email" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter email" value="{{ $user->email }}">
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
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <span class="kt-margin-l-10">or <a href="{{ route('admin.home') }}" class="kt-link kt-font-bold">Cancel</a></span>
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