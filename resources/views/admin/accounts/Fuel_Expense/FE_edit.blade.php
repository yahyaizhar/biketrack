@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    @if ($is_readonly==true)
    <div class="row">
            <div class="col-md-12">
            <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                            view Fuel Expense
                            </h3>
                        </div>
                    </div>
                    @include('admin.includes.message')
                        <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Rider:</label>
                            <select disabled class="form-control bk-select2 kt-select2-general" name="rider_id" >
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}" @if ($expense->rider_id==$rider->id) selected @endif>
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group">
                                <label>Bike:</label>
                                <select disabled class="form-control kt-select2-general" name="bike_id" >
                                    @foreach ($bikes as $bike)
                                    <option value="{{ $bike->id }}" @if ($expense->bike_id==$bike->id) selected @endif>
                                        {{ $bike->brand }}-{{$bike->bike_number}}
                                    </option>     
                                    @endforeach 
                                </select>
                                    
                        </div>
                            
                        <div class="form-group">
                                <label>Type:</label>
                                {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                                <select disabled class="form-control @if($errors->has('model')) invalid-field @endif kt-select2-general" name="type">
                                    <option value="vip_tag" @if ($expense->type=="vip_tag") selected @endif>VIP-Tag</option>
                                    <option value="cash" @if ($expense->type=="cash") selected @endif>Cash</option>
                                   
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
                                <label>Month:</label>
                                <input disabled type="text" data-month="{{Carbon\Carbon::parse($expense->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                                <label>Amount:</label>
                            <input disabled step="0.01" type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{$expense->amount}}">
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
                            <a href="{{url('admin/accounts/fuel_expense/edit',$expense->id)}}"><button class="btn btn-primary">Edit</button></a>
                                
                            </div>
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
                            Edit Fuel Expense
                            </h3>
                        </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('admin.includes.message')
                    <form class="kt-form" id="fuel_expense" action="{{ route('admin.update_edit_fuel_expense',$expense->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Rider:</label>
                            <select class="form-control bk-select2 kt-select2-general" name="rider_id" >
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}" @if ($expense->rider_id==$rider->id) selected @endif>
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bike:</label>
                            <select required class="form-control kt-select2-general" name="bike_id" >
                                @foreach ($bikes as $bike)
                                <option value="{{ $bike->id }}" @if ($expense->bike_id==$bike->id) selected @endif>
                                    {{ $bike->brand }}-{{$bike->bike_number}}
                                </option>     
                                @endforeach 
                            </select>    
                        </div>
                            
                        <div class="form-group">
                                <label>Type:</label>
                                {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                                <select required class="form-control @if($errors->has('model')) invalid-field @endif kt-select2-general" name="type">
                                    <option value="vip_tag" @if ($expense->type=="vip_tag") selected @endif>VIP-Tag</option>
                                    <option value="cash" @if ($expense->type=="cash") selected @endif>Cash</option>
                                   
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
                                <label>Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::parse($expense->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                                <label>Amount:</label>
                            <input required step="0.01" type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{$expense->amount}}">
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
    @endif
    
</div>

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(document).ready(function(){
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
      $('#fuel_expense [name="rider_id"]').on('change', function(){
        var rider_id=$(this).val();
        var bike_id=$('#fuel_expense [name="bike_id"]').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:"{{url('admin/accounts/fuel/expense/select/riders/bike')}}"+'/'+rider_id+"/"+bike_id,
            method: "GET"
        })
        .done(function(data) {  
            console.log(data);
            $('#fuel_expense [name="bike_id"]').val(data.assign_bike.bike_id).trigger('change');
        });
    });
    $('#fuel_expense [name="rider_id"]').trigger('change');
  });

</script>
@endsection