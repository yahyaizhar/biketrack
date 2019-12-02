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
                            Maintenance
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.accounts.maintenance_post') }}" method="POST" id="maintenance" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Maintenance Type:</label>
                            {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                            <select required  class="form-control bk-select2 @if($errors->has('maintenance_type')) invalid-field @endif kt-select2-general" name="maintenance_type">
                                <option value="accident">Accident</option>
                                <option value="regular">Regular</option>
                            </select> 
                            @if ($errors->has('maintenance_type'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('maintenance_type')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Workshop:</label>
                            <select required class="form-control bk-select2" name="workshop_id" >
                                @foreach ($workshops as $workshop)
                                <option value="{{ $workshop->id }}">
                                    {{ $workshop->name }}
                                </option>     
                                @endforeach 
                            </select> 
                        </div>

                        <div class="form-group">
                            <label>Bike:</label>
                            <select required class="form-control bk-select2" name="bike_id" >
                                @foreach ($bikes as $bikes)
                                <option value="{{ $bikes->id }}">
                                    {{ $bikes->model }}-{{ $bikes->bike_number }}
                                </option>     
                                @endforeach 
                            </select> 
                        </div>
                        {{-- <div class="form-group">
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
                            </div> --}}

                        <div class="form-group">
                            <label>Mainntenance Month:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                            <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
                            @if ($errors->has('amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('amount')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Paid By Rider:</label>
                            <input required type="number" class="form-control @if($errors->has('paid_by_rider')) invalid-field @endif" name="paid_by_rider" placeholder="Enter Amount" value="">
                            @if ($errors->has('paid_by_rider'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('paid_by_rider')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Paid By Company:</label>
                            <input required type="number" class="form-control @if($errors->has('paid_by_company')) invalid-field @endif" name="paid_by_company" placeholder="Enter Amount" value="">
                            @if ($errors->has('paid_by_company'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('paid_by_company')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" name="invoice_image" class="custom-file-input" id="invoice_image">
                                <label class="custom-file-label" for="invoice_image">Choose Image</label>
                                <span class="form-text text-muted">Choose Image</span>
                            </div>
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
    $('#maintenance [name="amount"]').on("change input",function(){
        var _val=parseFloat($('[name="amount"]').val());
        $('#maintenance [name="paid_by_company"]').val(_val);
        var _company=parseFloat($('#maintenance [name="paid_by_company"]').val());
        var _rider=_val-_company;
        $('#maintenance [name="paid_by_rider"]').val(_rider);
    });

    $('#maintenance [name="paid_by_company"]').on("change input",function(){
        var _c_val=$(this).val();
        var _val=parseFloat($('#maintenance [name="amount"]').val());
        var res_rider=_val-_c_val;
        if (_c_val>_val) {
            console.log("Value is greater");
            $(this).val(_val);  
        }else if(_c_val<0){
            console.log("Value is less");
            $(this).val(0);
        }else{
            $('#maintenance [name="paid_by_rider"]').val(res_rider);
        }
    });
    
    $('#maintenance [name="paid_by_rider"]').on("change input",function(){
        var _c_val=$(this).val();
        var _val=parseFloat($('#maintenance [name="amount"]').val());
        var res_comany=_val-_c_val;
        if (_c_val>_val) {
            console.log("Value is greater");
            $(this).val(_val);  
        }else if(_c_val<0){
            console.log("Value is less");
            $(this).val(0);
        }else{
            $('#maintenance [name="paid_by_company"]').val(res_comany); 
        }
    });



$('#maintenance [name="maintenance_type"]').on("change",function(){
    var _val=$(this).val();
    $("#accident_payment_status").show();
   if (_val=="accident") {
       $("#accident_payment_status").show();
       $('#maintenance [name="accident_payment_status"]').prop('required', true);
   }
   else{
    $("#accident_payment_status").hide();
    
    $('#maintenance [name="accident_payment_status"]').prop('checked', false).prop('required', false);
   }
});
    $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
});
$('#maintenance [name="month"]').on('change', function(){
        var _month = $('#maintenance [name="month"]').val();
        
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
                    $('#maintenance [name="bike_id"]').val(data.bike_histories.bike_id).trigger('change');
                }
                else{
                    $('#maintenance [name="bike_id"]')[0].selectedIndex = -1;
                    $('#maintenance [name="bike_id"]').trigger('change');
                    $('#maintenance [name="amount"]').val('');
                }
                
            });
        }
    });
    $('#maintenance [name="month"]').trigger('change'); 
 
</script>
@endsection