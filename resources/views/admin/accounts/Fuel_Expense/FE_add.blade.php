@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Fuel Expence
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" id="fuel_expense" action="{{ route('admin.fuel_expense_insert') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Type:</label>
                            <select required class="form-control @if($errors->has('model')) invalid-field @endif bk-select2" name="type">
                                <option value="vip_tag">VIP-Tag</option>
                                <option value="cash">Cash</option>
                            </select> 
                            @if ($errors->has('type'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('type')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Taken Fuel Month:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
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
                        <div class="form-group rider_id">
                            <label>Rider:</label>
                            <select class="form-control bk-select2 kt-select2-general" name="rider_id" >
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="row rider_that_assigned_in_month">
                            
                        </div>
                        <div class="form-group bike_id">
                            <label>Bike:</label>
                            <select required class="form-control bk-select2" name="bike_id" >
                                @foreach ($bikes as $bike)
                                <option value="{{ $bike->id }}">
                                    {{ $bike->brand }}-{{$bike->bike_number}}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="row bike_that_assigned_in_month">
                            
                        </div>
                        <div class="form-group">
                            <label>Given Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Given Date" value="">
                            @if ($errors->has('given_date'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('given_date') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter Given Date</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Amount:</label>
                            <input required step="0.01" type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
                            @if ($errors->has('amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('amount')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <div class="rider_sims"></div>
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
      $(".bike_that_assigned_in_month").hide();
      $(".rider_that_assigned_in_month").hide();
    $('#fuel_expense [name="bike_id"], #fuel_expense [name="month"]').on('change', function(){
        $('.rider_that_assigned_in_month').html("");
        $('.bike_that_assigned_in_month').html("");
        var _month = $('#fuel_expense [name="month"]').val();
        
        if(_month=='')return;
        _month = new Date(_month).format('yyyy-mm-dd');

        var bike_id = $('#fuel_expense [name="bike_id"]').val();
        if(typeof bike_id !== "undefined"){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/salik/ajax/get_active_bikes/')}}"+'/'+bike_id+"/"+_month+"/bike",
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                Object.keys(data.bike_histories).forEach(function(x ,i){
                var obj = data.bike_histories[x];
                var year=new Date(_month).format('yyyy');
                var month=new Date(_month).format('mm');
                var total_days_in_month=new Date(year , month , 00).getDate();
                var date_for_active=obj.bike_assign_date;
                var date_for_inactive=obj.bike_unassign_date;
                var test_month=new Date(_month).format('yyyy-mm')
                var test_date_assign=new Date(date_for_active).format('yyyy-mm');
                var test_date_unassign=new Date(date_for_inactive).format('yyyy-mm');
                if (test_date_assign!==test_month) {
                    date_for_active=new Date(test_month).format("yyyy-mm-01");
                }
                if (test_date_unassign!==test_month) {
                    var month_inactive=new Date(date_for_inactive).format("mm");
                    var year_inactive=new Date(date_for_inactive).format("yyyy");
                    date_inactive_total=new Date(year_inactive , month_inactive , 00).getDate();
                    var final_date_handle=new Date(year_inactive , month_inactive , date_inactive_total).format("yyyy-mm-dd");
                    date_for_inactive=new Date(final_date_handle).format("yyyy-mm-dd");
                }
                var date1 = new Date(date_for_active);
                if (date_for_active==date_for_inactive) {
                    var month_inactive=new Date(date_for_inactive).format("mm");
                    var year_inactive=new Date(date_for_inactive).format("yyyy");
                    date_inactive_total=new Date(year_inactive , month_inactive , 00).getDate();
                    var final_date_handle=new Date(year_inactive , month_inactive , date_inactive_total).format("yyyy-mm-dd");
                    date_for_inactive=final_date_handle;
                }
                var date2 = new Date(date_for_inactive);
                var work_days = date2.getDate() - date1.getDate();
                console.log(date2.getDate(), ' ', date1.getDate());
                
                // $('.rider_id').hide();
                $(".bike_that_assigned_in_month").hide();
                $(".rider_that_assigned_in_month").show();
                var append_bike='<div class="calculated__div" >'+
'                        <input type="hidden" name="data['+i+'][rider_id]" value="'+obj.rider.id+'">'+
'                                <div class="form-group">'+
'                                    <input readonly type="text" class="form-control" value="'+obj.rider.name+'" >'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input readonly type="text" class="form-control" value="'+(work_days+1)+'" name="data['+i+'][work_days_count]">'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input readonly type="text" class="form-control" value="'+total_days_in_month+'" name="data['+i+'][total_days]">'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input type="text" class="form-control" value="" name="data['+i+'][amount_given_by_days]">'+
'                                </div>'+
'                            </div>';      
                $('.rider_that_assigned_in_month').append(append_bike);

            });
                // if(data.bike_histories!==null){
                //     $('#fuel_expense [name="rider_id"]').val(data.bike_histories.rider_id).trigger('change.select2');
                // }
                // else{
                //     $('#fuel_expense [name="rider_id"]')[0].selectedIndex = -1;
                //     $('#fuel_expense [name="rider_id"]').trigger('change.select2');
                //     $('#fuel_expense [name="amount"]').val('');
                // }
            });
        }
    });

    $('#fuel_expense [name="rider_id"]').on('change', function(){
        $('.bike_that_assigned_in_month').html("");
        $('.rider_that_assigned_in_month').html("");
        var _month = $('#fuel_expense [name="month"]').val();
        
        if(_month=='')return;
        _month = new Date(_month).format('yyyy-mm-dd');

        var ride_id = $('#fuel_expense [name="rider_id"]').val();
        if(typeof ride_id !== "undefined"){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/salik/ajax/get_active_bikes/')}}"+'/'+ride_id+"/"+_month+"/rider",
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                Object.keys(data.bike_histories).forEach(function(x, i){
                var obj = data.bike_histories[x];
                var year=new Date(_month).format('yyyy');
                var month=new Date(_month).format('mm');
                var total_days_in_month=new Date(year , month , 00).getDate();
                var date_for_active=obj.bike_assign_date;
                var date_for_inactive=obj.bike_unassign_date;
                var test_month=new Date(_month).format('yyyy-mm')
                var test_date_assign=new Date(date_for_active).format('yyyy-mm');
                var test_date_unassign=new Date(date_for_inactive).format('yyyy-mm');
                if (test_date_assign!==test_month) {
                    date_for_active=new Date(test_month).format("yyyy-mm-01");
                }
                if (test_date_unassign!==test_month) {
                    var month_inactive=new Date(date_for_inactive).format("mm");
                    var year_inactive=new Date(date_for_inactive).format("yyyy");
                    date_inactive_total=new Date(year_inactive , month_inactive , 00).getDate();
                    var final_date_handle=new Date(year_inactive , month_inactive , date_inactive_total).format("yyyy-mm-dd");
                    date_for_inactive=new Date(final_date_handle).format("yyyy-mm-dd");
                }
                var date1 = new Date(date_for_active);
                if (date_for_active==date_for_inactive) {
                    var month_inactive=new Date(date_for_inactive).format("mm");
                    var year_inactive=new Date(date_for_inactive).format("yyyy");
                    date_inactive_total=new Date(year_inactive , month_inactive , 00).getDate();
                    var final_date_handle=new Date(year_inactive , month_inactive , date_inactive_total).format("yyyy-mm-dd");
                    date_for_inactive=final_date_handle;
                }
                var date2 = new Date(date_for_inactive);
                var work_days = date2.getDate() - date1.getDate();
                // $('.bike_id').hide();
                $(".bike_that_assigned_in_month").show();
                $(".rider_that_assigned_in_month").hide();
                var append_bike='<div class="calculated__div">'+
'                                <div class="form-group">'+
'                                   <input type="hidden" name="data['+i+'][bike_id]" value="'+obj.bike.id+'">'+
'                                    <input readonly type="text" class="form-control" value="'+obj.bike.brand+'-'+obj.bike.bike_number+'">'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input readonly type="text" class="form-control" value="'+(work_days+1)+'" name="data['+i+'][work_days_count]">'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input readonly type="text" class="form-control" value="'+total_days_in_month+'" name="data['+i+'][total_days]">'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input type="text" class="form-control" value="" name="data['+i+'][amount_given_by_days]">'+
'                                </div>'+
'                            </div>';      
                $('.bike_that_assigned_in_month').append(append_bike);

                });
                // if(data.bike_histories!==null){
                //     $('#fuel_expense [name="bike_id"]').val(data.bike_histories.bike_id).trigger('change.select2');
                // }
                // else{
                //     $('#fuel_expense [name="bike_id"]')[0].selectedIndex = -1;
                //     $('#fuel_expense [name="bike_id"]').trigger('change.select2');
                //     $('#fuel_expense [name="amount"]').val('');
                // }
            });
        }
    });

    var _gb_rider_id = $('#gb_rider_id').val();
    if(typeof _gb_rider_id !== "undefined"){
        $('#fuel_expense [name="rider_id"]').val(_gb_rider_id).trigger('change');
    }
    $("#fuel_expense [name='amount']").on("change input",function(){
        var amount=$(this).val();
        $('#fuel_expense .calculated__div').each(function(i, item){
            var total_days = parseFloat($(this).find('[name="data['+i+'][total_days]"]').val())||0;
            var work_days = parseFloat($(this).find('[name="data['+i+'][work_days_count]"]').val())||0;
            var days=work_days/total_days;
            var amount_to_give=amount*days;
            $(this).find('[name="data['+i+'][amount_given_by_days]"]').val(amount_to_give);

        });
    });
  });


</script>
@endsection