@extends('admin.layouts.app')
@section('main-content')
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
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Create Bike
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('bike.bike_create') }}" method="POST" enctype="multipart/form-data" id="add_bikeForm">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Owner:</label>
                            <select class="form-control @if($errors->has('owner')) invalid-field @endif kt-select2 bk-select2" id="kt_select2_3" name="owner" placeholder="Enter Owner" value="{{ old('owner') }}">
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
                                    <select class="form-control kt-select2 bk-select2" id="rider_id" name="rider_id" >
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
                            <select class="form-control @if($errors->has('model')) invalid-field @endif kt-select2 bk-select2" id="kt_select2_3" name="model" placeholder="Enter model" value="{{ old('model') }}">
                                    {{-- <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option> --}}
                                    @for ($i = 0; $i <= 20; $i++)
                                    @php
                                    $_m =Carbon\Carbon::now()->addYear(-$i); 
                                    @endphp
                                    <option value="{{$_m->format('Y')}}">{{$_m->format('Y')}}</option> 
                                    @endfor 
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
                                <select class="form-control @if($errors->has('brand')) invalid-field @endif kt-select2 bk-select2" id="kt_select2_3" name="brand" placeholder="Enter Brand (etc Honda)" required value="{{ old('brand') }}" >
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
                                <select class="form-control select2-dynamic" id="insurance_co" name="insurance_co" placeholder="Enter Insurance Company">
                                    @foreach ($insurance_co_name as $item)
                                        <option value="{{$item->insurance_co_name}}">{{$item->insurance_co_name}}</option>
                                    @endforeach
                                </select> 
                                <span class="form-text text-muted">Enter to save new Insurance company</span>
                            </div>
                            <div class="form-group">
                                <label>Insurance Issue Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control" name="issue_date" placeholder="Enter Issue Date">
                                <span class="form-text text-muted">Please enter Issue Bike Date</span>
                            </div>
                            <div class="form-group">
                                <label>Insurance Expiry Date:</label>
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
            </div>
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
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
      $('#datepicker_con_1').fdatepicker({format: 'M d, yyyy'}); 
      $('#datepicker_con_2').fdatepicker({format: 'M d, yyyy'});
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
        $('.select2-dynamic').select2({
                tags:true,
                placeholder: 'Select an option'
            });
            $('.select2-dynamic').on('change.select2', function(){
                var _select2 =$(this);
                var selected = $(this).find(':selected');
                if(typeof selected.attr('data-select2-tag') !=="undefined" && selected.attr('data-select2-tag')=='true' ){
                    var formData = new FormData();
                    formData.append('data', selected.text());
                    $.ajax({
                        url:'{{url('admin/get/company/insurance/name/')}}',
                        type:'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:formData,
                        cache:false,
                        processData:false,
                        contentType:false,
                    }).done(function(investor){
                        selected.removeAttr('data-select2-tag');
                        selected.val(investor.id);
                        _select2.trigger('change');
                        // init_table();
                        
                    }).fail(function( jqXHR, textStatus,errorThrown ) {
                        console.log(jqXHR);
                    });
                }
            })
    //   $(document).on("keyup",'.select2-search__field',function(event){
    //         var keycode = (event.keyCode ? event.keyCode : event.which);
    //         var  selected_option=$('.select2-search__field').val();
    //         if($('.select2-results__options').find('.select2-results__message').length >0){ 
    //             $('.select2-results__message').text('No results found! Click Enter to save Data');
    //         }
    //         if (keycode==13) { 
    //             $.ajax({
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //                 }, 
    //                 url:"{{}}",
    //                 method: "POST",
    //                 data:{data: selected_option},
    //             })
    //             .done(function(data) {  
    //                 console.log(data);
    //                 // window.location.reload();
    //                 var option_html= '<option value="'+data.insurance_co_name.insurance_co_name+'">'+data.insurance_co_name.insurance_co_name+'</option>';
    //                 $('[name="insurance_co"]').append(option_html);
    //                 $('[name="insurance_co"]').select2('close');
    //                 $('[name="insurance_co"]').val(data.insurance_co_name.insurance_co_name).trigger("change");
    //             });
    //         }
    //     });
      $(document).on("change input",'.select2-search__field',function(){
         var  selected_option=$(this).val();
        });

        $('#add_bikeForm [name="bike_number"]').on('input change', function(){
            var _val= $(this).val();
            var validated_val = _val.match( /[ ]{0,1}[\d]/g ).join([]);
            $(this).val(validated_val);
        });
  });

</script>
@endsection