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
                            Create Mobile Installment
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->

            <form class="kt-form" action="{{route('MobileInstallment.store')}}" method="POST" enctype="multipart/form-data">
                    {{-- {{ method_field('PUT') }} --}}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                       <div class="form-group">
                            <label>Installment Month:</label>
                            <input type="text" class="dp__custom form-control @if($errors->has('installment_month')) invalid-field @endif" autocomplete="off" name="installment_month" placeholder="Installment month " value="{{ old('installment_month') }}">
                            @if ($errors->has('installment_month'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('installment_month')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Installment Amount:</label>
                            <input type="number" class="form-control @if($errors->has('installment_amount')) invalid-field @endif" autocomplete="off" name="installment_amount" placeholder="Installment amount " value="{{ old('installment_amount') }}">
                            @if ($errors->has('installment_amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('installment_amount')}}
                                    </strong>
                                </span>
                            @endif
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
    });
    </script>
@endsection