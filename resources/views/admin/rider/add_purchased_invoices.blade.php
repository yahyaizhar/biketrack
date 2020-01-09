@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<style data-ajax>
     #mobile-table .mobile__remove{
            color: #ff8181;
            position: absolute;
            left: 17px;
            cursor: pointer;
        }
        #mobile-table .mobile__remove:hover{
            color: #f12626;
        } 
        #accessory-table .accessory__remove{
            color: #ff8181;
            position: absolute;
            left: 17px;
            cursor: pointer;
        }
        #accessory-table .accessory__remove:hover{
            color: #f12626;
        } 
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title page__title">
                            Add purchase invoice
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <div class="mobile__wrapper">
                <form class="kt-form" action="{{route('mobile.submit_purchased_invoices')}}" method="POST" enctype="multipart/form-data" id="mobile">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4"></div>
                                <div class="form-group col-md-4">
                                    <label>Purchasing Invoice Id:</label>
                                    <input type="number" class="form-control" data-name="invoice_purchase_id" name="invoice_purchase_id" placeholder="Enter Invoice Purchasing ID" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Invoice amount:</label>
                                <input type="number" class="form-control" placeholder="invoice amount" name="invoice_amount">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Invoice Image:</label>
                                    <div class="custom-file">
                                        <input type="file" name="invoice_picture" class="custom-file-input" id="invoice_picture">
                                        <label class="custom-file-label" for="invoice_picture">Choose Invoice Image</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Purchasing Date:</label>
                                    <input type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}"  readonly class="month_picker form-control" name="purchasing_date" placeholder="Enter Month" >
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-12">
                                    <label class="kt-checkbox main_check"> 
                                            <input name="taxcheck" type="checkbox">
                                            <h6 style="text-transform: capitalize;">Is it Tax Invoice</h6>
                                            <span></span>
                                     </label>
                                     <div class="hidden_tex" style="display:none;">
                                            <label>Enter tex amount:</label>
                                            <input type="number" class="form-control" name="tex_amount" placeholder="enter tex amount">
                                    </div>
                            </div>                                   
                            </div>
                        
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <div class="modal fade" id="add_seller_detail" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title">Add Seller Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="kt-form" enctype="multipart/form-data" id="sellers_form">
                                    <input type="hidden" name="statement_id">
                                    <div class="form-group">
                                        <label>Name:</label>
                                        <input type="text" class="form-control" name="name" placeholder="Enter Name">
                                        <span class="form-text text-muted">Please Enter Sellers Shop Name</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Address:</label>
                                        <input type="text" class="form-control" name="address" placeholder="Enter Address">
                                        <span class="form-text text-muted">Please Enter Address</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number:</label>
                                        <input type="number" class="form-control" name="phone_number" placeholder="Enter Phone Number">
                                        <span class="form-text text-muted">Please Enter Phone Number</span>
                                    </div>
                                    <div class="kt-form__actions kt-form__actions--right">
                                        <button type="submit" class="btn btn-success">Add Details</button>
                                    </div>
                                </form>
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
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src=" https://printjs-4de6.kxcdn.com/print.min.js" type="text/javascript"></script>
<link href=" https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<script data-ajax>
$(document).ready(function () {
    $('[name="taxcheck"]').on("change",function(){
        var _this=$(this);
        if (_this.prop("checked")==true) {
            $(".hidden_tex").show();
        }
        if (_this.prop("checked")==false) {
            $(".hidden_tex").hide();
        }
    });
});

</script>
@endsection

