@extends('layouts.app')

@section('title', 'Route Playback')
@section('css')
    <style>
        #map {
            height: 70vh;
            width: 100%;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        .playback-controls {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .route-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
        }
        .progress-container {
            margin-top: 10px;
        }
        .marker-popup {
            font-size: 14px;
            line-height: 1.4;
        }
        .current-position {
            background: #fff3cd;
            padding: 8px;
            border-radius: 4px;
            margin-top: 10px;
            border-left: 4px solid #ffc107;
        }
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }
    </style>
@endsection
@section('content')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Route Playback</div>
    <div class="ps-3">
        <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Driver Route Playback</li>
        </ol>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">  
        <!-- Route Selection Form -->
        <div class="playback-controls" style="display:none;">
            <form id="routeForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="driver_id" class="form-label">Select Driver</label>
                        <select class="form-select" id="driver_id" name="driver_id" required>
                            <option value="">Choose Driver...</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">
                                    {{ $driver->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="date" class="form-label">Select Date</label>
                        <input type="date" class="form-control" id="date" name="date"
                            max="{{ date('Y-m-d') }}"
                            value=""
                            required>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-route"></i> Load Route
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Calculating route... This may take a moment</p>
        </div>

        <!-- Route Information -->
        <div id="routeInfo" class="route-info" style="display: none;">
            <div class="row">
                <div class="col-md-3">
                    <strong>Start Time:</strong> <span id="startTime">-</span>
                </div>
                <div class="col-md-3">
                    <strong>End Time:</strong> <span id="endTime">-</span>
                </div>
                <div class="col-md-2">
                    <strong>Total Points:</strong> <span id="totalPoints">-</span>
                </div>
                <div class="col-md-2">
                    <strong>Vehicle:</strong> <span id="vehicleInfo">-</span>
                </div>
                <div class="col-md-2">
                    <strong>Route Distance:</strong> <span id="routeDistance">-</span>
                </div>
            </div>
            <div class="progress-container">
                <div class="d-flex justify-content-between">
                    <small>Progress:</small>
                    <small><span id="currentProgress">0</span>%</small>
                </div>
                <div class="progress">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Current Position -->
        <div id="currentPosition" class="current-position" style="display: none;">
            <strong>Current Position:</strong> 
            <span id="currentCoords">-</span> | 
            <span id="currentTime">-</span> | 
            Speed: <span id="currentSpeed">-</span> km/h |
            Segment: <span id="currentSegment">-</span>
        </div>

        <!-- Playback Controls -->
        <div class="playback-controls" id="playbackControls" style="display: none;">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <button id="playPause" class="btn btn-success btn-sm">
                        <i class="fas fa-play"></i> Play
                    </button>
                    <button id="reset" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                    <button id="prevPoint" class="btn btn-outline-primary btn-sm" title="Previous Point">
                        <i class="fas fa-step-backward"></i>
                    </button>
                    <button id="nextPoint" class="btn btn-outline-primary btn-sm" title="Next Point">
                        <i class="fas fa-step-forward"></i>
                    </button>
                </div>
                <div class="col-md-6">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Speed:</label>
                            <select class="form-select form-select-sm" id="playbackSpeed">
                                <option value="2000">0.5x</option>
                                <option value="1000">1x</option>
                                <option value="500" selected>2x</option>
                                <option value="250">4x</option>
                                <option value="100">10x</option>
                                <option value="50">20x</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Timeline:</label>
                            <input type="range" class="form-range" id="timelineSlider" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="showRoute" checked>
                        <label class="form-check-label" for="showRoute">Show Route</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="autoCenter" checked>
                        <label class="form-check-label" for="autoCenter">Auto Center</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div id="map" class="mb-4"></div>
    </div>
</div>
@endsection

@section('scripts')

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let map;
        let routeControl;
        let driverMarker;
        let startMarker;
        let endMarker;
        let routePoints = [];
        let routeSegments = [];
        let currentPointIndex = 0;
        let playbackInterval;
        let isPlaying = false;

        // Custom driver icon
        const driverIcon = L.divIcon({
            html: '<i class="fas fa-solid fa-truck-moving" style="color: #dc3545; font-size: 24px;"></i>', //fa-truck-moving 
            className: 'driver-marker',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        const startIcon = L.divIcon({
            html: '<i class="fas fa-play-circle" style="color: #28a745; font-size: 20px;"></i>',
            className: 'start-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        const endIcon = L.divIcon({
            html: '<i class="fas fa-stop-circle" style="color: #dc3545; font-size: 20px;"></i>',
            className: 'end-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        // Initialize map
        function initMap() {
            map = L.map('map').setView([20, 0], 2);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            L.control.scale().addTo(map);
        }

        // Calculate route using OSRM
        async function calculateRoute(points) {
            if (points.length < 2) {
                throw new Error('Need at least 2 points to calculate route');
            }

            // Use OSRM API to get the route
            const coordinates = points.map(p => `${p.lng},${p.lat}`).join(';');
            const url = `https://router.project-osrm.org/route/v1/driving/${coordinates}?overview=full&geometries=geojson`;
            
            try {
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.code !== 'Ok') {
                    throw new Error('Route calculation failed: ' + data.message);
                }

                return data.routes[0];
            } catch (error) {
                console.error('OSRM routing error:', error);
                // Fallback: create a straight line route if OSRM fails
                return createFallbackRoute(points);
            }
        }

        // Fallback route (straight line) if OSRM fails
        function createFallbackRoute(points) {
            const geometry = {
                type: "LineString",
                coordinates: points.map(p => [p.lng, p.lat])
            };
            
            return {
                geometry: geometry,
                distance: calculateTotalDistance(points),
                duration: calculateTotalDuration(points),
                legs: points.slice(1).map((point, index) => ({
                    distance: calculateDistance(points[index], point),
                    duration: calculateDuration(points[index], point)
                }))
            };
        }

        // Calculate distance between two points (Haversine formula)
        function calculateDistance(point1, point2) {
            const R = 6371000; // Earth's radius in meters
            const dLat = (point2.lat - point1.lat) * Math.PI / 180;
            const dLng = (point2.lng - point1.lng) * Math.PI / 180;
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(point1.lat * Math.PI / 180) * Math.cos(point2.lat * Math.PI / 180) *
                Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        function calculateDuration(point1, point2) {
            // Assume average speed of 50 km/h for fallback
            const distance = calculateDistance(point1, point2);
            return distance / (50000 / 3600); // duration in seconds
        }

        function calculateTotalDistance(points) {
            let total = 0;
            for (let i = 1; i < points.length; i++) {
                total += calculateDistance(points[i-1], points[i]);
            }
            return total;
        }

        function calculateTotalDuration(points) {
            let total = 0;
            for (let i = 1; i < points.length; i++) {
                total += calculateDuration(points[i-1], points[i]);
            }
            return total;
        }

        // Load route data
        // document.getElementById('routeForm').addEventListener('submit', async function(e) {
        //     e.preventDefault();
            
        //     const driverId = document.getElementById('driver_id').value;
        //     const date = document.getElementById('date').value;
            
        //     if (!driverId || !date) {
        //         alert('Please select both driver and date');
        //         return;
        //     }

        //     const formData = new FormData(this);
            
        //     // Show loading state
        //     const submitBtn = document.querySelector('#routeForm button[type="submit"]');
        //     submitBtn.disabled = true;
        //     submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        //     document.getElementById('loadingSpinner').style.display = 'block';
            
        //     try {
        //         const response = await fetch('{{ route("route.playback.data") }}', {
        //             method: 'POST',
        //             body: formData
        //         });
                
        //         if (!response.ok) {
        //             throw new Error('Network response was not ok');
        //         }
                
        //         const data = await response.json();
                
        //         if (data.success) {
        //             await displayRoute(data);
        //         } else {
        //             alert(data.message);
        //         }
        //     } catch (error) {
        //         console.error('Error:', error);
        //         alert('Error loading route data: ' + error.message);
        //     } finally {
        //         submitBtn.disabled = false;
        //         submitBtn.innerHTML = '<i class="fas fa-route"></i> Load Route';
        //         document.getElementById('loadingSpinner').style.display = 'none';
        //     }
        // });



        document.addEventListener('DOMContentLoaded', async function() {
            const urlParams = new URLSearchParams(window.location.search);
            const driverId = urlParams.get('driver_id');
            const date = urlParams.get('date');

            // Only run if both parameters are available
            if (driverId && date) {
                const formData = new FormData();
                formData.append('driver_id', driverId);
                formData.append('date', date);
                formData.append('_token', '{{ csrf_token() }}');

                // Optional: show loading indicator if you have one
                const loadingSpinner = document.getElementById('loadingSpinner');
                if (loadingSpinner) loadingSpinner.style.display = 'block';

                try {
                    const response = await fetch('{{ route("route.playback.data") }}', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    if (data.success) {
                        await displayRoute(data);
                    } else {
                        alert(data.message || 'No data found for this driver/date');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    alert('Error loading route data: ' + error.message);
                } finally {
                    if (loadingSpinner) loadingSpinner.style.display = 'none';
                }
            }
        });



        // Display route on map
        async function displayRoute(data) {
            // Clear existing layers
            clearMap();
            
            routePoints = data.route;
            currentPointIndex = 0;
            
            if (routePoints.length === 0) {
                alert('No route points available');
                return;
            }

            if (routePoints.length === 1) {
                alert('Only one point available. Need at least 2 points for a route.');
                return;
            }

            try {
                // Calculate actual road route
                const route = await calculateRoute(routePoints);
                
                // Create the route polyline on map
                const routeGeometry = route.geometry;
                const routePolyline = L.geoJSON(routeGeometry, {
                    style: {
                        color: '#0d6efd',
                        weight: 6,
                        opacity: 0.7,
                        lineJoin: 'round'
                    }
                }).addTo(map);

                // Extract route segments for simulation
                routeSegments = extractRouteSegments(routeGeometry, routePoints);

                // Add start and end markers
                const startPoint = routePoints[0];
                const endPoint = routePoints[routePoints.length - 1];
                
                startMarker = L.marker([startPoint.lat, startPoint.lng], { icon: startIcon })
                    .addTo(map)
                    .bindPopup(`
                        <div class="marker-popup">
                            <strong>Start Position</strong><br>
                            Time: ${startPoint.timestamp}<br>
                            Vehicle: ${startPoint.vehicle}<br>
                            Speed: ${startPoint.speed || 'N/A'} km/h
                        </div>
                    `);

                endMarker = L.marker([endPoint.lat, endPoint.lng], { icon: endIcon })
                    .addTo(map)
                    .bindPopup(`
                        <div class="marker-popup">
                            <strong>End Position</strong><br>
                            Time: ${endPoint.timestamp}<br>
                            Vehicle: ${endPoint.vehicle}<br>
                            Speed: ${endPoint.speed || 'N/A'} km/h
                        </div>
                    `);
                
                // Create driver marker
                driverMarker = L.marker([startPoint.lat, startPoint.lng], { icon: driverIcon })
                    .addTo(map)
                    .bindPopup(`
                        <div class="marker-popup">
                            <strong>Current Position</strong><br>
                            Time: ${startPoint.timestamp}<br>
                            Vehicle: ${startPoint.vehicle}<br>
                            Speed: ${startPoint.speed || 'N/A'} km/h
                        </div>
                    `)
                    .openPopup();
                
                // Fit map to route bounds with padding
                const bounds = routePolyline.getBounds();
                map.fitBounds(bounds, { padding: [20, 20] });
                
                // Update route info
                updateRouteInfo(data, route);
                
                // Initialize timeline slider
                document.getElementById('timelineSlider').max = routeSegments.length - 1;
                
                // Show controls and info
                document.getElementById('routeInfo').style.display = 'block';
                document.getElementById('playbackControls').style.display = 'block';
                document.getElementById('currentPosition').style.display = 'block';
                
                // Reset playback
                resetPlayback();

            } catch (error) {
                console.error('Error displaying route:', error);
                alert('Error calculating route: ' + error.message);
            }
        }

        // Extract detailed route segments from the route geometry
        function extractRouteSegments(routeGeometry, originalPoints) {
            const segments = [];
            const routeCoords = routeGeometry.coordinates;
            
            // For each original point, find the closest point on the route
            originalPoints.forEach((point, index) => {
                if (index < originalPoints.length - 1) {
                    const currentPoint = point;
                    const nextPoint = originalPoints[index + 1];
                    
                    // Find the closest coordinates on the route for current and next points
                    const currentCoord = findClosestCoordinate(routeCoords, [currentPoint.lng, currentPoint.lat]);
                    const nextCoord = findClosestCoordinate(routeCoords, [nextPoint.lng, nextPoint.lat]);
                    
                    // Get the segment between these coordinates
                    const startIndex = routeCoords.findIndex(coord => 
                        coord[0] === currentCoord[0] && coord[1] === currentCoord[1]);
                    const endIndex = routeCoords.findIndex(coord => 
                        coord[0] === nextCoord[0] && coord[1] === nextCoord[1]);
                    
                    if (startIndex !== -1 && endIndex !== -1) {
                        const segmentCoords = routeCoords.slice(startIndex, endIndex + 1);
                        segments.push(...segmentCoords.map(coord => ({
                            lat: coord[1],
                            lng: coord[0],
                            timestamp: currentPoint.timestamp,
                            speed: currentPoint.speed,
                            vehicle: currentPoint.vehicle
                        })));
                    }
                }
            });
            
            return segments;
        }

        // Find the closest coordinate on the route to a given point
        function findClosestCoordinate(routeCoords, targetCoord) {
            let closestCoord = routeCoords[0];
            let minDistance = Number.MAX_VALUE;
            
            routeCoords.forEach(coord => {
                const distance = calculateDistance(
                    { lat: coord[1], lng: coord[0] },
                    { lat: targetCoord[1], lng: targetCoord[0] }
                );
                
                if (distance < minDistance) {
                    minDistance = distance;
                    closestCoord = coord;
                }
            });
            
            return closestCoord;
        }

        function updateRouteInfo(data, route) {
            document.getElementById('startTime').textContent = data.start_location.timestamp;
            document.getElementById('endTime').textContent = data.end_location.timestamp;
            document.getElementById('totalPoints').textContent = data.total_points;
            document.getElementById('vehicleInfo').textContent = data.start_location.vehicle;
            
            // Display route distance
            const distanceKm = (route.distance / 1000).toFixed(2);
            document.getElementById('routeDistance').textContent = distanceKm + ' km';
            
            // Calculate duration
            const startTime = new Date(data.start_location.timestamp);
            const endTime = new Date(data.end_location.timestamp);
            const durationMs = endTime - startTime;
            const durationHours = Math.floor(durationMs / (1000 * 60 * 60));
            const durationMinutes = Math.floor((durationMs % (1000 * 60 * 60)) / (1000 * 60));
        }

        function clearMap() {
            if (routeControl) {
                map.removeControl(routeControl);
            }
            if (driverMarker) map.removeLayer(driverMarker);
            if (startMarker) map.removeLayer(startMarker);
            if (endMarker) map.removeLayer(endMarker);
            
            // Remove any existing polylines
            map.eachLayer(function(layer) {
                if (layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
        }

        // Playback controls
        document.getElementById('playPause').addEventListener('click', togglePlayback);
        document.getElementById('reset').addEventListener('click', resetPlayback);
        document.getElementById('prevPoint').addEventListener('click', prevPoint);
        document.getElementById('nextPoint').addEventListener('click', nextPoint);

        document.getElementById('timelineSlider').addEventListener('input', function() {
            const newIndex = parseInt(this.value);
            if (newIndex >= 0 && newIndex < routeSegments.length) {
                currentPointIndex = newIndex;
                updateDriverPosition();
                updateProgress();
            }
        });

        function togglePlayback() {
            if (isPlaying) {
                pausePlayback();
            } else {
                startPlayback();
            }
        }

        function startPlayback() {
            if (routeSegments.length === 0) return;
            
            isPlaying = true;
            document.getElementById('playPause').innerHTML = '<i class="fas fa-pause"></i> Pause';
            document.getElementById('playPause').classList.remove('btn-success');
            document.getElementById('playPause').classList.add('btn-warning');
            
            const speed = parseInt(document.getElementById('playbackSpeed').value);
            
            playbackInterval = setInterval(() => {
                if (currentPointIndex < routeSegments.length - 1) {
                    currentPointIndex++;
                    updateDriverPosition();
                    updateProgress();
                } else {
                    pausePlayback();
                }
            }, speed);
        }

        function pausePlayback() {
            isPlaying = false;
            document.getElementById('playPause').innerHTML = '<i class="fas fa-play"></i> Play';
            document.getElementById('playPause').classList.remove('btn-warning');
            document.getElementById('playPause').classList.add('btn-success');
            
            if (playbackInterval) {
                clearInterval(playbackInterval);
            }
        }

        function resetPlayback() {
            pausePlayback();
            currentPointIndex = 0;
            updateDriverPosition();
            updateProgress();
        }

        function prevPoint() {
            if (currentPointIndex > 0) {
                currentPointIndex--;
                updateDriverPosition();
                updateProgress();
            }
        }

        function nextPoint() {
            if (currentPointIndex < routeSegments.length - 1) {
                currentPointIndex++;
                updateDriverPosition();
                updateProgress();
            }
        }

        function updateDriverPosition() {
            if (routeSegments.length === 0) return;
            
            const point = routeSegments[currentPointIndex];
            
            // Update marker position
            driverMarker.setLatLng([point.lat, point.lng]);
            driverMarker.setPopupContent(`
                <div class="marker-popup">
                    <strong>Current Position</strong><br>
                    Time: ${point.timestamp}<br>
                    Vehicle: ${point.vehicle}<br>
                    Speed: ${point.speed || 'N/A'} km/h<br>
                    Segment: ${currentPointIndex + 1} of ${routeSegments.length}
                </div>
            `);
            
            // Update current position display
            document.getElementById('currentCoords').textContent = 
                `Lat: ${point.lat.toFixed(6)}, Lng: ${point.lng.toFixed(6)}`;
            document.getElementById('currentTime').textContent = point.timestamp;
            document.getElementById('currentSpeed').textContent = point.speed || 'N/A';
            document.getElementById('currentSegment').textContent = `${currentPointIndex + 1}/${routeSegments.length}`;
            
            // Center map on current position if auto-center is enabled
            if (document.getElementById('autoCenter').checked) {
                map.panTo([point.lat, point.lng]);
            }
        }

        function updateProgress() {
            const progress = ((currentPointIndex + 1) / routeSegments.length) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('currentProgress').textContent = progress.toFixed(1);
            document.getElementById('timelineSlider').value = currentPointIndex;
        }

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            document.getElementById('date').value = new Date().toISOString().split('T')[0];
        });
    </script>
    
@endsection