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
                            Fuel Expence
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" id="fuel_expense" action="{{ route('admin.fuel_expense_insert') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Rider:</label>
                            <select class="form-control bk-select2 kt-select2-general" name="rider_id" >
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Bike:</label>
                            <select required class="form-control bk-select2" name="bike_id" >
                                @foreach ($bikes as $bike)
                                <option value="{{ $bike->id }}">
                                    {{ $bike->brand }}-{{$bike->bike_number}}
                                </option>     
                                @endforeach 
                            </select>
                                
                        </div>
                        
                        <div class="form-group">
                            <label>Type:</label>
                            {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                            <select required class="form-control @if($errors->has('model')) invalid-field @endif bk-select2" name="type">
                                <option value="vip_tag">VIP-Tag</option>
                                <option value="cash">Cash</option>
                            </select> 
                            @if ($errors->has('type'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('type')}}
                                    </strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Taken Fuel Month:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('m')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                            @if ($errors->has('month'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('month') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter Month</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Given Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Given Date" value="">
                            @if ($errors->has('given_date'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('given_date') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter Given Date</span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label>Amount:</label>
                            <input required step="0.01" type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
                            @if ($errors->has('amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('amount')}}
                                    </strong>
                                </span>
                            @endif
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

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script data-ajax>
  $(document).ready(function(){
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
    $('#fuel_expense [name="month"], #fuel_expense [name="bike_id"]').on('change', function(){
        var _month = $('#fuel_expense [name="month"]').val();
        
        if(_month=='')return;
        _month = new Date(_month).format('yyyy-mm-dd');

        var bike_id = $('#fuel_expense [name="bike_id"]').val();
        if(typeof bike_id !== "undefined"){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/salik/ajax/get_active_bikes/')}}"+'/'+bike_id+"/"+_month+"/bike",
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                if(data.bike_histories!==null){
                    $('#fuel_expense [name="rider_id"]').val(data.bike_histories.rider_id).trigger('change.select2');
                }
                else{
                    $('#fuel_expense [name="rider_id"]')[0].selectedIndex = -1;
                    $('#fuel_expense [name="rider_id"]').trigger('change.select2');
                    $('#fuel_expense [name="amount"]').val('');
                }
            });
        }
    });

    $('#fuel_expense [name="rider_id"]').on('change', function(){
        var _month = $('#fuel_expense [name="month"]').val();
        
        if(_month=='')return;
        _month = new Date(_month).format('yyyy-mm-dd');

        var ride_id = $('#fuel_expense [name="rider_id"]').val();
        if(typeof ride_id !== "undefined"){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/salik/ajax/get_active_bikes/')}}"+'/'+ride_id+"/"+_month+"/rider",
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                if(data.bike_histories!==null){
                    $('#fuel_expense [name="bike_id"]').val(data.bike_histories.bike_id).trigger('change.select2');
                }
                else{
                    $('#fuel_expense [name="bike_id"]')[0].selectedIndex = -1;
                    $('#fuel_expense [name="bike_id"]').trigger('change.select2');
                    $('#fuel_expense [name="amount"]').val('');
                }
            });
        }
    });

    //set default rider
    var _gb_rider_id = $('#gb_rider_id').val();
    if(typeof _gb_rider_id !== "undefined"){
        $('#fuel_expense [name="rider_id"]').val(_gb_rider_id).trigger('change');
    }
  });

</script>
@endsection