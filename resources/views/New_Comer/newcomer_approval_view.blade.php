@extends('admin.layouts.app')
@section('head')
<!--begin::Page Vendors Styles(used by this page) -->
<link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
.highlighted{
    background-color: #FFFF88;
}
.dataTables_length{ 
display: block;   
}
.total_entries{
display: inline-block;
margin-left: 10px;
}
.dataTables_info{
display:none;
}
.customFilters {
padding: 10px;
}
</style>
<!--end::Page Vendors Styles -->
@endsection
@section('main-content')
<!-- begin:: Content -->
@include('admin.includes.message')

{{-- ////modal --}}


<div class="modal fade custom_approval_comer_model" id="quick_view" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document" style=" max-width: 70%">
<div class="modal-content"  style=" padding: 5px 10px;">
    <div class="modal-header border-bottom-0">
        <h5 class="modal-title text-center" style=" margin: 0px auto; width: 100%;">New Commer Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body custm_body">
    <div class="row">
        <div class="col-md-8"> 
        <table class="new_commer_approval_table">
        <tr>
            <th>Name:</th>
            <td class="approval_full_name">Full Name</td>
            <th>Phone number:</th>
            <td class="approval_phone_no">Full Name</td>
        <tr>
        <tr>
            <th>Nationality:</th>
            <td class="approval_nationality">Full Name</td>
            <th>Experience:</th>
            <td class="approval_experience">Full Name</td>
        <tr>
        <tr>
                <th>Emirates id:</th>
                <td class="approval_id_card_no">Full Name</td>
                <th>Education:</th>
                <td class="approval_education">Full Name</td>
        <tr>
        <tr>
                <th>License:</th>
                <td class="approval_license">Full Name</td>
                <th>License number:</th>
                <td class="approval_license_no">Full Name</td>
        <tr>
        <tr>
                <th>License issue date:</th>
                <td class="approval_license_date">Full Name</td>
                <th>Apply for:</th>
                <td class="approval_source">Full Name</td>

        <tr>
        <tr>
                <th>Watsapp number:</th>
                <td class="approval_w_no">Full Name</td>
                <th>Passport status:</th>
                <td class="approval_passport_status">Full Name</td>
        </tr>
        <tr>
                <th>Passport number:</th>
                <td class="approval_passport_no">Full Name</td>
                <th>Current residence:</th>
                <td class="approval_residence">Full Name</td>
        </tr>
        <tr>
                <th>Passport status:</th>
                <td class="approval_passport_status">Full Name</td>
                <th>Application status:</th>
                <td class="approval_app_status">Full Name</td>
        </tr>
        <tr>
                <th>Interview status:</th>
                <td class="approval_interview_status">Full Name</td>
                <th>Visa status:</th>
                <td class="approval_visa_status">Full Name</td>
        </tr>
        <tr>
                <th>Noc status:</th>
                <td class="approval_noc_status">Full Name</td>
                <th>Email:</th>
                <td class="newcomer_email">Full Name</td>
        </tr>
        <tr>
            <th>Have bike.?</th>
            <td class="newcomer_hasbike">Full Name</td>
            <th>Source:</th>
            <td class="newcomer_source">Full Name</td>
        </tr>

        </table>
    <div class="form-group">
        <label style="font-weight: 800;">Extra reviews:</label>
        <textarea readonly class="new_commer_reviews form-control"></textarea>
    </div>
        </div>
        <div class="col-md-4">
            <div class="approval_custom_images_section">
            <a href="" target="_blank"><img src="" style="width: 260px;"></a>
            <ul class="list-group list-group-horizontal" style="margin-top: 60px;">
                <li class="list-group-item active profile" img_src="">Profile picture</li>
                <li class="list-group-item license" img_src="">License picture</li>
                <li class="list-group-item passport" img_src="">Passport picture</li>
            </ul>
            </div>
        </div>

    </div>
    </div>
    <div class="modal-footer">
        <div class="footer_f_form" style="display:none;width:100%;">
        <form style="width:100%;" action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Approval status message</label>
            <textarea class="form-control" name="status_approval_message" id="status_approval_message"></textarea>
            <input name="new_commer_id" id="new_commer_id" type="hidden">
        </div>
        <div class="form-group">
            <label>Approval status</label>
            <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="approval_status" name="approval_status" style="width: 2% !important;" value="approve"  required /><h6 style="margin-top:10px;margin-left:10px;">Approve</h6></div>
            <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="approval_status" name="approval_status" style="width: 2% !important;" value="reject"  required /><h6 style="margin-top:10px;margin-left:10px;">Reject</h6></div>
            <input style="display:none;" type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker2 form-control @if($errors->has('given_date')) invalid-field @endif" name="interview_date" placeholder="Enter Given Date" value="">
        </div>
        <div class="form-group custm_reject-reason" style="display:none;">
        <label style=" padding-bottom: 10px;">Which points are missing or wrong</label>

        <div class="row">
            <div class="col-md-3">
            <label class="kt-checkbox">
            <input name="missing_data['newcommer_image']" type="checkbox" value="newcommer_image"> Image:
            <span></span>
            </label>
        </div>
        <div class="col-md-3">
                <label class="kt-checkbox">
                    <input name="missing_data['license_number']" type="checkbox" value="license_number"> License number:
                    <span></span>
                </label>
            </div>
            <div class="col-md-3">
                <label class="kt-checkbox">
                    <input name="missing_data['license_issue_date']" type="checkbox" value="license_issue_date"> License issue date:
                    <span></span>
                </label>
            </div>
            <div class="col-md-3">
                <label class="kt-checkbox">
                    <input name="missing_data['license_image']" type="checkbox" value="license_image"> License image:
                    <span></span>
                </label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                    <label class="kt-checkbox">
                    <input name="missing_data['passport_number']" type="checkbox" value="passport_number"> Passport number:
                    <span></span>
                    </label>
            </div>
            <div class="col-md-3">
                    <label class="kt-checkbox">
                    <input name="missing_data['passport_image']" type="checkbox" value="passport_image"> Passport image:
                    <span></span>
                    </label>
            </div>
            <div class="col-md-3">
                    <label class="kt-checkbox">
                        <input name="missing_data['email']" type="checkbox" value="email"> Email:
                        <span></span>
                    </label>
                </div>
                <div class="col-md-3">
                    <label class="kt-checkbox">
                        <input name="missing_data['phone_number']" type="checkbox" value="phone_number"> Contact No:
                        <span></span>
                    </label>
                </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                    <label class="kt-checkbox">
                        <input name="missing_data['national_id_card_number']" type="checkbox" value="national_id_card_number"> Emirates i'd::
                        <span></span>
                    </label>
                </div>
                <div class="col-md-3">
                    <label class="kt-checkbox">
                        <input name="missing_data['whatsapp_number']" type="checkbox" value="whatsapp_number"> WhatsApp Number:
                        <span></span>
                    </label>
                </div>
        </div>

        </div>
        <div class="form-group"> 
            <button class="btn btn-success" name="submit" type="submit">Submit</button>
        </div>
        </form>
    </div>
    <div class="footer_s_form" style="display:none;width:100%;">
            <form style="width:100%;" action="" method="POST" enctype="multipart/form-data">
                <input name="new_commer_id" id="new_commer_id" type="hidden">

                <div class="form-group">
                    <label>Interview by</label>
                    <input name="interview_by" id="interview_by" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label>Interview status message</label>
                    <textarea name="interview_status_message" id="interview_status_message" type="text" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Interview status</label>
                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="interview_status" name="interview_status" style="width: 2% !important;" value="approve"  required /><h6 style="margin-top:10px;margin-left:10px;">Approve</h6></div>
                <div style="display: flex;margin-left:20px;"> <input type="radio" class="form-control" id="interview_status" name="interview_status" style="width: 2% !important;" value="reject"  required /><h6 style="margin-top:10px;margin-left:10px;">Reject</h6></div>
                </div>
                <div class="form-group"> 
                    <button class="btn btn-success" name="submit" type="submit">Submit</button>
                </div>
            </form>
            </div>
            <div class="footer_t_form" style="display:none;width:100%;">
                <form style="width:100%;" action="" method="POST" enctype="multipart/form-data">
                    <input name="new_commer_id" id="new_commer_id" type="hidden">

                    <div class="form-group">
                        <label>Interview date</label>
                        <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker2 form-control @if($errors->has('given_date')) invalid-field @endif" name="interview_date" placeholder="Enter Given Date" value="">
                    </div>
                    <div class="form-group"> 
                        <button class="btn btn-success" name="submit" type="submit">Submit</button>
                    </div>
                </form>
                </div>
</div>
</div>
</div>
</div>

{{-- ///End modal --}}

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand fa fa-hotel"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                New Comers
            </h3>
            
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    &nbsp;
                    <input class="btn btn-success" type="button" onclick="export_data()" value="Export Newcomers Data">
                    &nbsp;
                    <div class="checkbox checkbox-danger btn btn-default btn-elevate btn-icon-sm">
                        <input id="check_id" class="checkbox checkbox-danger" type="checkbox">
                        <label for="check_id" >
                            Detailed View
                        </label>
                    </div>
                    <a href="{{ route('NewComer.form') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i>
                        New Record
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style=" padding: 8px; ">
            <div class="col-md-4">
    <button class="btn btn-primary cstmshow">Show Filters</button>
            </div></div>
    <div class="customFilters" style="display:none;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
            <label>Experience</label>
        <select class="form-control" data-num="4" multiple="multiple">
                {{-- <option selected disabled>Please select Experience</option> --}}
                <option value="fresh">Fresh</option>
                <option value="6 months">6 Months</option>
                <option value="1 Year">1 year</option>
                <option value="2 Year">2 years</option>
                <option value="More Than 2 Year">More than 2 years</option>
            </select>
        </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                    <label>Passport status</label>
                <select class="form-control" data-num="14" multiple="multiple">
                        {{-- <option selected disabled>Please select your option</option> --}}
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
        </div>
        <div class="col-md-4">
                <div class="form-group">
                        <label>Education</label>
                    <select id="education_level" class="form-control " name="education" placeholder="Education" data-num="9" multiple="multiple"> 
                    {{-- <option selected disabled>Select Education Level</option> --}}
                      <option>Matric / O-level</option>
                      <option>Intermediate / Deploma / A-level </option>
                      <option>Bachelor / Graduated</option>
                    </select>
                    </div>
            </div>
    </div>
    <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                    <label>Apply for</label>
                    <select id="applyfor" class="form-control " name="applyfor" placeholder="Apply for" data-num="21" multiple="multiple"> 
                    {{-- <option selected disabled>Please select your option</option> --}}
                    <option value="bike">Bike</option>
                    <option value="car">Car</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                    <label>Noc status</label>
                    <select id="nocstatus" class="form-control " name="nocstatus" placeholder="Noc Status" data-num="22" multiple="multiple"> 
                    {{-- <option selected disabled>Please select your option</option> --}}
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                    <label>Have bike</label>
                    <select id="havebike" class="form-control " name="havebike" placeholder="Have bike" data-num="23" multiple="multiple"> 
                    {{-- <option selected disabled>Please select your option</option> --}}
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label>Country</label>
                        <select id="country" class="form-control" name="nationality" placeholder="Nationality" data-num="3" multiple="multiple">
                                {{-- <option value="" selected disabled>Please select country</option> --}}
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
        </div>
</div>
    <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-hover table-checkable table-condensed" id="newComer-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Nationality</th>
                    <th>Experience</th>
                    <th>National id card number</th>
                    <th>Actions
                    <span class="dtr-data" style=" z-index: 999999999999999999;">
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="la la-ellipsis-h"></i>
                            </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" onclick="sort_by_status('all')"><i class="flaticon2-group"></i> All</a>
                                <a class="dropdown-item" onclick="sort_by_status('approve')"><i class="flaticon2-checkmark"></i> Approved</a>
                                <a class="dropdown-item" onclick="sort_by_status('reject')"><i class="flaticon2-cross"></i> Rejected</a>
                                <a class="dropdown-item" onclick="sort_by_status('pending')"><i class="flaticon2-pen"></i> Pending</a>
                                <a class="dropdown-item" onclick="sort_by_status('interview')"><i class="flaticon2-check-mark"></i> Interviewer's</a>
                                </div>    
                        </span>
                    </th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                    <th class="d-none"></th>

                    
                </tr>
            </thead>
        </table>

        <!--end: Datatable -->
    </div>
</div>
</div>

<!-- end:: Content -->
@endsection
@section('foot')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
<script>
var newcomer_table;
function export_data(){
    var export_details=[];
    var _data=newcomer_table.ajax.json().data;
    console.log(_data);
    _data.forEach(function(item,index) {
        export_details.push({
        "ID":item.id,
        "Name":item.full_name,
        "Nationality":item.nationality,
        "Number":item.phone_number,
        "Watsapp Number":item.whatsapp_number,
        "Education":item.education,
        "License":item.license_check,
        "License Number":item.license_number,
        "Residence":item.current_residence,
        "Status message":item.status_approval_message,
        "Interview Status":item.interview_status,
        "Interview by":item.interview_by,
        "Email":item.email,
        "Have bike":item.have_bike,


        });
    });
        var export_data = new CSVExport(export_details);
    return false;
}
var newcomer_data = [];
window._url = "{{url('admin/newApprovalComer/view/ajax/all')}}";
$(function() { 
var _settings =  {
    processing: true,
    search: {
        "regex": true
    },
    lengthMenu: [[-1], ["All"]],
    serverSide: false,
    'language': {
        'loadingRecords': '&nbsp;',
        'processing': $('.loading').show()
    },
    drawCallback:function(data){
        data.ajax = window._url;
        console.log(data)
        var api = this.api();
        var _data = api.data();
        var keys = Object.keys(_data).filter(function(x){return !isNaN(parseInt(x))});
        keys.forEach(function(_d,_i) {
            var __data = JSON.parse(JSON.stringify(_data[_d]).toLowerCase());
            newcomer_data.push(__data);
        });
    $('.total_entries').remove();
    $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
    mark_table();
    },
    ajax: window._url,
    columns:null, 
    responsive:true,
    order:[0,'desc'],
};
if(window.outerWidth>=686){
    //visa_expiry
    $('#newComer-table thead tr').prepend('<th></th>');
    _settings.columns=[
        //  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
        {
            "className":      'details-control',
            "orderable":      false,
            "data":           null,
            "defaultContent": ''
        },
        
        { "data": 'full_name', "name": 'full_name' },
        {"data": 'phone_number', "name": 'phone_number' },
        { "data": 'nationality', "name": 'nationality' },
        { "data": 'experiance', "name": 'experiance' },
        { "data": 'national_id_card_number', "name": 'national_id_card_number' },
        { "data": 'actions', "name": 'actions' },
        { "data": 'newcommer_image', "name": 'newcommer_image' },
        { "data": 'whatsapp_number', "name": 'whatsapp_number' },
        { "data": 'education', "name": 'education' },
        { "data": 'license_check', "name": 'license_check' },
        { "data": 'license_number', "name": 'license_number' },
        { "data": 'licence_issue_date', "name": 'licence_issue_date' },
        { "data": 'license_image', "name": 'license_image' },
        { "data": 'passport_status', "name": 'passport_status'},
        { "data": 'passport_number', "name": 'passport_number'},
        { "data": 'passport_image', "name": 'passport_image'},
        { "data": 'current_residence', "name": 'current_residence'},
        { "data": 'current_residence_countries', "name": 'current_residence_countries'},
        { "data": 'source', "name": 'source'},
        { "data": 'overall_remarks', "name": 'overall_remarks'},
        { "data": 'applying_for', "name": 'applying_for'},
        { "data": 'noc_status', "name": 'noc_status'},
        { "data": 'have_bike', "name": 'have_bike'},

    ],
    
    _settings.responsive=false;
    _settings.columnDefs=[
        {
            "targets": [ 7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23 ],
            "visible": false,
            searchable: true,
        },
    ];
}
else{
    _settings.columns=[
        { "data": 'full_name', "name": 'full_name' },
        {"data": 'phone_number', "name": 'phone_number' },
        { "data": 'nationality', "name": 'nationality' },
        { "data": 'experiance', "name": 'experiance' },
        { "data": 'national_id_card_number', "name": 'national_id_card_number' },
        { "data": 'actions', "name": 'actions' },
        { "data": 'newcommer_image', "name": 'newcommer_image' },
        { "data": 'whatsapp_number', "name": 'whatsapp_number' },
        { "data": 'education', "name": 'education' },
        { "data": 'license_check', "name": 'license_check' },
        { "data": 'license_number', "name": 'license_number' },
        { "data": 'licence_issue_date', "name": 'licence_issue_date' },
        { "data": 'license_image', "name": 'license_image' },
        { "data": 'passport_status', "name": 'passport_status'},
        { "data": 'passport_number', "name": 'passport_number'},
        { "data": 'passport_image', "name": 'passport_image'},
        { "data": 'current_residence', "name": 'current_residence'},
        { "data": 'current_residence_countries', "name": 'current_residence_countries'},
        { "data": 'source', "name": 'source'},
        { "data": 'overall_remarks', "name": 'overall_remarks'},
        { "data": 'applying_for', "name": 'applying_for'},
        { "data": 'noc_status', "name": 'noc_status'},
        { "data": 'have_bike', "name": 'have_bike'},

    ];
    
}
var mark_table = function(){}

newcomer_table = $('#newComer-table').DataTable(_settings);
    mark_table = function(){
    var _val = newcomer_table.search(); 
    if(_val===''){
        $("#newComer-table tbody").unmark();
        $("#newComer-table tbody > tr:visible").each(function() {
            var tr = $(this);
            var row = newcomer_table.row( tr );
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.remove();
                tr.removeClass('shown');
            }
        });
        return;
    }
    $('#newComer-table tbody > tr[role="row"]:visible').each(function() {
        var tr = $(this);
        var row = newcomer_table.row( tr );
        // console.warn("isShon: ",row.child.isShown());
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.remove();
            tr.removeClass('shown');
        }
            // This row is already open - close it
            var _arow = row.child( format(row.data()) );
            _arow.show();
            tr.addClass('shown');
    });
    $("#newComer-table tbody").unmark({
        done: function() {
            $("#newComer-table tbody").mark(_val, {
                "element": "span",
                "className": "highlighted"
            });
        }
    });
    
}
if(window.outerWidth>=686){
$('#newComer-table tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = newcomer_table.row( tr );

    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    }
    else {
        // Open this row
        row.child( format(row.data()) ).show();
        tr.addClass('shown');
    }
} );
}
function format ( d ) {
    if(d.current_residence =='other'){
    d.current_residence = d.current_residence_countries
    }
//    if(d.apply_for =='other'){
//     d.current_residence = d.current_residence_countries
//    }
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" id="new_comertable" border="0" style="padding-left:50px;">'+
        '<tr class="row'+d.id+'">'+
            '<td style="font-weight:900;">Passport status:</td>'+
            '<td colspan="2";>'+d.passport_status+'</td>'+
            '<td style="font-weight:900;">Passport Number:</td>'+
            '<td>'+d.passport_number+'</td>'+
            '<td style="font-weight:900;">Current residence:</td>'+
            '<td>'+d.current_residence+'</td>'+
            
        '</tr>'+
        '<tr class="row'+d.id+'">'+
            '<td style="font-weight:900;">License:</td>'+
            '<td colspan="2";>'+d.license_check+'</td>'+
            '<td style="font-weight:900;">License Number:</td>'+
            '<td>'+d.license_number+'</td>'+
            '<td style="font-weight:900;">License issue date:</td>'+
            '<td>'+d.licence_issue_date+'</td>'+
            
        '</tr>'+
        '<tr class="row'+d.id+'">'+
            '<td style="font-weight:900;">Apply for:</td>'+
            '<td colspan="2";>'+d.applying_for+'</td>'+
            '<td style="font-weight:900;">Education:</td>'+
            '<td>'+d.education+'</td>'+
            '<td style="font-weight:900;">Watsapp Number:</td>'+
            '<td>'+d.whatsapp_number+'</td>'+
            
        '</tr>'+
        '<tr class="row'+d.id+'" style="display:none;">'+
            '<td style="font-weight:900;">Comer image:</td>'+
            '<td colspan="2";>'+d.newcommer_image+'</td>'+
            '<td style="font-weight:900;">License image:</td>'+
            '<td>'+d.license_image+'</td>'+
            '<td style="font-weight:900;">Passport image:</td>'+
            '<td>'+d.passport_image+'</td>'+
        '</tr>'+
            
        '</table>';
}
if(window.outerWidth>=686){
    $("#check_id").change(function(){

        if($("#check_id").prop("checked") == true){
            $("td.details-control").each(function(){
                if (!$(this).parent().hasClass("shown")) {
                    $(this).trigger("click");
                }  
            });
        }
        if($("#check_id"). prop("checked") == false){
            $("td.details-control").each(function(){
                if ($(this).parent().hasClass("shown")) {
                    $(this).trigger("click");
                }
            });
        }
    });
}
else if(window.outerWidth<686){
    $("#check_id").change(function(){
        if($("#check_id").prop("checked") == true){
            $("td.sorting_1").each(function(){
                if (!$(this).parent().hasClass("parent")) {
                    $(this).trigger("click");
                }  
            });
        }
        if($("#check_id"). prop("checked") == false){
            $("td.sorting_1").each(function(){
                if ($(this).parent().hasClass("parent")) {
                    $(this).trigger("click");
                }  
            });
        }
    });
}
});

function show_waiting_comer(id,$this){
$('.footer_f_form').hide();
$('.footer_s_form').hide();
$('.footer_t_form').hide();
var _row = $($this).parents('.odd,.even');
$('[name="new_commer_id"]').val(id);
$('.custom_approval_comer_model').modal('show');
var _newcommerdata = newcomer_table.row(_row).data();
if(_newcommerdata.approval_status == 'reject' || _newcommerdata.approval_status == 'pending'){
    $('.footer_f_form').show();
}
if(_newcommerdata.interview_status == 'pending'){
    $('.footer_s_form').show();
}
if(_newcommerdata.approval_status == 'approve' && _newcommerdata.interview_status == null){
$('.footer_t_form').show();
}
console.log(_newcommerdata);
$('.approval_full_name').text(_newcommerdata.full_name);
$('.approval_phone_no').text(_newcommerdata.phone_number);
$('.approval_nationality').text(_newcommerdata.nationality);
$('.approval_experience').text(_newcommerdata.experiance);
$('.approval_id_card_no').text(_newcommerdata.national_id_card_number);
$('.approval_education').text(_newcommerdata.education);
$('.approval_license').text(_newcommerdata.license_check);
$('.new_commer_reviews').text(_newcommerdata.overall_remarks);
$('.approval_license_no').text(_newcommerdata.license_number);
$('.approval_license_date').text(_newcommerdata.licence_issue_date);
$('.approval_source').text(_newcommerdata.applying_for);
$('.approval_w_no').text(_newcommerdata.whatsapp_number);
$('.approval_passport_status').text(_newcommerdata.passport_status);
$('.approval_passport_no').text(_newcommerdata.passport_number);
$('.approval_interview_status').text(_newcommerdata.interview_status);
$('.approval_visa_status').text(_newcommerdata.visa_status);
$('.approval_noc_status').text(_newcommerdata.noc_status);
$('.newcomer_email').text(_newcommerdata.email);
$('.newcomer_hasbike').text(_newcommerdata.have_bike);
$('.newcomer_source').text(_newcommerdata.source);
$('.approval_app_status').text(_newcommerdata.approval_status);
if(_newcommerdata.current_residence == 'other'){
    _newcommerdata.current_residence = _newcommerdata.current_residence_countries
}
$('.approval_residence').text(_newcommerdata.current_residence);
if(_newcommerdata.newcommer_image.indexOf('uploads') <= -1){
    _newcommerdata.newcommer_image = "{{asset('dashboard/assets/media/no_image.png')}}";
}
$('.approval_custom_images_section img').attr('src',_newcommerdata.newcommer_image);
$('.approval_custom_images_section a').attr('href',_newcommerdata.newcommer_image);
$('.profile').attr('img_src',_newcommerdata.newcommer_image);
$('.license').attr('img_src',_newcommerdata.license_image);
$('.passport').attr('img_src',_newcommerdata.passport_image);
$('ul.list-group.list-group-horizontal li').removeClass('active');
$('ul.list-group.list-group-horizontal li.profile').addClass('active');
}
$('ul.list-group.list-group-horizontal li').click(function(){
$('ul.list-group.list-group-horizontal li').removeClass('active');
$(this).addClass('active');
var _newimgsrc = $(this).attr('img_src');
if(_newimgsrc.indexOf('uploads') <= -1){
    _newimgsrc ="{{asset('dashboard/assets/media/no_image.png')}}";
}
$('.approval_custom_images_section img').attr('src',_newimgsrc);
$('.approval_custom_images_section a').attr('href',_newimgsrc);

})

// first form

$('.custom_approval_comer_model .footer_f_form').find('form').off('submit').on('submit', function(e){
                    e.preventDefault();
                    $('.custom_approval_comer_model').modal('hide');
                    var _form = $(this);
                    var _url ="{{url('admin/newComer/approved')}}";
                    $.ajax({
                        url : _url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type : 'POST',
                        data: _form.serialize(),
                        success: function(data){
                            console.log(data);
                            
                            swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Record updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            newcomer_table.ajax.reload(null, false);
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
                });



                // second form

                $('.custom_approval_comer_model .footer_s_form').find('form').off('submit').on('submit', function(e){
                    e.preventDefault();
                    $('.custom_approval_comer_model').modal('hide');
                    var _form = $(this);
                    var _url ="{{url('admin/newComer/add_interview_status')}}";
                    $.ajax({
                        url : _url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type : 'POST',
                        data: _form.serialize(),
                        success: function(data){
                            console.log(data);
                            
                            swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Record updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            newcomer_table.ajax.reload(null, false);
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
                });



                // Third form

                $('.custom_approval_comer_model .footer_t_form').find('form').off('submit').on('submit', function(e){
                    e.preventDefault();
                    $('.custom_approval_comer_model').modal('hide');
                    var _form = $(this);
                    var _url ="{{url('admin/newComer/add_interview_date')}}";
                    $.ajax({
                        url : _url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type : 'POST',
                        data: _form.serialize(),
                        success: function(data){
                            console.log(data);
                            
                            swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Record updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            newcomer_table.ajax.reload(null, false);
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
                });



                // end of forms

        function sort_by_status(status){
            window._url = "{{url('admin/newApprovalComer/view/ajax')}}"+"/"+status;
            setTimeout(function(){
            newcomer_table.ajax.reload();
            },1000)
        }
        $('[name="approval_status"]').change(function(){
            $('[name="interview_date"]').val('');
            if($(this).val()=='approve'){
                $('[name="interview_date"]').show();
            }
            else{
                $('[name="interview_date"]').hide();
                $('.custm_reject-reason').show();
            }
        })
         var _h6 = $('input[type="radio"]').siblings('h6');
        $(_h6).css('cursor','pointer');
        $(_h6).click(function(){
            $(this).siblings('input[type="radio"]').prop( "checked", true );
            $(this).siblings('input[type="radio"]').change();
        })

// regex search
$('.customFilters select').on( 'change', function () {
    var _val  = $(this).val();
    _val = _val.toString().replace(/,/g, "|");
    var _num = $(this).attr('data-num');
    newcomer_table.column(_num).search(_val, true, false).draw();

    
});
$(document).ready(function() {
 $('.cstmshow').click(function(){
  $('.customFilters').toggle();
   $('.customFilters select').select2({
    });
})
});
</script>
<style>
td.details-control {
background: url('https://biketrack-dev.solutionwin.net/details_open.png') no-repeat center center;
cursor: pointer;
}
tr.shown td.details-control {
background: url('https://biketrack-dev.solutionwin.net/details_close.png') no-repeat center center;
}
</style>
@endsection