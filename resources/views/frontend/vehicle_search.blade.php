@extends('frontend.layouts.app')
@section('title', $page->meta_title ?? 'Search')
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')
@section('content')
    {{-- <section class="fromTo py-3">
        <div class="container">
            <div class="row">
                <form action="{{ route('book-buss') }}" method="GET" class="booking-form">
                    <div class="row align-items-center">
                        <!-- From Location -->
                        <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                            <label for="from">From</label>
                            <input type="text" id="from" name="from" value="{{ $from ?? '' }}" placeholder="Enter departure city">
                        </div>

                        <!-- Exchange Icon -->
                        <div class="col-lg-1 col-md-1 col-sm-12 text-center my-2 topBannerArrow">
                            <span class="alignment trans"><i class="fas fa-exchange-alt"></i></span>
                        </div>

                        <!-- To Location -->
                        <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                            <label for="to">To</label>
                            <input type="text" id="to" name="to"  value="{{ $to ?? '' }}" placeholder="Enter destination">
                        </div>

                        <!-- Date Picker -->
                        <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" value="{{ $date ?? date('Y-m-d') }}">
                        </div>

                        <!-- Search Button -->
                        <div class="col-lg-2 col-md-2 col-sm-12 mt-3 alignment">
                            <button type="submit" class="btn btn_style">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}
    <section class="topBanner py-5 px-3 search_warp">
        <div class="container-fluid">
            <div class="row booking-form">
                <!--<div class="col-lg-4 track_shipment" style="position: relative;">-->
                <!--    <h1 class="mb-3">From Here to Anywhere</h1>-->
                <!--    <p>Enter your starting point, destination, and date to find the best routes.</p>-->
                <!--    <form action="{{ route('book-buss') }}" id="index-booking-form" method="GET" class="">-->
                <!--        <div class="align-items-center">-->
                            <!-- From Location -->

                <!--            <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-4 position-relative">-->
                <!--                <i class="fa fa-circle position-absolute"-->
                <!--                    style="left: 10px; top: 50%; transform: translateY(-50%); color: #555;"></i>-->
                <!--                <input type="text" id="from" name="from" value="{{ $from ?? '' }}" placeholder="Enter Pickup location"-->
                <!--                    style="padding-left: 40px; width: 100%; border-radius:10px;">-->
                <!--            </div>-->
                            <!-- Exchange Icon -->
                <!--            {{-- <div class="col-lg-1 col-md-1 col-sm-12 text-center my-2 topBannerArrow">-->
                <!--                <span class="alignment trans"><i class="fas fa-exchange-alt"></i></span>-->
                <!--            </div>-->
                <!--                --}}-->
                            <!-- To Location -->
                <!--            <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-4 position-relative">-->
                <!--                <i class="fa fa-stop-circle position-absolute"-->
                <!--                    style="left: 10px; top: 50%; transform: translateY(-50%); color: #555;"></i>-->
                <!--                <input type="text" id="to" name="to"  value="{{ $to ?? '' }}" placeholder="Enter dropoff location"-->
                <!--                    style="padding-left: 40px; width: 100%;border-radius:10px;">-->
                <!--            </div>-->

                <!--            <div class="d-flex flex-column css-jkbjYp">-->

                <!--            </div>-->


                <!--            <div class="d-flex col-lg-12 col-md-12 col-sm-12 ">-->
                                <!-- Date Picker -->
                <!--                <div class="col-lg-8 col-md-8 col-sm-8 d-flex flex-column">-->

                <!--                    <input class="flatpickr-input flatpickr-mobile flatpickr-mobile" tabindex="1" type="text" id="date_date" placeholder="Select Date" name="date"value="2025-03-25" style="border-radius:10px;">-->
                <!--                </div>-->
                <!--                {{-- <div class="col-lg-8 col-md-8 col-sm-8 d-flex flex-column">-->
                <!--                    <input id="datePicker" type="text" name="date" placeholder="Select Date" value="2025-03-25" style="border-radius:10px;">-->
                <!--                </div> --}}-->
                                
                                <!-- Search Button -->
                <!--                <div class="col-lg-4 col-md-3 col-sm-3 alignment mx-2">-->
                <!--                    <button-->
                <!--                        @auth type="submit" @else type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" @endauth-->
                <!--                        class="btn btn_style" style="border-radius:10px;">Search Now</button>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </form>-->
                <!--</div>-->
                
                <div class="col-lg-12 fild_warp">
                    <div class="fild_search">
                      <h1 class="mb-3">From Here to Anywhere</h1>
                    <p>Enter your starting point, destination, and date to find the best routes.</p>
                    <form action="{{ route('book-buss') }}" id="index-booking-form" method="GET" class="">
                        <div class="align-items-center">
                            <!-- From Location -->

                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-4 position-relative">
                                <i class="fa fa-circle position-absolute"
                                    style="left: 10px; top: 50%; transform: translateY(-50%); color: #555;"></i>
                                <input type="text" id="from" name="from" value="{{ $from ?? '' }}" placeholder="Enter Pickup location"
                                    style="padding-left: 40px; width: 100%; border-radius:10px;">
                            </div>
                            <!-- Exchange Icon -->
                            {{-- <div class="col-lg-1 col-md-1 col-sm-12 text-center my-2 topBannerArrow">
                                <span class="alignment trans"><i class="fas fa-exchange-alt"></i></span>
                            </div>
                                --}}
                            <!-- To Location -->
                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-4 position-relative">
                                <i class="fa fa-stop-circle position-absolute"
                                    style="left: 10px; top: 50%; transform: translateY(-50%); color: #555;"></i>
                                <input type="text" id="to" name="to"  value="{{ $to ?? '' }}" placeholder="Enter dropoff location"
                                    style="padding-left: 40px; width: 100%;border-radius:10px;">
                            </div>

                            <div class="d-flex flex-column css-jkbjYp">

                            </div>


                            <div class="d-flex col-lg-12 col-md-12 col-sm-12 ">
                                <!-- Date Picker -->
                                <div class="col-lg-8 col-md-8 col-sm-8 d-flex flex-column">

                                    <input class="flatpickr-input flatpickr-mobile flatpickr-mobile" tabindex="1" type="text" id="date_date" placeholder="Select Date" name="date"value="2025-03-25" style="border-radius:10px;">
                                </div>
                                {{-- <div class="col-lg-8 col-md-8 col-sm-8 d-flex flex-column">
                                    <input id="datePicker" type="text" name="date" placeholder="Select Date" value="2025-03-25" style="border-radius:10px;">
                                </div> --}}
                                
                                <!-- Search Button -->
                                <div class="col-lg-4 col-md-3 col-sm-3 alignment mx-2">
                                    <button
                                        @auth type="submit" @else type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" @endauth
                                        class="btn btn_style" style="border-radius:10px;">Search Now</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    </div>
                    
                    {{-- <img src="{{ asset('assets/frontend_assets/img/home/map.jpg') }}" alt="" srcset="" style="width: 100%;border-radius: 15px"> --}}
                    <div id="map" style="width: 100%; height: 400px; border-radius: 15px;"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="viewBusesBox">
        <div class="container">
            @foreach ($vehicleCounts as $type => $data)
                <div class="row"> {{-- 'journey_ids' => implode(',', $data['journey_ids']), --}}
                    <div class="col-lg-12 m-auto booking-form d-flex mb-2" onclick="window.location.href='{{ route('home.vehicle-search', ['ids' => implode(',', $data['ids']),'from'=>$from,'to'=>$to,'date'=>$date]) }}'" style="cursor: pointer; flex-wrap:wrap;">
                        <div class="col-lg-1 p-2">
                            <img src="{{ $data['image'] }}" alt="" width="100%">
                        </div>
                        <div class="col-lg-11 alignment p-2 ps-5">
                            <a href="{{ route('home.vehicle-search', [
                            'ids' => implode(',', $data['ids']),
                            {{-- 'journey_ids' => implode(',', $data['journey_ids']), --}}
                            'from'=>$from,
                            'to'=>$to,
                            'date'=>$date
                            ]) }}"
                                style="text-decoration: none"> {{ $type }} ({{ $data['count'] }})
                            </a>
                            {{-- <p class="my-0">{{ $data['distance_text'] ?? '' }}, {{ $data['duration_text'] ?? '' }} away</p> --}}
                            <p>Affordable compact rides</p>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </div>
            @endforeach

        </div>
    </section>


    {{-- @foreach ($vehicleCounts as $type => $data)
        <div class="accordion-item">
            <div class="accordion-header shadow my-2 p-1" id="panelsStayOpen-headingThree">
                <div class="row p-2">
                    <div class="col-lg-3">
                        <h6>

                        </h6>
                    </div>

                    <div class="col-lg-1">
                        <a href="{{ route('home.vehicle-search', ['ids' => implode(',', $data['ids'])]) }}">
                            {{ $type }} ({{ $data['count'] }})
                        </a>
                    </div>

                </div>
            </div>
    @endforeach --}}


    </div>
    </div>
    </section>
@endsection


@section('scripts')
<!-- Leaflet CSS & JS -->
<script src="https://maps.gomaps.pro/maps/api/js?key=AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl&libraries=places,marker"></script>

<script>
    let map, directionsService, directionsRenderer;

    let vehicleMarkers = {}; // Stores all vehicle markers
    const vehicleIcons = {
        'bus': {
            url: 'https://maps.google.com/mapfiles/ms/icons/bus.png',
            scaledSize: new google.maps.Size(32, 32)
        },
        'taxi': {
            url: 'https://maps.google.com/mapfiles/ms/icons/taxi.png',
            scaledSize: new google.maps.Size(28, 28)
        },
        'auto': {
            url: 'https://maps.google.com/mapfiles/ms/icons/cabs.png',
            scaledSize: new google.maps.Size(26, 26)
        },
        'bike': {
            url: 'https://maps.google.com/mapfiles/ms/icons/motorcycling.png',
            scaledSize: new google.maps.Size(24, 24)
        }
    };

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 22.5726, lng: 88.3639 },
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true, // Hides default A/B markers
            polylineOptions: { strokeColor: "blue", strokeWeight: 6 }
        });

        calculateRoute();
        startRealTimeUpdates();
    }

    function calculateRoute() {
        const fromLat = @json($from_bus_stop->latitude ?? 23.8103);
        const fromLng = @json($from_bus_stop->longitude ?? 90.4125);
        const toLat = @json($to_bus_stop->latitude ?? 22.5726);
        const toLng = @json($to_bus_stop->longitude ?? 88.3639);

        const origin = new google.maps.LatLng(fromLat, fromLng);
        const destination = new google.maps.LatLng(toLat, toLng);

        directionsService.route(
            { origin, destination, travelMode: 'DRIVING' },
            (result, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                    
                    // Origin: Circle + Text (no close button)
                    // createCircleWithText(origin, @json($from_bus_stop->name ?? "Start"), '#4285F4');
                    createMarkerWithText(origin, @json($from_bus_stop->name ?? "Start"), '#4285F4');
                    
                    // Destination: Marker + Text (no close button)
                    createMarkerWithText(destination, @json($to_bus_stop->name ?? "Destination"), '#EA4335');
                }
            }
        );
    }

    function startRealTimeUpdates() {
        // Fetch vehicle data every 5 seconds
        setInterval(fetchVehicleData, 5000);
        fetchVehicleData(); // Initial load
    }

    function fetchVehicleData() {
        // Replace with your actual API endpoint
        fetch('/vehicles/realtime')
            .then(response => response.json())
            .then(data => updateVehiclePositions(data))
            .catch(error => console.error('Error:', error));
    }

    function updateVehiclePositions(vehicles) {
        // First, remove vehicles that are no longer in the data
        Object.keys(vehicleMarkers).forEach(vehicleId => {
            if (!vehicles.some(v => v.id === vehicleId)) {
                vehicleMarkers[vehicleId].setMap(null);
                delete vehicleMarkers[vehicleId];
            }
        });

        // Update or add new vehicles
        vehicles.forEach(vehicle => {
            const position = new google.maps.LatLng(vehicle.latitude, vehicle.longitude);
            
            if (vehicleMarkers[vehicle.id]) {
                // Update existing marker position
                vehicleMarkers[vehicle.id].setPosition(position);
            } else {
                // Create new marker
                vehicleMarkers[vehicle.id] = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: vehicleIcons[vehicle.type] || vehicleIcons['auto'],
                    title: `${vehicle.type.toUpperCase()} - ${vehicle.id}`,
                    optimized: false // Better for many markers
                });
                
                // Add info window with vehicle details
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 8px;">
                            <strong>${vehicle.type.toUpperCase()}</strong><br>
                            ID: ${vehicle.id}<br>
                            Speed: ${vehicle.speed || 'N/A'} km/h<br>
                            Last update: ${new Date(vehicle.timestamp).toLocaleTimeString()}
                        </div>
                    `
                });
                
                // Show info on click
                vehicleMarkers[vehicle.id].addListener('click', () => {
                    infoWindow.open(map, vehicleMarkers[vehicle.id]);
                });
            }
        });
    }

    // Creates a CIRCLE + Text for the origin (no close button)
    function createCircleWithText(position, text, color) {
        // Draw a circle
        new google.maps.Circle({
            map,
            center: position,
            radius: 10, // Small circle
            fillColor: color,
            fillOpacity: 1,
            strokeWeight: 0
        });

        // Create custom overlay for text
        const textDiv = document.createElement('div');
        textDiv.style.backgroundColor = color;
        textDiv.style.color = 'white';
        textDiv.style.padding = '6px 10px';
        textDiv.style.borderRadius = '16px';
        textDiv.style.fontFamily = 'Arial';
        textDiv.style.fontSize = '12px';
        textDiv.style.fontWeight = 'bold';
        textDiv.style.position = 'absolute';
        textDiv.style.whiteSpace = 'nowrap';
        textDiv.innerHTML = text;

        // Create overlay and position it
        const overlay = new google.maps.OverlayView();
        overlay.onAdd = function() {
            this.getPanes().floatPane.appendChild(textDiv);
        };
        overlay.onRemove = function() {
            textDiv.parentNode.removeChild(textDiv);
        };
        overlay.draw = function() {
            const point = this.getProjection().fromLatLngToDivPixel(
                new google.maps.LatLng(position.lat(), position.lng() + 0.0002)
            );
            if (point) {
                textDiv.style.left = (point.x + 15) + 'px';
                textDiv.style.top = (point.y - 15) + 'px';
            }
        };
        overlay.setMap(map);
    }

    // Creates a MARKER + Text for the destination (no close button)
    function createMarkerWithText(position, text, color) {
        // Add a marker
        new google.maps.Marker({
            position,
            map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                scaledSize: new google.maps.Size(30, 30)
            }
        });

        // Create custom overlay for text
        const textDiv = document.createElement('div');
        textDiv.style.backgroundColor = color;
        textDiv.style.color = 'white';
        textDiv.style.padding = '6px 10px';
        textDiv.style.borderRadius = '16px';
        textDiv.style.fontFamily = 'Arial';
        textDiv.style.fontSize = '12px';
        textDiv.style.fontWeight = 'bold';
        textDiv.style.position = 'absolute';
        textDiv.style.whiteSpace = 'nowrap';
        textDiv.innerHTML = text;

        // Create overlay and position it
        const overlay = new google.maps.OverlayView();
        overlay.onAdd = function() {
            this.getPanes().floatPane.appendChild(textDiv);
        };
        overlay.onRemove = function() {
            textDiv.parentNode.removeChild(textDiv);
        };
        overlay.draw = function() {
            const point = this.getProjection().fromLatLngToDivPixel(
                new google.maps.LatLng(position.lat(), position.lng() + 0.0002)
            );
            if (point) {
                textDiv.style.left = (point.x + 15) + 'px';
                textDiv.style.top = (point.y - 15) + 'px';
            }
        };
        overlay.setMap(map);
    }

    window.onload = initMap;
</script>
@endsection

