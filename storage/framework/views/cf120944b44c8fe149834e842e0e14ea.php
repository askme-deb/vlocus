<?php $__env->startSection('title'); ?>
    Vehicle Tracking
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .vehicle-list-container {
        height: 400px;
        overflow-y: auto;
        padding-right: 10px;
        position: relative;
    }
    
    .map-container {
        height: 100%;
        position: relative;
    }
    
    .list-group-item {
        border-radius: 8px;
        margin-bottom: 8px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border-left: 3px solid transparent;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    .vehicle-item.active {
        border-left: 3px solid #0d6efd;
    }
    
    .list-group-item.active {
        z-index: 2;
        color: var(--bs-list-group-active-color);
        background-color: var(--bs-list-group-active-bg);
        border-color: var(--bs-list-group-active-border-color);
    }
    
    /* Map loading overlay */
    .map-loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .row {
            flex-direction: column;
        }
        
        .col-md-4, .col-md-8 {
            width: 100%;
            max-width: 100%;
        }
        
        .vehicle-list-container {
            height: 300px;
            margin-bottom: 20px;
        }
        
        #vehicleTrackingMap {
            height: 400px;
        }
    }
    
    /* Custom scrollbar */
    .vehicle-list-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .vehicle-list-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .vehicle-list-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .vehicle-list-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Driver details styles */
    .driver-details-container {
        height: 100%;
        overflow-y: auto;
    }
    
    #back-to-list {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .trip-route {
        margin-top: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    
    .route-step {
        display: flex;
        margin-bottom: 8px;
        align-items: flex-start;
    }
    
    .route-step-icon {
        margin-right: 10px;
        color: #0d6efd;
    }
    
    .route-step-content {
        flex: 1;
    }
    
    .route-distance {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    /* Transition effects */
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Delivery progress specific styles */
    .route-steps-container {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .route-step {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: flex-start;
    }
    
    .route-step-icon {
        font-size: 1.2rem;
        margin-right: 10px;
        width: 24px;
        text-align: center;
    }
    
    .route-step-content {
        flex: 1;
    }
    
    .route-distance {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 3px;
    }
    
    /* Progress bar animation */
    .progress-bar {
        transition: width 0.6s ease;
    }
    
    /* Pulse animation for live updates */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .updating-route {
        animation: pulse 1.5s infinite;
    }
  
  /*---------------------------new css 4/7/26---------------------------------  */
   .vehicle-item{
    background:#fff;
    border:1px solid #edf2f7;
    border-radius:16px;
    padding:10px;
    margin-bottom:15px;
    cursor:pointer;
    transition:.3s;
    box-shadow:0 5px 20px rgba(15,23,42,.05);
}

.vehicle-item:hover{
    transform:translateY(-4px);
    box-shadow:0 15px 35px rgba(37,99,235,.12);
    border-color:#2563eb;
} 
 
 #search-container .form-control{
    height:50px;
    border-radius:14px;
    border:none;
    background:#f8fafc;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
}

#search-container .input-group-text{
    background:#f8fafc;
    border:none;
    border-radius:14px 0 0 14px;
}

.btn-group .btn{
    border-radius:12px !important;
    font-weight:600;
    padding:10px 18px;
}

.btn-check:checked+.btn{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    box-shadow:0 10px 20px rgba(37,99,235,.2);
}

.form-switch .form-check-input{
    width:50px;
    height:28px;
    cursor:pointer;
}

.map-container {
    /* background: #fff; */
    /* border-radius: 18px; */
    padding: 20px 0px;
    /* box-shadow: 0 10px 35px rgba(15, 23, 42, .08); */
}

#vehicleTrackingMap{
    border-radius:16px !important;
    overflow:hidden;
}

.driver-details-container .card{
    border:none;
    border-radius:16px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.status-online{
    background:#ecfdf5;
    color:#16a34a;
    padding:6px 14px;
    border-radius:50px;
    font-weight:600;
}

.status-offline{
    background:#fef2f2;
    color:#dc2626;
    padding:6px 14px;
    border-radius:50px;
}

.main-content{
    background:#f5f7fb;
}

.card{
    border:none;
    border-radius:20px;
}

.vehicle-list-container{
    height:64vh;
    overflow:auto;
}

.vehicle-list-container::-webkit-scrollbar{
    width:6px;
}

.vehicle-list-container::-webkit-scrollbar-thumb{
    background:#2563eb;
    border-radius:20px;
}
 
 #filter-container{
    display:flex !important;
    flex-wrap:wrap;
    gap:12px;
}

#filter-container .btn{
    border-radius: 12px !important;
    padding: 8px 18px;
    font-weight: 600;
    border: 1px solid #dbe4f0;
    /* background: #fff; */
    transition: .3s;
    height: 43px;
    font-size: 16px;
}

#filter-container .btn:hover{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    transform:translateY(-2px);
}

.btn-check:checked + .btn{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    box-shadow:0 8px 20px rgba(37,99,235,.25);
}
 .btn-group{
    display:flex !important;
    gap:12px;
}

.btn-group .btn{
    border-radius:12px !important;
    padding:8px 18px;
    font-weight:600;
    border:1px solid #dbe4f0;
    background:#fff;
    color:#475569;
    transition:all .3s ease;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
}

.btn-group .btn:hover{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(37,99,235,.25);
}

.aspo {
    position: absolute;
    z-index: 9;
    width: 100%;
    padding: 20px;
} 
  
  
  
.filter-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.list-title {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
}

#filter-container {
  display: flex;
  gap: 5px;
}

.btn {
  padding: 4px 10px;
  font-size: 12px;  
  border: 1px solid #007bff;
  border-radius: 4px;
  cursor: pointer;
  background: white;
  color: #007bff;
}

.btn-check {
  display: none;
}

.btn-check:checked + .btn {
  background-color: #007bff;
  color: white;
}
  
.gder {
    padding: 10px;
    border-radius: 30px;
    background: #ffffff;
}

div#search-container {
    border: 1px solid #3333332b;
    border-radius: 30px;
    padding: 4px;
}  

.list-group-item:first-child {
    border-radius: 16px !important;
} 

.list-group-item:last-child {
    border-radius: 16px !important;
}


/* ========================================================================== */
/* MOBILE RESPONSIVE FIXES FOR VEHICLE TRACKING PAGE                          */
/* ========================================================================== */

@media (max-width: 991.98px) {
    /* Allow natural scrolling on mobile */
    html, body {
        height: auto !important;
        overflow: auto !important;
    }

    /* Main wrapper and content spacing fixes */
    main.main-wrapper {
        margin-top: 60px !important;
        padding: 10px !important;
    }

    .main-content {
        padding: 0 !important;
    }

    /* Stack Bootstrap Columns Vertically (Vehicle List on Top, Map Below) */
    .row {
        display: flex !important;
        flex-direction: column !important;
        margin: 0 !important;
    }

    .col-md-4, .col-md-8 {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
        padding: 0 !important;
    }

    /* Vehicle list height adjustment for small screens */
    .vehicle-list-container {
        height: 350px !important;
        max-height: 50vh;
        margin-bottom: 15px;
    }

    /* Map container full width and touch-friendly height */
    .map-container {
        position: relative !important;
        height: auto !important;
        padding: 0 !important;
        margin-bottom: 60px;
    }

    #vehicleTrackingMap {
        height: 400px !important;
        width: 100% !important;
    }

    /* Filter buttons wrapping and spacing */
    .gder {
        flex-direction: column !important;
        gap: 10px !important;
        border-radius: 16px !important;
        padding: 12px !important;
    }

    #filter-container {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 6px !important;
    }

    #filter-container .btn {
        padding: 6px 12px !important;
        font-size: 11px !important;
    }

    /* Floating map overlay controls bar positioning fix */
    .aspo {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        padding: 10px 0 !important;
        background: transparent !important;
        flex-wrap: wrap;
        gap: 10px;
    }
}

@media (max-width: 575.98px) {
    /* Extra adjustments for very small screens */
    .vehicle-item {
        padding: 8px !important;
    }
    
    .btn-group .btn {
        padding: 6px 10px !important;
        font-size: 11px !important;
    }
}

    
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!--breadcrumb-->
    
    <!--end breadcrumb-->

    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div >
                        
                        <!-- Search Box -->
                        <div class="input-group mb-3" id="search-container">
                            <span class="input-group-text"><i class="material-icons-outlined">search</i></span>
                            <input type="text" class="form-control" id="vehicleSearch" placeholder="Search vehicles...">
                        </div>
                        
                       
                        
                        <!-- Vehicle List (initially visible) -->
                        <div class="vehicle-list-container" id="vehicle-list">
                            <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $current_active_delivery = $driver->currentActiveDelivery;
                                // if($current_active_delivery){
                                //     dd($current_active_delivery);

                                // }
                                $driver_vehicle = $current_active_delivery?->vehicle; // use null-safe operator
                                $driver_lat = $driver->driver?->latitude ?? $driver->driver?->latestLocation?->latitude;
                                $driver_lng = $driver->driver?->longitude ?? $driver->driver?->latestLocation?->longitude;
                                $has_rejected_shop = $current_active_delivery?->deliveryScheduleShops?->contains('status', 'rejected') ?? false;
                                $is_rejected = $has_rejected_shop || ($current_active_delivery->status ?? '') === 'rejected';
                            ?>
                            
                            <div class="list-group-item list-group-item-action flex-column align-items-start mb-2 vehicle-item"
                                 data-id="<?php echo e($driver_vehicle->id ?? null); ?>"
                                 data-driver-id="<?php echo e($driver->id ?? null); ?>"
                                 data-lat="<?php echo e($driver_lat); ?>"
                                 data-lng="<?php echo e($driver_lng); ?>"
                                 data-name="<?php echo e($driver_vehicle->name ?? null); ?>"
                                 data-vehicle-number="<?php echo e($driver_vehicle->vehicle_number ?? null); ?>"
                                 data-driver-name="<?php echo e($driver->name); ?>"
                                 data-driver-phone="<?php echo e($driver->phone); ?>"
                                 data-status="<?php echo e($driver->driver?->ride_mode == 1 ? 'Online' : 'Offline'); ?>"
                                 data-running-status="<?php echo e(!empty($driver_vehicle) == 1 ? 'Running' : 'Idle'); ?>"
                                 data-delivery-status="<?php echo e($is_rejected ? 'rejected' : ($current_active_delivery->status ?? '')); ?>"
                                 data-last-updated="<?php echo e(optional($driver_vehicle?->updated_at)?->diffForHumans() ?? 'N/A'); ?>">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo e($driver_vehicle->name ?? null); ?></h6>
                                    <div class="status">
                                        <span class="dash-lable mb-0 bg-<?php echo e($driver->driver?->ride_mode == 1 ? 'success' : 'danger'); ?> bg-opacity-10 text-<?php echo e($driver->driver?->ride_mode == 1 ? 'success' : 'danger'); ?> rounded-2"><?php echo e($driver->driver?->ride_mode == 1 ? 'Online' : 'Offline'); ?></span>
                                        <span class="dash-lable mb-0 bg-<?php echo e(!empty($driver_vehicle) ? 'success' : 'danger'); ?> bg-opacity-10 text-<?php echo e(!empty($driver_vehicle) ? 'success' : 'danger'); ?> rounded-2"><?php echo e(!empty($driver_vehicle) ? 'Running' : 'Idle'); ?></span>
                                        <?php if($is_rejected): ?>
                                            <span class="dash-lable mb-0 bg-danger bg-opacity-10 text-danger rounded-2">Rejected</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="mb-1">Driver: <?php echo e($driver->name); ?> (<?php echo e($driver->phone); ?>)</p>
                                <p class="mb-1">Vehicle Number: <?php echo e($driver_vehicle->vehicle_number ?? 'N/A'); ?></p>
                                <small>Last updated: <span class="update-time"><?php echo e($driver->updated_at->diffForHumans()); ?></span></small>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                        </div>
                        
                        <!-- Driver Details Container (initially hidden) -->
                        <div class="driver-details-container fade-in" id="driver-details" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Driver Details</h5>
                                <button class="btn btn-sm btn-outline-secondary" id="back-to-list">
                                    <i class="fa fa-arrow-left"></i> Back
                                </button>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 id="detail-vehicle-name" class="mb-0"></h5>
                                        <span id="detail-status" class="badge"></span>
                                    </div>
                                    <div class="mt-2">
                                        <p class="mb-1"><strong>Vehicle:</strong> <span id="detail-vehicle-number"></span></p>
                                        <p class="mb-1"><strong>Driver:</strong> <span id="detail-driver-name"></span></p>
                                        <p class="mb-1"><strong>Driver Phone:</strong> <span id="detail-driver-phone"></span></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-body">
                                    <h6>Delivery Progress</h6>
                                    <!--<div class="progress mb-3">-->
                                    <!--    <div id="delivery-progress" class="progress-bar" role="progressbar"></div>-->
                                    <!--</div>-->
                                    
                                    <div id="current-task-info"></div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Map Column -->
                <div class="col-md-8">
                    <div class="d-flex justify-content-between gder">
                    
                     <!-- Status Filter Radio Buttons -->
                        <div class="btn-group btn-group-sm" id="filter-container" role="group" aria-label="Status filter">
                            <input type="radio" class="btn-check" name="statusFilter" id="filterAll" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="filterAll">All</label>
                            
                            <input type="radio" class="btn-check" name="statusFilter" id="filterOnline" autocomplete="off">
                            <label class="btn btn-outline-primary" for="filterOnline">Online</label>
                            
                            <input type="radio" class="btn-check" name="statusFilter" id="filterOffline" autocomplete="off">
                            <label class="btn btn-outline-primary" for="filterOffline">Offline</label>
                            
                            <input type="radio" class="btn-check" name="statusFilter" id="filterIdle" autocomplete="off">
                            <label class="btn btn-outline-primary" for="filterIdle">Idle</label>

                            <input type="radio" class="btn-check" name="statusFilter" id="filterRunning" autocomplete="off">
                            <label class="btn btn-outline-primary" for="filterRunning">Running</label>
                            
                            <input type="radio" class="btn-check" name="statusFilter" id="filterRejected" autocomplete="off">
                            <label class="btn btn-outline-primary" for="filterRejected">Reject</label>
                        </div>
                         <div class="align-items-center mb-3">
                            <h5 class="mb-0b lvt" id="list-title">Live Vehicle Tracking</h5>
                   </div>
                        </div>
                        
                        
                    <div class="map-container">
                        <div class="d-flex justify-content-between align-items-center mb-3 aspo">
                            <!--<h5 class="mb-0">Live Location Tracking</h5>-->
                             <!--<h5 class="mb-0">Live Vehicle Tracking</h5>-->
                            
                             <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="liveTrackingToggle" checked>
                                <label class="form-check-label" for="liveTrackingToggle" style="font-size: 12px;padding: 10px 5px;">Live Updates</label>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-secondary" id="zoom-to-fit">
                                    <i class="fa fa-expand"></i> Fit All
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="center-on-driver">
                                    <i class="fa fa-location-arrow"></i> Center
                                </button>
                            </div>
                        </div>
                        <div id="vehicleTrackingMap" style="height: 590px; width: 100%; border-radius: 8px;">
                            <div class="map-loading-overlay">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading map...</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Last updated: <span id="map-update-time">Just now</span></small>
                                <small class="text-muted">Tracking interval: <span id="update-interval">30</span> seconds</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Cache DOM elements
        const $searchInput = $('#vehicleSearch');
        const $statusFilters = $('input[name="statusFilter"]');
        const $vehicleItems = $('.vehicle-item');
        
        // Search function
        $searchInput.on('input', function() {
            filterVehicles();
        });
        
        // Status filter function
        $statusFilters.on('change', function() {
            filterVehicles();
        });
        
        // function filterVehicles() {
        //     const searchTerm = $searchInput.val().toLowerCase();
        //     const selectedStatus = $('input[name="statusFilter"]:checked').attr('id');
        //     const selectedRunningStatus = $('input[name="runningFilter"]:checked').attr('id');
            
        //     $vehicleItems.each(function() {
        //         const $item = $(this);
        //         const name = String($item.data('name') || '').toLowerCase();
        //         const vehicleNumber = String($item.data('vehicle-number') || '').toLowerCase();
        //         const driverName = String($item.data('driver-name') || '').toLowerCase();
        //         const driverPhone = String($item.data('driver-phone') || '').toLowerCase();
        //         const status = String($item.data('status') || '');
        //         const running_status = String($item.data('running-status') || '');
                
        //         // Check search term
        //         const matchesSearch = name.includes(searchTerm) || 
        //                             vehicleNumber.includes(searchTerm) || 
        //                             driverName.includes(searchTerm) || 
        //                             driverPhone.includes(searchTerm);
                
        //         // Check status filter
        //         let matchesStatus = true;
        //         if (selectedStatus === 'filterOnline') {
        //             matchesStatus = status === 'Online';
        //         } else if (selectedStatus === 'filterOffline') {
        //             matchesStatus = status === 'Offline';
        //         } 

        //         if (selectedRunningStatus === 'filterIdle') {
        //             matchesStatus = running_status === 'Idle';
        //         } else if (selectedRunningStatus === 'filterRunning') {
        //             matchesStatus = running_status === 'Running';
        //         }
                
        //         // Show/hide based on filters
        //         if (matchesSearch && matchesStatus) {
        //             $item.show();
        //         } else {
        //             $item.hide();
        //         }
        //     });
        // }

        function filterVehicles() {
            const searchTerm = $searchInput.val().toLowerCase();
            const selectedStatus = $('input[name="statusFilter"]:checked').attr('id');
            
            $vehicleItems.each(function() {
                const $item = $(this);
                console.log($item);
                const name = String($item.data('name') || '').toLowerCase();
                const vehicleNumber = String($item.data('vehicle-number') || '').toLowerCase();
                const driverName = String($item.data('driver-name') || '').toLowerCase();
                const driverPhone = String($item.data('driver-phone') || '').toLowerCase();
                const status = String($item.data('status') || '');
                const running_status = String($item.data('running-status') || '');
                const delivery_status = String($item.data('delivery-status') || '').toLowerCase();

                // --- Search Filter ---
                const matchesSearch =
                    name.includes(searchTerm) ||
                    vehicleNumber.includes(searchTerm) ||
                    driverName.includes(searchTerm) ||
                    driverPhone.includes(searchTerm);

                // --- Online/Offline Filter ---
                let matchesStatus = true;
                if (selectedStatus === 'filterOnline') {
                    matchesStatus = status === 'Online';
                } else if (selectedStatus === 'filterOffline') {
                    matchesStatus = status === 'Offline';
                } else if (selectedStatus === 'filterIdle') {
                    matchesStatus = running_status === 'Idle';
                } else if (selectedStatus === 'filterRunning') {
                    matchesStatus = running_status === 'Running';
                } else if (selectedStatus === 'filterRejected') {
                    matchesStatus = delivery_status === 'rejected';
                }

                // --- Final Combined Logic ---
                if (matchesSearch && matchesStatus) {
                    $item.show();
                } else {
                    $item.hide();
                }
            });
        }

        
        // Trigger initial filter
        filterVehicles();
    });
</script>
<script>
    // Global variables
    const GOOGLE_MAPS_API_KEY = "<?php echo e(env('GOOGLE_MAPS_API_KEY')); ?>";
    let map;
    let markers = [];
    let liveUpdateInterval;
    const updateInterval = 30000; // 30 seconds
    let currentDriverPosition = null;
    let infoWindow = null;
    let allMarkersVisible = true;
    let directionsService;
    let directionsRenderer;
    let selectedDriverMarker = null;
    let routePolyline = null;
    let destinationMarker = null;

    // Truck icons configuration
    let truckIcons; // Declare variable at global scope
    
    const deliveryMap = {
        currentDeliveries: [],
        deliveryRenderers: [],  // This was missing
        deliveryMarkers: [],
        liveUpdateInterval: null
    };

    // Initialize the map
    function initMap() {
        try {
            // Hide loading overlay
            const loadingOverlay = document.querySelector('.map-loading-overlay');
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            
            // Initialize the map with default center
            const defaultCenter = new google.maps.LatLng(22.505095, 88.373974);
            map = new google.maps.Map(document.getElementById("vehicleTrackingMap"), {
                center: defaultCenter,
                zoom: 12,
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            
            truckIcons = {
                Online: {
                    url: "<?php echo e(asset('assets/dashboard-assets/assets/images/pickup-truck.png')); ?>",
                    scaledSize: new google.maps.Size(40, 40)
                },
                Offline: {
                    url: "<?php echo e(asset('assets/dashboard-assets/assets/images/pickup-truck.png')); ?>",
                    scaledSize: new google.maps.Size(40, 40),
                    opacity: 0.6
                }
            };

            // Initialize info window
            infoWindow = new google.maps.InfoWindow();
            
            // Initialize directions service
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: '#0d6efd',
                    strokeOpacity: 0.8,
                    strokeWeight: 4
                }
            });
            directionsRenderer.setMap(map);
            
            deliveryMap.directionsService = new google.maps.DirectionsService();
            deliveryMap.directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: '#0d6efd',
                    strokeOpacity: 0.8,
                    strokeWeight: 4
                }
            });
            deliveryMap.directionsRenderer.setMap(map);
            
            // Add all drivers to the map initially
            addAllDriversToMap();
            
            // Set up event listeners
            setupEventListeners();
            
            // Set up live updates
            setupLiveUpdates();
            
        } catch (error) {
            console.error('Error initializing map:', error);
            const loadingOverlay = document.querySelector('.map-loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.innerHTML = `
                    <div class="alert alert-danger">
                        <h5>Map Initialization Error</h5>
                        <p>Failed to initialize the map. Please try again later.</p>
                    </div>
                `;
                loadingOverlay.style.display = 'flex';
            }
        }
    }

    // Set up all event listeners
    function setupEventListeners() {
        // Vehicle item click handler
        document.querySelectorAll('.vehicle-item').forEach(item => {
            item.addEventListener('click', function() {
                console.log('clicked');
                // Highlight selected vehicle
                document.querySelectorAll('.vehicle-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);
                
                // Create vehicle data object
                const vehicleData = {
                    lat: lat,
                    lng: lng,
                    name: this.dataset.name,
                    vehicleNumber: this.dataset.vehicleNumber,
                    driverName: this.dataset.driverName,
                    driverId: this.dataset.driverId,
                    driverPhone: this.dataset.driverPhone,
                    status: this.dataset.status,
                    lastUpdated: this.dataset.lastUpdated
                };
                
                console.log(vehicleData);
                
                centerMapOnVehicle(lat, lng, vehicleData);
            });
        });
        
        // Back to list button
        document.getElementById('back-to-list').addEventListener('click', backToListView);
        
        // Center on driver button
        document.getElementById('center-on-driver').addEventListener('click', () => {
            if (currentDriverPosition) {
                map.setCenter(currentDriverPosition);
                map.setZoom(15);
            }
        });
        
        // Fit all markers button
        document.getElementById('zoom-to-fit').addEventListener('click', () => {
            allMarkersVisible = true;
            showAllMarkers();
            zoomToFitAllMarkers();
        });
        
        // Live tracking toggle
        document.getElementById('liveTrackingToggle').addEventListener('change', function() {
            if (this.checked) {
                setupLiveUpdates();
            } else {
                clearInterval(liveUpdateInterval);
            }
        });
        
        // Note: search/status-filter listeners are already bound in the
        // other script block's $(document).ready handler.
    }

    // Add all drivers to the map initially
    function addAllDriversToMap() {
        document.querySelectorAll('.vehicle-item').forEach(item => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);

            // Skip drivers who have never reported any GPS position
            if (isNaN(lat) || isNaN(lng)) {
                console.warn('Skipping vehicle marker with no coordinates:', item.dataset.name);
                return;
            }

            addMarker(new google.maps.LatLng(lat, lng), item.dataset);
        });
        
        // Zoom to fit all markers
        zoomToFitAllMarkers();
    }

    // Add a marker to the map
    function addMarker(position, vehicleData) {
        try {
            const marker = new google.maps.Marker({
                position: position,
                map: allMarkersVisible ? map : null,
                title: vehicleData.name,
                icon: truckIcons[vehicleData.status === 'Online' ? 'Online' : 'Offline']
            });
            
            const infoContent = `
                <div style="padding: 10px; min-width: 200px;">
                    <h6 style="margin-bottom: 5px; font-weight: 600;">${vehicleData.name}</h6>
                    <p style="margin-bottom: 3px;"><strong>Driver:</strong> ${vehicleData.driverName} (${vehicleData.driverPhone})</p>
                    <p style="margin-bottom: 3px;"><strong>Vehicle:</strong> ${vehicleData.vehicleNumber}</p>
                    <p style="margin-bottom: 3px;"><strong>Status:</strong> 
                        <span class="badge bg-${vehicleData.status === 'Online' ? 'success' : 'danger'}">
                            ${vehicleData.status}
                        </span>
                    </p>
                    <small><strong>Last updated:</strong> ${vehicleData.lastUpdated}</small>
                    <div class="mt-2">
                        <a href="https://www.google.com/maps?q=${position.lat()},${position.lng()}" 
                          target="_blank" 
                          class="btn btn-sm btn-outline-primary">
                            Open in Maps
                        </a>
                    </div>
                </div>
            `;
            
            marker.addListener('click', () => {
                infoWindow.setContent(infoContent);
                infoWindow.open(map, marker);
            });
            
            markers.push(marker);
            return marker;
        } catch (error) {
            console.error('Error creating marker:', error);
            return null;
        }
    }

    // Center map on selected vehicle and show details
    function centerMapOnVehicle(lat, lng, vehicleData) {
        const position = new google.maps.LatLng(lat, lng);
        currentDriverPosition = position;
        
        // Show driver details and hide list
        showDriverDetails(vehicleData);
        
        // Center map on selected vehicle
        map.setCenter(position);
        map.setZoom(15);
        
        // Hide all markers except the selected one
        allMarkersVisible = false;
        markers.forEach(marker => {
            if (marker.title === vehicleData.name) {
                marker.setMap(map);
            } else {
                marker.setMap(null);
            }
        });
        
        // Create or update selected driver marker
        if (selectedDriverMarker) {
            selectedDriverMarker.setPosition(position);
        } else {
            selectedDriverMarker = new google.maps.Marker({
                position: position,
                map: map,
                title: vehicleData.name,
                icon: truckIcons[vehicleData.status === 'Online' ? 'Online' : 'Offline']
            });
        }
        
        // Fetch trip information (including destination)
        showDeliveryRoute(vehicleData);
        // fetchTripInformation(vehicleData);
    }

    // Show driver details view
    function showDriverDetails(vehicleData) {
        console.log(vehicleData);
        // Hide list and show details
        document.getElementById('vehicle-list').style.display = 'none';
        document.getElementById('search-container').style.display = 'none';
        document.getElementById('filter-container').style.display = 'none';
        document.getElementById('list-title').style.display = 'none';
        document.getElementById('driver-details').style.display = 'block';
        
        // Populate details
        document.getElementById('detail-vehicle-name').textContent = vehicleData.name;
        document.getElementById('detail-driver-name').textContent = vehicleData.driverName;
        document.getElementById('detail-driver-phone').textContent = vehicleData.driverPhone;
        document.getElementById('detail-vehicle-number').textContent = vehicleData.vehicleNumber;
        document.getElementById('detail-status').textContent = vehicleData.status;
        document.getElementById('detail-status').className = `badge bg-${vehicleData.status === 'Online' ? 'success' : 'danger'}`;
        // document.getElementById('detail-last-updated').textContent = vehicleData.lastUpdated;
        
        // Show loading state for trip info
        // document.getElementById('trip-info').innerHTML = '<p>Loading trip information...</p>';
    }

    // Fetch trip information from server
    function fetchTripInformation(vehicleData) {
        // In a real application, you would make an AJAX call here
        // For demonstration, we'll use mock data after a short delay
        setTimeout(() => {
            // Mock data - replace with actual API call
            const mockTripData = {
                hasTrip: true,
                pickup: "Current Location",
                destination: "123 Main St, City",
                destinationLat: parseFloat(vehicleData.lat) + 0.01,
                destinationLng: parseFloat(vehicleData.lng) + 0.01,
                distance: "5.2 km",
                duration: "15 mins",
                status: "In Progress"
            };
            
            if (mockTripData.hasTrip) {
                // Update trip info display
                document.getElementById('trip-info').innerHTML = `
                    <div class="trip-info">
                        <p><strong>Status:</strong> <span class="badge bg-info">${mockTripData.status}</span></p>
                        <p><strong>From:</strong> ${mockTripData.pickup}</p>
                        <p><strong>To:</strong> ${mockTripData.destination}</p>
                        <p><strong>Distance:</strong> ${mockTripData.distance}</p>
                        <p><strong>Duration:</strong> ${mockTripData.duration}</p>
                    </div>
                    <div class="trip-route">
                        <h6>Route</h6>
                        <div class="route-step">
                            <div class="route-step-icon"><i class="fa fa-map-marker-alt"></i></div>
                            <div class="route-step-content">${mockTripData.pickup}</div>
                        </div>
                        <div class="route-step">
                            <div class="route-step-icon"><i class="fa fa-arrow-down"></i></div>
                            <div class="route-step-content">${mockTripData.distance} • ${mockTripData.duration}</div>
                        </div>
                        <div class="route-step">
                            <div class="route-step-icon"><i class="fa fa-flag-checkered"></i></div>
                            <div class="route-step-content">${mockTripData.destination}</div>
                        </div>
                    </div>
                `;
                
                // Show the route on the map
                showRoute(
                    new google.maps.LatLng(vehicleData.lat, vehicleData.lng),
                    new google.maps.LatLng(mockTripData.destinationLat, mockTripData.destinationLng)
                );
            } else {
                document.getElementById('trip-info').innerHTML = '<p>No active trip information available.</p>';
                
                // Clear any existing route
                directionsRenderer.setDirections({routes: []});
                if (routePolyline) routePolyline.setMap(null);
                if (destinationMarker) destinationMarker.setMap(null);
            }
        }, 500);
    }
    
    // Show delivery route when driver/delivery is clicked
    // Show delivery route when driver/delivery is clicked
    async function showDeliveryRoute(vehicleData) {
        
        // 1. FIRST HIDE ALL OTHER DRIVERS/MARKERS
        markers.forEach(marker => {
            marker.setMap(null); // Remove all markers from map
        });
        
        // 2. Clear any existing routes
        if (routePolyline) routePolyline.setMap(null);
        directionsRenderer.setDirections({routes: []});
        clearDeliveryMarkers();
        
        try {
            // Show loading state
            $('#current-task-info').html(`
                <div class="text-center my-3">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading delivery route...</p>
                </div>
            `);
            
            // Clear any existing route and markers
            clearDeliveryMarkers();
            directionsRenderer.setDirections({routes: []});
            
            // Fetch delivery details and driver position
            const [deliveriesResponse, positionResponse] = await Promise.all([
                fetch(`/delivery/get-delivery-details?driver_id=${vehicleData.driverId}`),
                fetch(`/driver/position/${vehicleData.driverId}`)
            ]);
            
            const [deliveriesData, positionData] = await Promise.all([
                deliveriesResponse.json(),
                positionResponse.json()
            ]);
    
            // Hide loader now that we have data
            $('#current-task-info').empty();
            
            if (!deliveriesData.response || !deliveriesData.data.deliveries.length) {
                $('#current-task-info').html(`
                    <div class="alert alert-info">
                        No deliveries found for this driver.
                    </div>
                `);
                return;
            }
            
            // Get driver's current position
            const driverPosition = {
                lat: parseFloat(positionData.data.latitude),
                lng: parseFloat(positionData.data.longitude)
            };
            
            // Process all shops in their original order (excluding rejected/cancelled stops from the route)
            const allShops = [];
            deliveriesData.data.deliveries.forEach(delivery => {
                // Sort shops by their original sequence (assuming there's a sequence field)
                const sortedShops = delivery.delivery_schedule_shops.sort((a, b) => a.sequence - b.sequence);

                sortedShops.forEach(shop => {
                    if (shop.status === 'rejected' || shop.status === 'cancelled') return;

                    allShops.push({
                        deliveryId: delivery.id,
                        shopId: shop.id,
                        sequence: shop.sequence,
                        status: shop.status,
                        name: shop.shop.shop_name,
                        address: shop.shop.shop_address,
                        position: new google.maps.LatLng(
                            parseFloat(shop.shop.shop_latitude),
                            parseFloat(shop.shop.shop_longitude)
                        ),
                        phone: shop.shop.shop_phone
                    });
                });
            });
            
            // Display delivery information (includes rejected/cancelled stops for visibility)
            displayDeliveryInfo(deliveriesData.data.deliveries);

            if (allShops.length === 0) {
                if (routePolyline) routePolyline.setMap(null);
                directionsRenderer.setDirections({routes: []});
                clearDeliveryMarkers();
                $('#route-steps').html(`
                    <div class="alert alert-info">
                        No active delivery locations found for this driver.
                    </div>
                `);
                return;
            }

            // Calculate and display sequential route (rejected/cancelled stops excluded)
            await calculateSequentialRoute(
                new google.maps.LatLng(driverPosition.lat, driverPosition.lng),
                allShops
            );

            // Start live updates
            startDeliveryUpdates(vehicleData.driverId, allShops);
            
        } catch (error) {
            console.error('Error loading delivery route:', error);
            $('#current-task-info').html(`
                <div class="alert alert-danger">
                    ${error.message || 'Failed to load delivery route'}
                </div>
            `);
        }
    }
    
    // Calculate route that follows specific sequence (A → B → C)
    async function calculateSequentialRoute(startLocation, shops) {
        try {
            // Clear existing markers
            clearDeliveryMarkers();
            
            // We'll create separate routes for each segment to maintain sequence
            // This is because Google's waypoints will optimize the order by default
            
            // Create an array to hold all route paths
            const routePaths = [];
            
            // Start with driver's position
            let origin = startLocation;
            
            // Sort shops by their sequence number
            shops.sort((a, b) => a.sequence - b.sequence);
            
            // Create a route for each segment
            for (let i = 0; i < shops.length; i++) {
                const destination = shops[i].position;
                
                const request = {
                    origin: origin,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING
                };
                
                // Calculate route for this segment
                const response = await new Promise((resolve, reject) => {
                    directionsService.route(request, (response, status) => {
                        if (status === 'OK') resolve(response);
                        else reject(new Error('Directions request failed: ' + status));
                    });
                });
                
                // Store the path for this segment
                if (response.routes[0] && response.routes[0].overview_path) {
                    routePaths.push(...response.routes[0].overview_path);
                }
                
                // Next segment starts where this one ended
                origin = destination;
            }
            
            // Create a single polyline connecting all segments
            if (routePolyline) routePolyline.setMap(null);
            routePolyline = new google.maps.Polyline({
                path: routePaths,
                geodesic: true,
                strokeColor: '#0d6efd',
                strokeOpacity: 0.8,
                strokeWeight: 4,
                map: map
            });
            
            // Create markers for each shop
            createShopMarkers(startLocation, shops);
            
            // Fit map to show entire route
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(startLocation);
            shops.forEach(shop => bounds.extend(shop.position));
            map.fitBounds(bounds);
            
            // Display route steps
            displayRouteSteps(startLocation, shops);
            
        } catch (error) {
            console.error('Route calculation error:', error);
            throw error;
        }
    }
    
    // Display route steps in order
    function displayRouteSteps(startLocation, shops) {
        let routeStepsHtml = '<div class="route-steps-container">';
        
        // Add start point
        routeStepsHtml += `
            <div class="route-step">
                <div class="route-step-icon"><i class="fa fa-truck"></i></div>
                <div class="route-step-content">
                    <strong>Driver Location</strong>
                    <div class="route-distance">Start point</div>
                </div>
            </div>
        `;
        
        // Add each shop in sequence
        shops.forEach((shop, index) => {
            routeStepsHtml += `
                <div class="route-step">
                    <div class="route-step-icon">
                        <i class="fa fa-${shop.status === 'delivered' ? 'check-circle' : 'map-marker-alt'}"></i>
                    </div>
                    <div class="route-step-content">
                        <strong>${index + 1}. ${shop.name}</strong>
                        <div class="route-distance">${shop.address}</div>
                        <small class="text-muted">Status: ${shop.status}</small>
                    </div>
                </div>
            `;
        });
        
        routeStepsHtml += '</div>';
        $('#route-steps').html(routeStepsHtml);
    }
    
    // Display delivery information
    function displayDeliveryInfo(deliveries) {
        let html = '<div class="deliveries-container">';
        
        deliveries.forEach(delivery => {
            const deliveryStatusClass = delivery.status === 'completed' ? 'success' :
                                      delivery.status === 'active' ? 'primary' :
                                      (delivery.status === 'rejected' || delivery.status === 'cancelled') ? 'danger' : 'secondary';

            html += `
                <div class="delivery-card mb-3">
                    <div class="card">
                        <div class="card-header bg-${deliveryStatusClass} bg-opacity-10">
                            <h5 class="mb-0">
                                Delivery #${delivery.id}
                                <span class="badge bg-${deliveryStatusClass} float-end">
                                    ${delivery.status}
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="delivery-shops">
            `;

            delivery.delivery_schedule_shops.forEach(shop => {
                const shopStatusClass = shop.status === 'delivered' ? 'success' :
                                       shop.status === 'in_progress' ? 'warning' :
                                       (shop.status === 'rejected' || shop.status === 'cancelled') ? 'danger' : 'secondary';

                html += `
                    <div class="shop-item mb-2">
                        <div class="d-flex justify-content-between">
                            <strong>${shop.shop.shop_name}</strong>
                            <span class="badge bg-${shopStatusClass}">
                                ${shop.status}
                            </span>
                        </div>
                        <small class="text-muted">${shop.shop.shop_address}</small>
                    </div>
                `;
            });

            html += `
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        $('#current-task-info').html(html);
    }
    
    // Create markers for shops and driver
    function createShopMarkers(startLocation, shops) {
        // Driver marker
        const driverMarker = new google.maps.Marker({
            position: startLocation,
            map: map,
            icon: {
                url: "<?php echo e(asset('assets/dashboard-assets/assets/images/pickup-truck.png')); ?>",
                scaledSize: new google.maps.Size(40, 40)
            },
            title: "Driver Position"
        });
        deliveryMap.deliveryMarkers.push(driverMarker);
        
        // Shop markers
        shops.forEach((shop, index) => {
            const markerColor = shop.status === 'delivered' ? '#0F9D58' : 
                              shop.status === 'in_progress' ? '#F4B400' : '#4285F4';
            
            const marker = new google.maps.Marker({
                position: shop.position,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: markerColor,
                    fillOpacity: 1,
                    strokeColor: '#fff',
                    strokeWeight: 2,
                    scale: 8
                },
                label: {
                    text: (index + 1).toString(),
                    color: '#fff',
                    fontWeight: 'bold'
                },
                title: `${shop.name} (${shop.status})`
            });
            
            // Info window content
            const infoContent = `
                <div style="min-width: 200px;">
                    <h6>${shop.name}</h6>
                    <p>${shop.address}</p>
                    <p>Phone: ${shop.phone || 'N/A'}</p>
                    <p>Status: <span class="badge bg-${getStatusBadgeColor(shop.status)}">
                        ${shop.status}
                    </span></p>
                    <div class="mt-2">
                        <a href="https://www.google.com/maps?q=${shop.position.lat()},${shop.position.lng()}" 
                          target="_blank" 
                          class="btn btn-sm btn-outline-primary">
                            Open in Maps
                        </a>
                    </div>
                </div>
            `;
            
            marker.addListener('click', () => {
                infoWindow.setContent(infoContent);
                infoWindow.open(map, marker);
            });
            
            deliveryMap.deliveryMarkers.push(marker);
        });
    }
    
    function displayAllDeliveries(deliveries) {
        let html = '<div class="deliveries-container">';
        
        deliveries.forEach(delivery => {
            const deliveryStatusClass = delivery.status === 'completed' ? 'success' :
                                      delivery.status === 'active' ? 'primary' :
                                      (delivery.status === 'rejected' || delivery.status === 'cancelled') ? 'danger' : 'secondary';

            html += `
                <div class="delivery-card mb-3">
                    <div class="card">
                        <div class="card-header bg-${deliveryStatusClass} bg-opacity-10">
                            <h5 class="mb-0">
                                Delivery #${delivery.id}
                                <span class="badge bg-${deliveryStatusClass} float-end">
                                    ${delivery.status}
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="delivery-shops">
            `;

            delivery.delivery_schedule_shops.forEach(shop => {
                const shopStatusClass = shop.status === 'delivered' ? 'success' :
                                       shop.status === 'in_progress' ? 'warning' :
                                       (shop.status === 'rejected' || shop.status === 'cancelled') ? 'danger' : 'secondary';

                html += `
                    <div class="shop-item mb-2">
                        <div class="d-flex justify-content-between">
                            <strong>${shop.shop.shop_name}</strong>
                            <span class="badge bg-${shopStatusClass}">
                                ${shop.status}
                            </span>
                        </div>
                        <small class="text-muted">${shop.shop.shop_address}</small>
                    </div>
                `;
            });

            html += `
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        $('#current-task-info').html(html);
    }
    
    // Modified calculateDeliveryRoute to handle multiple deliveries
    async function calculateDeliveryRoute(startLocation, waypoints, deliveryInfo) {
        try {
            // Group waypoints by delivery while preserving the original waypoint data
            const deliveryGroups = {};
            const originalWaypoints = []; // Store original waypoint data with delivery info
            
            waypoints.forEach(waypoint => {
                if (!deliveryGroups[waypoint.deliveryId]) {
                    deliveryGroups[waypoint.deliveryId] = [];
                }
                
                // Create clean waypoint object for Google Maps API
                const cleanWaypoint = {
                    location: waypoint.location,
                    stopover: true
                };
                
                deliveryGroups[waypoint.deliveryId].push(cleanWaypoint);
                originalWaypoints.push(waypoint); // Store original data with delivery info
            });
    
            // Create different colors for each delivery
            const colors = ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14', '#ffc107', '#198754'];
            let colorIndex = 0;
    
            // Clear existing markers and renderers
            clearDeliveryMarkers();
    
            // Process each delivery group
            for (const deliveryId in deliveryGroups) {
                const deliveryWaypoints = deliveryGroups[deliveryId];
                const color = colors[colorIndex % colors.length];
                colorIndex++;
    
                // Skip if no waypoints for this delivery
                if (deliveryWaypoints.length === 0) continue;
    
                // Calculate route for this delivery group
                const destination = deliveryWaypoints[deliveryWaypoints.length - 1].location;
                const waypointsForRoute = deliveryWaypoints.slice(0, -1);
    
                const request = {
                    origin: startLocation,
                    destination: destination,
                    waypoints: waypointsForRoute,
                    optimizeWaypoints: true,
                    travelMode: google.maps.TravelMode.DRIVING
                };
    
                const response = await new Promise((resolve, reject) => {
                    directionsService.route(request, (response, status) => {
                        if (status === 'OK') resolve(response);
                        else reject(new Error('Directions request failed: ' + status));
                    });
                });
    
                // Create a new directions renderer for each delivery
                const deliveryRenderer = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 4
                    },
                    map: map
                });
    
                deliveryRenderer.setDirections(response);
                deliveryMap.deliveryRenderers.push(deliveryRenderer);
    
                // Create markers for this delivery using the original waypoint data
                const deliveryOriginalWaypoints = originalWaypoints.filter(wp => wp.deliveryId === deliveryId);
                createDeliveryMarkers(response, deliveryOriginalWaypoints, color);
            }
    
            // Fit map to show all routes
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(startLocation);
            waypoints.forEach(wp => bounds.extend(wp.location));
            map.fitBounds(bounds);
    
        } catch (error) {
            console.error('Route calculation error:', error);
            throw error;
        }
    }
    
    function createDeliveryMarkers(response, waypoints, color) {
        // Create markers for each waypoint
        waypoints.forEach(waypoint => {
            const marker = new google.maps.Marker({
                position: waypoint.location,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: color,
                    fillOpacity: 1,
                    strokeColor: '#fff',
                    strokeWeight: 2,
                    scale: 8
                },
                title: `Delivery ${waypoint.deliveryId} - Shop ${waypoint.shopId}`
            });
    
            // Add info window
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="min-width: 200px;">
                        <h6>Delivery ${waypoint.deliveryId}</h6>
                        <p>Shop ID: ${waypoint.shopId}</p>
                        <p>Status: <span class="badge bg-${waypoint.status === 'delivered' ? 'success' : 'warning'}">
                            ${waypoint.status}
                        </span></p>
                    </div>
                `
            });
    
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
    
            deliveryMap.deliveryMarkers.push(marker);
        });
    }
    
    // Clear existing delivery markers
    function clearDeliveryMarkers() {
        deliveryMap.deliveryMarkers.forEach(marker => marker.setMap(null));
        deliveryMap.deliveryMarkers = [];
    }
    
    // Start live updates for delivery progress
    function startDeliveryUpdates(driverId, allShops) {
        // Clear any existing interval
        if (deliveryMap.liveUpdateInterval) {
            clearInterval(deliveryMap.liveUpdateInterval);
        }
        
        // First update immediately
        updateDeliveryProgress(driverId, allShops);
        
        // Set up periodic updates
        deliveryMap.liveUpdateInterval = setInterval(() => {
            updateDeliveryProgress(driverId, allShops);
        }, 30000); // 30 seconds
    }
    
    // Update delivery progress
    async function updateDeliveryProgress(driverId, allShops) {
        try {
            // 1. Get updated driver position
            const positionResponse = await fetch(`/driver/position/${driverId}`);
            const positionData = await positionResponse.json();
            const newPosition = new google.maps.LatLng(
                parseFloat(positionData.data.latitude),
                parseFloat(positionData.data.longitude)
            );
            
            // 2. Refresh delivery details
            const deliveriesResponse = await fetch(`/delivery/get-delivery-details?driver_id=${driverId}`);
            const deliveriesData = await deliveriesResponse.json();
            
            if (deliveriesData.response && deliveriesData.data.deliveries.length) {
                const delivery = deliveriesData.data.deliveries[0];
                deliveryMap.currentDelivery = delivery;
                
                // Prepare updated waypoints
                const waypoints = delivery.delivery_schedule_shops
                    .filter(shop => shop.status !== 'delivered')
                    .map(shop => ({
                        location: new google.maps.LatLng(
                            parseFloat(shop.shop.shop_latitude),
                            parseFloat(shop.shop.shop_longitude)
                        ),
                        stopover: true
                    }));
                
                // Recalculate route with new position
                // await calculateDeliveryRoute(newPosition, waypoints, delivery);
                await calculateSequentialRoute(
                    new google.maps.LatLng(positionData.data.latitude, positionData.data.longitude),
                    allShops
                );
            
                
                // Animate driver marker
                if (deliveryMap.deliveryMarkers.length > 0) {
                    const driverMarker = deliveryMap.deliveryMarkers[0];
                    driverMarker.setPosition(newPosition);
                    driverMarker.setAnimation(google.maps.Animation.BOUNCE);
                    setTimeout(() => driverMarker.setAnimation(null), 1500);
                }
                
                // Update last updated time
                $('#map-update-time').text(new Date().toLocaleTimeString());
            }
        } catch (error) {
            console.error('Error updating delivery progress:', error);
        }
    }
    
    // Helper function to get driver position
    async function getDriverPosition(driverId) {
        const response = await fetch(`/driver/position/${driverId}`);
        return await response.json();
    }
    
    // Helper function for status badge colors
    function getStatusBadgeColor(status) {
        switch (status) {
            case 'delivered': return 'success';
            case 'pending': return 'warning';
            case 'cancelled': return 'danger';
            default: return 'secondary';
        }
    }
    
    // Back to list view
    function backToListView() {
        
        // 1. FIRST HIDE ALL OTHER DRIVERS/MARKERS
        markers.forEach(marker => {
            marker.setMap(null); // Remove all markers from map
        });
        
        // 2. Clear any existing routes
        if (routePolyline) routePolyline.setMap(null);
        directionsRenderer.setDirections({routes: []});
        clearDeliveryMarkers();
        // Clear route display
        // deliveryMap.directionsRenderer.setDirections({routes: []});
        // clearDeliveryMarkers();
        
        // Stop live updates
        if (deliveryMap.liveUpdateInterval) {
            clearInterval(deliveryMap.liveUpdateInterval);
        }
        
        // Reset state
        deliveryMap.currentDelivery = null;
        
        // Show all drivers again
        updateAllDriverPositions();
        showAllMarkers();
        zoomToFitAllMarkers();
    }

    // Show route between two points
    function showRoute(start, end) {
        // Clear any existing route
        directionsRenderer.setDirections({routes: []});
        if (routePolyline) routePolyline.setMap(null);
        if (destinationMarker) destinationMarker.setMap(null);
        
        // Add destination marker
        destinationMarker = new google.maps.Marker({
            position: end,
            map: map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                scaledSize: new google.maps.Size(32, 32)
            }
        });
        
        // Calculate and display route
        directionsService.route({
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.DRIVING
        }, (response, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
            } else {
                console.error('Directions request failed due to ' + status);
                
                // Fallback: draw a straight line if directions service fails
                routePolyline = new google.maps.Polyline({
                    path: [start, end],
                    geodesic: true,
                    strokeColor: '#0d6efd',
                    strokeOpacity: 0.8,
                    strokeWeight: 4,
                    map: map
                });
            }
        });
    }

    // Return to list view
    function backToListView() {
        // Show list and hide details
        document.getElementById('vehicle-list').style.display = 'block';
        document.getElementById('search-container').style.display = 'flex';
        document.getElementById('filter-container').style.display = 'flex';
        document.getElementById('list-title').style.display = 'block';
        document.getElementById('driver-details').style.display = 'none';
        
        // Clear any selected vehicle highlight
        document.querySelectorAll('.vehicle-item').forEach(i => i.classList.remove('active'));
        
        // Show all markers again
        allMarkersVisible = true;
        showAllMarkers();
        
        // Clear the route and destination marker
        directionsRenderer.setDirections({routes: []});
        if (routePolyline) routePolyline.setMap(null);
        if (destinationMarker) destinationMarker.setMap(null);
        
        // Zoom to fit all markers
        zoomToFitAllMarkers();
    }

    // Show all markers on the map
    function showAllMarkers() {
        markers.forEach(marker => {
            marker.setMap(map);
        });
    }

    // Zoom to fit all markers
    function zoomToFitAllMarkers() {
        if (markers.length === 0) return;
        
        const bounds = new google.maps.LatLngBounds();
        markers.forEach(marker => {
            bounds.extend(marker.getPosition());
        });
        
        // Add some padding
        map.fitBounds(bounds);
        
        // Don't zoom in too close if only one marker
        if (markers.length === 1 && map.getZoom() > 15) {
            map.setZoom(15);
        }
    }

    // Filter vehicles based on search and status
    // function filterVehicles() {
    //     const searchTerm = $('#vehicleSearch').val().toLowerCase();
    //     const selectedStatus = $('input[name="statusFilter"]:checked').attr('id');
        
    //     $('.vehicle-item').each(function() {
    //         const $item = $(this);
    //         const name = String($item.data('name') || '').toLowerCase();
    //         const vehicleNumber = String($item.data('vehicle-number') || '').toLowerCase();
    //         const driverName = String($item.data('driver-name') || '').toLowerCase();
    //         const driverPhone = String($item.data('driver-phone') || '').toLowerCase();
    //         const status = String($item.data('status') || '');
            
    //         // Check search term
    //         const matchesSearch = name.includes(searchTerm) || 
    //                             vehicleNumber.includes(searchTerm) || 
    //                             driverName.includes(searchTerm) || 
    //                             driverPhone.includes(searchTerm);
            
    //         // Check status filter
    //         let matchesStatus = true;
    //         if (selectedStatus === 'filterOnline') {
    //             matchesStatus = status === 'Online';
    //         } else if (selectedStatus === 'filterOffline') {
    //             matchesStatus = status === 'Offline';
    //         }
            
    //         // Show/hide based on filters
    //         if (matchesSearch && matchesStatus) {
    //             $item.show();
    //         } else {
    //             $item.hide();
    //         }
    //     });
    // }

    // Set up live updates
    function setupLiveUpdates() {
        // Clear any existing interval
        clearInterval(liveUpdateInterval);
        
        // Initial update
        updateVehiclePositions();
        
        // Set up new interval for live updates
        liveUpdateInterval = setInterval(updateVehiclePositions, updateInterval);
        
        // Update UI
        document.getElementById('update-interval').textContent = updateInterval / 1000;
    }

    // Update vehicle positions from server
    async function updateVehiclePositions() {
        try {
            // Update last updated time
            document.getElementById('map-update-time').textContent = new Date().toLocaleTimeString();
            
            // Fetch real-time driver positions from your API
            const response = await fetch('/api/drivers/positions');
            const driversData = await response.json();
            
            if (!driversData || !driversData.response || !driversData.data || !driversData.data.drivers) {
                throw new Error('Invalid driver position data');
            }
    
            // Update each driver's position
            driversData.data.drivers.forEach(driver => {
                // Find the corresponding vehicle item in the DOM
                const vehicleItem = document.querySelector(`.vehicle-item[data-driver-id="${driver.driver_id}"]`);
                
                if (!vehicleItem) return;
    
                // Update the DOM element with new position data
                vehicleItem.dataset.lat = driver.latitude;
                vehicleItem.dataset.lng = driver.longitude;
                vehicleItem.dataset.status = driver.ride_mode === 1 ? 'Online' : 'Offline';
                vehicleItem.querySelector('.update-time').textContent = 'Just now';
    
                // Update the corresponding marker on the map
                const marker = markers.find(m => m.title === vehicleItem.dataset.name);
                if (marker) {
                    const newPosition = new google.maps.LatLng(
                        parseFloat(driver.latitude),
                        parseFloat(driver.longitude)
                    );
                    
                    marker.setPosition(newPosition);
                    marker.setIcon(getVehicleIcon(driver.ride_mode === 1 ? 'Online' : 'Offline'));
    
                    // If this is the currently tracked driver, update the route
                    if (vehicleItem.classList.contains('active')) {
                        currentDriverPosition = newPosition;
                        
                        // Update the route if we have destinations
                        if (deliveryMap.currentDelivery) {
                            const waypoints = deliveryMap.currentDelivery.delivery_schedule_shops
                                .filter(shop => shop.status !== 'delivered')
                                .map(shop => ({
                                    location: new google.maps.LatLng(
                                        parseFloat(shop.shop.shop_latitude),
                                        parseFloat(shop.shop.shop_longitude)
                                    ),
                                    stopover: true
                                }));
                            
                            if (waypoints.length > 0) {
                                calculateDeliveryRoute(
                                    currentDriverPosition,
                                    waypoints,
                                    deliveryMap.currentDelivery
                                );
                                
                                // await calculateSequentialRoute(
                                //     new google.maps.LatLng(positionData.data.latitude, positionData.data.longitude),
                                //     allShops
                                // );
                            }
                        }
                    }
                }
            });
    
        } catch (error) {
            console.error('Error updating vehicle positions:', error);
            // Fallback to simulated updates if API fails
            fallbackSimulatedUpdates();
        }
    }
    
    // Fallback function when API fails
    function fallbackSimulatedUpdates() {
        document.querySelectorAll('.vehicle-item[data-status="Online"]').forEach(item => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);
            
            // Small random movement for simulation
            const newLat = lat + (Math.random() * 0.001 - 0.0005);
            const newLng = lng + (Math.random() * 0.001 - 0.0005);
            
            item.dataset.lat = newLat;
            item.dataset.lng = newLng;
            item.querySelector('.update-time').textContent = 'Just now (simulated)';
            
            // Update marker
            const marker = markers.find(m => m.title === item.dataset.name);
            if (marker) {
                marker.setPosition(new google.maps.LatLng(newLat, newLng));
            }
        });
    }
    
    // Helper function to get appropriate vehicle icon
    function getVehicleIcon(status) {
        return {
            url: "<?php echo e(asset('assets/dashboard-assets/assets/images/pickup-truck.png')); ?>",
            scaledSize: new google.maps.Size(40, 40),
            opacity: status === 'Online' ? 1 : 0.6
        };
    }

    // Load Google Maps API
    function loadGoogleMaps() {
        return new Promise((resolve, reject) => {
            if (typeof google !== 'undefined' && google.maps) {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = `https://maps.mapthrust.io//maps/api/js?key=AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl&libraries=geometry,places&callback=initMap`;
            script.async = true;
            script.defer = true;
            script.onerror = reject;
            
            document.head.appendChild(script);
        });
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        loadGoogleMaps().catch(error => {
            console.error('Error loading Google Maps:', error);
            const loadingOverlay = document.querySelector('.map-loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.innerHTML = `
                    <div class="alert alert-danger">
                        <h5>Error loading map</h5>
                        <p>Failed to load Google Maps. Please check your internet connection.</p>
                    </div>
                `;
            }
        });
    });

    // Clean up when page unloads
    window.addEventListener('beforeunload', function() {
        clearInterval(liveUpdateInterval);
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/admin/tracking/index.blade.php ENDPATH**/ ?>