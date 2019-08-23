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
                            ID charges
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.accounts.id_charges_post') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">

                        <div class="form-group">
                            <label>Rider:</label>
                            <select required class="form-control kt-select2-general" name="rider_id" >
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                                
                        </div>
                        
                        <div class="form-group">
                            <label>Type:</label>
                            {{-- <input  autocomplete="off" list="model" class="form-control @if($errors->has('model')) invalid-field @endif" name="model"  > --}}
                            <select required class="form-control @if($errors->has('model')) invalid-field @endif kt-select2-general" name="type">
                                <option value="visa">Visa</option>
                                <option value="new_licence">New Licence</option>
                                <option value="rta_card">RTA Card</option>
                                <option value="emirates_id">Emirates ID</option>
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
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" checked="checked" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="kt-portlet__body">
                            <h1>Bike Registration Detail</h1>
                            <div class="form-group">
                                    <label>Registration Number:</label>
                                    <input type="text" class="form-control @if($errors->has('registration_number')) invalid-field @endif" name="registration_number" placeholder="Registration Number" value="{{ old('registration_number') }}">
                                    @if ($errors->has('registration_number'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{$errors->first('registration_number')}}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                               
                    </div> --}}

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
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 

  });

</script>
@endsection