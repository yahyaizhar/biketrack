@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Employee Allowns
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" id="emp_allowns" action="{{ route('employee.insert_employee_allowns') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <input type="hidden" name="employee_id" value="">
                        <div class="form-group">
                            <label>Month:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker_only form-control" name="month" placeholder="Enter Month">
                            <span class="form-text text-muted">Please enter Month</span>
                        </div>
                        <div class="form-group">
                            @php
                                $type=$_GET['type'];
                            @endphp
                            <label>Type:</label>
                            <select required  class="form-control @if($errors->has('type')) invalid-field @endif bk-select2" name="type">
                                <option value="transport" id="transport" @if($type=="transport") selected @endif data-default="200">Transport Allowance</option>
                                <option value="marketing" id="marketing" @if($type=="marketing") selected @endif data-default="0">Marketing Allowance</option>
                                <option value="health" id="health" @if($type=="health") selected @endif data-default="0">Health Allowance</option>
                            </select> 
                        </div>
                        <div class="form-group">
                            <label>Given Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control" name="given_date" placeholder="Enter Month">
                            <span class="form-text text-muted">Please enter Given Date</span>
                        </div>
                        <div class="form-group">
                            <label>Amount:</label>
                            <input required type="number" class="form-control" name="amount" placeholder="Enter Amount">
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Give Allownces</button>
                        </div>
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
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script data-ajax>
  $(document).ready(function(){
    $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'});
    $("#emp_allowns [name='type']").on("change", function(){
        var _this_attr=$(this).find(':selected').attr("data-default");
        console.log(this)
        $('#emp_allowns [name="amount"]').val(_this_attr);
    });
    $("#emp_allowns [name='type']").trigger("change");
    var gb_rider=$("#gb_rider_id");
    if (gb_rider.length) {
        $('#emp_allowns [name="employee_id"]').val(gb_rider.val());
    }
  });
</script>
@endsection