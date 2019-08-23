@extends('admin.layouts.app')
@section('head')
<style>
    .fields_wrapper{
        display: none;
    }
    .fields_wrapper--show{
        display: block;
    }
</style>
@endsection
@section('main-content')
@php
    $riders=\App\Model\Rider\Rider::where('active_status', 'A')->get();
@endphp
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Company debits
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        {{-- &nbsp;
                        <a href="{{ route('SimTransaction.create_sim') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Select type:</label>
                        <div>
                            <select class="form-control kt-select2" id="kt_select2_12_1" >
                                <option value="">Select type</option>
                                <option value="salary">Salary</option>
                                <option value="advance">Advance</option>
                            </select> 
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div id="salary" class="fields_wrapper">
                        @include('admin.accounts.partial.salary')
                    </div>

                    <div id="advance" class="fields_wrapper">
                        @include('admin.accounts.partial.advance')
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('foot')
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
    
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>

@yield('partial_foot_salary')
@yield('partial_foot_advance') 

<script>
    $(function(){
        console.log('form main')
        $('.kt-select2').select2({
            placeholder: "Select an option",
            width:'100%'    
        });
        $('.month_picker').fdatepicker({ 
            format: 'MM yyyy', 
            startView:3,
            minView:3,
            maxView:4
        });
        $('#kt_select2_12_1').on('change', function(){
            var _val = $(this).val().trim();
            if(_val=="")return;

            $('.fields_wrapper').removeClass('fields_wrapper--show');
            $('#'+_val).addClass('fields_wrapper--show');
            var _from = $('#'+_val).find('form');
            _from.find('[name="type_db"]').remove();
            _from.prepend('<input type="hidden" name="type_db" value="'+_val+'">');
        });
    })
</script>
@endsection