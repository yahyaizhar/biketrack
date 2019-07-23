@extends('client.layouts.app')
@section('main-content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

        <!--Begin::Dashboard 3-->
    
        <!--Begin::Section-->
        <div class="row mt-minus-60">
            <div class="col-xl-4">
                <!--begin:: Widgets/Top Locations-->
                <div class="kt-portlet kt-portlet--head--noborder kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Riders Locations
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="dropdown dropdown-inline">
                                <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-lg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="flaticon-more-1"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">
                                        {{-- <li class="kt-nav__section kt-nav__section--first">
                                            <span class="kt-nav__section-text">Finance</span>
                                        </li> --}}
                                        <li class="kt-nav__item">
                                            <a href="{{ route('client.riders') }}" class="kt-nav__link">
                                                <i class="kt-nav__link-icon fa fa-motorcycle"></i>
                                                <span class="kt-nav__link-text">Riders</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget15">
                            <div class="kt-widget15__map">
                                {{-- <div id="kt_chart_latest_trends_map" style="height:640px;"></div> --}}
                                <div id="map1" style="height:640px;"></div>
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
@endsection
@section('foot')
    <!--begin::Page Vendors(used by this page) -->
    <script src="{{ asset('dashboard/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
    {{-- <script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script> --}}
    <script src="{{ asset('dashboard/assets/vendors/custom/gmaps/gmaps.js') }}" type="text/javascript"></script>
    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ asset('dashboard/assets/js/demo3/pages/dashboard.js') }}" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNszaWh7ChpQi4WJ3A2gOAvt1yDEeBKbQ&callback=initMap"
    async defer></script>
    <script>
        
        function initMap(){
            var options = {
                zoom : 5,
                center : {lat: 30.3753, lng: 69.3451}
            }
            var map = new google.maps.Map(document.getElementById('map1'), options);

            var markers = [
                {
                    coords:{lat: 33.6844, lng: 73.0479},
                    content: "<h2>Islamabad</h2>"
                },
                {
                    coords:{lat: 31.5204, lng: 74.3587},
                    content: "<h2>Lahore</h2>"
                }
            ];
            for(var i=0; i < markers.length; i++)
            {
                addMarker(markers[i]);
            }
            function addMarker(props)
            {
                var marker = new google.maps.Marker({
                    position: props.coords,
                    map:map
                });
                if(props.content)
                {
                    var infoWindow = new google.maps.InfoWindow({
                        content : props.content
                    });
                    marker.addListener('click', function(){
                        infoWindow.open(map1, marker);
                    });
                }
            }
        }

        

        // $(document).ready(function()
        // {
        //     window.setInterval(function() {
        //         console.log('ready!');
        //     }, 3000);
        // });
    </script>
@endsection