@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="" style="padding-top:15px;">
                        <h3 class="kt-portlet__head-title">
                          Assign Sim To: <a href="">{{$rider_id->name}}</a>
                          @if ($sim_val<=0)
                            @else
                            <span style="font-size: 15px;color: #5867e4;display: block;width: 100%;font-weight: bold;">This Rider have already an active Sim</span>
                            @endif
                        </h3>
                        {{-- @if ($assign_bike<=0)
                        @else --}}
                        {{-- <span style="color: #5867e4;display: block;width: 100%;font-weight: bold;">This Rider have already an active bike</span> --}}
                        {{-- @endif --}}
                        
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('client.includes.message')
    @php
    // $bike = App\Model\Bike\Bikes::all();
    // $current_bike = App\Model\Rider\Rider::find($rider->id)->bike;

@endphp

            <form class="kt-form" action="{{route('SimHistory.store_simHistory',$rider_id->id)}}" method="POST" enctype="multipart/form-data">
                    {{-- {{ method_field('PUT') }} --}}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Select Sim:</label>
                          <div>
                            <select class="form-control kt-select2" id="kt_select2_3" name="sim_id" >
                                 @foreach ($sim as $sims)
                                 @php
                                 $temp=true; 
                                     $sim_history=$sims->Sim_History()->where('status','active')->get()->first();
                                    if (isset($sim_history)) {
                                        $temp=false; 
                                    }
                                 @endphp

                                 @if ($temp && $sims->status==1)
                                 <option value="{{ $sims->id }}">
                                    {{ $sims->sim_company }}-{{ $sims->sim_number}}
                                </option>  
                                @endif
                                @endforeach 
                            </select> 
                             </div> 
                            </div>
                           
                        <div class="form-group">
                            <label>Allowed Balance:</label>
                            <input type="text" class="form-control @if($errors->has('allowed_balance')) invalid-field @endif" name="allowed_balance" placeholder="Allowed Balance " value="{{ old('allowed_balance') }}">
                            @if ($errors->has('allowed_balance'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('allowed_balance')}}
                                    </strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                                <label>Given Date:</label>
                                <input type="text" id="datepicker1" autocomplete="off" class="form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter given Date" >
                                @if ($errors->has('given_date'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('given_date') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter your Given Date</span>
                                @endif
                            </div>
                            <div class="form-group">
                                    <label>Return Date:</label>
                                    <input type="text" id="datepicker2" autocomplete="off" class="form-control @if($errors->has('return_date')) invalid-field @endif" name="return_date" placeholder="Enter Return Date">
                                    @if ($errors->has('return_date'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('return_date') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter your Return Date</span>
                                    @endif
                                </div>
                        </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <span class="kt-margin-l-10">or <a href="{{url('/admin/riders')}}" class="kt-link kt-font-bold">Cancel</a></span> --}}
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>
@endsection
@section('foot')
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
 
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
  
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 
  <script>
      $(document).ready(function(){
          $('#datepicker1').fdatepicker({format: 'dd-mm-yyyy'});  
          $('#datepicker2').fdatepicker({format: 'dd-mm-yyyy'}); 
         
      });
  
  </script>
    @endsection