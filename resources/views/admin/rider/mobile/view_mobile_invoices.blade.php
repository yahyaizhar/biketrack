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
                        <h3 class="kt-portlet__head-title page__title" id="head_title">
                            View Mobile
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <div class="mobile__wrapper">
                <form class="kt-form" action="{{route('Mobile.update',$mobile->id)}}" method="POST" enctype="multipart/form-data" id="mobile">
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="form-group col-md-4">
                                    <label class="image_selector">Invoice Image:</label>
                                    <div class="custom-file image_selector">
                                        <input type="file" name="invoice_picture" class="custom-file-input" id="invoice_picture">
                                        <label class="custom-file-label" for="invoice_picture">Choose Invoice Image</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @if($mobile->invoice_picture)
                                        <img class="profile-logo img img-thumbnail" style="display:block;margin-left:50px;" src="{{ asset(Storage::url($mobile->invoice_picture)) }}" alt="image">
                                    @else
                                        <img class="profile-logo img img-thumbnail" style="display:block;margin-left:50px;" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Seller Detail:</label>
                                    <select class="form-control bk-select2 kt-select2" data-seller="seller_detail" id="seller_detail" data-non-readonly data-name="seller_id" name="seller_detail" required>
                                    @foreach ($sellers as $seller)
                                        <option value="{{ $seller->id }}" @if ($mobile->seller_id==$seller->id) selected @endif>
                                            {{ $seller->name }}
                                        </option>     
                                    @endforeach 
                                    </select> 
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Purchasing Date:</label>
                                    <input type="text" data-month="{{Carbon\Carbon::parse($mobile->purchasing_date)->format('M d, Y')}}"  readonly class="month_picker form-control" name="purchasing_date" placeholder="Enter Month" >
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Purchasing Invoice Id:</label>
                                    <input type="number" class="form-control" value="{{$mobile->purchased_invoice_id}}" data-name="invoice_purchase_id" name="invoice_purchase_id" placeholder="Enter Invoice Purchasing ID" >
                                </div>
                            </div>
                            <table class="table table-striped- table-hover table-checkable table-condensed" id="mobile-table">
                                <thead>
                                    <tr>
                                        <th class="mobile-cell-sr">ID</th>
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
                            <div class="kt-form__actions kt-form__actions--right" style="margin-top:50px;">
                                <button type="submit" class="btn btn-primary update_submit_mobile">Update Mobile</button>
                                <a class="btn btn-warning edit_submit_mobile">Edit Mobile</a>
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
        '       <td class="mobile__table-row_cell-sr"></span>' + (total_rows + 1) + ' </td>  ' +
'               <td><input required type="text" class="form-control" data-name="model" value="{{$mobile->model}}" name="mobiles['+total_rows+'][model]" placeholder="Model"> </td>  '  + 
'                <td style="width: 14%;">  '  + 
'                   <select class="form-control kt-select2" data-name="brand" id="brand_select" name="mobiles['+total_rows+'][brand]" >  '  + 
'                        <option value="Samsung" @if ($mobile->brand=="Samsung") selected @endif>Samsung</option>  '  + 
'                        <option value="huawei" @if ($mobile->brand=="huawei") selected @endif>Huawei</option>  '  + 
'                        <option value="Google" @if ($mobile->brand=="Google") selected @endif>Google</option>  '  + 
'                        <option value="Sony" @if ($mobile->brand=="Sony") selected @endif>Sony</option>  '  + 
'                        <option value="Nokia" @if ($mobile->brand=="Nokia") selected @endif>Nokia</option>  '  + 
'                        <option value="LG" @if ($mobile->brand=="LG") selected @endif>LG</option>  '  + 
'                        <option value="OnePlus" @if ($mobile->brand=="OnePlus") selected @endif>OnePlus</option>  '  + 
'                        <option value="Doro" @if ($mobile->brand=="Doro") selected @endif>Doro</option>  '  + 
'                        <option value="Motorola" @if ($mobile->brand=="Motorola") selected @endif>Motorola</option>  '  + 
'                        <option value="BlackBerry" @if ($mobile->brand=="BlackBerry") selected @endif>BlackBerry</option>  '  + 
'                        <option value="Xiaomi" @if ($mobile->brand=="Xiaomi") selected @endif>Xiaomi</option>  '  + 
'                        <option value="Acer" @if ($mobile->brand=="Acer") selected @endif>Acer</option>  '  + 
'                        <option value="Oppo" @if ($mobile->brand=="Oppo") selected @endif>Oppo</option>  '  + 
'                     </select>  '  + 
'                  </td>  '  + 
'                  <td><input required type="text" data-name="imei1" class="form-control" value="{{$mobile->imei_1}}" name="mobiles['+total_rows+'][imei_1]" placeholder="IMEI 1st number"></td>  '  + 
'                  <td><input required type="text" data-name="imei2" class="form-control" value="{{$mobile->imei_2}}" name="mobiles['+total_rows+'][imei_2]" placeholder="IMEI 2nd number"></td>  '  + 
'                  <td style="width: 12%;"><input data-name="purchase_price" required value="{{$mobile->purchase_price}}" type="number" class="form-control" name="mobiles['+total_rows+'][purchase_price]" placeholder="Purchase price"></td>  '  + 
'                  <td style="width: 10%;"><input data-name="vat_paid" step="0.01" type="number" value="{{$mobile->vat_paid}}" class="form-control" name="mobiles['+total_rows+'][vat_paid]" placeholder="VAT Paid"></td>  '  + 
'                  <td style="width: 10%;"><input data-name="sale_price" required value="{{$mobile->sale_price}}" type="number" class="form-control" name="mobiles['+total_rows+'][sale_price]" placeholder="sale price"></td>  ' +
'  </tr>  ';
    $("#mobile-table tbody").append(markup);
    // biketrack.refresh_global();
}
append_row();
$(document).ready(function(){
    $('[type="text"] ,[type="number"] ,[data-name="brand"],[data-seller="seller_detail"]').prop("disabled", true);
    $('.image_selector').hide();
    $('.view_submit_mobile , .update_submit_mobile').hide();
    $("#head_title").html("View Mobile");
    $('.edit_submit_mobile').on("click",function(){
        $("#head_title").html("Edit Mobile");
        $(this).hide();
        $('.image_selector').show();
        $('[type="text"] ,[type="number"] ,[data-name="brand"],[data-seller="seller_detail"]').prop("disabled", false);
        $('.view_submit_mobile , .update_submit_mobile').show();
    });
});
</script>
@endsection

