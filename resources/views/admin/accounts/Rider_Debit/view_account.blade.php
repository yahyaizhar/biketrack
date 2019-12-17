@extends('admin.layouts.app')
@section('head')
<style>
    .fields_wrapper{
        display: none;
    }
    .fields_wrapper--show{
        display: block;
    }
    .table th, .table td{
        padding:0 !important;
    }
    .kt-header.kt-grid__item.kt-header--fixed{
        display: none !important;
    } 
    .kt-grid__item.kt-grid__item--fluid.kt-grid.kt-grid--hor.mt-minus-60 {
    margin-top: -140px;
    }   
    td .print_class{
        font-size:12px;
    }
</style>
@endsection
@section('main-content') 
{{-- <ul class="kt-sticky-toolbar" style="margin-top: 0px !important;top:6% !important;">
	<li class="kt-sticky-toolbar__item kt-sticky-toolbar__item--demo-toggle" id="kt_demo_panel_toggle" data-toggle="kt-tooltip" title="" data-placement="right" data-original-title="Check out more demos">
		<a class="">Admin</a>
	</li>
</ul> --}}
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
                        <select class="form-control kt-select2" name="rider_id" class="rider_selector" >
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
                                    <input type="radio" id="" data-start="{{Carbon\Carbon::now()->subMonths(1)->startOfMonth()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->subMonths(1)->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="last_month" >{{carbon\carbon::now()->subMonths(1)->format('F')}}
                                <span></span>
                            </label>
                            <label class="kt-radio">
                                <input type="radio" id="" data-start="{{Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}" name="sort_by" value="month" checked> {{carbon\carbon::now()->format('F')}}
                                <span></span>
                            </label>  
                            {{-- <label class="kt-radio">
                                <input type="radio" data-start="{{Carbon\Carbon::now()->startOfYear()->format('Y-m-d')}}" data-end="{{Carbon\Carbon::now()->endOfYear()->format('Y-m-d')}}" name="sort_by" value="year"> This Year
                                <span></span>
                            </label> --}}
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
                <div class="kt-widget24" style="padding:5px !important;padding-left:25px !important;">
                    <div class="kt-widget24__details" >
                        <a href="https://kingridersapp.solutionwin.net/admin/livemap" class="kt-widget24__info">
                            <h4 class="kt-widget24__title">
                                Closing Balance:  <span style="font-size: 1.2rem !important;" class="kt-widget24__stats kt-font-success" id="closing_balance"> </span>
                            </h4>
                            
                        </a>
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
                    Rider Account
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar"> 
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                         {{-- <a href="" class="btn btn-primary btn-elevate btn-icon-sm" data-toggle="modal" data-target="#cash_pay_modal" >
                            <i class="la la-money"></i>
                             Bike Rent
                        </a>
                        &nbsp; --}}
                        {{-- <a href="" class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#cash_pay_modal" >
                            <i class="la la-money"></i>
                             Mobile Charges
                        </a>
                        &nbsp; --}}
                        {{-- <a href="" class="btn btn-info btn-elevate btn-icon-sm" data-toggle="modal" data-target="#cash_pay_modal" >
                            <i class="la la-money"></i>
                             Bike Allowns
                        </a>  
                        &nbsp; --}}
                         &nbsp;
                        <a style="" href="" class="btn btn-info btn-elevate btn-icon-sm" data-toggle="modal" data-target="#cash_paid" >
                            <i class="la la-money"></i>
                                 Pay Cash To Rider
                        </a>
                        &nbsp;
                        <a style="" href="" class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#advance" >
                            <i class="la la-money"></i>
                                Advance
                        </a>
                         &nbsp;
                         <a style="" href="" class="btn btn-danger btn-elevate btn-icon-sm" data-toggle="modal" data-target="#cash_pay_debit" >
                            <i class="la la-money"></i>
                                Receive Amount From Rider
                        </a>
                        {{-- &nbsp; --}}
                        {{-- <a style="" href="" class="btn btn-success btn-elevate btn-icon-sm" data-toggle="modal" data-target="#cash_pay_credit" >
                            <i class="la la-money"></i>
                                Loan
                        </a>
                        &nbsp; --}}
                        {{-- <a href="{{ route('admin.accounts.rider_expense_get') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>  --}}
                    </div>
                </div>
            </div> 
        </div>
        {{-- pay cash --}}
        <div class="modal fade" id="cash_paid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Cash Paid to Rider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" enctype="multipart/form-data" id="cash_paid">
                    <div class="modal-body">
                        <input type="hidden" name="cash_rider_id">
                        {{-- <div class="form-group">
                            <label>Rider Cash Paid Date:</label>
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
                            <label>Cash Paid Month:</label>
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
                            <textarea required class="form-control @if($errors->has('desc')) invalid-field @endif" name="desc" cols="3" rows="5"></textarea>
                            @if ($errors->has('desc'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('desc')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Pay Cash To Rider</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <div class="modal fade" id="cash_pay_debit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Receiveable From Rider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" enctype="multipart/form-data" id="cash_pay_dr">
                    <div class="modal-body">
                        <input type="hidden" name="cash_rider_id">
                        {{-- <div class="form-group">
                            <label>Month:</label>
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
                            <label>Receive Loan Month:</label>
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
                            <textarea required class="form-control @if($errors->has('desc')) invalid-field @endif" name="desc" cols="3" rows="5"></textarea>
                            @if ($errors->has('desc'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('desc')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-danger">Receive Loan</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <div class="modal fade" id="cash_pay_credit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Payable To Rider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" enctype="multipart/form-data" id="cash_pay_cr">
                    <div class="modal-body">
                        <input type="hidden" name="cash_rider_id">
                        {{-- <div class="form-group">
                            <label>Rider Cash Paid Date:</label>
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
                            <label>Loan Month:</label>
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
                            <textarea required class="form-control @if($errors->has('desc')) invalid-field @endif" name="desc" cols="3" rows="5"></textarea>
                            @if ($errors->has('desc'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('desc')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-warning">Loan</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
        {{-- end pay cash --}}
        <div class="modal fade" id="remaining_pay_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Pay Salary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" enctype="multipart/form-data" id="remaining_salary">
                    <div class="modal-body">
                        <input type="hidden" name="account_id" value="">
                        {{-- <div class="form-group">
                            <label>Rider Date Paid:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('month_paid_rider')) invalid-field @endif" name="month_paid_rider" placeholder="Enter Month" value="">
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
                            <label>Salary Month:</label>
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
                            {{-- <div class="form-group">
                                    <label>Total Salary:</label>
                                    <input readonly type="text" class="form-control @if($errors->has('net_salary')) invalid-field @endif" name="net_salary" value="">
                                    @if ($errors->has('net_salary'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('net_salary') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Net salary</span>
                                    @endif 
                                </div> --}}
                            <div class="form-group">
                                <label>Available Balance:</label>
                            <input readonly type="text" class="form-control @if($errors->has('gross_salary')) invalid-field @endif" name="gross_salary" value="">
                                @if ($errors->has('gross_salary'))
                                    <span class="invalid-response" role="alert">
                                        <strong>
                                            {{ $errors->first('gross_salary') }}
                                        </strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Gross salary</span>
                                @endif
                                    
                            </div>
                                <div class="form-group">
                                    <label>Amount To Pay:</label>
                                    <input type="text" class="form-control @if($errors->has('recieved_salary')) invalid-field @endif" name="recieved_salary" value="">
                                    @if ($errors->has('recieved_salary'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('recieved_salary') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Recieved salary</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Salary Remaining:</label>
                                    <input readonly type="text" class="form-control @if($errors->has('remaining_salary')) invalid-field @endif" name="remaining_salary" value="">
                                    @if ($errors->has('remaining_salary'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('remaining_salary') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Remaining salary</span>
                                    @endif
                                </div>
                                
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Pay Salary</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <div class="modal fade" id="advance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Advance Give to Rider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="kt-form" enctype="multipart/form-data" id="advance_paid">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="payment_status" value="pending">
                            <label>Advance & Return  Type:</label>
                            <select required  class="form-control @if($errors->has('type')) invalid-field @endif kt-select2-general" name="type">
                                <option value="advance">Advance</option>
                                <option value="return">Return</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Rider:</label>
                            <select required class="form-control kt-select2-general" name="rider_id" >
                                @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}">
                                    {{ $rider->name }}
                                </option>     
                                @endforeach 
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Taken Advance Month:</label>
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
                            <label>Given Advance Date:</label>
                            <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" required readonly class="month_picker form-control @if($errors->has('given_date')) invalid-field @endif" name="given_date" placeholder="Enter Given Date" value="">
                            @if ($errors->has('given_date'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('given_date') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter Given Advance Date</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Amount:</label>
                            <input required type="number" class="form-control @if($errors->has('amount')) invalid-field @endif" name="amount" placeholder="Enter Amount" value="">
                            @if ($errors->has('amount'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('amount')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Pay Advance To Rider</button>
                        </div>
                    </div>
                </form>
            </div>
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
                        <th>Debit To Company Account</th>
                        <th>Cash Paid</th> 
                        <th>Running Balance</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
            </table>
            <!--end: Datatable -->
            <div>
                <div>
                    <button style="float:right;margin-right: 10px;" class="btn btn-warning btn-elevate btn-icon-sm" id="for_print" type="button" onclick="printJS('print_slip_for_rider', 'html')">
                        Print Salary Slip
                    </button>
                </div>
                <div>
                    <button style="float:right;margin-right: 10px;" class="btn btn-info btn-elevate btn-icon-sm" id="for_edit" type="button">
                        Edit Salary Slip
                    </button>
                </div>
                <div>
                    <button style="float:right;margin-right: 10px;" class="btn btn-info btn-elevate btn-icon-sm" id="for_days_payouts" type="button">
                        Rider Payout detail
                    </button>
                </div>
                <div>
                    <button style="float:right;margin-right: 10px;" data-toggle="modal" class="btn btn-success btn-elevate btn-icon-sm" id="to_pay" type="button">
                        <i class="fa fa-dollar-sign"></i> Pay Salary
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- rider payouts by days --}}
<div class="attendance__msg-container">
    <div class="attendance__msg"></div>
    <div class="attendance__sync-data">

    </div>
</div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid days_payout" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Days Payouts
                </h3>
            </div>
            {{-- <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <div class="mt-2">
                            <div class="kt-portlet__head-actions" id="select_day">
                                <label for="dr1">Select Weekly Off Day</label>
                                <select class="form-control bk-select2" name="custom_select_Day" value="select Day">
                                    <option >Select Day</option>
                                    <option value="Monday">Monday</option>   
                                    <option value="Tuesday">Tuesday</option>   
                                    <option value="Wednesday">Wednesday</option>   
                                    <option value="Thursday">Thursday</option>   
                                    <option value="Friday">Friday</option>   
                                    <option value="Saturday">Saturday</option>   
                                    <option value="Sunday">Sunday</option>   
                                </select> 
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="kt-portlet__body" id="rider_days_detail">
                <style type="text/css">
                    table {
                        border:solid #000 !important;
                        border-width:1px 0 0 1px !important;
                    }
                    th, td {
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
        <button style="float:right;margin-right: 10px;" onclick="print_data()"  class="btn btn-success btn-elevate btn-icon-sm" id="sync_data" type="button">
                Print Data
        </button>
    </div>
</div>
{{-- end rider payouts by days --}}
{{-- salary slip --}}
<div>
<div class="print_slip_editable" style="">
<div style="display:grid;padding: 15px 50px 0px 50px;" id="print_slip_for_rider">
    
    <table style="">
        <tr><th style="border:1px solid #dddd;background-color:#73acac69;text-align:center;">SALARY SLIP</th></tr>
        <tr><th class="month_year" style="border:1px solid #dddd;text-align:center;"></th></tr>
    </table>
    <table class="print_class" style=" margin-top: 1px;">
        <tr>
            <th style="border:1px solid #dddd;width:25%;text-align:left;">NAME</th>
            <td class="rider_name" style="border:1px solid #dddd;width:25%;text-align:left;"></td>
            <th style="border:1px solid #dddd;width:25%;text-align:left;">Designation:</th>
            <td style="border:1px solid #dddd;width:25%;text-align:left;"></td>
        </tr>
        <tr>
            <th style="border:1px solid #dddd;width:25%;text-align:left;">EMPLOYEE ID:</th>
            <td class="employee_id" style="border:1px solid #dddd;width:25%;text-align:left;"></td>
            <th style="border:1px solid #dddd;width:25%;text-align:left;">WORKPLACE:</th>
            <td style="border:1px solid #dddd;width:25%;text-align:left;"></td>
        </tr>
        <tr>
            <th style="border:1px solid #dddd;width:25%;text-align:left;">DATE OF JOINING:</th>
            <td class="today_date" style="border:1px solid #dddd;width:25%;text-align:left;"></td>
            <th style="border:1px solid #dddd;width:25%;text-align:left;"></th>
            <td style="border:1px solid #dddd;width:25%;text-align:left;"></td>
        </tr>
    </table>

    <table style=" margin-top: 1px;">
        <tr>
            <th style="border:1px solid #dddd;width:50%;text-align:center;">DESCRIPTION</th>
            <th style="border:1px solid #dddd;width:25%;text-align:center;">EARNINGS</th>
            <th style="border:1px solid #dddd;width:25%;text-align:center;">DEDUCTIONS:</th>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">Previous Balance</td>
            <td contenteditable='true' class="previous_balance" style="border:1px solid #dddd;width:25%;text-align:end;"></td> 
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">BASIC SALARY (<strong>Trips:</strong><span class="total_trips"></span>) (<strong>Hours:</strong><span class="total_hours"></span>) (<strong>Extra Trips:</strong><span class="extra_trips"></span>)</td>
            <td contenteditable='true' class="salary" style="border:1px solid #dddd;width:25%;text-align:end;"></td> 
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">NCW ALLOWANCE</td>
            <td contenteditable='true' class="ncw" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">CUSTOMER TIP</td>
            <td contenteditable='true' class="tip" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">BIKE ALLOWANCE</td>
            <td contenteditable='true' class="bike_allowns" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">BONES</td>
            <td contenteditable='true' class="bones" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">Bike Fine</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="bike_fine" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">ADVANCE</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="advance" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">SALIK PLANTI</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="salik" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">SIM PLANTI</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="sim" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">ZOMATO PLANTI</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="zomato" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">DC DEDUCTION</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="dc" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">MCDONALD DEDUCTION</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="macdonald" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">RTA FINE</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="rta" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">MOBILE EMI</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="mobile" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">DISPLAN FINE</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="discipline" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">MICS CHARGES</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="mics" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">Others</td>
            <td style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td contenteditable='true' class="cash_paid" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">TOTAL</td>
            <td class="total_cr" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
            <td class="total_dr" style="border:1px solid #dddd;width:25%;text-align:end;"></td>
        </tr>
        
    </table>
    <table style="">
        <tr>
            <td class="payment_date" style="border:1px solid #dddd;width:50%;text-align:left;"></td>
            <td style="border:1px solid #dddd;width:50%;text-align:center;background-color:#73acac69;">NET PAY</td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:center;"></td>
            <td class="net_pay" style="border:1px solid #dddd;width:50%;text-align:center;background-color:#73acac69;"></td>
        </tr>
        {{-- <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">Cash Paid</td>
            <td contenteditable='true' class="cash_pay" style="border:1px solid #dddd;width:25%;text-align:center;background-color:#73acac69;">0</td>
        </tr>
        <tr>
            <td style="border:1px solid #dddd;width:50%;text-align:left;">Remaining Pay</td>
            <td class="remaining_pay" style="border:1px solid #dddd;width:50%;text-align:center;background-color:#73acac69;">0</td>
        </tr> --}}
    </table>
    <div style=""> 
        <p style="font-size:12px;line-height: 14px;"><strong>Note: </strong>MR <span id="rider_id_1"></span> received <span id="total_net_pay"></span> from King Riders Delivery Services LLC, and MR <span id="rider_id_2"></span> no is not valid for any kind of Gratuity, yearly tickets or any other expenses other than the salary.
        </p>
    </div>
    <div style=" margin-top: 1px;">  
        <p><strong>Signature:</strong>________________________</p>
    </div>
    <div style=" margin-top: 1px;"> 
        <p><strong>Thumb:</strong>__________________________</p>
    </div>
    <div style="text-align:end;"> 
        <p style="margin-bottom: 3px;"><strong>KING RIDERS DELIVERY SERVICES LLC</strong></p>
        <p style="margin-bottom: 3px;"><Strong>ACCOUNTANT</Strong></p>
        <p style="margin-bottom: 3px;"><strong>DANISH MUNIR</strong></p>
    </div>
</div>
</div>
    
</div>
{{-- end salary slip --}}
<input type="hidden" name="absent_days">
<input type="hidden" name="weekly_off">
<input type="hidden" name="weekly_off_day">
<input type="hidden" name="extra_day">
@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>

 {{-- <script src="{{ asset('dashboard/assets/js/print.js') }}" type="text/javascript"></script> --}}
{{-- <link href="{{ asset('dashboard/assets/css/print.min.csss') }}" rel="stylesheet"> --}}

<script src=" https://printjs-4de6.kxcdn.com/print.min.js" type="text/javascript"></script>
<link href=" https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">

<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('js/dataTables.cellEdit.js') }}" type="text/javascript"></script>
<script>
  
    $("#advance").on('shown.bs.modal', function(){
        var month=biketrack.getUrlParameter('r1d1');
        var _month=new Date(month).format("mmmm yyyy");
        if (month!="") { 
            $("#advance [name='month']").attr("data-month", _month)
            biketrack.refresh_global()
        }
    });
    $("#cash_paid").on('shown.bs.modal', function(){
        var rider_id=biketrack.getUrlParameter('rider_id');
        $('#cash_paid [name="cash_rider_id"]').val(rider_id);
        var month=biketrack.getUrlParameter('r1d1');
        var _month=new Date(month).format("mmmm yyyy");
        if (month!="") { 
            $("#cash_paid [name='month']").attr("data-month", _month)
            biketrack.refresh_global()
        }
    });
    $("#cash_pay_debit").on('shown.bs.modal', function(){
    var month=biketrack.getUrlParameter('r1d1');
    var _month=new Date(month).format("mmmm yyyy");
    if (month!="") { 
        $("#cash_pay_debit [name='month']").attr("data-month", _month)
        biketrack.refresh_global()
    }
    });
    $("#cash_pay_credit").on('shown.bs.modal', function(){
    var month=biketrack.getUrlParameter('r1d1');
    var _month=new Date(month).format("mmmm yyyy");
    if (month!="") { 
        $("#cash_pay_credit [name='month']").attr("data-month", _month)
        biketrack.refresh_global()
    }
    });
    $("#remaining_pay_modal").on('shown.bs.modal', function(){
    var month=biketrack.getUrlParameter('r1d1');
    var _month=new Date(month).format("mmmm yyyy");
    if (month!="") { 
        $("#remaining_pay_modal [name='month']").attr("data-month", _month)
        biketrack.refresh_global()
    }
    });
    
   
        $(".days_payout").hide();
        $('.print_slip_editable').hide();
        $("#for_days_payouts").on("click",function(){
        $(".days_payout").show();
        $('.print_slip_editable').hide();
        var month=biketrack.getUrlParameter('r1d1');
        var rider_id=biketrack.getUrlParameter('rider_id');
         var _Url = "{{url('admin/rider/hours/trips/details')}}"+"/"+month+"/"+rider_id;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : _Url,
                type : 'GET',
                beforeSend: function() {            
                    $('.bk_loading').show();
                },
                complete: function(){
                    $('.bk_loading').hide();
                },
                success: function(data){
                    var _data=data.data;
                    console.log(_data);
                    if(!_data) return; 
                    var time_sheet=_data.time_sheet;
                    var  rows='';
                    var calculated_trips=0;
                    var calculated_hours=0;
                    
                    var total_absents=_data.absents_count;
                    var extra_day=_data.extra_day;
                    time_sheet.forEach(function(item,j){
                        var trips=parseFloat(item.trips)||0;
                        var login_hours=parseFloat(item.login_hours)||0;
                        var date=new Date(item.date).format("dd mmm yyyy dddd");
                        if (login_hours>11) {
                            login_hours=11;
                        }
                        
                        
                        var off__status=item.off_days_status;
                        
                        var status='';
                        switch (off__status) {
                            case 'weeklyoff':
                                status='<div style="color:green;">Weekly Off</div>';
                                break;
                            case 'absent':
                                status='<div style="color:red;">Absent</div>';
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
                       rows+='<tr><td style=" width: 25%;">'+date+'</td><td style=" width: 25%;text-align: center;">'+trips+'</td><td style=" width: 25%;text-align: center;">'+login_hours+'</td> <td class="absents" style=" width: 25%;text-align: center;">'+status+'</td></tr>';
                    });
                    $("[name='absent_days']").val(total_absents);
                    $('[name="extra_day"]').val(extra_day);
                    $(".rider_days_detail tbody").html(rows); 
                    $(".rider_days_detail tfoot").html('<tr><th>Total</th><th>'+calculated_trips.toFixed(2)+'</th><th>'+calculated_hours.toFixed(2)+'</th></tr>');
                  var _name =  $('[name="rider_id"]:eq(0) option:selected').text().trim();
                  $('.custom_rider_id').text('Rider id: '+_data.rider_id);
                  $('.custom_rider_name').text('Rider name: '+_name);


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
        $('[name="custom_select_Day"]').on("change",function(){
            var _day=$(this).val();
            console.log(_day);
            var month=biketrack.getUrlParameter('r1d1');
            var rider_id=biketrack.getUrlParameter('rider_id');
            var _Url = "{{url('admin/rider/week/days/off/status')}}"+"/"+month+"/"+rider_id+"/"+_day;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : _Url,
                type : 'GET',
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record Entered successfully.',
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
        });
        $("#for_print").on("click",function(){
            $('.print_slip_editable').hide();
        });
        $("#for_edit").on("click",function(){
            $('.print_slip_editable').show();
            $(".days_payout").hide();
        });
        $('form#advance_paid').on('submit', function(e){
            e.preventDefault();
            var _form = $(this);
            var url = '{{route('admin.AR_store')}}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                data: _form.serializeArray(),
                success: function(data){
                    $('#advance').modal('hide');
                    // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
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
        $('form#cash_paid').on('submit', function(e){
            e.preventDefault();
            var _form = $(this);
            // var _cta = _form.find('[type="submit"]');
            // _cta.prop('disabled', true).addClass('btn-icon').html('<i class="fa flaticon2-refresh fa-spin"></i>');
            var url = '{{route('admin.cash_paid')}}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                data: _form.serializeArray(),
                success: function(data){
                    $('#cash_paid').modal('hide');
                    // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
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
        $('form#cash_pay_cr').on('submit', function(e){
            e.preventDefault();
            var _form = $(this);
            // var _cta = _form.find('[type="submit"]');
            // _cta.prop('disabled', true).addClass('btn-icon').html('<i class="fa flaticon2-refresh fa-spin"></i>');
            var url = '{{route('admin.cash_credit_rider')}}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                data: _form.serializeArray(),
                success: function(data){
                    $('#cash_pay_credit').modal('hide');
                    // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
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
        $('form#cash_pay_dr').on('submit', function(e){
            e.preventDefault();
            var _form = $(this);
            // var _cta = _form.find('[type="submit"]');
            // _cta.prop('disabled', true).addClass('btn-icon').html('<i class="fa flaticon2-refresh fa-spin"></i>');
            var url = '{{route('admin.cash_debit_rider')}}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                data: _form.serializeArray(),
                success: function(data){
                    $('#cash_pay_debit').modal('hide');
                    // _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
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
        

     $('#month_picker').each(function(){
        var _initDate = "{{Carbon\Carbon::now()->format('M Y')}}";
        
        $(this).fdatepicker({ 
            format: 'MM yyyy', 
            // initialDate: 'July 2019',
            startView:3,
            minView:3,
            maxView:4
        });
        $(this).fdatepicker('update', new Date(_initDate));
        
        
    });
    var table;
    $(function(){
        
        $('.kt-select2').select2({
            placeholder: "Select a rider",
            width:'100%'    
        });
        
        $('form#remaining_salary').on('submit', function(e){
            e.preventDefault();
            $("to_pay").hide();
            var _form = $(this);
            var _modal = _form.parents('.modal');
            var _cta = _form.find('[type="submit"]');
            _cta.prop('disabled', true).addClass('btn-icon').html('<i class="fa flaticon2-refresh fa-spin"></i>');
            var url = '{{route('admin.accounts.rider_remaining_salary_add')}}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                data: _form.serializeArray(),
                success: function(data){
                    _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
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
                    _cta.prop('disabled', false).removeClass('btn-icon').html('Submit');
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
        $('[name="rider_id"] , [name="rider_id_num"]').on('change', function(){
            var _riderId = $(this).val();
            $('[name="cash_rider_id"]').val(_riderId);
            var _SE = $('[name="sort_by"]:checked');
            var _SortBy = _SE.val();
            var start = _SE.attr('data-start'),
                end = _SE.attr('data-end');
            $('#custom_range,#select_month_custom').fadeOut('fast');
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
            var url = "{{ url('admin/accounts/rider/account/') }}"+"/"+JSON.stringify(_data) ;
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
            var url = "{{ url('admin/accounts/rider/account/') }}"+"/"+JSON.stringify(_data) ;
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
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = "{{ url('admin/accounts/rider/account/') }}"+"/"+JSON.stringify(_data) ;
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
            var data = {
                r1d1:_data.range.start_date, 
                r1d2:_data.range.end_date,
                rider_id: $('[name="rider_id"]').val(),
                sort_by: $('[name="sort_by"]:checked').val()
            }
            
            console.log(data);
            biketrack.updateURL(data);
            var url = JSON.stringify(_data) 
            getData(url);
            console.log(url);
        }


        


        var getData = function(url){
            var rider_id=biketrack.getUrlParameter('rider_id');
            $('[name="rider_id"], [name="rider_id_num"]').val(rider_id).trigger("change.select2");
            console.warn(url)
            table = $('#data-table').DataTable({
                lengthMenu: [[-1], ["All"]],
                destroy: true,
                ordering: false,
                processing: true,
                serverSide: true,
                'language': { 
                    'loadingRecords': '&nbsp;',
                    'processing': $('.loading').show()
                },
                drawCallback:function(data){
                    console.log(data);
                    var response = table.ajax.json();
                    console.log(response);
                    var _ClosingBalance = 0;
                    if(response && typeof response.closing_balance !== "undefined")_ClosingBalance = response.closing_balance;
                    $('.previous_balance').text(response.closing_balance_prev);
                    $('.rider_name').html(response.rider);
                    $('.month_year').html(response.month_year);
                    $('.today_date').html(response.today_date);
                    $('.employee_id').html('KR-'+response.employee_id);
                    $('.payment_date').html('PAYMENT DATE: '+response.payment_date);
                    $('.salary').html(response.salary);
                    $('.total_trips').html(response.trips);
                    $('.total_hours').html(response.hours);
                    $('.extra_trips').html(response.extra_trips);
                    $('.ncw').html(response.ncw);
                    $('.bike_allowns').html(response.bike_allowns);
                    $('.tip').html(response.tip);
                    $('.bones').html(response.bones);


                    $('.bike_fine').html(response.bike_fine);
                    $('.advance').html(response.advance);
                    $('.salik').html(response.salik);
                    $('.sim').html(response.sim);
                    $('.macdonald').html(response.macdonald);
                    $('.dc').html(response.dc);
                    $('.rta').html(response.rta);
                    $('.discipline').html(response.dicipline);
                    $('.mobile').html(response.mobile);
                    $('.zomato').html(response.denial_penalty);
                    $('.mics').html(response.mics);
                    $('.cash_paid').html(response.cash_paid);
                    

                    var total_cr=parseFloat(response.closing_balance_prev)+parseFloat(response.salary)+parseFloat(response.ncw)+parseFloat(response.bike_allowns)+parseFloat(response.tip)+parseFloat(response.bones);
                    var total_dr=parseFloat(response.cash_paid)+parseFloat(response.bike_fine)+parseFloat(response.mics)+parseFloat(response.denial_penalty)+parseFloat(response.dicipline)+parseFloat(response.mobile)+parseFloat(response.rta)+parseFloat(response.advance)+parseFloat(response.salik)+parseFloat(response.sim)+parseFloat(response.dc)+parseFloat(response.macdonald);
                    var net_pay=(total_cr-total_dr).toFixed(2);
                    $('.remaining_pay').text(_ClosingBalance);
                    $('.total_cr').html(total_cr);
                    $('.total_dr').html(total_dr);
                    $('.net_pay').html(net_pay);
                    $('#total_net_pay').html(net_pay);
                    $('#rider_id_1').html(response.rider);
                    $('#rider_id_2').html(response.rider);
                   var onclick_event_of_pay=$("#getting_val").attr('onclick')||"";
                    if (onclick_event_of_pay!=="") {
                        $("#to_pay").show();
                        $("#to_pay").attr("onclick",$("#getting_val").attr('onclick'));
                        $("#to_pay").attr("data-target",$("#getting_val").attr('data-target'));
                    }
                    else{
                        $("#to_pay").hide();
                    }
                    
                    
                },
                ajax: url,
                columns: [
                    { data: 'date', name: 'date' },            
                    { data: 'desc', name: 'desc' },
                    { data: 'cr', name: 'cr' },
                    { data: 'dr', name: 'dr' },
                    { data: 'cash_paid', name: 'cash_paid' },
                    { data: 'balance', name: 'balance' },
                    // { data: 'action', name: 'action' },
                    
                ],
                responsive:true,
                order: [0, 'asc'],
            });

            table.MakeCellsEditable("destroy"); 
            table.MakeCellsEditable({
                "onUpdate": InlineEdit_CallBack,
                "onValidate": function(updatedCell, updatedRow, newValue){
                    var __data = updatedRow.data();
                    console.warn('__data', __data);
                    return true;
                },
                "allowNulls": {
                    "errorClass": 'error'
                },
                "columns": [1,2,3,4],
                "inputCss":'form-control',
                "dont_apply_if_null": "action", // check the field, if null, then will not make the cell editable (custom work - on public\js\dataTables.cellEdit.js)
                "inputTypes": [
                    {
                        "column":2, 
                        "type":"number-confirm", 
                        "options":null 
                    }
                ]
            });
        }

        function InlineEdit_CallBack (updatedCell, updatedRow, oldValue) {
            console.log(updatedRow.data());
            return;
            var __data = updatedRow.data();
            var _filterMonth = new Date(Date.now()).format("mmmm yyyy");
            if($('#month_picker').val()!==""){
                _filterMonth =$('#month_picker').val();
            }
                // if(__data.month && __data.month !== ""){
                //     _filterMonth = __data.month;
                // }
            __data.filterMonth=new Date(_filterMonth).format('yyyy-mm-dd');
            __data.status=$(__data.status).text().toLowerCase();
            var _data = {
                action: "edit",
                data: __data
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('SimTransaction.edit_sim_inline')}}",
                data: _data,
                method: "POST"
            }).done(function(data){
                console.log(data);
            }).fail(function(xhr, status, error){
                console.log(xhr);
                console.log(error);
                console.log(status);
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
    function remaining_pay($rider_id, account_id){
        var r1d1=biketrack.getUrlParameter('r1d1');
        $('#remaining_salary [name="month_paid_rider"]').fdatepicker('update', new Date(r1d1));
        _month = new Date(r1d1).format('yyyy-mm-dd');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{url('admin/accounts/company/debits/get_salary_deduction/')}}"+'/'+_month+'/'+$rider_id,
                method: "GET"
            })
            .done(function(data) {  
                console.log(data);
                $('#remaining_salary [name="account_id"]').val(account_id);
                $('#remaining_salary [name="recieved_salary"]').off('change input').on('change input', function(){
                    var _gross_salary = parseFloat($('#remaining_salary [name="gross_salary"]').val().trim());
                    var _recieved_salary = parseFloat($(this).val().trim());
                    $('#remaining_salary [name="remaining_salary"]').val(_recieved_salary-_gross_salary);
                });
                $('#remaining_salary [name="is_paid"]').val(data.is_paid);
                $('#remaining_salary [name="gross_salary"], #remaining_salary [name="recieved_salary"]').val(data.gross_salary).trigger('change');
                $('#remaining_salary [name="net_salary"]').val(data.net_salary).trigger('change');
                $('#remaining_salary [name="total_deduction"]').val(data.total_deduction);
                $('#remaining_salary [name="total_salary"]').val(data.total_salary);
                $('#remaining_salary [name="total_bonus"]').val(data.total_bonus); 
                var is_paid=data.is_paid; 
                if (is_paid) {
                    $('#remaining_salary [type="submit"]').html("The Rider has already paid").prop("disabled",true);
                }else{
                    $('#remaining_salary [type="submit"]').html("Submit").prop("disabled",false);
                }
                

            });
            
        // $('#remaining_salary [name="remaining_salary"]').val(0);
        
        // $('#remaining_salary [name="account_id"]').val(account_id);
        // $('#remaining_salary [name="recieved_salary"]').off('change input').on('change input', function(){
        //     var _gross_salary = parseFloat($('#remaining_salary [name="gross_salary"]').val().trim());
        //     var _recieved_salary = parseFloat($(this).val().trim());
        //     $('#remaining_salary [name="remaining_salary"]').val(_recieved_salary-_gross_salary);
        // });

        // $('#remaining_salary [name="net_salary"]').val(total_S);
        // $('#remaining_salary [name="gross_salary"]').val(total_G);
        // $('#remaining_salary [name="recieved_salary"]').val(total_G);
       
    }
    
    function updateStatus(id)
{
    var url = "{{ url('admin/rider/accounts') }}" + "/" + id + "/updateStatus";
    console.log(url,true);
    swal.fire({
        title: 'Are you sure?',
        text: "You want paid salary!",
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
 function change_edit_prints_inputs(){
        var previous_balance=$(".previous_balance").text();
        var salary=$('.salary').text();
        var ncw=$('.ncw').text();
        var bike_allowns=$('.bike_allowns').text();
        var tip=$('.tip').text();
        var bones=$('.bones').text();

        var bike_fine=$('.bike_fine').text();
        var advance=$('.advance').text();
        var salik=$('.salik').text();
        var sim=$('.sim').text();
        var macdonald=$('.macdonald').text();
        var dc=$('.dc').text();
        var rta=$('.rta').text();
        var dicipline=$('.discipline').text();
        var mobile=$('.mobile').text();
        var zomato=$('.zomato').text();
        var mics=$('.mics').text();
        var denial_penalty='0';
        var cash_paid=$('.cash_paid').text();
        
        previous_balance=previous_balance==""?0:previous_balance;
        salary=salary==""?0:salary;
        ncw=ncw==""?0:ncw;
        bike_allowns=bike_allowns==""?0:bike_allowns;
        tip=tip==""?0:tip;
        bones=bones==""?0:bones;

        bike_fine=bike_fine==""?0:bike_fine;
        advance=advance==""?0:advance;
        salik=salik==""?0:salik;
        sim=sim==""?0:sim;
        macdonald=macdonald==""?0:macdonald;
        dc=dc==""?0:dc;
        rta=rta==""?0:rta;
        mobile=mobile==""?0:mobile;
        zomato=zomato==""?0:zomato;
        mics=mics==""?0:mics;
        denial_penalty=denial_penalty==""?0:denial_penalty;
        dicipline=dicipline==""?0:dicipline;
        cash_paid=cash_paid==""?0:cash_paid;

        var total_cr=parseFloat(previous_balance)+parseFloat(salary)+parseFloat(ncw)+parseFloat(bike_allowns)+parseFloat(tip)+parseFloat(bones);
        var total_dr=parseFloat(zomato)+parseFloat(bike_fine)+parseFloat(cash_paid)+parseFloat(mics)+parseFloat(denial_penalty)+parseFloat(dicipline)+parseFloat(mobile)+parseFloat(rta)+parseFloat(advance)+parseFloat(salik)+parseFloat(sim)+parseFloat(dc)+parseFloat(macdonald);
        var net_pay=parseFloat(total_cr-total_dr).toFixed(2);
        $('.total_cr').html(total_cr);
        $('.total_dr').html(total_dr);
        $('.net_pay').html(net_pay);
        $('#total_net_pay').html(net_pay);
        // var cash_paid=$('.cash_pay').text();
        // cash_paid=cash_paid==""?0:parseFloat(cash_paid);
        // var total_remain=net_pay-cash_paid;
        // if (cash_paid=="") {
        //     $(".remaining_pay").html($('#closing_balance').text(_ClosingBalance));
        // }
        // else{
        //     $(".remaining_pay").html(total_remain);
        // }
        

 }
 $('#print_slip_for_rider [contenteditable]').on('change input', function(){
	change_edit_prints_inputs();
})
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
                    // table_bills.ajax.reload(null, false);
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
function SYNC_DATA(){
    var absent_days=$('[name="absent_days"]').val();
    var weekly_off=$('[name="weekly_off"]').val();
    var weekly_off_day=$('[name="weekly_off_day"]').val();
    var extra_day=$('[name="extra_day"]').val();
    var month=r1d1=biketrack.getUrlParameter('r1d1');
    var rider_id=biketrack.getUrlParameter('rider_id');
    var _Url = "{{url('admin/rider/week/days/sync/data')}}"+"/"+month+"/"+rider_id+"/"+weekly_off_day+"/"+absent_days+"/"+weekly_off+"/"+extra_day;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url : _Url,
        type : 'GET',
        success: function(data){
            swal.fire({
                position: 'center',
                type: 'success',
                title: 'Record Synchronized successfully.',
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
                text: 'Unable to Synchronized.',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });

}
function print_data(){
    printJS('rider_days_detail','html');
}
</script>
@endsection