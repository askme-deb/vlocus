<?php $__env->startSection('title'); ?>
    Delivery Details
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<style>
    html, body {
        height: 100%;
        margin: 0;
    }
    .full-height {
        height: calc(100vh - 60px); /* Adjust if navbar height changes */
    }
    .map-container {
        height: 100%;
        min-height: 400px;
    }
    .details-container {
        overflow-y: auto;
        height: 100%;
    }
</style>

<div class="container-fluid p-0">
    <div class="card shadow-lg border-0 rounded-0 full-height">

        <div class="card-body p-0">
            <div class="row g-0 h-100">
                <!-- LEFT: Map -->
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <div id="shopMap" class="map-container"></div>
                </div>

                <!-- RIGHT: Details -->
                <div class="col-lg-4 col-md-5 col-sm-12 bg-light p-4 d-flex flex-column" style="height: 100vh;">
                    
                    <!-- Top: Delivery & Driver Info -->
                    <div style="flex-shrink: 0; font-size: 1rem; line-height: 1.5;">
                        <!-- Delivery Info -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 fw-bold" style="font-size: 1.2rem;">Delivery Details</h5>
                            <p class="mb-2"><strong>Date:</strong> <?php echo e($delivery->delivery_date); ?></p>
                            <p class="mb-2"><strong>Status:</strong> <?php echo e(ucfirst($delivery->status)); ?></p>
                        </div>

                        <!-- Driver Info -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 fw-bold" style="font-size: 1.2rem;">Driver Info</h5>
                            <p class="mb-2"><strong>Name:</strong> <?php echo e($driver->name); ?></p>
                            <p class="mb-2"><strong>Phone:</strong> <?php echo e($driver->phone); ?></p>
                        </div>
                    </div>

                    <!-- Scrollable Shops Section -->
                    <div style="overflow-y: auto; flex: 1; font-size: 1rem; padding-right: 5px;">
                        <h5 class="border-bottom pb-2 fw-bold" style="font-size: 1.2rem;">Shops</h5>
                        <ol class="ps-3">
                            <?php $__currentLoopData = $delivery_schedule_shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-4" data-delivery-schedule-shop-id="<?php echo e($shop->id); ?>" data-shop-id="<?php echo e($shop->shop->id ?? ''); ?>">
                                    <p class="mb-2"><strong>Status : </strong>
                                        <span class="shop-status">
                                        <?php if($shop->is_delivered): ?>
                                            <span class="badge bg-success">Delivered</span>
                                        <?php elseif($shop->status === 'rejected'): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php elseif($shop->is_accepted): ?>
                                            <span class="badge bg-info text-dark">Accepted</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                        </span>
                                    </p>
                                    <p class="mb-2"><strong>LR No:</strong> <?php echo e($shop->lr_no); ?></p>
                                    <p class="mb-2"><strong>Shop Name:</strong> <?php echo e($shop->shop->shop_name ?? 'N/A'); ?></p>
                                    <p class="mb-2"><strong>Address:</strong> <?php echo e($shop->shop->shop_address ?? 'N/A'); ?></p>
                                    <p class="mb-2"><strong>Contact:</strong> <?php echo e($shop->shop->shop_contact_person_name ?? 'N/A'); ?>

                                        (<?php echo e($shop->shop->shop_contact_person_phone ?? ''); ?>)</p>
                                    <p class="mb-2">
                                        <strong>Location:</strong>
                                        <a href="https://maps.google.com/?q=<?php echo e($shop->shop->shop_latitude); ?>,<?php echo e($shop->shop->shop_longitude); ?>"
                                        target="_blank" class="text-decoration-none">
                                            View on Map
                                        </a>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Invoice:</strong>
                                        <a href="<?php echo e(route('delivery.invoice', ['deliveryId' => $delivery->id, 'shop_id' => $shop->shop->id])); ?>" target="_blank" class="text-decoration-none">
                                            Click Here
                                        </a>
                                    </p>
                                    <?php if(!empty($shop->products)): ?>
                                        <div class="mt-2">
                                            <strong>Products:</strong>
                                            <ul class="mb-0">
                                                <?php $__currentLoopData = $shop->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($product->title); ?> - <?php echo e($product->qty); ?> <?php echo e($product->unit_or_box); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    <div class="mt-2 reject-shop-wrapper" <?php if($shop->is_delivered || $shop->status === 'rejected'): ?> style="display:none;" <?php endif; ?>>
                                        <button type="button" class="btn btn-sm btn-outline-danger reject-shop" data-delivery-schedule-shop-id="<?php echo e($shop->id); ?>">
                                            <i class="fa fa-times"></i> Reject
                                        </button>
                                    </div>
                                    <hr>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<!-- Load GoMaps -->


<script src="https://maps.mapthrust.io/maps/api/js?key=AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl&libraries=places,geometry"></script>

<script>
    let map;
    let routeLine;
    let selectedShops = [];
    let shopMarkers = [];
    let driverLat = <?php echo e($delivery_sender_branch->latitude ?? 22.5059365); ?>;
    let driverLng = <?php echo e($delivery_sender_branch->longitude ?? 88.3716779); ?>;

    <?php $__currentLoopData = $delivery_schedule_shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($item->status !== 'rejected'): ?>
    selectedShops.push({
        id: <?php echo e($item->shop->id); ?>,
        deliveryScheduleShopId: <?php echo e($item->id); ?>,
        name: <?php echo json_encode($item->shop->shop_name, 15, 512) ?>,
        lat: parseFloat(<?php echo e($item->shop->shop_latitude); ?>),
        lng: parseFloat(<?php echo e($item->shop->shop_longitude); ?>),
        address: <?php echo json_encode($item->shop->shop_address, 15, 512) ?>,// keep products so you can pre-fill items
    });
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    function initializeMap() {
        
        map = new google.maps.Map(document.getElementById("shopMap"), {
            center: {
                lat: driverLat,
                lng: driverLng
            },
            zoom: 12,
        });

        console.log(map);
        new google.maps.Marker({
            position: {
                lat: driverLat,
                lng: driverLng
            },
            map: map,
            title: "Driver",
            icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
        });
    }

    async function drawMultiStopRoute() {
        if (selectedShops.length === 0) {
            if (routeLine) routeLine.setMap(null);
            shopMarkers.forEach(marker => marker.setMap(null));
            shopMarkers = [];
            return;
        }

        const body = {
            origin: {
                location: {
                    latLng: {
                        latitude: driverLat,
                        longitude: driverLng
                    }
                }
            },
            destination: {
                location: {
                    latLng: {
                        latitude: selectedShops.at(-1).lat,
                        longitude: selectedShops.at(-1).lng
                    }
                }
            },
            intermediates: selectedShops.slice(0, -1).map(shop => ({
                location: {
                    latLng: {
                        latitude: shop.lat,
                        longitude: shop.lng
                    }
                }
            })),
            travelMode: "DRIVE",
            routingPreference: "TRAFFIC_AWARE",
            computeAlternativeRoutes: false,
            polylineEncoding: "ENCODED_POLYLINE",
            languageCode: "en-US",
            units: "METRIC"
        };

        try {
            const response = await fetch("https://routes.mapthrust.io/directions/v2:computeRoutes", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Goog-Api-Key": "AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl",
                    "X-Goog-FieldMask": "*"
                },
                body: JSON.stringify(body)
            });

            const data = await response.json();
            if (!data.routes || !data.routes.length) {
                alert("No route found.");
                return;
            }

            const polyline = data.routes[0].polyline.encodedPolyline;
            const decodedPath = google.maps.geometry.encoding.decodePath(polyline);

            if (routeLine) routeLine.setMap(null);
            routeLine = new google.maps.Polyline({
                path: decodedPath,
                geodesic: true,
                strokeColor: "#000000",
                strokeOpacity: 1.0,
                strokeWeight: 4,
            });
            routeLine.setMap(map);

            const bounds = new google.maps.LatLngBounds();
            decodedPath.forEach(p => bounds.extend(p));
            map.fitBounds(bounds);

            google.maps.event.addListenerOnce(map, "bounds_changed", function () {
                map.setZoom(12); // closer view
            });

            // Clear old markers
            shopMarkers.forEach(marker => marker.setMap(null));
            shopMarkers = [];

            const infoWindow = new google.maps.InfoWindow();

            const allPoints = [{
                    lat: driverLat,
                    lng: driverLng,
                    label: "Driver",
                    address: ""
                },
                ...selectedShops.map(shop => ({
                    lat: shop.lat,
                    lng: shop.lng,
                    label: shop.name,
                    address: shop.address
                }))
            ];

            allPoints.forEach(point => {
                const marker = new google.maps.Marker({
                    position: {
                        lat: point.lat,
                        lng: point.lng
                    },
                    map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: "#000000",
                        fillOpacity: 1,
                        strokeWeight: 2,
                        strokeColor: "#fff"
                    },
                    title: point.label
                });

                // marker.addListener("click", () => {
                //     infoWindow.setContent(`<strong>${point.label}</strong><br>${point.address || ''}`);
                //     infoWindow.open(map, marker);
                // });

                // showing on google maps

                marker.addListener("click", () => {
                    const mapsUrl =
                        `https://www.google.com/maps/search/?api=1&query=${point.lat},${point.lng}`;
                    const content = `
                        <strong>${point.label}</strong><br>
                        ${point.address || ''}<br>
                        <a href="${mapsUrl}" target="_blank" style="color: #007bff; text-decoration: underline;">
                            View on Google Maps
                        </a>
                    `;
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                });

                shopMarkers.push(marker);
            });
        } catch (err) {
            console.error("Route draw failed:", err);
        }
    }

    $(document).ready(function() {
        try {
            initializeMap(); // Always initialize with driver's location
        } catch (e) {
            console.error('initializeMap failed, continuing without the map:', e);
        }

        drawMultiStopRoute();


        $(document).on('click', '.toggle-details', function() {
            const $details = $(this).closest('li').find('.shop-details');
            $details.slideToggle(200);

            // Rotate icon for feedback
            $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
        });

        $(document).on('click', '.reject-shop', function() {
            const $btn = $(this);
            const $li = $btn.closest('li');
            const deliveryScheduleShopId = $btn.data('delivery-schedule-shop-id');

            if (!confirm('Reject this stop and remove it from the route?')) {
                return;
            }

            $btn.prop('disabled', true).text('Rejecting...');

            $.ajax({
                url: '<?php echo e(route("delivery-schedule.shop.reject", ":id")); ?>'.replace(':id', deliveryScheduleShopId),
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (!response.success) {
                        alert(response.message || 'Failed to reject this stop.');
                        $btn.prop('disabled', false).html('<i class="fa fa-times"></i> Reject');
                        return;
                    }

                    // Update the status badge and hide the reject button
                    $li.find('.shop-status').html('<span class="badge bg-danger">Rejected</span>');
                    $li.find('.reject-shop-wrapper').hide();

                    // Remove this stop from the live route and recalculate
                    selectedShops = selectedShops.filter(shop => shop.deliveryScheduleShopId != deliveryScheduleShopId);
                    drawMultiStopRoute();
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to reject this stop.';
                    alert(message);
                    $btn.prop('disabled', false).html('<i class="fa fa-times"></i> Reject');
                }
            });
        });

        // Collapse all shop details if user clicks outside any shop block
        // $(document).on('click', function(e) {
        //     const $target = $(e.target);
        //     const clickedInsideShop = $target.closest(
        //         '.list-group-item, #suggestions, #search_shop_name').length > 0;

        //     if (!clickedInsideShop) {
        //         $('.shop-details').slideUp(200);
        //         $('.toggle-details i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        //     }
        // });
        
        $(document).on('click', function(e) {
            const $target = $(e.target);
            const isInsideRelevantBlock = $target.closest(
                    '.list-group-item, .toggle-details, .shop-details, .search-shop-name, #suggestions')
                .length > 0;

            if (!isInsideRelevantBlock) {
                $('.shop-details').slideUp(200);
                $('.toggle-details i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                $('#suggestions').hide();
            }
        });

    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/delivery_schedules/tracking_details.blade.php ENDPATH**/ ?>