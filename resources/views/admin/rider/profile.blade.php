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
                            Rider Profile
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                        <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                
                                <div class="form-group">
                                    <label>Full Name:</label>
                                    <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Full name" value="{{ $rider->name }}" disabled>
                                    @if ($errors->has('name'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('name') }}
                                            </strong>
                                        </span>
                                    
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Email address:</label>
                                    <input type="email" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Email" value="{{ $rider->email }}" disabled>
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
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                @if($rider->profile_picture)
                                    <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="image">
                                @else
                                    <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                               
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Personal Phone Number:</label>
                            <input type="text" class="form-control @if($errors->has('phone')) invalid-field @endif" name="phone" placeholder="Enter phone number" value="{{ $rider->phone }}" disabled>
                            @if ($errors->has('phone'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('phone') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Rider phone number</span>
                            @endif
                        </div>
                        <div class="form-group"> 
                            <label>Date Of Birth:</label>
                            <input type="text" id="date_of_birth" autocomplete="off" class="form-control @if($errors->has('date_of_birth')) invalid-field @endif" name="date_of_birth" placeholder="Enter Date Of Birth" value="{{ $rider->date_of_birth }}" disabled>
                            @if ($errors->has('date_of_birth'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('date_of_birth') }}
                                    </strong>
                                </span>
                           
                            @endif
                        </div>
                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" class="form-control @if($errors->has('address')) invalid-field @endif" name="address" placeholder="Enter city" value="{{ $rider->address }}" disabled>
                            @if ($errors->has('address'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('address')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        @if ( $rider_details->passport_collected=="no")
                        <div style="border: 1px solid #adadc9;padding: 7px;">
                                <div class="form-group" >
                                    <label>IS Passport Collected:</label>
                                    <input type="text" class="form-control " value="{{ $rider_details->passport_collected }}" disabled>
                                    </div>
                                    @if ($rider_details->is_guarantee=="employee")
                                    <div class="form-group">
                                        @php
                                            $rider_pasport=App\Model\Rider\Rider::find($rider_details->empoloyee_reference);
                                        @endphp
                                            <label>Employee Reference:</label>
                                            <input type="text" class="form-control @if($errors->has('empoloyee_reference')) invalid-field @endif" name="empoloyee_reference"  value="{{ $rider_pasport->name }}" disabled>
                                            @if ($errors->has('empoloyee_reference'))
                                                <span class="invalid-response" role="alert">
                                                    <strong>
                                                        {{$errors->first('empoloyee_reference')}}
                                                    </strong>
                                                </span>
                                            @endif
                                        </div> 
                                    @endif
                                    @if ($rider_details->is_guarantee=="outsider")
                                    <div class="form-group">
                                            <label>Someone else passport:</label>
                                            <input type="text" class="form-control @if($errors->has('other_passport_given')) invalid-field @endif" name="other_passport_given"  value="{{ $rider_details->other_passport_given }}" disabled>
                                            @if ($errors->has('other_passport_given'))
                                                <span class="invalid-response" role="alert">
                                                    <strong>
                                                        {{$errors->first('other_passport_given')}}
                                                    </strong>
                                                </span>
                                            @endif
                                        </div> 
                                    @endif
                                   @if ($rider_details->is_guarantee=="not_given")
                                   <div class="form-group">
                                        <label>Not Given:</label>
                                        <input type="text" class="form-control @if($errors->has('not_given')) invalid-field @endif" name="not_given"  value="{{ $rider_details->not_given }}" disabled>
                                        @if ($errors->has('not_given'))
                                            <span class="invalid-response" role="alert">
                                                <strong>
                                                    {{$errors->first('not_given')}}
                                                </strong>
                                            </span>
                                        @endif
                                    </div> 
                                   @endif
                                        
                                    <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        @if($rider_details->passport_document_image)
                                            <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->passport_document_image)) }}" alt="image">
                                        @else
                                            <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                        @endif
                                        <span class="form-text text-muted"> Passport Or Document Image</span> 
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                            @if($rider_details->agreement_image)
                                                <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->agreement_image)) }}" alt="image">
                                            @else
                                                <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                            @endif
                                            <span class="form-text text-muted"> Agreement Image</span> 
                                        </div>
                                        </div>
                                    </div> 
                        @else
                        <div class="form-group" >
                                <label>IS Passport Collected:</label>
                                <input type="text" class="form-control " value="{{ $rider_details->passport_collected }}" disabled>
                                </div> 
                        @endif
                         
                      <div class="row">
                             <div class="col-lg-3 col-md-3 col-sm-12">
                             <div class="form-group">
                                 <label>Start Time:</label>
                                 <input type="hidden" id="start_timer1" name="start_time" value="{{ $rider->start_time }}">
                                 <input type="text" autocomplete="off" id="timepicker1" class="form-control @if($errors->has('start_time')) invalid-field @endif" placeholder="Start Time" value="{{ $rider->start_time }}" disabled>
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
                                 <input type="text" autocomplete="off" id="timepicker2" class="form-control @if($errors->has('end_time')) invalid-field @endif"  placeholder="End Time" value="{{ $rider->end_time }}" disabled>
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
                                 <input type="text" autocomplete="off" id="timepicker3" class="form-control @if($errors->has('break_start_time')) invalid-field @endif"  placeholder="Break Start Time" value="{{ $rider->break_start_time }}" disabled>
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
                                 <input type="text" autocomplete="off" id="timepicker4" class="form-control @if($errors->has('break_end_time')) invalid-field @endif"  placeholder="Break End Time" value="{{ $rider->break_end_time }}" disabled>
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
                                <input type="text" id="datepicker1" autocomplete="off" class="form-control @if($errors->has('date_of_joining')) invalid-field @endif" name="date_of_joining" placeholder="Enter joining Date" value="{{ $rider_details->date_of_joining }}" disabled>
                                @if ($errors->has('date_of_joining'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('date_of_joining') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Rider Joining Date</span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    @isset($sim)
                                    
                                    <div class="form-group">
                                        
                                        <label>Sim Number:</label>
                                        <div class="row">
                                        <div class="col-md-12 ">
                                            <input type="text" class="form-control @if($errors->has('official_given_number')) invalid-field @endif" name="official_given_number" placeholder="Enter official number" value="{{ $sim!==null?$sim->sim_number:'' }}" disabled> 
                                            @if ($errors->has('official_given_number'))
                                                <span class="invalid-response" role="alert">
                                                    <strong> 
                                                        {{ $errors->first('official_given_number') }}
                                                    </strong>
                                                </span>
                                            @else
                                                <span class="form-text text-muted">Rider official phone number</span>
                                            @endif
                                        </div>
                                       
                                    </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-sm-4 col-md-4">
                                                    <label>Sim Allowed Balance:</label>
                                                    <input type="text" class="form-control @if($errors->has('official_given_number')) invalid-field @endif"  value="{{$sim_history->allowed_balance }}" disabled> 
                                            </div>

                                            <div class="col-sm-4 col-md-4">
                                                    <label>Sim Given Date:</label>
                                                    <input type="text" class="form-control @if($errors->has('official_given_number')) invalid-field @endif" name="official_given_number" placeholder="Enter official number" value="{{$sim_history->given_date }}" disabled> 
                                           </div>

                                            <div class="col-sm-4 col-md-4">
                                                    <label>Sim Return Date:</label>
                                                    <input type="text" class="form-control @if($errors->has('official_given_number')) invalid-field @endif" name="official_given_number" placeholder="Enter official number" value="{{ $sim_history->return_date }}" disabled> 
                                           </div>
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="h5">No sim is assigned to this rider.</div>
                                        </div>
                                       
                                    </div>
                                    @endisset
                                    
                                </div>
                            </div>
                               <div style="border: 2px solid #ddd;padding: 15px;margin: 7px 0;">
                                    <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-12">
                                                    <div class="form-group" style="margin-bottom:0px;">
                                                            <label>Passport Expiry:</label>
                                                            <input type="text" id="datepicker3" autocomplete="off" class="form-control @if($errors->has('passport_expiry')) invalid-field @endif" name="passport_expiry" placeholder="Enter Passport Expiry" value="{{ $rider_details->passport_expiry }}" disabled>
                                                            @if ($errors->has('passport_expiry'))
                                                                <span class="invalid-response" role="alert">
                                                                    <strong>
                                                                        {{ $errors->first('passport_expiry') }}
                                                                    </strong>
                                                                </span>
                                                            @else
                                                                <span class="form-text text-muted">Rider Passport Expiry Date</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                   
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                            @if($rider_details->passport_image)            
                                            <a href="#" id="passport_image_front" data-featherlight="{{ asset(Storage::url($rider_details->passport_image)) }}"> <img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->passport_image)) }}" alt="image"></a>
                                            <span class="form-text text-muted"> Front Image</span>
                                            @else
                                      <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;"  class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                      <span class="form-text text-muted"> Front Image</span> 
                                      @endif
                                           
                                        </div>
                                        {{-- <div class="col-lg-4 col-md-4 col-sm-12">
                                            @if($rider_details->passport_image_back)
                                            <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->passport_image_back)) }}"><img style="width:150px;height:150px;" style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->passport_image_back)) }}" alt="image"></a>
                                            <span class="form-text text-muted"> Back Image</span>
                                            @else
                                        <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;" style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                        <span class="form-text text-muted"> Back Image</span> 
                                        @endif
                                           
                                        </div> --}}
                                    </div> 
                                </div>
                                    <div style="border: 2px solid #ddd;padding: 15px;margin: 7px 0;">
                                            <div class="row">  
                                                    <div class="col-lg-8 col-md-8 col-sm-12">         
                                        <div class="form-group" style="margin-bottom:0px;margin-top:25px;">
                                                            <label>Visa Expiry:</label>
                                                            <input type="text" id="datepicker4" autocomplete="off" class="form-control @if($errors->has('visa_expiry')) invalid-field @endif" name="visa_expiry" placeholder="Enter Visa Expiry" value="{{ $rider_details->visa_expiry }}" disabled>
                                                            @if ($errors->has('visa_expiry'))
                                                                <span class="invalid-response" role="alert">
                                                                    <strong>
                                                                        {{ $errors->first('visa_expiry') }}
                                                                    </strong>
                                                                </span>
                                                            @else
                                                                <span class="form-text text-muted">Rider Visa Expiry Date</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                   
                                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                                @if($rider_details->visa_image)
                                                <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->visa_image)) }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->visa_image)) }}" alt="image"></a>
                                                <span class="form-text text-muted"> Front Image</span>
                                                @else
                                                <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                                <span class="form-text text-muted"> Front Image</span>
                                                @endif
                                               
                                            </div>
                                            {{-- <div class="col-lg-4 col-md-4 col-sm-12">
                                                @if($rider_details->visa_image_back)
                                                <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->visa_image_back)) }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->visa_image_back)) }}" alt="image"></a>
                                                <span class="form-text text-muted"> Back Image</span>
                                                @else
                                                <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                                <span class="form-text text-muted"> Back Image</span>
                                                @endif
                                               
                                            </div> --}}
                                        
                                    </div>
                                    </div>
                                    <div style="border: 2px solid #ddd;padding: 15px;margin: 7px 0;">
                                            <div class="row">  
                                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group" style="margin-bottom:0px;margin-top:25px;">
                                               <label>Emirates ID:</label>
                                               <input type="text"  class="form-control @if($errors->has('emirate_id')) invalid-field @endif" name="emirate_id" placeholder="Enter Emirate ID" value="{{ $rider_details->emirate_id }}" disabled>
                                               @if ($errors->has('emirate_id'))
                                                   <span class="invalid-response" role="alert">
                                                       <strong>{{ $errors->first('emirate_id') }}</strong>
                                                   </span>
                                               @else
                                                   <span class="form-text text-muted">Rider Emirate ID.</span>
                                               @endif
                                           </div></div>
                                          
                                           
                                           <div class="col-lg-4 col-md-4 col-sm-12">
                                            @if($rider_details->emirate_image)
                                            <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->emirate_image)) }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->emirate_image)) }}" alt="image"></a>
                                            <span class="form-text text-muted"> Front Image</span>
                                            @else
                                            <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                            <span class="form-text text-muted"> Front Image</span>
                                            @endif
                                       
                                               </div>
                                               <div class="col-lg-4 col-md-4 col-sm-12">
                                                @if($rider_details->emirate_image_back)
                                                <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->emirate_image_back)) }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->emirate_image_back)) }}" alt="image"></a>
                                                <span class="form-text text-muted"> Back Image</span>
                                                @else
                                            <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;"  class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                            <span class="form-text text-muted"> Back Image</span>
                                            @endif
                                           
                                                   </div>
                                            </div>
                                    </div>
                                    <div style="border: 2px solid #ddd;padding: 15px;margin: 7px 0;">
                                            <div class="row">   
                                        <div class="col-lg-4 col-md-4 col-sm-12">           
                                        <div class="form-group" style="margin-bottom:0px;margin-top:25px;">
                                                            <label>Licence Expiry:</label>
                                                            <input type="text" id="datepicker5" autocomplete="off" class="form-control @if($errors->has('licence_expiry')) invalid-field @endif" name="licence_expiry" placeholder="Enter Licence Expiry" value="{{ $rider_details->licence_expiry }}" disabled>
                                                            @if ($errors->has('licence_expiry'))
                                                                <span class="invalid-response" role="alert">
                                                                    <strong>
                                                                        {{ $errors->first('licence_expiry') }}
                                                                    </strong>
                                                                </span>
                                                            @else
                                                                <span class="form-text text-muted">Rider Licence Expiry Date</span>
                                                            @endif
                                                        </div></div>
                                                   
                                                    
                                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                    @if($rider_details->licence_image)
                                                    <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->licence_image)) }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->licence_image)) }}" alt="image"></a>
                                                    <span class="form-text text-muted"> Front Image</span>
                                                    @else
                                                    <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                                    <span class="form-text text-muted"> Front Image</span>
                                                    @endif
                                                  
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    @if($rider_details->licence_image_back)
                                                    <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->licence_image_back)) }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->licence_image_back)) }}" alt="image"></a>
                                                    <span class="form-text text-muted"> Back Image</span>
                                                    @else
                                                    <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                                    <span class="form-text text-muted"> Back Image</span>
                                                    @endif  
                                                  
                                                </div>
                                              
                                        </div>
                                    </div>
                                        @if ($bike==null)
                                            
                                        @else

                                        <div style="border: 2px solid #ddd;padding: 15px;margin: 7px 0;">
                                                <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            <div class="form-group" style="margin-bottom:0px;margin-top:25px;">
                                            <label>Mulkiya Expiry:</label>
                                            <input type="text" class="form-control @if($errors->has('mulkiya_expiry')) invalid-field @endif" name="mulkiya_expiry" placeholder="Enter Licence Expiry" value="{{ $bike->mulkiya_expiry }}" disabled>
                                           
                                                <span class="form-text text-muted">Rider Mulkiya Expiry Date</span>
                                           
                                        </div> 
                                                        </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                    @if($rider_details->mulkiya_picture)
                                                        <img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_details->mulkiya_picture)) }}" alt="image">
                                                        <a href="#" data-featherlight="{{ asset(Storage::url($bike->mulkiya_picture)) }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($bike->mulkiya_picture)) }}" alt="image"> </a>
                                                        <span class="form-text text-muted"> Front Image</span>
                                                        @else
                                                        <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                                        <span class="form-text text-muted"> Front Image</span>
                                                        @endif
                                                  
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    @if($rider_details->mulkiya_picture_back)
                                                    <a href="#" data-featherlight="{{ asset(Storage::url($bike->mulkiya_picture_back)) }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($bike->mulkiya_picture_back)) }}" alt="image"></a>
                                                    <span class="form-text text-muted"> Back Image</span>
                                                    @else
                                                <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}"><img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" /></a>
                                                <span class="form-text text-muted"> Back Image</span>
                                                @endif
                                                  
                                                </div>
                                              
                                        </div></div>
                                        @endif
                                       
                                   
                                
                                               
                            
                                            <div class="form-group"style="margin-top:25px;">
                                                    <label>Other Details:</label>
                                                    <textarea type="text" rows="8"  autocomplete="off" class="form-control @if($errors->has('other_details')) invalid-field @endif" name="other_details" placeholder="Enter Further Details" disabled >{{ $rider_details->other_details }}</textarea>
                                                </div>
                                                
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right"> 
                            <button class="btn btn-primary btn-sm btn-upper"  onclick="deleteRiderprofile({{$rider->id}})"><i class="fa fa-trash"></i> Delete Rider</button>
                            @php
                                $status_text = $rider->status == 1 ? 'Inactive' : 'Active';
                            @endphp
                            <button class="btn btn-primary btn-sm btn-upper"  onclick="updateRiderStatus({{$rider->id}},{{$rider->status}})"><i class="fa fa-toggle-on"></i>{{$status_text}}</button>
                            <a style="float:right;" href="{{ route('admin.riders.edit', $rider->id) }}" class="btn btn-primary btn-sm btn-upper">Edit</a>&nbsp;
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right"> 
                            <a class="btn btn-primary btn-sm btn-upper" href="{{route('Sim.simHistory', $rider->id)}}"><i class="fa fa-eye"></i>Sim History</a>
                            <a class="btn btn-primary btn-sm btn-upper" href="{{route('Bike.assignedToRiders_History', $rider->id)}}"><i class="fa fa-eye"></i>Bikes History</a>
                            <a class="btn btn-primary btn-sm btn-upper" href="{{route('Client.client_history', $rider->id)}}"><i class="fa fa-eye"></i>Client History</a>
                            <a class="btn btn-primary btn-sm btn-upper" href="{{route('Rider.spell_time', $rider->id)}}"><i class="fa fa-eye"></i> Rider Duration Time</a>
                           @if (isset($bike_html))
                           <a class="btn btn-primary btn-sm btn-upper" href="{{route('rider.rider_salik', $rider->id)}}"><i class="fa fa-eye"></i> View Salik</a>
                           @endif
                           @if (isset($assign_bike))
                           <a class="btn btn-primary btn-sm btn-upper" href="{{route('bike.bike_assignRiders', $rider->id)}}"><i class="fa fa-eye"></i> Change Bike</a>
                           @endif
                           @if (isset($sim_history_change))
                           <a class="btn btn-primary btn-sm btn-upper" href="{{route('SimHistory.addsim', $rider->id)}}"><i class="fa fa-eye"></i> Change Sim</a>
                           @endif
                           
                          </div>
                    </div>
                </div>
                </form>
 
                <!--end::Form-->
            </div>

        <!--end::Portlet-->
    </div>
</div>
<div>
        <div class="modal fade" id="inactive_reasons" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="exampleModalLabel">Inactive Reasons</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="kt-form" id="inactive"  enctype="multipart/form-data">
                        <div class="container">
                            <div class="form-group">
                                <label>Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('inactive_month')) invalid-field @endif" name="inactive_month" placeholder="Enter Month" value="">
                            </div>
                            <div class="form-group">
                                <label>Reason:</label>
                                <textarea type="text"  rows="6" autocomplete="off" class="form-control" name="inactive_reason" placeholder="Enter Details" ></textarea>
                            </div>
                            <div class="modal-footer border-top-0 d-flex justify-content-center">
                                <button class="upload-button btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
    </div>
<!-- end:: Content -->
@endsection
@section('foot') 
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>


<script>
    function deleteRiderprofile(id){ 
    var url = "{{ url('admin/delete/Rider') }}"+ "/" + id;
    sendDeleteRequest(url, false, "{{url('admin/riders')}}", null);
}
$(document).ready(function(){
  var image_url = $('#passport_image_front').attr('data-featherlight');
  $('[data-featherlight]').on('click', function(){
      var imgURL = $(this).attr('data-featherlight');
      setTimeout(function(){
        $('.featherlight .featherlight-image').wrap('<div />').parent().zoom({
            url: imgURL,
            magnify:2
            });
      },500);
      
  })
  
});
</script>
    <script>
        function sendSMS(id)
        {
            var rider_id = id;
            var textbox_id = "#message_"+id;
            var url = "{{ url('admin/rider') }}" + "/" + id + "/sendMessage";
            var method = 'POST';
            var data = {
                'message' : $(textbox_id).val()
            };
            
            if(data.message == '')
            {
                swal.fire("Message Empty!", "Please enter some message.", "error");
            }
            else
            {
                // console.log(data.message);
                sendRequest(url, method, data, true, false, null, true);
                $(textbox_id).val('');
            }
        }
        function deleteRider(client_id, rider_id)
        {
            // console.log(client_id + ' , ' + rider_id);
            var url = "{{ url('admin/client') }}" + "/" + client_id + "/removeRider/" + rider_id;
            console.log(url);
            sendDeleteRequest(url, true);
        }
        function updateRiderStatus(rider_id,status){  
    if (status==1) {
       $('#inactive_reasons').modal("show");
       $("form#inactive").on("submit",function(e){
           e.preventDefault();
           var _form = $(this);
           var _modal = _form.parents('.modal');
           var url = "{{ url('admin/rider') }}" + "/" + rider_id + "/updateStatus";
            swal.fire({
                title: 'Are you sure?',
                text: "You want update status!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes!'
            }).then(function(result) {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url : url,
                        type : 'POST',
                        data: _form.serializeArray(),
                        beforeSend: function() {            
                            $('.loading').show();
                        },
                        complete: function(){
                            $('.loading').hide();
                            // window.location.reload();
                        },
                        success: function(data){
                            console.log(data);
                            _modal.modal('hide');
                            swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Record updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function(error){
                            _modal.modal('hide');
                            swal.fire({
                                position: 'center',
                                type: 'error',
                                title: 'Oops...',
                                text: 'Unable to update.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                }
        });
       });
} else {
    // var active_month=$($this).attr("data-active-month");   
    var url = "{{ url('admin/rider') }}" + "/" + rider_id + "/updateStatus";
    swal.fire({
        title: 'Are you sure?',
        text: "You want update status!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes!', 
        // footer: '<p><strong>Last Active Status: </strong>'+active_month+'</p>'
    }).then(function(result) {
        if (result.value) {
            $.ajaxSetup({
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                    // window.location.reload();
                },
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to update.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });    
}
}
    </script>
@endsection