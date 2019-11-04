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
                            Edit Bike Information
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('Bike.bike_update', $bike->id) }}" method="post" enctype="multipart/form-data">
                    {{-- {{ method_field('PUT') }} --}}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Owner:</label>
                                    <select class="form-control bk-select2 @if($errors->has('owner')) invalid-field @endif kt-select2" id="kt_select2_3" name="owner" placeholder="Enter Owner" value="{{ old('owner') }}">
                                        <option @if ($bike->owner=="rent") selected @endif  value="rent">Rental Bike</option>
                                        <option @if ($bike->owner=="kr_bike") selected @endif  value="kr_bike">KR-Bike</option>
                                        <option @if ($bike->owner=="self") selected @endif value="self">Rider Own Bike</option>
                                    </select> 
                                </div>
                                <div class="all_details_kr">
                                    <div class="row rental_bike_details">
                                        <div class="form-group col-md-5"> 
                                            <label>Rental Company:</label>
                                        <input type="text" class="form-control" name="rental_company" placeholder="Enter Rental Company Name" value="{{$bike->rental_company}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Contract Start Date:</label>
                                        <input type="text" id="datepicker_con_1" autocomplete="off" class="form-control" name="contract_start" placeholder="Enter Contract Start Date" value="{{$bike->contract_start}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Contract End Date:</label>
                                            <input type="text" id="datepicker_con_2" autocomplete="off" class="form-control" name="contract_end" placeholder="Enter Contract End Date" value="{{$bike->contract_end}}">
                                        </div>
                                    </div>
                                    <div class="form-group monthly_rent"> 
                                        <label>Monthly Rent:</label>
                                        <input type="text" class="form-control" name="rent_amount" placeholder="Ente Amount of Rent" value="{{$bike->rent_amount}}">
                                    </div>
                                    <div class="form-group purchase_price"> 
                                        <label>Purchase price:</label>
                                        <input type="text" class="form-control" id="purchase_price" name="amount" placeholder="Ente Bike Price" value="{{$bike->amount}}">
                                    </div>
                                    <div class="row rider_self_detail">
                                        <div class="col-md-9">
                                            <label>Select Rider:</label>
                                            <select class="form-control kt-select2 bk-select2" id="rider_id" name="rider_id" >
                                                <option value="Select Rider">Select Rider</option>
                                                @foreach ($riders as $rider)
                                                <option @if ($bike->rider_id==$rider->id) selected @endif value="{{ $rider->id }}" 
                                                    >{{ $rider->name }}</option>    
                                                @endforeach
                                            </select>
                                        </div> 
                                        <div class="form-group col-md-3"> 
                                            <label>Bike Allowns:</label>
                                            <input type="text" class="form-control" id="bike_allowns" name="bike_allowns" placeholder="Ente Bike Allowns" value="{{$bike->bike_allowns}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Model(2015):</label>
                                    {{-- <input type="text" class="form-control @if($errors->has('model')) invalid-field @endif" name="model" placeholder="Model" value="{{ $bike->model }}"> --}}
                                    <select class="form-control @if($errors->has('model')) invalid-field @endif kt-select2" id="kt_select2_3" name="model" placeholder="Enter model" >
                                            <option @if ($bike->model=="2010") selected @endif value="2010">2010</option>
                                            <option @if ($bike->model=="2011") selected @endif value="2011">2011</option>
                                            <option @if ($bike->model=="2012") selected @endif value="2012">2012</option>
                                            <option @if ($bike->model=="2013") selected @endif value="2013">2013</option>
                                            <option @if ($bike->model=="2014") selected @endif value="2014">2014</option>
                                            <option @if ($bike->model=="2015") selected @endif value="2015">2015</option>
                                            <option @if ($bike->model=="2016") selected @endif value="2016">2016</option>
                                            <option @if ($bike->model=="2017") selected @endif value="2017">2017</option>
                                            <option @if ($bike->model=="2018") selected @endif value="2018">2018</option>
                                            <option @if ($bike->model=="2019") selected @endif value="2019">2019</option>
                                            </select>
                                    @if ($errors->has('model'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('model') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter model name</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Bike Number(etc k-3102):</label>
                                    <input type="text" required class="form-control @if($errors->has('bike_number')) invalid-field @endif" name="bike_number" placeholder="Enter Bike Number (etc K-3102)" value="{{ $bike->bike_number }}">
                                
                                    @if ($errors->has('bike_number'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('bike_number') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">We Want your Bike Number</span>
                                    @endif
                                </div> 
                          
                        <div class="form-group">
                                <label>Brand(etc Honda):</label> 
                                {{-- <input type="text" class="form-control @if($errors->has('brand')) invalid-field @endif" name="brand" placeholder="Enter Brand (etc Honda)" value="{{ $bike->brand }}"> --}}
                                <select class="form-control  @if($errors->has('brand')) invalid-field @endif kt-select2" id="kt_select2_3" name="brand" placeholder="Enter Brand (etc Honda)" >
                                        <option value="Honda Unicorn" @if ($bike->brand=="Honda Unicorn") selected @endif>Honda Unicorn</option>
                                        <option value="Pulsar" @if ($bike->brand=="Pulsar") selected @endif>Pulsar</option>
                                        </select> 
                                @if ($errors->has('brand'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('brand')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                    <label>Chassis Number:</label>
                                    <input type="text"  class="form-control " name="chassis_number" placeholder="Enter Chassis Number" value="{{ $bike->chassis_number }}">
                                    <span class="form-text text-muted">Enter Your Chassis Number.</span>
                                </div> 
                      
                        <div class="form-group">
                                <label>Mulkiya Number:</label>
                                <input type="text"  class="form-control" name="mulkiya_number" placeholder="Enter Mulkiya Nnumber" value="{{ $bike->mulkiya_number }}">
                               
                            </div> 
                            <div class="form-group">
                                    <label>Mulkiya Expiry:</label>
                                    <input type="text" id="datepicker" autocomplete="off" class="form-control " name="mulkiya_expiry" placeholder="Enter Mulkiya Expiry" value="{{ $bike->mulkiya_expiry}}">
                                   
                                </div>
                        </div>
                           
                        </div>
                        <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group col-md-6 pull-right mtr-15">
                                        <div class="custom-file">
                                            <input type="file" name="mulkiya_picture" class="custom-file-input" id="mulkiya_picture">
                                            <label class="custom-file-label" for="mulkiya_picture">Choose Mulkiya Picture</label>
                                        </div>
                                        <span class="form-text text-muted">Select Mulkiya Front Picture</span>
                                    </div>    
                            @if($bike->mulkiya_picture)
                                    <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($bike->mulkiya_picture)) }}" alt="image">
                                @else
                                    <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group col-md-6 pull-right mtr-15">
                                        <div class="custom-file">
                                            <input type="file" name="mulkiya_picture_back" class="custom-file-input" id="mulkiya_picture_back">
                                            <label class="custom-file-label" for="mulkiya_picture_back">Choose Mulkiya Picture</label>
                                        </div>
                                        <span class="form-text text-muted">Select Mulkiya Back Picture</span>
                                    </div>    
                            @if($bike->mulkiya_picture_back)
                                    <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($bike->mulkiya_picture_back)) }}" alt="image">
                                @else
                                    <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                               
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" {!! $bike->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                        
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span> --}}
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
<script>
    $(document).ready(function () {
        if($('#change-password').prop('checked') == true)
        {
            $('#password-fields').show();
        }
        $('#change-password').change(function () {
            $('#password-fields').fadeToggle();
        });
    });
</script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">

 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script>
   $(document).ready(function(){
    $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
    $('#datepicker_con_1').fdatepicker({format: 'dd-mm-yyyy'}); 
      $('#datepicker_con_2').fdatepicker({format: 'dd-mm-yyyy'});
      $(".purchase_price").hide();
      $(".monthly_rent").hide();
      $(".rider_self_detail").hide();
      $('#kt_select2_3').on('change',function(){
          var opt=$(this).val();
          if (opt == 'rent'){
            $(".rental_bike_details").show();
            $('.monthly_rent').show();
            $('.purchase_price').hide();
            $(".rider_self_detail").hide();
            $('#purchase_price').val('');
            $('#bike_allowns').val('');
          }
          if (opt == 'kr_bike'){
            $(".rental_bike_details").hide();
            $(".rider_self_detail").hide();
            $('.purchase_price').show();
            $('.monthly_rent').show();
            $('[name="rental_company"]').val('');   
            $('[name="contract_start"]').val('');  
            $('[name="contract_end"]').val('');
            $('#bike_allowns').val('');

          }
          if (opt == 'self'){
            $(".rental_bike_details").hide();
            $('.purchase_price').hide();
            $('.monthly_rent').hide();
            $(".rider_self_detail").show();
            $('[name="rental_company"]').val('');   
            $('[name="contract_start"]').val('');  
            $('[name="contract_end"]').val('');
            $('[name="rent_amount"]').val('');
            $('#purchase_price').val('');
          }
      });
      $('#kt_select2_3').trigger('change');
   });

</script>
@endsection