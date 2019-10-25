@extends('admin.layouts.app')
@section('main-content')
<style>
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label" >
                        <h3 class="kt-portlet__head-title">
                            Rider Detail
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="p-2">
                        <select class="form-control kt-select2 bk-select2" id="month_id" name="month_id" >
                            <option >Select Month</option>
                            <option value="01">January</option>   
                            <option value="02">Febuary</option>   
                            <option value="03">March</option>   
                            <option value="04">April</option>   
                            <option value="05">May</option>   
                            <option value="06">June</option>   
                            <option value="07">July</option>   
                            <option value="08">August</option>   
                            <option value="09">September</option>   
                            <option value="10">October</option>   
                            <option value="11">November</option>   
                            <option value="12">December</option>    
                        </select> 
                    </div>
                    <div class="p-2">
                        <select class="form-control kt-select2 bk-select2" id="rider_id" name="rider_id" >
                        <option value="Select Rider">Select Rider</option>
                            @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}" 
                                    >{{ $rider->name }}</option>    
                                 @endforeach
                        </select> 
                    </div>
                    
                </div>
                @include('admin.includes.message')
                <div class="kt-portlet__body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script>
$(document).ready(function(){
      $('[name="month_id"]').on("change",function(){
         $('[name="rider_id"]').on("change",function(){
            alert("rider and month");
         });
      });
});
</script>
@endsection
