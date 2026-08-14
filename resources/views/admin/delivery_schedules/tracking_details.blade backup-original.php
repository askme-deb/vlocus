<!DOCTYPE html>
<html>
<head>
    <title>Track Delivery</title>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
        .driver-info {
            background: #f8f9fa;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 500;
            border-bottom: 1px solid #ccc;
        }
        .driver-info span {
            display: inline-block;
            margin-right: 20px;
        }
    </style>
    <script src="https://maps.gomaps.pro/maps/api/js?key=AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl&libraries=places,geometry"></script>
</head>
<body>

    <!-- ✅ Driver Info Section -->
    <div class="driver-info">
        <span><strong>Driver:</strong> {{ $driver->name }}</span>
        <span><strong>Phone:</strong> <a href="tel:{{ $driver->phone }}">{{ $driver->phone }}</a></span>
        @if (!empty(getDriverDetails($driver->id)->vehicle->vehicle_number))
        <span><strong>Vehicle:</strong> {{ getDriverDetails($driver->id)->vehicle->vehicle_number }}</span>
        @endif
    </div>
    
     <div class="driver-info">
         <span><strong>Challan No :</strong> {{ $delivery->invoice_no }}</span>
        <span><strong>Delivery Notes:</strong> {{ $delivery->delivery_note }}</span>
        <span><strong>Payment Type:</strong> {{ $delivery->payment_type }}</span>
        <span><strong>Amount:</strong> {{ $delivery->amount }}</span>
 
    </div>

    <!-- ✅ Map Container -->
    <div id="map"></div>

    <!-- ✅ Map Script (same as previous) -->
    <script>
        let map;
        let driverMarker;
        let directionsRenderer;

        const shopLat = parseFloat("{{ $shopLat }}");
        const shopLng = parseFloat("{{ $shopLng }}");
        const driverLat = parseFloat("{{ $driverLat }}");
        const driverLng = parseFloat("{{ $driverLng }}");

        const shopPosition = { lat: shopLat, lng: shopLng };
        let driverPosition = { lat: driverLat, lng: driverLng };

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: driverPosition,
                zoom: 15
            });

            // 🟥 Shop Marker
            const shopMarker = new google.maps.Marker({
                position: shopPosition,
                map,
                title: "Shop Location",
                icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
            });

            const shopInfo = new google.maps.InfoWindow({
                content: `
                    <strong>Shop: {{ $shop->shop_name }}</strong><br>
                    <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=${shopLat},${shopLng}">
                        View on Google Maps
                    </a>
                `
            });
            shopMarker.addListener("click", () => shopInfo.open(map, shopMarker));

            // 🟦 Driver Marker (default icon)
            driverMarker = new google.maps.Marker({
                position: driverPosition,
                map: map,
                title: "Driver"
            });

            const driverInfo = new google.maps.InfoWindow();
            driverMarker.addListener("click", () => {
                const gmapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${driverPosition.lat},${driverPosition.lng}&destination=${shopLat},${shopLng}`;
                driverInfo.setContent(`
                    <strong>Driver Location</strong><br>
                    <a href="${gmapsUrl}" target="_blank">View Route in Google Maps</a>
                `);
                driverInfo.open(map, driverMarker);
            });

            // Route line
            directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: true });
            directionsRenderer.setMap(map);
            drawRoute(driverPosition);

            setInterval(updateDriverLocation, 10000); // every 10s
        }

        function drawRoute(origin) {
            const directionsService = new google.maps.DirectionsService();
            directionsService.route({
                origin: origin,
                destination: shopPosition,
                travelMode: google.maps.TravelMode.DRIVING
            }, (response, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(response);
                }
            });
        }

        function updateDriverLocation() {
            fetch("{{ url('get-driver-location') }}?driver_id={{ $driverId }}")
                .then(res => res.json())
                .then(data => {
                    if (data.response) {
                        const newLat = parseFloat(data.data.latitude);
                        const newLng = parseFloat(data.data.longitude);
                        driverPosition = { lat: newLat, lng: newLng };

                        driverMarker.setPosition(driverPosition);
                        map.panTo(driverPosition);
                        drawRoute(driverPosition);
                    }
                })
                .catch(err => {
                    console.error("Location update failed", err);
                });
        }

        window.onload = initMap;
    </script>
</body>
</html>
