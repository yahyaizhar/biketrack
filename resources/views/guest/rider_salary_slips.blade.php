@extends('guest.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-7 m-auto">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            <span id="rider_name"> Riders</span> Salary Slip
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 m-auto">
            <div class="kt-portlet">
                <form class="kt-form" id="riders_slip_attendence" action="" method="GET" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="form-group col-md-9">
                                <label>Emirate's ID:</label>
                                <input type="text" required autocomplete="off" class="form-control" name="emirate_id" placeholder="Enter Emirate's ID">
                            </div>
                            {{-- <div class="form-group">
                                <label>Passport No':</label>
                                <input type="text" class="form-control" name="amount" placeholder="Enter Passport No'">
                            </div> --}}
                            <div class="kt-form__actions kt-form__actions--right col-md-3" style="margin-top: 1.9rem !important;">
                                <button type="submit" id="show_salary_slip" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"> <span class="error_msg"></span></div>
                        </div>
                    </div>
                      
                </form>
            </div>
        </div>
    </div>
</div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="Tabs_for_slips_attendence" style="display:none">
    <div class="kt-portlet kt-portlet--tabs">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#kt_portlet_base_demo_2_1_tab_content" role="tab" aria-selected="false">
                            <i class="la la-money" aria-hidden="true"></i>Salary Slips
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="attendence_sheet" data-toggle="tab" href="#kt_portlet_base_demo_2_2_tab_content" role="tab" aria-selected="false">
                            <i class=" flaticon2-line-chart" aria-hidden="true"></i>Attendence Sheet
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="tab-content">
                <div class="tab-pane active" id="kt_portlet_base_demo_2_1_tab_content" role="tabpanel">
                    <div class="salaryslip_err"></div>
                    <div style="display:grid;padding: 15px 50px 0px 50px;" id="print_slip_for_rider2">
                        <style type="text/css">
                            #print_slip_for_rider2 table {
                                border:solid #000 !important;
                                border-width:1px 0 0 1px !important;
                            }
                            #print_slip_for_rider2 th,#print_slip_for_rider2 td {
                                border:solid #000 !important;
                                border-width:0 1px 1px 0 !important;
                            }
                            </style>
                        <div style="height:25px"></div>
                        <table style="">
                            <tr><th style="background-color:#73acac69;text-align:center;">SALARY SLIP FOR RIDER</th></tr>
                            <tr><th class="month_year" style="text-align:center;"></th></tr>
                        </table>
                        <table class="print_class" style="border-top: unset !important;">
                                <tr>
                                    <th style="width:15%;text-align:left;">NAME</th>
                                    <td class="rider_name" style="width:45%;text-align:left;"></td>
                                    {{-- <th style="width:15%;text-align:left;">Designation:</th>
                                    <td style="width:45%;text-align:left;"></td> --}}
                                </tr>
                                <tr>
                                    <th style="width:15%;text-align:left;">EMPLOYEE ID:</th>
                                    <td class="employee_id" style="width:45%;text-align:left;"></td>
                                    {{-- <th style="width:15%;text-align:left;">WORKPLACE:</th>
                                    <td style="width:45%;text-align:left;"></td> --}}
                                </tr>
                                <tr>
                                    <th style="width:15%;text-align:left;">DATE OF JOINING:</th>
                                    <td class="today_date" style="width:45%;text-align:left;"></td>
                                    {{-- <th style="width:15%;text-align:left;"></th>
                                    <td style="width:45%;text-align:left;"></td> --}}
                                </tr>
                            </table>
                    
                        <table style="border-top: unset !important;">
                            <tr>
                                <th style="width:50%;text-align:center;">DESCRIPTION</th>
                                <th style="width:50%;text-align:center;">EARNINGS & DEDUCTIONS</th>
                                {{-- <th style="width:25%;text-align:center;">DEDUCTIONS:</th> --}}
                            </tr>
                            {{-- <tr>
                                <td style="width:50%;text-align:left;">Previous Balance</td>
                                <td  class="previous_balance" style="width:25%;text-align:end;"></td> 
                                <td style="width:25%;text-align:end;"></td>
                            </tr> --}}
                            <tr>
                                <td style="width:50%;text-align:left;">BASIC SALARY (<strong>Trips:</strong><span class="total_trips"></span>) (<strong>Hours:</strong><span class="total_hours"></span>) (<strong>Extra Trips:</strong><span class="extra_trips"></span>)</td>
                                <td  class="salary text-success" style="width:25%;text-align:end;"></td> 
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">NCW ALLOWANCE</td>
                                <td  class="ncw text-success" style="width:25%;text-align:end;"></td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">CUSTOMER TIP</td>
                                <td  class="tip text-success" style="width:25%;text-align:end;"></td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">BIKE ALLOWANCE</td>
                                <td  class="bike_allowns text-success" style="width:25%;text-align:end;"></td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">BONUS</td>
                                <td  class="bones text-success" style="width:25%;text-align:end;"></td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">Bike Fine</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="bike_fine text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">ADVANCE</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="advance text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">SALIK PLANTI</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="salik text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">SIM PLANTI</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="sim text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">ZOMATO PLANTI</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="zomato text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">CASH DELIVERY</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="dc text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">MCDONALD DEDUCTION</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="macdonald text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">RTA FINE</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="rta text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">MOBILE EMI</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="mobile text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">DISPLAN FINE</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="discipline text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">MICS CHARGES</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="mics text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            <tr>
                                <td style="width:50%;text-align:left;">Others</td>
                                {{-- <td style="width:25%;text-align:end;"></td> --}}
                                <td  class="cash_paid text-danger" style="width:25%;text-align:end;"></td>
                            </tr>
                            {{-- <tr>
                                <td style="width:50%;text-align:left;">TOTAL</td>
                                <td class="total_cr" style="width:25%;text-align:end;"></td>
                                <td class="total_dr" style="width:25%;text-align:end;"></td>
                            </tr> --}}
                            
                        </table>
                        <table style="border-top: unset !important;">
                            {{-- <tr>
                                <td class="payment_date" style="width:50%;text-align:left;"></td>
                                <td style="width:50%;text-align:center;background-color:#73acac69;">NET PAY</td>
                            </tr> --}}
                            <tr>
                                <td style="width:50%;text-align:left;">Net Salary</td>
                                <td class="net_pay" style="width:50%;text-align:center;background-color:#73acac69;"></td>
                            </tr>
                            <tr style="display:none">
                                <td style="width:50%;">SALARY PAID</td>
                                <td class="paid_salary" style="width:50%;text-align:center;background-color:#73acac69;"></td>
                            </tr>
                        </table>
                        {{-- <div style=""> 
                            <p style="font-size:12px;line-height: 14px;"><strong>Note: </strong>MR <span id="rider_id_1"></span> received <span class="paid_salary"></span> from King Riders Delivery Services LLC, and MR <span id="rider_id_2"></span> no is not valid for any kind of Gratuity, yearly tickets or any other expenses other than the salary.
                            </p>
                        </div> --}}
                    </div>
                </div>
                <div class="tab-pane" id="kt_portlet_base_demo_2_2_tab_content" role="tabpanel">
                        <div class="attendance_err"></div>
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid days_payout" id="Attendence_sheet">
                        <div class="kt-portlet kt-portlet--mobile">
                            <div class="kt-portlet__head kt-portlet__head--lg">
                                <div class="kt-portlet__head-label">
                                    <span class="kt-portlet__head-icon">
                                        <i class="kt-font-brand fa fa-hotel"></i>
                                    </span>
                                    <h3 class="kt-portlet__head-title">
                                        Attendence Sheet
                                    </h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body" id="rider_days_detail">
                                    <style type="text/css">
                                        #rider_days_detail table {
                                            border:solid #000 !important;
                                            border-width:1px 0 0 1px !important;
                                        }
                                        #rider_days_detail th,#rider_days_detail td {
                                            border:solid #000 !important;
                                            border-width:0 1px 1px 0 !important;
                                        }
                                        .custom_rider_id {
                                            font-size: 18px;
                                            }
                                        .custom_rider_name {
                                        font-size: 18px;
                                        }
                                        </style>
                                        <div class="custom_rider_id"></div>
                                        <div class="custom_rider_name"></div>
                    
                                <table class="table table-striped- table-hover table-checkable table-condensed rider_days_detail"  style="width:100%;margin:0px auto;">
                                    <thead>
                                        <tr>
                                            <th style=" width: 25%;border: 1px solid black;">Date</th>
                                            <th style=" width: 25%;border: 1px solid black;">Trips</th>
                                            <th style=" width: 25%;border: 1px solid black;">Hours</th>     
                                            <th style=" width: 25%;border: 1px solid black;">Status</th>                   
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      
        </div>
    </div>
</div>


@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/bindings/inputmask.binding.min.js"></script>
<script>
    $(document).ready(function(){
        $('#datepicker1').fdatepicker({format: 'dd-mm-yyyy'});
        $('#datepicker2').fdatepicker({format: 'dd-mm-yyyy'});
        
    });

</script>
<script>
    $(document).ready(function(){
        var basic_alert= '   <div><div class="alert alert-outline-danger fade show" role="alert">  '  + 
 '                                   <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>  '  + 
 '                                       <div class="alert-text">A simple danger alert—check it out!</div>  '  + 
 '                                       <div class="alert-close">  '  + 
 '                                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">  '  + 
 '                                           <span aria-hidden="true"><i class="la la-close"></i></span>  '  + 
 '                                       </button>  '  + 
 '                                   </div>  '  + 
 '                              </div> </div>  ' ;
        var emirate_mask = [{ "mask": "###-####-#######-#"}];
        $('[name="emirate_id"]').inputmask({
            mask: emirate_mask, 
            greedy: true,
            showMaskOnFocus:false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} 
        });
        $("#riders_slip_attendence").on("submit",function(e){
            e.preventDefault();
            var _form=$(this);
            if($('[name="emirate_id"]').val().trim()=='') return;
            var url="{{url('rider/show_slip/attendence')}}"
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    url:url,
                    method: "GET",
                    beforeSend: function() {            
                        $('.loading').show();
                    },
                    complete: function(){
                        $('.loading').hide();
                    },
                    data:_form.serializeArray(),
                })
                .done(function(data) {
                    console.log(data);
                    $('.error_msg').html('');
                    $("#rider_name").html("Rider");
                    $("#Tabs_for_slips_attendence").hide();
                    if (data.status==0) {
                        var _msg = $(basic_alert);
                        _msg.find('.alert-text').html(data.msg);
                        $('.error_msg').html(_msg.html());
                        return;
                    } else {
                        $("#Tabs_for_slips_attendence").show();
                        $("#Attendence_sheet").show();
                        $("#print_slip_for_rider2").show();
                        $('.salaryslip_err,.attendance_err').html('');
                        if (data.show_salaryslip!="1") {
                            $("#print_slip_for_rider2").hide();
                            var _msg = $(basic_alert);
                            _msg.find('.alert-text').html('You do not have permission to view this');
                            $('.salaryslip_err').html(_msg.html());
                        }
                        if (data.show_attendanceslip!="1") {
                            $("#Attendence_sheet").hide();
                            var _msg = $(basic_alert);
                            _msg.find('.alert-text').html('You do not have permission to view this');
                            $('.attendance_err').html(_msg.html());
                        }
                        $('.month_year').html(new Date(data.month).format("mmm,yyyy"));
                        $("#rider_name , .rider_name , #rider_id_1").html(data.rider_name);
                        $(" .employee_id ").html("KR-"+data.rider_id);
                        $(".today_date").html(new Date(data.date_of_joining).format('mm/dd/yyyy'));

                        $('.salary').html(data.salary);
                        $('.total_trips').html(data.trips);
                        $('.total_hours').html(data.hours);
                        $('.extra_trips').html(data.extra_trips);
                        $('.ncw').html(data.ncw);
                        $('.tip').html(data.tip);
                        $('.bike_allowns').html(data.bike_allowns);
                        $('.bones').html(data.bonus);

                        $('.bike_fine').html(data.bike_fine);
                        $('.advance').html(data.advance);
                        $('.salik').html(data.salik);
                        $('.sim').html(data.sim);
                        $('.zomato').html(data.denial_penalty);
                        $('.dc').html(data.dc);
                        $('.macdonald').html(data.macdonald);
                        $('.rta').html(data.rta);
                        $('.mobile').html(data.mobile);
                        $('.discipline').html(data.dicipline);
                        $('.mics').html(data.mics);
                        $('.cash_paid').html(data.cash_paid_in_advance);

                        $('.payment_date').html('PAYMENT DATE: '+data.payment_date);

                        var total_cr=parseFloat(data.salary)+parseFloat(data.ncw)+parseFloat(data.bike_allowns)+parseFloat(data.tip)+parseFloat(data.bonus);
                        var total_dr=parseFloat(data.cash_paid_in_advance)+parseFloat(data.bike_fine)+parseFloat(data.mics)+parseFloat(data.denial_penalty)+parseFloat(data.dicipline)+parseFloat(data.mobile)+parseFloat(data.rta)+parseFloat(data.advance)+parseFloat(data.salik)+parseFloat(data.sim)+parseFloat(data.dc)+parseFloat(data.macdonald);
                        var net_pay=(total_cr-total_dr).toFixed(2);
                        $('.total_cr').html(total_cr);
                        $('.total_dr').html(total_dr);
                        $('.net_pay').html(net_pay);
                        $('.paid_salary').html(data.salary_paid);

                        var _data=data.income_zomato;
                        
                        $(".rider_days_detail tbody").html(""); 
                        $(".rider_days_detail tfoot").html(""); 
                    
                        if(_data==null){
                            var _msg = $(basic_alert);
                            _msg.find('.alert-text').html('No record found');
                            var _html = _msg.html();
                            var wrapped = '<tr><td colspan="4">'+_html+'</td></tr>';
                            $('.rider_days_detail tbody').html(wrapped);
                            return;
                        }
                        
                        var time_sheet=_data.time_sheet;
                        var  rows='';
                        var calculated_trips=0;
                        var calculated_hours=0;
                    
                        var total_absents=_data.absents_count;
                        var extra_day=_data.extra_day;

                        var absent_hours=total_absents*11;
                        var work_hours_days=_data.working_days*11;
                        time_sheet.sort(function(a,b){
                            return a.date<b.date?-1:1;
                        });
                        time_sheet.forEach(function(item,j){
                            var trips=parseFloat(item.trips)||0;
                            var login_hours=parseFloat(item.login_hours)||0;
                            var date=new Date(item.date).format("dd mmm yyyy dddd");
                            if (login_hours>11) {
                                login_hours=11;
                            }

                            var absent__status=item.absent_status;
                            
                            var absent_stat='';
                            var absent_color='';
                            switch (absent__status) {
                                case 'Approved':
                                    absent_stat='- Approved ';
                                    absent_color='green';
                                    break;
                                case 'Rejected':
                                    absent_stat='- Rejected';
                                    absent_color='red';
                                    break;
                                default:
                                    absent_stat=' (Pending)';
                                    absent_color='red';
                                    break;
                            }
                        
                        
                            var off__status=item.off_days_status;
                            
                            var status='';
                            switch (off__status) {
                                case 'weeklyoff':
                                    status='<div style="color:green;">Weekly Off</div>';
                                    break;
                                case 'absent':
                                    status='<div style="color:'+absent_color+';" class="absents">Absent'+absent_stat+'</div>';
                                    break;
                                case 'extraday':
                                    login_hours=0;
                                    status='<div style="color:orange;">Extra Day</div>';
                                    break;
                                case 'present':
                                    status='<div>Present</div>';
                                    break;
                            
                                default:
                                    break;
                            }
                            calculated_trips+=trips;
                            calculated_hours+=login_hours;
                        rows+='<tr><td style=" width: 25%;">'+date+'</td><td style=" width: 25%;text-align: center;">'+trips+'</td><td style=" width: 25%;text-align: center;">'+login_hours+'</td> <td style=" width: 25%;text-align: center;">'+status+'</td></tr>';
                        });
                        var less_time=work_hours_days - calculated_hours;
                        var actual_hours=286 - less_time - absent_hours;
                        $("[name='absent_days']").val(total_absents);
                        $('[name="extra_day"]').val(extra_day);
                        $(".rider_days_detail tbody").html(rows); 
                        var tr='<tr><th>Total</th><th>'+calculated_trips.toFixed(2)+'</th><th>'+calculated_hours.toFixed(2)+'</th><th></th></tr>';
                        $(".rider_days_detail tfoot").html(tr);
                        var _name =  $('[name="rider_id"]:eq(0) option:selected').text().trim(); 
                        $('.custom_rider_id').text('Rider id: '+_data.rider_id);
                        $('.custom_rider_name').text('Rider name: '+data.rider_name);
                        // <tr><th>Actual Hours</th><th></th><th colspan="2">(Total: 286)-(Off: '+absent_hours+')-(Less time: '+less_time.toFixed(2)+')= '+actual_hours.toFixed(2)+'</th></tr>
                    }
                });
        });
    });
</script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
        
@endsection