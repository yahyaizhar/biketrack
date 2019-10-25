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
                <div  id="hidden_area">
{{-- for bike --}}
                {{-- <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="bike_detail">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-portlet__body">
                                    <div class="kt-widget kt-widget--user-profile-3">
                                        <div class="kt-widget__top">
                                            <div style="color:#ccc;" class="kt-widget__media kt-hidden-">
                                                <h2>Bike Detail</h2>
                                            </div>
                                            <div class="kt-widget__content">
                                                <div class="kt-widget__head">
                                                    <a class="kt-widget__username">
                                                        <h2><span class="bike_name"></span></h2>
                                                    </a>
                                                    <div class="kt-widget__action"> --}}
                                                        {{-- <a href="" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp; --}}
                                                        {{-- <h6>Assign_date: <strong><span class="assign_date"></span></strong></h6>
                                                    </div>
                                                </div>
                                                <div class="kt-widget__subhead">
                                                    <a>Bike Number: <strong><span class="bike_number"></span></strong></a>
                                                </div>
                                                <div class="">
                                                    <p>
                                                        Salik_Amount: <strong><span class="salik"></span></strong>
                                                    </p>
                                                    <p>
                                                        Fuel Expense: <strong><span class="fuel_expense"></span></strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>          
                            </div>
                        </div>
                    </div>
                </div>    --}}
{{-- end for bike --}} 
{{-- for sim  --}}
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="sim_detail">
        <div class="row">
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div style="color:#ccc;" class="kt-widget__media kt-hidden-">
                                    <h2>Sim Detail</h2>
                                </div>
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                        <a class="kt-widget__username">
                                            <h2><span class="sim_name"></span></h2>
                                        </a>
                                        <div class="kt-widget__action">
                                            {{-- <a href="" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp; --}}
                                            <h6>Assign_date: <strong><span class="assign_date_sim"></span></strong></h6>
                                        </div>
                                    </div>
                                    <div class="kt-widget__subhead">
                                        <a>Sim Number: <strong><span class="sim_number"></span></strong></a> 
                                    </div>
                                    <div class="">
                                        <p>
                                            Sim_Charges: <strong><span class="sim_charges"></span></strong>
                                        </p>
                                        {{-- <p>
                                            Fuel Expense: <strong><span class="fuel_expense"></span></strong>
                                        </p> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>          
                </div>
            </div>
        </div>
    </div>
{{-- end sim --}}
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
    // $("#hidden_area").hide();
    $('[name="month_id"]').on("change",function(){
        $('[name="rider_id"]').on("change",function(){
        var month=$('[name="month_id"]').val();
        var rider_id=$('[name="rider_id"]').val();
        // $("#hidden_area").show();
        var url="{{ url('admin/rider/detail/ajax') }}" + "/" + rider_id + "/" + month;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:  url,
            method: "GET"
        })
        .done(function(data) {  
            console.log(data);
            // $(".bike_name").html(data.brand + " " + data.model);
            // $(".assign_date").html(data.assign_bike_date);
            // $(".bike_number").html(data.bike_number);
            // $(".salik").html(data.salik);
            // $(".fuel_expense").html(data.fuel_expense);
            
            swal.fire({
                position: 'center',
                type: 'success',
                title: 'Record updated successfully.',
                showConfirmButton: false,
                timer: 1500
                });
        });
        });
    });
});
</script>
@endsection
