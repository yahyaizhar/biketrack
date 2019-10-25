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
                <div class="" id="hidden_area">
{{-- for bike --}}
                <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="bike_detail">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-portlet__body">
                                    <div class="kt-widget kt-widget--user-profile-3">
                                        <div class="kt-widget__top">
                                            <div class="kt-widget__media kt-hidden-">
                                                <h4>Bike Detail</h4>
                                            </div>
                                                {{-- <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                                    JM
                                                </div> --}}
                                            <div class="kt-widget__content">
                                                <div class="kt-widget__head">
                                                    <a class="kt-widget__username">
                                                        {{ 'asma' }}
                                                        {{-- @if ($rider->online)
                                                            <i class="flaticon2-correct"></i>                                            
                                                        @endif --}}
                                                    </a>
                                                    <div class="kt-widget__action">
                                                        <a href="" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp;
                                                        <a href="" class="btn btn-label-danger btn-sm btn-upper">View Location</a>&nbsp;
                                                        {{-- <button class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp; --}}
                                                    </div>
                                                </div>
                                                <div class="kt-widget__subhead">
                                                    {{-- <a href="mailto:{{ $rider->email }}"><i class="flaticon2-new-email"></i>{{ $rider->email }}</a> --}}
                                                    <a><i class="flaticon2-calendar-3"></i>09090990 </a>
                                                    {{-- @if ($bike==null) --}}
                                                    <a><i class="fa fa-motorcycle"></i>No Bike assigned to this rider</a>     
                                                    {{-- @else --}}
                                                    <a><i class="fa fa-motorcycle"></i>{{ 'ferty6657' }}</a> 
                                                    {{-- @endif --}}
                                                    
                                                </div>
                                                <div class="kt-widget__info">
                                                    <i class="flaticon-location"></i>&nbsp;
                                                    <div class="kt-widget__desc">
                                                        {{ 'lahore' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>          
                            </div>
                        </div>
                    </div>
                </div>   
{{-- end for bike --}}  
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
           var month=$('[name="month_id"]').val();
           var rider_id=$('[name="rider_id"]').val();
           console.log(month);
           console.log(rider_id);
         });
      });
});
</script>
@endsection
