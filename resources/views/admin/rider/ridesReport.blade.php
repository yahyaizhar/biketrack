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

@include('admin.includes.message')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

      
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-motorcycle"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Rides Reports 
               </h3>
                
            </div>
            {{-- <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        &nbsp;
                        <a href="{{ route('admin.riders.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped table-hover table-checkable table-condensed" id="rides-report-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rider Name</th>
                        <th>Starting Time</th>
                        <th>Ending Time</th>
                        <th>Online Time</th>
                        {{-- <th>No of Trips</th>
                        <th>No of Hours</th> --}}
                        {{-- <th>Created at</th> --}}
                        <th>Starting/Ending-location</th>
                        {{-- <th>Mileage</th> --}}
                        
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            
            <!--end: Datatable -->
        </div>
    </div>
    
</div>

<div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">See starting and ending location on map</h4>
          <button type="button" class="close" data-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="locations_map" style="width:100%;height:300px;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<!-- end:: Content -->
@endsection
@section('foot')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('dashboard/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/datatables/basic/basic.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
<script>
var rides_report_table;
$(function() {
    rides_report_table = $('#rides-report-table').DataTable({
        drawCallback: function( ) {
            // console.log('a');
     time_conversion();
  },
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
        ajax: '{!! route('admin.ridesReport.data', $rider->id) !!}',
        
        columns: [
            { data: 'id', name: 'id' },
            { data: 'rider_name', name: 'rider_name' },
            { data: 'start_time', name: 'start_time' },
            { data: 'end_time', name: 'end_time' },
            { data: 'online_hours', name: 'online_hours' },
            // { data: 'no_of_trips', name: 'no_of_trips' },
            // { data: 'no_of_hours', name: 'no_of_hours' },
            { data: 'start/end-location', name: 'start/end-location' },
            { data: 'actions', name: 'actions' }
        ],
        responsive:true,
        order:[0,'desc']
    });
});
function deleteRecord(id){
    var url = "{{ url('admin/ridesReportRecord') }}"+ "/" + id;
    sendDeleteRequest(url, false, null, rides_report_table);
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBovpIsxtSqMTpBMysndin00-26bcWy3YM&callback=initMap"
async defer></script>
<script> 
    function initMap() {

        var get_attr_start;
        var get_attr_end;
var start_marker=null;
var end_marker=null;
 var  map = new google.maps.Map(document.getElementById('locations_map'), {
            mapTypeControl: false,
            center: {lat: 25.2048, lng: 55.2708},
            zoom: 10,
        });
        if (start_marker=="" && end_marker==""){
            deleteMarkers();
        }
        $("#myModal").on('shown.bs.modal', function (event) {
            var get_attr_start=$(event.relatedTarget).attr("data_start").replace(/\'/g, '"'); 
            var get_attr_end=$(event.relatedTarget).attr("data_end").replace(/\'/g, '"'); 
            console.log(get_attr_end);
if(start_marker!=null){
    start_marker.setMap(null);
}
if(end_marker!=null){
    end_marker.setMap(null);
}
            end_marker = new google.maps.Marker(
                {position:JSON.parse(get_attr_end),
                // animation: google.maps.Animation.DROP,
                icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
                map: map,
                title:"Ended Location"
                // label: {color: 'blue', fontSize: '14px', text: vehicle_number}
            });
             start_marker = new google.maps.Marker(
                {
                position: JSON.parse(get_attr_start),
                // animation: google.maps.Animation.DROP,
                icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
                map: map,
                title:"Started Location"
                // label: {color: 'blue', fontSize: '14px', text: vehicle_number}
            }); 
           
        
           });
        
        
        
    }
   
      



</script>
@endsection