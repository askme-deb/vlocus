<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Tracking | Vlocus</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7814820408838796"
     crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Poppins", sans-serif;
    }

    .navbar {
      background-color: #ffffff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .navbar-brand img {
      height: 45px;
    }

    .map-container {
      height: 300px;
      border-radius: 15px;
      overflow: hidden;
      margin-bottom: 20px;
    }

    #map {
      height: 100%;
      width: 100%;
    }

    .tracking-info {
      background: #ffffff;
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 15px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .ads-section {
      background: #fffbea;
      border-radius: 12px;
      padding: 15px;
      text-align: center;
      font-size: 14px;
      color: #6c757d;
      margin-bottom: 20px;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }

    .btn-custom {
      border-radius: 50px;
      padding: 10px 25px;
      font-weight: 600;
    }

    .footer-ads {
      margin-top: 30px;
      background-color: #fffbea;
      text-align: center;
      padding: 20px;
      border-radius: 15px;
    }

    @media (max-width: 768px) {
      .tracking-info {
        text-align: center;
      }
      .btn-group {
        flex-direction: column;
        gap: 10px;
      }
    }
  </style>
</head>

<body>
  <!-- Header -->
  <nav class="navbar navbar-light px-3">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('assets/logo.png') }}" alt="Company Logo">
      {{-- <span class="ms-2 fw-bold text-success">QuickDeliver</span> --}}
    </a>
  </nav>

  <div class="container py-3">
    <!-- Ads Banner -->
    <!-- Top Ad -->
    <div class="ads-section">
    <ins class="adsbygoogle"
        style="display:block"
        data-ad-client="ca-pub-7814820408838796"
        data-ad-slot="1234567890"
        data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>


    <!-- Map -->
    <div class="map-container">
      <div id="map"></div>
    </div>

    <!-- Delivery Info -->
    {{-- <div class="tracking-info">
      <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <h5 class="mb-0 text-success fw-bold">Your order is on the way 🚴</h5>
        <span class="badge bg-warning text-dark mt-2 mt-md-0">ETA: 12 min</span>
      </div>
      <p class="text-muted mb-3">Your driver {{ $driver->name }}<strong></strong> Please stay available at your location.</p>

      <div class="d-flex gap-2 btn-group">
        <a class="btn btn-outline-success btn-custom w-100">Order Details</a>
        <a href="{{ route('delivery.invoice', ['deliveryId' => $delivery->id, 'shop_id' => $delivery_shop->id]) }}" class="btn btn-success btn-custom w-100">Invoice</a>
      </div>
    </div> --}}
    <!-- Delivery Info -->
    <div class="tracking-info">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <h5 class="mb-0 text-success fw-bold">Your order is on the way 🚴</h5>
            <span class="badge bg-warning text-dark mt-2 mt-md-0">ETA: 12 min</span>
        </div>
        <p class="text-muted mb-3">
            Your driver {{ $driver->name }}<strong></strong> Please stay available at your location.
        </p>

        <div class="d-flex gap-2 btn-group">
            <button class="btn btn-outline-success btn-custom w-100" 
                        data-bs-toggle="collapse" data-bs-target="#orderDetails">
                Order Details
            </button>
            <a href="{{ route('delivery.invoice', ['deliveryId' => $delivery->id, 'shop_id' => $delivery_shop->id]) }}" 
                class="btn btn-success btn-custom w-100">
                Invoice
            </a>
        </div>
    </div>

    <!-- Collapsible Order Details -->
    <div class="collapse mt-3" id="orderDetails">
  <div class="card card-body shadow-sm">
    <h6 class="fw-bold text-success mb-2">Order Details</h6>

    <ul class="list-group list-group-flush">
      <li class="list-group-item">Order ID: <strong>{{ $delivery->order_id }}</strong></li>
      <li class="list-group-item">Shop Name: <strong>{{ $delivery_shop->shop_name }}</strong></li>
      <li class="list-group-item">Shop Address: <strong>{{ $delivery_shop->shop_address }}</strong></li>
      <li class="list-group-item">Total Items: <strong>{{ count($products) }}</strong></li>
      <li class="list-group-item">Payment Mode: <strong>{{ $delivery_schedule_shops[0]->payment_type }}</strong></li>
      <li class="list-group-item">Total Price: <strong>₹{{ $delivery_schedule_shops[0]->amount }}</strong></li>
    </ul>

    @if(!empty($products) && count($products) > 0)
      <div class="mt-3">
        <strong class="d-block mb-1 text-success">Products:</strong>
        <ul class="list-group list-group-flush">
          @foreach($products as $product)
            <li class="list-group-item">
              {{ $product->title }} - {{ $product->qty }} {{ $product->unit_or_box }}
            </li>
          @endforeach
        </ul>
      </div>
    @endif

  </div>
</div>



    <!-- Inline Ads -->
    <div class="ads-section">
    <ins class="adsbygoogle"
        style="display:block"
        data-ad-client="ca-pub-7814820408838796"
        data-ad-slot="9876543210"
        data-ad-format="auto"
        data-adtest="on"
        data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>

    <div class="ads-section">
    <ins class="adsbygoogle"
        style="display:block"
        data-ad-client="ca-pub-7814820408838796"
        data-ad-slot="9876543210"
        data-ad-format="auto"
        data-adtest="on"
        data-full-width-responsive="true"></ins>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>

    <!-- Footer Ads -->
    <div class="footer-ads">
        <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-7814820408838796"
            data-ad-slot="2468024680"
            data-ad-format="auto"
            data-adtest="on"
            data-full-width-responsive="true"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>
  </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Google Maps -->
    <script src="https://maps.gomaps.pro/maps/api/js?key=AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl&libraries=places,geometry"></script>
    <script>
    let map, driverMarker, deliveryMarker, directionsService, directionsRenderer;

    function initMap() {
        const driver = {
        lat: parseFloat({{ $driver_details->latitude }}),
        lng: parseFloat({{ $driver_details->longitude }})
        };

        const delivery = {
        lat: parseFloat({{ $delivery_shop->shop_latitude }}),
        lng: parseFloat({{ $delivery_shop->shop_longitude }})
        };

        map = new google.maps.Map(document.getElementById("map"), {
        zoom: 14,
        center: driver,
        disableDefaultUI: true,
        });

        // Driver & Delivery markers
        driverMarker = new google.maps.Marker({
            position: driver,
            map,
            icon: "https://maps.google.com/mapfiles/kml/shapes/motorcycling.png",
            title: "Driver"
        });

        deliveryMarker = new google.maps.Marker({
            position: delivery,
            map,
            icon: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
            title: "Delivery Location"
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true,
        polylineOptions: {
            strokeColor: "#28a745",
            strokeWeight: 5
        }
        });

        // Draw initial route
        updateRoute(driver, delivery);

        // Refresh every 15 seconds
        setInterval(fetchDriverLocation, 15000);
    }

    function updateRoute(driver, delivery) {
        const request = {
        origin: driver,
        destination: delivery,
        travelMode: google.maps.TravelMode.DRIVING,
        };

        directionsService.route(request, function (result, status) {
        if (status === google.maps.DirectionsStatus.OK) {
            directionsRenderer.setDirections(result);

            const route = result.routes[0].legs[0];
            const distance = route.distance.text;
            const duration = route.duration.text;

            document.querySelector('.badge').innerText = `ETA: ${duration}`;
            document.querySelector('.text-muted strong').innerText =
            ` (${distance} away)`;
        } else {
            console.error("Directions request failed:", status);
        }
        });
    }

    function fetchDriverLocation() {
        // AJAX call to get updated driver coordinates from Laravel
        fetch("{{ route('driver.location', $driver_details->id) }}")
        .then(response => response.json())
        .then(data => {
            const newDriverPos = { lat: parseFloat(data.latitude), lng: parseFloat(data.longitude) };
            driverMarker.setPosition(newDriverPos);
            map.panTo(newDriverPos);

            const delivery = {
            lat: parseFloat({{ $delivery_shop->shop_latitude }}),
            lng: parseFloat({{ $delivery_shop->shop_longitude }})
            };

            // Redraw the route
            updateRoute(newDriverPos, delivery);
        })
        .catch(err => console.error("Error updating driver location:", err));
    }

    window.onload = initMap;
    </script>

</body>
</html>
