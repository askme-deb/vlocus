<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Route Playback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #map {
            height: 500px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .controls {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }
        .speed-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .progress-container {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h4 class="mb-3">Driver Route Playback</h4>
        
        <div class="card">
            <div class="card-body">
                <div id="map"></div>
                
                <div class="progress-container">
                    <div class="progress">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small id="progressText">0% Complete</small>
                        <small id="positionText">Position: 0 / 0</small>
                    </div>
                </div>
                
                <div class="controls">
                    <button id="playBtn" class="btn btn-primary">Play</button>
                    <button id="pauseBtn" class="btn btn-secondary" disabled>Pause</button>
                    <button id="resetBtn" class="btn btn-outline-secondary">Reset</button>
                    
                    <div class="speed-control">
                        <label for="speedControl">Speed:</label>
                        <select id="speedControl" class="form-select form-select-sm">
                            <option value="2000">0.5x</option>
                            <option value="1000" selected>1x</option>
                            <option value="500">2x</option>
                            <option value="250">4x</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample route data - in a real app, this would come from your backend
        const routeData = [
            { latitude: 40.7128, longitude: -74.0060 }, // New York
            { latitude: 40.7210, longitude: -74.0102 },
            { latitude: 40.7295, longitude: -74.0055 },
            { latitude: 40.7350, longitude: -74.0020 },
            { latitude: 40.7410, longitude: -73.9980 },
            { latitude: 40.7470, longitude: -73.9900 },
            { latitude: 40.7520, longitude: -73.9780 },
            { latitude: 40.7580, longitude: -73.9855 },
            { latitude: 40.7630, longitude: -73.9805 },
            { latitude: 40.7680, longitude: -73.9810 },
            { latitude: 40.7730, longitude: -73.9820 },
            { latitude: 40.7780, longitude: -73.9770 },
            { latitude: 40.7830, longitude: -73.9720 },
            { latitude: 40.7880, longitude: -73.9670 },
            { latitude: 40.7930, longitude: -73.9620 },
            { latitude: 40.7980, longitude: -73.9570 }
        ];

        let map, marker, polyline, index = 0, interval, isPlaying = false;
        const bikeIcon = {
            url: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBmaWxsPSIjMDA3YmZmIiBkPSJNMjU2IDhDMTE5IDggOCAxMTkgOCAyNTZzMTExIDI0OCAyNDggMjQ4IDI0OC0xMTEgMjQ4LTI0OFMzOTMgOCAyNTYgOHptMTI4IDM1MmMwIDQuNC0zLjYgOC04IDhIMTM2Yy00LjQgMC04LTMuNi04LThzMy42LTggOC04aDc1LjJMMTQ0IDI5OS41Yy0zLjEtMy4xLTMuMS04LjIgMC0xMS4zbDExLjMtMTEuM2MzLjEtMy4xIDguMi0zLjEgMTEuMyAwTDIyNCAzNjEuOGw1Ny40LTU3LjRjMy4xLTMuMSA4LjItMy4xIDExLjMgMGwxMS4zIDExLjNjMy4xIDMuMSAzLjEgOC4yIDAgMTEuM0wyOTYgMzQ0aDc1LjJjNC40IDAgOCAzLjYgOCA4eiIvPjwvc3ZnPg==',
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20)
        };

        function initMap() {
            if (!routeData.length) {
                alert("No location data available");
                return;
            }

            const start = {
                lat: parseFloat(routeData[0].latitude),
                lng: parseFloat(routeData[0].longitude)
            };

            // Initialize map
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: start,
                mapTypeId: 'roadmap',
                styles: [
                    {
                        "featureType": "road",
                        "stylers": [
                            { "visibility": "simplified" }
                        ]
                    }
                ]
            });

            // Draw route polyline with actual path
            const routePath = routeData.map(p => ({
                lat: parseFloat(p.latitude),
                lng: parseFloat(p.longitude)
            }));

            polyline = new google.maps.Polyline({
                path: routePath,
                geodesic: true,
                strokeColor: "#007BFF",
                strokeOpacity: 0.7,
                strokeWeight: 5,
            });
            polyline.setMap(map);

            // Place bike marker at start
            marker = new google.maps.Marker({
                position: start,
                map: map,
                icon: bikeIcon,
                title: "Bike"
            });

            // Set up event listeners
            document.getElementById('playBtn').addEventListener('click', playRoute);
            document.getElementById('pauseBtn').addEventListener('click', pauseRoute);
            document.getElementById('resetBtn').addEventListener('click', resetRoute);
            document.getElementById('speedControl').addEventListener('change', updateSpeed);
            
            // Update progress display
            updateProgress();
        }

        function playRoute() {
            if (isPlaying) return;
            
            isPlaying = true;
            document.getElementById('playBtn').disabled = true;
            document.getElementById('pauseBtn').disabled = false;
            
            const speed = parseInt(document.getElementById('speedControl').value);
            
            interval = setInterval(() => {
                if (index < routeData.length - 1) {
                    index++;
                    const pos = {
                        lat: parseFloat(routeData[index].latitude),
                        lng: parseFloat(routeData[index].longitude)
                    };
                    
                    // Smooth animation between points
                    if (index > 0) {
                        const prevPos = {
                            lat: parseFloat(routeData[index-1].latitude),
                            lng: parseFloat(routeData[index-1].longitude)
                        };
                        
                        // Calculate rotation angle for the bike
                        const angle = calculateBearing(prevPos, pos);
                        rotateBikeIcon(angle);
                    }
                    
                    marker.setPosition(pos);
                    map.panTo(pos);
                    updateProgress();
                } else {
                    // Reached the end
                    clearInterval(interval);
                    isPlaying = false;
                    document.getElementById('playBtn').disabled = false;
                    document.getElementById('pauseBtn').disabled = true;
                }
            }, speed);
        }

        function pauseRoute() {
            if (!isPlaying) return;
            
            clearInterval(interval);
            isPlaying = false;
            document.getElementById('playBtn').disabled = false;
            document.getElementById('pauseBtn').disabled = true;
        }

        function resetRoute() {
            pauseRoute();
            index = 0;
            
            const start = {
                lat: parseFloat(routeData[0].latitude),
                lng: parseFloat(routeData[0].longitude)
            };
            
            marker.setPosition(start);
            map.panTo(start);
            updateProgress();
        }

        function updateSpeed() {
            if (isPlaying) {
                pauseRoute();
                playRoute();
            }
        }

        function updateProgress() {
            const progress = Math.round((index / (routeData.length - 1)) * 100);
            document.getElementById('progressBar').style.width = `${progress}%`;
            document.getElementById('progressText').textContent = `${progress}% Complete`;
            document.getElementById('positionText').textContent = `Position: ${index} / ${routeData.length - 1}`;
        }

        function calculateBearing(start, end) {
            const startLat = start.lat * Math.PI / 180;
            const startLng = start.lng * Math.PI / 180;
            const endLat = end.lat * Math.PI / 180;
            const endLng = end.lng * Math.PI / 180;

            const y = Math.sin(endLng - startLng) * Math.cos(endLat);
            const x = Math.cos(startLat) * Math.sin(endLat) -
                      Math.sin(startLat) * Math.cos(endLat) * Math.cos(endLng - startLng);
            let bearing = Math.atan2(y, x);
            bearing = bearing * 180 / Math.PI;
            bearing = (bearing + 360) % 360;
            
            return bearing;
        }

        function rotateBikeIcon(angle) {
            // Create a rotated version of the bike icon
            const rotatedIcon = {
                ...bikeIcon,
                rotation: angle
            };
            marker.setIcon(rotatedIcon);
        }
    </script>

    <!-- Load Google Maps API -->
    <script
        src="https://maps.gomaps.pro/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"
        async
        defer>
    </script>
</body>
</html>