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
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        Bill Detail By Month
                        </h3>
                    </div>
                </div>
            <div class="kt-portlet__body">
            <div class="form-group">
                <label>Clients:</label>
                <select class="form-control bk-select2 kt-select2-general" name="client_id" >
                    @foreach ($clients as $client)
                    <option value="{{ $client->id }}">
                        {{ $client->name }}
                    </option>     
                    @endforeach 
                </select>
            </div>
            <div>
                <select class="form-control bk-select2" id="kt_select2_3_5" name="month_id" >
                    @for ($i = 0; $i <= 12; $i++)
                        @php
                            $_m =Carbon\Carbon::now()->startOfMonth()->addMonth(-$i);
                        @endphp
                        <option value="{{$_m->format('Y-m-d')}}">{{$_m->format('F-Y')}}</option>
                    @endfor   
                </select> 
                </div>
            </div>
        </div>
    </div>
</div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="bills_hidden">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Bills Details
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        {{-- <a href="{{ route('admin.accounts.id_charges_index') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="charges-table">
                <thead>
                    <tr>
                        <th>Rider Name</th>
                        <th>Sim Bill</th>
                        <th>Bike Rent</th>
                        {{-- <th>Bike Bills</th> --}}
                        <th>Bike Fines</th>     
                        <th>Fuel</th>
                        <th>Salik</th>
                        <th>Salary</th>                    
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script>
$("#bills_hidden").hide();
var charges_table;  
$("[name='client_id'] , #kt_select2_3_5").on("change",function(){
    var client_id=$("[name='client_id']").val();
    var month=$("#kt_select2_3_5").val();
    var month_url=new Date(month).format("yyyy-mm-dd");
            var data = {
                month:month_url,
                client_id:client_id,
            }
            biketrack.updateURL(data);
    $("#bills_hidden").show();
    $(function() {
    var url="{!! url('/admin/ajax/generated/rider/bill/status/"+month+"/"+client_id+"') !!}";
    charges_table = $('#charges-table').DataTable({
        lengthMenu: [[-1], ["All"]],
        processing: true,
        serverSide: true,
        destroy:true,
        'language': { 
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
        $('.total_entries').remove();
        $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
    },
        ajax: url,
        columns: [
            { data: 'rider_id', name: 'rider_id' },
            { data: 'sim_bill', name: 'sim_bill' },            
            { data: 'bike_rent', name: 'bike_rent' },
            // { data: 'bike_bill', name: 'bike_bill' }, 
            { data: 'bike_fine', name: 'bike_fine' },
            { data: 'fuel', name: 'fuel' },
            { data: 'salik', name: 'salik' },
            { data: 'salary', name: 'salary' },
        ],
        responsive:true,
        order:[0,'asc'],
    });
});
});

var mon=biketrack.getUrlParameter('month');
var client=biketrack.getUrlParameter('client_id');
if (mon!="" && client!="" ) {
    $('#kt_select2_3_5').val(mon).trigger('change.select2');
}
    $("[name='client_id']").val(client).trigger("change");

        
</script>
@endsection