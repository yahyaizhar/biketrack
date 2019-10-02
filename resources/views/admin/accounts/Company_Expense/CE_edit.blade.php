@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    @if ($readonly==true)
    <div class="row">
            <div class="col-md-12">
            <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                               View Company Expence
                            </h3>
                        </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('admin.includes.message')
                        <div class="kt-portlet__body">
                                
                            <div class="form-group">
                                <label>Month:</label>
                                <input readonly type="text" data-month="{{Carbon\Carbon::parse($edit_expense->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                                @if ($errors->has('month'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('month') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter Month</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Description:</label> 
                            <textarea disabled type="text" class="form-control @if($errors->has('description')) invalid-field @endif" rows="5" cols="12" name="description" placeholder="Enter Description">{{$edit_expense->description}}</textarea>
                                @if ($errors->has('description'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('description')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
    
                            {{-- <div class="form-group">
                                <label>Select Rider:</label>
                                <select disabled class="form-control kt-select2" id="kt_select2_3" name="rider_id" >
                                    <option value="">No rider<option>
                                    @foreach ($riders as $rider)
                                        <option @if($edit_expense->rider_id==$rider->id) selected @endif value="{{ $rider->id }}">
                                            {{ $rider->name }}
                                        </option>     
                                    @endforeach 
                                </select> 
                            </div> --}}
                            <div class="form-group">
                                <label>Amount:</label>
                                <input disabled type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{$edit_expense->amount}}">
                                @if ($errors->has('amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                @if($edit_expense->bill_picture)
                                    <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($edit_expense->bill_picture)) }}" alt="image">
                                @else
                                    <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                               
                            </div>
                         
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                            <a href="{{url('admin/accounts/CE/edit',$edit_expense->id)}}"><button class="btn btn-primary">Edit</button></a>
                            </div>
                        </div>
                </div>
        </div>
    @else
    <div class="row">
            <div class="col-md-12">
            <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                               Edit Company Expence
                            </h3>
                        </div>
                        <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Available Balance: <span id="available_balance" class="text-danger">{{$available_balance}}</span>   
                                </h3>
                            </div>
                    </div>
    
                    <!--begin::Form-->
                    
                    @include('admin.includes.message')
                    <form class="kt-form" id="CE" action="{{ route('admin.CE_update',$edit_expense->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::parse($edit_expense->month)->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                                @if ($errors->has('month'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('month') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter Month</span>
                                @endif
                            </div>
                                
                            <div class="form-group">
                                <label>Description:</label> 
                            <textarea required type="text" class="form-control @if($errors->has('description')) invalid-field @endif" rows="5" cols="12" name="description" placeholder="Enter Description">{{$edit_expense->description}}</textarea>
                                @if ($errors->has('description'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('description')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
    
                            {{-- <div class="form-group">
                                <label>Select Rider:</label>
                                <select class="form-control kt-select2" id="kt_select2_3" name="rider_id" >
                                    <option value="">No rider<option>
                                    @foreach ($riders as $rider)
                                        <option @if($edit_expense->rider_id==$rider->id) selected @endif value="{{ $rider->id }}">
                                            {{ $rider->name }}
                                        </option>     
                                    @endforeach 
                                </select> 
                            </div> --}}
                            <div class="form-group">
                                <label>Amount:</label>
                                <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{$edit_expense->amount}}">
                                @if ($errors->has('amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group kt-checkbox-list" id="check_hide">
                                <label class="kt-checkbox" id="investment_amount" >
                                        <input type="checkbox" name="investment_amount">
                                        <input type="hidden" name="checkbox_amount">
                                        <span></span>
                                </label>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group col-md-8 pull-right mtr-15">
                                    <div class="custom-file">
                                        <input type="file" name="bill_picture" class="custom-file-input" id="bill_picture">
                                        <label class="custom-file-label" for="bill_picture">Choose Picture</label>
                                    </div>
                                    <span class="form-text text-muted">Select Picture</span>
                                </div>
                                @if($edit_expense->bill_picture)
                                    <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($edit_expense->bill_picture)) }}" alt="image">
                                @else
                                    <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                
                            </div>
                        </div>
                    </form>
    
                    <!--end::Form-->
                </div>
    
            <!--end::Portlet-->
        </div> 
    @endif
   
</div>

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
        $(function(){
            $('.kt-select2').select2({
                placeholder: "Select an rider",
                width:'100%'    
            });
        });
        $(document).ready(function(){
            $("#check_hide").hide();
            $('#CE [name="month"]').on('change', function(){
                var _month = $('#CE [name="month"]').val();
                if(_month=='')return;
                _month = new Date(_month).format('yyyy-mm-dd');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    url:"{{url('admin/accounts/company/expense/investment/detail')}}"+'/'+_month,
                    method: "GET"
                })
                .done(function(data) {
                    $('#available_balance').text(data.available_balance);
                    $('#CE [name="amount"]').on("change input",function(){
                        if ($('#CE [name="amount"]').val()<=0) {
                            $(this).val(0);
                            $('#available_balance').text(data.available_balance);
                        }
                        var amount=parseFloat($(this).val().trim());
                        var avilable_balance=data.available_balance;
                        var _res=amount-avilable_balance;
                        var _res_available_balance=avilable_balance-amount;
                         $('#available_balance').text(_res_available_balance);
                         if (_res>_res_available_balance) {
                            $("#check_hide").show(); 
                            $('#investment_amount')[0].childNodes[2].textContent = 'Add '+_res+' AED amount as Company Investment By Admin'; 
                            $('#CE [name="checkbox_amount"]').val(_res);
                         }else{
                            $("#check_hide").hide();
                         }
                    });
                });
            });
        });
        
        </script>
@endsection