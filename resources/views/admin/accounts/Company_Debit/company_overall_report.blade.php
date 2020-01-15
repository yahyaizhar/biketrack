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
        .watermark{
            background: transparent;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 146%;
            pointer-events: none; 
        }
        #watermark-text
        {
            color: #f5f5f5;
            font-size: 25rem;
            opacity: 0.5;
            background: transparent;
        }
        .table th, .table td{
            padding:0 !important;
        }
    </style>
    <!--end::Page Vendors Styles -->
@endsection 
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        Months
                        </h3>
                    </div>
                </div>
 @include('client.includes.message')
<div class="kt-portlet__body">
<div class="col-md-6">
        <div class="mt-2 mx-4">
            <label>Show result of:</label>
            <div class="kt-radio-inline">
                <label class="kt-radio">
                <input type="radio" data-start="{{Carbon\Carbon::now()->subMonths(1)->startOfMonth()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->subMonths(1)->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="week" checked> Last Month
                    <span></span>
                </label>
                <label class="kt-radio">
                    <input type="radio" data-start="{{Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="month"> This Month
                    <span></span>
                </label>  
                <label class="kt-radio">
                    <input type="radio" data-start="{{Carbon\Carbon::now()->startOfYear()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->endOfYear()->format('Y-m-d')}}" name="sort_by" value="year"> This Year
                    <span></span>
                </label>
                <label class="kt-radio">
                    <input type="radio" name="sort_by" value="custom"> Custom
                    <span></span>
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mt-2">
                <div class="kt-portlet__head-actions" id="custom_range" style="display:none">
                    <label for="dr1">Select range</label>
                    <input type="text" id="d1" name="dr1" class="form-control" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="kt-widget24">
<div class="kt-widget24__details">
    <a class="kt-widget24__info">
        <h4 class="kt-widget24__title">Overall Balance</h4>
        <span class="kt-widget24__stats kt-font-success" id="overall_balnce">0</span>
    </a>
    <a class="kt-widget24__info">
        <h4 class="kt-widget24__title">Total Profit</h4>
        <span class="kt-widget24__stats kt-font-primary" id="total_profit">0</span>
    </a>
    <a class="kt-widget24__info">
        <h4 class="kt-widget24__title">Overall Balance Monthly</h4>
        <span class="kt-widget24__stats kt-font-warning" id="overall_balnce_monthly">0</span>
    </a>
    <a class="kt-widget24__info">
            <h4 class="kt-widget24__title">Total Investment</h4>
            <span class="kt-widget24__stats kt-font-primary" id="total_investment">0</span>
        </a>
    <a class="kt-widget24__info">
        <h4 class="kt-widget24__title">Payable To Riders</h4>
        <span class="kt-widget24__stats kt-font-danger" id="payable_to_riders">0</span>
    </a>
</div>
</div>
</div>
</div>
</div>
</div>
{{-- Month OPTIONS --}}
        
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content-b">
    <div class="kt-portlet kt-portlet--mobile" style="position: relative;">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    View Company overall Report
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="CO_report">
                <thead>
                    <tr> 
                        <th>Date</th>
                        <th>Description</th>
                        <th>Credit</th>
                        <th>Debit</th>                    
                    </tr>
                </thead>
            </table>
        </div>
        <div class="watermark"></div> 
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script>
    var table;
    var month=null;
    var rider=null;
$(document).ready(function(){
//   custom month data

$('[name="sort_by"]').on('change', function(){
            var _SE = $('[name="sort_by"]:checked');
            var _SortBy = _SE.val();
            var start = _SE.attr('data-start'),
                end = _SE.attr('data-end');
            $('#custom_range').fadeOut('fast');
            if(_SortBy=='custom'){
                $('#custom_range').fadeIn('fast');
                start = $('[name="dr1"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                end = $('[name="dr1"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
            }
            var _data = {
                range: {
                    start_date: start,
                    end_date: end
                },
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
        });

        $('[name="sort_by"]').on('change', function(){
            var _SortBy = $(this).val();
            var start = $(this).attr('data-start'),
                end = $(this).attr('data-end');
            $('#custom_range').fadeOut('fast');
            if(_SortBy=='custom'){
                $('#custom_range').fadeIn('fast');
                return;
            }
            var _data = {
                range: {
                    start_date: start,
                    end_date: end
                },
                rider_id: $('[name="rider_id"]').val()
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
            // var _Url = "{{url('/company/debits/get_salary_deduction/')}}"+"/"+_riderId+''
        });

        $('input[name="dr1"]').daterangepicker({
            opens: 'left', 
            locale: {
                format: 'DD-MM-YYYY'
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));

            
        }).on('apply.daterangepicker', function(ev, picker) {
            dpCallback(picker);
        });

        var dpCallback = function(picker){
            console.log(picker.startDate.format('YYYY-MM-DD'));
            console.log(picker.endDate.format('YYYY-MM-DD'));
            var _data = {
                range: {
                    start_date: picker.startDate.format('YYYY-MM-DD'),
                    end_date: picker.endDate.format('YYYY-MM-DD')
                },
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
        }


        


        var getData = function(ranges){
            var url = "{{ url('admin/accounts/company/overall/account/') }}"+"/"+ranges;
            console.log(url)
            table = $('#CO_report').DataTable({
                lengthMenu: [[-1], ["All"]],
                destroy: true,
                // "dom": 'rft',
                processing: true,
                ordering: false,
                serverSide: false,
                'language': { 
                    'loadingRecords': '&nbsp;',
                    'processing': $('.loading').show()
                },
                drawCallback:function(data){
                    var api = this.api();
                    var _data = api.data();
                    console.log(_data,'datadatadata');
                    var response = _data.ajax.json(); 
                     if(typeof response=="undefined"){
                         return;
                     }
                    var _overall_balnce = response.overall_balnce;
                    var _total_profit = response.total_profit;
                    var _overall_balnce_monthly = response.overall_balnce_monthly;
                    var _payable_to_riders = response.payable_to_riders;
                    var _total_investment =response.total_investment;

                    $('#overall_balnce').text(_overall_balnce);
                    $('#total_profit').text(_total_profit);
                    $('#overall_balnce_monthly').text(_overall_balnce_monthly);
                    $('#payable_to_riders').text(_payable_to_riders);
                    $('#total_investment').text(_total_investment);



                    console.log(data);
                    $('#btnSend_profit').text('').fadeOut('fast'); 
                    var response = table.ajax.json();
                    console.log(response);
                    
                    if(typeof response == "undefined") return;
                    var _ClosingBalance = response.closing_balance;
                    var _Month = response.last_month;
                    var _Running_Balance = response.running_static_balance;
                    $('#closing_balance').text(_ClosingBalance);
                    var running_closing_balance = parseFloat($('#running_closing_balance').text());
                    if(running_closing_balance > 0){
                        $('#btnSend_profit').text('Send '+parseFloat(_Running_Balance).toFixed(2)+' to Company Profit').attr('data-month', _Month).attr('data-profit', _Running_Balance).fadeIn('fast'); 
                    }
                    
                },
                ajax: url,
                columns: [
                    { data: 'month', name: 'month' },
                    { data: 'description', name: 'description' },
                    {data:'cr',name:'cr'},
                    {data:'dr',name:'dr'}, 
                ],
                responsive:true,
            });
        }

        var r1d1=biketrack.getUrlParameter('r1d1');
        var r1d2=biketrack.getUrlParameter('r1d2');
        var sort_by=biketrack.getUrlParameter('sort_by');
        console.log(r1d1, r1d2, sort_by);
        if(r1d1!="" && r1d2!="" && sort_by!=""){
            $('[name="sort_by"][value="'+sort_by+'"]').prop('checked', true)
            $('#custom_range').hide();
            if(sort_by=="custom"){
                $('#custom_range').fadeIn('fast');
                $('[name="dr1"]')
                .daterangepicker({ startDate: new Date(r1d1).format('mm/dd/yyyy'), endDate: new Date(r1d2).format('mm/dd/yyyy') })
                .on('apply.daterangepicker', function(ev, picker) {
                    dpCallback(picker);
                });
            }
        }
        $('[name="sort_by"]:checked').trigger('change');
    });
</script>
    @endsection