@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Add New Salary
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                    
                
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('account.added_salary')}}" method="post" id="salary" enctype="multipart/form-data">
                    {{ csrf_field() }}
                     
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Select Rider:</label>
                            <select class="form-control kt-select2" id="kt_select2_3" name="rider_id" >
                            @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>     
                            @endforeach 
                            </select> 
                        </div>
                        {{-- <div class="form-group">
                            <label>Month:</label>
                            <input type="text" id="datepicker" readonly class="form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                            @if ($errors->has('month'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('month') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter Month</span>
                            @endif
                        </div> --}}
                        {{-- <div class="form-group">
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
                        </div> --}}
                        <div class="form-group">
                            <label>Salary Generated Month:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                            <label>Given Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Given Date" value="">
                            @if ($errors->has('given_date'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('given_date') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter Given Date</span>
                            @endif
                        </div>
                        <div class="not_visible">
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-6" placeholder="Enter Monthly Hours" value="Monthly Hours" >
                            <input type="text" required readonly class="form-control col-md-6" name="monthly_hours_val" placeholder="Enter Monthly Hours" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-3"  placeholder="Enter Absent Day" value="Absent Days" >
                            <input type="text" required readonly class="form-control col-md-3" name="absent_day_val" placeholder="Enter Absent Day" value="" >
                            <input type="text" required readonly class="form-control col-md-3"  placeholder="Enter Absent Hours" value="Absent Hours" >
                            <input type="text" required readonly class="form-control col-md-3" name="absent_hours_val" placeholder="Enter Absent Hours" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-3"  placeholder="Enter Workable Days" value="Workable Days" >
                            <input type="text" required readonly class="form-control col-md-3" name="workable_days" placeholder="Enter Workable Days" value="" >
                            <input type="text" required readonly class="form-control col-md-3"  placeholder="Enter Workable Days Based On Available Days" value="Workable Days Based On Available Days" >
                            <input type="text" required readonly class="form-control col-md-3" name="workable_hours_based_on_available_days" placeholder="Enter Workable Days Based On Available Days" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-6" value="Working Zomato hours during available days" >
                            <input type="text" required readonly class="form-control col-md-6" name="working_zomato_hours" placeholder="Enter Working hours during available days" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-6" value="Working hours during available days" >
                            <input type="text" required readonly class="form-control col-md-6" name="working_hours_during_available_days" placeholder="Enter Working hours during available days" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-6" value="Less time calculated" >
                            <input type="text" required readonly class="form-control col-md-6" name="Less time calculated" placeholder="Enter Less time calculated" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-3"  value="Payable Time" >
                            <input type="text" required readonly class="form-control col-md-3" name="finals_hours" value="" >
                            <input type="text" required readonly class="form-control col-md-3" name="hours_multiply_with"  value="" >
                            <input type="text" required readonly class="form-control col-md-3" name="hours_amount" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-3"  value="Trips" >
                            <input type="text" required readonly class="form-control col-md-3" name="trips" value="" >
                            <input type="text" required readonly class="form-control col-md-3" name="trips_multiply_with"  value="" >
                            <input type="text" required readonly class="form-control col-md-3" name="trips_amount" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-3"  value="Extra Trips" >
                            <input type="text" required readonly class="form-control col-md-3" name="extra_trips" value="" >
                            <input type="text" required readonly class="form-control col-md-3" name="extra_trips_multiply_with"  value="" >
                            <input type="text" required readonly class="form-control col-md-3" name="extra_trips_amount" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-6" value="Final Salary" >
                            <input type="text" required readonly class="form-control col-md-6" name="final_salary" value="" >
                        </div>
                        <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                            <input type="text" required readonly class="form-control col-md-6" value="400 Trips Aceivement Bonus" >
                            <input type="text" required readonly class="form-control col-md-6" name="bonus" value="" >
                        </div>
                        </div>
                        <div class="form-group">
                            <input readonly type="hidden" class="form-control @if($errors->has('net_salary')) invalid-field @endif" name="net_salary" value="">
                         </div>
                        <div class="form-group">
                        <input readonly type="hidden" class="form-control @if($errors->has('gross_salary')) invalid-field @endif" name="gross_salary" value="">
                        </div>
                        {{-- <div class="form-group">
                            <label>Salary Paid to Rider:</label>
                            <input type="text" class="form-control @if($errors->has('recieved_salary')) invalid-field @endif" name="recieved_salary" value="">
                            @if ($errors->has('recieved_salary'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('recieved_salary') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Recieved salary</span>
                            @endif
                                
                        </div> --}}
                        {{-- <div class="form-group">
                            <label>Salary Remaining:</label>
                            <input type="text" class="form-control @if($errors->has('remaining_salary')) invalid-field @endif" name="remaining_salary" value="">
                            @if ($errors->has('remaining_salary'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('remaining_salary') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Remaining salary</span>
                            @endif
                                
                        </div> --}}
                        {{-- <div class="form-group">
                                <label>Payment status:</label>
                                <div class="kt-radio-inline">
                                    <label class="kt-radio">
                                        <input type="radio" name="payment_status" value="paid"> Paid
                                        <span></span>
                                    </label>
                                    <label class="kt-radio">
                                            <input type="radio" name="payment_status" value="pending"> Pending
                                        <span></span>
                                    </label>
                                </div>
                        </div> --}}
                       
                        {{-- <div>
                            <input type="hidden" name="setting"> --}}
                            <input type="hidden"  name="total_salary" >
                            {{-- <input type="hidden"  name="total_bonus" >
                            <input type="hidden"  name="total_deduction" >
                            <input type="hidden"  name="payment_status" value="pending" >
                        </div> --}}
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="upload-button btn btn-primary">Submit</button>
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
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script data-ajax>
    $(function(){
        $('.kt-select2').select2({
            placeholder: "Select an rider",
            width:'100%'    
        });
        $('#datepicker').fdatepicker({ 
            format: 'MM yyyy', 
            startView:3,
            minView:3,
            maxView:4
        });
        $(".not_visible").hide();
        $('#salary [name="recieved_salary"]').on('change input', function(){
            var _gross_salary = parseFloat($('#salary [name="gross_salary"]').val().trim());
            var _recieved_salary = parseFloat($(this).val().trim());
            $('#salary [name="remaining_salary"]').val(_recieved_salary-_gross_salary);
        });
        $('#salary [name="rider_id"],#salary [name="month"]').on('change', function(){
            var _riderid = $('#salary [name="rider_id"]').val();
            var _month = $('#salary [name="month"]').val();
            $(".not_visible").show();
            if(_riderid==''||_month=='')return;
            _month = new Date(_month).format('yyyy-mm-dd');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/accounts/company/debits/get_salary_deduction/')}}"+'/'+_month+'/'+_riderid,
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                var extra_trips=0;
                var bonus=0;
                var absent_days=data.absent_count;
                var absent_hours=absent_days*11;
                var working_days=data.working_days;
                var working_days_hours=working_days*11;
                var monthly_hours=286;
                var working_zomato_hours=data.zomato_hours;
                var hours=data.hours;
                var less_time_calculated=working_days_hours-hours;
                var final_hours_payout=monthly_hours-(absent_hours+less_time_calculated);
                var hours_amount=final_hours_payout*7.87;
                var trips=data.trips;
                if (trips>400) {
                    trips=400;
                    extra_trips=(data.trips)-400;
                    bonus=50;
                }
                var trips_amount=trips*2;
                var extra_trips_amount=extra_trips*4;
                var final_salary=trips_amount+hours_amount+extra_trips_amount;
                console.log("gdsjhdsghaj"+hours_amount);
                $('#salary [name="monthly_hours_val"]').val(data._s_monthlyHours);
                $('#salary [name="absent_day_val"]').val(data.absent_count);
                $('#salary [name="absent_hours_val"]').val((data.absent_count*data._s_maxHours).toFixed(2));
                $('#salary [name="workable_days"]').val(data.working_days);
                $('#salary [name="workable_hours_based_on_available_days"]').val((data.working_hours).toFixed(2));
                $('#salary [name="working_hours_during_available_days"]').val((data.hours).toFixed(2));
                $('#salary [name="working_zomato_hours"]').val((data.zomato_hours).toFixed(2));
                $('#salary [name="Less time calculated"]').val((data.less_time).toFixed(2));
                $('#salary [name="finals_hours"]').val((data.payable_hours).toFixed(2));
                $('#salary [name="hours_multiply_with"]').val(data._s_hoursFormula);
                $('#salary [name="hours_amount"]').val((data.payable_hours*data._s_hoursFormula).toFixed(2));
                $('#salary [name="trips"]').val(data.trips);
                $('#salary [name="trips_multiply_with"]').val(data._s_tripsFormula);
                $('#salary [name="trips_amount"]').val(data.trips_payable);
                $('#salary [name="extra_trips"]').val(data.trips_EXTRA);
                $('#salary [name="extra_trips_multiply_with"]').val(data._s_maxTripsFormula);
                $('#salary [name="extra_trips_amount"]').val(data.trips_EXTRA_payable);
                
                $('#salary [name="bonus"]').val(data.bonus);
                $('#salary [name="final_salary"]').val((data.total_salary).toFixed(2));
                $('#salary [name="total_salary"]').val((data.total_salary).toFixed(2));
                

                $('#salary [name="is_paid"]').val(data.is_paid);
                $('#salary [name="gross_salary"], #salary [name="recieved_salary"]').val(data.gross_salary).trigger('change');
                $('#salary [name="net_salary"]').val(data.net_salary).trigger('change');
                $('#salary [name="total_deduction"]').val(data.total_deduction);
                // $('#salary [name="total_salary"]').val(data.total_salary);
                // $('#salary [name="total_bonus"]').val(data.total_bonus); 
                var is_paid=data.is_paid; 
                if (is_paid) {
                    $('.upload-button').html("The Rider has already paid").prop("disabled",true);
                }else{
                    $('.upload-button').html("Submit").prop("disabled",false);
                }

                var is_generated=data.is_generated; 
                if (is_generated) {
                    $('.upload-button').html("Update Salary").prop("disabled",false);
                }else{
                    $('.upload-button').html("Generate Salary");
                }


            });
        });

        var gb_rider_id = $('#gb_rider_id').val();
        if(typeof gb_rider_id !== "undefined"){
            $('#salary [name="rider_id"]').val(gb_rider_id).trigger('change');
        }
       
    });
            
</script>

@endsection