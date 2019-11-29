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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-2">
                                <label for="month_id">Select Month</label>
                                <select class="form-control kt-select2 bk-select2" id="month_id" name="month_id" >
                                    <option value="">Select Month</option>
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-01-01">January</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-02-01">Febuary</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-03-01">March</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-04-01">April</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-05-01">May</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-06-01">June</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-07-01">July</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-08-01">August</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-09-01">September</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-10-01">October</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-11-01">November</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-12-01">December</option>    
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2">
                                <label for="rider_id">Select Rider</label>
                                <select class="form-control kt-select2 bk-select2" id="rider_id" name="rider_id" >
                                <option value="">Select Rider</option>
                                    @foreach ($riders as $rider)
                                        <option value="{{ $rider->id }}" 
                                        >{{ $rider->name }}</option>    
                                        @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2">
                                <label for="sim_id">Select Sim</label>
                                <select class="form-control kt-select2 bk-select2" id="sim_id" name="sim_id" >
                                <option value="">Select Sim</option>
                                    @foreach ($sims as $sim)
                                        <option value="{{ $sim->id }}">{{ $sim->sim_number }}</option>    
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2">
                                <label for="bike_id">Select Bike</label>
                                <select class="form-control kt-select2 bk-select2" id="bike_id" name="bike_id" >
                                <option value="">Select Bike</option>
                                    @foreach ($bikes as $bike)
                                        <option value="{{ $bike->id }}">{{ $bike->model }}-{{$bike->bike_number}}</option>    
                                    @endforeach
                                </select> 
                            </div>
                        </div>
                    </div>
                    
                    
                    
                </div>
                @include('admin.includes.message')
                <div  id="hidden_area">
                    {{-- for rider  --}}
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="rider_detail">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="kt-portlet kt-portlet--height-fluid">
                                    <div class="kt-portlet__body">
                                        <div class="kt-widget kt-widget--user-profile-3">
                                            <div class="kt-widget__top">
                                                <div style="color:#ccc;" class="kt-widget__media kt-hidden-">
                                                    <h2>Rider Detail</h2>
                                                </div>
                                                <div class="kt-widget__content">
                                                    <div class="kt-widget__head">
                                                        <a class="kt-widget__username rider_name_wrapper">
                                                            <h2><span class="rider_name"></span></h2>
                                                        </a>
                                                    </div>
                                                    <div class="kt-widget__subhead">
                                                        <a>KR ID: <strong><span class="kr_id"></span></strong></a> 
                                                    </div>
                                                    {{-- <div class="">
                                                        <p>
                                                            Allowed Blance: <strong><span class="allowed_balance"></span></strong>
                                                        </p>
                                                        <p>
                                                            Useage: <strong><span class="useage"></span></strong>
                                                        </p>
                                                        <p>
                                                            Extra Useage: <strong><span class="extra_useage"></span></strong>
                                                        </p>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>          
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end rider --}}
                {{-- for bike --}}
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="bike_detail">
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
                                                        <div class="kt-widget__action">
                                                            {{-- <a href="" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp; --}}
                                                            <h6>Assign_date: <strong><span class="assign_date"></span></strong></h6>
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
                    </div>   
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
                                                            <h2><span class="sim_company_name"></span></h2>
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
                                                            Allowed Blance: <strong><span class="allowed_balance"></span></strong>
                                                        </p>
                                                        <p>
                                                            Useage: <strong><span class="useage"></span></strong>
                                                        </p>
                                                        <p>
                                                            Extra Useage: <strong><span class="extra_useage"></span></strong>
                                                        </p>
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

                    {{-- for Client  --}}
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="client_detail">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="kt-portlet kt-portlet--height-fluid">
                                    <div class="kt-portlet__body">
                                        <div class="kt-widget kt-widget--user-profile-3">
                                            <div class="kt-widget__top">
                                                <div style="color:#ccc;" class="kt-widget__media kt-hidden-">
                                                    <h2>Client Detail</h2>
                                                </div>
                                                <div class="kt-widget__content">
                                                    <div class="kt-widget__head">
                                                        <a class="kt-widget__username client_name_wrapper">
                                                            <h2><span class="client_name"></span>
                                                                <i class="flaticon2-correct active_mark" style="display:none"></i>  
                                                            </h2>
                                                        </a>
                                                        <div class="kt-widget__action">
                                                            <h6>Active Dates: <strong><span class="client_assign_date"></span></strong></h6>
                                                        </div>
                                                    </div>
                                                    <div class="kt-widget__subhead">
                                                        <a>KR-C ID: <strong><span class="kr_c_id"></span></strong></a> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>          
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end Client --}}
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
    $("#hidden_area").hide();
    
    $('[name="rider_id"],[name="sim_id"],[name="bike_id"],[name="month_id"]').on("change",function(){
        var month=$('[name="month_id"]').val();
        if(month == ""){
            alert('Select Month first');
            return;
        }
        var _type = $(this).attr('name');
        var according_to = '';
        var _sim_id = $('[name="sim_id"]').val();
        var _bike_id = $('[name="bike_id"]').val();
        var _rider_id = $('[name="rider_id"]').val();
        if(_rider_id!='') {
            according_to = 'rider';
            _type = 'rider_id';
        }
        else if(_sim_id!='') {
            according_to = 'sim';
            _type = 'sim_id';
        }
        else if(_bike_id!='') {
            according_to = 'bike';
            _type = 'bike_id';
        }
        if(according_to==''){
            //alert('Select one of the options');
            return;
        }
        var _id=$('[name="'+_type+'"]').val();
        // $("#hidden_area").show();
        var url="{{ url('admin/rider/detail/ajax') }}" + "/" + _id + "/" + month+"/"+according_to;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:  url,
            method: "GET"
        })
        .done(function(data) {  
            console.log(data);
            if(!data.status || data.status != 1){
                alert(data.message);
                return;
            } 
            if(data.rider == null){
                $('#rider_detail').hide();
            }
            else{
                $('#rider_detail').show();
                $('.rider_name_wrapper').attr('href', "{{url('admin/rider')}}/"+data.rider.id+"/profile")
                .find('.rider_name').html(data.rider.name);
                $('.kr_id').html('KR'+data.rider.id);
            }
            if(data.client == null){
                $('#client_detail').hide();
            }
            else{
                $('#client_detail').show();
                $('.client_name_wrapper').attr('href', "{{url('admin/client')}}/"+data.client.id+"/riders")
                .find('.client_name').html(data.client.name);
                $('.kr_c_id').html('KR-C-'+data.rider.id);
                if(data.client_history_found.active_status=='active') $('.client_name_wrapper').find('.active_mark').show();
                else $('.client_name_wrapper').find('.active_mark').hide();
            }
            
            $(".bike_name").html(data.brand + " " + data.model);
            $(".assign_date").html(data.assign_bike_date__start +'<span style=" color: #999; padding: 0 6px; ">TO</span>'+data.assign_bike_date__end);
            $(".bike_number").html(data.bike_number);
            $(".salik").html(data.salik);
            $(".fuel_expense").html(data.fuel_expense);
            
            if(data.sim == null){
                $('#sim_detail').hide();
            }
            else{
                $('#sim_detail').show();
                $(".sim_company_name").html(data.sim_company);
                $(".assign_date_sim").html(data.assign_sim_date__start +'<span style=" color: #999; padding: 0 6px; ">TO</span>'+data.assign_sim_date__end);
                $(".sim_number").html(data.sim_number);
                $(".allowed_balance").html(data.allowed_balance);
                $(".useage").html(data.sim_usage);
                $(".extra_useage").html(data.sim_Extra_usage);
            }
            $("#hidden_area").show();
        });
    });
    
});
</script>
@endsection
