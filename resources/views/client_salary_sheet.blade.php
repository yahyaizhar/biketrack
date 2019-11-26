@extends('admin.layouts.app')
@section('main-content')
<style>
    .custom-file-label::after{
        color: white;
        background-color: #5578eb;
    }
    .custom-file-label{
        overflow: hidden;
    }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{$client->name}} Month Summary
                        </h3>
                    </div>
                </div>
            <div class="kt-portlet__body">
                <div>
                    <select class="form-control bk-select2" id="kt_select2_3_5" name="month_id" >
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
            </div>
            </div>
        </div>
    </div>
</div>   
<div class="kt-content  kt-grid__item kt-grid__item--fluid hidden_values" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{$client->name}} Month Summary 
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body" style="display:flex; flex-flow:wrap column;">
                    <div class="row">
                        <div class="total_payout col-md-2" style="padding-right: 0px !important;"></div>
                        <div class="aed_hour_client col-md-2" style="padding-right: 0px !important;"></div>
                        <div class="aed_trips_client col-md-2" style="padding-right: 0px !important;"></div>
                        <div class="sum_1 col-md-2" style="padding-right: 0px !important;"></div>
                    </div>
                    <div class="row">
                        <div class="salary_bonus col-md-2"></div>
                        <div class="trips col-md-1"></div>
                        <div class="hours col-md-2"></div>
                        <div class="aed_trips col-md-2"></div>
                        <div class="aed_hours col-md-2"></div>
                        <div class="bonus col-md-1"></div>
                        <div class="sum_2 col-md-2"></div>
                    </div>
                    <div class="row">
                        <div class="expense_bills col-md-2"></div>
                        <div class="bike_rent col-md-2"></div>
                        <div class="fuel col-md-2"></div>
                        <div class="salik col-md-2"></div>
                        <div class="sim col-md-2"></div>
                        <div class="sum_3 col-md-2"></div>
                    </div>
                    <div class="row">
                        <div class="profit col-md-2"></div>
                        <div class="profit_val col-md-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="client_id" value="{{$client->id}}">
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
        $(".hidden_values").hide();
        $('#kt_select2_3_5').on("change",function(){
            $(".hidden_values").show();
            var month=$(this).val();
            var client=$('[name="client_id"]').val();
            var url = "{{ url('admin/client/month/record') }}"+"/"+month+"/"+client;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'GET',
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    console.log(data);
                    $hour_client=(data.aed_hours_client).toFixed(2);
                    $trip_client=(data.aed_trips_client).toFixed(2);
                    $sum_1=(data.sum_1).toFixed(2);
                    $('.total_payout').html('<strong>Total Payout:</strong>');
                    $('.aed_hour_client').html('AED Hours: <strong>'+$hour_client+'</strong>');
                    $('.aed_trips_client').html('AED Trips: <strong>'+$trip_client+'</strong>');
                    $('.sum_1').html('Sum/Total: <strong>'+$sum_1+'</strong>');

                    $trips=(data.trips).toFixed(2);
                    $hours=(data.hours).toFixed(2);
                    $aed_trips=(data.aed_trips).toFixed(2);
                    $aed_hours=(data.aed_hours).toFixed(2);
                    $bonus=(data.bonus).toFixed(2);
                    $sum_2=(data.sum_2).toFixed(2);
                    $('.salary_bonus').html('<strong>Salary + Bonus:</strong>');
                    $('.hours').html('Hours: <strong>'+$hours+'</strong>');
                    $('.trips').html('Trips: <strong>'+$trips+'</strong>');
                    $('.aed_trips').html('AED Trips: <strong>'+$aed_trips+'</strong>');
                    $('.aed_hours').html('AED Hours: <strong>'+$aed_hours+'</strong>');
                    $('.bonus').html('Bonus: <strong>'+$bonus+'</strong>');
                    $('.sum_2').html('Sum/Total: <strong>'+$sum_2+'</strong>');

                    $bike_rent=(data.bike_rent).toFixed(2);
                    $fuel=(data.fuel).toFixed(2);
                    $salik=(data.salik).toFixed(2);
                    $sim=(data.sim).toFixed(2);
                    $sum_3=(data.sum_3).toFixed(2);
                    $('.expense_bills').html('<strong>Expense & Bills:</strong>');
                    $('.bike_rent').html('Bike-Rent: <strong>'+$bike_rent+'</strong>');
                    $('.fuel').html('Fuel: <strong>'+$fuel+'</strong>');
                    $('.salik').html('Salik: <strong>'+$salik+'('+data.salik_extra+')</strong>');
                    $('.sim').html('Sim: <strong>'+$sim+'('+data.sim_extra+')</strong>');
                    $('.sum_3').html('Sum/Total: <strong>'+$sum_3+'</strong>');

                    $profit=(data.sum_1-(data.sum_2+data.sum_3)).toFixed(2);
                    $('.profit').html('<strong>Profit:</strong>');
                    $('.profit_val').html('Profit:<strong>'+$profit+'</strong>');


                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to update.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    });

</script>
@endsection