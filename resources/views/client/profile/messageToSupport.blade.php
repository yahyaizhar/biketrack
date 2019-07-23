@extends('client.layouts.app')
@section('main-content')
<?php $user = Auth::user(); ?>
    <!-- begin:: Content Head -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><a href="{{ route('client.home') }}">Dashboard</a></h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <span class="kt-subheader__desc">Profile</span>
            
        </div>
    </div>
    <!-- end:: Content Head -->
    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <div class="col-md-12">
            <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Send Message to Support
                            </h3>
                        </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('client.includes.message')
                    <form class="kt-form" action="{{ route('client.sendMessageToSupport') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                                    
                            <div class="form-group">
                                <label>From:</label>
                                <input type="text" class="form-control @if($errors->has('from')) invalid-field @endif" name="from" placeholder="Enter your email" value="{{ $user->email }}">
                                @if ($errors->has('from'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('from') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">We'll never share your email with anyone else</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Subject:</label>
                                <input type="text" class="form-control @if($errors->has('subject')) invalid-field @endif" name="subject" placeholder="Enter subject">
                                @if ($errors->has('subject'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('subject') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group form-group-last">
                                <label for="message">Message:</label>
                                <textarea class="form-control @if($errors->has('message')) invalid-field @endif" id="message" name="message" rows="5"></textarea>
                                @if ($errors->has('message'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('message') }}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <span class="kt-margin-l-10">or <a href="{{ route('client.home') }}" class="kt-link kt-font-bold">Cancel</a></span>
                            </div>
                        </div>
                    </form>
    
                    <!--end::Form-->
                </div>
    
            <!--end::Portlet-->
        </div>
    </div>
@endsection