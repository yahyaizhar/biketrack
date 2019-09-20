@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label" >
                            
                        <h3 class="kt-portlet__head-title">
                            Create Mobile Consumption
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->

            <form class="kt-form" action="{{route('Mobile.consumption_mobile_records_insert')}}" method="POST" id="mobile" enctype="multipart/form-data">
                    {{-- {{ method_field('PUT') }} --}}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Date:</label>
                            <input type="text" data-month="{{carbon\carbon::now()->format('M d, Y')}}" class=" month_picker form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" id="month" placeholder="Enter Month_Year" value="{{ old('month_year') }}">
                            @if ($errors->has('month_year'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('month_year')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Select Mobile:</label>
                            <div>
                                <select id="mobile" class="form-control kt-select2" id="kt_select2_3" name="brand" >
                                    @foreach ($mobiles as $mobile)
                                <option value='{{$mobile->id}}'>{{$mobile->model}}</option>
                                    @endforeach
                                </select> 
                                <span class="form-text text-muted">Like <strong>Samsung</strong>.</span>
                            </div> 
                        </div>
                        <div class="form-group">
                            <label>Sale Price:</label>
                            <input readonly type="number" class="form-control @if($errors->has('sale_price')) invalid-field @endif" name="sale_price" placeholder="Enter sale price " value="{{ old('sale_price') }}">
                            @if ($errors->has('sale_price'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('sale_price')}}
                                    </strong>
                                </span>
                            @endif
                        </div> 
                        <div class="just_installment">
                        <div class="form-group">
                            <label>Amount received:</label>
                            <input readonly id="amount_received" type="text" class="form-control " name="amount_received" placeholder="Enter received amount " >
                        </div>
                        <div class="form-group">
                            <label>Remaining Amount:</label>
                            <input readonly  id="remaining_amount" type="text" class=" form-control" autocomplete="off" name="remaining_amount">
                        </div>
                        <div class="form-group">
                            <label>Installment Amount:</label>
                            <input   type="number" class="form-control " autocomplete="off" name="per_month_installment_amount" placeholder="Per month installments " >
                        </div>
                    </div>
                        
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <span class="kt-margin-l-10">or <a href="{{url('/admin/riders')}}" class="kt-link kt-font-bold">Cancel</a></span> --}}
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>
@endsection
@section('foot')
    <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
    <script>
    $(function(){
        $('.dp__custom').fdatepicker({ format: 'MM yyyy',startView:3,minView:3,maxView:4});
        $('.kt-select2').select2({
            placeholder: "Select an option",
            width:'100%'    
        });
    });
    $('#mobile [name="brand"],#mobile [name="month_year"] ').on('change', function(){
            var _brandId = $('#mobile [name="brand"]').val();
            var _month = $('#mobile [name="month_year"]').val();
            console.log(_brandId);
            console.log(_month);
            _month = new Date(_month).format('yyyy-mm-dd');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('/admin/mobile/ajax/data/')}}"+"/"+_brandId+"/"+_month,
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                $('#mobile [name="sale_price"]').val(data.sale_price).attr('data-amount', data.sale_price); 
                $('#mobile [name="amount_received"]').val(data.amount_received).attr('data-amount', data.amount_received);  
                $('#mobile [name="remaining_amount"]').val(data.remaining_amount).attr('data-amount', data.remaining_amount); 
                $('#mobile [name="per_month_installment_amount"]').val(data.installment_amount).attr('data-amount', data.installment_amount); 
            });
        }); 
        $(document).ready(function(){
        $('#mobile [name="per_month_installment_amount"]').on('change input', function(){
            var _installment=parseFloat($(this).val());
            var sale_price = parseFloat($('#mobile [name="sale_price"]').attr('data-amount'));
            var remaining_amount = parseFloat($('#mobile [name="remaining_amount"]').attr('data-amount'));
            var _amount_received =parseFloat($('#mobile [name="amount_received"]').attr('data-amount'));
            var _rest_RA =remaining_amount-_installment; 
            var _res_AR=_amount_received+_installment;
            if (_res_AR<=sale_price ) {
            $('#mobile [name="amount_received"]').val(_res_AR);
            $('#mobile [name="remaining_amount"]').val(_rest_RA);
            }
            else if(_rest_RA<=0){
              $(this).val(remaining_amount).trigger('change');
            }
            if(_installment==0 || isNaN(_installment)){
                $('#mobile [name="remaining_amount"]').val(remaining_amount);
                $('#mobile [name="amount_received"]').val(_amount_received);
            }
           
            
        });
    });
    </script>
@endsection