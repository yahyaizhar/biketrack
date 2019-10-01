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
                            Company Expence
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
                <form class="kt-form" action="{{ route('admin.CE_store') }}" method="POST" id="CE" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Month:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                            <textarea required type="text" class="form-control @if($errors->has('description')) invalid-field @endif" rows="5" cols="12" name="description" placeholder="Enter Description" value=""></textarea>
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
                                    <option value="{{ $rider->id }}">
                                        {{ $rider->name }}
                                    </option>     
                                @endforeach 
                           </select> 
                       </div> --}}
                      
                        
                        <div class="form-group">
                            <label>Amount:</label>
                            <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="0">
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
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            
                        </div>
                    </div>
                </div>
                <input type="hidden" name="result">
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
    $('#CE [name="month"]').trigger("change");
});

</script>
@endsection