@extends('admin.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title" id="seller_head_title_view">
                            View Sellers
                        </h3>
                    </div>
                </div>
                @include('admin.includes.message')
                <form class="kt-form" action="{{ route('mobile.sellers_update',$seller->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label>Name:</label>
                            <input required type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter Name" value="{{ $seller->name }}">
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <input required type="text" class="form-control @if($errors->has('address')) invalid-field @endif" name="address" placeholder="Enter Address" value="{{ $seller->address }}">
                        </div>
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <input required type="number" class="form-control @if($errors->has('phone_number')) invalid-field @endif" name="phone_number" placeholder="Enter Phone Number" value="{{ $seller->phone_number }}">
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a class="btn btn-warning edit_view">Edit</a>
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
<script>
  $(document).ready(function(){
    $('#datepicker').fdatepicker({format: 'dd-mm-yyyy'}); 
    $('[type="number"] , [type="text"]').prop("disabled",true);
    $('[type="submit"]').hide();
    $(".edit_view").show();
    $("#seller_head_title_view").html("View Seller");
    $(".edit_view").on("click",function(){
        $('[type="number"] , [type="text"]').prop("disabled",false);
        $('[type="submit"]').show();
        $(".edit_view").hide();
        $("#seller_head_title_view").html("Edit Seller");
    });

  });

</script>
@endsection