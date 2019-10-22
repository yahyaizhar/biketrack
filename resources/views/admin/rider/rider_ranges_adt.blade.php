@extends('admin.layouts.app')
@section('head')
    <link href="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
            <div class="kt-portlet__head-label col-md-6">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Rider Ranges ADT
                </h3>
                <input style="margin-right:10px;" class="btn btn-primary" type="button" onclick="export_data()" value="Export Zomato Data">
            <div class="form-group" style="display:contents;">
                <div class="kt-radio-inline">
                    <label class="kt-radio">
                        <input type="radio" name="payment_status" id="report" value="report" checked> By Report
                        <span></span>
                    </label>
                    <label class="kt-radio">
                        <input type="radio" name="payment_status" id="date" value="date"> By Date
                        <span></span>
                    </label>
                </div>
            </div>
            </div>
            <div class="kt-portlet__head-toolbar col-md-6">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions" id="Report_range">
                            Range1: <select class="range_report"  name="range_report">
                            @foreach ($ZAD as $item)
                                <option id="report_date1" data-r1="{{$item['r1']}}" data-r2="{{$item['r2']}}">{{$item['r2']}} - {{$item['r1']}}</option>
                            @endforeach
                        </select>  
                        Range2: <select class="range_report"  name="range_report">
                            @foreach ($ZAD as $item)
                               <option id="report_date2" data-r1="{{$item['r1']}}" data-r2="{{$item['r2']}}">{{$item['r2']}} - {{$item['r1']}}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="kt-portlet__head-actions" id="ranges_adt">
                        Range1:     <input type="text" id="datapick1" name="daterange" />
                        Range2:     <input type="text" id="datapick2" name="daterange" />
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <table class="table table-striped- table-hover table-checkable table-condensed" id="bike-table">
                <thead>
                    <tr>
                        <th>FEID</th>
                        <th>Rider Name</th>
                        <th>Area</th>
                        <th>Range1 ADT</th>
                        <th>Range2 ADT</th>
                        <th>Improvement %</th>                       
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
{{-- @php
    $model = new \App\Model\Accounts\Fuel_Expense;
    echo get_class($model);
@endphp --}}
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
   $(document).ready(function(){
       $("#ranges_adt").hide();
       $('.range_report').eq(0).trigger('change');
       $("#report").change(function(){
        $("#ranges_adt").hide();
        $("#Report_range").show();
        $('.range_report').eq(0).trigger('change');
       });
       $("#date").change(function(){
        $("#ranges_adt").show();
        $("#Report_range").hide();
       });
   });
var riders_data = [];

function export_data(){
    var r1d1=biketrack.getUrlParameter('r1d1');
    var r1d2=biketrack.getUrlParameter('r1d2');
    var r2d1=biketrack.getUrlParameter('r2d1');
    var r2d2=biketrack.getUrlParameter('r2d2');
    var r1=(new Date(r1d1)).format('dd mmm');
    var r2=(new Date(r1d2)).format('dd mmm');
    var r3=(new Date(r2d1)).format('dd mmm');
    var r4=(new Date(r2d2)).format('dd mmm');
    var date1=r1 +'-'+r2;
    var date2=r3 +'-'+r4;
    var _perData=riders_data;
    var export_details=[];
    _perData.forEach(function(item,index) { 
        export_details.push({
        "FEID":item.feid,    
        "Rider Name":item.rider_id,
        "Locality":item.area,
        "DATE 1":date1,
        "ADT 1":item.adt1,
        "DATE 2":date2,
        "ADT 2":item.adt2,
        "Improvements":item.improvements,
        "Called Over":"",
        "Status":"",
        "Operator Comments":"",
    });
});
        var export_data = new CSVExport(export_details, "Kingriders ADT Performance");
        return false;
}

$(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left', 
            locale: {
                format: 'DD-MM-YYYY '
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
            $date_data1=$(".datapick1").val();
            $date_data2=$(".datapick2").val();
            var _data = {
                range1: {
                    start_date:$('#datapick1').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    end_date: $('#datapick1').data('daterangepicker').endDate.format('YYYY-MM-DD')
                },
                range2: {
                    start_date:$('#datapick2').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    end_date: $('#datapick2').data('daterangepicker').endDate.format('YYYY-MM-DD')
                }
            };
            var data = {
                r1d1:_data.range1.start_date, 
                r1d2:_data.range1.end_date, 
                r2d1:_data.range2.start_date,
                r2d2:_data.range2.end_date
            }
            biketrack.updateURL(data);
            var url = "{{ url('admin/range/ajax/adt/') }}"+"/"+JSON.stringify(_data) ;
            getData(url);
            
        });
        
        
});

$(function() { 
    
    $('.range_report').change(function(){
        var a=$('.range_report').eq(0).find(':selected').attr('data-r1');    
        var b=$('.range_report').eq(0).find(':selected').attr('data-r2');  
        var c=$('.range_report').eq(1).find(':selected').attr('data-r1');    
        var d=$('.range_report').eq(1).find(':selected').attr('data-r2');   
        getreports(b,a,d,c);
    });

    function getreports(r1,r2,r3,r4) {
            var _data = {
                range1: {
                    start_date:r1,
                    end_date:r2
                },
                range2: {
                    start_date:r3,
                    end_date: r4
                }
            };
            var data = {
                r1d1:_data.range1.start_date, 
                r1d2:_data.range1.end_date, 
                r2d1:_data.range2.start_date,
                r2d2:_data.range2.end_date
            }
            biketrack.updateURL(data);
            var url = "{{ url('admin/range/ajax/adt/') }}"+"/"+JSON.stringify(_data) ;
            getData(url);
        }
});

var bike_table;
$(function() {
    var r1d1=biketrack.getUrlParameter('r1d1');
    var r1d2=biketrack.getUrlParameter('r1d2');
    var r2d1=biketrack.getUrlParameter('r2d1');
    var r2d2=biketrack.getUrlParameter('r2d2');
    if(r1d1!="" && r1d2!="" && r2d1!="" && r2d2!=""){
        new Date(r1d1).format('mm-dd-yyyy')
        $('input[name="daterange"]').eq(0).val(new Date(r1d1).format('dd-mm-yyyy')+' - '+new Date(r1d2).format('dd-mm-yyyy'));
        $('input[name="daterange"]').eq(1).val(new Date(r2d1).format('dd-mm-yyyy')+' - '+new Date(r2d2).format('dd-mm-yyyy'));
        var _data = {
            range1: {
                start_date:r1d1,
                end_date: r1d2
            },
            range2: {
                start_date:r2d1,
                end_date: r2d2
            }
        };
        var url = "{{ url('admin/range/ajax/adt/') }}"+"/"+JSON.stringify(_data) ;
        getData(url);
        
    }
});
var getData = function(url){
    bike_table = $('#bike-table').DataTable({
        "lengthMenu": [[-1], ["All"]],
        destroy: true,
        processing: true,
        serverSide: true,
        
        'language': { 
            'loadingRecords': '&nbsp;',
            'processing': $('.loading').show()
        },
        drawCallback:function(data){
            riders_data = [];
            var api = this.api();
            var _data = api.data();
            var keys = Object.keys(_data).filter(function(x){return !isNaN(parseInt(x))});
            keys.forEach(function(_d,_i) {
                var __data = JSON.parse(JSON.stringify(_data[_d]).toLowerCase());
                riders_data.push(__data);
                console.log(riders_data)
            });
            $('.total_entries').remove();
            $('.dataTables_length').append('<div class="total_entries">'+$('.dataTables_info').html()+'</div>');
        },
        ajax : {
            url : url,
            dataSrc: function(json) {
                var rows = [];
                for (var i=0;i<json.data.length;i++) {
                    //skip rows "if a condition is met"
                    //here just any rows except row #1
                    var adt1 = parseFloat(json.data[i].adt1);
                    var adt2 = parseFloat(json.data[i].adt2);
                    if(!(adt1 == 0 && adt2 == 0))rows.push(json.data[i]);
                }
                return rows;
            }
        },
        columns: [
            { data: 'feid', name: 'feid' },
            { data: 'rider_id', name: 'rider_id' },    
            { data: 'area', name: 'area' },        
            { data: 'adt1', name: 'adt1' },
            { data: 'adt2', name: 'adt2' },
            { data: 'improvements', name: 'improvements' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
}
</script>
@endsection