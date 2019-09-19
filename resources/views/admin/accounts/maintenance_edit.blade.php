@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    @if ($readonly==true)
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
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>Maintenance Type:</label>
                                {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                                <select disabled class="form-control @if($errors->has('maintenance_type')) invalid-field @endif kt-select2-general" name="maintenance_type">
                                    <option @if($maintenance->maintenance_type=='accident') selected @endif value="accident">Accident</option>
                                    <option @if($maintenance->maintenance_type=='regular') selected @endif value="regular">Regular</option>
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
                                <select disabled class="form-control kt-select2-general" name="workshop_id" >
                                    @foreach ($workshops as $workshop)
                                    <option @if($maintenance->workshop_id==$workshop->id) selected @endif value="{{ $workshop->id }}">
                                        {{ $workshop->name }}
                                    </option>     
                                    @endforeach 
                                </select> 
                            </div>
    
                            <div class="form-group">
                                <label>Bike:</label>
                                <select disabled class="form-control kt-select2-general" name="bike_id" >
                                    @foreach ($bikes as $bike)
                                    <option @if($maintenance->bike_id==$bike->id) selected @endif  value="{{ $bike->id }}">
                                        {{ $bike->model }}-{{ $bike->bike_number }}
                                    </option>     
                                    @endforeach 
                                </select> 
                            </div>
    
                            <div class="form-group">
                                    <label>Month:</label>
                            <input disabled type="text" class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="{{$maintenance->month}}">
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
                            <input disabled type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{$maintenance->amount}}">
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
                                <input disabled type="number" class="form-control @if($errors->has('paid_by_rider')) invalid-field @endif" name="paid_by_rider" placeholder="Enter Amount" value="{{$maintenance->paid_by_rider}}">
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
                                <input disabled type="number" class="form-control @if($errors->has('paid_by_company')) invalid-field @endif" name="paid_by_company" placeholder="Enter Amount" value="{{$maintenance->paid_by_company}}">
                                @if ($errors->has('paid_by_company'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('paid_by_company')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                       
                                @if($maintenance->invoice_image)
                                        <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($maintenance->invoice_image)) }}" alt="image">
                                    @else
                                        <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                    <input disabled data-switch="true" name="status" id="status" type="checkbox" @if($maintenance->status==1) checked @endif data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                                </div>
                            </div>
                        
                        
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                            <a href='{{url('admin/accounts/maintenance/edit',$maintenance->id)}}'><button class="btn btn-primary">Edit</button></a>
                                
                            </div>
                        </div>
                    </form>
    
                    <!--end::Form-->
                </div>
    
            <!--end::Portlet-->
        </div>
    @else
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
                    <form class="kt-form" action="{{ route('admin.maintenance_update',$maintenance  ->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>Maintenance Type:</label>
                                {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                                <select required class="form-control @if($errors->has('maintenance_type')) invalid-field @endif kt-select2-general" name="maintenance_type">
                                    <option @if($maintenance->maintenance_type=='accident') selected @endif value="accident">Accident</option>
                                    <option @if($maintenance->maintenance_type=='regular') selected @endif value="regular">Regular</option>
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
                                    <option @if($maintenance->workshop_id==$workshop->id) selected @endif value="{{ $workshop->id }}">
                                        {{ $workshop->name }}
                                    </option>     
                                    @endforeach 
                                </select> 
                            </div>
    
                            <div class="form-group">
                                <label>Bike:</label>
                                <select required class="form-control kt-select2-general" name="bike_id" >
                                    @foreach ($bikes as $bike)
                                    <option @if($maintenance->bike_id==$bike->id) selected @endif  value="{{ $bike->id }}">
                                        {{ $bike->model }}-{{ $bike->bike_number }}
                                    </option>     
                                    @endforeach 
                                </select> 
                            </div>
    
                            <div class="form-group">
                                    <label>Month:</label>
                                    <input type="text" data-month="{{Carbon\Carbon::parse($maintenance->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                            <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{$maintenance->amount}}">
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
                                <input required type="number" class="form-control @if($errors->has('paid_by_rider')) invalid-field @endif" name="paid_by_rider" placeholder="Enter Amount" value="{{$maintenance->paid_by_rider}}">
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
                                <input required type="number" class="form-control @if($errors->has('paid_by_company')) invalid-field @endif" name="paid_by_company" placeholder="Enter Amount" value="{{$maintenance->paid_by_company}}">
                                @if ($errors->has('paid_by_company'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('paid_by_company')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group col-md-6 pull-right mtr-15">
                                            <div class="custom-file">
                                                <input type="file" name="invoice_image" class="custom-file-input" id="invoice_image">
                                                <label class="custom-file-label" for="invoice_image">Choose Image</label>
                                            </div>
                                            <span class="form-text text-muted">Select Image</span>
                                        </div>    
                                @if($maintenance->invoice_image)
                                        <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($maintenance->invoice_image)) }}" alt="image">
                                    @else
                                        <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <div>
                                    <input data-switch="true" name="status" id="status" type="checkbox" @if($maintenance->status==1) checked @endif data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
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
    
   
    $('[name="amount"]').on("change input",function(){
        var _val=parseFloat($('[name="amount"]').val());
        $('[name="paid_by_company"]').val(_val);
        var _company=parseFloat($('[name="paid_by_company"]').val());
        var _rider=_val-_company;
        $('[name="paid_by_rider"]').val(_rider);
        
        
    });

    $('[name="paid_by_company"]').on("change input",function(){
        var _c_val=$(this).val();
        var _val=parseFloat($('[name="amount"]').val());
        var res_rider=_val-_c_val;
        if (_c_val>_val) {
            console.log("Value is greater");
            $(this).val(_val);  
        }else if(_c_val<0){
            console.log("Value is less");
            $(this).val(0);
        }else{
            $('[name="paid_by_rider"]').val(res_rider);
        }
        
    });
    
    $('[name="paid_by_rider"]').on("change input",function(){
        var _c_val=$(this).val();
        var _val=parseFloat($('[name="amount"]').val());
        var res_comany=_val-_c_val;
        if (_c_val>_val) {
            console.log("Value is greater");
            $(this).val(_val);  
        }else if(_c_val<0){
            console.log("Value is less");
            $(this).val(0);
        }else{
            $('[name="paid_by_company"]').val(res_comany); 
        }
        
    });

$('[name="maintenance_type"]').on("change",function(){
     var _val=$(this).val();1
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