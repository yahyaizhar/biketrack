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
                        <label>Select Rider:</label>
                        <select class="form-control kt-select2" id="kt_select2_3" name="rider_id" >
                        @foreach ($riders as $rider)
                       <option value="{{ $rider->id }}">
                        {{ $rider->name }}
                    </option>     
                      @endforeach 
                       </select> 
                   </div>

                    <div class="kt-portlet__body">
                        <div class="form-group">
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
                        </div>
                        <div class="form-group">
                            <label>Total Salary:</label>
                            <input readonly type="text" class="form-control @if($errors->has('net_salary')) invalid-field @endif" name="net_salary" value="">
                            @if ($errors->has('net_salary'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('net_salary') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Net salary</span>
                            @endif 
                                
                        </div>
                        <div class="form-group">
                            
                                
                        </div>
                        <div class="form-group">
                            <label>Gross Salary:</label>
                        <input readonly type="text" class="form-control @if($errors->has('gross_salary')) invalid-field @endif" name="gross_salary" value="">
                            @if ($errors->has('gross_salary'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('gross_salary') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Gross salary</span>
                            @endif
                                
                        </div>
                        <div class="form-group">
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
                                
                        </div>
                        <div class="form-group">
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
                                
                        </div>
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
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" checked="checked" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                       
                        <div>
                            <input type="hidden" name="setting">
                            <input type="hidden"  name="total_salary" >
                            <input type="hidden"  name="total_bonus" >
                            <input type="hidden"  name="total_deduction" >
                            <input type="hidden"  name="payment_status" value="pending" >
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
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

        $('#salary [name="recieved_salary"]').on('change input', function(){
            var _gross_salary = parseFloat($('#salary [name="gross_salary"]').val().trim());
            var _recieved_salary = parseFloat($(this).val().trim());
            $('#salary [name="remaining_salary"]').val(_recieved_salary-_gross_salary);
        });
        $('#salary [name="rider_id"],#salary [name="month"]').on('change', function(){
            var _riderid = $('[name="rider_id"]').val();
            var _month = $('[name="month"]').val();
            
            if(_riderid==''||_month=='')return;
            _month = new Date(_month).format('yyyy-mm-dd');
            console.log(_riderid, _month);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/accounts/company/debits/get_salary_deduction/')}}"+'/'+_month+'/'+_riderid,
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                $('#salary [name="gross_salary"], #salary [name="recieved_salary"]').val(data.gross_salary).trigger('change');
                $('#salary [name="net_salary"]').val(data.net_salary).trigger('change');
                $('#salary [name="total_deduction"]').val(data.total_deduction);
                $('#salary [name="total_salary"]').val(data.total_salary);
                $('#salary [name="total_bonus"]').val(data.total_bonus);
            });
        });
    });
</script>

@endsection