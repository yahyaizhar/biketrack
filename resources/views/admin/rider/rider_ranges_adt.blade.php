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
                        <th>Status</th>                       
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="extraStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header border-bottom-0">
        <h5 class="modal-title" >Additional Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form id="extraStatusField" class="kt-form" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label>Called Over:</label>
                    <input type="text" class="form-control" name="called_over" placeholder="Enter Source Of Contact">
                    <span class="form-text text-muted">Please enter your Source of Contact</span>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <input type="text" class="form-control" name="status" placeholder="Enter Status">
                    <span class="form-text text-muted">What Your Status in Kingriders</span>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <input type="text" class="form-control" name="comments" placeholder="Enter Comments">
                    <span class="form-text text-muted">Comments Given By Kingriders</span>
                </div>
            </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
        </form>
    </div>
    </div>
</div>
<table style="display:none;" id="export_excel_table">
        <tbody>
        <tr> 
          <td colspan="6"><h1>King Rider's Performance Report Audit</h1></td>
        </tr>
        <tr>
          <th style="text-align:left">Date Duration</th>
          <td class="date_append"></td>
        </tr>
         <tr>
          <th style="text-align:left">Supervisor</th>
          <td>Danish Munir</td>
        </tr>
         <tr>
          <th style="text-align:left">Operator</th>
          <td>Sohaib Faheem</td>
        </tr>
        <tr><td></td></tr>
        <tr>
          <th style="text-align:left">Performance</th>
          <th style="text-align:left">Color Presentation</th>
          <th style="text-align:left">Number of riders</th>
        </tr>
        <tr>
          <td>Good ADT</td>
          <td style="background-color:#c5e0b3">Good Performance</td>
          <th style="text-align:left" class="good_performance"></th>
        </tr>
        <tr>
          <td>Average ADT</td>
          <td style="background-color:#ffff00">Warning Given</td>
          <th style="text-align:left" class="warning_given"></th>
        </tr>
        <tr>
          <td>Unavailable</td>
          <td style="background-color:#ff0000">Rider Replaced</td>
          <th style="text-align:left" class="replaced"></th>
        </tr>
         <tr><td></td></tr>
         <tr>
         <th style="text-align:left" colspan="2">Supervisor Comment</th>
         <td colspan="4">Some riders are performing well and some are improving their performance. Hopefull statistics will better in next report.</td>
         </tr>
         <tr>
           <th style="text-align:left" colspan="2">Operator Comment</th>
           <td colspan="4">We're working hard to improve our rider's performance in such a way that they perform their duties with hardworking. You will get awesome results very soon.</td>
         </tr>
         <tr><td></td></tr>
         <tr><td></td></tr>
         <tr>
            <th style="text-align:left">FEID</th> 
            <th style="text-align:left">Rider Name</th>
            <th style="text-align:left">Area</th>
            <th style="text-align:left">Range1 ADT</th>
            <th style="text-align:left">Range2 ADT</th>
            <th style="text-align:left">Improvement %</th>
            <th style="text-align:left">Called Over</th>
            <th style="text-align:left">Status</th>
            <th style="text-align:left">Operator Comments</th>
            <th style="text-align:left"></th>
        </tr>
    </tbody>
    </table>
{{-- @php
    $model = new \App\Model\Accounts\Fuel_Expense;
    echo get_class($model);
@endphp --}}
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('/dashboard/assets/js/jquery.tableToExcel.js') }}" type="text/javascript"></script>

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

$(".date_append").html("");
$(".good_performance").html("");
$(".warning_given").html("");
$(".replaced").html("");
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
    var date3=r1 +'-'+r4;
    var _perData=riders_data;
//     var export_details=[];
//     _perData.forEach(function(item,index) { 
//         export_details.push({
//         "FEID":item.feid,    
//         "Rider Name":item.rider_id,
//         "Locality":item.area,
//         "DATE 1":date1,
//         "ADT 1":item.adt1,
//         "DATE 2":date2,
//         "ADT 2":item.adt2,
//         "Improvements":item.improvements,
//         "Called Over":item.called_over,
//         "Status":item.status,
//         "Operator Comments":item.comments,
//     });
// });
        // var export_data = new CSVExport(export_details, "Kingriders ADT Performance");
        
            
            var val='';
            var good_performance=0;
            var warning=0;
            var replaced=0;
            $(".main_row").remove();
            $(".date_append").html(date3);
            _perData.sort(function(a,b){
                return a.adt2 <b.adt2?-1:a.adt2 > b.adt2?1:0;
            });
        _perData.forEach(function(item,index){
            var tr='';
            var type='';
            if(item.adt2 <= 40){
                good_performance++;
                type='good_performance';
            }
            if(item.adt2 > 40 && item.adt2 <=50){
                warning++;
                type='warning';
            }
            if(item.adt2 > 50 || item.adt2 == 0){
                replaced++;
                type='replaced';
            }
            tr+='<td class="feid">'+item.feid+'</td>';
            tr+='<td class="rider_name">'+item.name+'</td>'
            tr+='<td class="area">'+item.area+'</td>'
            tr+='<td class="adt1">'+item.adt1+'</td>'
            tr+='<td class="adt2">'+item.adt2+'</td>'
            tr+='<td class="improvements">'+item.improvements+'</td>'
            tr+='<td class="called_over">'+item.called_over+'</td>'
            tr+='<td class="status">'+item.status+'</td>'
            tr+='<td class="comments" colspan="2">'+item.comments+'</td>'
            val+='<tr class="main_row" data-type="'+type+'">'+tr+'</tr>'
        });
        console.log(_perData);
        $(".good_performance").html(good_performance);
        $(".warning_given").html(warning);
        $(".replaced").html(replaced);
        $("#export_excel_table tbody").append(val); 

        $('tr.main_row[data-type="good_performance"]').eq(0).append('<td style="background-color:#c5e0b3; font-size:32px; text-align:center;" colspan="4" rowspan="'+good_performance+'">Good Performers</td>');
        $('tr.main_row[data-type="warning"]').eq(0).append('<td style="background-color:#ffff00; font-size:32px; text-align:center;" colspan="4" rowspan="'+warning+'">Average Performers</td>');
        $('tr.main_row[data-type="replaced"]').eq(0).append('<td style="background-color:#ff0000; font-size:32px; text-align:center;" colspan="4" rowspan="'+replaced+'">Repaced/Bad Performers</td>');
        $("#export_excel_table").tblToExcel();
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
            { data: 'action', name: 'action' },
        ],
        responsive:true,
        order:[0,'desc'],
    });
}
function extraFields(feid){
$("#extraStatus").modal("show");
$('form#extraStatusField').on("submit",function(e){
    e.preventDefault(); 
    var form=$(this);
    var url="{{ url('admin/update/extra/fields/adt/performance') }}"+ "/" +feid;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, 
        url:  url,
        data: form.serializeArray(),
        method: "GET"
    })
    .done(function(data) {  
        console.log(data);
        $("#extraStatus").modal("hide");
        window.location.reload();
        swal.fire({
            position: 'center',
            type: 'success',
            title: 'Record updated successfully.',
            showConfirmButton: false,
            timer: 1500
        });
    });
});
}
</script>
@endsection
