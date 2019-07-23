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
                            Assign Riders - <a href="{{ route('admin.clients.riders', $client->id) }}">{{ $client->name }}</a>
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('client.includes.message')
                <form class="kt-form" action="{{ route('admin.clients.assignRiders', $client->id) }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Riders: </label>
                            <div>
                                <select class="form-control kt-select2" id="kt_select2_3" name="riders[]" multiple="multiple">
                                @foreach ($riders as $rider)
                                    <option value="{{ $rider->id }}" 
                                        {{-- @foreach ($client->getRiders as $assigned_rider)
                                            @if($rider->id == $assigned_rider->id)
                                                selected = "selected"
                                            @endif
                                        @endforeach --}}
                                    >{{ $rider->name }} - {{ $rider->vehicle_number }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <span class="kt-margin-l-10">or <a href="{{ route('admin.clients.index') }}" class="kt-link kt-font-bold">Cancel</a></span>
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
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
@endsection