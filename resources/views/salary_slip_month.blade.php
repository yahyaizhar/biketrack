@extends('admin.layouts.app')
@section('head')
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
        </style>
@endsection
@section('main-content')
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                   layout is better
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
            <div class="row">
                <div class="col-md-12" style="text-align: center;border: 1px solid #dddd; background-color:cyan"><h4>SALARY SLIP</h4></div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center;border: 1px solid #dddd;">Aug 2019</div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3" style="border: 1px solid #dddd;">NAME:</div>
                <div class="col-md-3" style="border: 1px solid #dddd;">Waqas</div>
                <div class="col-md-3" style="border: 1px solid #dddd;">Designation:</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-3" style="border: 1px solid #dddd;">EMPLOYEE ID:</div>
                <div class="col-md-3" style="border: 1px solid #dddd;">KRD-104</div>
                <div class="col-md-3" style="border: 1px solid #dddd;">WORKPLACE:</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-3" style="border: 1px solid #dddd;">DATE OF JOINING:</div>
                <div class="col-md-3" style="border: 1px solid #dddd;">7/17/2019</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6" style="text-align: center;border: 1px solid #dddd;">DESCRIPTION</div>
                <div class="col-md-3" style="text-align: center;border: 1px solid #dddd;">EARNINGS</div>
                <div class="col-md-3" style="text-align: center;border: 1px solid #dddd;">DEDUCTIONS</div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">BASIC SALARY</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">NCW ALLOWANCE</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">CUSTOMER TIP</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">BIKE ALLOWANCE</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">BONES</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">ADVANCE</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">SALIK PLANTI</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">SIM PLANTI</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">ZOMATO PLANTI</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">DC DEDUCTION</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">MCDONALD DEDUCTION</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">RTA FINE</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">MOBILE EMI</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">DISPLAN FINE</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">MICS CHARGES</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">TOTAL</div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
                <div class="col-md-3" style="border: 1px solid #dddd;"></div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;">PAYMENT DATE:Tuesday, September 24,2019</div>
                <div class="col-md-6" style="text-align:center;border: 1px solid #dddd; background-color:cyan">NET PAY</div>
            </div>
            <div class="row">
                <div class="col-md-6" style="border: 1px solid #dddd;"></div>
                <div class="col-md-6" style="text-align:center;border: 1px solid #dddd;background-color:cyan">2986.82</div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

@endsection

