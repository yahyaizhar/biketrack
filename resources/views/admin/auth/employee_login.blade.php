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
                Add Employee
            </h3>
        </div>
        </div>
@include('admin.includes.message')
<form class="kt-form" action="{{ route('Employee.insert_employee') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
        <div class="kt-portlet__body">
            <div class="form-group"> 
                <label>Name:</label>
                <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter Your Name" required autofocus value="{{ old('name') }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group"> 
                <label>Email:</label>
                <input type="text" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter Your Email" required value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group"> 
                <label>Password:</label>
                <input type="password" class="form-control @if($errors->has('password')) invalid-field @endif" name="password" placeholder="Enter Your Password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group"> 
                <label>Password:</label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm your password" required>
            </div>
            <div class="form-group">
                <label>Seniour Employee:</label>
                <select class="form-control bk-select2 kt-select2-general" name="s_emp_id" >
                    <option value="">Select Seniour Employee</option>
                    @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->name }}
                    </option>     
                    @endforeach 
                </select>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label for="address">Logo:</label>
                        <div class="custom-file">
                            <input type="file" name="logo" class="custom-file-input" id="logo" value="{{ old('logo') }}">
                            <label class="custom-file-label" for="logo">Choose logo</label>
                        </div>
                    </div>
                </div>
            </div>
            <h5 style=" margin-bottom: 25px; ">User Rights</h5>
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
                                <input name="labelcheck" type="checkbox">
                                <h4 style="text-transform: capitalize;">{{$cat_arr}}</h4>
                                <span></span>
                         </label>
                        <br>
                        @php
                            $arr=[];
                        @endphp
          <ul class="nav">
            <li class="nav-item" style="width:25%;font-weight:700;">
                <label class="kt-checkbox" style="font-weight:700;"> <input name="checktype" type="checkbox"> View <span></span> </label>
                <ul style=" padding: 0px; list-style-type: none; ">
                    <li>
                            <div class="route_data">
                                @foreach($webroutes as $wroute)
                                @if(strtolower($wroute->category) == $cat_arr && $wroute->type =="view")
                                @if(in_array(strtolower($wroute->label), $arr))
                                    <label class="kt-checkbox" style="display:none;"> 
                                    <input     id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}" routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                    <span></span>
                                </label>
                                @else
                                @php
                                array_push($arr,strtolower($wroute->label));
                                @endphp
                                <label class="kt-checkbox" title="{{$wroute->route_description}}"> 
                                        <input     id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}"  routelabel="{{$wroute->label}}"> {{$wroute->label}} 
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
                <label class="kt-checkbox" style="font-weight:700;"> <input name="checktype" type="checkbox"> Add <span></span> </label>
                <ul style=" padding: 0px; list-style-type: none; ">
                        <li>
                                <div class="route_data">
                                    @foreach($webroutes as $wroute)
                                    @if(strtolower($wroute->category) == $cat_arr && $wroute->type =="add")
                                    @if(in_array(strtolower($wroute->label), $arr))
                                        <label class="kt-checkbox" style="display:none;"> 
                                        <input     id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}" routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                        <span></span>
                                    </label>
                                    @else
                                    @php
                                    array_push($arr,strtolower($wroute->label));
                                    @endphp
                                    <label class="kt-checkbox" title="{{$wroute->route_description}}"> 
                                            <input     id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}"  routelabel="{{$wroute->label}}"> {{$wroute->label}} 
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
                <label class="kt-checkbox" style="font-weight:700;"> <input name="checktype" type="checkbox"> Edit/Update/Delete <span></span> </label>
                <ul style=" padding: 0px; list-style-type: none; ">
                        <li>
                                <div class="route_data">
                                    @foreach($webroutes as $wroute)
                                    @if(strtolower($wroute->category) == $cat_arr && $wroute->type =="eud")
                                    @if(in_array(strtolower($wroute->label), $arr))
                                        <label class="kt-checkbox" style="display:none;"> 
                                        <input id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}" routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                        <span></span>
                                    </label>
                                    @else
                                    @php
                                    array_push($arr,strtolower($wroute->label));
                                    @endphp
                                    <label class="kt-checkbox" title="{{$wroute->route_description}}"> 
                                            <input  id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}"  routelabel="{{$wroute->label}}"> {{$wroute->label}} 
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
                <label class="kt-checkbox" style="font-weight:700;"> <input name="checktype" type="checkbox"> Others <span></span> </label>
                <ul style=" padding: 0px; list-style-type: none; ">
                        <li>
                                <div class="route_data">
                                    @foreach($webroutes as $wroute)
                                    @if(strtolower($wroute->category) == $cat_arr && $wroute->type =="others")
                                    @if(in_array(strtolower($wroute->label), $arr))
                                        <label class="kt-checkbox" style="display:none;"> 
                                        <input  id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}" routelabel="{{$wroute->label}}"> {{$wroute->label}} 
                                        <span></span>
                                    </label>
                                    @else
                                    @php
                                    array_push($arr,strtolower($wroute->label));
                                    @endphp
                                    <label class="kt-checkbox" title="{{$wroute->route_description}}"> 
                                            <input     id="action_name" name="action_name[]" type="checkbox" value="{{$wroute->route_name}}"  routelabel="{{$wroute->label}}"> {{$wroute->label}} 
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
                <button type="submit" class="btn btn-primary">Submit</button>
                <span class="kt-margin-l-10">or <a href="{{ url('/admin') }}" class="kt-link kt-font-bold">Cancel</a></span>
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

            ///trigger same label input
            $('[name="action_name[]"]').change(function(){
                var route_label  = $(this).attr('routelabel')
                var _chk = $(this).prop('checked');
                $(this).parents('.route_data').find('input').each(function(){
                var route_label2  = $(this).attr('routelabel');
                if(route_label.toLowerCase() == route_label2.toLowerCase()){
                    if(_chk){
                    $(this).prop('checked',true);
                }else{
                    $(this).prop('checked',false);
                }
                }
                })
            })
            ///end trigger same label input

                       //on type change check/uncheck its routes

                       $('[name="checktype"]').change(function(){
            var _prop  = $(this).prop('checked');
            if(_prop == false){
            $(this).parents('.kt-checkbox').siblings('ul').find('li input').prop('checked',false);
          }else{
            $(this).parents('.kt-checkbox').siblings('ul').find('li input').prop('checked',true);
            }
            })
            
            //end on type change check/uncheck its routes

                        /////on parent input check all
                        $('[name="labelcheck"]').change(function(){
            var _prop  = $(this).prop('checked');
            if(_prop == false){
            $(this).parents('.main_check').siblings('ul').find('.nav-item input').prop('checked',false);
          }else{
            $(this).parents('.main_check').siblings('ul').find('.nav-item input').prop('checked',true);
            }
            })
            //// end on parent input check all


  });
</script>
@endsection