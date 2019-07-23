var map;
var $$ = Dom7;
$$(document).on('pageInit', '.page[data-page="index"]', function (e) {
    
    app.startLocationTracking();
});

var maps = {
    current_lat_long: '',
    infowindow: '',
    setCurrentLocation: function () {
        navigator.geolocation.getCurrentPosition(function (position) {
            currentLattitude = position.coords.latitude;
            currentLongitude = position.coords.longitude;
            maps.current_lat_long = {lat: currentLattitude, lng: currentLongitude};
            var current_loc = maps.current_lat_long;
        },
                function (error) {
                    console.log(error);
                }
        , {timeout: 5000});
    },
    show_map: function (map_id) {
        if (typeof map_id == 'undefined') {
            map_id = 'map';
        }
        var current_loc = maps.current_lat_long;
        if (typeof current_loc == 'undefined' || current_loc == '') {
            maps.current_lat_long = {lat: 25.204849, lng: 55.270782};
        }
        map = new google.maps.Map(document.getElementsByClassName(map_id)[0], {
            center: maps.current_lat_long,
            zoom: 12
        });
        var marker = new google.maps.Marker({
            position: maps.current_lat_long,
            map: map,
            animation: google.maps.Animation.DROP,
            title: 'Hello World!'
        });
        marker.addListener('click', function () {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        });
        maps.infowindow = new google.maps.InfoWindow();
        marker.setMap(map);
    },
    
    changeTrackingStatus: function () {
        var status = localStorage.getItem('isStarted');
        if(status == true || status == 'true'){
            localStorage.setItem('isStarted', false);
            maps.sendStatus(2);
        }else{
            localStorage.setItem('isStarted', true);
            maps.sendStatus(1);
        }
        app.startLocationTracking();
    },
    sendStatus: function (status) {
        var driver_id = localStorage.getItem('driver_id');
        var login_data = {rider_id : driver_id, status: status};
        var url = "rider/changeStatus";
        core.postRequest(url, login_data, function (response, status) {
            
        });
    }
};

