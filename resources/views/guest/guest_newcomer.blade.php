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
                            Welcome to king Rider Delivery Service LLC
                        </h3>
                    </div>
            
                </div>
                <div class="custmstructure">
                        <h5 style=" padding: 15px;color: #57445c;text-align: center; margin-bottom: 15px;">Apply For Job</h5>
                        <div class="row">
                            <div class="col-sm-6 text-center">
                           <div class="biker_image">
                               <img class="img img-thumbnail" style=" width: 50%;" src="{{ asset('dashboard/assets/media/newcomer/bike.png') }}">
                               <div style=" padding-top: 12px;">
                                  <p class="custmclick" style="cursor: pointer;border: 1px solid; display: inline-block; padding: 6px 25px;">Bike rider</p>
                               </div>
                           </div>
                            </div>
                            <div class="col-sm-6 text-center">
                               <div class="car_image">
                                   <img class="img img-thumbnail" style=" width: 50%;" src="{{ asset('dashboard/assets/media/newcomer/car.jpg') }}">
                                   <div style=" padding-top: 12px;">
                                  <p class="custmclick" style="cursor: pointer;border: 1px solid; display: inline-block; padding: 6px 25px;">Car driver</p>
                                   </div>
                               </div>
                            </div>
                        </div>
                </div>
                <!--begin::Form-->
                
                <div class="alreay_registred" style="margin: 0px auto;padding: 20px 25px;"> 
                    <h5 style="color: #57445c;">Already Applied for job in King Riders?</h5>
                                  <button class="btn btn-info custm_hidden_btn float-right" style="text-align: center; width: 100%;margin-bottom: 10px;margin-top: 12px; font-size: 16px;">Check your application status</button>
                       <div class="divtoappnd">
                                  <div class="hidde_status_form" style="display:none;">
                       <form method="POST" enctype="multipart/form-data">
                       <div class="form-group">
                           <label>Emirates i'd:</label>
                           <input class="form-control" name="national_id_card_no" id="national_id_card_no" type="text">
                           <button name="submit" type="submit" class="btn btn-success" style="margin-top: 10px;">Submit</button>
                       </div>
                       </form>
                        </div>
                    <div class="approval_message" style="display:none;overflow: hidden;width: 100%;">
                        </div>
                    </div>
                </div>
                <form class="kt-form" action="{{ route('guest.newComer_add') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body" style="display:none;">
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
                          <input type="hidden" name="applying_for"> 
                                
                        <div class="form-group">
                            <label>Full Name:</label>
                            <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="full_name" placeholder="Enter your name" value="{{ old('name') }}" required>
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
                                <label>Email:</label>
                                <input type="email" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter your name" value="{{ old('email') }}" required>
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

                        <div class="form-group">
                            <label>Nationality:</label>
                            {{-- <input type="text" >  --}}
                            <select id="country" class="form-control @if($errors->has('nationality')) invalid-field @endif" name="nationality" placeholder="Nationality" value="{{ old('nationality') }}" required>
                                    <option value="">Please select your country</option>
                                    <option value="Afganistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire">Bonaire</option>
                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Canary Islands">Canary Islands</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Channel Islands">Channel Islands</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Island">Cocos Island</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaco">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="India">India</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea Sout">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Nambia">Nambia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Phillipines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uraguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                 </select>
                            @if ($errors->has('nationality'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('nationality') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Contact No:</label>
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
                            <label>Emirates i'd:</label>
                            <input type="text" class="form-control" name="national_id_card_number" id="national_id_card_number" placeholder="Enter National id card number" required> 
                        </div>
                        <div class="form-group">
                            <label>WhatsApp Number:</label>
                            <input type="text" class="form-control @if($errors->has('whatsapp_number')) invalid-field @endif" name="whatsapp_number" placeholder="Whatsapp Number" value="{{ old('whatsapp_number') }}" required> 
                            @if ($errors->has('whatsapp_number'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('whatsapp_number') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Education Level:</label>
                            <select id="education_level" class="form-control @if($errors->has('education')) invalid-field @endif" name="education" placeholder="Education" value="{{ old('education') }}" required> 
                            <option>Matric / O-level</option>
                            <option>Intermediate / Deploma / A-level </option>
                            <option>Bachelor / Graduated</option>
                            </select>
                            @if ($errors->has('education'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('education') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        
                        <div class="form-group">
                                <label>Do you have your own bike / car .?</label>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control " id="have_bike" name="have_bike" style="width: 15px !important;" value="yes" required=""><h6 style="margin-top: 10px; margin-left: 10px; cursor: pointer;">Yes</h6></div>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control " id="have_bike" name="have_bike" style="width: 15px !important;" value="no" required=""><h6 style="margin-top: 10px; margin-left: 10px; cursor: pointer;">NO</h6></div>
                        
                            </div>
                        <div class="form-group">
                                <label>Visa status:</label>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control " id="visa_status" name="visa_status" style="width: 15px !important;" value="employment" required=""><h6 style="margin-top:10px;margin-left:10px;">Employment</h6></div>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control " id="visa_status" name="visa_status" style="width: 15px !important;" value="cancellation" required=""><h6 style="margin-top:10px;margin-left:10px;">Cancellation</h6></div>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control " id="visa_status" name="visa_status" style="width: 15px !important;" value="visit" required=""><h6 style="margin-top:10px;margin-left:10px;">Visit</h6></div>
                        </div>
                        <div class="form-group noc_status" style="display:none;">
                                <label>Do you have Noc:</label>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control "  name="noc_status" style="width: 15px !important;" value="yes"><h6 style="margin-top:10px;margin-left:10px;">Yes</h6></div>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control "  name="noc_status" style="width: 15px !important;" value="no"><h6 style="margin-top:10px;margin-left:10px;">No</h6></div>
                        </div>
                        <div class="form-group">
                                <label>Do you have license:</label>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control " id="license_true" name="license_check" style="width: 15px !important;" value="yes" required=""><h6 style="margin-top:10px;margin-left:10px;">Yes</h6></div>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control " id="license_false" name="license_check" style="width: 15px !important;" value="no" required=""><h6 style="margin-top:10px;margin-left:10px;">No</h6></div>

                        </div>
                        <div class="form-group license_number" style="display:none">
                                <label>Enter License Number:</label>
                                <input type="text" name="license_number" class="form-control @if($errors->has('licence_number')) invalid-field @endif" id="license_number" autocomplete="off" placeholder="Licence Number" value="{{ old('licence_number') }}">
                        </div>
                        <div class="form-group licence_date" style="display:none;">
                            <label>Licence Issue Date:</label>
                            <input type="text" id="licence_date" autocomplete="off" class="form-control @if($errors->has('licence_issue_date')) invalid-field @endif" name="licence_issue_date" placeholder="Licence Issue Date" value="{{ old('licence_issue_date') }}"> 
                            @if ($errors->has('licence_issue_date'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('licence_issue_date') }}
                                    </strong>
                                </span>
                            
                            @endif
                        </div>
                        <div class="form-group license_image" style="display:none">
                            <label>License Image:</label>
                            <label class="btn btn-info" style=" color: white;"> Upload picture <input name="license_image" id="license_image" type="file" hidden=""> </label>
                            {{-- <input type="file" name="license_image" class="form-control @if($errors->has('licence_image')) invalid-field @endif" id="license_image" autocomplete="off" value="{{ old('licence_image') }}"> --}}
                        </div>  
                        <div class="form-group">
                            <label for="experience">How much do you have riding experience in UAE:</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 15px !important;" value="Fresh" required /><h6 style="margin-top:10px;margin-left:10px;">Fresh</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 15px !important;" value="6 months"  required /><h6 style="margin-top:10px;margin-left:10px;">6 months</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 15px !important;" value="1 Year"  required /><h6 style="margin-top:10px;margin-left:10px;">1 Year</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 15px !important;" value="2 Year"  required /><h6 style="margin-top:10px;margin-left:10px;">2 Year</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('experiance')) invalid-field @endif" id="experiance" name="experiance" style="width: 15px !important;" value="More Than 2 Year"  required /><h6 style="margin-top:10px;margin-left:10px;">More Than 2 Year</h6></div>
                        </div>  
                        <div class="form-group">
                            <label for="passport_status">Do you have passport?</label>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('passport_status')) invalid-field @endif" id="passport_status" name="passport_status" style="width: 15px !important;" value="yes"  required/><h6 style="margin-top:10px;margin-left:10px;">Yes</h6></div>
                           <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('passport_status')) invalid-field @endif" id="passport_status" name="passport_status" style="width: 15px !important;" value="no"  required /><h6 style="margin-top:10px;margin-left:10px;">No</h6></div>
                          
                           <label for="passport_number" class="passport_number_label" style="display:none;">Enter Passport Number:</label>
                           <input type="text"  id="passport_number" style="display:none;" autocomplete="off"class="form-control @if($errors->has('passport_number')) invalid-field @endif" name="passport_number" placeholder="Passport Number" value="{{ old('passport_number') }}"> 
                           
                           <label for="passport_image" class="passport_image_label" style="display:none;">Passport Image:</label>
                           <label class="btn btn-info" id="passport_image" style="color: white;display:none;"> Upload picture <input name="passport_image" type="file" hidden=""> </label>

                           {{-- <input type="file"  id="passport_image" style="display:none;" autocomplete="off"class="form-control @if($errors->has('passport_image')) invalid-field @endif" name="passport_image" placeholder="Passport Image" value="{{ old('passport_image') }}">  --}}
                       </div>
                       <div class="form-group">
                            <label for="current_residence">Your current residence:</label>
                            <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('current_residence')) invalid-field @endif" id="current_residence" name="current_residence" style="width: 15px !important;" value="uae"  required/><h6 style="margin-top:10px;margin-left:10px;">United Arab Emirates</h6></div>
                            <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('current_residence')) invalid-field @endif" id="current_residence" name="current_residence" style="width: 15px !important;" value="other"  required/><h6 style="margin-top:10px;margin-left:10px;">Other</h6></div>
                       <div class="current_residence_countries" style="display:none;">
                            <select id="current_residence_countries" class="form-control" name="current_residence_countries" placeholder="Select your country" value="{{ old('current_residence_countries') }}" required>
                                    <option value="Afganistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire">Bonaire</option>
                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Canary Islands">Canary Islands</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Channel Islands">Channel Islands</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Island">Cocos Island</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaco">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="India">India</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea Sout">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Nambia">Nambia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Phillipines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uraguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                 </select>
                       </div>
                       </div>
                        <div class="form-group">
                                <label>From which source do you know KingRiders Company:</label>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('source')) invalid-field @endif" id="source" name="source" style="width: 15px !important;" value="Friends" required /><h6 style="margin-top:10px;margin-left:10px;">Friends</h6></div>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('source')) invalid-field @endif" id="source" name="source" style="width: 15px !important;" value="social" required /><h6 style="margin-top:10px;margin-left:10px;">Social media</h6></div>
                                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control @if($errors->has('source')) invalid-field @endif" id="source" name="source" style="width: 15px !important;" value="Ads" required /><h6 style="margin-top:10px;margin-left:10px;">Ads</h6></div>

                        </div>
                        <div class="form-group">
                            <label>Want to tell anything else about yourself:</label>
                            <textarea  class="form-control @if($errors->has('overall_remarks')) invalid-field @endif" name="overall_remarks" placeholder="About yourself" required>{{ old('overall_remarks') }}</textarea>
                            @if ($errors->has('overall_remarks'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('overall_remarks') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">About yourself</span>
                            @endif
                        </div>
                         </div>
                    
                 

                    <div class="kt-portlet__foot" style="display:none;">
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


///Make natianality select 2
// $('#country').select2({
//     placeholder: "Select a Country",
//     allowClear: true
// });
// $('#education_level').select2({
//     placeholder: "Select Education",
//     allowClear: true
// });

//// show hide license info in newcomer form

$('[name="license_check"]').change(function(){
    if($(this).val()== 'yes'){
        $('.license_number').show();
        $('.license_image').show();
        $('.licence_date').show();
    }
    else{
        $('.license_number').hide();
        $('.license_image').hide();
        $('.licence_date').hide();
    }
})
$('[name="passport_status"]').change(function(){
    if($(this).val()== 'yes'){
        $('#passport_number').show();
        $('.passport_number_label').show();
        $('#passport_image').show();
        $('.passport_image_label').show();
    }
    else{
        $('#passport_number').hide();
        $('.passport_number_label').hide();
        $('#passport_image').hide();
        $('.passport_image_label').hide();
    }
})
$('[name="visa_status"]').change(function(){
    if($(this).val()== 'employment'){
        $('.noc_status').show();
    }
    else{
        $('.noc_status').hide();
    }
})
$('[name="current_residence"]').change(function(){
    if($(this).val()== 'other'){
        $('.current_residence_countries').show();
    //    $('#current_residence_countries').select2({
    //      placeholder: "Select a Country",
    //      allowClear: true
    //   });
    }
    else{
        $('.current_residence_countries').hide();
    }
})
$('.custm_hidden_btn').click(function(){
    var _dataa = $('.divtoappnd').html();
    $('#mics_charges').find('.modal-body').html(_dataa);
    $('#mics_charges').modal('show')
    $('.modal-body .hidde_status_form').show();
    var a = $('.approval_message').html();
    $('.modal-body').append(a)

$('.modal-body').find('form').off('submit').on('submit', function(e){
                        e.preventDefault();
                        var _form = $(this);
                        var _url ="{{url('guest/newcomer/status_check')}}";
                        $.ajax({
                            url : _url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type : 'POST',
                            data: _form.serialize(),
                            success: function(data){
                                console.log(data);
                                if(data == 'error'){
                                $('.modal-body .approval_message').show();
                                $('.modal-body .approval_message').css('color','red');
                                $('.modal-body .approval_message').html('<ul><li>No data found against your national id card number.Please submit you application if you have not applied yet.</li></ul>')
                                }
                               else{
                                   if(data[0].approval_status =="pending"){
                                $('.modal-body .approval_message').show();
                                $('.modal-body .approval_message').css('color','red'); 
                                $('.modal-body .approval_message').html('<ul><li>Your application is still pending.We will inform you shortly within a week.</li></ul>');
                                   }
                                else if(data[0].approval_status == "reject"){
                                if(data[0].status_approval_message !== null){
                                $('.modal-body .approval_message').show();
                                $('.modal-body .approval_message').css('color','red'); 
                                $('.modal-body .approval_message').html('<ul><li>Your application is reject </li><li> Application status message:'+data[0].status_approval_message+'</li></ul>');
                                if(data[0].missing_fields !== null){
                                $('.modal-body .approval_message ul').append('<li>Click on this link to edit your profile <a href="newcomer/'+data[0].id+'/update">Click here</a></li>')
                                }
                                }else{
                                $('.modal-body .approval_message').show();
                                $('.modal-body .approval_message').css('color','red'); 
                                $('.modal-body .approval_message').html('<ul><li>Your application is rejected.</li></ul>');
                                if(data[0].missing_fields !== null){
                                $('.modal-body .approval_message ul').append('<li>Click on this link to edit your profile <a href="newcomer/'+data[0].id+'/update">Click here</a></li>')
                                }
                                }
                                    
                                }
                                else{
                                $('.modal-body .approval_message').show();
                                $('.modal-body .approval_message').css('color','red'); 
                                if(data[0].interview_date ==''){
                                $('.modal-body .approval_message').html('<ul><li>Your application has been Approved. We will inform you about your interview soon.</li></ul>');
                                }
                                else{
                                if(data[0].interview_status =='pending'){
                                    $('.modal-body .approval_message').html('<ul><li>Your application has been Approved</li><li>Your Interview is scheduled on '+data[0].interview_date+'</li></ul>');
                                }
                                else{
                                    $('.modal-body .approval_message').html('<ul><li>Your application has been Approved</li><li>Your Interview is scheduled on '+data[0].interview_date+'</li><li>Interview status: '+data[0].interview_status+'</li><li>Interview Message: '+data[0].interview_status_message+'</li></ul>');

                                }
                                }
                                }
                               }
                            },
                            error: function(error){
                            }
                        });
                    });
                })
                $('.custmclick').click(function(){
                    var _index = $(this).text().indexOf('Bike');
                    $('.kt-portlet__body').show();
                    $('.kt-portlet__foot').show();
                    $('.custmstructure').hide();
                    $('.alreay_registred').hide();
                    if(_index > -1){
                        $('.kt-portlet__head-title').text('Bike Rider Job Registration Form')
                        $('[name="applying_for"]').val('bike')
                    }
                    else{
                        $('.kt-portlet__head-title').text('Car Driver Job Registration Form')
                        $('[name="applying_for"]').val('car')
                    }
                    })
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