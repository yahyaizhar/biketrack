@extends('admin.layouts.app')
@section('main-content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
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
                                Live Locations
                            </h3>
                        </div>
                    </div>
                    <form class="kt-form" action="{{ route('admin.post.assignArea') }}" method="POST" enctype="multipart/form-data">
                    
                        {{ csrf_field() }}
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>Select Location: </label>
                                <div>
                                    {{-- <select class="form-control kt-select2" id="kt_select2_3" name="rider_id">
                                    @foreach ($riders as $rider)
                                        <option value="{{ $rider->id }}" 
                                            {{-- @foreach ($client->getRiders as $assigned_rider)
                                                @if($rider->id == $assigned_rider->id)
                                                    selected = "selected"
                                                @endif
                                            @endforeach --}}
                                        {{-- >{{ $rider->name }} - {{ $rider->vehicle_number }}</option>
                                    @endforeach
                                    </select> --}} 
                                    <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter Location name" value="{{ old('name') }}">
                                    <span class="form-text text-muted">Enter location name and select area in map.</span>
                                </div>
                                <input type="hidden" name="area_bounds" value="">
                                <div class="kt-portlet__body">
                                    <div class="kt-widget15">
                                        <div class="kt-widget15__map">
                                            {{-- <div id="kt_chart_latest_trends_map" style="height:640px;"></div> --}}
                                            <div id="map" style=" width:100%; height:640px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions kt-form__actions--right">
                                        <button type="submit" class="btn btn-primary">Save Area</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                </div>
                <!--end:: Widgets/Top Locations-->
            </div>
        </div>
    
</div>
        <!--End::Section-->
@endsection
@section('foot')
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBovpIsxtSqMTpBMysndin00-26bcWy3YM&libraries=drawing&callback=initMap"
async defer></script>
<script>
    
    var map;
    var bounds=[];

    function start() {
        clearInterval(timerID);
        getRidersLocations();
        // getClientsLocations();
        timerID = setInterval(function () {
            getRidersLocations();
        }, 15000);
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 25.2048, lng: 55.2708},
            zoom: 10,
        });
        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['polygon']
            },
            polygonOptions: {
                fillColor: '#333',
                fillOpacity: 0.6,
                strokeWeight: 5,
                clickable: true,
                editable: true,
                zIndex: 1
            }
        });
        drawingManager.setMap(map);
        google.maps.event.addListener(drawingManager, 'click', function(event) {
            
            console.log(event);
        });
        google.maps.event.addListener(drawingManager, 'polygoncomplete', function(event) {
            var a = event.getPath().getArray();
            bounds=[];
            a.forEach(function(_d,_i){
                console.warn(_d.lat(), _d.lng())
                bounds.push({lat:_d.lat(), lng:_d.lng()});
            });
            $('[name="area_bounds"]').val(JSON.stringify(bounds));
            google.maps.event.addListener(event.getPath(), 'insert_at', function(index, obj) {
           //polygon object: yourPolygon
                var a = event.getPath().getArray();
                bounds=[];
                a.forEach(function(_d,_i){
                    console.warn(_d.lat(), _d.lng())
                    bounds.push({lat:_d.lat(), lng:_d.lng()});
                });
                $('[name="area_bounds"]').val(JSON.stringify(bounds));
            });
            google.maps.event.addListener(event.getPath(), 'set_at', function(index, obj) {
                //polygon object: yourPolygon
                var a = event.getPath().getArray();
                bounds=[];
                a.forEach(function(_d,_i){
                    console.warn(_d.lat(), _d.lng())
                    bounds.push({lat:_d.lat(), lng:_d.lng()});
                });
                $('[name="area_bounds"]').val(JSON.stringify(bounds));
            });
        });
    }
    function getRidersLocations()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            url : "{{ url('api/admin/liveLocations') }}",
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
                var locations = data.data;
                var count = 0;
                info_count = 0;
                // console.log(locations.length);
                if(locations.length == 1)
                {
                    deleteMarkers();
                }
                for(var i=0; i < locations.length; i++)
                {
                    count++;
                    // if(locations[i].status)
                    {
                        addMarker(locations[i], count, i);
                    }
                } 
            },
            error: function(error){
                $('#location-not-available').show();
            }
        });
    }
    function getClientsLocations()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            url : "{{ url('api/admin/clients/liveLocations') }}",
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
                var locations = data.data;
                var count = 0;
                info_count_clients = 0;
                for(var i=0; i < locations.length; i++)
                {
                    count++;
                    if(locations[i].status)
                    {
                        addClientMarker(locations[i], count, i);
                    }
                } 
            },
            error: function(error){
                $('#location-not-available').show();
            }
        });
    }
    function addMarker(locations, count, index)
    {
        var rider_name = locations.name;
        // console.log(rider_name);
        var phone = locations.phone;
        var vehicle_number = locations.vehicle_number;
        var lat = parseFloat(locations.latitude);
        var lng = parseFloat(locations.longitude);
        
        if(count == 1)
        {
            deleteMarkers();
        }
        var contentString = '<div class="map-box"><ul><li><strong>Name: </strong>'+rider_name+'</li><li><strong>Vehicle No: </strong>'+vehicle_number+'</li><li><strong>Phone: </strong>'+phone+'</li></ul></div>';
        infoWindowContent.push(contentString);
        var marker = new google.maps.Marker(
            {position: {lat: lat, lng: lng},
            // animation: google.maps.Animation.DROP,
            icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
            map: map,
            // label: {color: 'blue', fontSize: '14px', text: vehicle_number}
        });
        // if(locations.online)
        // {
        //     // marker.setIcon("{{ asset('dashboard/assets/media/custom/icon-bike.png') }}");
        //     marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
        // }
        // else
        // {
        //     marker.setIcon("http://maps.google.com/mapfiles/ms/icons/red-dot.png");
        // }
        x = mapMarkers.push(marker);
        if (infoWindow) {
            infoWindow.close();
        }
        // infoWindow = new google.maps.InfoWindow({
        //     content : contentString
        // });
        // mapMarkers[x - 1].addListener('mouseover', function () {
        //     infoWindow.setContent(infoWindowContent[index]);
        //     infoWindow.open(map, marker);
        //     console.log(infoWindowContent[4]);
        // });
        // mapMarkers[x - 1].addListener('click', function () {
        //     infoWindow.setContent(infoWindowContent[index]);
        //     infoWindow.open(map, marker);
        // });
        // info_count++;
        
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
    function addClientMarker(locations, count, index)
    {
        var client_name = locations.name;
        // console.log(rider_name);
        var client_phone = locations.phone;
        var client_address = locations.address;
        var client_lat = parseFloat(locations.latitude);
        var client_lng = parseFloat(locations.longitude);
        if(count == 1)
        {
            deleteClientsMarkers();
        }
        var contentString = '<div class="map-box"><ul><li><strong>Name: </strong>'+client_name+'</li><li><strong>Address: </strong>'+client_address+'</li><li><strong>Phone: </strong>'+client_phone+'</li></ul></div>';
        infoWindowContentClients.push(contentString);
        var marker = new google.maps.Marker(
            {position: {lat: client_lat, lng: client_lng},
            // animation: google.maps.Animation.DROP,
            map: map,
            icon: "{{ asset('dashboard/assets/media/icons/svg/Home/Building.svg') }}",
            // label: {color: 'blue', fontSize: '14px', text: vehicle_number}
        });
        z = mapMarkersClients.push(marker);
        if (infoWindowClients) {
            infoWindowClients.close();
        }
        // infoWindow = new google.maps.InfoWindow({
        //     content : contentString
        // });
        // mapMarkers[x - 1].addListener('mouseover', function () {
        //     infoWindow.setContent(infoWindowContent[index]);
        //     infoWindow.open(map, marker);
        //     console.log(infoWindowContent[4]);
        // });
        // mapMarkers[x - 1].addListener('click', function () {
        //     infoWindow.setContent(infoWindowContent[index]);
        //     infoWindow.open(map, marker);
        // });
        // info_count++;
        
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

    function deleteClientsMarkers(){
        clearClientsMarkers();
        mapMarkersClients = [];
        infoWindowContentClients = [];
        info_count_clients = 0;
    }
    function clearClientsMarkers() {
        setMapOnAllClients(null);
    }
    function setMapOnAllClients(map) {
        for (var i = 0; i < mapMarkersClients.length; i++) {
            mapMarkersClients[i].setMap(map);
        }
    }
    // Shows any markers currently in the array.
    function showMarkersClients() {
        setMapOnAllClients(map);
    }
    
</script>
@endsection
