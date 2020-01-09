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
                    <label style="font-size: 12px;">Employee ID:</label>
                    <select class="form-control kt-select2" name="employee_id_num" class="employee_selector" >
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->id }}
                        </option>     
                        @endforeach 
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="my-2 mx-4">
                    <label>Select Employee:</label>
                    <select class="form-control kt-select2" name="employee_id" id="gb_employee_id">
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->name }}
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
                            <input type="radio" name="sort_by" id="select_month" value="select_month"> Select Month
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
    </div>
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row text-center py-3">
                <div class="kt-portlet__head-toolbar col-md-12">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            {{-- <a href="" class=" btn btn-success btn-elevate btn-icon-sm" data-toggle="modal" data-target="#employee_mobile_charges">
                                <i class="fa fa-mobile-alt"></i>
                                Mobile Charges
                            </a>
                            
                            &nbsp; --}}
                            <a href=""  class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#employee_bonus" >
                                <i class="la la-money"></i>
                                Bonus
                            </a>
                            &nbsp; 
                            <a href="" class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#employee_fine" >
                                <i class="la la-money"></i>
                                Employee Fine
                            </a>
                            &nbsp; 
                            {{-- <a href="" class=" btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#employee_sim_bill">
                                <i class="fa fa-sim-card"></i>
                                Sim Bill
                            </a>
                            &nbsp; --}}
                            {{-- <a href="" class=" btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#employee_salary">
                                <i class="la la-plus"></i>
                                Generate Salary
                            </a>  --}}
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
                    Employee Account
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="employee-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Company Profit</th>
                        <th>Running Balance</th>
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
                                {{-- <th>Action</th>  --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            {{-- <div class="row">
                <div class="col">
                    <button class="btn btn-success float-right" id="btnSend_profit" onclick="send_profit()"></button>
                </div>
            </div> --}}
        </div>
    </div>
</div>


<div class="modal fade" id="employee_bonus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">  
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="title_rider_expense">Add Bonus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" enctype="multipart/form-data" id="bonus">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Employee:</label>
                        <select class="form-control bk-select2" name="employee_id" class="employee_selector" >
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->name }}
                                </option>      
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bonus Month:</label>
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
                        <input required type="number" step="0.01" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
                        @if ($errors->has('amount'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{$errors->first('amount')}}
                                </strong>
                            </span>
                        @endif
                    </div>
                    <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-success">Add Bonus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="employee_fine" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">  
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="title_rider_expense">Add Fine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" enctype="multipart/form-data" id="fine">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Employee:</label>
                        <select class="form-control bk-select2" name="employee_id" class="employee_selector" >
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->name }}
                                </option>      
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fine Month:</label>
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
                        <input required type="number" step="0.01" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
                        @if ($errors->has('amount'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{$errors->first('amount')}}
                                </strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Reason for Fine:</label> 
                        <textarea required type="text" class="form-control @if($errors->has('source')) invalid-field @endif" rows="5" cols="12" name="source" placeholder="Enter Reason For Fine" value=""></textarea>
                    </div>
                    <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-success">Add Fine</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="employee_sim_bill" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">  
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="title_rider_expense">Add Sim Bill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" enctype="multipart/form-data" id="sim_bill">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fine Month:</label>
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
                        <label>Select Employee:</label>
                        <select class="form-control bk-select2" name="employee_id" class="employee_selector" >
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->name }}
                                </option>      
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Sim:</label>
                        <select class="form-control bk-select2" name="sim_id" class="sim_selector" >
                            @foreach ($sims as $sim)
                                <option value="{{ $sim->id }}">
                                        {{$sim->sim_company}} - {{ $sim->sim_number }} 
                                </option>      
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group ">
                        <label>Allowed Balance:</label>
                        <input required readonly type="text" class="form-control" name="allowed_balance" >
                    </div> 
                    <div class="form-group">
                        <label>Bill Amount:</label>
                        <input required type="text" class="form-control" name="bill_amount">
                    </div>
                    <div class="form-group">
                        <label>Extra Usage Amount:</label>
                        <input required readonly type="text" class="form-control" name="extra_usage_amount" placeholder="Enter extra usage amount ">
                    </div>
                    <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-success">Add Bill</button>
                    </div>
                </div>
            </form>
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
        $("#employee_bonus").on('shown.bs.modal', function(){
            var month=biketrack.getUrlParameter('r1d1');
            var _month=new Date(month).format("mmmm yyyy");
            if (month!="") { 
                $("#employee_bonus [name='month']").attr("data-month", _month)
                biketrack.refresh_global()
            }
        });
        $("#employee_sim_bill").on('shown.bs.modal', function(){
            var month=biketrack.getUrlParameter('r1d1');
            var _month=new Date(month).format("mmmm yyyy");
            if (month!="") { 
                $("#employee_sim_bill [name='month']").attr("data-month", _month)
                biketrack.refresh_global()
            }
        });
        $("#employee_fine").on('shown.bs.modal', function(){
            var month=biketrack.getUrlParameter('r1d1');
            var _month=new Date(month).format("mmmm yyyy");
            if (month!="") { 
                $("#employee_fine [name='month']").attr("data-month", _month)
                biketrack.refresh_global()
            }
        });
        $('form#bonus').on('submit', function(e){   
            e.preventDefault();
            var _form = $(this);
            var _modal = _form.parents('.modal');
            var url ="{{ url('admin/employee/bonus/') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'GET',
                data: _form.serializeArray(),
                success: function(data){
                    _modal.modal('hide');
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
                    // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
                    _modal.modal('hide');
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

        $('form#fine').on('submit', function(e){   
            e.preventDefault();
            var _form = $(this);
            var _modal = _form.parents('.modal');
            var url ="{{ url('admin/employee/fine/') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'GET',
                data: _form.serializeArray(),
                success: function(data){
                    _modal.modal('hide');
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
                    // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
                    _modal.modal('hide');
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
    var table;
    var table_bills;
    var send_profit = function(){
        var _profit = $('#btnSend_profit').attr('data-profit');
        var _Month = $('#btnSend_profit').attr('data-month');
        var _Emloyee_Id = $('[name="employee_id"]').val();
        var _FormData = new FormData();
        _FormData.append('profit', _profit);
        _FormData.append('month', _Month);
        _FormData.append('rider_id', _Emloyee_Id);

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
            placeholder: "Select an Employee",
            width:'100%'    
        });
        
        $('[name="employee_id"], [name="employee_id_num"]').on('change', function(){
            var _employeeId = $(this).val();
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
            if(_SortBy=='select_month'){
                $('#select_month_custom').fadeIn('fast');
                var custom_month=$('[name="custom_select_month"]').val();
                var year=new Date(custom_month).format("yyyy");
                var month=new Date(custom_month).format("mm");
                start=new Date(year,month-1,1).format("yyyy-mm-dd");
                end=new Date(year,month,0).format("yyyy-mm-dd");
            }
            var _data = {
                range: {
                    start_date: start,
                    end_date: end
                },
                empployee_id: _employeeId
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                empployee_id: _employeeId,
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
            $('#custom_range,#select_month_custom').fadeOut('fast');
            if(_SortBy=='custom'){
                $('#custom_range').fadeIn('fast');
                return;
            }
            if(_SortBy=='select_month'){
                $('#select_month_custom').fadeIn('fast');
                return;
            }
            var _data = {
                range: {
                    start_date: start,
                    end_date: end
                },
                empployee_id: $('[name="employee_id"]').val()
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                empployee_id: $('[name="employee_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
            // var _Url = "{{url('/company/debits/get_salary_deduction/')}}"+"/"+_riderId+''
        });
        
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
                empployee_id: $('[name="employee_id"]').val()
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                empployee_id: $('[name="employee_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
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
                empployee_id: $('[name="employee_id"]').val()
            };
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                empployee_id: $('[name="employee_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
        }
        var getData = function(ranges){
            var empployee_id=biketrack.getUrlParameter('empployee_id');
            $('[name="employee_id"], [name="employee_id_num"]').val(empployee_id).trigger("change.select2");
            var url = "{{ url('admin/accounts/employee/account/') }}"+"/"+ranges;
            console.warn(url)
            table = $('#employee-table').DataTable({
                lengthMenu: [[-1], ["All"]],
                destroy: true,
                "dom": 'rft',
                processing: true,
                ordering: false,
                serverSide: false,
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
                ],
                responsive:true,
            });
            var url = "{{ url('admin/accounts/employee/bills/') }}"+"/"+ranges;
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
                    // { data: 'action', name: 'action' },
                   
                ],
                responsive:true,
            });
        }

        var init_table=function(){
            var r1d1=biketrack.getUrlParameter('r1d1');
            var r1d2=biketrack.getUrlParameter('r1d2');
            var empployee_id=biketrack.getUrlParameter('empployee_id');
            var sort_by=biketrack.getUrlParameter('sort_by');
            console.log(r1d1, r1d2, empployee_id, sort_by);
            
            if(r1d1!="" && r1d2!="" && empployee_id!="" && sort_by!=""){
                $('[name="sort_by"][value="'+sort_by+'"]').prop('checked', true);
                $('#custom_range').hide();
                if(sort_by=="custom"){
                    $('#custom_range').fadeIn('fast');
                    $('[name="dr1"]')
                    .daterangepicker({ startDate: new Date(r1d1).format('mm/dd/yyyy'), endDate: new Date(r1d2).format('mm/dd/yyyy') })
                    .on('apply.daterangepicker', function(ev, picker) {
                        dpCallback(picker);
                    });
                }
                if(sort_by=="select_month"){
                    $('#select_month_custom').fadeIn('fast');
                    $('[name="custom_select_month"]').val(r1d1).trigger('change.select2');
                }
                $('[name="employee_id"],[name="employee_id_num"]').val(empployee_id);
                $('[name="employee_id_num"]').trigger('change');
                return;
            }
            $('[name="sort_by"]:checked').trigger('change')
        }
        init_table();
        

    })
function FineBike(employee_id,bike_fine_id,amount,month){
    console.log(month)
    var url = "{{ url('admin/accounts/fine/paid/Rider') }}" + "/" + employee_id +"/"+bike_fine_id+"/"+amount+"/"+month;
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

    function updateStatus(employee_id,month,type)
{
    var url = "{{ url('admin/bill/payment') }}" + "/" + employee_id + "/updateStatus" + "/" + month + "/" + type;
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



</script> 
@endsection