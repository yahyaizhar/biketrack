@extends('admin.layouts.app')
@section('head')
<style>
    .fields_wrapper{
        display: none;
    }
    .fields_wrapper--show{
        display: block;
    }
    /* .table th, .table td{
        padding:0 !important;
    } */
</style>
@endsection
@section('main-content') 


<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet">
            <div class="row row-no-padding">
                <div class="col-md-1">
                    <div class="my-2 mx-1">
                        <label>Rider ID:</label>
                        <select class="form-control kt-select2" name="rider_id_num" class="rider_selector" >
                            @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}">
                                {{ $rider->id }}
                            </option>     
                            @endforeach 
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="my-2 mx-4">
                        <label>Select Rider:</label>
                        <select class="form-control kt-select2" name="rider_id" id="gb_rider_id">
                            @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}">
                                {{ $rider->name }}
                            </option>     
                            @endforeach 
                        </select>
                            
                    </div>
                </div>

                <div class="col-md-6 offset-md-1">
                    <div class="mt-2 mx-4">
                        <label>Show result of:</label>
                        <div class="kt-radio-inline">
                            <label class="kt-radio">
                            <input type="radio" data-start="{{Carbon\Carbon::now()->subMonths(1)->startOfMonth()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->subMonths(1)->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="last_month" >{{carbon\carbon::now()->subMonths(1)->format('F')}}
                                <span></span>
                            </label>
                            <label class="kt-radio">
                                <input type="radio" data-start="{{Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="month" checked>{{carbon\carbon::now()->format('F')}}
                                <span></span>
                            </label>
                            <label class="kt-radio">
                                <input type="radio" name="select_month" id="select_month" value="select_month"> Select Month
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
                    <div class="col-md-12">
                        <div class="mt-2">
                            <div class="kt-portlet__head-actions" id="select_month_custom" style="display:none">
                                <label for="dr1">Select month</label>
                                <select class="form-control bk-select2" name="custom_select_month" value="select month">
                                    <option >Select Month</option>
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-01-01">January</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-02-01">Febuary</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-03-01">March</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-04-01">April</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-05-01">May</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-06-01">June</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-07-01">July</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-08-01">August</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-09-01">September</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-10-01">October</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-11-01">November</option>   
                                    <option value="{{Carbon\Carbon::now()->format('Y')}}-12-01">December</option>    
                                </select> 
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <div class="row row-no-padding row-col-separator-xl">
            {{-- <div class="col-md-12 col-lg-6 col-xl-6">
    
                <!--begin::New Orders-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <a href="#" class="kt-widget24__info">
                            <h4 class="kt-widget24__title">
                                Opening Balance
                            </h4>
                            
                            <span class="kt-widget24__stats kt-font-danger">
                                {{$opening_balance}}
                            </span>
                        </a>
                    </div>
                    
                </div>
    
                <!--end::New Orders-->
            </div> --}}
            <div class="col-md-12 col-lg-6 col-xl-6">
    
                <!--begin::New Users-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <a class="kt-widget24__info">
                            <h4 class="kt-widget24__title">
                                Closing Balance
                            </h4>
                            <span class="kt-widget24__stats kt-font-success" id="closing_balance">
                                
                            </span>
                        </a>
                    </div>
                </div>
    
                <!--end::New Users-->
            </div>
            
        </div>
    </div>
    <div class="kt-portlet">
            <div class="kt-portlet__body  kt-portlet__body--fit">
                <div class="row text-center py-3">
                    <div class="kt-portlet__head-toolbar col-md-12">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="" data-ajax="{{ route('MobileInstallment.create') }}" class=" btn btn-success btn-elevate btn-icon-sm">
                                    <i class="fa fa-mobile-alt"></i>
                                    Mobile Installment
                                </a>
                                
                                &nbsp;
                                <a href="" data-ajax="{{ route('admin.fuel_expense_create') }}" class=" btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="fa fa-gas-pump"></i>
                                    Fuel
                                </a>
                                &nbsp;
                                <a href="" data-ajax="{{ route('admin.create_bike_rent') }}" class=" btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="fa fa-motorcycle"></i>
                                    Bike Rent
                                </a>
                                &nbsp;
                                <a href="" data-ajax="{{ route('admin.accounts.maintenance_index') }}" class=" btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="flaticon2-gear"></i>
                                    Maintenance
                                </a>
                                &nbsp;
                                <a href="" data-ajax="{{ route('salik.add_salik') }}" class=" btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="flaticon2-lorry"></i>
                                    Add Salik
                                </a>
                                &nbsp;
                                <a href="" data-ajax="{{ route('SimTransaction.create_sim') }}" class=" btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="fa fa-sim-card"></i>
                                    Sim Bill
                                </a>
                                &nbsp;
                                <a href="" data-ajax="{{ route('account.new_salary') }}" class=" btn btn-brand btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    Generate Salary
                                </a> 
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Company-Rider Account
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

                <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="data-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>Date</th>
                        <th>Description</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Company Profit</th>
                        <th>Running Balance</th>
                        <th>Action</th>
                        
                    </tr>
                </thead>
            </table>

            <div class="row">
                <div class="col">
                    <div class="h1 text-center mt-5">Bill Account</div>
                    <table class="table table-striped- table-hover table-checkable table-condensed" id="table-bills">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Bill</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Action</th> 
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!--end: Datatable -->

            <div class="row">
                <div class="col">
                    <button class="btn btn-success float-right" id="btnSend_profit" onclick="send_profit()"></button>
                </div>
            </div>
        </div>
    

    </div>
</div>

<div class="modal fade" id="quick_view" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header border-bottom-0">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

        </div>
    </div>
    </div>
</div>
@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
    
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
   
    var table;
    var table_bills;
    var send_profit = function(){
        var _profit = $('#btnSend_profit').attr('data-profit');
        var _Month = $('#btnSend_profit').attr('data-month');
        var _RiderId = $('[name="rider_id"]').val();
        var _FormData = new FormData();
        _FormData.append('profit', _profit);
        _FormData.append('month', _Month);
        _FormData.append('rider_id', _RiderId);

        var url = '{{route('admin.accounts.add_company_profit')}}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : url,
            type : 'POST',
            data: _FormData,
            processData: false,
            contentType: false,
            success: function(data){
                console.log(data);
                
                swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'Record updated successfully.',
                    showConfirmButton: false,
                    timer: 1500
                });
                table.ajax.reload(null, false);
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
    $(function(){
        $('[data-ajax]').on('click', function(e){
            e.preventDefault();
            var _ajaxUrl = $(this).attr('data-ajax');
            console.log(_ajaxUrl);
            var _self = $(this);
            var loading_html = '<div class="d-flex justify-content-center modal_loading"><i class="la la-spinner fa-spin display-3"></i></div>';
            var _quickViewModal = $('#quick_view');
            var selected_month=new Date(biketrack.getUrlParameter("r1d1")).format('mmmm yyyy');
            console.log(selected_month);
            _quickViewModal.find('.modal-body').html(loading_html);
            _quickViewModal.modal('show');
            $.ajax({
                url : _ajaxUrl,
                type : 'GET',
                dataType: 'html',
                success: function(data){
                    console.log($(data));
                    
                    var _targetForm = $(data).find('form').wrap('<p/>').parent().html();
                    
                    
                    _quickViewModal.find('.modal-title').text(_self.text().trim());
                   
                    _quickViewModal.find('.modal-body').html(_targetForm);
                    _quickViewModal.find('[name="month"]').attr('data-month',selected_month);
                    _quickViewModal.find('[name="month_year"]').attr('data-month',selected_month);
                    $('script[data-ajax]').remove();
                    $('body').append('<script data-ajax>'+$(data).find('[data-ajax]').html()+'<\/script>');
                        biketrack.refresh_global();
                    //add event handler to submit form in modal
                    _quickViewModal.find('form').off('submit').on('submit', function(e){
                        e.preventDefault();
                        _quickViewModal.modal('hide');
                        var _form = $(this);
                        var _url = _form.attr('action');
                        $.ajax({
                            url : _url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type : 'POST',
                            data: _form.serialize(),
                            success: function(data){
                                console.log(data);
                                
                                swal.fire({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Record updated successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                table.ajax.reload(null, false);
                                table_bills.ajax.reload(null, false);
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
                    });

                    biketrack.refresh_global();
                },
                error: function(error){
                    console.log(error);
                }
            });
            
        });


        
        $('.kt-select2').select2({
            placeholder: "Select a rider",
            width:'100%'    
        });
        
        $('[name="rider_id"], [name="rider_id_num"]').on('change', function(){
            var _riderId = $(this).val();
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
                rider_id: _riderId
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: _riderId,
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
        });

        $('[name="sort_by"]').on('change', function(){
            $('[name="select_month"]').prop("checked",false);
            $('#select_month_custom').fadeOut('fast');
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
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
            // var _Url = "{{url('/company/debits/get_salary_deduction/')}}"+"/"+_riderId+''
        });
        
        $('[name="select_month"]').on("change",function(){
            $('[name="sort_by"]').prop("checked",false);
            $('#custom_range').fadeOut('fast');
        var selected_month=$(this).val();
                $('#select_month_custom').fadeOut('fast');
            if(selected_month=='select_month'){
                $('#select_month_custom').fadeIn('fast');
                $('[name="custom_select_month"]').on("change",function(){
                    var custom_month=$(this).val();
                    var year=new Date(custom_month).format("yyyy");
                    var month=new Date(custom_month).format("mm");
                    var start=new Date(year,month-1,1).format("yyyy-mm-dd");
                    var end=new Date(year,month,0).format("yyyy-mm-dd");
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
                        rider_id: $('[name="rider_id"]').val(),
                        sort_by: $('[name="select_month"]:checked').val()
                    }
                    
                    console.log(data);
                    biketrack.updateURL(data);
                    var url = JSON.stringify(_data) ;
                    getData(url);
                });
                return;
            }
            
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
                rider_id: $('[name="rider_id"]').val()
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
        }


        


        var getData = function(ranges){
            var rider_id=biketrack.getUrlParameter('rider_id');
            $('[name="rider_id"], [name="rider_id_num"]').val(rider_id).trigger("change.select2");
            var url = "{{ url('admin/accounts/company/account/') }}"+"/"+ranges;
            console.warn(url)
            table = $('#data-table').DataTable({
                lengthMenu: [[-1], ["All"]],
                destroy: true,
                "dom": 'rft',
                processing: true,
                ordering: false,
                serverSide: true,
                'language': { 
                    'loadingRecords': '&nbsp;',
                    'processing': $('.loading').show()
                },
                drawCallback:function(data){
                    console.log(data);
                    $('#btnSend_profit').text('').fadeOut('fast'); 
                    var response = table.ajax.json();
                    console.log(response);
                    
                    if(typeof response == "undefined") return;
                    var _ClosingBalance = response.closing_balance;
                    var _Month = response.last_month;
                    var _Running_Balance = response.running_static_balance;
                    $('#closing_balance').text(_ClosingBalance);
                    var running_closing_balance =_Running_Balance;
                    if(running_closing_balance > 0){
                        $('#btnSend_profit').text('Send '+parseFloat(_Running_Balance).toFixed(2)+' to Company Profit').attr('data-month', _Month).attr('data-profit', _Running_Balance).fadeIn('fast'); 
                    }
                    
                },
                ajax: url,
                columns: [
                    { data: 'date', name: 'date' },            
                    { data: 'desc', name: 'desc' },
                    { data: 'cr', name: 'cr' },
                    { data: 'dr', name: 'dr' },
                    { data: 'company_profit', name: 'company_profit' },
                    { data: 'balance', name: 'balance' },
                    { data: 'action', name: 'action' },
                   
                ],
                responsive:true,
            });
            var url = "{{ url('admin/accounts/company/bills/') }}"+"/"+ranges;
            table_bills = $('#table-bills').DataTable({
                lengthMenu: [[-1], ["All"]],
                dom: 't',
                destroy: true,
                processing: true,
                ordering: false,
                serverSide: true,
                'language': { 
                    'loadingRecords': '&nbsp;',
                    'processing': $('.loading').show()
                },
                drawCallback:function(data){
                    console.log(data);
                    $('#btnSend_profit').text('').fadeOut('fast'); 
                    var response = table.ajax.json();
                    console.log(response);
                    
                    if(typeof response == "undefined") return;
                    var _ClosingBalance = response.closing_balance;
                    var _Month = response.last_month;
                    var _Running_Balance = response.running_static_balance;
                    $('#closing_balance').text(_ClosingBalance);
                    var running_closing_balance = _Running_Balance;
                    if(running_closing_balance > 0){
                        $('#btnSend_profit').text('Send '+parseFloat(_Running_Balance).toFixed(2)+' to Company Profit').attr('data-month', _Month).attr('data-profit', _Running_Balance).fadeIn('fast'); 
                    }
                    
                },
                ajax: url,
                columns: [
                    { data: 'date', name: 'date' },            
                    { data: 'bill', name: 'bill' },
                    { data: 'amount', name: 'amount' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'action', name: 'action' },
                   
                ],
                responsive:true,
            });
        }

        var r1d1=biketrack.getUrlParameter('r1d1');
        var r1d2=biketrack.getUrlParameter('r1d2');
        var rider_id=biketrack.getUrlParameter('rider_id');
        var sort_by=biketrack.getUrlParameter('sort_by');
        console.log(r1d1, r1d2, rider_id, sort_by);
        var already_triggered = false;
        if(r1d1!="" && r1d2!="" && rider_id!="" && sort_by!=""){
            $('[name="sort_by"][value="'+sort_by+'"]').prop('checked', true);
            if (sort_by=='select_month') {
                $('[name="select_month"][value="'+sort_by+'"]').prop('checked', true);
                $('[name="sort_by"][value="'+sort_by+'"]').prop('checked', false);
            }
            $('#custom_range').hide();
            if(sort_by=="custom"){
                $('#custom_range').fadeIn('fast');
                $('[name="dr1"]')
                .daterangepicker({ startDate: new Date(r1d1).format('mm/dd/yyyy'), endDate: new Date(r1d2).format('mm/dd/yyyy') })
                .on('apply.daterangepicker', function(ev, picker) {
                    dpCallback(picker);
                });
            }
            already_triggered = true;
            $('[name="rider_id"],[name="rider_id_num"]').val(rider_id).trigger('change');
        }
        if(!already_triggered) $('[name="sort_by"]:checked').trigger('change');
        

    })
function FineBike(rider_id,bike_fine_id,amount,month){
    console.log(month)
    var url = "{{ url('admin/accounts/fine/paid/Rider') }}" + "/" + rider_id +"/"+bike_fine_id+"/"+amount+"/"+month;
   console.log(url)
    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'GET',
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload(null, false);
                    table_bills.ajax.reload(null, false);
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

    function updateStatus(rider_id,month,type)
{
    var url = "{{ url('admin/bill/payment') }}" + "/" + rider_id + "/updateStatus" + "/" + month + "/" + type;
    console.log(url,true);
    swal.fire({
        title: 'Are you sure?',
        text: "You want to Pay Bill!",
        type: 'warning', 
        showCancelButton: true,
        confirmButtonText: 'Yes!'
    }).then(function(result) {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'PUT',
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record updated successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload(null, false);
                    table_bills.ajax.reload(null, false);
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
    });
}


function deleteCompanyRows(id,model_class,model_id,rider_id,string,month){
    var url = "{{ url('admin/delete/accounts/rows') }}";
    console.log(url);
    swal.fire({
        title: 'Are you sure?',
        text: "You want to delete!",
        type: 'warning', 
        showCancelButton: true,
        confirmButtonText: 'Yes!'
    }).then(function(result) {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data={
                "id":id,
                "model_class":model_class,
                "model_id":model_id,
                "rider_id":rider_id,
                "string":string,
                "month":month,
            };
            $.ajax({
                url  :  url,
                type : 'GET',
                data : data,
                beforeSend: function() {            
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                },
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record Deleted successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    table.ajax.reload(null, false);
                    table_bills.ajax.reload(null, false);
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to Delete.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
    
}
</script> 
@endsection