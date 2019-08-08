@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
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

                <!--begin::Form-->
                
                @include('client.includes.message')
    @php
    // $bike = App\Model\Bike\Bikes::all();
    // $current_bike = App\Model\Rider\Rider::find($rider->id)->bike;

@endphp
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
                                    {{-- @if ($current_bike !== null)
                                    hidden
                                @endif --}}
                                 >{{ $bike->brand }}&nbsp{{$bike->bike_number}}&nbsp{{ $bike->model }}</option>    
                                @endif
                                  
                                @endforeach
                                </select> 
                                 
                                
                                        
                            </div>
                            @else
                                <div>
                                   
                                    <div>
                                        <select class="form-control kt-select2" id="kt_select2_3" name="bike_id" >
                                       @foreach ($bikes as $bike)
                                       @if ($bike->availability=='yes')
                                       <option value="{{ $bike->id }}" 
                                           {{-- @if ($current_bike !== null)
                                           hidden
                                       @endif --}}
                                        >{{ $bike->brand }}&nbsp{{$bike->bike_number}}&nbsp{{ $bike->model }}</option>    
                                       @endif
                                         
                                       @endforeach
                                       </select> 
                                        
                                       
                                               
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

                <!--end::Form-->
            </div>

        <!--end::Portlet-->
    </div>
</div>
@endsection
@section('foot')
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
@endsection