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
                            Assign Riders - <a href="{{ route('admin.clients.riders', $client->id) }}">{{ $client->name }}</a>
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('client.includes.message')
                <form class="kt-form" action="{{ route('admin.clients.assignRiders', $client->id) }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Riders: </label>
                            <div>
                                <select required class="form-control kt-select2" id="kt_select2_3" name="riders[]" multiple="multiple">
                                @foreach ($riders as $rider)
                                    <option value="{{ $rider->id }}"
                                    >{{ $rider->name }} </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date Rider Assign to Client:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="assign_date" placeholder="Enter Month" value="">
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <span class="kt-margin-l-10">or <a href="{{ route('admin.clients.index') }}" class="kt-link kt-font-bold">Cancel</a></span>
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
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
     
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
      $(document).ready(function(){
          $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
      });
    </script>
@endsection