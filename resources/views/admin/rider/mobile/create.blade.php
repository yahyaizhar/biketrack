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
                            Create Mobile
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <div class="mobile__wrapper">
                <form class="kt-form" action="{{route('mobile.create_mobile_POST')}}" method="POST" enctype="multipart/form-data" id="mobile">
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
                                    <label>Seller Detail:</label>
                                    <select class="form-control bk-select2 kt-select2" id="seller_detail" data-non-readonly data-name="seller_id" name="seller_detail" required>
                                    @foreach ($sellers as $seller)
                                        <option value="{{ $seller->id }}">
                                            {{ $seller->name }}
                                        </option>     
                                    @endforeach 
                                    </select> 
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
                            <table class="table table-striped- table-hover table-checkable table-condensed" id="mobile-table">
                                <thead>
                                    <tr>
                                        <th class="mobile-cell-sr">SR #</th>
                                        <th class="mobile-cell-model">Model</th>
                                        <th class="mobile-cell-brand">Brand</th>
                                        <th class="mobile-cell-imei1">IMEI No 1</th>
                                        <th class="mobile-cell-imei2">IMEI No 2</th>
                                        <th class="mobile-cell-pur_price">Purchased Price(inc Tax)</th>
                                        <th class="mobile-cell-tax_paid">Tax Paid</th> 
                                        <th class="mobile-cell-sale_price">Sale Price</th>                 
                                    </tr>
                                        <tbody>
                                        </tbody>
                                </thead>
                            </table>
                        
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button style="float:left;padding: 5px;" class="btn btn-primary btn--addnewrow" onclick="append_row();return false">Add Mobile</button> <span style="float:left;padding: 5px;">With</span>
                                <div class="kt-checkbox-list" style="display:flex;padding:4px;"><label class="kt-checkbox"> <input name="check_accessory" type="checkbox" value="0"><span></span> Accessories </label></div>
                            </div>
                        </div>
                        <div class="">
                            <table class="table table-striped- table-hover table-checkable table-condensed" id="accessory-table">
                                <thead>
                                    <tr>
                                        <th class="mobile-cell-sr">SR #</th>
                                        <th class="mobile-cell-model">Description</th>
                                        <th class="mobile-cell-brand">Amount</th>               
                                    </tr>
                                <tbody>
                                </tbody>
                                </thead>
                            </table>
                        </div>
                        <div class="kt-portlet__foot append_for_accesory">
                            <div class="kt-form__actions kt-form__actions--right">
                                <button style="float:left;padding: 5px;" class="btn btn-primary btn--addnewrow" onclick="append_row_accessory();return false">Add Accessry</button>
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
    $(document).on("change input","#mobile-table tbody [data-name='purchase_price']",function(){
        var _this=$(this).val();
        var vat=5/100;
        var vat_amt=_this*vat;
        console.log(_this);
        $(this).parents('tr').find("[data-name='vat_paid']").val(vat_amt);
    });

    $("#accessory-table").hide();
    $(".append_for_accesory").hide();
    $('[name="check_accessory"]').on("change",function(){
        var _this=$(this);
        if (_this.prop("checked")==true) {
            $("#accessory-table").show();
            $(".append_for_accesory").show();
            _this.val("1");
        }
        if (_this.prop("checked")==false) {
            $("#accessory-table").hide();
            $(".append_for_accesory").hide();
            _this.val("0");
        }
    });
    $(document).on("keyup",'.select2-search__field',function(event){
         var  selected_option=$('.select2-search__field').val();
         var keycode = (event.keyCode ? event.keyCode : event.which);

            var  selected_option=$('.select2-search__field').val();
            if($('.select2-results__options').find('.select2-results__message').length >0){ 
                $('.select2-results__message').text('No results found! Click Enter to save Data');
            }
            if (keycode==13) {
                $("#add_seller_detail").modal("show");
                $('#seller_detail').select2('close');
                $('#sellers_form [name="name"]').val(selected_option); 
            }
        });
        $("#sellers_form").on("submit",function(e){
            e.preventDefault();
            var _form=$(this);
            var _modal = _form.parents('.modal');
            var url="{{url('admin/add/seller/details/')}}"
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    url:url,
                    method: "POST",
                    data:_form.serializeArray(),
                })
                .done(function(data) {
                    $("#add_seller_detail").modal("hide");  
                    console.log(data);
                    $("#mobile [name='seller_detail']").html(''); 
                    data.seller_all.forEach(function(x,i){
                        var _option='<option value="'+x.id+'">'+x.name+'</option>'
                        $("#mobile [name='seller_detail']").append(_option); 
                    });
                });
        });
});



function append_row($row_data = null) {
    var markup = '';
    var total_rows = parseFloat($("#mobile-table tbody tr").length);
    markup = '' +
        '   <tr>  ' +
        '       <td class="mobile__table-row_cell-sr"> <span class="flaticon2-trash mobile__remove" onclick="delete_row(this);"></span>' + (total_rows + 1) + ' </td>  ' +
'               <td><input required type="text" class="form-control" data-name="model" name="mobiles['+total_rows+'][model]" placeholder="Model"> </td>  '  + 
'                <td style="width: 14%;">  '  + 
'                   <select class="form-control kt-select2" data-name="brand" id="brand_select" name="mobiles['+total_rows+'][brand]" >  '  + 
'                        <option value="Samsung">Samsung</option>  '  + 
'                        <option value="huawei">Huawei</option>  '  + 
'                        <option value="Google">Google</option>  '  + 
'                        <option value="Sony">Sony</option>  '  + 
'                        <option value="Nokia">Nokia</option>  '  + 
'                        <option value="LG">LG</option>  '  + 
'                        <option value="OnePlus">OnePlus</option>  '  + 
'                        <option value="Doro">Doro</option>  '  + 
'                        <option value="Motorola">Motorola</option>  '  + 
'                        <option value="BlackBerry">BlackBerry</option>  '  + 
'                        <option value="Xiaomi">Xiaomi</option>  '  + 
'                        <option value="Acer">Acer</option>  '  + 
'                        <option value="Oppo">Oppo</option>  '  + 
'                     </select>  '  + 
'                  </td>  '  + 
'                  <td><input required type="number" data-name="imei1" class="form-control" name="mobiles['+total_rows+'][imei_1]" placeholder="IMEI 1st number"></td>  '  + 
'                  <td><input required type="number" data-name="imei2" class="form-control" name="mobiles['+total_rows+'][imei_2]" placeholder="IMEI 2nd number"></td>  '  + 
'                  <td style="width: 12%;"><input data-name="purchase_price" required type="number" class="form-control" name="mobiles['+total_rows+'][purchase_price]" placeholder="Purchase price"></td>  '  + 
'                  <td style="width: 10%;"><input data-name="vat_paid" type="number" class="form-control" name="mobiles['+total_rows+'][vat_paid]" placeholder="VAT Paid"></td>  '  + 
'                  <td style="width: 10%;"><input data-name="sale_price" required type="number" class="form-control" name="mobiles['+total_rows+'][sale_price]" placeholder="sale price"></td>  ' +
'  </tr>  ';
    $("#mobile-table tbody").append(markup);
    // biketrack.refresh_global();
}

function append_row_accessory($row_data = null) {
    var markup = '';
    var total_rows = parseFloat($("#accessory-table tbody tr").length);
    markup = '' +
        '   <tr>  ' +
        '       <td  style="text-align:center;" class="accessory__table-row_cell-sr"> <span class="flaticon2-trash accessory__remove" onclick="delete_row_accessory(this);"></span>' + (total_rows + 1) + ' </td>  ' +
        '       <td><input type="text" class="form-control" data-name="model" name="accessory['+total_rows+'][description]" placeholder="Description"> </td>  '  + 
        '       <td style="width: 12%;"><input type="number" class="form-control" name="accessory['+total_rows+'][amount]" placeholder="Amount"></td>  '  + 
        '   </tr>  ';
    $("#accessory-table tbody").append(markup);
    // biketrack.refresh_global();
}

function delete_row(ctl) {
    $(ctl).parents("tr").remove();
    console.info("====================================================3");
    subtotal();
    typeof receive_payment !=="undefined" && (receive_payment.modal_confirmation_required=true);
}

function delete_row_accessory(ctl) {
    $(ctl).parents("tr").remove();
    console.info("====================================================3");
    subtotal();
    typeof receive_payment !=="undefined" && (receive_payment.modal_confirmation_required=true);
}

</script>
@endsection

