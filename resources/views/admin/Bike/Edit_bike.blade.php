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
                            Edit Bike Information
                            
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('Bike.bike_update', $bike->id) }}" method="post" enctype="multipart/form-data">
                    {{-- {{ method_field('PUT') }} --}}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                
                                <div class="form-group">
                                    <label>Model:</label>
                                    <input type="text" class="form-control @if($errors->has('model')) invalid-field @endif" name="model" placeholder="Model" value="{{ $bike->model }}">
                                    @if ($errors->has('model'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('model') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter model name</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Bike Number:</label>
                                    <input type="text" class="form-control @if($errors->has('bike_number')) invalid-field @endif" name="bike_number" placeholder="Enter Bike Number" value="{{ $bike->bike_number }}">
                                    @if ($errors->has('bike_number'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('bike_number') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">We Want your Bike Number</span>
                                    @endif
                                </div>
                            </div>
                           
                        </div>
                        <div class="form-group">
                            <label>Availability:</label>
                            <input type="text" class="form-control @if($errors->has('availability')) invalid-field @endif" name="availability" placeholder="yes or no" value="{{ $bike->availability }}">
                            @if ($errors->has('availability'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('availability') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Is Bike is available or not?</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" {!! $bike->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
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