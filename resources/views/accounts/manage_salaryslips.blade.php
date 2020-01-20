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
                                    <input name="show_slip" type="checkbox" onchange="toggleAllCheckbox(this);update_data(this,false,0);"> Show All
                                    <span></span>
                                </label>
                            </th>                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riders as $rider) 
                        <tr data-riderid="{{$rider->id}}">
                                <td>KRD{{$rider->id}} - {{$rider->name}}</td>
                                <td data-name="active_month">@if($rider->Rider_detail->show_salaryslip==1){{Carbon\Carbon::parse($rider->Rider_detail->salaryslip_month)->format('F Y')}}@endif</td>
                                <td data-name="expiry">@if($rider->Rider_detail->show_salaryslip==1){{Carbon\Carbon::parse($rider->Rider_detail->salaryslip_expiry)->format('M d, Y')}}@endif</td>
                                <td>
                                    <label class="kt-checkbox">
                                        <input name="show_slip" type="checkbox" onchange="update_data(this,false,{{$rider->id}})" @if($rider->Rider_detail->show_salaryslip==1)checked @endif> Show Slip
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
    if(!is_month){
        is_checked=$(_this).prop('checked');
    }
    var month = new Date($('#rider_salaryslips [name="month"]').val()).format('yyyy-mm-dd');
    var expiry_date = new Date($('#rider_salaryslips [name="expiry_date"]').val()).format('yyyy-mm-dd');
    var url = '{{url('admin/rider/update_salaryslips')}}'+"/"+rider_id+"/"+is_checked+"/"+month+"/"+expiry_date; 
    if(is_month){
        url = '{{url('admin/rider/update_salaryslips')}}'+"/"+rider_id+"/"+is_checked+"/"+month+"/"+expiry_date;
    }
    $.ajax({
        url :url,
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
            data.rider_detail.forEach(function(item,i){
                let _month = new Date(item.salaryslip_month).format('mmmm yyyy');
                let _expiry = new Date(item.salaryslip_expiry).format('mmm dd, yyyy');
                let is_checked=item.show_salaryslip=='1'?true:false;
                if(!is_checked){
                    _month='';
                    _expiry='';
                }
                $('#rider_salaryslips tr[data-riderid="'+item.rider_id+'"]').find('[data-name="active_month"]').text(_month);
                $('#rider_salaryslips tr[data-riderid="'+item.rider_id+'"]').find('[data-name="expiry"]').text(_expiry);
                $('#rider_salaryslips tr[data-riderid="'+item.rider_id+'"]').find('[name="show_slip"]').prop('checked', is_checked);
            });
            $('#rider_salaryslips table thead [name="show_slip"]').prop('checked', $('#rider_salaryslips table tbody [name="show_slip"]:checked').length === $('#rider_salaryslips table tbody [name="show_slip"]').length);
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
    $('#rider_salaryslips table tbody [name="show_slip"]').prop('checked', $(_this).prop('checked'));
}


</script>
@endsection