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
                        <span class="form-text text-muted float-right rider__feid"></span>
                            
                    </div>
                </div>

                <div class="col-md-6 offset-md-1">
                    <div class="mt-2 mx-4">
                        <label>Show result of:</label>
                        <div class="kt-radio-inline">
                            <label class="kt-radio">
                            <input type="radio" data-start="{{Carbon\Carbon::now()->startOfMonth()->subMonths(1)->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->startOfMonth()->subMonths(1)->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="last_month" >{{carbon\carbon::now()->startOfMonth()->subMonths(1)->format('F')}}
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
                                    {{-- <option >Select Month</option> --}}
                                    @for ($i = 0; $i <= 12; $i++)
                                        @php
                                            $_m =Carbon\Carbon::now()->startOfMonth()->addMonth(-$i);
                                        @endphp
                                        <option value="{{$_m->format('Y-m-d')}}">{{$_m->format('F-Y')}}</option>
                                    @endfor 
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
                                {{-- <a href="" data-ajax="{{ route('MobileInstallment.create') }}" class=" btn btn-success btn-elevate btn-icon-sm">
                                    <i class="fa fa-mobile-alt"></i>
                                    Mobile Installment
                                </a>
                                
                                &nbsp; --}}
                                
                                <a href="" data-ajax="{{ route('admin.fuel_expense_create') }}" class=" btn btn-success btn-elevate btn-icon-sm">
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
                            <div class="kt-portlet__head-actions" style="margin-top:10px;">
                                <a href=""  class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#rider_expense_bonus" >
                                    <i class="la la-money"></i>
                                        Bonus
                                </a>
                                &nbsp; 
                                <a href="" class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#rider_expense_discipline" >
                                    <i class="la la-money"></i>
                                        KingRiders Fine
                                </a>
                                &nbsp;      
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
<div class="modal fade" id="edit_row_model" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header border-bottom-0">
            <h5 class="modal-title">Edit Company Account</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form class="kt-form" enctype="multipart/form-data">
                <input type="hidden" name="statement_id">
                <div class="form-group">
                    <label>Description:</label>
                    <textarea class="form-control" name="desc" placeholder="Enter Description"></textarea>
                    <span class="form-text text-muted">Please enter Description</span>
                </div>
                <div class="form-group">
                    <label>Amount:</label>
                    <input type="number" step="0.01" required class="form-control" name="amount" placeholder="Enter Amount" value="0">
                    <span class="form-text text-muted">Please enter Amount</span>
                </div>

                <div class="kt-form__actions kt-form__actions--right">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="update_row_model" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">Update Company Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="kt-form" enctype="multipart/form-data" id="update_rows">
                    <input type="hidden" name="statement_id">
                    <input type="hidden" name="source_id">
                    <input type="hidden" name="source_key">
                    <div class="form-group">
                        <label>Rider:</label>
                        <select required class="form-control bk-select2" name="rider_id_update" >
                            @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}">
                                {{ $rider->name }}
                            </option>     
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Month:</label>
                        <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month_update" placeholder="Enter Month" value="">
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
                        <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Month" value="">
                        @if ($errors->has('given_date'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{ $errors->first('given_date') }}
                                </strong>
                            </span>
                        @else
                            <span class="form-text text-muted">Please enter Month</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea class="form-control" name="desc" placeholder="Enter Description"></textarea>
                        <span class="form-text text-muted">Please enter Description</span>
                    </div>
    
                    <div class="form-group">
                        <label>Amount:</label>
                        <input type="number" step="0.01" required class="form-control" name="amount" placeholder="Enter Amount" value="0">
                        <span class="form-text text-muted">Please enter Amount</span>
                    </div>
    
                    <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
<div class="modal fade" id="rider_expense_bonus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <label>Select Rider:</label>
                        <select class="form-control bk-select2" name="rider_id" class="rider_selector" >
                            @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>      
                            @endforeach 
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <label>Date:</label>
                        <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                        @if ($errors->has('month'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{ $errors->first('month') }}
                                </strong>
                            </span>
                        @else
                            <span class="form-text text-muted">Please enter Month</span>
                        @endif
                    </div> --}}
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
<div class="modal fade" id="rider_expense_discipline" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">  
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="title_rider_expense">Add Discipline Fine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" enctype="multipart/form-data" id="discipline">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Rider:</label>
                        <select class="form-control bk-select2 kt-select2" name="rider_id" class="rider_selector" >
                            @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>      
                            @endforeach 
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <label>Date:</label>
                        <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month')) invalid-field @endif" name="month" placeholder="Enter Month" value="">
                        @if ($errors->has('month'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{ $errors->first('month') }}
                                </strong>
                            </span>
                        @else
                            <span class="form-text text-muted">Please enter Month</span>
                        @endif
                    </div> --}}
                    <div class="form-group">
                        <label>Kingriders Fine Month:</label>
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
                        <label>Description:</label>
                        <textarea required type="text" cols="12" rows="6" class="form-control" name="desc" placeholder="Enter Description" value=""></textarea>
                    </div>
                    <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-danger">Add Fine</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bills_image_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">  
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="title_rider_expense">Sim Bills Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" enctype="multipart/form-data" id="sim_image">
                <div class="modal-body">
                  <div class="sim_bills" style="display:grid;"></div>
                    {{-- <div class="kt-form__actions kt-form__actions--right">
                        <button type="submit" class="btn btn-danger">Add Fine</button>
                    </div> --}}
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="bills_detail_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">  
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="title_rider_expense">Fuel Expense Cash</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" enctype="multipart/form-data" id="bills_detail">
                <div class="modal-body">
                  <div class="bills_detail_html" style="display:grid;"></div>
                    <div class="form-group row">
                        <div class=" col-md-1 mr-4"><strong>#</strong></div>
                        <div class=" col-md-3 mr-4"><strong>Source</strong></div>
                        <div class=" col-md-4 mr-4"><strong>Date</strong></div>
                        <div class=" col-md-2 mr-4"><strong>Amount</strong></div>
                    </div>
                    <div class="form-group" id="bills_html">
                        
                    </div>
                    <div class="form-group row">
                        <div class=" col-md-1 mr-4"><strong></strong></div>
                        <div class=" col-md-3 mr-4"><strong></strong></div>
                        <div class=" col-md-4 mr-4"><strong>Total Amount</strong></div>
                        <div class=" col-md-2 mr-4" id="total_bill_amount_detail"><strong>0.00</strong></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="bill_update_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">  
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="title_rider_expense">Update bill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" enctype="multipart/form-data" id="bills_detail">
                <div class="modal-body">
                    <p class="bill_data"></p>
                  <div class="bills_update_html" style="display:grid;"></div>
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

    var regenerate_bill=function(_this,source,rider_id,month){
        var url = '{{route('bills.update.get')}}';
        var loading_html = '<div class="d-flex justify-content-center modal_loading" style="padding-bottom: 6rem !important;"><div class="loader"></div></div>';
        var _Modal = $('#bill_update_model');
        _Modal.find('.bills_update_html').html(loading_html);
        _Modal.modal('show');
        $.ajax({
            url : url,
            type : 'PUT',
            data: {
                source:source,
                rider_id:rider_id,
                month:month
            },
            success: function(data){
                // _Modal.modal('hide');
                
            },
            error: function(error){
                // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
                // _Modal.modal('hide');
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
         
         $("#rider_expense_bonus").on('shown.bs.modal', function(){
    var month=biketrack.getUrlParameter('r1d1');
    var _month=new Date(month).format("mmmm yyyy");
    if (month!="") { 
        $("#rider_expense_bonus [name='month']").attr("data-month", _month)
        biketrack.refresh_global()
    }
    });
    $("#rider_expense_discipline").on('shown.bs.modal', function(){
    var month=biketrack.getUrlParameter('r1d1');
    var _month=new Date(month).format("mmmm yyyy");
    if (month!="") { 
        $("#rider_expense_discipline [name='month']").attr("data-month", _month)
        biketrack.refresh_global()
    }
    });
    $('form#bonus').on('submit', function(e){   
            e.preventDefault();
            var _form = $(this);
            var _modal = _form.parents('.modal');
            var url ="{{ url('admin/rider/expense/bonus/') }}";
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

        $('form#discipline').on('submit', function(e){
            e.preventDefault();
            var _form = $(this);
            _form.find('[type="submit"]').prop('disabled',true);
            var _modal = _form.parents('.modal');
            var url ="{{ url('admin/rider/expense/discipline/') }}";
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
                    _form.find('[type="submit"]').prop('disabled',false);
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
                    _form.find('[type="submit"]').prop('disabled',false);
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
                success: function(data,textStatus,xhr){
                    console.log($(data));
                    
                    var _targetForm = $(data).find('form').wrap('<p/>').parent().html();
                    
                    
                    _quickViewModal.find('.modal-title').text(_self.text().trim());
                   
                    _quickViewModal.find('.modal-body').html(_targetForm);
                    _quickViewModal.find('[name="month"]').attr('data-month',selected_month);
                    _quickViewModal.find('[name="month_year"]').attr('data-month',selected_month);
                    $('script[data-ajax]').remove();
                    console.warn($(data).find('[data-ajax]'));
                    var $ajax_script = $(data).find('[data-ajax]');
                    if($ajax_script.length==0) $ajax_script = $(data).filter('[data-ajax]');

                    if($ajax_script.length==0) alert('Cannot find ajax script in this form');
                    $('body').append('<script data-ajax>'+$ajax_script.eq(0).html()+'<\/script>');

                    var rider_id = $('#gb_rider_id').val();
                    if(_quickViewModal.find('[name="rider_id"]').length){
                        _quickViewModal.find('[name="rider_id"]').val(rider_id).trigger('change.select2');
                    }
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
                            data: new FormData(_form[0]),
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(data){
                                console.log(data);
                                if (data.pm="commission_based") {
                                    if (data.bike_own=="self") {
                                        alert("Commision Based Rider");
                                    }
                                } 
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
                error: function(error,a,xhr){
                    console.log(error);
                    _quickViewModal.modal('hide');
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
            if(_SortBy=='select_month'){
                $('#select_month_custom').fadeIn('fast');
                var custom_month=$('[name="custom_select_month"]').val();
                var year=new Date(custom_month).format("yyyy");
                var month=new Date(custom_month).format("mm");
                start=new Date(year,month-1,1).format("yyyy-mm-dd");
                end=new Date(year,month,0).format("yyyy-mm-dd");
            }
            var _search = biketrack.getUrlParameter('search');
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
                sort_by: $('[name="sort_by"]:checked').val(),
                search:_search
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
                $("[name='custom_select_month']").trigger("change");
                return;
            }
            var _data = {
                range: {
                    start_date: start,
                    end_date: end
                },
                rider_id: $('[name="rider_id"]').val()
            };
            var _search = biketrack.getUrlParameter('search');

            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val(),
                search:_search
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
                rider_id: $('[name="rider_id"]').val()
            };
            var _search = biketrack.getUrlParameter('search');

            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val(),
                search:_search
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
                rider_id: $('[name="rider_id"]').val()
            };
            var _search = biketrack.getUrlParameter('search');

            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val(),
                search:_search
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) ;
            getData(url);
        }


        


        var getData = function(ranges){
            var rider_id=biketrack.getUrlParameter('rider_id');
            $('.rider__feid').text('');
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

                    var feid=response.feid;
                    $('.rider__feid').text(feid);
                },
                ajax: url,
                columns: [
                    { data: 'date', name: 'date' },            
                    { data: 'description', name: 'description' },
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

        var init_table=function(){
            var r1d1=biketrack.getUrlParameter('r1d1');
            var r1d2=biketrack.getUrlParameter('r1d2');
            var rider_id=biketrack.getUrlParameter('rider_id');
            var sort_by=biketrack.getUrlParameter('sort_by');
            console.log(r1d1, r1d2, rider_id, sort_by);
            
            if(r1d1!="" && r1d2!="" && rider_id!="" && sort_by!=""){
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
                $('[name="rider_id"],[name="rider_id_num"]').val(rider_id);
                $('[name="rider_id_num"]').trigger('change');
                return;
            }
            $('[name="sort_by"]:checked').trigger('change')
        }
        init_table();
        

    })
function BillsDetails(){
    var month=biketrack.getUrlParameter('r1d1');
    var rider_id=biketrack.getUrlParameter('rider_id');
    var _onlyMonth=new Date(month).format("yyyy-mm-dd");
    var _model = $('#bills_detail_model');
    $("#bills_html").append("");
    $("#total_bill_amount_detail").html("");
    _model.modal('show');
    var url ="{{ url('admin/rider/bills_details/ajax') }}"+ "/" + rider_id + "/" + _onlyMonth ;
    $.ajax({
        url : url,
        type : 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
            console.log(data)
            $("#bills_html").html("");
            var count=1;
            data.bills.fuel_cash.forEach(function(item,j){
                var given_date=new Date(item.given_date).format("mmmm dd,yyyy");
                var export_key = typeof item.export_key!=="undefined"?item.export_key+'-':'';
                var _row_bill_details= '<div class="row mt-5"><div class=" col-md-1 mr-4">'+(count++)+'</div>'+ 
                                       '<div class=" col-md-3 mr-4">'+export_key+item.source+'</div>'+ 
                                       '<div class=" col-md-4 mr-4">'+given_date+'</div>'+ 
                                       '<div class=" col-md-2 mr-4">'+item.amount+'</div></div>';                      
                $("#bills_html").append(_row_bill_details);
            });
            $("#total_bill_amount_detail").html("<strong>"+data.bills.fuel_cash_amt+"</strong>");
            if (data.bills.fuel_cash=='') {
                $("#bills_html").append("");
            }
        },
    });
}
function FineBike(rider_id,bike_fine_id,amount,month,given_date){
    console.log(month)
    var url = "{{ url('admin/accounts/fine/paid/Rider') }}" + "/" + rider_id +"/"+bike_fine_id+"/"+amount+"/"+month+"/"+given_date;
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

    function updateStatusBills(rider_id,month,type)
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

function editRows($this,id,model_class,model_id,rider_id,string,month){
    console.log(id);
    var _tr = $($this).parents('tr');
    var _row = table.row(_tr).data();
    var _model = $('#edit_row_model');
    var _source = _row.desc==null?_row.source:_row.desc;
    _model.find('[name="desc"]').val(_source);
    _model.find('[name="amount"]').val(_row.amount);
    _model.find('[name="statement_id"]').val(_row.id);
    
    _model.modal('show');

    _model.find('form').off('submit').on('submit', function(e){
        e.preventDefault();
        var url = "{{route('admin.accounts.edit_company')}}";
        var _form = $(this);
        var _submitBtn = _form.find('[type="submit"]');
        $.ajax({
            url : url,
            type : 'PUT',
            data:_form.serializeArray(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {            
                // $('.loading').show();
                _submitBtn.prop('disabled', true);
            },
            complete: function(){
                // $('.loading').hide();
                _submitBtn.prop('disabled', false);
                _model.modal('hide');
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
    });

}
function deleteRows(id,model_class,model_id,rider_id,string,month,year,source_id,source_key,given_date){
    // var url = "{{ url('admin/delete/accounts/rows') }}";
    var url = "{{ url('admin/send_notification/delete/rows') }}";
    console.log(url);
    swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete! Just wait for your senior response.",
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
                "year":year,
                "source_id":source_id,
                "source_key":source_key,
                "given_date":given_date,
                'statement_type':'company__accounts'
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
                    if(data.status==0){
                        //error returned
                        swal.fire({
                            position: 'center',
                            type: 'error',
                            title: 'Oops...',
                            text: data.msg,
                            showConfirmButton: true,
                        });
                        return;
                    }
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record Deleted notification sent to your senior successfully.',
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
function SimBillsImage(rider_id,month,type){
    var url = "{{ url('admin/sim/bill/image') }}" + "/" + rider_id + "/" + month + "/" + type ;
    console.log(url,true);
    $("#bills_image_model").modal("show");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'GET',
                success: function(data){
                    console.log(data);
                    $(".sim_bills").html("");
                    data.sim_trans_id.forEach(function(i,j){
                        console.log(i);
                       var image='<div style="text-align: center;margin: 2px 0px 20px 0px;"><img class="profile-logo img img-thumbnail" src="'+i+'" alt="image"></div>'
                        if (i!=null&&i!='') {
                         $(".sim_bills").append(image);   
                        }
                    });
                    
                    table.ajax.reload(null, false);
                    table_bills.ajax.reload(null, false);
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to Show.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });

}
function UpdateRows($this,id,model_class,model_id,rider_id,string,month,year,source_id,source_key,given_date){
    
    var _tr = $($this).parents('tr');
    var _row = table.row(_tr).data();
    var _model = $('#update_row_model');
    var r1d1=biketrack.getUrlParameter('r1d1');
    var rider_id_update=biketrack.getUrlParameter('rider_id');
    var _month=new Date(_row.month).format("mmmm yyyy");
    var _givenDate=new Date(_row.given_date).format("mm dd, yyyy");
    var _source = _row.desc==null?_row.source:_row.desc;
    _model.find('#update_rows [name="desc"]').val(_source);
    _model.find('#update_rows [name="amount"]').val(_row.amount);
    _model.find('#update_rows [name="statement_id"]').val(_row.id);
    _model.find('#update_rows [name="source_id"]').val(source_id);
    _model.find('#update_rows [name="source_key"]').val(source_key);
    _model.find('#update_rows [name="month_update"]').attr("data-month",_month);
    _model.find('#update_rows [name="given_date"]').attr("data-month",_givenDate);
    _model.find('#update_rows [name="rider_id_update"]').val(rider_id_update).trigger("change.select2");
    biketrack.refresh_global();
    var bk_rider_id=rider_id;
    var bk_month=month;
    var bk_year=year;
    _model.modal('show');

    _model.find('form').off('submit').on('submit', function(e){
        e.preventDefault();
        // var url ="{{ url('admin/accounts/company/update_row/') }}"+ "/" + bk_rider_id + "/" + bk_month + "/" + bk_year ;
        var url ="{{ url('admin/accounts/send_notification/update_row/') }}" ;
        var data=new FormData(this);
        data.append('rider_id',bk_rider_id);
        data.append('bk_month',bk_month);
        data.append('bk_year',bk_year);
        data.append('source',model_id);
        data.append('statement_type','company__accounts');
        var _form = $(this);
        var _submitBtn = _form.find('[type="submit"]');
        $.ajax({
            url : url,
            type : 'POST',
            data:data,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {            
                // $('.loading').show();
                _submitBtn.prop('disabled', true);
            },
            complete: function(){
                // $('.loading').hide();
                _submitBtn.prop('disabled', false);
                _model.modal('hide');
            },
            success: function(data){
                if(data.status==0){
                    //error returned
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: data.msg,
                        showConfirmButton: true,
                    });
                    return;
                }
                swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'Record update request sent successfully.',
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

}
</script> 
@endsection