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
                            New Comer Edit
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('NewComer.updatenewComer', $newcomer->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                                
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter your name" value="{{$newcomer->name}}" required>
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
                            <input type="text" class="form-control @if($errors->has('phone_number')) invalid-field @endif" name="phone_number" placeholder="Enter phone number" value="{{$newcomer->phone_number}}" required>
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
                            <input type="text" class="form-control @if($errors->has('nationality')) invalid-field @endif" name="nationality" placeholder="Nationality" value="{{$newcomer->nationality}}" required> 
                            @if ($errors->has('nationality'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('nationality') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        <div class="form-group">
                            <label>WhatsApp Number:</label>
                            <input type="text" class="form-control @if($errors->has('whatsapp_number')) invalid-field @endif" name="whatsapp_number" placeholder="Whatsapp Number" value="{{$newcomer->whatsapp_number}}" required> 
                            @if ($errors->has('whatsapp_number'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('whatsapp_number') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Education:</label>
                            <input type="text" class="form-control @if($errors->has('education')) invalid-field @endif" name="education" placeholder="Education" value="{{$newcomer->education}}" required> 
                            @if ($errors->has('education'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('education') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Licence Issue Date:</label>
                            <input type="text" id="licence_date" autocomplete="off" class="form-control @if($errors->has('licence_issue_date')) invalid-field @endif" name="licence_issue_date" placeholder="Licence Issue Date" value="{{$newcomer->licence_issue_date}}" required> 
                            @if ($errors->has('licence_issue_date'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('licence_issue_date') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                      
                        
                        <div class="form-group">
                            <label for="source_of_contact">Source Of Contact:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('source_of_contact')) invalid-field @endif" id="source_of_contact_whatsapp" name="source_of_contact" style="width: 2% !important;" @if ($newcomer->source_of_contact==='whatsapp') checked @endif value="whatsapp"  required/><h6 style="margin-top:10px;margin-left:10px;">WhatsApp</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('source_of_contact')) invalid-field @endif" id="source_of_contact_phone_call" name="source_of_contact" style="width: 2% !important;" @if ($newcomer->source_of_contact==='phone_call') checked @endif value="phone_call"  required /><h6 style="margin-top:10px;margin-left:10px;">Phone Call</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('source_of_contact')) invalid-field @endif" id="Reference" name="source_of_contact" style="width: 2% !important;" @if ($newcomer->source_of_contact!=='whatsapp'&& $newcomer->source_of_contact!=='phone_call') checked @endif   required/><h6 style="margin-top:10px;margin-left:10px;">Reference</h6></div>   
                           <div id="refrence"><input type="text" id="refrence_input"   autocomplete="off"class="form-control @if($errors->has('source_of_contact')) invalid-field @endif" placeholder="Reference"@if ($newcomer->source_of_contact==='whatsapp' || $newcomer->source_of_contact==='phone_call') style="display:none;" @else value="{{$newcomer->source_of_contact}}"  @endif ><span class="form-text text-muted">Enter Your Reference</span>   </div>
                        </div>
                        <div class="form-group">
                            <label for="source_of_contact">Experiance:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 2% !important;" @if ($newcomer->experiance==='less than 1 year') checked @endif value="less than 1 year" required /><h6 style="margin-top:10px;margin-left:10px;">Less Than 1 Year</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 2% !important;" @if ($newcomer->experiance==='more than 1 year') checked @endif value="more than 1 year"  required /><h6 style="margin-top:10px;margin-left:10px;">More Than 1 Year</h6></div>
                           <label for="experience_input">Experiance Input:</label>
                        <input type="text"  id="experience_input" autocomplete="off"class="form-control @if($errors->has('experience_input')) invalid-field @endif" name="experience_input" placeholder="Experiance Input" value="{{$newcomer->experience_input}}"><span class="form-text text-muted">Experience Input</span>   
                        </div>
                           <div class="form-group">
                            <label for="source_of_contact">Passport Status:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('passport_status')) invalid-field @endif" id="passport_status" name="passport_status" style="width: 2% !important;" @if ($newcomer->passport_status==='yes') checked @endif value="yes"  required/><h6 style="margin-top:10px;margin-left:10px;">Yes</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('passport_status')) invalid-field @endif" id="passport_status" name="passport_status" style="width: 2% !important;" @if ($newcomer->passport_status==='no') checked @endif value="no"  required /><h6 style="margin-top:10px;margin-left:10px;">No</h6></div>
                           <label for="passport_reason">Passport Reason:</label>
                           <input type="text"  id="passport_reason" autocomplete="off"class="form-control @if($errors->has('passport_reason')) invalid-field @endif" name="passport_reason" placeholder="Passport Reason" value="{{$newcomer->passport_status }}"><span class="form-text text-muted">Passport Reason</span>   
                       </div>
                       <div class="form-group">
                        <label for="kingriders_interview">Kingriders Interview:</label>
                       <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('kingriders_interview')) invalid-field @endif" id="kingriders_interview" name="kingriders_interview" style="width: 2% !important;"@if ($newcomer->kingriders_interview==='selected') checked @endif value="selected"  required/><h6 style="margin-top:10px;margin-left:10px;">Selected</h6></div>
                       <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('kingriders_interview')) invalid-field @endif" id="kingriders_interview" name="kingriders_interview" style="width: 2% !important;"@if ($newcomer->kingriders_interview==='rejected') checked @endif value="rejected"  required /><h6 style="margin-top:10px;margin-left:10px;">Rejected</h6></div>
                       <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('kingriders_interview')) invalid-field @endif" id="kingriders_interview" name="kingriders_interview" style="width: 2% !important;"@if ($newcomer->kingriders_interview==='pending') checked @endif value="pending"  required/><h6 style="margin-top:10px;margin-left:10px;">Pending</h6></div>   
                    </div>
                        <div class="form-group">
                            <label for="interview"> Interview:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('interview')) invalid-field @endif" id="interview_waiting" name="interview" style="width: 2% !important;" @if ($newcomer->interview==='Waiting For Interview') checked @endif value="Waiting For Interview"  required/><h6 style="margin-top:10px;margin-left:10px;">Waiting For Interview</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('interview')) invalid-field @endif" id="interview_completed" name="interview" style="width: 2% !important;" @if ($newcomer->interview==='Interview Completed') checked @endif value="Interview Completed"   required /><h6 style="margin-top:10px;margin-left:10px;">Interview Completed</h6></div>
                        </div>
                        <div class="form-group" id="interview_status">
                            <label for="source_of_contact"> Interview Status:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('interview_status')) invalid-field @endif" id="rejected_interview" name="interview_status" style="width: 2% !important;" @if ($newcomer->interview_status==='rejected') checked @endif value="rejected" /><h6 style="margin-top:10px;margin-left:10px;">Rejected</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('interview_status')) invalid-field @endif" id="accpected_interview" name="interview_status" style="width: 2% !important;" @if ($newcomer->interview_status==='accepted') checked @endif value="accepted"  /><h6 style="margin-top:10px;margin-left:10px;">Accepted</h6></div>
                        </div>
                        <div class="form-group" id="accpeted">
                            
                            <label>If Accepted:</label>
                            <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12"><input type="text"  id="datepicker1" autocomplete="off"class="form-control @if($errors->has('interview_date')) invalid-field @endif" name="interview_date" placeholder="Interview Date"  value="{{ $newcomer->interview_date}}"><span class="form-text text-muted">Interview Date</span> </div>
                            <div class="col-lg-4 col-md-4 col-sm-12"> <input type="text" id="interview_by" class="form-control @if($errors->has('interview_By')) invalid-field @endif" name="interview_By" placeholder="Interview By"   value="{{ $newcomer->interview_By }}"><span class="form-text text-muted">Interview Conducted By</span> </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">  <input type="text" id="datepicker2" autocomplete="off" class="form-control @if($errors->has('joining_date')) invalid-field @endif" name="joining_date" placeholder="Joining Date"   value="{{ $newcomer->joining_date }}"><span class="form-text text-muted">Interview Joining Date</span> </div>
                            </div>
                        </div>
                        <div class="form-group" id="rejected_why"> 
                            <label>If Rejected:</label>
                            <input type="textarea" id="why_reject" class="form-control @if($errors->has('why_rejected')) invalid-field @endif" name="why_rejected" placeholder="Why Rejected" value="{{$newcomer->why_rejected}}">  
                        </div>
                        <div class="form-group">
                            <label>Overall Remarks:</label>
                            <input type="text" class="form-control @if($errors->has('overall_remarks')) invalid-field @endif" name="overall_remarks" placeholder="Overall Remarks" value="{{$newcomer->overall_remarks}}" required>
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
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('priority')) invalid-field @endif" id="priority" name="priority" style="width: 2% !important;" @if ($newcomer->priority==='normal') checked @endif value="normal"  required/><h6 style="margin-top:10px;margin-left:10px;">Normal</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('priority')) invalid-field @endif" id="priority" name="priority" style="width: 2% !important;" @if ($newcomer->priority==='priority') checked @endif value="priority"  required /><h6 style="margin-top:10px;margin-left:10px;">Priority</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('priority')) invalid-field @endif" id="priority" name="priority" style="width: 2% !important;" @if ($newcomer->priority==='top_priority') checked @endif value="top_priority"  required/><h6 style="margin-top:10px;margin-left:10px;">Top Priority</h6></div>   
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
                $('#datepicker1').fdatepicker({format: 'dd-mm-yyyy'});
                $('#datepicker2').fdatepicker({format: 'dd-mm-yyyy'});
                
            });
        
        </script>
        <script>
        $(document).ready(function(){
   
        $("#interview_status").hide();
        $("#accpeted").hide();
        $("#rejected_why").hide();
    
    
  

        $("#interview_completed").change(function(){
            if($(this).is(':checked')){
                $("#interview_status").show(); 
            }
             
        });
        $('#interview_waiting').change(function(){
            if($(this).is(':checked')){
                $("#interview_status").hide();
                $("#interview_status [type=radio]").prop('checked', false).val('');
               
               
                $("#accpeted").hide();
                $("#rejected_why").hide();
            }
        });


        $('#accpected_interview').change(function(){
            if($(this).is(':checked')){
                $("#accpeted").show();
                $("#rejected_why").hide();
            }
        });
        $('#rejected_interview').change(function(){
            if($(this).is(':checked')){
                $("#rejected_why").show();
                $("#accpeted").hide();
            }
        });
     
        // $('#refrence').hide();
$('#Reference').change(function(){
    if($(this).is(':checked')){
        $('#refrence,#refrence_input').show();
         }
    
});
$('#refrence_input').on('change input', function(){
    $('#Reference').val($(this).val());
});
$('#source_of_contact_whatsapp').change(function(){
    if($(this).is(':checked')){
    $('#refrence').hide();
    $('#refrence_input').val('');}
});
$('#source_of_contact_phone_call').change(function(){
    if($(this).is(':checked')){
     $('#refrence').hide();
    $('#refrence_input').val('');
    }
});

        $('#interview_completed,#accpected_interview,#rejected_interview,#interview_waiting,#rejected_why').trigger('change');
    });
        
        </script>
          <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
          <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
          
          <script>
              $(document).ready(function(){
                  $('#licence_date').fdatepicker({format: 'dd-mm-yyyy'}); 
                 
               });
          </script>
@endsection