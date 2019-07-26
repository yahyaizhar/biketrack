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
                            Edit Rider Information
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.riders.update', $rider->id) }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                
                                <div class="form-group">
                                    <label>Full Name:</label>
                                    <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter full name" value="{{ $rider->name }}">
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
                                    <label>Email address:</label>
                                    <input type="email" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter email" value="{{ $rider->email }}">
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
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                @if($rider->profile_picture)
                                    <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="image">
                                @else
                                    <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                                <div class="form-group col-md-6 pull-right mtr-15">
                                    <div class="custom-file">
                                        <input type="file" name="profile_picture" class="custom-file-input" id="profile_picture">
                                        <label class="custom-file-label" for="profile_picture">Choose Picture</label>
                                    </div>
                                    <span class="form-text text-muted">Select if you want to update picture</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="text" class="form-control @if($errors->has('phone')) invalid-field @endif" name="phone" placeholder="Enter phone number" value="{{ $rider->phone }}">
                            @if ($errors->has('phone'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('phone') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your phone number</span>
                            @endif
                        </div>
                        
                        <label class="kt-checkbox">
                            <input id="change-password" name="change_password" type="checkbox" {{ old('change_password') ? 'checked' : '' }}> Change Password
                            <span></span>
                        </label>
                        <div id="password-fields" style="display:none;">
                            <div class="form-group">
                                <label>Password:</label>
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
                                <label>Confirm Password:</label>
                                <input type="password" class="form-control @if($errors->has('passsword')) invalid-field @endif" name="password_confirmation" placeholder="Enter confirm password">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" class="form-control @if($errors->has('address')) invalid-field @endif" name="address" placeholder="Enter city" value="{{ $rider->address }}">
                            @if ($errors->has('address'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('address')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                      
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                             <div class="form-group">
                                 <label>Start Time:</label>
                                 <input type="hidden" id="start_timer1" name="start_time" value="{{ $rider->start_time }}">
                                 <input type="text" autocomplete="off" id="timepicker1" class="form-control @if($errors->has('start_time')) invalid-field @endif" placeholder="Start Time" value="{{ $rider->start_time }}">
                                 @if ($errors->has('start_time'))
                                     <span class="invalid-response" role="alert">
                                         <strong>
                                             {{$errors->first('start_time')}}
                                         </strong>
                                     </span>
                                 @endif
                             </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                             <div class="form-group">
                                 <label>End Time:</label>
                                 <input type="hidden" id="start_timer2" name="end_time" value="{{ $rider->end_time }}">
                                 <input type="text" autocomplete="off" id="timepicker2" class="form-control @if($errors->has('end_time')) invalid-field @endif"  placeholder="End Time" value="{{ $rider->end_time }}">
                                 @if ($errors->has('end_time'))
                                     <span class="invalid-response" role="alert">
                                         <strong>
                                             {{$errors->first('end_time')}}
                                         </strong>
                                     </span>
                                 @endif
                             </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                             <div class="form-group">
                                 <label>Break Start Time:</label>
                                 <input type="hidden" id="start_timer3" name="break_start_time" value="{{ $rider->break_start_time }}">
                                 <input type="text" autocomplete="off" id="timepicker3" class="form-control @if($errors->has('break_start_time')) invalid-field @endif"  placeholder="Break Start Time" value="{{ $rider->break_start_time }}">
                                 @if ($errors->has('break_start_time'))
                                     <span class="invalid-response" role="alert">
                                         <strong>
                                             {{$errors->first('break_start_time')}}
                                         </strong>
                                     </span>
                                 @endif
                             </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                             <div class="form-group">
                                 <label>Break End Time:</label>
                                 <input type="hidden" id="start_timer4" name="break_end_time" value="{{ $rider->break_end_time }}">
                                 <input type="text" autocomplete="off" id="timepicker4" class="form-control @if($errors->has('break_end_time')) invalid-field @endif"  placeholder="Break End Time" value="{{ $rider->break_end_time }}">
                                 @if ($errors->has('break_end_time'))
                                     <span class="invalid-response" role="alert">
                                         <strong>
                                             {{$errors->first('break_end_time')}}
                                         </strong>
                                     </span>
                                 @endif
                             </div>
                            </div>
                             </div>
                      
            
                      <div class="form-group">
                                <label>Date of Joining:</label>
                                <input type="text" id="datepicker1" autocomplete="off" class="form-control @if($errors->has('date_of_joining')) invalid-field @endif" name="date_of_joining" placeholder="Enter joining Date" value="{{ $rider_detail->date_of_joining }}">
                                @if ($errors->has('date_of_joining'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('date_of_joining') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter your Joining Date</span>
                                @endif
                            </div>
                            <div class="form-group">
                                    <label>Official Given Number:</label>
                                    <input type="text" class="form-control @if($errors->has('official_given_number')) invalid-field @endif" name="official_given_number" placeholder="Enter official number" value="{{ $rider_detail->official_given_number }}">
                                    @if ($errors->has('official_given_number'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('official_given_number') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter your official phone number</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                        <label>Official Sim Given Date:</label>
                                        <input type="text" id="datepicker2" autocomplete="off" class="form-control @if($errors->has('official_sim_given_date')) invalid-field @endif" name="official_sim_given_date" placeholder="Enter official sim given Date" value="{{ $rider_detail->official_sim_given_date }}">
                                        @if ($errors->has('official_sim_given_date'))
                                            <span class="invalid-response" role="alert">
                                                <strong>
                                                    {{ $errors->first('official_sim_given_date') }}
                                                </strong>
                                            </span>
                                        @else
                                            <span class="form-text text-muted">Please enter your Official Sim Date</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                            <label>Is Passport Collected:</label>
                                            <div>
                                                <input data-switch="true" name="passport_collected" id="passport_collected" type="checkbox" {!! $rider_detail->passport_collected ==  'yes' ? 'checked' : '' !!} data-on-text="yes" data-handle-width="70" data-off-text="no" data-on-color="brand">
                                            </div>
                                            
                                        </div>
                                    <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                            <label>Passport Expiry:</label>
                                                            <input type="text" id="datepicker3" autocomplete="off" class="form-control @if($errors->has('passport_expiry')) invalid-field @endif" name="passport_expiry" placeholder="Enter Passport Expiry" value="{{ $rider_detail->passport_expiry }}">
                                                            @if ($errors->has('passport_expiry'))
                                                                <span class="invalid-response" role="alert">
                                                                    <strong>
                                                                        {{ $errors->first('passport_expiry') }}
                                                                    </strong>
                                                                </span>
                                                            @else
                                                                <span class="form-text text-muted">Please enter your Passport Expiry Date</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                            @if($rider_detail->passport_image)
                                                <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_detail->passport_image)) }}" alt="image">
                                            @else
                                                <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                            @endif
                                            <div class="form-group col-md-6 pull-right mtr-15">
                                                <div class="custom-file">
                                                    <input type="file" name="passport_image" class="custom-file-input" id="passport_image">
                                                    <label class="custom-file-label" for="passport_image">Choose Passport Picture</label>
                                                </div>
                                                <span class="form-text text-muted">Select if you want to update Passport picture</span>
                                            </div>
                                        </div>
                                    </div> <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                            <label>Visa Expiry:</label>
                                                            <input type="text" id="datepicker4" autocomplete="off" class="form-control @if($errors->has('visa_expiry')) invalid-field @endif" name="visa_expiry" placeholder="Enter Visa Expiry" value="{{ $rider_detail->visa_expiry }}">
                                                            @if ($errors->has('visa_expiry'))
                                                                <span class="invalid-response" role="alert">
                                                                    <strong>
                                                                        {{ $errors->first('visa_expiry') }}
                                                                    </strong>
                                                                </span>
                                                            @else
                                                                <span class="form-text text-muted">Please enter your Visa Expiry Date</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                @if($rider_detail->visa_image)
                                                    <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_detail->visa_image)) }}" alt="image">
                                                @else
                                                    <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                                @endif
                                                <div class="form-group col-md-6 pull-right mtr-15">
                                                    <div class="custom-file">
                                                        <input type="file" name="visa_image" class="custom-file-input" id="visa_image">
                                                        <label class="custom-file-label" for="visa_image">Choose Visa Picture</label>
                                                    </div>
                                                    <span class="form-text text-muted">Select if you want to update Visa picture</span>
                                                </div>
                                            </div>
                                        
                                    </div>
                                    <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                            <label>Licence Expiry:</label>
                                                            <input type="text" id="datepicker5" autocomplete="off" class="form-control @if($errors->has('licence_expiry')) invalid-field @endif" name="licence_expiry" placeholder="Enter Licence Expiry" value="{{ $rider_detail->licence_expiry }}">
                                                            @if ($errors->has('licence_expiry'))
                                                                <span class="invalid-response" role="alert">
                                                                    <strong>
                                                                        {{ $errors->first('licence_expiry') }}
                                                                    </strong>
                                                                </span>
                                                            @else
                                                                <span class="form-text text-muted">Please enter your Licence Expiry Date</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                    @if($rider_detail->licence_image)
                                                        <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_detail->licence_image)) }}" alt="image">
                                                    @else
                                                        <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                                    @endif
                                                    <div class="form-group col-md-6 pull-right mtr-15">
                                                        <div class="custom-file">
                                                            <input type="file" name="licence_image" class="custom-file-input" id="licence_image">
                                                            <label class="custom-file-label" for="licence_image">Choose Licence Picture</label>
                                                        </div>
                                                        <span class="form-text text-muted">Select if you want to update Licence picture</span>
                                                    </div>
                                                </div>
                                              
                                        </div>
                                               
                            
                                            <div class="form-group">
                                                    <label>Other Details:</label>
                                                    <textarea type="text" rows="8"  autocomplete="off" class="form-control @if($errors->has('other_details')) invalid-field @endif" name="other_details" placeholder="Enter Further Details" >{{ $rider_detail->other_details }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                        <label>Status:</label>
                                                        <div>
                                                            <input data-switch="true" name="status" id="status" type="checkbox" {!! $rider->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
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
@endsection
@section('foot')
{{-- start_timer , timepicker1 --}}
<script>
$(document).ready(function(){
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
 
 {{-- end time picker --}}
 <script>
      $(document).ready(function(){
        $('#timepicker1').fdatepicker({ format: 'hh:ii',startView:1,maxView:0,pickTime: true,});
        $('#timepicker2').fdatepicker({ format: 'hh:ii',startView:1,maxView:0,pickTime: true});
        $('#timepicker3').fdatepicker({ format: 'hh:ii',startView:1,maxView:0,pickTime: true});
        $('#timepicker4').fdatepicker({ format: 'hh:ii',startView:1,maxView:0,pickTime: true});
	 });
 </script>
    
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
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
          <link rel="stylesheet" href="/resources/demos/style.css">
       
          <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $(document).ready(function(){
                $('#datepicker1').fdatepicker({dateFormat: 'yy-mm-dd'}); 
                $('#datepicker2').fdatepicker({dateFormat: 'yy-mm-dd'}); 
                $('#datepicker3').fdatepicker({dateFormat: 'yy-mm-dd'}); 
                $('#datepicker4').fdatepicker({dateFormat: 'yy-mm-dd'});  
                $('#datepicker5').fdatepicker({dateFormat: 'yy-mm-dd'}); 
                $('#datepicker6').fdatepicker({dateFormat: 'yy-mm-dd'}); 
            });
        
        </script>
@endsection