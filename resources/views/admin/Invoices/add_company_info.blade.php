@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .dataTables_length{
           display: block;   
        }
        .total_entries{
        display: inline-block;
        margin-left: 10px;
        }
        .dataTables_info{
            display:none;
        }
        </style>
@endsection
@section('main-content')
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <div class="col-md-12">
            <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Add Company Information
                            </h3>
                        </div>
                    </div>
                    {{-- {{$company_info}} --}}
                    <form class="kt-form" id="company_info" action="{{ route('invoice.company_info_store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>Company Name:</label>
                             <input type="text" class="form-control" name="company_name" placeholder="Enter Company Name" @if($company_info) value="{{$company_info->company_name}}" @endif>
                            </div>
    
                            <div class="form-group">
                                <label>Company Address:</label>
                                <textarea type="text" class="form-control" name="company_address" placeholder="Enter Company Address">@if($company_info) {{$company_info->company_address}} @endif</textarea>
                            </div>
                            <div class="form-group">
                                    <label>Email:</label>
                                    <textarea type="email" class="form-control" name="company_email" placeholder="Enter Company Emial">@if($company_info) {{$company_info->company_email}} @endif</textarea>
                                </div>
                            <div class="form-group">
                                <label>Phone no:</label>
                                <input type="text" class="form-control" name="company_phone_no" placeholder="Enter Company Phone No" @if($company_info) value="{{$company_info->company_phone_no}}" @endif>
                            </div>
    
                            <div class="form-group">
                                <label>TRN:</label>
                                <input type="text"  required  class="form-control" name="company_trn" placeholder="Enter TRN" @if($company_info) value="{{$company_info->company_tax_return_no}}" @endif>
                            </div>
                            <div class="form-group">
                                <label>Account No:</label>
                                <input type="text" required class="form-control" name="company_account_no" placeholder="Enter Account No" @if($company_info) value="{{$company_info->company_account_no}}" @endif>
                            </div>
                            
                            <div class="form-group">
                                <label>Bank Name:</label>
                                <input required  type="text" class="form-control" name="company_bank_name" placeholder="Enter Bank Amount" @if($company_info) value="{{$company_info->company_bank_name}}" @endif>
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
    </div>
</div>
    
@endsection
@section('foot')
@endsection