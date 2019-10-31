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
                        Total Hours: <input type="text" class="form-control" id="hour">
                        Total Trips: <input type="text" class="form-control" id="trip">
                        AED Hours: <input type="text" class="form-control" id="aed_hour">
                        AED Trips: <input type="text" class="form-control" id="aed_trips">
                        Incentives: <input type="text" class="form-control" id="ncw">
                        Tips: <input type="text" class="form-control" id="tips">
                        Denial Penalty: <input type="text" class="form-control" id="penalty">
                        DC Chrges & COD Amount: <input type="text" class="form-control" id="dc_cod">
                        Total Payout: <input type="text" class="form-control" id="total_payout">
                        Salik: <input type="text" class="form-control" id="salik">
                        Fuel: <input type="text" class="form-control" id="fuel">
                        Sim: <input type="text" class="form-control" id="sim">
                        Salary: <input type="text" class="form-control" id="salary">
                        Profit: <input type="text" class="form-control" id="profit">
                          
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
                        $payout=data.payout;
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
                        $aed_trip=$trip * 6.86;
                        $dc_cod= $cod+$dc_charges;
                        $profit=$payout-($fuel+$salik+$sim+$salary);
                        $('#hour').val($hour);
                        $('#trip').val($trip);
                        $('#aed_hour').val($aed_hour);
                        $('#aed_trips').val($aed_trip);
                        $('#ncw').val($ncw);
                        $('#tips').val($tips);
                        $('#penalty').val($penalty);
                        $('#dc_cod').val($dc_cod);

                        $('#total_payout').val($payout);
                        $('#salik').val($salik);
                        $('#fuel').val($fuel);
                        $('#sim').val($sim);
                        $('#salary').val($salary);
                        $('#profit').val($profit.toFixed(2));
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