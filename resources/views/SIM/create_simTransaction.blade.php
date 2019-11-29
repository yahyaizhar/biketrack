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
                            <div class="form-group">
                                <label>Select Rider:</label>
                                <select class="form-control bk-select2 kt-select2" id="kt_select2_3" name="rider_id" >
                                @foreach ($riders as $rider)
                                    <option value="{{ $rider->id }}">
                                        {{ $rider->name }}
                                    </option>     
                                @endforeach 
                                </select> 
                            </div>

                            <div class="form-group">
                                <label>Sim:</label>
                                <select required class="form-control kt-select2 bk-select2" name="sim_id" >
                                    @foreach ($sims as $sim)
                                    <option value="{{ $sim->id }}">
                                        {{ $sim->sim_number }}
                                    </option>     
                                    @endforeach 
                                </select>
                            </div>
                            <div class="form-group ">
                                <label>Allowed Balance:</label>
                                <input required readonly type="text" class="form-control @if($errors->has('usage_limit')) invalid-field @endif" name="usage_limit" >
                                @if ($errors->has('usage_limit'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('usage_limit')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>       
                            <div class="form-group">
                                <label>Bill Amount:</label>
                                <input required type="text" class="form-control @if($errors->has('bill_amount')) invalid-field @endif" name="bill_amount">
                            </div>
                            <div class="form-group">
                                <label>Extra Usage Amount:</label>
                                <input required readonly type="text" class="form-control @if($errors->has('extra_usage_amount')) invalid-field @endif" name="extra_usage_amount" placeholder="Enter extra usage amount " value="{{ old('extra_usage_amount') }}">
                                @if ($errors->has('extra_usage_amount'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{$errors->first('extra_usage_amount')}}
                                        </strong>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" name="total_month_days">
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
        $('#sims [name="month_year"],#sims [name="sim_id"]').on('change', function(){
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
                        $('#sims [name="rider_id"]').val(data.sim_histories.rider_id).trigger('change.select2');
                        $('#sims [name="usage_limit"], #sims [name="bill_amount"]').val(data.sim_histories.allowed_balance).trigger('change');                
                    }
                    else{
                        $('#sims [name="rider_id"]')[0].selectedIndex = -1;
                        $('#sims [name="rider_id"]').trigger('change.select2');
                    }
                    // $('#sims [name="usage_limit"], #sims [name="bill_amount"]').val(data.usage_limit).trigger('change');
                });
            }
        })
        $('#sims [name="rider_id"]').on('change', function(){
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
                        $('#sims [name="sim_id"]').val(data.sim_histories.sim_id).trigger('change.select2');
                        $('#sims [name="usage_limit"], #sims [name="bill_amount"]').val(data.sim_histories.allowed_balance).trigger('change');                
                    }
                    else{
                        $('#sims [name="sim_id"]')[0].selectedIndex = -1;
                        $('#sims [name="sim_id"]').trigger('change.select2');
                    }
                    // $('#sims [name="usage_limit"], #sims [name="bill_amount"]').val(data.usage_limit).trigger('change');
                });
            }
        })
        //$('#sims [name="month_year"]').trigger('change');
        $('#sims [name="rider_id"]').trigger('change');
        //     $(' #sims [name="sim_id"]').on('change', function(){
        //     var _simId = $('#sims [name="sim_id"]').val();
        //     var _month = $('#sims [name="month_year"]').val();
        //     var _rider_id=$('#sims [name="rider_id"]').val();
        //     if(_simId== null) {
        //         $('#sims [name="usage_limit"]').val('');
        //         $('#sims [name="original_bill_amount"]').val('');
        //         $('#sims [name="extra_usage_amount"]').val('');
        //         return;
        //     }
    //         _month = new Date(_month).format('yyyy-mm-dd');

    //         $.ajax({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }, 
    //             url:"{{url('admin/sim/ajax/data/')}}"+"/"+_simId+"/"+_month +"/"+_rider_id,
    //             method: "GET"
    //         })
    //         .done(function(data) { 
    //             if(data.sim_history!==null){
    //                     $('#sims [name="usage_limit"], #sims [name="bill_amount"]').val(data.sim_history.allowed_balance).trigger('change');
    //                 }
    //                 else{
    //                     $('#sims [name="usage_limit"], #sims [name="bill_amount"]').val(0);
    //                 }
               
    //         });
    // });
        
        $('#sims [name="bill_amount"]').on('change input', function(){
            var _usage_limit = $('#sims [name="usage_limit"]').val();
            var _billAmt = $(this).val();
            var _extra =_billAmt - _usage_limit;
            console.log(_extra);
            if(_extra < 0) _extra = 0;
            $('#sims [name="extra_usage_amount"]').val(_extra);
        });

    }); 


</script>
@endsection