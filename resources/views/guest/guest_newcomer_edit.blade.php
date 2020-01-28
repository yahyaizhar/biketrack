@extends('guest.layouts.app')
@section('page_title')
Apply for riders/driver job
@endsection
@section('main-content')
<style>
.form-group label {
    font-size: 20px;
    color: #57445c;
}
.form-group input {
    font-size: 18px;
    color: #57445c;
}
.form-group select {
    font-size: 18px;
    color: #57445c;
}
.form-group h6{
    font-size: 18px;
    color: #57445c;
}
@media only screen and (max-width:480px){
.form-group input[type='radio'] {
    font-size: 16px !important;
  }
}
</style>



<div class="modal fade" id="mics_charges" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">Check status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                </div>
        </div>
        </div>
    </div>



<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-7 m-auto"> 
        <!--begin::Portlet-->
            <div class="kt-portlet">
                    <img alt="Logo" style="text-align:center;max-width: 200px;margin: 0px auto;" src="https://biketrack.solutionwin.net/dashboard/assets/media/logos/company-logo.png">
                    @include('admin.includes.message')  
                    <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label" style="width:100%;">
                        <h3 class="kt-portlet__head-title" style="text-align:center;width: 100%;">
                            Welcome to king Riderd Delivery Service LLC
                        </h3>
                    </div>
            
                </div>
                <!--begin::Form-->
                <form class="kt-form" action="{{ route('guest.newComer_store',$newcomer_data->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        {{-- @if($newcomer_data->missing_fields) --}}
                        @if (strpos($newcomer_data->missing_fields, 'newcommer_image') !== false)
                            <div class="form-group">
                                <div style="text-align:center;">
                                    <img class="profile-logo img img-thumbnail" src="https://biketrack.solutionwin.net/dashboard/assets/media/users/default.jpg">
                                </div>
                                <div style=" text-align: center;margin-top: 5px; ">
                                <label class="btn btn-info" style=" color: white;">
                                        Upload picture <input name="newcommer_image" id="newcommer_image" type="file" hidden>
                                </label>
                            </div>
                           </div> 
                        @endif
                        @if (strpos($newcomer_data->missing_fields, 'email') !== false)

                        <div class="form-group">
                                <label>Email:</label>
                                <input type="email" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter your name" value="{{$newcomer_data->email}}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('email') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter your email</span>
                                @endif
                        </div>
                        @endif
                        @if (strpos($newcomer_data->missing_fields, 'phone_number') !== false)

                        <div class="form-group">
                            <label>Contact No:</label>
                            <input type="text" class="form-control @if($errors->has('phone_number')) invalid-field @endif" name="phone_number" placeholder="Enter phone number" value="{{$newcomer_data->phone_number}}" required>
                            @if ($errors->has('phone_number'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('phone_number') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your phone number</span>
                            @endif
                        </div>
                        @endif
                        @if (strpos($newcomer_data->missing_fields, 'national_id_card_number') !== false)

                        <div class="form-group">
                            <label>Emirates i'd:</label>
                            <input value="{{$newcomer_data->national_id_card_number}}" type="text" class="form-control" name="national_id_card_number" id="national_id_card_number" placeholder="Enter National id card number" required> 
                        </div>
                        @endif
                        @if (strpos($newcomer_data->missing_fields, 'whatsapp_number') !== false)
                        <div class="form-group">
                            <label>WhatsApp Number:</label>
                            <input type="text" class="form-control @if($errors->has('whatsapp_number')) invalid-field @endif" name="whatsapp_number" placeholder="Whatsapp Number" value="{{$newcomer_data->whatsapp_number}}" required> 
                            @if ($errors->has('whatsapp_number'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('whatsapp_number') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        @endif
                        @if (strpos($newcomer_data->missing_fields, 'license_number') !== false)
                        <div class="form-group license_number">
                                <label>Enter License Number:</label>
                                <input type="text" name="license_number" class="form-control @if($errors->has('licence_number')) invalid-field @endif" id="license_number" autocomplete="off" placeholder="Licence Number" value="{{$newcomer_data->license_number}}">
                        </div>
                        @endif
                        @if (strpos($newcomer_data->missing_fields, 'license_issue_date') !== false)

                        <div class="form-group licence_date">
                            <label>Licence Issue Date:</label>
                            <input type="text" id="licence_date" autocomplete="off" class="form-control @if($errors->has('licence_issue_date')) invalid-field @endif" name="licence_issue_date" placeholder="Licence Issue Date" value="{{$newcomer_data->licence_issue_date}}"> 
                            @if ($errors->has('licence_issue_date'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('licence_issue_date') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        @endif
                        @if (strpos($newcomer_data->missing_fields, 'license_image') !== false)
                        <div class="form-group license_image">
                            <label>License Image:</label>
                            <label class="btn btn-info" style=" color: white;"> Upload picture <input name="license_image" id="license_image" type="file" hidden=""> </label>
                        </div>  
                          @endif
                          @if (strpos($newcomer_data->missing_fields, 'passport_number') !== false)

                        <div class="form-group">
                             <label for="passport_number" class="passport_number_label" >Enter Passport Number:</label>
                           <input type="text"  id="passport_number"  autocomplete="off"class="form-control @if($errors->has('passport_number')) invalid-field @endif" name="passport_number" placeholder="Passport Number" value="{{$newcomer_data->passport_number}}"> 
                        </div>
                        @endif
                        @if (strpos($newcomer_data->missing_fields, 'passport_image') !== false)
                        <div class="form-group">
                           <label for="passport_image" class="passport_image_label" >Passport Image:</label>
                           <label class="btn btn-info" id="passport_image" style="color: white;"> Upload picture <input name="passport_image" type="file" hidden=""> </label>
                       </div>
                       @endif

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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
          <link rel="stylesheet" href="/resources/demos/style.css">
       
          <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $(document).ready(function(){
                $('#datepicker1').fdatepicker({format: 'dd-mm-yyyy'});
                $('#datepicker2').fdatepicker({format: 'dd-mm-yyyy'});
                
            });
        
        </script>
        <script>
        $(document).ready(function(){

//// show hide license info in newcomer form
    function readURL(input) {
    if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
        $('img.profile-logo.img.img-thumbnail').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
        }
    }
    $("#newcommer_image").change(function(){
        readURL(this);
    });

});
        </script>
        <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
        
        <script>
            $(document).ready(function(){
                $('#licence_date').fdatepicker({format: 'dd-mm-yyyy'}); 
                var _h6 = $('input[type="radio"]').siblings('h6');
                $(_h6).css('cursor','pointer');
                $(_h6).click(function(){
                    $(this).siblings('input[type="radio"]').prop( "checked", true );
                    $(this).siblings('input[type="radio"]').change();
                })
               
             });
        </script>
@endsection