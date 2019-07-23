@extends('client.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid mt-minus-60" id="kt_content">

    <!--Begin::Dashboard 3-->
    <div id="location-not-available" style="display:none;">
        <div class="kt-section__content ">
            <div class="alert alert-danger fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">Locations not available.</div>
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
                            Riders Locations
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget15">
                        <div class="kt-widget15__map">
                            {{-- <div id="kt_chart_latest_trends_map" style="height:640px;"></div> --}}
                            <div id="map" style=" width:100%; height:640px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Top Locations-->
        </div>
    </div>

    <!--End::Section-->

    <!--End::Dashboard 3-->
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
    var latitude = {{ Auth::user()->latitude }} ? {{ Auth::user()->latitude }} : 25.2048;
    var longitude = {{ Auth::user()->longitude }} ? {{ Auth::user()->longitude }} : 55.2708;
    var map_center = {
        lat: parseFloat(latitude),
        lng: parseFloat(longitude)
    }

    function start() {
        clearInterval(timerID);
        // addClientMarker();
        getRidersLocations();
        timerID = setInterval(function () {
            getRidersLocations();
        }, 15000);
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            mapTypeControl: false,
            center: {lat: map_center.lat, lng: map_center.lng},
            zoom: 8
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
            url : "{{ url('api/client') }}" + "/" + {{ Auth::user()->id }} + "/riders",
            type : 'GET',
            dataType: 'JSON',
            success: function(data){
                $('#location-not-available').hide();
                var locations = data.data;
                var count = 0;
                info_count = 0;
                for(var i=0; i < locations.length; i++)
                {
                    count++;
                    console.log(locations[i]);
                    if(locations[i].status)
                    {
                        console.log('jhgjhgb')
                        addMarker(locations[i], count);
                    }
                } 
            },
            error: function(error){
                $('#location-not-available').show();
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
        if(count == 1 && lat!=null)
        {
            deleteMarkers();
        }
        var contentString = '<div class="map-box"><ul><li><b>Name: </b>'+rider_name+'</li><li><b>Vehicle No: </b>'+vehicle_number+'</li><li><b>Phone: </b>'+phone+'</li></ul></div>';
        var marker = new google.maps.Marker(
            {position: {lat: lat, lng: lng},
            map: map,
            // icon: "{{ asset('dashboard/assets/media/custom/icon-bike.png') }}",
            // icon : 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
            // label: {color: 'blue', fontSize: '14px', text: vehicle_number}
        });
        mapMarkers.push(marker);
        
        if(locations.online)
        {
            // marker.setIcon("{{ asset('dashboard/assets/media/custom/icon-bike.png') }}");
            marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
        }
        else
        {
            marker.setIcon("http://maps.google.com/mapfiles/ms/icons/red-dot.png");
        }
        var infoWindow = new google.maps.InfoWindow({
            content : contentString
        });
        marker.addListener('mouseover', function(){
            infoWindow.open(map, marker);
        });

        // assuming you also want to hide the infowindow when user mouses-out
        marker.addListener('mouseout', function() {
            infoWindow.close();
        });
        marker.addListener('click', function(){
            infoWindow.open(map, marker);
            clicked = true;
        });
    }
    function addClientMarker()
    {
        var client_name = "{{ Auth::user()->name }}";
        var client_address = "{{ Auth::user()->address }}";
        var client_phone = "{{ Auth::user()->phone }}";
        var client_lat = parseFloat({{ Auth::user()->latitude }});
        var client_lng = parseFloat({{ Auth::user()->longitude }});
        var contentString = '<div class="map-box"><ul><li><strong>Name: </strong>'+client_name+'</li><li><strong>Address: </strong>'+client_address+'</li><li><strong>Phone: </strong>'+client_phone+'</li></ul></div>';
        var marker = new google.maps.Marker(
            {position: {lat: client_lat, lng: client_lng},
            map: map,
            icon: "{{ asset('dashboard/assets/media/icons/svg/Home/Building.svg') }}",
        });
        var infoWindow1 = new google.maps.InfoWindow({
            content : contentString
        });
        marker.addListener('mouseover', function(){
            infoWindow1.open(map, marker);
        });

        // assuming you also want to hide the infowindow when user mouses-out
        marker.addListener('mouseout', function() {
            infoWindow1.close();
        });
        marker.addListener('click', function(){
            infoWindow1.open(map, marker);
        });
    }
    
</script>
@endsection
