@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<style data-ajax>
    .custom-file-label::after{
           color: white;
           background-color: #5578eb;
        }
    .custom-file-label{
        overflow: hidden;
        }
        #client_income .table-row_cell-sr{
            display: inline-flex;
            width: 100%;
            text-align: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            padding-top: 17px;
            position: relative;
        }
        .kt-checkbox > input:disabled ~ span {
            opacity: 0.3;
        }
        [data-name="qty"]{
            padding: 5px;
            text-align: center;
        }
        #client_income .invoice__remove{
            color: #ff8181;
            position: absolute;
            left: 2px;
            cursor: pointer;
        }
        #client_income .invoice__remove:hover{
            color: #f12626;
        }   
        .balance_due--wrapper h3{
            font-size: 14px;
            margin: 0;
            font-weight: 400;
        }
        .balance_due--wrapper .balance_due{
            font-size: 30px;
            color: #08976d;
            font-weight: 500;
            letter-spacing: 1px;
        }
        .__pm__content{
            width: 170px;
            display: grid;
            grid-template-columns: auto auto;
            justify-content: normal;
        }
        .__pm__heading{
            font-weight: 500;
            margin-bottom: 5px;
            font-size: 13px;
            color: #000;
        }
        .invoice__header-generated_by-text{
            font-size: 11px;
            color: #999;
        }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title page__title">
                            Add Income
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
            <form class="kt-form" action="{{route('admin.client_income_store')}}" method="POST" enctype="multipart/form-data" id="client_income">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Month:</label>
                                <input type="text" data-non-readonly data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Select Month" value="">
                                @if ($errors->has('month'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('month') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please select Month</span>
                                @endif
                            </div>

                            <div class="form-group col-md-3">
                                <label>Customer:</label>
                                <select class="form-control bk-select2 kt-select2" id="kt_select2_3" data-non-readonly data-name="client_id" name="client_id" required>
                                    <option value="">Select Client</option>
                                    @foreach ($clients as $client)
                                        @if ($client->setting!=null)
                                            @php
                                                $client_setting = json_decode($client->setting, true);
                                                $pm = $client_setting['payout_method'];
                                            @endphp
                                            @if ($pm=='fixed_based')
                                                <option value="{{ $client->id }}">
                                                    {{ $client->name }}
                                                </option>
                                            @endif
                                        @endif
                                    @endforeach 
                                </select> 
                            </div>

                            <div class="col-md-12">
                                <div class="messages">
    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Date:</label>
                                <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control @if($errors->has('date')) invalid-field @endif" data-name="date" name="date" placeholder="Enter Date" >
                            </div>
                        </div>
                        <table class="table table-striped- table-hover table-checkable table-condensed" id="client_income_table">
                            <thead>
                                <tr>
                                    <th class="text-center table-row_cell-sr table__cell--bk_width-5">#</th>
                                    <th class="text-center table__cell--bk_width-25">Rider Name</th>
                                    <th class="text-right table__cell--bk_width-10">Per day Hours</th>
                                    <th class="text-right table__cell--bk_width-10">Working Days</th>
                                    <th class="text-right table__cell--bk_width-10">Total Hours</th>
                                    <th class="text-right table__cell--bk_width-10">Extra Hours</th>
                                    <th class="text-right table__cell--bk_width-20">Total</th>
                                    <th class="text-right table__cell--bk_width-10">Total Payout</th>                 
                                </tr>
                                    <tbody>
                                    </tbody>
                            </thead>
                        </table>
                    </div>
                    <div class="kt-portlet__foot">
                            
                        <div class="kt-form__actions kt-form__actions--right">
                            {{-- <button style="float:left;padding: 5px;" class="btn btn-primary btn--addnewrow" onclick="append_row();return false">Add Rows</button> --}}
                            <div class="row mt-3">
                                <div class="col-md-5 text-right offset-md-7">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <h4>Subtotal</h4>
                                        </div>
                                        <div class="col-md-5">
                                            <h4 class="subtotal_value">AED 0.00</h4>
                                            <input type="hidden" data-name="income_subtotal" name="income_subtotal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-warning btn-form-submit btn-save-invoice-drafted">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script data-ajax>
var invoiceObj=null;
var basic_alert= '   <div><div class="alert alert-outline-danger fade show" role="alert">  '  + 
 '                                   <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>  '  + 
 '                                       <div class="alert-text">A simple danger alert—check it out!</div>  '  + 
 '                                       <div class="alert-close">  '  + 
 '                                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">  '  + 
 '                                           <span aria-hidden="true"><i class="la la-close"></i></span>  '  + 
 '                                       </button>  '  + 
 '                                   </div>  '  + 
 '                              </div> </div>  ' ; 
$(document).ready(function () {
    $('#client_income [name="client_id"],#client_income [name="month"]').on('change', function(){
        var _client_id = $('#client_income [name="client_id"]').val();
        var _month = $('#client_income [name="month"]').val();
        _month=new Date(_month).format('yyyy-mm-dd');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:"{{url('admin/client_income/')}}"+'/'+_client_id+'/getRiders/month/'+_month,
            method: "GET"
        })
        .done(function(resp) {  
            console.log(resp);
            $("#client_income_table tbody").html('');
            var riders_HTMLData=[];
            Object.keys(resp.data).forEach(function(key, index){
                var client_rider = resp.data[key];
                var rider = client_rider.rider;
                var client = client_rider.client;
                var _clinetSettings = JSON.parse(client.setting); // already fixed based clients

                //default data
                var _pm = _clinetSettings.payout_method;
                var _workingDays = parseFloat(_clinetSettings.fb__working_days)||0;
                var _perdayHours = parseFloat(_clinetSettings.fb__perdayHours)||0;
                var _payout = parseFloat(_clinetSettings.fb__amount)||0;


                riders_HTMLData.push(
                    {
                        rider:rider,
                        client:client,
                        perday_hours:_perdayHours,
                        working_days:_workingDays,
                        total_hours:_perdayHours*_workingDays,
                        extra_hours:0,
                        total:_payout,
                    }
                );
            });
            append_row(riders_HTMLData);
            
        });
    });
});



function append_row($row_data = null) {
    var markup = '';
    var total_rows = parseFloat($("#client_income_table tbody tr").length);
    if ($row_data != null) {
        console.log($row_data);
        $row_data.forEach(function (item, i) {
            var perday__amount = parseFloat((item.total/item.total_hours).toFixed(2));
            var extra_day_payable=perday__amount*item.extra_hours;
            var total_payout = item.total + extra_day_payable;
            markup += '' +
                '   <tr>  ' +
                '       <td class="invoice__table-row_cell-sr"> <span class="flaticon2-trash invoice__remove" onclick="delete_row(this);"></span>' + (i + 1)+
                '           <input type="hidden" name="incomes['+i+'][rider_id]" value="'+item.rider.id+'">'+
                '           <input type="hidden" name="incomes['+i+'][client_id]" value="'+item.client.id+'">'+
                '       </td>'+
                '       <td> <textarea type="text" class="form-control auto-expandable" readonly data-name="description" rows="1">KR' + item.rider.id +' - '+item.rider.name+'</textarea> </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" data-name="perday_hours" oninput="subtotal()" name="incomes['+i+'][perday_hours]" min="0" value="' + item.perday_hours + '"> </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" data-name="working_days" oninput="subtotal()" name="incomes['+i+'][working_days]" min="1" value="' + item.working_days + '"> </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" readonly data-name="total_hours" oninput="subtotal()" name="incomes['+i+'][total_hours]" min="1" value="' + item.total_hours + '"> </td>  ' +
                '       <td> <input data-input-type="float" class="form-control" data-name="extra_hours" oninput="subtotal()" name="incomes['+i+'][extra_hours]" min="1" value="' + item.extra_hours + '"> </td>  ' +
                '       <td> ' +
                '           <div class="">   ' +
                '               <input type="text" class="form-control" placeholder="Total" data-name="total" name="incomes['+i+'][total]" value="'+item.total+'" oninput="subtotal()">   ' +
                '               <div class="">  ' +
                '                   <p><strong>Perday Amount: </strong><span class="perday__amount">'+perday__amount+'</span></p>' +
                '                   <p><strong>Extra Hours Payable: </strong><span class="extra_hours__payable">'+perday__amount+' x '+item.extra_hours+' = '+extra_day_payable+'</span></p>' +
                '               </div>   ' +
                '           </div>  ' +
                '       </td>  ' +
                '       <td> <input data-input-type="float" readonly class="form-control" data-name="total_payout" name="incomes['+i+'][total_payout]" min="1" value="' + total_payout + '"> </td>  ' +
                '  </tr>  ';
        });
        $("#client_income_table tbody").append(markup);
        subtotal();
        return;
    }

    // markup = '' +
    //     '   <tr>  ' +
    //     '       <td class="invoice__table-row_cell-sr"> <span class="flaticon2-trash invoice__remove" onclick="delete_row(this);"></span>' + (i + 1)+
    //     '           <input type="hidden" name="incomes['+total_rows+'][rider_id]" value="">'+
    //     '           <input type="hidden" name="incomes['+total_rows+'][client_id]" value="">'+
    //     '       </td>'+
    //     '       <td> <textarea type="text" class="form-control auto-expandable" readonly data-name="description" rows="1">KR' + item.rider.id +' - '+item.rider.name+'</textarea> </td>  ' +
    //     '       <td> <input data-input-type="float" class="form-control" data-name="perday_hours" oninput="subtotal()" name="incomes['+i+'][perday_hours]" min="0" value="' + item.perday_hours + '"> </td>  ' +
    //     '       <td> <input data-input-type="float" class="form-control" data-name="working_days" oninput="subtotal()" name="incomes['+i+'][working_days]" min="1" value="' + item.working_days + '"> </td>  ' +
    //     '       <td> <input data-input-type="float" class="form-control" data-name="total_hours" oninput="subtotal()" name="incomes['+i+'][total_hours]" min="1" value="' + item.total_hours + '"> </td>  ' +
    //     '       <td> <input data-input-type="float" class="form-control" data-name="extra_horus" oninput="subtotal()" name="incomes['+i+'][extra_hours]" min="1" value="' + item.extra_hours + '"> </td>  ' +
    //     '       <td> ' +
    //     '           <div class="">   ' +
    //     '               <input type="text" class="form-control" placeholder="Total" data-name="total" name="incomes['+i+'][total]" value="'+item.total+'" oninput="subtotal()">   ' +
    //     '               <div class="">  ' +
    //     '                   <p><strong>Perday Amount: </strong><span class="perday__amount">'+perday__amount+'</span></p>' +
    //     '                   <p><strong>Extra Hours Payable: </strong><span class="extra_hours__payable">'+perday__amount+' x '+item.extra_hours+' = '+extra_day_payable+'</span></p>' +
    //     '               </div>   ' +
    //     '           </div>  ' +
    //     '       </td>  ' +
    //     '       <td> <input data-input-type="float" readonly class="form-control" data-name="total_payout" name="incomes['+i+'][total_payout]" min="1" value="' + total_payout + '"> </td>  ' +
    //     '  </tr>  ';
    // $("#invoice-table tbody").append(markup);
}

function delete_row(ctl) {
    $(ctl).parents("tr").remove();
    subtotal();
}
var subtotal = function(){
    var subtotal_amount=0;
    $('#client_income_table tbody tr').each(function(i,elem){
        var _this = $(this);
        var perday_hours = parseFloat(_this.find('[data-name="perday_hours"]').val())||0;
        var working_days = parseFloat(_this.find('[data-name="working_days"]').val())||0;
        _this.find('[data-name="total_hours"]').val(perday_hours*working_days);


        var total_hours = parseFloat(_this.find('[data-name="total_hours"]').val())||0;
        var extra_hours = parseFloat(_this.find('[data-name="extra_hours"]').val())||0;
        var total = parseFloat(_this.find('[data-name="total"]').val())||0;

        var perday__amount = parseFloat((total/total_hours).toFixed(2));
        var extra_day_payable=perday__amount*extra_hours;
        var total_payout = total + extra_day_payable;
        _this.find('.perday__amount').text(perday__amount);
        _this.find('.extra_hours__payable').text(perday__amount=' x '+extra_hours+' = '+extra_day_payable);
        _this.find('[data-name="total_payout"]').val(total_payout.toFixed(2));
        
        subtotal_amount+=total_payout;
    });
    $('#client_income .subtotal_value').text('AED '+Math.round(subtotal_amount))
    $('#client_income [data-name="income_subtotal"]').val(Math.round(subtotal_amount));
}


function autosize() {
    var el = this;
    console.log(el)
    setTimeout(function () {
        el.style.cssText = 'height:auto; padding:0';
        // for box-sizing other than "content-box" use:
        // el.style.cssText = '-moz-box-sizing:content-box';
        el.style.cssText = 'height:' + (el.scrollHeight + 23) + 'px';
    }, 0);
}


</script>
@endsection

