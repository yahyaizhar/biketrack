@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        Edit Client Income
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.client_income_update',$edit_client_income->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Client:</label>
                            <select required class="form-control kt-select2-general" name="client_id" >
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}" @if ($edit_client_income->client_id==$client->id) selected @endif>
                                    {{ $client->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group">
                                <label>Rider:</label>
                                <select required class="form-control kt-select2-general" name="rider_id" data-value="{{ $edit_client_income->rider_id }}">
                                @php
                                    $clients=App\Model\Client\Client::find($edit_client_income->client_id);
                                    $riders=$clients->riders;
                                @endphp         
                                @foreach ($riders as $rider)
                                    <option value="{{$rider->id}}"@if ($edit_client_income->rider_id==$rider->id) selected @endif>
                                        {{$rider->name}}</option>
                                @endforeach
                                </select>
                        </div>
                        <div class="form-group">
                                <label>Month:</label>
                                <input type="text" data-month="{{Carbon\Carbon::parse($edit_client_income->month)->format('M Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" >
                                @if ($errors->has('month'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('month') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter Month</span>
                                @endif
                        </div>
                        <div class="form-group">
                            <label>Amount:</label>
                        <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="{{ $edit_client_income->amount }}">
                            @if ($errors->has('amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('amount')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" {!! $edit_client_income->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                    </div>
                    
                   

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>

        <!--end::Portlet-->
    </div>
</div>

@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(document).ready(function(){
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
      $('[name="client_id"]').on('change', function(){

            var _client_id = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/client_income/')}}"+'/'+_client_id+'/getRiders',
                method: "GET"
            })
            .done(function(resp) {  
                // console.log(resp);
                $('[name="rider_id"]').html('');
                resp.riders.forEach(function(rider, index){
                    $('[name="rider_id"]').append('<option value="'+rider.id+'">'+rider.name+'</option>');
                });
                $('[name="rider_id"]')[0].selectedIndex = -1;
                $('[name="rider_id"]').trigger('change');
            });
        });
      
  });

</script>
@endsection