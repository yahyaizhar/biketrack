@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<style>
    .icon_change_password {
        position: absolute;
        top: 35%;
        right: 1%;
        color: #5578eb;
        font-size: 20px;
    }

    .icon_change_password_confirmation {
        position: absolute;
        top: 48%;
        right: 1%;
        color: #5578eb;
        font-size: 20px;
    }

    .custom-file-label {
        overflow: hidden;
    }

    .custom-file-label::after {
        color: white;
        background-color: #5578eb;
    }

    .streric {
        color: red;
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
                            Edit Rider Information
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->

                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.riders.update', $rider) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Full Name: <span class="streric">*</span></label>
                                    <input type="text"
                                        class="form-control @if($errors->has('name')) invalid-field @endif" name="name"
                                        placeholder="Enter full name" value="{{ $rider->name }}">
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
                                    <input type="email"
                                        class="form-control @if($errors->has('email')) invalid-field @endif"
                                        name="email" placeholder="Enter email" value="{{ $rider->email }}">
                                    @if ($errors->has('email'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('email') }}
                                        </strong>
                                    </span>
                                    @else
                                    <span class="form-text text-muted">We'll never share your email with anyone
                                        else</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                @if($rider->profile_picture)
                                <img class="profile-logo img img-thumbnail"
                                    src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="image">
                                @else
                                <img class="profile-logo img img-thumbnail"
                                    src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                                <div class="form-group col-md-6 pull-right mtr-15">
                                    <div class="custom-file">
                                        <input type="file" name="profile_picture" class="custom-file-input"
                                            id="profile_picture">
                                        <label class="custom-file-label" for="profile_picture">Choose Picture</label>
                                    </div>
                                    <span class="form-text text-muted">Select if you want to update picture</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Personal Phone Number:</label>
                            <input type="text" class="form-control" name="phone" placeholder="Enter phone number"
                                value="{{ $rider->phone }}">
                        </div>
                        <div class="form-group">
                            <label>Date Of Birth:</label>
                            <input type="text" id="date_of_birth" autocomplete="off" class="form-control"
                                name="date_of_birth" placeholder="Enter Date Of Birth"
                                value="{{ $rider->date_of_birth }}">
                            <span class="form-text text-muted">Please enter your Date Of Birth</span>
                        </div>

                        <label class="kt-checkbox">
                            <input id="change-password" name="change_password" type="checkbox"
                                {{ old('change_password') ? 'checked' : '' }}> Change Password
                            <span></span>
                        </label>
                        <div id="password-fields" style="display:none;">
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Enter password">
                                <span class="form-text text-muted">Please enter your password</span>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password:</label>
                                <input type="password"
                                    class="form-control @if($errors->has('passsword')) invalid-field @endif"
                                    name="password_confirmation" placeholder="Enter confirm password">
                            </div>
                        </div>
                    <div class="form-group">
                        <label>City:</label>
                        <input type="text" class="form-control" name="address" placeholder="Enter city"
                            value="{{ $rider->address }}">
                    </div>
                    <div class="form-group">
                        <label>Rider Active Date:</label>
                        <input type="text" data-month="{{Carbon\Carbon::parse($rider->active_month)->format('M d, Y')}}"
                            readonly
                            class="month_picker form-control @if($errors->has('active_month')) invalid-field @endif"
                            name="active_month" placeholder="Enter Month">
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Start Time:</label>
                                <input type="hidden" id="start_timer1" name="start_time"
                                    value="{{ $rider->start_time }}">
                                <input type="text" autocomplete="off" id="timepicker1" class="form-control"
                                    placeholder="Start Time" value="{{ $rider->start_time }}">

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>End Time:</label>
                                <input type="hidden" id="start_timer2" name="end_time" value="{{ $rider->end_time }}">
                                <input type="text" autocomplete="off" id="timepicker2" class="form-control"
                                    placeholder="End Time" value="{{ $rider->end_time }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Break Start Time:</label>
                                <input type="hidden" id="start_timer3" name="break_start_time"
                                    value="{{ $rider->break_start_time }}">
                                <input type="text" autocomplete="off" id="timepicker3" class="form-control "
                                    placeholder="Break Start Time" value="{{ $rider->break_start_time }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Break End Time:</label>
                                <input type="hidden" id="start_timer4" name="break_end_time"
                                    value="{{ $rider->break_end_time }}">
                                <input type="text" autocomplete="off" id="timepicker4" class="form-control"
                                    placeholder="Break End Time" value="{{ $rider->break_end_time }}">
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label>Date of Joining:</label>
                        <input type="text" id="datepicker1" autocomplete="off" class="form-control"
                            name="date_of_joining" placeholder="Enter Joining Date"
                            value="{{ $rider_detail->date_of_joining }}">
                        <span class="form-text text-muted">Please enter your Joining Date</span>
                    </div>
                    <div class="form-group">
                        <label>Salary:</label>
                        <input type="text" class="form-control" name="salary" placeholder="Enter An Amount"
                            value="{{ $rider_detail->salary }}">
                        <span class="form-text text-muted">Please enter Rider Salary</span>
                    </div>
                    <div class="form-group">

                        <input type="hidden" class="form-control" name="salik_amount" placeholder="Enter A Salik Amount"
                            value="{{ $rider_detail->salik_amount }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @isset($sim_number)

                            <div class="form-group">

                                <label>Official Given Number:</label>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <input type="text" class="form-control" name="official_given_number"
                                            placeholder="Enter official number"
                                            value="{{ $sim_number!==null?$sim_number->sim_number:'' }}" disabled>
                                        <span class="form-text text-muted">Please enter your official phone
                                            number</span>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" style="width:100px;" class="btn btn-danger"
                                            onclick="updateSimBalance({{$rider->id}})">
                                            EDIT
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="h5">No sim is assigned to this rider.</div>
                                </div>
                                <div class="col-sm-12">
                                    <a href="{{route('SimHistory.addsim', $rider->id)}}"
                                        style="margin-bottom: 15px;width:100px;" class="btn btn-danger">
                                        Assign
                                    </a>
                                </div>
                            </div>
                            @endisset

                        </div>
                    </div>
            <div class="form-group">
                <label>Is Passport Collected:</label>
                <div>
                    <input data-switch="true" name="passport_collected" id="passport_collected" type="checkbox" {!!
                        $rider_detail->passport_collected == 'yes' ? 'checked' : '' !!} data-on-text="yes"
                    data-handle-width="70" data-off-text="no" data-on-color="brand">
                </div>
            </div>
            <div class="row" id="passport_status_no">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="kt-radio">
                            <input type="radio" data-depended=".is_guarantee__employee"
                                @if($rider_detail->is_guarantee=='employee') checked @endif name="is_guarantee"
                            value="employee"> Employee Reference
                            <span></span>
                        </label>
                        <div
                            class="is_guarantee__employee dependend-field @if($rider_detail->is_guarantee!='employee') d-none @endif">
                            @php
                            $e__riders=App\Model\Rider\Rider::where("active_status","A")->where("status","1")->get();
                            @endphp
                            <select id="empoloyee_reference" class="form-control  kt-select2" id="kt_select2_3"
                                name="empoloyee_reference" placeholder="Enter Employee Reference"
                                value="{{ old('empoloyee_reference') }}">
                                @foreach ($e__riders as $e__rider)
                                <option @if($rider_detail->empoloyee_reference==$e__rider->id) selected @endif
                                    value="{{$e__rider->id}}">{{$e__rider->name}}</option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Who referred this rider?</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="kt-radio">
                            <input type="radio" data-depended=".is_guarantee__outsider"
                                @if($rider_detail->is_guarantee=='outsider') checked @endif name="is_guarantee"
                            value="outsider"> Someone else passport
                            <span></span>
                        </label>
                        <textarea type="text" rows="5" autocomplete="off"
                            class="dependend-field @if($rider_detail->is_guarantee!='outsider') d-none @endif is_guarantee__outsider form-control "
                            name="other_passport_given" placeholder="Other person detail">
                            {{ $rider_detail->other_passport_given }}
                    </textarea>
                        <span
                            class="form-text text-muted is_guarantee__outsider dependend-field @if($rider_detail->is_guarantee!='outsider') d-none @endif">Where
                            that person works?</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="kt-radio">
                            <input type="radio" data-depended=".is_guarantee__not_given"
                                @if($rider_detail->is_guarantee=='not_given') checked @endif name="is_guarantee"
                            value="not_given"> Not given
                            <span></span>
                        </label>
                        <textarea type="text" rows="5" autocomplete="off"
                            class="dependend-field @if($rider_detail->is_guarantee!='not_given') d-none @endif is_guarantee__not_given form-control "
                            name="not_given" placeholder="Reason">
                        {{ $rider_detail->not_given }}
                    </textarea>
                        <span
                            class="form-text text-muted is_guarantee__not_given dependend-field @if($rider_detail->is_guarantee!='not_given') d-none @endif">Why
                            not?</span>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12">
                    @if($rider_detail->passport_document_image)
                    <img class="profile-logo img img-thumbnail"
                        src="{{ asset(Storage::url($rider_detail->passport_document_image)) }}" alt="image">
                    @else
                    <img class="profile-logo img img-thumbnail"
                        src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                    @endif
                    <div class="form-group">
                        <label>Passport Image:</label>
                        <div class="custom-file">
                            <input type="file" name="passport_document_image" class="custom-file-input"
                                id="passport_document_image">
                            <label class="custom-file-label" for="passport_document_image">Choose Referral Passport
                                Image</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    @if($rider_detail->agreement_image)
                    <img class="profile-logo img img-thumbnail"
                        src="{{ asset(Storage::url($rider_detail->agreement_image)) }}" alt="image">
                    @else
                    <img class="profile-logo img img-thumbnail"
                        src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                    @endif
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
                <input type="text" class="form-control" name="passport_number" value="{{ $rider_detail->passport_number }}" placeholder="Enter Passport Number">
            </div>
            <div class="form-group" style="margin-bottom:0px;">
                <label>Passport Expiry:</label>
                <input type="text" id="datepicker3" autocomplete="off" class="form-control" name="passport_expiry"
                    placeholder="Enter Passport Expiry" value="{{ $rider_detail->passport_expiry }}">
                <span class="form-text text-muted">Please enter your Passport Expiry Date</span>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    @if($rider_detail->passport_image)
                    <img class="profile-logo img img-thumbnail"
                        src="{{ asset(Storage::url($rider_detail->passport_image)) }}" alt="image">
                    @else
                    <img class="profile-logo img img-thumbnail"
                        src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                    @endif
                    <div class="form-group col-md-6 pull-right mtr-15">
                        <div class="custom-file">
                            <input type="file" name="passport_image" class="custom-file-input" id="passport_image">
                            <label class="custom-file-label" for="passport_image">Choose Passport Picture</label>
                        </div>
                        <span class="form-text text-muted">Select Passport Front Image</span>
                    </div>
                </div>
                {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                                        @if($rider_detail->passport_image_back)
                                            <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_detail->passport_image_back)) }}"
                alt="image">
                @else
                <img class="profile-logo img img-thumbnail"
                    src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                @endif
                <div class="form-group col-md-6 pull-right mtr-15">
                    <div class="custom-file">
                        <input type="file" name="passport_image_back" class="custom-file-input"
                            id="passport_image_back">
                        <label class="custom-file-label" for="passport_image_back">Choose Passport Picture</label>
                    </div>
                    <span class="form-text text-muted">Select Passport Back Image</span>
                </div>
            </div> --}}
        </div>

        <div class="form-group" style="margin-bottom:0px;margin-top:25px;">
            <label>Visa Expiry:</label>
            <input type="text" id="datepicker4" autocomplete="off" class="form-control" name="visa_expiry"
                placeholder="Enter Visa Expiry" value="{{ $rider_detail->visa_expiry }}">
            <span class="form-text text-muted">Please enter your Visa Expiry Date</span>

        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                @if($rider_detail->visa_image)
                <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_detail->visa_image)) }}"
                    alt="image">
                @else
                <img class="profile-logo img img-thumbnail"
                    src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                @endif
                <div class="form-group col-md-6 pull-right mtr-15">
                    <div class="custom-file">
                        <input type="file" name="visa_image" class="custom-file-input" id="visa_image">
                        <label class="custom-file-label" for="visa_image">Choose Visa Picture</label>
                    </div>
                    <span class="form-text text-muted">Select Visa Front Image</span>
                </div>
            </div>
            {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                                            @if($rider_detail->visa_image_back)
                                                <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_detail->visa_image_back)) }}"
            alt="image">
            @else
            <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
            @endif
            <div class="form-group col-md-6 pull-right mtr-15">
                <div class="custom-file">
                    <input type="file" name="visa_image_back" class="custom-file-input" id="visa_image_back">
                    <label class="custom-file-label" for="visa_image_back">Choose Visa Picture</label>
                </div>
                <span class="form-text text-muted">Select Visa Back Image</span>
            </div>
        </div> --}}

    </div>


    <div class="form-group" style="margin-bottom:0px;margin-top:25px;">
        <label>Emirates ID:</label>
        <input type="text" class="form-control" name="emirate_id" placeholder="Enter Emirate ID"
            value="{{ $rider_detail->emirate_id }}">
        <span class="form-text text-muted">Please enter your Emirate ID.</span>

    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            @if($rider_detail->emirate_image)
            <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_detail->emirate_image)) }}"
                alt="image">
            @else
            <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
            @endif
            <div class="form-group col-md-6 pull-right mtr-15">
                <div class="custom-file">
                    <input type="file" name="emirate_image" class="custom-file-input" id="emirate_image">
                    <label class="custom-file-label" for="emirate_image">Choose Emirate Picture</label>
                </div>
                <span class="form-text text-muted">Select Emirate Front Image</span>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            @if($rider_detail->emirate_image_back)
            <img class="profile-logo img img-thumbnail"
                src="{{ asset(Storage::url($rider_detail->emirate_image_back)) }}" alt="image">
            @else
            <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
            @endif
            <div class="form-group col-md-6 pull-right mtr-15">
                <div class="custom-file">
                    <input type="file" name="emirate_image_back" class="custom-file-input" id="emirate_image_back">
                    <label class="custom-file-label" for="emirate_image_back">Choose Emirate Picture</label>
                </div>
                <span class="form-text text-muted">Select Emirate Back Image</span>
            </div>
        </div>
    </div>


    <div class="form-group" style="margin-bottom:0px;margin-top:25px;">
        <label>Licence Expiry:</label>
        <input type="text" id="datepicker5" autocomplete="off" class="form-control " name="licence_expiry"
            placeholder="Enter Licence Expiry" value="{{ $rider_detail->licence_expiry }}">

        <span class="form-text text-muted">Please enter your Licence Expiry Date</span>

    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            @if($rider_detail->licence_image)
            <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($rider_detail->licence_image)) }}"
                alt="image">
            @else
            <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
            @endif
            <div class="form-group col-md-6 pull-right mtr-15">
                <div class="custom-file">
                    <input type="file" name="licence_image" class="custom-file-input" id="licence_image">
                    <label class="custom-file-label" for="licence_image">Choose Licence Picture</label>
                </div>
                <span class="form-text text-muted">Select Licence Front Image</span>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            @if($rider_detail->licence_image_back)
            <img class="profile-logo img img-thumbnail"
                src="{{ asset(Storage::url($rider_detail->licence_image_back)) }}" alt="image">
            @else
            <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
            @endif
            <div class="form-group col-md-6 pull-right mtr-15">
                <div class="custom-file">
                    <input type="file" name="licence_image_back" class="custom-file-input" id="licence_image_back">
                    <label class="custom-file-label" for="licence_image_back">Choose Licence Picture</label>
                </div>
                <span class="form-text text-muted">Select Licence Back Image</span>
            </div>
        </div>

    </div>


    <div class="form-group" style="margin-top:25px;">
        <label>Other Details:</label>
        <textarea type="text" rows="8" autocomplete="off"
            class="form-control @if($errors->has('other_details')) invalid-field @endif" name="other_details"
            placeholder="Enter Further Details">{{ $rider_detail->other_details }}</textarea>
    </div>
</div>
<div class="kt-portlet__foot">
    <div class="kt-form__actions kt-form__actions--right">
        <button type="submit" class="btn btn-primary">Submit</button>
        <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}"
                class="kt-link kt-font-bold">Cancel</a></span>
    </div>
</div>

</form>

<!--end::Form-->
</div>

<!--end::Portlet-->
</div>
</div>
<div>

    @isset($sim_date)

    <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">Create Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" id="form_dates" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Allowed Balance:</label>
                            <input type="text"
                                class="form-control @if($errors->has('allowed_balance')) invalid-field @endif"
                                name="allowed_balance" placeholder="Allowed Balance "
                                value="{{$sim_date?$sim_date->allowed_balance:''}}">
                            @if ($errors->has('allowed_balance'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{$errors->first('allowed_balance')}}
                                </strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Given Date:</label>
                            <input type="text" id="datepicker_given" autocomplete="off"
                                class="form-control @if($errors->has('given_date')) invalid-field @endif"
                                name="given_date" placeholder="Enter given Date"
                                value="{{$sim_date?$sim_date->given_date:''}}">
                            @if ($errors->has('given_date'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{ $errors->first('given_date') }}
                                </strong>
                            </span>
                            @else
                            <span class="form-text text-muted">Please enter your Given Date</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Return Date:</label>
                            <input type="text" id="datepicker_return" autocomplete="off"
                                class="form-control @if($errors->has('return_date')) invalid-field @endif"
                                name="return_date" placeholder="Enter Return Date"
                                value="{{$sim_date?$sim_date->return_date:''}}">
                            @if ($errors->has('return_date'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{ $errors->first('return_date') }}
                                </strong>
                            </span>
                            @else
                            <span class="form-text text-muted">Please enter your Return Date</span>
                            @endif
                        </div>



                    </div>
                    <div class="modal-footer border-top-0 d-flex justify-content-center">
                        <button type="submit" id="submit_btn_dates" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endisset

</div>
@endsection
@section('foot')
{{-- start_timer , timepicker1 --}}
<script>
    $(document).ready(function () {


        $("[name='password']").after(
            '<div class="icon_change_password" data-target="password"><i class="fa fa-eye for_password"></i></div>'
        );
        $(".icon_change_password ").parent().addClass("position-relative");
        $(".icon_change_password").on("click", function () {
            if ($("[name='password']").attr("type") == "password") {
                $("[name='password']").attr("type", "text");
                $(".for_password").removeClass("fa fa-eye ");
                $(".for_password").addClass("fa fa-eye-slash");
            } else if ($("[name='password']").attr("type") == "text") {
                $("[name='password']").attr("type", "password");
                $(".for_password").removeClass("fa fa-eye-slash ");
                $(".for_password").addClass("fa fa-eye");
            }
        });
        $("[name='password_confirmation']").after(
            '<div class="icon_change_password_confirmation" data-target="password"><i class="fa fa-eye for_password_confirmation"></i></div>'
        );
        $(".icon_change_password_confirmation").parent().addClass("position-relative");
        $(".icon_change_password_confirmation").on("click", function () {
            if ($("[name='password_confirmation']").attr("type") == "password") {
                $("[name='password_confirmation']").attr("type", "text");
                $(".for_password_confirmation").removeClass("fa fa-eye ");
                $(".for_password_confirmation").addClass("fa fa-eye-slash");
            } else if ($("[name='password_confirmation']").attr("type") == "text") {
                $("[name='password_confirmation']").attr("type", "password");
                $(".for_password_confirmation").removeClass("fa fa-eye-slash ");
                $(".for_password_confirmation").addClass("fa fa-eye");
            }
        });


        $('.kt-select2').select2({
            placeholder: "Select an option",
            width: '100%'
        });
        // $('.dependend-field').hide('fast');
        // $(':radio[data-depended]').trigger('change');
        $(':radio[data-depended]').on('change', function () {
            $('.dependend-field').removeClass('d-none');
            if (!$(this).is(':checked')) return false;
            $('[name="passport_document_image"]')
                .siblings('.custom-file-label')
                .text('Choose Passport Image')
                .parents('.custom-file')
                .siblings('label')
                .text('Passport Image');
            var _dependend = $(this).attr('data-depended');
            if ($(this).val().trim() == 'not_given') {
                $('[name="passport_document_image"]')
                    .siblings('.custom-file-label')
                    .text('Choose Document Image')
                    .parents('.custom-file')
                    .siblings('label')
                    .text('Document Image');
            }
            $('.dependend-field').val('').hide('fast');
            $('#empoloyee_reference')[0].selectedIndex = -1;
            $('#empoloyee_reference').trigger('change');
            $(_dependend).fadeIn('fast');
        })
        // time 1
        $('#timepicker1').change(function () {
            var a = $('#timepicker1').val();
            var getUTC_date = new Date(a.toDate('h:m')).format('HH:MM', true);
            $("#start_timer1").val(getUTC_date);
        });

        var b = $('#start_timer1').val();
        var getlocal_date = new Date((b).toDate('h:m').format('yyyy-mm-dd HH:MM:ss') + ' UTC').format('HH:MM');
        $('#timepicker1').val(getlocal_date);
        // end time 1
        // time 2
        $('#timepicker2').change(function () {
            var a = $('#timepicker2').val();
            var getUTC_date = new Date(a.toDate('h:m')).format('HH:MM', true);
            $("#start_timer2").val(getUTC_date);
        });
        var b = $('#start_timer2').val();
        var getlocal_date = new Date((b).toDate('h:m').format('yyyy-mm-dd HH:MM:ss') + ' UTC').format('HH:MM');
        $('#timepicker2').val(getlocal_date);
        // end time 2
        // time 3
        $('#timepicker3').change(function () {
            var a = $('#timepicker3').val();
            var getUTC_date = new Date(a.toDate('h:m')).format('HH:MM', true);
            $("#start_timer3").val(getUTC_date);
        });
        var b = $('#start_timer3').val();
        var getlocal_date = new Date((b).toDate('h:m').format('yyyy-mm-dd HH:MM:ss') + ' UTC').format('HH:MM');
        $('#timepicker3').val(getlocal_date);
        // end time 3
        // time 4
        $('#timepicker4').change(function () {
            var a = $('#timepicker4').val();
            var getUTC_date = new Date(a.toDate('h:m')).format('HH:MM', true);
            $("#start_timer4").val(getUTC_date);
        });
        var b = $('#start_timer4').val();
        var getlocal_date = new Date((b).toDate('h:m').format('yyyy-mm-dd HH:MM:ss') + ' UTC').format('HH:MM');
        $('#timepicker4').val(getlocal_date);
        // end time 4



    });

</script>
{{-- timepicker --}}
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">


<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js">
</script>

{{-- end time picker --}}
<script>
    $(document).ready(function () {
        $('#timepicker1').fdatepicker({
            format: 'hh:ii',
            startView: 1,
            maxView: 0,
            pickTime: true,
        });
        $('#timepicker2').fdatepicker({
            format: 'hh:ii',
            startView: 1,
            maxView: 0,
            pickTime: true
        });
        $('#timepicker3').fdatepicker({
            format: 'hh:ii',
            startView: 1,
            maxView: 0,
            pickTime: true
        });
        $('#timepicker4').fdatepicker({
            format: 'hh:ii',
            startView: 1,
            maxView: 0,
            pickTime: true
        });
    });

</script>

<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}"
    type="text/javascript"></script>
<script>
    $(document).ready(function () {
        if ($('#change-password').prop('checked') == true) {
            $('#password-fields').show();
        }
        $('#change-password').change(function () {
            $('#password-fields').fadeToggle();
        });
    });

</script>
<script>
    $(document).ready(function () {
        window.onscroll = function () {
            myFunction()
        };

        function myFunction() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("myBar").style.width = scrolled + "%";
        }

    });

</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        $('#datepicker1').fdatepicker({
            format: 'dd-mm-yyyy'
        });
        $('#datepicker2').fdatepicker({
            format: 'dd-mm-yyyy'
        });
        $('#datepicker3').fdatepicker({
            format: 'dd-mm-yyyy'
        });
        $('#datepicker4').fdatepicker({
            format: 'dd-mm-yyyy'
        });
        $('#datepicker5').fdatepicker({
            format: 'dd-mm-yyyy'
        });
        $('#datepicker6').fdatepicker({
            format: 'dd-mm-yyyy'
        });
        $('#date_of_birth').fdatepicker({
            format: 'dd-mm-yyyy'
        });
    });

</script>

<script>
    $(document).ready(function () {
        $('#datepicker_given').fdatepicker({
            format: 'dd-mm-yyyy'
        });
        $('#datepicker_return').fdatepicker({
            format: 'dd-mm-yyyy'
        });

    });

</script>
{{-- edit_sim_number --}}
<script>
    function updateSimBalance(rider_id) {
        $('#form').modal('show');
        // submit_btn_dates
        $('#form_dates').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('admin/update/History')}}" + '/' + rider_id + '/Sim',
                data: form.serializeArray(),
                method: "POST"
            })
            .done(function (data) {
                console.log(data);
                $('#form').modal('toggle');

            });

        });
    }

</script>
<script>
    $(document).ready(function () {

        var _checked1 = $("#passport_collected").prop("checked");
        if (_checked1 == false) {
            $("#passport_status_no").show();
        } else if (_checked1 == true) {
            $("#passport_status_no").hide();
        }

        $("#passport_collected").on("switchChange.bootstrapSwitch", function () {
            var a = $("#passport_collected").attr("data-off-text");
            var _checked = $(this).prop("checked");
            if (_checked == false) {
                $("#passport_status_no").show().fadeIn(3000);
            } else if (_checked == true) {
                $("#passport_status_no").hide().fadeOut(3000);
                $("#empoloyee_reference")[0].selectedIndex = -1;
                $("#other_passport_given").val("");
                $("#not_given").val("");
            }
        });
    });

</script>

@endsection
