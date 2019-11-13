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
                            Add Bike Rent
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.post_bike_rent') }}" id="bike_rent" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Rider:</label>
                            <select class="form-control bk-select2 kt-select2-general" name="rider_id" >
                                <option value="">Select a Rider<option>
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bike:</label>
                            <select  class="form-control bk-select2" name="bike_id" >
                                    <option value="">Select a Bike<option>
                                @foreach ($bikes as $bike)
                                <option value="{{ $bike->id }}">
                                    {{ $bike->brand }} - {{$bike->bike_number}}
                                </option>     
                                @endforeach 
                            </select> 
                        </div>
                        <div class="form-group">
                            <label>Month:</label>
                        <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                            <input required type="number" step="0.01" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
                            @if ($errors->has('amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('amount')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Owner:</label>
                            <select  class="form-control bk-select2" name="owner" >
                                <option value="kr_own">Kr-Bike</option>
                                <option value="rent">Rental Bike</option>
                                <option value="rider_bike">Rider Own Bike</option>
                            </select> 
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
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 

      $('#bike_rent [name="month"]').on('change', function(){
        var _month = $('#bike_rent [name="month"]').val();
        
        if(_month=='')return;
        _month = new Date(_month).format('yyyy-mm-dd');

        var gb_rider_id = $('#gb_rider_id').val();
        if(typeof gb_rider_id !== "undefined"){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/salik/ajax/get_active_riders/')}}"+'/'+gb_rider_id+"/"+_month,
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);

                // $('#salik [name="amount"]').val(data.salik_amount).trigger('change');
                if(data.bike_histories!==null){
                    $('#bike_rent [name="bike_id"]').val(data.bike_histories.bike_id).trigger('change');
                }
                else{
                    $('#bike_rent [name="bike_id"]')[0].selectedIndex = -1;
                    $('#bike_rent [name="bike_id"]').trigger('change');
                    $('#bike_rent [name="amount"]').val('');
                }
                
            });
        }
    });
    $('#bike_rent [name="month"]').trigger('change');
    $('#bike_rent [name="rider_id"]').on('change', function(){
        var rider_id=$(this).val();
        var bike_id=$('#bike_rent [name="bike_id"]').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:"{{url('admin/accounts/fuel/expense/select/riders/bike')}}"+'/'+rider_id+"/"+bike_id,
            method: "GET"
        })
        .done(function(data) {  
            console.log(data);
            $('#bike_rent [name="bike_id"]').val(data.assign_bike.bike_id).trigger('change');
        });
    });
    $('#bike_rent [name="rider_id"]').trigger('change');
  });
  

</script>
@endsection