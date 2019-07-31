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
                            Create Sim
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('Sim.update_sim',$sim->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                            <div class="form-group">
                                    <label>Sim Number:</label>
                            <input type="text" class="form-control @if($errors->has('sim_number')) invalid-field @endif" name="sim_number" placeholder="Enter Sim Number" value="{{$sim->sim_number}}">
                                    @if ($errors->has('sim_number'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{$errors->first('sim_number')}}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                        <label>Sim Company:</label>
                                        {{-- <input type="text" class="form-control @if($errors->has('sim_company')) invalid-field @endif" name="sim_company" placeholder="Enter Sim Company" value="{{$sim->sim_company}}"> --}}
                                        <select class="form-control @if($errors->has('sim_company')) invalid-field @endif kt-select2" id="kt_select2_3" name="sim_company" placeholder="Enter Sim Company" >
                                            <option value="{{$sim->sim_company}}">{{$sim->sim_company}}</option>
                                            <option value="DU">DU</option>
                                            <option value="Ethisalat">Ethisalat</option>
                                            </select> 
                                        @if ($errors->has('sim_company'))
                                            <span class="invalid-response" role="alert">
                                                <strong>
                                                    {{$errors->first('sim_company')}}
                                                </strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                            <label>Status:</label>
                                            <div>
                                                <input data-switch="true" name="status" id="status" type="checkbox" {!! $sim->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                            </div>
                                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span> --}}
                        </div>
                    </div>
                </form></div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
@endsection