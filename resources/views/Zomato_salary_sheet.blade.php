@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
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
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Zomato September Sheet
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('bike.bike_create') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="hour"></div>
                        <div class="trip"></div>
                        <div class="aed_hour"></div>
                        <div class="aed_trips"></div>
                        <div class="ncw"></div>
                        <div class="tips"></div>
                        <div class="penalty"></div>
                        <div class="dc_cod"></div>
                        <div class="total_payout"></div>
                        <div class="salik"></div>
                        <div class="fuel"></div>
                        <div class="sim"></div>
                        <div class="salary"></div>
                        <div class="profit"></div>
                    </div>
                </form>
            </div>
    </div>
</div>
</div>

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
    var url = "{{ url('admin/zomato/september') }}";
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
                        $fuel=data.bike_fuel;
                        $salik=data.salik;
                        $sim=data.sim;
                        $salary=data.salary;
                        $hour=data.total_hours;
                        $trip=data.total_trips;
                        $ncw=data.ncw;
                        $tips=data.tips;
                        $penalty=data.denials_penalty;
                        $cod=data.cod;
                        $dc_charges=data.DC_deduction;
                        $aed_hour=$hour * 6;
                        $aed_trip=$trip * 6.86059;
                        $dc_cod= $cod+$dc_charges;
                        $payout=$aed_hour+$aed_trip;
                        $profit=$payout-($fuel+$salik+$sim+$salary);
                        
                        $('.hour').html('Total Hours: <stron>'+$hour+'</strong>');
                        $('.trip').html('Total Trips: <stron>'+$trip+'</strong>');
                        $('.aed_hour').html('AED Hours: <stron>'+$aed_hour+'</strong>');
                        $('.aed_trips').html('AED Trips: <stron>'+$aed_trip.toFixed(2)+'</strong>');
                        $('.ncw').html('Incentives: <stron>'+$ncw+'</strong>');
                        $('.tips').html('Tips: <stron>'+$tips+'</strong>');
                        $('.penalty').html('Denial Penalty: <stron>'+$penalty+'</strong>');
                        $('.dc_cod').html('DC Chrges & COD Amount: <stron>'+$dc_cod+'</strong>');
                        $('.total_payout').html('Total Payout: <stron>'+$payout.toFixed(2)+'</strong>');
                        $('.salik').html('Salik: <stron>'+$salik+'</strong>');
                        $('.fuel').html('Fuel: <stron>'+$fuel+'</strong>');
                        $('.sim').html('Sim: <stron>'+$sim+'</strong>');
                        $('.salary').html('Salary: <stron>'+$salary+'</strong>' );
                        $('.profit').html('Profit: <stron>'+$profit.toFixed(2)+'</strong>' );
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

</script>
@endsection