@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Add New Salary
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                    
                
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('account.added_salary')}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                     
                    <div class="kt-portlet__body">
                        <label>Select Rider:</label>
                        <select class="form-control kt-select2" id="kt_select2_3" name="rider_id" >
                        @foreach ($riders as $rider)
                       <option value="{{ $rider->id }}">
                        {{ $rider->name }}
                    </option>     
                      @endforeach 
                       </select> 
                   </div>

                    <div class="kt-portlet__body">
                        {{-- <div class="form-group">
                            <label>Month:</label>
                            <input type="text" class="form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="{{ old('month') }}">
                            @if ($errors->has('month'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('month') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter Month</span>
                            @endif
                        </div> --}}
                        <div class="form-group">
                            <label>Salary:</label>
                            <input type="text" class="form-control @if($errors->has('salary')) invalid-field @endif" name="salary" placeholder="Enter Salary" value="{{ old('salary') }}">
                            @if ($errors->has('salary'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('salary') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Enter Salary in RS.</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="paid_by">Paid By:</label>
                            <input class="form-control @if($errors->has('paid_by')) invalid-field @endif" id="paid_by" name="paid_by" rows="3" placeholder="Paid By"  value="{{ old('paid_by') }}" />
                            @if ($errors->has('paid_by'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('paid_by') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Salary Paid By:</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" checked="checked" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                        <div>
                            <input  name="setting" style="visibility:hidden;">
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

@endsection