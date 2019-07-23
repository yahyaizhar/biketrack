@extends('admin.layouts.app')
@section('main-content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-md-12">
        <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Edit Client Information
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                
                @include('client.includes.message')
                <form class="kt-form" action="{{ route('admin.clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                
                                <div class="form-group">
                                    <label>Full Name:</label>
                                    <input type="text" class="form-control @if($errors->has('name')) invalid-field @endif" name="name" placeholder="Enter full name" value="{{ $client->name }}">
                                    @if ($errors->has('name'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('name') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter your full name</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Email address:</label>
                                    <input type="email" class="form-control @if($errors->has('email')) invalid-field @endif" name="email" placeholder="Enter email" value="{{ $client->email }}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('email') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">We'll never share your email with anyone else</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                @if($client->logo)
                                    <img class="profile-logo img img-thumbnail" src="{{ asset(Storage::url($client->logo)) }}" />
                                @else
                                    <img class="profile-logo img img-thumbnail" src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                @endif
                                <div class="form-group col-md-6 pull-right mtr-15">
                                    <div class="custom-file">
                                        <input type="file" name="logo" class="custom-file-input" id="logo" value="{{ $client->logo }}">
                                        <label class="custom-file-label" for="logo">Choose logo</label>
                                    </div>
                                    <span class="form-text text-muted">Select only if you want to update logo</span>
                                </div>
                            </div>
                        </div>
                        <label class="kt-checkbox">
                            <input id="change-password" name="change_password" type="checkbox"> Change Password
                            <span></span>
                        </label>
                        <div id="password-fields" style="display:none;">
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" class="form-control @if($errors->has('passsword')) invalid-field @endif" name="password" placeholder="Enter password">
                                @if ($errors->has('password'))
                                    <span class="invalid-response" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @else
                                    <span class="form-text text-muted">Please enter your password</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Confirm Password:</label>
                                <input type="password" class="form-control @if($errors->has('passsword')) invalid-field @endif" name="password_confirmation" placeholder="Enter confirm password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="text" class="form-control @if($errors->has('phone')) invalid-field @endif" name="phone" placeholder="Enter phone number" value="{{ $client->phone }}">
                            @if ($errors->has('phone'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('phone') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your phone number</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" class="form-control @if($errors->has('address')) invalid-field @endif" name="address" placeholder="Enter city" value="{{ $client->address }}">
                            @if ($errors->has('address'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{$errors->first('address')}}
                                    </strong>
                                </span>
                            @endif
                        </div>
                        {{-- <span style="padding:5px; border: 1px solid gray;"><strong>Note: </strong> <a style="color:blue;" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank"> You can find latitude and longitude here</a></span>
                        <div class="form-group">
                            <label>Latitude:</label>
                            <input type="text" class="form-control @if($errors->has('latitude')) invalid-field @endif" name="latitude" placeholder="Enter latitude" value="{{ $client->latitude }}">
                            @if ($errors->has('latitude'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('latitude') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your location latitude</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Longitude:</label>
                            <input type="text" class="form-control @if($errors->has('longitude')) invalid-field @endif" name="longitude" placeholder="Enter longitude " value="{{ $client->longitude }}">
                            @if ($errors->has('longitude'))
                                <span class="invalid-response" role="alert">
                                    <strong>
                                        {{ $errors->first('longitude') }}
                                    </strong>
                                </span>
                            @else
                                <span class="form-text text-muted">Please enter your location longitude</span>
                            @endif
                        </div> --}}
                        {{-- <span style="padding:5px; border: 1px solid gray;"><strong>Note: </strong> <a style="color:blue;" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank"> You can find latitude and longitude here</a></span> --}}
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <label>Address: </label>
                                <div class="input-group">
                                    <input type="text" id="search_location" name="restaurant_location" class="form-control" placeholder="Search for...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary get_map" type="button" id="findOnMap">Go!</button>
                                    </div>
                                    @if ($errors->has('restaurant_location'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('restaurant_location') }}
                                            </strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="map" style="width: 100%; height:400px;"></div>
                            </div>
                            <div class="col-lg-3 col-md-3" style="display:none;">
                                <div class="form-group" style="margin-top:5%">
                                    <label>Latitude:</label>
                                    <input type="text" class="form-control @if($errors->has('latitude')) invalid-field @endif" name="latitude" id="search_latitude" placeholder="Enter latitude" value="{{ $client->latitude }}">
                                    @if ($errors->has('latitude'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('latitude') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter your location latitude</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Longitude:</label>
                                    <input type="text" class="form-control @if($errors->has('longitude')) invalid-field @endif" name="longitude" id="search_longitude" placeholder="Enter longitude " value="{{ $client->longitude }}">
                                    @if ($errors->has('longitude'))
                                        <span class="invalid-response" role="alert">
                                            <strong>
                                                {{ $errors->first('longitude') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="form-text text-muted">Please enter your location longitude</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status:</label>
                            <div>
                                <input data-switch="true" name="status" id="status" type="checkbox" {!! $client->status ==  1 ? 'checked' : '' !!} data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions kt-form__actions--right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <span class="kt-margin-l-10">or <a href="{{ route('admin.clients.index') }}" class="kt-link kt-font-bold">Cancel</a></span>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>

        <!--end::Portlet-->
    </div>
</div>
@endsection
@section('foot')
    
<script src="{{ asset('dashboard/assets/js/demo1/pages/crud/forms/widgets/bootstrap-switch.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $('#change-password').change(function () {
            $('#password-fields').fadeToggle();
        });
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBovpIsxtSqMTpBMysndin00-26bcWy3YM&callback=initialize"
async defer></script>
<script>
    var geocoder;
    var map;
    var marker;
    function initialize(){
        var initialLat = $('#search_latitude').val();
        var initialLong = $('#search_longitude').val();
        initialLat = initialLat ? initialLat : 25.2048;
        initialLong = initialLong ? initialLong : 55.2708;

        var latlng = new google.maps.LatLng(initialLat, initialLong);
        var options = {
            zoom:12,
            center: latlng,
        };
        map = new google.maps.Map(document.getElementById("map"), options);

        geocoder = new google.maps.Geocoder();
        
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            position: latlng
        });
        google.maps.event.addListener(marker, "dragend", function(){
            var point = marker.getPosition();
            map.panTo(point);
            geocoder.geocode({'latLng' : marker.getPosition()}, function(results, status){
                if(status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    $('#search_latitude').val(marker.getPosition().lat());
                    $('#search_longitude').val(marker.getPosition().lng());
                }
            } );
        });
    }
    $(document).ready(function(){
        //load google map
        // autocomplete location search
        var PostCodeId = '#search_location';
        // $(function(){
        //     $(PostCodeId).autocomplete({
        //         source: function (request, response){
        //             geocoder.geocode({
        //                 'address' : request.term
        //             }, function(results, status) {
        //                 response($.map(results, function (item) {
        //                     return {
        //                         label : item.formatted_address,
        //                         value: item.formatted_address,
        //                         lat: item.geometry.location.lat(),
        //                         lon: item.geometry.location.lng()
        //                     };
        //                 }));
        //             });
        //         },
        //         select: function(event, ui) {
        //             $('#search_latitude').val(ui.item.lat);
        //             $('#search_longitude').val(ui.item.lon);
        //             var latlng = new google.maps.LatLng(ui.item.lat, ui.item.lon);
        //             marker.setPosition(latlng);
        //             initialize();
        //         }
        //     });
        // });

        // Point Location on google map
        $('#findOnMap').click(function(){
            var address = $(PostCodeId).val();
            geocoder.geocode({'address' : address}, function(results, status){
                if(status == google.maps.GeocoderStatus.OK){
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    $('#search_latitude').val(marker.getPosition().lat());
                    $('#search_longitude').val(marker.getPosition().lng());
                }
                else
                {
                    // alert("Unable to locate due to: " + status);
                    swal.fire("Unable to locate", "Please enter proper address.", "warning");
                }
            });
            // e.preventDefault();
        });

        // Add listener to marker for reverse geocoding
        google.maps.event.addListener(marker, 'drag', function(){
            geocoder.geocode({'latLng' : marker.getPosition()}, function(results, status){
                if(status == google.maps.GeocoderStatus.OK){
                    if(results[0]){
                        $('#search_latitude').val(marker.getPosition().lat());
                        $('#search_longitude').val(marker.getPosition().lng());
                    }
                }
            });
        });

    });

</script>
@endsection