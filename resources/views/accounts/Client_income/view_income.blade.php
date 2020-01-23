@extends('admin.layouts.app')
@section('head')
    <!--begin::Page Vendors Styles(used by this page) -->
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
    <!--end::Page Vendors Styles -->
@endsection
@section('main-content')
<!-- begin:: Content -->
@include('admin.includes.message')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                View Client Income
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <button class="btn btn-danger btn-elevate btn-icon-sm" id="bulk_delete">Delete Selected</button> --}}
                        &nbsp;
                        <a href="" data-ajax="{{ route('admin.client_income_index') }}" class="btn btn-success btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Fixed Income
                        </a>
                        &nbsp;
                        <a href="" data-ajax="{{ route('admin.careem_payout_index') }}" class="btn btn-success btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Comission Income
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-hover table-checkable table-condensed" id="client_income-table">
                <thead>
                    <tr>
                        {{-- <th>
                            <input type="checkbox" id="select_all" >
                        </th> --}}
                        <th>ID</th>
                        <th>Client_Id</th>
                        <th>Month</th>
                        <th>Rider Name</th>
                        <th>Income Amount</th>
                        <th>Status</th>
                        <th>Actions</th>                        
                    </tr>
                </thead>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>
<div class="modal fade bk-modal-lg" id="quick_view" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header border-bottom-0">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body bk-scroll">

        </div>
    </div>
    </div>
</div>

<!-- end:: Content -->
@endsection
@section('foot')

<!--begin::Page Scripts(used by this page) -->
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>  
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src=" https://printjs-4de6.kxcdn.com/print.min.js" type="text/javascript"></script>
<link href=" https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">

<!--end::Page Scripts -->
<script>
var client_income_table;
$(function() {
    client_income_table = $('#client_income-table').DataTable({
        lengthMenu: [[-1], ["All"]],
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
        ajax: "{!! route('admin.getclient_income') !!}",
        columns: [
            //  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'id', name: 'id' },
            { data: 'client_id', name: 'client_id' },
            { data: 'month', name: 'month' },  
            { data: 'rider_id', name: 'rider_id' },            
            { data: 'amount', name: 'amount' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions' },
        ],
        responsive:true,
        order:[0,'desc'],
    });

    $('[data-ajax]').on('click', function (e, parem) {
        e.preventDefault();
        if(!(parem && parem.reseturl==false)){
            var url_data = {    
                edit:0
            }
            biketrack.updateURL(url_data);
        }
        var _ajaxUrl = $(this).attr('data-ajax');
        console.log(_ajaxUrl);
        var _self = $(this);
        var loading_html = '<div class="d-flex justify-content-center modal_loading"><i class="la la-spinner fa-spin display-3"></i></div>';
        var _quickViewModal = $('#quick_view');
        _quickViewModal.find('.modal-body').html(loading_html);
        _quickViewModal.modal('show');
        $.ajax({
            url: _ajaxUrl,
            type: 'GET',
            dataType: 'html',
            success: function (data) {
                var _d = $(data).wrapAll('<div class="new__ajax__testing">');
                // console.log( $(data).filter('[data-ajax]')  );

                var _targetForm = $(data).find('form').wrap('<p/>').parent().html();


                _quickViewModal.find('.modal-title').html($(data).find('.page__title').html());

                _quickViewModal.find('.modal-body').html(_targetForm);
                $('script[data-ajax],style[data-ajax]').remove();

                $('body').append('<script data-ajax>' + $(data).filter('script[data-ajax]').eq(0).html() + '<\/script>');
                $('body').append('<style data-ajax>' + $(data).find('style[data-ajax]').eq(0).html() + '<\/style>');
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
                            
                            swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Record updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            client_income_table.ajax.reload(null, false);
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
            error: function (error) {
                console.log(error);
            }
        });

    });
    setScrollBkModal();
});
function deleteRow(id)
{
    var url = "{{ url('admin/accounts/client_income/delete') }}"+ "/" + id  ;
    console.log(url,true);
    sendDeleteRequest(url, false, null, client_income_table);
}
function setScrollBkModal() {
    $('.bk-scroll').each(function (i, elem) {
        KTUtil.scrollInit(this, {
            mobileNativeScroll: true,
            resetHeightOnDestroy: true,
            handleWindowResize: true,
            height: function () {
                var height;

                height = KTUtil.getViewPort().height;

                if (KTUtil.getByID('kt_header')) {
                    height = height - KTUtil.actualHeight('kt_header');
                }

                if (KTUtil.getByID('kt_subheader')) {
                    height = height - KTUtil.actualHeight('kt_subheader');
                }

                if (KTUtil.getByID('kt_footer')) {
                    height = height - parseInt(KTUtil.css('kt_footer', 'height'));
                }

                if (KTUtil.getByID('kt_content')) {
                    height = height - parseInt(KTUtil.css('kt_content', 'padding-top')) - parseInt(KTUtil.css('kt_content', 'padding-bottom'));
                }

                return height;
            }
        });
    });

}
function updateStatus(id)
{
    var url = "{{ url('admin/accounts/client_income') }}" + "/" + id +"/updatestatus";
    console.log(url,true);
    swal.fire({
        title: 'Are you sure?',
        text: "You want update status!",
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
                type : 'POST',
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
                    client_income_table.ajax.reload(null, false);
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

</script>
@endsection