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
        /* #client_income .table-row_cell-sr{
            display: inline-flex;
            width: 100%;
            text-align: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            padding-top: 17px;
            position: relative;
        } */
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
                            Commission Based Payout
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" action="{{route('admin.client_comission_income_store')}}" method="POST" enctype="multipart/form-data" id="client_income">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="form-group col-md-4">
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

                            <div class="form-group col-md-4">
                                <label>Customer:</label>
                                <select class="form-control bk-select2 kt-select2" id="kt_select2_3" data-non-readonly data-name="client_id" name="client_id" required>
                                    <option value="">Select Client</option>
                                    @foreach ($clients as $client)
                                        @if ($client->setting!=null)
                                            @php
                                                $client_setting = json_decode($client->setting, true);
                                                $pm = $client_setting['payout_method'];
                                            @endphp
                                            @if ($pm=='commission_based')
                                                <option value="{{ $client->id }}">
                                                    {{ $client->name }}
                                                </option>
                                            @endif
                                        @endif
                                    @endforeach 
                                </select> 
                            </div>
                            <div class="form-group col-md-4" id="rider_hidden">
                                <label>Rider:</label> 
                                <select class="form-control bk-select2 kt-select2" id="rider_id_html"  data-non-readonly data-name="rider_id" name="rider_id" required>
                                    <option value="">Select Rider</option> 
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
                                    <th class="text-center table__cell--bk_width-35">Week</th>
                                    <th class="table__cell--bk_width-20" colspan="2">
                                        <span class="d-block text-center">Trips Amount</span>
                                        <div class="row">
                                            <span class="col-md-6 text-center">Cash</span>
                                            <span class="col-md-6 text-center">Bank<sup class="text-danger h6">*</sup></span>
                                        </div>
                                    </th>
                                    {{-- <th class="text-right table__cell--bk_width-10"></th> --}}
                                    <th class="text-right table__cell--bk_width-10">Caption Tips</th>
                                    <th class="text-right table__cell--bk_width-15">Item Bought</th>
                                    <th class="text-right table__cell--bk_width-15">Total Payout</th>                 
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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
    $("#rider_hidden").hide();
    $('#client_income [name="client_id"]').on('change', function(){
        var _client_id = $('#client_income [name="client_id"]').val();
        var _month = $('#client_income [name="month"]').val();
        $("#rider_hidden").show();
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
            $("#rider_id_html").html("");
            var _selectRider="";
            Object.keys(resp.data).forEach(function(key, index){
                var client_rider = resp.data[key];
                var rider = client_rider.rider;
                if (rider!=null) {
                    _selectRider+='<option value="'+rider.id+'">'+rider.name+'</option>';
                } 
            });
                $("#rider_id_html").html(_selectRider);
            });
        });
        $('#client_income [name="rider_id"]').on('change', function(){
        var _rider_id = $(this).val();
        var _month = $('#client_income [name="month"]').val();
        _month=new Date(_month).format('yyyy-mm-dd');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            url:"{{url('admin/client_income/rider_joining_date/')}}"+'/'+_rider_id + "/" + _month,
            method: "GET"
        })
        .done(function(resp) {  
            console.log(resp);
            var riders_HTMLData=[];
            var day_of_joining=new Date(resp.Join_date).format("yyyy-mm-dd");
            var _monthDay=new Date(resp.month).format("yyyy-mm-dd");
            var weekday=new Date(day_of_joining).getDay();
            var month=new Date(_monthDay).getDay();
            for(var i=0;i<=6; i++){
                var matching_day=moment(_monthDay).subtract(i, 'd').day();
                if(weekday===matching_day){
                var weekday_start=moment(_monthDay).subtract(i, 'd').format("YYYY,MM,DD,dddd");
                }
            }
                
            for(var j=0;j<=6;j++){
                var week_end=moment(weekday_start, "YYYY,MM,DD,dddd").add(1 ,"W").format("YYYY,MM,DD,dddd");
                var a=moment(weekday_start, "YYYY,MM,DD,dddd").month()+1;
                var b=moment(week_end, "YYYY,MM,DD,dddd").month()+1;
                var _month=moment(_monthDay).format("MM");
                if(a==_month || b==_month){
                    // alert(weekday_start+"-------"+week_end);
                    var _ws = moment(weekday_start, "YYYY,MM,DD,dddd").format("DD-MM-YYYY");
                    var _we = moment(week_end, "YYYY,MM,DD,dddd").format("DD-MM-YYYY");
                    var _ci = resp.client__incomes.find(function(x){
                        return moment(x.week_start, "DD-MM-YYYY").isSame(moment(_ws, "YYYY-MM-DD")) 
                        && 
                        moment(x.week_end, "DD-MM-YYYY").isSame(moment(_we, "YYYY-MM-DD"))
                    })
                    riders_HTMLData.push(
                    {
                        weekday_start:_ws,
                        week_end:_we,
                        ci:_ci
                    });
                }
                weekday_start=week_end;
            }
            append_row(riders_HTMLData);
            });
        });

    });

function append_row($row_data = null) {
    var markup = '';
    var total_rows = parseFloat($("#client_income_table tbody tr").length);
    console.log($row_data);
    if ($row_data != null) {
        $row_data.forEach(function (item, i) {
            var _ci = item.ci;
            var cash_amount = 0;
            var cash_trips = 0;
            var bank_amount = 0;
            var bank_trips = 0;
            var captain_tips = 0;
            var item_bought = 0;
            var item_qty = 0;
            var total_payout = 0;
            if(_ci!=null){
                cash_amount = parseFloat(_ci.cash)||0;
                cash_trips = parseFloat(_ci.cash_trips)||0;
                bank_amount = parseFloat(_ci.bank)||0;
                bank_trips = parseFloat(_ci.bank_trips)||0;
                captain_tips = parseFloat(_ci.captain_tips)||0;
                item_bought = parseFloat(_ci.item_bought)||0;
                item_qty = parseFloat(_ci.item_qty)||0;
                total_payout = parseFloat(_ci.total_payout)||0;
            }

            markup += '' +
                '   <tr>  ' +
                '           <input type="hidden" data-name-start="datarange_start" name="incomes['+i+'][week_start]" value="'+item.weekday_start+'">'+
                '           <input type="hidden" data-name-end="datarange_end" name="incomes['+i+'][week_end]" value="'+item.week_end+'">'+
                '       <td class="invoice__table-row_cell-sr"><span class="flaticon2-trash invoice__remove" onclick="delete_row(this);"></span>' + (i + 1)+
                '       </td>'+
                '       <td> Ranges:     <input readonly class="form-control" autocomplete="off" type="text" name="daterange" value="'+item.weekday_start+' - '+item.week_end+'" /></td>  ' +
                '       <td> ' +
                '           <div class="">   ' +
                '               <input type="text" class="form-control" value="'+cash_amount+'" placeholder="Cash" data-name="cash" oninput="subtotal()" name="incomes['+i+'][cash]">' +
                '               <div class="">  ' +
                '                   <p style="margin-top: 13px !important;"><input placeholder="Trips" value="'+cash_trips+'" class="form-control" data-name="cash_trips" name="incomes['+i+'][cash_trips]"></p>' +
                '               </div>   ' +
                '           </div>  ' +
                '       </td>  ' +
                
                '       <td> ' +
                '           <div class="">   ' +
                '               <input type="text" class="form-control" placeholder="Bank" value="'+bank_amount+'" data-name="bank" oninput="subtotal()" name="incomes['+i+'][bank]">   ' +
                '               <div class="">  ' +
                '                   <p style="margin-top: 13px !important;"><input placeholder="Trips" value="'+bank_trips+'" class="form-control" data-name="bank_trips" name="incomes['+i+'][bank_trips]"></p>' +
                '               </div>   ' +
                '           </div>  ' +
                '       </td>  ' +
                '       <td> <input class="form-control" placeholder="Captain Tips" data-name="captain_tips" value="'+captain_tips+'" oninput="subtotal()" name="incomes['+i+'][captain_tips]"> </td>  ' +
                '       <td> ' +
                '           <div class="">   ' +
                '               <input type="text" class="form-control" placeholder="Item Bought" value="'+item_bought+'" data-name="item_bought" oninput="subtotal()" name="incomes['+i+'][item_bought]">   ' +
                '               <div class="">  ' +
                '                   <p style="display:inline-flex;margin-top:13px;"><strong style="margin-top:10px;">QTY: </strong><input placeholder="QTY" value="'+item_qty+'" class="form-control" data-name="item_qty" name="incomes['+i+'][item_qty]"></p>' +
                '               </div>   ' +
                '           </div>  ' +
                '       </td>  ' +
                '       <td> <input data-input-type="float" readonly value="'+total_payout+'" class="form-control" placeholder="Payout" data-name="total_payout" name="incomes['+i+'][total_payout]"> </td>  ' +
                '  </tr>  ';
        });
        $("#client_income_table tbody").html(markup);
        $('#client_income input[name="daterange"]').daterangepicker({
            opens: 'right', 
            autoUpdateInput:true,
            locale: {
                format: 'DD-MM-YYYY '
            }
        }, function(start, end, label) {
            $date_data1=$(".datapick1").val();
            updateRange();
            // var _data = {
            //     range1: {
            //         start_date:$('#datapick1').data('daterangepicker').startDate.format('YYYY-MM-DD'),
            //         end_date: $('#datapick1').data('daterangepicker').endDate.format('YYYY-MM-DD')
            //     },
            // };
        });
        
        subtotal();
        return;
    }
}

function delete_row(ctl) {
    $(ctl).parents("tr").remove();
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

var subtotal = function(){
    var subtotal_amount=0;
    $('#client_income_table tbody tr').each(function(i,elem){
        var _this = $(this);
        var cash = parseFloat(_this.find('[data-name="cash"]').val())||0;
        var bank = parseFloat(_this.find('[data-name="bank"]').val())||0;
        var caption_tips = parseFloat(_this.find('[data-name="captain_tips"]').val())||0;
        var item_bought = parseFloat(_this.find('[data-name="item_bought"]').val())||0;

        var total_payout=cash+bank+caption_tips+item_bought;
        _this.find('[data-name="total_payout"]').val(total_payout.toFixed(2));
        
        subtotal_amount+=total_payout;
    });
    $('#client_income .subtotal_value').text('AED '+Math.round(subtotal_amount))
    $('#client_income [data-name="income_subtotal"]').val(Math.round(subtotal_amount));
}
function updateRange(){
    $('#client_income input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
        var current_start_date=picker.startDate.format('MM/DD/YYYY');
        var current_end_date=picker.endDate.format('MM/DD/YYYY');
        var index=$(this).parents("tr").index();
// console.log('========');
        var prev_startDate=moment(current_start_date, 'MM/DD/YYYY');
        var prev_endDate=moment(current_end_date, 'MM/DD/YYYY');

        $('#client_income_table tbody tr').each(function(i,x){
        var _current_index=$(this).index();
        console.log(index,'========',_current_index);
        if(_current_index>index){
            var new_start_date=prev_endDate.format('DD-MM-YYYY');
            var new_end_day=moment(prev_endDate,"DD-MM-YYYY").add(7, 'd').format("DD-MM-YYYY");
            
            var __month = moment($('#client_income [name="month"]').val(), 'MMMM YYYY').month()+1;
            console.log(new_start_date, '===', new_end_day, '==========',__month );

            $(this).find('[name="daterange"]').data('daterangepicker').setStartDate(new_start_date);
            $(this).find('[name="daterange"]').data('daterangepicker').setEndDate(new_end_day);

            prev_startDate=moment(new_start_date, 'DD-MM-YYYY');
            prev_endDate=moment(new_end_day, 'DD-MM-YYYY');

            if(__month!=(moment(new_start_date, 'DD-MM-YYYY').month()+1) && __month!=(moment(new_end_day, 'DD-MM-YYYY').month()+1)){
                console.log('delete', this);
                $(this).remove();
            }
            
        }
        });
    });
}

</script>
@endsection

