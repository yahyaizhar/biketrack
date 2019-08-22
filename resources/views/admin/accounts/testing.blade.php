@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
   {{-- sim transactions --}}
    <div class="row">
        <div class="col-md-4">
            <h5>sim transactions</h5>
            <form class="kt-form" action="{{ route('SimTransaction.store_simTransaction') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="kt-portlet__body">
                    <div class="form-group">
                        <label>Month_Year:</label>
                        <input type="text" id="datepicker" class="form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month_Year" value="{{ old('month_year') }}">
                        @if ($errors->has('month_year'))
                            <span class="invalid-response" role="alert">
                                <strong>
                                    {{$errors->first('month_year')}}
                                </strong>
                            </span>
                        @endif
                    </div>
        <div class="form-group">
            <label>Bill Amount:</label>
            <input type="text" class="form-control @if($errors->has('bill_amount')) invalid-field @endif" name="bill_amount" placeholder="Enter Bill Amount" value="{{ old('bill_amount') }}">
            @if ($errors->has('bill_amount'))
                <span class="invalid-response" role="alert">
                    <strong>
                        {{$errors->first('bill_amount')}}
                    </strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Extra Usage Amount:</label>
            <input type="text" class="form-control @if($errors->has('extra_usage_amount')) invalid-field @endif" name="extra_usage_amount" placeholder="Enter extra usage amount " value="{{ old('extra_usage_amount') }}">
            @if ($errors->has('extra_usage_amount'))
                <span class="invalid-response" role="alert">
                    <strong>
                        {{$errors->first('extra_usage_amount')}}
                    </strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Extra Usage Payment Status:</label>
            <select class="form-control @if($errors->has('extra_usage_payment_status')) invalid-field @endif kt-select2" id="kt_select2_3" name="extra_usage_payment_status" placeholder="Enter extra usage payment status" value="{{ old('extra_usage_payment_status') }}">
                    <option style="font-weight:bold;">Select Payment Status:</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
            </select> 
            @if ($errors->has('extra_usage_payment_status'))
                <span class="invalid-response" role="alert">
                    <strong>
                        {{$errors->first('extra_usage_payment_status')}}
                    </strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Bill Status:</label>
            <select class="form-control @if($errors->has('bill_status')) invalid-field @endif kt-select2" id="kt_select2_3" name="bill_status" placeholder="Enter bill status" value="{{ old('bill_status') }}">
                    <option style="font-weight:bold;">Select Bill Status:</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
            </select> 
            @if ($errors->has('bill_status'))
                <span class="invalid-response" role="alert">
                    <strong>
                        {{$errors->first('bill_status')}}
                    </strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Status:</label>
            <div>
                <input data-switch="true" name="status" id="status" type="checkbox" checked="checked" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
            </div>
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
   {{-- ends sim transactions --}}
</div>
<div class="row">
    <div class="col-md-6">
            <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="kt-font-brand fa fa-hotel"></i>
                        </span>
                        <h3 class="kt-portlet__head-title">
                            Company Accounts 
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                Closing Balance:   {{$closing_balance_CA}} 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                <table class="table table-striped- table-hover table-checkable table-condensed" id="company_acounts">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Source</th>                        
                    </tr>
                </thead>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
            <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-hotel"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Rider Acounts
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                                Closing Balance: {{$closing_balance_RA}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <table class="table table-striped- table-hover table-checkable table-condensed" id="rider_accounts">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Source</th>
                            {{-- <th>Actions</th>                         --}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@section('foot')
<style>
.form-group {
    margin-bottom: 0;
}
</style>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
 <script>
    $(document).ready(function(){
        $('#datepicker').fdatepicker({ format: 'MM yyyy',startView:3,minView:3,maxView:4});
          }); 
</script>
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script>
var company_table;
$(function() {
    company_table = $('#company_acounts').DataTable({ 
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
        $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
   rider_accounts();
    },
        ajax: "{!! route('admin.ajax_company_accounts') !!}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'type', name: 'type' },            
            { data: 'amount', name: 'amount' },
            { data: 'source', name: 'source' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
});

</script>
{{-- end company accounts --}}
<script>
var rider_table;
function rider_accounts() {
    rider_table = $('#rider_accounts').DataTable({
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
$('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
    },
        ajax: "{!! route('admin.ajax_rider_accounts') !!}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'type', name: 'type' },            
            { data: 'amount', name: 'amount' },
            { data: 'source', name: 'source' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
};

</script>
@endsection