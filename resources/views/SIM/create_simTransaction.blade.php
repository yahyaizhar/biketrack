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
                            Create Sim Transaction
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form id="sims" class="kt-form" action="{{ route('SimTransaction.store_simTransaction') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                            {{-- <div class="form-group">
                                <label>Date:</label>
                                <input type="text" data-month="{{carbon\carbon::now()->format('M, Y')}}" class=" month_picker form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month_Year" value="{{ old('month_year') }}">
                                @if ($errors->has('month_year'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('month_year')}}
                                        </strong>
                                    </span> 
                                @endif
                            </div> --}}
                            <div class="form-group">
                                <label>Sim Bill Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month" value="">
                                @if ($errors->has('month_year'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('month_year') }} 
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter Month</span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Select Rider:</label>
                                    <select class="form-control bk-select2 kt-select2" id="kt_select2_3" name="rider_id" >
                                    @foreach ($riders as $rider)
                                        <option value="{{ $rider->id }}">
                                            {{ $rider->name }}
                                        </option>     
                                    @endforeach 
                                    </select> 
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sim:</label>
                                    <select required class="form-control kt-select2 bk-select2" name="sim_id" >
                                        @foreach ($sims as $sim)
                                        <option value="{{ $sim->id }}">
                                            {{ $sim->sim_number }}
                                        </option>     
                                        @endforeach 
                                    </select>
                                </div>
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
                            {{-- <div class="form-group ">
                                <label>Allowed Balance:</label>
                                <input required readonly type="text" class="form-control @if($errors->has('usage_limit')) invalid-field @endif" name="usage_limit" >
                                @if ($errors->has('usage_limit'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('usage_limit')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>        --}}
                            <div class="form-group">
                                <label>Amount To be Given:</label>
                                <input required type="text" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount">
                            </div>
                            {{-- <div class="form-group">
                                <label>Extra Usage Amount:</label>
                                <input required readonly type="text" class="form-control @if($errors->has('extra_usage_amount')) invalid-field @endif" name="extra_usage_amount" placeholder="Enter extra usage amount " value="{{ old('extra_usage_amount') }}">
                                @if ($errors->has('extra_usage_amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('extra_usage_amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div> --}}
                            {{-- <input type="hidden" name="total_month_days"> --}}
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span> --}}
                        </div>
                    </div>
                </form></div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 <script data-ajax>

    $(document).ready(function(){
        $('#sims [name="sim_id"]').on('change', function(){
            var _month = new Date($('#sims [name="month_year"]').val()).format('yyyy-mm-dd');
            console.log(_month)
            //select current rider
            var sim_id = $('#sims [name="sim_id"]').val();
            if(typeof sim_id !== "undefined"){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    url:"{{url('admin/sim/ajax/get_active_sims/')}}"+"/"+sim_id+"/"+_month+"/sim",
                    method: "GET"
                })
                .done(function(data) {  
                    console.log(data); 
                    if(data.sim_histories!==null){
                        split_objects(data.sim_histories, _month, 'rider');
                    }
                });
            }
        });
        
        $('#sims [name="month_year"],#sims [name="rider_id"]').on('change', function(){
            var _month = new Date($('#sims [name="month_year"]').val()).format('yyyy-mm-dd');
            //select current rider
            var rider_id = $('#sims [name="rider_id"]').val();
            if(typeof rider_id !== "undefined"){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    url:"{{url('admin/sim/ajax/get_active_sims/')}}"+"/"+rider_id+"/"+_month+"/rider",
                    method: "GET"
                })
                .done(function(data) {  
                    console.log(data); 
                    if(data.sim_histories!==null){
                        split_objects(data.sim_histories, _month, 'sim');
                    }
                });
            }
        });
        $('#sims [name="rider_id"]').trigger('change');
    
        
        $('#sims [name="bill_amount"]').on('change input', function(){
            var _usage_limit = $('#sims [name="usage_limit"]').val();
            var _billAmt = $(this).val();
            var _extra =_billAmt - _usage_limit;
            console.log(_extra);
            if(_extra < 0) _extra = 0;
            $('#sims [name="extra_usage_amount"]').val(_extra);
        });

    }); 
    $("#sims [name='amount']").on("change input",function(){
        var amount=$(this).val();
        $('#sims .split--calculated__div').each(function(i, item){
            var total_days = parseFloat($(this).find('[name="data['+i+'][total_days]"]').val())||0;
            var work_days = parseFloat($(this).find('[name="data['+i+'][work_days_count]"]').val())||0;
            var work_days_allowed_balance = parseFloat($(this).find('[name="data['+i+'][work_days_allowed_balance]"]').val())||0;
            var days=work_days/total_days;
            var amount_to_give=amount*days;
            $(this).find('[name="data['+i+'][bill_amount_given_by_days]"]').val(amount_to_give.toFixed(2));
            if (amount_to_give>work_days_allowed_balance) {
                var extra_amt=amount_to_give-work_days_allowed_balance;
                $(this).find('[name="data['+i+'][extra_useage_amount_given_by_days]"]').val(extra_amt.toFixed(2));
            }
            else{
                $(this).find('[name="data['+i+'][extra_useage_amount_given_by_days]"]').val(0);
            }

        });
    });
    var split_objects=function(histories, _month, according_to){
        $('#sims .split__object-container').remove();
        if(Object.keys(histories).length>0){
            $('#sims [name="sim_id"]').parents('.form-group').after('<div class="split__object-container"></div>');
            var previous_unassigned_date=null;
            Object.keys(histories).forEach(function(x ,i){
                console.log( histories[x]);
                var obj = histories[x];
                var days_in_month=moment(_month, "YYYY-MM-DD").daysInMonth();
                var start  = moment(_month, "YYYY-MM-DD").startOf('month');
                var end    = moment(_month, "YYYY-MM-DD").endOf('month');
                var assign_date = moment(obj.given_date, "YYYY-MM-DD").min(start).max(end);
                var unassign_date = moment(obj.return_date, "YYYY-MM-DD").min(start).max(end);
    // debugger;
                if(previous_unassigned_date!=null){
                    var _differPrevious = previous_unassigned_date.diff(assign_date, 'days');
                    if(_differPrevious==0){
                        //dates are same
                        assign_date = assign_date.add(1, 'days');
                    }
                }
                previous_unassigned_date=unassign_date;
                if(obj.status=="active"){ // unassign_date will be last of the month
                    unassign_date = end;
                }
                
                var work_days = unassign_date.diff(assign_date, 'days')+1;
                console.log('assign_date', assign_date.format("YYYY-MM-DD"), 'unassign_date', unassign_date.format("YYYY-MM-DD"));
                
                console.warn(work_days);
                var work_days_allowed_balance=(work_days/days_in_month)*obj.allowed_balance;
                var append_sim='';
                if(according_to=="sim"){
                    append_sim='<div class="split--calculated__div" >'+
    '                                <div class="form-group">'+
    '                                    <input type="hidden" name="data['+i+'][sim_id]" value="'+obj.sim.id+'"> <input type="hidden" name="data['+i+'][type]" value="'+according_to+'">'+
    '                                     <span class="form-text text-muted">Sim Number:</span>'+
    '                                    <input readonly type="text" class="form-control" value="'+obj.sim.sim_number+'-'+obj.sim.sim_company+'">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Allowed Balance</span>'+
    '                                    <input readonly type="text" class="form-control" name="data['+i+'][allowed_balance]" value="'+obj.allowed_balance+'">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Working Days</span>'+
    '                                    <input type="text" class="form-control" value="'+(work_days)+'" name="data['+i+'][work_days_count]">'+
    '                                     <span class="form-text text-muted">'+assign_date.format("DD/MM/YYYY")+' - '+unassign_date.format("DD/MM/YYYY")+'</span>'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Allowed Balance in Working Days</span>'+
    '                                    <input type="text" class="form-control" name="data['+i+'][work_days_allowed_balance]" value="'+work_days_allowed_balance+'">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Total Month Days</span>'+
    '                                    <input readonly type="text" class="form-control" value="'+days_in_month+'" name="data['+i+'][total_days]">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Bill Amount</span>'+
    '                                    <input type="text" class="form-control" value="" name="data['+i+'][bill_amount_given_by_days]">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Extra Useage Amount</span>'+
    '                                    <input readonly type="text" class="form-control" value="" name="data['+i+'][extra_useage_amount_given_by_days]">'+
    '                                </div>'+
    '                            </div>'; 
                }
                else{
                    append_sim='<div class="split--calculated__div" >'+
    '                                <div class="form-group">'+
    '                                   <input type="hidden" name="data['+i+'][rider_id]" value="'+obj.rider.id+'"> <input type="hidden" name="data['+i+'][type]" value="'+according_to+'">'+
    '                                     <span class="form-text text-muted">Rider Name:</span>'+
    '                                    <input readonly type="text" class="form-control" value="'+obj.rider.name+'" >'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Allowed Balance</span>'+
    '                                    <input readonly type="text" class="form-control" name="data['+i+'][allowed_balance]" value="'+obj.allowed_balance+'">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Working Days</span>'+
    '                                    <input type="text" class="form-control" value="'+(work_days)+'" name="data['+i+'][work_days_count]">'+
    '                                     <span class="form-text text-muted">'+assign_date.format("DD/MM/YYYY")+' - '+unassign_date.format("DD/MM/YYYY")+'</span>'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Allowed Balance in Working Days</span>'+
    '                                    <input  type="text" class="form-control" name="data['+i+'][work_days_allowed_balance]" value="'+work_days_allowed_balance+'">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Total Month Days</span>'+
    '                                    <input readonly type="text" class="form-control" value="'+days_in_month+'" name="data['+i+'][total_days]">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Bill Amount</span>'+
    '                                    <input type="text" class="form-control" value="" name="data['+i+'][bill_amount_given_by_days]">'+
    '                                </div>'+
    '                                <div class="form-group">'+
    '                                     <span class="form-text text-muted">Extra Useage Amount</span>'+
    '                                    <input readonly type="text" class="form-control" value="" name="data['+i+'][extra_useage_amount_given_by_days]">'+
    '                                </div>'+
    '                            </div>';   
                }

                
                $('#sims .split__object-container').append(append_sim);
                
                // $('#bike_rent [name=*"owner"]').val(histories[Object.keys(histories)[0]].bike.owner).trigger('change');
                
            });

           
        }
    }

</script>
@endsection