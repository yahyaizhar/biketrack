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
                            New Comer
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('NewComer.insert') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                                
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter your name" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('name') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your name</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="text" class="form-control @if($errors->has('phone_number')) invalid-field @endif" name="phone_number" placeholder="Enter phone number" value="{{ old('phone_number') }}" required>
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
                        <div class="form-group">
                            <label>Nationality:</label>
                            <input type="text" class="form-control @if($errors->has('nationality')) invalid-field @endif" name="nationality" placeholder="Nationality" value="{{ old('nationality') }}" required> 
                            @if ($errors->has('nationality'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('nationality') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                      
                        
                        <div class="form-group">
                            <label for="source_of_contact">Source Of Contact:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('source_of_contact')) invalid-field @endif" id="source_of_contact_whatxapp" name="source_of_contact" style="width: 2% !important;" value="whatsapp"  required/><h6 style="margin-top:10px;margin-left:10px;">WhatsApp</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('source_of_contact')) invalid-field @endif" id="source_of_contact_phone_call" name="source_of_contact" style="width: 2% !important;" value="phone_call"  required /><h6 style="margin-top:10px;margin-left:10px;">Phone Call</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="Reference" style="width: 2% !important;" name="source_of_contact"   required/><h6 style="margin-top:10px;margin-left:10px;">Reference</h6></div>
                           <div id="refrence"><input type="text" id="refrence_input"   autocomplete="off"class="form-control @if($errors->has('source_of_contact')) invalid-field @endif" name="source_of_contact" placeholder="Reference" value="{{ old('source_of_contact') }}"><span class="form-text text-muted">Enter Your Reference</span>   </div>
                        </div>
                        <div class="form-group">
                            <label for="experience">Experiance:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 2% !important;" value="less than 1 year" required /><h6 style="margin-top:10px;margin-left:10px;">Less Than 1 Year</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 2% !important;" value="more than 1 year"  required /><h6 style="margin-top:10px;margin-left:10px;">More Than 1 Year</h6></div>
                          
                        
                           <label for="experience_input">Experiance Input:</label>
                        <input type="text"  id="experience_input" autocomplete="off"class="form-control @if($errors->has('experience_input')) invalid-field @endif" name="experience_input" placeholder="Experiance Input" value="{{ old('experience_input') }}"><span class="form-text text-muted">Experience Input</span>   
                    </div>  
                           <div class="form-group">
                            <label for="passport_status">Passport Status:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('passport_status')) invalid-field @endif" id="passport_status" name="passport_status" style="width: 2% !important;" value="yes"  required/><h6 style="margin-top:10px;margin-left:10px;">Yes</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('passport_status')) invalid-field @endif" id="passport_status" name="passport_status" style="width: 2% !important;" value="no"  required /><h6 style="margin-top:10px;margin-left:10px;">No</h6></div>
                           {{-- <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('passport_status')) invalid-field @endif" id="passport_status" name="passport_status" style="width: 2% !important;" value="Already given"  required/><h6 style="margin-top:10px;margin-left:10px;">Already given</h6></div>    --}}
                           <label for="passport_reason">Passport Reason:</label>
                           <input type="text"  id="passport_reason" autocomplete="off"class="form-control @if($errors->has('passport_reason')) invalid-field @endif" name="passport_reason" placeholder="Passport Reason" value="{{ old('passport_reason') }}"><span class="form-text text-muted">Passport Reason</span>   
                       </div>
                        <div class="form-group">
                            <label for="kingriders_interview">Kingriders Interview:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('kingriders_interview')) invalid-field @endif" id="kingriders_interview" name="kingriders_interview" style="width: 2% !important;" value="selected"  required/><h6 style="margin-top:10px;margin-left:10px;">Selected</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('kingriders_interview')) invalid-field @endif" id="kingriders_interview" name="kingriders_interview" style="width: 2% !important;" value="rejected"  required /><h6 style="margin-top:10px;margin-left:10px;">Rejected</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('kingriders_interview')) invalid-field @endif" id="kingriders_interview" name="kingriders_interview" style="width: 2% !important;" value="pending"  required/><h6 style="margin-top:10px;margin-left:10px;">Pending</h6></div>   
                        </div>
                        <div class="form-group">
                            <label for="interview"> Interview:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('interview')) invalid-field @endif" id="interview_waiting" name="interview" style="width: 2% !important;" value="Waiting For Interview"  required/><h6 style="margin-top:10px;margin-left:10px;">Waiting For Interview</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('interview')) invalid-field @endif" id="interview_completed" name="interview" style="width: 2% !important;" value="Interview Completed"   required /><h6 style="margin-top:10px;margin-left:10px;">Interview Completed</h6></div>
                        </div>
                        <div class="form-group" id="interview_status">
                            <label for="source_of_contact"> Interview Status:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('interview_status')) invalid-field @endif" id="rejected_interview" name="interview_status" style="width: 2% !important;" value="rejected" /><h6 style="margin-top:10px;margin-left:10px;">Rejected</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('interview_status')) invalid-field @endif" id="accpected" name="interview_status" style="width: 2% !important;" value="accepted"  /><h6 style="margin-top:10px;margin-left:10px;">Accepted</h6></div>
                        </div>
                        <div class="form-group" id="accpeted">
                            
                            <label>If Accepted:</label>
                            <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12"><input type="text"  id="datepicker1" autocomplete="off"class="form-control @if($errors->has('interview_date')) invalid-field @endif" name="interview_date" placeholder="Interview Date" value="{{ old('interview_date') }}"><span class="form-text text-muted">Interview Date</span>  </div>
                            <div class="col-lg-4 col-md-4 col-sm-12"> <input type="text" id="interview_by" class="form-control @if($errors->has('interview_By')) invalid-field @endif" name="interview_By" placeholder="Interview By" value="{{ old('interview_By') }}"><span class="form-text text-muted">Interview Conducted By</span> </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">  <input type="text" id="datepicker2" autocomplete="off" class="form-control @if($errors->has('joining_date')) invalid-field @endif" name="joining_date" placeholder="Joining Date" value="{{ old('joining_date') }}"> <span class="form-text text-muted">Interview Joining Date</span> </div>
                            </div>
                        </div>
                        <div class="form-group" id="rejected"> 
                            <label>If Rejected:</label>
                            <input type="textarea" id="why_reject" class="form-control @if($errors->has('why_rejected')) invalid-field @endif" name="why_rejected" placeholder="Why Rejected" value="{{ old('why_rejected') }}">  
                        </div>
                        <div class="form-group">
                            <label>Overall Remarks:</label>
                            <input type="text" class="form-control @if($errors->has('overall_remarks')) invalid-field @endif" name="overall_remarks" placeholder="Overall Remarks" value="{{ old('overall_remarks') }}" required>
                            @if ($errors->has('overall_remarks'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('overall_remarks') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Overall Remarks</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="source_of_contact">Priority:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('priority')) invalid-field @endif" id="priority" name="priority" style="width: 2% !important;" value="normal"  required/><h6 style="margin-top:10px;margin-left:10px;">Normal</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('priority')) invalid-field @endif" id="priority" name="priority" style="width: 2% !important;" value="priority"  required /><h6 style="margin-top:10px;margin-left:10px;">Priority</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('priority')) invalid-field @endif" id="priority" name="priority" style="width: 2% !important;" value="top_priority"  required/><h6 style="margin-top:10px;margin-left:10px;">Top Priority</h6></div>   
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
          <link rel="stylesheet" href="/resources/demos/style.css">
       
          <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $(document).ready(function(){
                $('#datepicker1').datepicker({format: "dd.mm.yyyy"}); 
                $('#datepicker2').datepicker({format: "dd.mm.yyyy"}); 
                
            });
        
        </script>
        <script>
        $(document).ready(function(){
    
       $("#interview_status").hide();
       $("#accpeted").hide();
       $("#rejected").hide();
  

        $("#interview_completed").change(function(){
        $("#rejected_interview").prop("checked",false);
        $("#accpected").prop("checked",false);
         
        $('#why_reject').val('');
        $('#interview_by').val('');
        $('#datepicker1').val('');
        $('#datepicker2').val('');
        
        $("#interview_status").show();
        
        $('#accpected').change(function(){
            $("#accpeted").show();
            $("#rejected").hide();
        });
        $('#rejected_interview').change(function(){
            $("#rejected").show();
            $("#accpeted").hide();
        });      
});
     
     $('#interview_waiting').change(function(){
        $("#interview_status").hide();
        $("#accpeted").hide();
        $("#rejected").hide();
        
        $("#rejected_interview").prop("checked",false);
        $("#accpected").prop("checked",false);
        $('#why_reject').val('');
        $('#interview_by').val('');
        $('#datepicker1').val('');
        $('#datepicker2').val('');
        });
        $('#refrence').hide();
$('#Reference').change(function(){
    $('#refrence').show();
});
$('#source_of_contact_whatxapp').change(function(){
    $('#refrence').hide();
    
    $('#refrence_input').val('');
});
$('#source_of_contact_phone_call').change(function(){
    $('#refrence').hide();
    $('#refrence_input').val('');
});





    });
        </script>
@endsection