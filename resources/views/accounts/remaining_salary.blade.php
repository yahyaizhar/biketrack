@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Remaining Salary
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('Rider.add_update_remaining_salary') }}" method="POST" enctype="multipart/form-data" id="remaining_salary_update">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Month:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                            <span class="form-text text-muted">Please enter Month</span>
                        </div>
                        <div class="form-group">
                            <label>Rider:</label>
                            <select required class="form-control kt-select2-general" name="rider_id" >
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Given Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Given Date" value="">
                            <span class="form-text text-muted">Please enter Given Date</span>
                        </div>
                        <div class="form-group">
                            <label>Salary Paid:</label>
                            <input readonly step="0.01" required type="number" class="form-control @if($errors->has('salary_paid')) invalid-field @endif" name="salary_paid" placeholder="Enter Amount" value="">
                        </div>
                        <div class="form-group">
                            <label>Remaining Salary:</label>
                            <input required step="0.01" type="number" class="form-control @if($errors->has('reamining_salary')) invalid-field @endif" name="reamining_salary" placeholder="Enter Reamining Salary" value="">
                        </div>
                        <input step="0.01" type="hidden" name="total_remaining">
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Update Salary</button>
                        </div>
                    </div>
                </form>
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
    $("#remaining_salary_update [name='rider_id']").on("change",function(){
        var rider_id=$(this).val();
        var month=$('#remaining_salary_update [name="month"]').val();
        var _month=new Date(month).format("yyyy-mm-dd");
        var url = "{{ url('admin/accounts/company/debits/get_salary_deduction/') }}" + "/" + _month + "/" +rider_id;
        $.ajax({
                url : url,
                type : 'GET',
                success: function(data){
                    console.log(data);
                    $('#remaining_salary_update [type="submit"]').prop("disabled",true).html("Salary is not paid");
                    if (data.status==0) {
                        var salary_paid=0;
                        var remaining_salary=0;
                        $('#remaining_salary_update [type="submit"]').prop("disabled",true).html("Salary is not paid");
                    }
                    if (data.status==1) {
                        var salary_paid=data.salary_paid;
                        var remaining_salary=data.gross_salary;
                        $('#remaining_salary_update [type="submit"]').prop("disabled",false).html("Update Salary");
                    }
                    $('#remaining_salary_update [name="salary_paid"]').val(salary_paid);
                    $('#remaining_salary_update [name="reamining_salary"]').val(remaining_salary);
                    $('#remaining_salary_update [name="total_remaining"]').val(remaining_salary);
                    if (remaining_salary==0 && salary_paid!=0) {
                        $('#remaining_salary_update [type="submit"]').prop("disabled",true).html("Salary is already paid");
                    }
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to udpate.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
    });
    $("#remaining_salary_update [name='rider_id']").trigger("change");
    $("#remaining_salary_update [name='month']").on("change",function(){
        $("#remaining_salary_update [name='rider_id']").trigger("change");
    });
  });
</script>
@endsection