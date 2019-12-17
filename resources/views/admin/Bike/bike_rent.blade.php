@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Add Bike Rent
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('admin.post_bike_rent') }}" id="bike_rent" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Bike Rent Month:</label>
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
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label>Rider:</label>
                            <select class="form-control bk-select2 kt-select2-general" name="rider_id" >
                                <option value="">Select a Rider<option>
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Bike:</label>
                            <select  class="form-control bk-select2" name="bike_id" >
                                    <option value="">Select a Bike<option>
                                @foreach ($bikes as $bike)
                                <option value="{{ $bike->id }}">
                                    {{ $bike->brand }} - {{$bike->bike_number}}
                                </option>     
                                @endforeach 
                            </select> 
                        </div>
                        </div>
                        <div class="form-group">
                            <label>Owner:</label>
                            <select  class="form-control bk-select2" name="owner" >
                                <option value="kr_bike">Kr-Bike</option>
                                <option value="rent">Rental Bike</option>
                                <option value="self">Rider Own Bike</option>
                            </select> 
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
                        {{-- <div class="form-group" id="bike_allowns_field">
                            <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;" >
                                <input type="text" disabled class="form-control col-md-6" value="Absent Days">
                                <input type="text" readonly class="form-control col-md-6" name="absent_days" placeholder="Enter Absent Days">
                            </div>
                            <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                                <input type="text" disabled class="form-control col-md-6" value="Working Days With Weekly Off">
                                <input type="text"readonly class="form-control col-md-6" name="working_days" placeholder="Enter Working Days With Weekly Off">
                            </div>
                            <div class="form-group row" style="margin-right:0px !important;margin-left:0px !important;">
                                <input type="text" disabled class="form-control col-md-6" value="Total Month Days">
                                <input type="text" readonly class="form-control col-md-6" name="month_days" placeholder="Enter Month Days">
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label>Amount:</label>
                            <input required type="number" step="0.01" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
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
    //   $('#datepicker').datepicker({dateFormat: 'yy-mm-dd'}); 
    $("#bike_allowns_field").hide();
      $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 

      $('#bike_rent [name="month"],#bike_rent [name="bike_id"]').on('change', function(){
        var _month = $('#bike_rent [name="month"]').val();
        
        if(_month=='')return;
        _month = new Date(_month).format('yyyy-mm-dd');

        var _bike_id = $('#bike_rent [name="bike_id"]').val();
        if(typeof _bike_id !== "undefined" && _bike_id!=""){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/salik/ajax/get_active_bikes/')}}"+'/'+_bike_id+"/"+_month+"/bike",
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                if(data.bike_histories!==null){
                    split_objects(data.bike_histories, _month, 'rider');
                }
                
            });
        }
    });

    var split_objects=function(histories, _month, according_to){
        $('.split__object-container').remove();
        
        $('#bike_rent [name="bike_id"]').parents('.form-group').after('<div class="split__object-container"></div>');
        Object.keys(histories).forEach(function(x ,i){
            var obj = histories[x];
            var days_in_month=moment(_month, "YYYY-MM-DD").daysInMonth();
            var start  = moment(_month, "YYYY-MM-DD").startOf('month');
            var end    = moment(_month, "YYYY-MM-DD").endOf('month');
            var assign_date = moment(obj.bike_assign_date, "YYYY-MM-DD").min(start).max(end);
            var unassign_date = moment(obj.bike_unassign_date, "YYYY-MM-DD").min(start).max(end);

            if(obj.status=="active"){ // unassign_date will be last of the month
                unassign_date = end;
            }
            
            var work_days = unassign_date.diff(assign_date, 'days');
            console.log('assign_date', assign_date.format("YYYY-MM-DD"), 'unassign_date', unassign_date.format("YYYY-MM-DD"));
            
            console.warn(work_days);
            var append_bike='';
            if(according_to=="bike"){
                append_bike='<div class="split--calculated__div" >'+
'                                <div class="form-group">'+
'                                    <input type="hidden" name="data['+i+'][bike_id]" value="'+obj.bike.id+'">'+
'                                    <input readonly type="text" class="form-control" value="'+obj.bike.brand+'-'+obj.bike.bike_number+'">'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input readonly type="text" class="form-control" value="'+(work_days)+'" name="data['+i+'][work_days_count]">'+
'                                     <span class="form-text text-muted">'+assign_date.format("DD/MM/YYYY")+' - '+unassign_date.format("DD/MM/YYYY")+'</span>'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input readonly type="text" class="form-control" value="'+days_in_month+'" name="data['+i+'][total_days]">'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input type="text" class="form-control" value="" name="data['+i+'][amount_given_by_days]">'+
'                                </div>'+
'                            </div>'; 
            }
            else{
                append_bike='<div class="split--calculated__div" >'+
'                                <div class="form-group">'+
'                                   <input type="hidden" name="data['+i+'][rider_id]" value="'+obj.rider.id+'">'+
'                                    <input readonly type="text" class="form-control" value="'+obj.rider.name+'" >'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input type="text" class="form-control" value="'+(work_days)+'" name="data['+i+'][work_days_count]">'+
'                                     <span class="form-text text-muted">'+assign_date.format("DD/MM/YYYY")+' - '+unassign_date.format("DD/MM/YYYY")+'</span>'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input readonly type="text" class="form-control" value="'+days_in_month+'" name="data['+i+'][total_days]">'+
'                                </div>'+
'                                <div class="form-group">'+
'                                    <input type="text" class="form-control" value="" name="data['+i+'][amount_given_by_days]">'+
'                                </div>'+
'                            </div>';   
            }

            
            $('.split__object-container').append(append_bike);
        });

        if(according_to=="bike"){
            $('#bike_rent [name="owner"]').val(histories[Object.keys(histories)[0]].bike.owner).trigger('change.select2');
        }
    }
    $("#bike_rent [name='amount']").on("change input",function(){
        var amount=$(this).val();
        $('#bike_rent .split--calculated__div').each(function(i, item){
            var total_days = parseFloat($(this).find('[name="data['+i+'][total_days]"]').val())||0;
            var work_days = parseFloat($(this).find('[name="data['+i+'][work_days_count]"]').val())||0;
            var days=work_days/total_days;
            var amount_to_give=amount*days;
            $(this).find('[name="data['+i+'][amount_given_by_days]"]').val(amount_to_give.toFixed(2));

        });
    });
    $('#bike_rent [name="rider_id"]').on('change', function(){
        var _month = $('#bike_rent [name="month"]').val();
        
        if(_month=='')return;
        _month = new Date(_month).format('yyyy-mm-dd');
        var rider_id=$(this).val();
        if(rider_id=="") return;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:"{{url('admin/salik/ajax/get_active_bikes/')}}"+'/'+rider_id+"/"+_month+"/rider",
            method: "GET"
        })
        .done(function(data) {  
            console.log(data);
            if(data.bike_histories!==null){
                split_objects(data.bike_histories, _month, "bike");
            }
        });
    });
    //set default rider
    var _gb_rider_id = $('#gb_rider_id').val();
    if(typeof _gb_rider_id !== "undefined"){
        $('#bike_rent [name="rider_id"]').val(_gb_rider_id).trigger('change');
    }
  });
  

</script>
@endsection