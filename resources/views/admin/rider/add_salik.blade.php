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
                            Add Salik
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('Saik.insert_salik') }}" id="salik" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">

                        <div class="form-group">
                            <label>Bike:</label>
                            <select required class="form-control bk-select2" name="bike_id" >
                                @foreach ($bikes as $bike)
                                <option value="{{ $bike->id }}">
                                {{ $bike->brand }}-{{$bike->bike_number}}-{{$bike->model}}
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
                            <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
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
    $('#salik [name="month"]').on('change', function(){
        var _month = $('#salik [name="month"]').val();
        
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
                $('#salik [name="amount"]').val(data.salik_amount).trigger('change');
                if(data.bike_histories!==null){
                    $('#salik [name="bike_id"]').val(data.bike_histories.bike_id).trigger('change');
                }
                else{
                    $('#salik [name="bike_id"]')[0].selectedIndex = -1;
                    $('#salik [name="bike_id"]').trigger('change');
                    $('#salik [name="amount"]').val('');
                }
                
            });
        }
    });
    $('#salik [name="month"]').trigger('change');
  });
  

</script>
@endsection