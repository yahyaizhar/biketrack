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
                <form class="kt-form" action="{{ route('admin.accounts.maintenance_post') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Maintenance Type:</label>
                            {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                            <select required  class="form-control @if($errors->has('maintenance_type')) invalid-field @endif kt-select2-general" name="maintenance_type">
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
                            <select required class="form-control kt-select2-general" name="workshop_id" >
                                @foreach ($workshops as $workshop)
                                <option value="{{ $workshop->id }}">
                                    {{ $workshop->name }}
                                </option>     
                                @endforeach 
                            </select> 
                        </div>

                        <div class="form-group">
                            <label>Bike:</label>
                            <select required class="form-control kt-select2-general" name="bike_id" >
                                @foreach ($bikes as $bikes)
                                <option value="{{ $bikes->id }}">
                                    {{ $bikes->model }}-{{ $bikes->bike_number }}
                                </option>     
                                @endforeach 
                            </select> 
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
                        <div class="form-group" id="accident_payment_status">
                            <label>Accident Payment type:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input required type="radio" name="accident_payment_status" value="pending"> Pending
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input required type="radio" name="accident_payment_status" value="paid"> Paid
                                    <span></span>
                                </label>
                            </div>
                        </div>
                     
                        
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" checked="checked" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
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
<script>
$(document).ready(function(){
$('[name="maintenance_type"]').on("change",function(){
    var _val=$(this).val();
    $("#accident_payment_status").show();
   if (_val=="accident") {
       $("#accident_payment_status").show();
       $('[name="accident_payment_status"]').prop('required', true);
   }
   else{
    $("#accident_payment_status").hide();
    
    $('[name="accident_payment_status"]').prop('checked', false).prop('required', false);
   }
});
    $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
});
 
</script>
@endsection