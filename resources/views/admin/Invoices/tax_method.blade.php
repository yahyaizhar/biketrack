@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Tax Method
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" id="tax_method" action="{{ route('invoice.store_tax_method') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Name:</label>
                            <input required  type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter Your Name" value="">
                        </div>
                        <div class="form-group">
                            <label>Type:</label>
                             <select required class="form-control @if($errors->has('type')) invalid-field @endif bk-select2" name="type">
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Value:</label>
                            <input required step="0.01" type="number" class="form-control @if($errors->has('value')) invalid-field @endif" name="value" placeholder="Enter Amount" value="">
                        </div>

                        <div class="form-group">
                            <label>Is default:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="is_default" value="0" checked> No
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="is_default" value="1"> Yes
                                <span></span>
                            </label>
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
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection