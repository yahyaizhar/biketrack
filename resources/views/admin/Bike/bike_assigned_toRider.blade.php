@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="" style="padding-top:15px;">
                        <h3 class="kt-portlet__head-title">
                          Assign bike to: <a href="{{route('admin.rider.profile', $rider->id)}}">{{$rider->name}}</a>
                        </h3>
                        @if ($assign_bike<=0)
                        @else
                            <span style="color: #5867e4;display: block;width: 100%;font-weight: bold;">This Rider have already an active bike</span>
                        @endif
                    </div>
                </div>
            @include('client.includes.message')
            <form class="kt-form" action="{{ route('bike.bike_assignRiders', $rider->id) }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Select bike:</label>
                            @if ($assign_bike<=0)
                        <div>
                            <select class="form-control kt-select2" id="kt_select2_3" name="bike_id" >
                                @foreach ($bikes as $bike)
                                @if ($bike->availability=='yes' && $bike->status==1)
                                    <option value="{{ $bike->id }}"
                                    >{{ $bike->brand }}&nbsp{{$bike->bike_number}}&nbsp{{ $bike->model }}</option>    
                                @endif
                                @endforeach
                            </select>      
                        </div>
                        <div class="form-group mt-4">
                            <label>Bike Assign Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('Y-m-d')}}"  readonly class="month_picker form-control" name="bike_assign_date" placeholder="Enter Expiry Date">
                        </div>
                            @else
                            <div>
                                <div>
                                    <select class="form-control kt-select2" id="kt_select2_3" name="bike_id" >
                                        @foreach ($bikes as $bike)
                                        @if ($bike->availability=='yes')
                                            <option value="{{ $bike->id }}" 
                                        >{{ $bike->brand }}&nbsp{{$bike->bike_number}}&nbsp{{ $bike->model }}</option>    
                                        @endif
                                        @endforeach
                                    </select>     
                                </div>
                                <div class="form-group mt-4">
                                    <label>Bike Assign Date:</label>
                                    <input type="text" data-month="{{Carbon\Carbon::now()->format('Y-m-d')}}"  readonly class="month_picker form-control" name="bike_assign_date" placeholder="Enter Expiry Date">
                                </div>
                            </div>
                            @endif
                    </div>
            </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <span class="kt-margin-l-10">or <a href="{{url('/admin/riders')}}" class="kt-link kt-font-bold">Cancel</a></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('foot')
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
@endsection