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
                                View Month Information
                                
                            </h3>
                        </div>
                    </div>
                    @include('admin.includes.message')
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Rider:</label>
                                        <select disabled  class="form-control kt-select2-general" name="rider_id" >
                                            @foreach ($riders as $rider)
                                            <option value="{{ $rider->id }}" @if ($month->rider_id==$rider->id) selected @endif>
                                                {{ $rider->name }}
                                            </option>     
                                            @endforeach 
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Salary:</label>
                                        <input disabled type="text" class="form-control @if($errors->has('salary')) invalid-field @endif" name="salary" placeholder="salary" value="{{ $month->total_salary }}">
                                        @if ($errors->has('salary'))
                                            <span class="invalid-response" role="alert">
                                                <strong>
                                                    {{ $errors->first('salary') }}
                                                </strong>
                                            </span>
                                        @else
                                            <span class="form-text text-muted">Please enter Ammount</span>
                                        @endif
                                    </div>
                                </div>
                               
                            </div>
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                    <input disabled data-switch="true" name="status" id="status" type="checkbox" {!! $month->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                            <a href="{{route('account.edit_month',$month->id)}}"><button  class="btn btn-primary">Edit</button></a>    
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
                                Edit Month Information
                                
                            </h3>
                        </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('admin.includes.message')
                    <form class="kt-form" action="{{ route('account.month_update', $month->id) }}" method="post" enctype="multipart/form-data">
                        {{-- {{ method_field('PUT') }} --}}
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Rider:</label>
                                        <select  class="form-control kt-select2-general" name="rider_id" >
                                            @foreach ($riders as $rider)
                                            <option value="{{ $rider->id }}" @if ($month->rider_id==$rider->id) selected @endif>
                                                {{ $rider->name }}
                                            </option>     
                                            @endforeach 
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Salary:</label>
                                        <input type="text" class="form-control @if($errors->has('salary')) invalid-field @endif" name="salary" placeholder="salary" value="{{ $month->total_salary }}">
                                        @if ($errors->has('salary'))
                                            <span class="invalid-response" role="alert">
                                                <strong>
                                                    {{ $errors->first('salary') }}
                                                </strong>
                                            </span>
                                        @else
                                            <span class="form-text text-muted">Please enter Ammount</span>
                                        @endif
                                    </div>
                                </div>
                               
                            </div>
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                    <input data-switch="true" name="status" id="status" type="checkbox" {!! $month->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                {{-- <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span> --}}
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