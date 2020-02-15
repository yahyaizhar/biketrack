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
                        <a style="padding:8.45px 13px;" href="" data-toggle="modal" data-target="#import_data"  class="btn btn-info btn-sm btn-upper">
                            <i class="la la-file-text-o"></i>
                            Import Careem Payout
                        </a>&nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="income_import_err"></div>

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

<div>
    <div class="modal fade" id="import_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="kt-form" id="form_dates"  enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="UppyDragDrop"></div>
                    <div class="card card-body bg-light py-1 mt-1 uppy_result">
                        <span></span>
                    </div>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-center">
                    <button class="upload-button btn btn-success">Import</button>
                    
                    
                </div>
            </form>
            {{-- <button class="btn btn-danger"  onclick="delete_lastImport();return false;"><i class="fa fa-trash"></i> Delete Last Import</button> --}}
            </div>
        </div>
    </div>
</div>

<!-- end:: Content -->
@endsection
@section('foot')

<!--begin::Page Scripts(used by this page) -->
<link href="https://transloadit.edgly.net/releases/uppy/v1.3.0/uppy.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.6/js/foundation-datepicker.min.js"></script>  
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script src=" https://printjs-4de6.kxcdn.com/print.min.js" type="text/javascript"></script>
<link href=" https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<script src="https://transloadit.edgly.net/releases/uppy/v1.3.0/uppy.min.js"></script>
<script src="{{ asset('js/papaparse.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
<script>
var client_income_table;

var basic_alert= '   <div><div class="alert alert-danger fade show" role="alert">  '  + 
 '                                   <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>  '  + 
 '                                       <div class="alert-text">A simple danger alert—check it out!</div>  '  + 
 '                                       <div class="alert-close">  '  + 
 '                                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">  '  + 
 '                                           <span aria-hidden="true"><i class="la la-close"></i></span>  '  + 
 '                                       </button>  '  + 
 '                                   </div>  '  + 
 '                              </div> </div>  ' ;
$(function() {

    var uppy = Uppy.Core({
        debug: true,
        autoProceed: false,
        allowMultipleUploads: true,
        restrictions: {
            allowedFileTypes: ['.csv']
        }
    });
  uppy.use(Uppy.DragDrop, { 
      target: '.UppyDragDrop',
        
   });
   uppy.on('restriction-failed', (file, error) => {
    alert(error);
    }).on('file-added', (res) => {
        console.log(res);
        append_import_files();
        
    });
    var append_import_files = function(){
        var files = uppy.getFiles();
        $('.uppy_result').html('');
        files.forEach(function(file,i){
            var _fileName = file.name;
            var _alert = ''+
                     '<div class="alert alert-warning alert-dismissible my-1">'+
                    '   <a href="#" class="close import__file-details" data-file-id="'+file.id+'" data-dismiss="alert" aria-label="close">&times;</a>'+
                    '   <strong>'+(i+1)+')</strong>&nbsp;'+_fileName+
                    '</div>'   
            $('.uppy_result').append(_alert);
        });
    }
    $(document).on('click', '.import__file-details', function(){
        var file_id=$(this).attr('data-file-id');
        console.log('file id: ', file_id);
        uppy.removeFile(file_id);
        append_import_files();
    });
  
    $('.upload-button').on('click', function (e) {
        e.preventDefault();
        $('#import_data').modal('hide');
        var files = uppy.getFiles();
        if(files.length<=0){
            alert('Choose .csv file first');
            return;
        }
        parse_csv(files, function(import_data){
            console.log('import_data', import_data);
            if(import_data.length){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : "{{route('import.income')}}",
                    type : 'POST',
                    data: {data: import_data},
                    beforeSend: function() {            
                        $('.loading').show();
                    },
                    complete: function(){
                        $('.loading').hide();
                    },
                    success: function(data){
                        // console.log(data);
                        $('.income_import_err').html('');
                       
                        if(data.warnings && data.warnings.length){
                            //some errors found, display them
                            var _errs='';
                            data.warnings.forEach(function(err,i){
                                _errs+="<li>"+err.msg+"</li>";
                            });
                            var _msg = $(basic_alert);
                            _msg.find('.alert-text').html("<h4 class='alert-heading'>Some Errors Occured!</h4><ul>"+_errs+"</ul>");
                            $('.income_import_err').html(_msg.html());
                            // return;
                        }
                        swal.fire({
                            position: 'center',
                            type: 'success',
                            title: 'Record imported successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        performance_table.ajax.reload(null, false);
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
    });
    var parse_csv=function(files,callback=null,parsed_data=[]){
        if(files.length){
            //still data to parse
            var _file = files.pop();
            Papa.parse(_file.data, {
                header:true,
                dynamicTyping: true,
                beforeFirstChunk: function( chunk ) {
                    var rows = chunk.split( /\r\n|\r|\n/ );
                    var headings = rows[0].split( ',' );console.warn(headings);
                    headings.forEach(function(_d, _i){
                        if(headings[_i].includes('week@')) {
                            headings[_i]=_d;
                            // var _str = headings[_i].split('@')[1];

                            // var week_start = _str.split('_')[0];
                            // var week_end = _str.split('_')[1];
                            // headings[_i]='week_start@'+week_start+',week_end@'+week_end;
                        }
                        else headings[_i]=_d.trim().replace(/ /g, '_').replace(/[0-9]/g, '').replace('(AED)', '_aed').toLowerCase();
                    });
                    rows[0] = headings.join();
                    return rows.join( '\n' );
                }, 
                error: function(err, file, inputElem, reason){ console.log(err); },
                complete: function(results, file){ 
                    var import_data = results.data;
                    console.log('import_data', import_data);
                    import_data.forEach(function(item,i){
                        // console.log(import_data[i]);
                        if(item.captain_id && item.captain_id != null){
                            var _firstKey = Object.keys(import_data[i]);
                            if(_firstKey && _firstKey[0] && _firstKey[0].includes('week@')){
                                var _data = _firstKey[0];
                                var _str = _data.split('@')[1];

                                var week_start = _str.split('_')[0];
                                var week_end = _str.split('_')[1];
                                item.week_start=week_start;
                                item.week_end=week_end;
                                delete item[_firstKey[0]];
                                parsed_data.push(item);
                            }
                            else{
                                parsed_data.push(item);
                            }
                        }
                    });
                    parse_csv(files,callback,parsed_data);
                }
            });
        }
        else{
            //all data parsed - call the callback
            if(typeof callback=="function") callback(parsed_data);
        }
    }
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


});
function deleteRow(id)
{
    var url = "{{ url('admin/accounts/client_income/delete') }}"+ "/" + id  ;
    console.log(url,true);
    sendDeleteRequest(url, false, null, client_income_table);
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