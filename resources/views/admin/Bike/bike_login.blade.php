@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<style>
    .custom-file-label::after{
           color: white;
           background-color: #5578eb;
       }
       .custom-file-label{
        overflow: hidden;
    }
   </style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Create Bike
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('bike.bike_create') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Owner:</label>
                            <select class="form-control @if($errors->has('owner')) invalid-field @endif kt-select2" id="kt_select2_3" name="owner" placeholder="Enter Owner" value="{{ old('owner') }}">
                                <option value="rent">Rental Bike</option>
                                <option value="kr_bike">KR-Bike</option>
                                <option value="self">Rider Own Bike</option>
                            </select> 
                        </div>
                        <div class="all_details_kr">
                            <div class="row rental_bike_details">
                                <div class="form-group col-md-5"> 
                                    <label>Rental Company:</label>
                                    <input type="text" class="form-control" name="rental_company" placeholder="Enter Rental Company Name">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Contract Start Date:</label>
                                    <input type="text" id="datepicker_con_1" autocomplete="off" class="form-control" name="contract_start" placeholder="Enter Contract Start Date">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Contract End Date:</label>
                                    <input type="text" id="datepicker_con_2" autocomplete="off" class="form-control" name="contract_end" placeholder="Enter Contract End Date">
                                </div>
                            </div>
                            <div class="form-group monthly_rent"> 
                                <label>Monthly Rent:</label>
                                <input type="text" class="form-control" name="rent_amount" placeholder="Ente Amount of Rent">
                            </div>
                            <div class="form-group purchase_price"> 
                                <label>Purchase price:</label>
                                <input type="text" class="form-control" id='purchase_price' name="amount" placeholder="Ente Bike Price">
                            </div>
                            <div class="row rider_self_detail">
                                <div class="col-md-9">
                                    <label>Select Rider:</label>
                                    <select class="form-control kt-select2 " id="rider_id" name="rider_id" >
                                        <option value="Select Rider">Select Rider</option>
                                        @foreach ($riders as $rider)
                                        <option value="{{ $rider->id }}" 
                                            >{{ $rider->name }}</option>    
                                        @endforeach
                                    </select>
                                </div> 
                                <div class="form-group col-md-3"> 
                                    <label>Bike Allowns:</label>
                                    <input type="text" class="form-control" id="bike_allowns" name="bike_allowns" placeholder="Ente Bike Allowns">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Model(2015):</label>
                            <select class="form-control @if($errors->has('model')) invalid-field @endif kt-select2" id="kt_select2_3" name="model" placeholder="Enter model" value="{{ old('model') }}">
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    </select> 
                            @if ($errors->has('model'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('model')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Bike Number(etc K-3102):</label>
                            <input type="text" required class="form-control @if($errors->has('bike_number')) invalid-field @endif" name="bike_number" placeholder="Enter Bike_Number (etc K-3102)" value="{{ old('bike_number') }}">
                            @if ($errors->has('bike_number'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('bike_number')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                                <label>Brand(etc Honda):</label>
                                {{-- <input type="text" class="form-control @if($errors->has('brand')) invalid-field @endif" name="brand" placeholder="Enter Brand (etc Honda)" value="{{ old('brand') }}"> --}}
                                <select class="form-control @if($errors->has('brand')) invalid-field @endif kt-select2" id="kt_select2_3" name="brand" placeholder="Enter Brand (etc Honda)" required value="{{ old('brand') }}" >
                                        <option value="Honda Unicorn">Honda Unicorn</option>
                                        <option value="Pulsar">Pulsar</option>
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
                                <input type="text" class="form-control" name="chassis_number" placeholder="Enter Chassis_Number" value="{{ old('chassis_number') }}">
                            </div>
                            <div class="form-group">
                                <label>Insurance Company:</label>
                                <select class="form-control bk-select2 kt-select2" id="insurance_co" name="insurance_co" placeholder="Enter Insurance Company">
                                    @foreach ($insurance_co_name as $item)
                                <option value="{{$item->insurance_co_name}}">{{$item->insurance_co_name}}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group">
                                <label>Issue Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control" name="issue_date" placeholder="Enter Issue Date">
                                <span class="form-text text-muted">Please enter Issue Bike Date</span>
                            </div>
                            <div class="form-group">
                                <label>Expiry Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control" name="expiry_date" placeholder="Enter Expiry Date">
                                <span class="form-text text-muted">Please enter Bike Expiry Date</span>
                            </div>
                        <div class="form-group">
                                <label>Mulkiya Number:</label>
                                <input type="text" class="form-control" name="mulkiya_number" placeholder="Enter Mulkiya Number" value="{{ old('mulkiya_number') }}">
                            </div>
                            <div class="form-group">
                                    <label>Mulkiya Expiry:</label>
                                    <input type="text" id="datepicker" autocomplete="off" class="form-control" name="mulkiya_expiry" placeholder="Enter Mulkiya Expiry" value="{{ old('mulkiya_expiry') }}">
                                </div>
                                <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" name="mulkiya_picture" class="custom-file-input" id="mulkiya_picture">
                                        <label class="custom-file-label" for="mulkiya_picture">Choose Mulkiya Picture</label>
                                        <span class="form-text text-muted">Choose Mulkiya Front Side</span>
                                    </div> 
                                </div></div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" name="mulkiya_picture_back" class="custom-file-input" id="mulkiya_picture_back">
                                        <label class="custom-file-label" for="mulkiya_picture_back">Choose Mulkiya Picture</label>
                                        <span class="form-text text-muted">Choose Mulkiya Back Side</span>
                                    </div>
                                </div></div>
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
                            <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>

        <!--end::Portlet-->
    </div>
</div>
</div>

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(document).ready(function(){
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
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
            // $('#bike_allowns').val('');
          }
          if (opt == 'kr_bike'){
            $(".rental_bike_details").hide();
            $(".rider_self_detail").hide();
            $('.purchase_price').show();
            $('.monthly_rent').show();
            $('[name="rental_company"]').val('');   
            $('[name="contract_start"]').val('');  
            $('[name="contract_end"]').val('');
            // $('#bike_allowns').val('');

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

      $(document).on("keyup",'.select2-search__field',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            var  selected_option=$('.select2-search__field').val();
            if (keycode==13) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    url:"{{url('admin/get/company/insurance/name/')}}",
                    method: "POST",
                    data:{data: selected_option},
                })
                .done(function(data) {  
                    console.log(data);
                    window.location.reload();
                    swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Record updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                });
            }
        });
      $(document).on("change input",'.select2-search__field',function(){
         var  selected_option=$(this).val();
        });
  });

</script>
@endsection