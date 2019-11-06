@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="" style="padding-top:15px;">
                        <h3 class="kt-portlet__head-title">
                        <a href="">{{$bike->brand}}-{{$bike->model}} {{$bike->bike_number}}</a>
                        @if ($bike->is_given=='0')
                            <h5>The Bike is already given to Kingriders</h5>
                        @endif
                        </h3>
                    </div>
                </div>
                @include('client.includes.message')
            <form class="kt-form" action="{{route('bike.is_given_bike_status',$bike->id)}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        {{-- <div class="form-group">
                            <label>Company:</label>
                            <input required type="text" class="form-control @if($errors->has('assigned_company')) invalid-field @endif" name="assigned_company" placeholder="Select Company to Assign Bike">
                        </div> --}}
                        @if ($bike->rider_id==null)
                        <div class="form-group">
                            <label>Bike Rent:</label>
                            <input type="text" class="form-control" name="monthly_rent" placeholder="Enter price of Monthly Rent" value="{{$bike->rent_amount}}">
                        </div>
                        @else
                        <div class="form-group">
                            <label>Bike Allowns:</label>
                            <input type="text" class="form-control " name="bike_allowns" placeholder="Enter Bike Allowns" value="{{$bike->bike_allowns}}">
                        </div>
                        @endif
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot')
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
@endsection