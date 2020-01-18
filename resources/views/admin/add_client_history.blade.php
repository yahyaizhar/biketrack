@extends('admin.layouts.app')
@section('head')

@endsection
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-hotel"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                       Add Client history detail
                    </h3>
                </div>
            </div>
            @include('admin.includes.message')  
        <form class="kt-form" method="POST" action="{{url('admin/submit_manual_client_history')}}">
            @csrf
                <div class="kt-portlet__body">
                    <div class="form-group">
                    <label>Clients</label>
                    <select class="form-control bk-select2" name="client_id">
                        @foreach($clients as $client)
                        <option value="{{$client->id}}">{{$client->name}}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="form-group">
                    <label>Riders</label>
                    <select class="form-control bk-select2" name="rider_id">
                            @foreach($riders as $rider)
                            <option value="{{$rider->id}}">{{$rider->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                    <label>Assign date</label>
                    <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required="" readonly="" class="month_picker form-control ft-init" name="assign_date" placeholder="Enter Month" value="">
                    </div>
                    <div class="form-group">
                     <label>Deassign date</label>
                    <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required="" readonly="" class="month_picker form-control ft-init" name="deassign_date" placeholder="Enter Month" value="">
                    </div>
                    <div class="form-group">
                    <label>FEID</label>
                    <input type="text" name="client_rider_id" id="client_rider_id" class="form-control">
                    </div>
                    <button class="btn btn-success" name="submit" type="submit">Submit</button>

                </div>
        </form>
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

  });

</script>
@endsection
