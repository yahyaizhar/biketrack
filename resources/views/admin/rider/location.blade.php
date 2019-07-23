@extends('admin.layouts.app')
@section('head')

@endsection
@section('main-content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div id="location-not-available" style="display:none;">
        <div class="kt-section__content ">
            <div class="alert alert-danger fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">Location not available.</div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="la la-close"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--Begin::Section-->
    <div class="row">
            <div class="col-sm-12 col-md-12 col-xl-12">
                <!--begin:: Widgets/Top Locations-->
                <div class="kt-portlet kt-portlet--head--noborder kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                <a href="{{ route('admin.rider.profile', $rider->id) }}">{{ $rider->name }}</a> - Location
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget15">
                            <div class="kt-widget15__map">
                                {{-- <div id="kt_chart_latest_trends_map" style="height:640px;"></div> --}}
                                <div id="map" style="height:640px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Top Locations-->
            </div>
        </div>
    
</div>
        <!--End::Section-->
@endsection
@section('foot')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBovpIsxtSqMTpBMysndin00-26bcWy3YM&callback=initMap"
async defer></script>
<script>
    
    var map;
    var mapMarkers = [];
    var timerID;
    var infoWindowContent = [];
    var infowindow;
    var infowindow;
    var click = false;
    var map_center = {
        lat: parseFloat({{ $rider->getLatestLocation($rider->id) ? $rider->getLatestLocation($rider->id)->latitude : 30.3753 }}),
        lng: parseFloat({{ $rider->getLatestLocation($rider->id) ? $rider->getLatestLocation($rider->id)->longitude : 69.3451 }})
    }

    function start() {
        clearInterval(timerID);
        getRidersLocations();
        timerID = setInterval(function () {
            getRidersLocations();
        }, 15000);
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            mapTypeControl: false,
            // center: {lat: 30.3753, lng: 69.3451},
            center: {lat: map_center.lat, lng: map_center.lng},
            zoom: 10
        });
        start();
    }
    function getRidersLocations()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            url : "{{ route('api.admin.rider.location', $rider->id) }}",
            type : 'GET',
            dataType: 'JSON',
            // beforeSend: function() {            
            //     $('.loading').show();
            // },
            // complete: function(){
            //     $('.loading').hide();
            // },
            success: function(data){
                $('#location-not-available').hide();
                var status = data.status;
                if(status)
                {
                    var location = data.data;
                    var count = 0;
                    info_count = 0;
                    count++;
                    addMarker(location, count);
                }
            },
            error: function(error){
                // console.log(error);
                $('#location-not-available').show();
                // alert('error');
            }
        });
    }
    function setMapOnAll(map) {
        for (var i = 0; i < mapMarkers.length; i++) {
            mapMarkers[i].setMap(map);
        }
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
    }

    // Shows any markers currently in the array.
    function showMarkers() {
        setMapOnAll(map);
    }

    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
        clearMarkers();
        mapMarkers = [];
        infoWindowContent = [];
        info_count = 0;
    }
    function addMarker(locations, count)
    {
        var rider_name = locations.name;
        var phone = locations.phone;
        var vehicle_number = locations.vehicle_number;
        var lat = parseFloat(locations.latitude);
        var lng = parseFloat(locations.longitude);
        deleteMarkers();
        var contentString = '<div class="map-box"><ul><li style="margin-left:0px;"><b>Name: </b>'+rider_name+'</li><li><b>Bike No: </b>'+vehicle_number+'</li><li><b>Phone: </b>'+phone+'</li></ul></div>';
        var marker = new google.maps.Marker(
            {position: {lat: lat, lng: lng},
            map: map,
            // label: {color: 'blue', fontSize: '14px', text: vehicle_number}
        });
        if(locations.online)
        {
            // marker.setIcon("{{ asset('dashboard/assets/media/custom/icon-bike.png') }}");
            marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
        }
        else
        {
            marker.setIcon("http://maps.google.com/mapfiles/ms/icons/red-dot.png");
        }
        mapMarkers.push(marker);
        infoWindow = new google.maps.InfoWindow({
            content : contentString
        });
        marker.addListener('mouseover', function(){
            infoWindow.open(map, marker);
        });

        // assuming you also want to hide the infowindow when user mouses-out
        // marker.addListener('mouseout', function() {
        //     infoWindow.close();
        // });
        marker.addListener('click', function(){
            infoWindow.open(map, marker);
            clicked = true;
        });
    }
    
</script>
@endsection
