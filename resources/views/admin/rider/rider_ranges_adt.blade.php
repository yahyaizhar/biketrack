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
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hotel"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Rider Ranges ADT
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
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
                        <th>Rider Name</th>
                        <th>Range1 ADT</th>
                        <th>Range2 ADT</th>
                        <th>Improvement %</th>                       
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>

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
            
            console.log(data);
            biketrack.updateURL(data);
            console.log($date_data1);
            console.log($date_data2);
            var url = "{{ url('admin/range/ajax/adt/') }}"+"/"+JSON.stringify(_data) ;
            getData(url);
        });
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
        destroy: true,
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
        ajax: url,
        columns: [
            { data: 'rider_id', name: 'rider_id' },            
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