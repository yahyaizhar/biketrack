@extends('admin.layouts.app')
@section('head')
<link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
.highlighted{
    background-color: #FFFF88;
}
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
                    <i class="kt-font-brand fa fa-motorcycle"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                   Tax View
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped table-hover table-checkable table-condensed" id="tax-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Bike Number</th>
                        <th>Chassis Number</th>
                        <th>Status</th>
                        <th class="d-none"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- end:: Content -->

@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/mark.js/8.6.0/jquery.mark.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script>
var tax_table;
var tax_data = [];
$(function() {
    var _settings = {
        processing: true,
        serverSide: true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show() 
        },
        drawCallback:function(data){
            var api = this.api();
            var _data = api.data();
            var keys = Object.keys(_data).filter(function(x){return !isNaN(parseInt(x))});
            keys.forEach(function(_d,_i) {
                var __data = JSON.parse(JSON.stringify(_data[_d]).toLowerCase());
                tax_data.push(__data);
            });
            // dataTables_info
            $('.total_entries').remove();
            $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
            mark_table();
        },
        ajax: '{!! route('bike.bike_show') !!}',
        columns: null,
        responsive:true, 
        order:[0,'desc']
    };
    if(window.outerWidth>=720){
        //visa_expiry
        $('#tax-table thead tr').prepend('<th></th>');
        _settings.columns=[
            {
            "className":      'details-control',
            "orderable":      false,
            "data":           null,
            "defaultContent": ''
        },
            { "data": 'id', "name": 'id' },
            { "data": 'brand', "name": 'brand' },
            { "data": 'model', "name": 'model' },
            { "data": 'bike_number', "name": 'bike_number' },
            { "data": 'chassis_number', "name": 'chassis_number' },
            { "data": 'status', "name": 'status' },
            { "data": 'assigned_to', "name": 'assigned_to' },
        ];
        _settings.responsive=false;
        _settings.columnDefs=[
            {
                "targets": [7],
                "visible": false,
                searchable: true,
            },
        ];
    }
    tax_table = $('#tax-table').DataTable(_settings);
    var mark_table = function(){
        var _val = tax_table.search();
        if(_val===''){
            $("#tax-table tbody").unmark();
            $("#tax-table tbody > tr:visible").each(function() {
                var tr = $(this);
                var row = tax_table.row( tr );
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.remove();
                    tr.removeClass('shown');
                }
            });
            return;
        }
        $('#tax-table tbody > tr[role="row"]:visible').each(function() {
            var tr = $(this);
            var row = tax_table.row( tr );
            // console.warn("isShon: ",row.child.isShown());
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.remove();
                tr.removeClass('shown');
            }
                // This row is already open - close it
                var _arow = row.child( format(row.data()) );
                _arow.show();
                tr.addClass('shown');
        });
        $("#tax-table tbody").unmark({
            done: function() {
                $("#tax-table tbody").mark(_val, {
                    "element": "span",
                    "className": "highlighted"
                });
            }
        });
        
    }


    if(window.outerWidth>=720){
        $('#tax-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tax_table.row( tr );
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                var _arow = row.child( format(row.data()) );
                _arow.show();
                tr.addClass('shown');
            }
        });
    }
function format ( data ) {
    var a="Pulsar";
    var b="Honda Unicorn";
    return '<form class="kt-form" action="{{ route('bike.bike_create') }}" method="POST" enctype="multipart/form-data">  '  + 
 '                       {{ csrf_field() }}  '  + 
 '                       <div class="kt-portlet__body">  '  + 
 '                             '  + 
 '                           <div class="form-group">  '  + 
 '                               <label>Bike Number(etc K-3102):</label>  '  + 
 '                               <input type="text" required class="form-control @if($errors->has('bike_number')) invalid-field @endif" name="bike_number" placeholder="Enter Bike_Number (etc K-3102)" value="'+data.bike_number+'">  '  + 
 '                               @if ($errors->has('bike_number'))  '  + 
 '                                   <span class="invalid-response" role="alert">  '  + 
 '                                       <strong>  '  + 
 '                                           {{$errors->first('bike_number')}}  '  + 
 '                                       </strong>  '  + 
 '                                   </span>  '  + 
 '                               @endif  '  + 
 '                           </div>  '  + 
 '                           <div class="form-group">  '  + 
 '                                   <label>Brand(etc Honda):</label>  '  + 
 '                                   {{-- <input type="text" class="form-control @if($errors->has('brand')) invalid-field @endif" name="brand" placeholder="Enter Brand (etc Honda)" value="{{ old('brand') }}"> --}}  '  + 
 '                                   <select class="form-control @if($errors->has('brand')) invalid-field @endif kt-select2" id="kt_select2_3" name="brand" placeholder="Enter Brand (etc Honda)" required >  '  + 
 '                                           <option  value="Honda Unicorn" @if ('+data.brand==b +') selected @endif>Honda Unicorn</option>  '  + 
 '                                           <option  value="Pulsar" @if ('+data.brand==a +') selected @endif>Pulsar</option>  '  + 
 '                                           </select>   '  + 
 '                                   @if ($errors->has('brand'))  '  + 
 '                                       <span class="invalid-response" role="alert">  '  +  
 '                                           <strong>  '  + 
 '                                               {{$errors->first('brand')}}  '  + 
 '                                           </strong>  '  + 
 '                                       </span>  '  + 
 '                                   @endif  '  + 
 '                               </div>  '  + 
 '                               <div class="form-group">   '  + 
 '                                       <label>Chassis Number:</label>  '  + 
 '                                       <input type="text" class="form-control @if($errors->has('chassis_number')) invalid-field @endif" name="chassis_number" placeholder="Enter Chassis_Number" required value="'+data.chassis_number+'">  '  + 
 '                                       @if ($errors->has('chassis_number'))  '  + 
 '                                           <span class="invalid-response" role="alert">  '  + 
 '                                               <strong>  '  + 
 '                                                   {{$errors->first('chassis_number')}}  '  + 
 '                                               </strong>  '  + 
 '                                           </span>  '  + 
 '                                       @endif  '  + 
 '                                   </div>  '  + 
 '                          '  + 
 '                            '  + 
 '                           '  + 
 '                       </div>  '  + 
 '                         '  + 
 '                       <div class="kt-portlet__foot">  '  + 
 '                           <div class="kt-form__actions kt-form__actions--right">  '  + 
 '                               <button type="submit" class="btn btn-primary">Submit</button>  '  + 
 '                               <span class="kt-margin-l-10">or <a href="{{ route('admin.riders.index') }}" class="kt-link kt-font-bold">Cancel</a></span>  '  + 
 '                           </div>  '  + 
 '                       </div>  '  + 
 '                  </form>  ' ; 
}
});
</script>
<style>
    td.details-control {
        background: url('https://biketrack-dev.solutionwin.net/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('https://biketrack-dev.solutionwin.net/details_close.png') no-repeat center center;
    }
</style>
@endsection