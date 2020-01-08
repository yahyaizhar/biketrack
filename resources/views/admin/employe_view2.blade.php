@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
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
<!--begin::Portlet-->
        <div class="kt-portlet">
        <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                View Employee
            </h3>
        </div>
        </div>
@include('admin.includes.message')
<form class="kt-form">
    {{ csrf_field() }}
        <div class="kt-portlet__body">
            <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12"> 
            <div class="form-group"> 
                <label>Name:</label>
                <input type="text" readonly class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter Your Name" required autofocus value="{{ $edit_employee->name }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="text" readonly class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter Your Email" required value="{{ $edit_employee->email }}">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group col-md-6 pull-right mtr-15">
                            <div class="custom-file">
                                <input disabled type="file" name="logo" class="custom-file-input" id="logo">
                                <label class="custom-file-label" for="logo">Choose Profile Picture</label>
                            </div>
                    </div>    
                @if($edit_employee->logo)
                        <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($edit_employee->logo)) }}" alt="image">
                    @else
                        <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                    @endif
                   
            </div>
        </div>
            <h5>User Rights</h5>
            <br>
            <br>
            <hr>
            {{-- ($dublicate_route->toArray()); --}}
        <div class="row">
            @php
                 $category_arr=[];
            @endphp
                @foreach($webroutes as $dublicate_r)
                @if(in_array(strtolower($dublicate_r->category), $category_arr))
                @else
                @php
                 array_push($category_arr,strtolower($dublicate_r->category)) 
                @endphp
                @endif
                @endforeach
                @foreach($category_arr as $cat_arr)
            <div class="col-md-12">
              <div class="form-group">
                    <label class="kt-checkbox main_check"> 
                            <input name="labelcheck" type="checkbox" disabled>
                            <h4 style="text-transform: capitalize;">{{$cat_arr}}</h4>
                            <span></span>
                     </label>
                    <br>
                    @php
                        $arr=[];
                    @endphp
      <ul class="nav">
        <li class="nav-item" style="width:25%;font-weight:700;">
            <label class="kt-checkbox" style="font-weight:700;"> <input disabled name="checktype" type="checkbox"> View <span></span> </label>
            <ul style=" padding: 0px; list-style-type: none; ">
                <li>
                        <div class="route_data">
                            @foreach($webroutes as $wroute)
                            @if(strtolower($wroute->category) == $cat_arr && $wroute->type =="view")
                            @if(in_array(strtolower($wroute->label), $arr))
                                <label class="kt-checkbox" style="display:none;"> 
                                <input disabled @foreach($users as $user) @if($user['action_name'] == $wroute->route_name) checked @endif @endforeach   id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}" routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                <span></span>
                            </label>
                            @else
                            @php
                            array_push($arr,strtolower($wroute->label));
                            @endphp
                            <label class="kt-checkbox" title="{{$wroute->route_description}}"> 
                                    <input disabled @foreach($users as $user) @if($user['action_name'] == $wroute->route_name) checked @endif @endforeach   id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}"  routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                    <span></span>
                            </label>
                            @endif
                            @endif
                            @endforeach
                        </div>
                </li>
            </ul>
        </li>
        <li class="nav-item" style="width:25%;font-weight:700;">
            <label class="kt-checkbox" style="font-weight:700;"> <input disabled name="checktype" type="checkbox"> Add <span></span> </label>
            <ul style=" padding: 0px; list-style-type: none; ">
                    <li>
                            <div class="route_data">
                                @foreach($webroutes as $wroute)
                                @if(strtolower($wroute->category) == $cat_arr && $wroute->type =="add")
                                @if(in_array(strtolower($wroute->label), $arr))
                                    <label class="kt-checkbox" style="display:none;"> 
                                    <input disabled @foreach($users as $user) @if($user['action_name'] == $wroute->route_name) checked @endif @endforeach   id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}" routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                    <span></span>
                                </label>
                                @else
                                @php
                                array_push($arr,strtolower($wroute->label));
                                @endphp
                                <label class="kt-checkbox" title="{{$wroute->route_description}}"> 
                                        <input disabled @foreach($users as $user) @if($user['action_name'] == $wroute->route_name) checked @endif @endforeach   id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}"  routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                        <span></span>
                                </label>
                                @endif
                                @endif
                                @endforeach
                            </div>
                    </li>
                </ul>
        </li>
        <li class="nav-item" style="width:25%;font-weight:700;">
            <label class="kt-checkbox" style="font-weight:700;"> <input disabled name="checktype" type="checkbox"> Edit/Update/Delete <span></span> </label>
            <ul style=" padding: 0px; list-style-type: none; ">
                    <li>
                            <div class="route_data">
                                @foreach($webroutes as $wroute)
                                @if(strtolower($wroute->category) == $cat_arr && $wroute->type =="eud")
                                @if(in_array(strtolower($wroute->label), $arr))
                                    <label class="kt-checkbox" style="display:none;"> 
                                    <input disabled @foreach($users as $user) @if($user['action_name'] == $wroute->route_name) checked @endif @endforeach   id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}" routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                    <span></span>
                                </label>
                                @else
                                @php
                                array_push($arr,strtolower($wroute->label));
                                @endphp
                                <label class="kt-checkbox" title="{{$wroute->route_description}}"> 
                                        <input disabled @foreach($users as $user) @if($user['action_name'] == $wroute->route_name) checked @endif @endforeach   id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}"  routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                        <span></span>
                                </label>
                                @endif
                                @endif
                                @endforeach
                            </div>
                    </li>
                </ul>
        </li>
        <li class="nav-item" style="width:25%;font-weight:700;">
            <label class="kt-checkbox" style="font-weight:700;"> <input disabled name="checktype" type="checkbox"> Others <span></span> </label>
            <ul style=" padding: 0px; list-style-type: none; ">
                    <li>
                            <div class="route_data">
                                @foreach($webroutes as $wroute)
                                @if(strtolower($wroute->category) == $cat_arr && $wroute->type =="others")
                                @if(in_array(strtolower($wroute->label), $arr))
                                    <label class="kt-checkbox" style="display:none;"> 
                                    <input disabled @foreach($users as $user) @if($user['action_name'] == $wroute->route_name) checked @endif @endforeach   id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}" routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                    <span></span>
                                </label>
                                @else
                                @php
                                array_push($arr,strtolower($wroute->label));
                                @endphp
                                <label class="kt-checkbox" title="{{$wroute->route_description}}"> 
                                        <input disabled @foreach($users as $user) @if($user['action_name'] == $wroute->route_name) checked @endif @endforeach   id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}"  routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                        <span></span>
                                </label>
                                @endif
                                @endif
                                @endforeach
                            </div>
                    </li>
                </ul>
        </li>
      </ul>
              </div>
            </div>
            @endforeach
         </div>
        </div>
        <div class="kt-portlet__foot">
                <div class="kt-form__actions kt-form__actions--right">
               <a class="btn btn-info" href="{{ route('Employee.edit_employee',$edit_employee->id) }}" class="kt-link kt-font-bold">Edit</a></span>
               <span class="kt-margin-l-10">or <a href="{{ url('/admin/show/employee') }}" class="kt-link kt-font-bold">Back</a></span>   
            </div>
            </div>
</form>
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
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 

  });
</script>
@endsection