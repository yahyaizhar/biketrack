@extends('admin.layouts.app')
@section('main-content')
<style>
#passport_status_no{
    border: 1px solid rgb(221, 221, 221);
    padding: 14px 0px;
}
     .icon_change_password{
         position: absolute;
        top: 35%;
        right: 1%;
        color: #5578eb;
        font-size: 20px;
    }
    .icon_change_password_confirmation{
         position: absolute;
        top: 48%;
        right: 1%;
        color: #5578eb;
        font-size: 20px;
    }
    .custom-file-label{
        overflow: hidden;
    }
    .custom-file-label::after{
        color: white;
        background-color: #5578eb;
    }
    .streric{
        color: red;
    }
</style>
<!-- begin:: Content -->
<div class="progress-bar sticky-top" id="myBar" style="height: 10px;background: #4caf50;width: 0%;"></div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label" >
                            
                        <h3 class="kt-portlet__head-title">
                            Create Rider
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.riders.store') }}" method="POST" enctype="multipart/form-data" >
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                                
                        <div class="form-group">
                            <label>Full Name: <span class="streric">*</span></label>
                            <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter full name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('name') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your full name</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Email address: <span class="streric">*</span></label>
                            <input type="email" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('email') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">We'll never share your email with anyone else</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Personal Phone Number:</label>
                            <input type="text" class="form-control " name="phone" placeholder="Enter phone number" value="{{ old('phone') }}">
                            <span class="form-text text-muted">Please enter your phone number</span>
                           
                        </div>
                        <div class="form-group"> 
                            <label>Date Of Birth:</label>
                            <input type="text" id="date_of_birth" autocomplete="off" class="form-control" name="date_of_birth" placeholder="Enter Date Of Birth" >
                                <span class="form-text text-muted">Please enter your Date Of Birth</span>
                           
                        </div>
                        <div class="form-group">
                            <label>Password: <span class="streric">*</span></label>
                            <input type="password" class="form-control @if($errors->has('passsword')) invalid-field @endif" name="password" placeholder="Enter password">
                            @if ($errors->has('password'))
                                <span class="invalid-response" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your password</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Confirm Password: <span class="streric">*</span></label>
                            <input type="password" class="form-control @if($errors->has('passsword')) invalid-field @endif" name="password_confirmation" placeholder="Enter confirm password">
                        </div>
                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" class="form-control" name="address" placeholder="Enter city">
                        </div>
                        <div class="form-group">
                                <label>Rider Active Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control @if($errors->has('active_month')) invalid-field @endif" name="active_month" placeholder="Enter Month" value="">
                            </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label>Start Time:</label>
                                    <input type="hidden" id="start_timer1" name="start_time">
                                    <input type="text" autocomplete="off" id="timepicker1" class="form-control"  placeholder="Start Time" value="{{ old('start_time') }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>End Time:</label>
                                        <input type="hidden" id="start_timer2" name="end_time">
                                        <input type="text" autocomplete="off" id="timepicker2" class="form-control"  placeholder="End Time" value="{{ old('end_time') }}">
                                    </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Break Start Time:</label>
                                        <input type="hidden" id="start_timer3" name="break_start_time">
                                        <input type="text" autocomplete="off" id="timepicker3" class="form-control"  placeholder="Break Start Time" value="{{ old('break_start_time') }}">
                                    </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Break End Time:</label>
                                        <input type="hidden" id="start_timer4" name="break_end_time">
                                        <input type="text" autocomplete="off" id="timepicker4" class="form-control"  placeholder="Break End Time" value="{{ old('break_end_time') }}">
                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <label>Profile Picture</label>
                                <input type="file" name="profile_picture" class="custom-file-input" id="profile_picture">
                                <label class="custom-file-label" for="profile_picture">Choose Picture</label>
                                <span class="form-text text-muted">Select Profile Picture</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Date of Joining:</label>
                            <input type="text" id="datepicker1" autocomplete="off" class="form-control" name="date_of_joining" placeholder="Enter Joining Date">
                        </div>
                        <div class="form-group">
                                <label>Salary:</label>
                                <input type="text" class="form-control" name="salary" placeholder="Enter An Amount">
                            </div>
                            
                            <div class="form-group">
                                
                                 <input type="hidden" class="form-control " name="salik_amount" placeholder="Enter A Salik Amount" value="50">
                            </div>
                        <div class="form-group">
                            <label>Is Passport Collected:</label>
                            <div>
                                <input data-switch="true" name="passport_collected" id="passport_collected" type="checkbox" checked="checked" data-on-text="Yes" data-handle-width="70" data-off-text="No" data-on-color="brand">
                            </div>
                        </div>
                          
                        <div class="row" id="passport_status_no">
                            <div class="col-lg-4 col-md-4 col-sm-12"> 
                                <div class="form-group">
                                    <label class="kt-radio">
                                        <input type="radio"  data-depended=".is_guarantee__employee" name="is_guarantee" value="employee"> Employee Reference
                                        <span></span>
                                    </label>
                                    <div class="is_guarantee__employee dependend-field">
                                        @php
                                            $riders=App\Model\Rider\Rider::where("active_status","A")->where("status","1")->get();
                                        @endphp
                                        <select id="empoloyee_reference" class="form-control   kt-select2" id="kt_select2_3" name="empoloyee_reference" placeholder="Enter Employee Reference" value="{{ old('empoloyee_reference') }}">
                                            @foreach ($riders as $rider)
                                            <option value="{{$rider->id}}">{{$rider->name}}</option>
                                            @endforeach
                                        </select> 
                                        <span class="form-text text-muted">Who referred this rider?</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12"> 
                                <div class="form-group">
                                    <label class="kt-radio">
                                        <input type="radio" data-depended=".is_guarantee__outsider" name="is_guarantee" value="outsider"> Someone else passport
                                        <span></span>
                                    </label>
                                    <textarea type="text" rows="5" autocomplete="off" class="dependend-field is_guarantee__outsider form-control " name="other_passport_given" placeholder="Other person detail" ></textarea>
                                    <span class="form-text text-muted is_guarantee__outsider dependend-field">Where that person works?</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12"> 
                                <div class="form-group">
                                    <label class="kt-radio">
                                        <input type="radio" data-depended=".is_guarantee__not_given" name="is_guarantee" value="not_given"> Not given
                                        <span></span>
                                    </label>
                                    <textarea type="text" rows="5" autocomplete="off" class="dependend-field is_guarantee__not_given form-control " name="not_given" placeholder="Reason" ></textarea>
                                    <span class="form-text text-muted is_guarantee__not_given dependend-field">Why not?</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Passport Image:</label>
                                    <div class="custom-file">
                                        <input type="file" name="passport_document_image" class="custom-file-input" id="passport_document_image">
                                        <label class="custom-file-label" for="passport_document_image">Choose Referral Passport Image</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Agreement Image:</label>
                                    <div class="custom-file">
                                        <input type="file" name="agreement_image" class="custom-file-input" id="agreement_image">
                                        <label class="custom-file-label" for="agreement_image">Choose Agreement Image</label>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label>Passport Number:</label>
                            <input type="text" class="form-control" name="passport_number" placeholder="Enter Passport Number">
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Passport Expiry:</label>
                                <input type="text" id="datepicker3" autocomplete="off" class="form-control @if($errors->has('passport_expiry')) invalid-field @endif" name="passport_expiry" placeholder="Enter Passport Expiry">
                                <span class="form-text text-muted">Please enter your Passport Expiry Date</span>
                            </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <div class="custom-file" style="    margin-top: 26px;">
                                    <input type="file" name="passport_image" class="custom-file-input" id="passport_image">
                                    <label class="custom-file-label" for="passport_image">Choose Passport Picture</label>
                                    <span class="form-text text-muted">Choose Passport Front Image</span>
                                </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Visa Expiry:</label>
                                    <input type="text" id="datepicker4" autocomplete="off" class="form-control @if($errors->has('visa_expiry')) invalid-field @endif" name="visa_expiry" placeholder="Enter Visa Expiry">
                                     <span class="form-text text-muted">Please enter your Visa Expiry Date</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file" style="    margin-top: 26px;">
                                        <input type="file" name="visa_image" class="custom-file-input" id="visa_image">
                                        <label class="custom-file-label" for="visa_image">Choose Visa Picture</label>
                                        <span class="form-text text-muted">Choose Visa Front Image</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Emirates ID:</label>
                                    <input type="text"  class="form-control" name="emirate_id" placeholder="Enter Emirate ID">
                                    <span class="form-text text-muted">Please enter your Emirate ID.</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file" style="    margin-top: 26px;">
                                        <input type="file" name="emirate_image" class="custom-file-input" id="emirate_image">
                                        <label class="custom-file-label" for="emirate_image">Choose Emirates Picture</label>
                                        <span class="form-text text-muted">Choose Emirate Front Image</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file" style="    margin-top: 26px;">
                                        <input type="file" name="emirate_image_back" class="custom-file-input" id="emirate_image_back">
                                        <label class="custom-file-label" for="emirate_image_back">Choose Emirates Picture</label>
                                        <span class="form-text text-muted">Choose Emirate Back Image</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Licence Expiry:</label>
                                    <input type="text" id="datepicker5" autocomplete="off" class="form-control @if($errors->has('licence_expiry')) invalid-field @endif" name="licence_expiry" placeholder="Enter Licence Expiry">
                                    <span class="form-text text-muted">Please enter your Licence Expiry Date</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file" style="    margin-top: 26px;">
                                        <input type="file" name="licence_image" class="custom-file-input" id="licence_image">
                                        <label class="custom-file-label" for="licence_image">Choose Licence Picture</label>
                                        <span class="form-text text-muted">Choose Licence Front Image</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <div class="custom-file" style="    margin-top: 26px;">
                                        <input type="file" name="licence_image_back" class="custom-file-input" id="licence_image_back">
                                        <label class="custom-file-label" for="licence_image_back">Choose Licence Picture</label>
                                        <span class="form-text text-muted">Choose Licence Back Image</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Other Details:</label>
                            <textarea type="text"  rows="8" autocomplete="off" class="form-control @if($errors->has('other_details')) invalid-field @endif" name="other_details" placeholder="Enter Further Details" ></textarea>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    $(document).ready(function(){

        
        $("[name='password']").after('<div class="icon_change_password" data-target="password"><i class="fa fa-eye for_password"></i></div>');
        $(".icon_change_password ").parent().addClass("position-relative");
        $(".icon_change_password").on("click",function(){
            if ($("[name='password']").attr("type")=="password") {
                $("[name='password']").attr("type","text");
                $(".for_password").removeClass("fa fa-eye ");
                $(".for_password").addClass("fa fa-eye-slash");
            }
            else if($("[name='password']").attr("type")=="text"){
                $("[name='password']").attr("type","password");
                $(".for_password").removeClass("fa fa-eye-slash ");
                $(".for_password").addClass("fa fa-eye");
            } 
        });
        $("[name='password_confirmation']").after('<div class="icon_change_password_confirmation" data-target="password"><i class="fa fa-eye for_password_confirmation"></i></div>');
        $(".icon_change_password_confirmation").parent().addClass("position-relative");
        $(".icon_change_password_confirmation").on("click",function(){
            if ($("[name='password_confirmation']").attr("type")=="password") {
                $("[name='password_confirmation']").attr("type","text");
                $(".for_password_confirmation").removeClass("fa fa-eye ");
                $(".for_password_confirmation").addClass("fa fa-eye-slash");
            }
            else if($("[name='password_confirmation']").attr("type")=="text"){
                $("[name='password_confirmation']").attr("type","password");
                $(".for_password_confirmation").removeClass("fa fa-eye-slash ");
                $(".for_password_confirmation").addClass("fa fa-eye");
            } 
        });


        $('.dependend-field').hide('fast');
        $(':radio[data-depended]').on('change', function(){
            $('[name="passport_document_image"]')
                .siblings('.custom-file-label')
                .text('Choose Passport Image')
                .parents('.custom-file')
                .siblings('label')
                .text('Passport Image');
            var _dependend = $(this).attr('data-depended');
            if($(this).val().trim()=='not_given'){
                $('[name="passport_document_image"]')
                .siblings('.custom-file-label')
                .text('Choose Document Image')
                .parents('.custom-file')
                .siblings('label')
                .text('Document Image');
            }
            $('.dependend-field').hide('fast');
            $(_dependend).fadeIn('fast');
        });
        $('#timepicker1').change(function(){
        var a = $('#timepicker1').val();
        var getUTC_date=new Date(a.toDate('h:m')).format('HH:MM',true);
        $("#start_timer1").val(getUTC_date);
        });
    
        $('#timepicker2').change(function(){
        var a = $('#timepicker2').val();
        var getUTC_date=new Date(a.toDate('h:m')).format('HH:MM',true);
        $("#start_timer2").val(getUTC_date);
        });
    
        $('#timepicker3').change(function(){
        var a = $('#timepicker3').val();
        var getUTC_date=new Date(a.toDate('h:m')).format('HH:MM',true);
        $("#start_timer3").val(getUTC_date);
        });
    
        $('#timepicker4').change(function(){
        var a = $('#timepicker4').val();
        var getUTC_date=new Date(a.toDate('h:m')).format('HH:MM',true);
        $("#start_timer4").val(getUTC_date);
        });
        
     
    
    });
    </script>

 
{{-- timepicker --}}
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
 {{-- end time picker --}}
 <script>
      $(document).ready(function(){
        $('.kt-select2').select2({
            placeholder: "Select an option",
            width:'100%'    
        });
        $('#timepicker1').fdatepicker({ format: 'hh:ii',startView:1,maxView:0,pickTime: true,});
        $('#timepicker2').fdatepicker({ format: 'hh:ii',startView:1,maxView:0,pickTime: true});
        $('#timepicker3').fdatepicker({ format: 'hh:ii',startView:1,maxView:0,pickTime: true});
        $('#timepicker4').fdatepicker({ format: 'hh:ii',startView:1,maxView:0,pickTime: true});
	 });
 </script>
<script>
    $(document).ready(function(){
        $('#datepicker1').fdatepicker({format: 'dd-mm-yyyy'}); 
        $('#datepicker2').fdatepicker({format: 'dd-mm-yyyy'}); 
        $('#datepicker3').fdatepicker({format: 'dd-mm-yyyy'}); 
        $('#datepicker4').fdatepicker({format: 'dd-mm-yyyy'}); 
        $('#datepicker5').fdatepicker({format: 'dd-mm-yyyy'}); 
        $('#datepicker6').fdatepicker({format: 'dd-mm-yyyy'}); 
        $('#date_of_birth').fdatepicker({format: 'dd-mm-yyyy'}); 
     });
</script>  
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function(){
window.onscroll = function() {myFunction()};
function myFunction() {
  var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var scrolled = (winScroll / height) * 100;
  document.getElementById("myBar").style.width = scrolled + "%";
}

});
</script>
<script>
$(document).ready(function(){
    $("#passport_status_no").hide();
    $("#passport_collected").on("switchChange.bootstrapSwitch",function(){
       var a=$("#passport_collected").attr("data-off-text");
       var _checked=$(this).prop("checked");
       if (_checked==false) {
        $("#passport_status_no").show().fadeIn(3000);
        
       }
        if(_checked==true){
        $("#passport_status_no").hide().fadeOut(3000);
       }
        
    }); 
});
</script>

@endsection