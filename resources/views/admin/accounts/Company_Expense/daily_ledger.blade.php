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
                    Daily Ledger
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="" data-ajax="{{ route('admin.AR_index') }}" class=" btn btn-success btn-elevate btn-icon-sm">
                            <i class="fa fa-money-bill"></i>
                            Pay advance cash
                        </a>
                        &nbsp;
                        <a href="" data-ajax="{{ route('admin.fuel_expense_create') }}" class=" btn btn-success btn-elevate btn-icon-sm">
                            <i class="fa fa-gas-pump"></i>
                            Pay Fuel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filter by</label>
                        <select name="filter_by" id="filter_by" class="form-control">
                            <option value="day" data-target="#day_picker-wrapper" selected>Day</option>
                            <option value="month" data-target="#month_picker-wrapper">Month</option>
                            {{-- <option value="year">Year</option> --}}
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group filter_picker" id="day_picker-wrapper" style="display:none;">
                        <label>Select Date</label>
                        <input id="day_picker" type="text" data-month="{{Carbon\Carbon::now()->format('M d, Y')}}" readonly class="month_picker form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month" value="">
                    </div>
                    <div class="form-group filter_picker" id="month_picker-wrapper" style="display:none;">
                        <label>Select Month</label>
                        <input id="month_picker" type="text" data-month="{{Carbon\Carbon::now()->format('M Y')}}" readonly class="month_picker_only form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month" value="">
                    </div>
                    <div class="form-group filter_picker" id="year_picker-wrapper" style="display:none;">
                        <label>Select Year</label>
                        <input id="year_picker" type="text" data-month="{{Carbon\Carbon::now()->format('Y')}}" readonly class="year_picker_only form-control @if($errors->has('month_year')) invalid-field @endif" name="month_year" placeholder="Enter Month" value="">
                    </div>
                </div>
            </div>
            <table class="table table-striped- table-hover table-checkable table-condensed" id="expense-table">
                <thead>
                    <tr>
                        <th>Given Date</th>
                        {{-- <th>ID</th> --}}
                        <th>Source</th>
                        {{-- <th>Rider</th> --}}
                        <th>Paid By</th>
                        <th>Amount</th>
                        <th></th>
                        {{-- <th>Payment Status</th>                         --}}
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Total:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="quick_view" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header border-bottom-0">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

        </div>
    </div>
    </div>
</div>
<div class="modal fade" id="update_row_model" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header border-bottom-0">
            <h5 class="modal-title">Update Company Account</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form class="kt-form" enctype="multipart/form-data" id="update_rows">
                <input type="hidden" name="statement_id">
                <input type="hidden" name="source_id">
                <input type="hidden" name="source_key">
                <div class="form-group">
                    <label>Rider:</label>
                    <select required class="form-control kt-select2-general" name="rider_id_update" >
                        @foreach ($riders as $rider)
                        <option value="{{ $rider->id }}">
                            {{ $rider->name }}
                        </option>     
                        @endforeach 
                    </select>
                </div>
                <div class="form-group">
                    <label>Month:</label>
                    <input type="text" data-month="{{Carbon\Carbon::now()->format('F Y')}}" required readonly class="month_picker_only form-control @if($errors->has('month')) invalid-field @endif" name="month_update" placeholder="Enter Month" value="">
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
                    <label>Description:</label>
                    <textarea class="form-control" name="desc" placeholder="Enter Description"></textarea>
                    <span class="form-text text-muted">Please enter Description</span>
                </div>
                <div class="form-group">
                    <label>Amount:</label>
                    <input type="number" step="0.01" required class="form-control" name="amount" placeholder="Enter Amount" value="0">
                    <span class="form-text text-muted">Please enter Amount</span>
                </div>

                <div class="kt-form__actions kt-form__actions--right">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>
<script>
var expense_table;

$(function() {
    $('[name="filter_by"]').on('change', function(){
        var _selectedop = $(this).find(':selected');
        var _target = _selectedop.attr('data-target');
        $('.filter_picker').hide();
        $(_target)
        .show()
        .find('[name="month_year"]').trigger("change");
    });
    $('[name="month_year"]').on("change",function(){
        var month=$(this).val();
        console.log('month',this);
        
        var _filterBy=$('[name="filter_by"]').val();
        var push_state={
            month:new Date(month).format("yyyy-mm-dd"),
            filter_by:_filterBy,
        }
        biketrack.updateURL(push_state);
        init_table();
    });
    var is_check_month=biketrack.getUrlParameter('month');
    if (is_check_month!='') {
        $('[name="month_year"]').fdatepicker('update', new Date(is_check_month));
    }
    var q_filter=biketrack.getUrlParameter('filter_by');
    if (q_filter!='') {
        $('[name="filter_by"]').val(q_filter);
    }
    $('[name="filter_by"]').trigger("change");

    $('[data-ajax]').on('click', function(e){
        e.preventDefault();
        var _ajaxUrl = $(this).attr('data-ajax');
        console.log(_ajaxUrl);
        var _self = $(this);
        _self.find('[type="submit"]').prop('disabled',true);
        var loading_html = '<div class="d-flex justify-content-center modal_loading"><i class="la la-spinner fa-spin display-3"></i></div>';
        var _quickViewModal = $('#quick_view');
        var selected_month=new Date(biketrack.getUrlParameter("month")).format('mmmm yyyy');
        console.log(selected_month);
        _quickViewModal.find('.modal-body').html(loading_html);
        _quickViewModal.modal('show');
        $.ajax({
            url : _ajaxUrl,
            type : 'GET',
            dataType: 'html',
            success: function(data){
                console.log($(data));
                
                var _targetForm = $(data).find('form').wrap('<p/>').parent().html();
                
                
                _quickViewModal.find('.modal-title').text(_self.text().trim());
                
                _quickViewModal.find('.modal-body').html(_targetForm);
                _quickViewModal.find('[name="month"]').attr('data-month',selected_month);
                _quickViewModal.find('[name="month_year"]').attr('data-month',selected_month);
                // var rider_id = $('#gb_rider_id').val();
                // if(_quickViewModal.find('[name="rider_id"]').length){
                //     _quickViewModal.find('[name="rider_id"]').val(rider_id).trigger('change.select2');
                // }
                $('script[data-ajax]').remove();
                console.warn($(data).find('[data-ajax]'));
                var $ajax_script = $(data).find('[data-ajax]');
                if($ajax_script.length==0) $ajax_script = $(data).filter('[data-ajax]');

                if($ajax_script.length==0) alert('Cannot find ajax script in this form');
                $('body').append('<script data-ajax>'+$ajax_script.eq(0).html()+'<\/script>');

                
                    biketrack.refresh_global();
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
                        data: _form.serialize(),
                        success: function(data){
                            console.log(data);
                            _self.find('[type="submit"]').prop('disabled',true);
                            
                            swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Record updated successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            expense_table.ajax.reload(null, false); 
                        },
                        error: function(error){
                            _self.find('[type="submit"]').prop('disabled',true);
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
            error: function(error){
                console.log(error);
            }
        });
        
    });
});

var init_table=function(){
    var _month=new Date($('[name="month_year"]:visible').val()).format("yyyy-mm-dd");
    var _filterBy=$('[name="filter_by"]').val();
    expense_table = $('#expense-table').DataTable({
        lengthMenu: [[-1], ["All"]],
        processing: true,
        serverSide: false,
        destroy:true,
        'language': { 
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
            $('.total_entries').remove();
            $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
        },
        ajax: "{{ url('admin/account/daily_ledger/ajax') }}" + "/" + _month+"/"+_filterBy,
        columns: [
            { data: 'given_date', name: 'given_date' },
            // { data: 'id', name: 'id' },
            // { data: 'source', name: 'source' },
            { data: 'rider', name: 'rider' },
            { data: 'paid_by', name: 'paid_by' },
            { data: 'amount', name: 'amount' },
            { data: 'action', name: 'action' },
            // { data: 'payment_status', name: 'payment_status' },
        ],
        responsive:true,
        order:[0,'desc'],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            
            //source count
            var totalcount=0;
            var _temp = api
            .column( 1 )
            .data()
            .reduce( function (a, b) {
                return totalcount++;
            }, 0 );
            // Total over this page
            pageTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                console.log('pageTotal', pageTotal);
 
            // Update footer
            $( api.column( 3 ).footer() ).html(
                pageTotal
            );
            $( api.column( 1 ).footer() ).html(
                totalcount
            );
        }
    });
}
function UpdateRows($this,id,model_class,model_id,rider_id,string,month,year,source_id,source_key,given_date,stype){
    var _tr = $($this).parents('tr');
    var _row = expense_table.row(_tr).data();
    var _model = $('#update_row_model');
    var r1d1=_row.month;
    var rider_id_update=_row.rider_id;
    var _month=new Date(r1d1).format("mmmm yyyy");
    var _source = _row.desc==null?_row.source:_row.desc;
    _model.find('#update_rows [name="desc"]').val(_source);
    _model.find('#update_rows [name="amount"]').val(_row.amount);
    _model.find('#update_rows [name="statement_id"]').val(id);
    _model.find('#update_rows [name="source_id"]').val(source_id);
    _model.find('#update_rows [name="source_key"]').val(source_key);
    _model.find('#update_rows [name="month_update"]').attr("data-month",_month);
    _model.find('#update_rows [name="rider_id_update"]').val(rider_id_update).trigger("change.select2");
    biketrack.refresh_global();
    var bk_rider_id=rider_id;
    var bk_month=month;
    var bk_year=year;
    _model.modal('show');

    _model.find('form').off('submit').on('submit', function(e){
        e.preventDefault();
        var url ="{{ url('admin/accounts/send_notification/update_row/') }}" ;
        var data=new FormData(this);
        data.append('rider_id',bk_rider_id);
        data.append('bk_month',bk_month);
        data.append('bk_year',bk_year);
        data.append('given_date',given_date);
        data.append('source',model_id);
        data.append('statement_type',stype);
        var _form = $(this);
        var _submitBtn = _form.find('[type="submit"]');
        $.ajax({
            url : url,
            type : 'POST',
            data:data,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {            
                // $('.loading').show();
                _submitBtn.prop('disabled', true);
            },
            complete: function(){
                // $('.loading').hide();
                _submitBtn.prop('disabled', false);
                _model.modal('hide');
            },
            success: function(data){
                if(data.status==0){
                    //error returned
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: data.msg,
                        showConfirmButton: true,
                    });
                    return;
                }
                swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'Record update request sent successfully.',
                    showConfirmButton: false,
                    timer: 1500
                });
                expense_table.ajax.reload(null, false);
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

}
function deleteRows(id,model_class,model_id,rider_id,string,month,year,source_id,source_key,given_date,stype){
    // var url = "{{ url('admin/delete/accounts/rows') }}";
    var url = "{{ url('admin/send_notification/delete/rows') }}";
    console.log(url);
    swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete! Just wait for your senior response.",
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
                "year":year,
                "source_id":source_id,
                "source_key":source_key,
                "given_date":given_date,
                'statement_type':stype
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
                    if(data.status==0){
                        //error returned
                        swal.fire({
                            position: 'center',
                            type: 'error',
                            title: 'Oops...',
                            text: data.msg,
                            showConfirmButton: true,
                        });
                        return;
                    }
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record Deleted notification sent to your senior successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    expense_table.ajax.reload(null, false);
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
</script>
@endsection