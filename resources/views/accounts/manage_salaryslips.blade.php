@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .dataTables_length{
           display: block;   
        }
        .total_entries{
        display: inline-block;
        margin-left: 10px;
        }
        .dataTables_info{ 
            display:none;
        }
        </style>
    <!--end::Page Vendors Styles -->
@endsection
@section('main-content')
<!-- begin:: Content -->
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Salary slips
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body" id="rider_salaryslips">
            <div class="row">
                <div class="col-md-3">
                    <label for="month">Month</label>
                    <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" readonly class="month_picker_only form-control" name="month" placeholder="Select Month" >
                </div>
                <div class="col-md-3">
                    <label for="expiry_date">Expiry date</label>
                    <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" readonly class="month_picker form-control" name="expiry_date" placeholder="Select Expiry" >
                </div>
            </div>

            
            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed">
                    <thead>
                        <tr>
                            {{-- <th>
                                <input type="checkbox" id="select_all" >
                            </th> --}}
                            <th>Rider</th>
                            <th>Active Month</th>
                            <th>Expire on</th>
                            <th>
                                <label class="kt-checkbox">
                                    <input name="show_slip" type="checkbox" data-target="show_slip" onchange="toggleAllCheckbox(this);update_data(this,false,0);"> All
                                    <span></span>
                                </label>
                            </th>
                            <th>
                                <label class="kt-checkbox">
                                    <input name="show_atsh" type="checkbox" data-target="show_atsh" onchange="toggleAllCheckbox(this);update_data(this,false,0);"> All
                                    <span></span>
                                </label>
                            </th>                     
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riders as $rider) 
                        @php
                            $show_dates=$rider->Rider_detail->show_salaryslip==1||$rider->Rider_detail->show_attendanceslip==1;
                            $onlyMonth = Carbon\Carbon::parse($rider->Rider_detail->salaryslip_month)->format('m');
                            $onlyYear = Carbon\Carbon::parse($rider->Rider_detail->salaryslip_month)->format('Y');
                            $salary_generated = App\Model\Accounts\Rider_salary::where('rider_id',$rider->id)
                            ->whereMonth("month",$onlyMonth)
                            ->whereYear("month",$onlyYear)
                            ->get()
                            ->first();
                            $show__row=false;
                        @endphp
                       @isset($salary_generated)
                       @php
                           $show__row=true;
                       @endphp
                       @endisset
                        <tr data-riderid="{{$rider->id}}" @if($show__row==false)style="display:none" @endif>
                                <td>KRD{{$rider->id}} - {{$rider->name}}</td>
                                <td data-name="active_month">@if($show_dates){{Carbon\Carbon::parse($rider->Rider_detail->salaryslip_month)->format('F Y')}}@endif</td>
                                <td data-name="expiry">@if($show_dates){{Carbon\Carbon::parse($rider->Rider_detail->salaryslip_expiry)->format('M d, Y')}}@endif</td>
                                <td>
                                    <label class="kt-checkbox">
                                        <input name="show_slip" type="checkbox" onchange="update_data(this,false,{{$rider->id}})" @if($rider->Rider_detail->show_salaryslip==1)checked @endif> Show Salary
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="kt-checkbox">
                                        <input name="show_atsh" type="checkbox" onchange="update_data(this,false,{{$rider->id}})" @if($rider->Rider_detail->show_attendanceslip==1)checked @endif> Show Attendance
                                        <span></span>
                                    </label>
                                </td>
                            </tr>
                        
                        @endforeach
                    </tbody>
                </table>
    
                <!--end: Datatable -->
        </div>
    </div>
</div>

<!-- end:: Content -->
@endsection
@section('foot')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>

<!--end::Page Scripts -->
<script>
$(function() {
    $('#rider_salaryslips [name="month"]').on('changeDate', function(){
        console.log($(this).val());
        update_data(this,true);
    });
    $('#rider_salaryslips [name="expiry_date"]').on('changeDate', function(){
        console.log($(this).val());
        update_data(this,true);
    });
    $('#rider_salaryslips table thead [name="show_slip"]').prop('checked', $('#rider_salaryslips table tbody [name="show_slip"]:checked').length === $('#rider_salaryslips table tbody [name="show_slip"]').length);
    $('#rider_salaryslips table thead [name="show_atsh"]').prop('checked', $('#rider_salaryslips table tbody [name="show_atsh"]:checked').length === $('#rider_salaryslips table tbody [name="show_atsh"]').length);
    var _firstCheckedMonth = $('#rider_salaryslips table tbody [name="show_slip"]:checked');
    if(_firstCheckedMonth.length){
        var _monnth = _firstCheckedMonth.eq(0).parents('tr').find('[data-name="active_month"]').text();
        var _expiry = _firstCheckedMonth.eq(0).parents('tr').find('[data-name="expiry"]').text();
        $('#rider_salaryslips [name="month"]').attr('data-month', new Date(_monnth).format('mmmm yyyy'));
        $('#rider_salaryslips [name="expiry_date"]').attr('data-month', new Date(_expiry).format('mmm dd, yyyy'));
        biketrack.refresh_global();
    }
    
});
function update_data(_this,is_month=false,rider_id=null){
    if(rider_id==null && !is_month) return;
    var is_checked=false;
    var type=null;
    if(!is_month){
        is_checked=$(_this).prop('checked');
        type=$(_this).attr('name');
    }
    var month = new Date($('#rider_salaryslips [name="month"]').val()).format('yyyy-mm-dd');
    var expiry_date = new Date($('#rider_salaryslips [name="expiry_date"]').val()).format('yyyy-mm-dd');
    var url = '{{route('admin.accounts.update_salaryslips')}}';
    $.ajax({
        url :url,
        data:{
            type:type,
            rider_id:rider_id,
            is_checked:is_checked,
            month:month,
            expiry_date:expiry_date
        }, 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type : 'PUT',
        beforeSend: function() {            
            $('.loading').show();
        },
        complete: function(){
            $('.loading').hide();
        },
        success: function(data){
            if(typeof data.rider_detail !=="undefined"){
                data.rider_detail.forEach(function(item,i){
                    let _month = new Date(item.salaryslip_month).format('mmmm yyyy');
                    let _expiry = new Date(item.salaryslip_expiry).format('mmm dd, yyyy');
                    let show_salaryslip=item.show_salaryslip=='1'?true:false;
                    let show_attendanceslip=item.show_attendanceslip=='1'?true:false;
                    if(!show_salaryslip && !show_attendanceslip){
                        _month='';
                        _expiry='';
                    }
                    $('#rider_salaryslips tr[data-riderid="'+item.rider_id+'"]').find('[data-name="active_month"]').text(_month);
                    $('#rider_salaryslips tr[data-riderid="'+item.rider_id+'"]').find('[data-name="expiry"]').text(_expiry);
                    $('#rider_salaryslips tr[data-riderid="'+item.rider_id+'"]').find('[name="show_slip"]').prop('checked', show_salaryslip);
                    $('#rider_salaryslips tr[data-riderid="'+item.rider_id+'"]').find('[name="show_atsh"]').prop('checked', show_attendanceslip);
                });
            }
            else{
                data.data.forEach(function(item,i){
                    var rider_detail = item.rider_detail;
                    let _month = new Date(rider_detail.salaryslip_month).format('mmmm yyyy');
                    let _expiry = new Date(rider_detail.salaryslip_expiry).format('mmm dd, yyyy');
                    let show_salaryslip=rider_detail.show_salaryslip=='1'?true:false;
                    let show_attendanceslip=rider_detail.show_attendanceslip=='1'?true:false;
                    if(!show_salaryslip && !show_attendanceslip){
                        _month='';
                        _expiry='';
                    }
                    var _row = $('#rider_salaryslips tr[data-riderid="'+rider_detail.rider_id+'"]');
                    _row.show();
                    if(!item.salary_generated){
                        _row.hide();
                    }
                    _row.find('[data-name="active_month"]').text(_month);
                    _row.find('[data-name="expiry"]').text(_expiry);
                    _row.find('[name="show_slip"]').prop('checked', show_salaryslip);
                    _row.find('[name="show_atsh"]').prop('checked', show_attendanceslip);
                });
            }
            $('#rider_salaryslips table thead [name="show_slip"]').prop('checked', $('#rider_salaryslips table tbody [name="show_slip"]:checked').length === $('#rider_salaryslips table tbody [name="show_slip"]').length);
            $('#rider_salaryslips table thead [name="show_atsh"]').prop('checked', $('#rider_salaryslips table tbody [name="show_atsh"]:checked').length === $('#rider_salaryslips table tbody [name="show_atsh"]').length);
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
}
function toggleAllCheckbox(_this) {
    var _which = $(_this).attr('data-target');
    $('#rider_salaryslips table tbody [name="'+_which+'"]').prop('checked', $(_this).prop('checked'));
}


</script>
@endsection