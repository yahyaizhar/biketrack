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
                          Total Payout: <input type="text" class="form-control" id="total_payout">
                          Salik: <input type="text" class="form-control" id="salik">
                          Fuel: <input type="text" class="form-control" id="fuel">
                          Sim: <input type="text" class="form-control" id="sim">
                          Rent: <input type="text" class="form-control" id="rent">
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
                        $rent=0;
                        $profit=$payout-($fuel+$salik+$sim+$rent);
                        $('#total_payout').val($payout);
                        $('#salik').val($fuel);
                        $('#fuel').val($salik);
                        $('#sim').val($sim);
                        $('#rent').val($rent);
                        $('#profit').val($profit);
                        swal.fire({
                            position: 'center',
                            type: 'success',
                            title: 'Record updated successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(error){
                        _modal.modal('hide');
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