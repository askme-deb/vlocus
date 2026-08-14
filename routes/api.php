<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    AuthController,
    HomeController,
    VehicleController,
    DriverApiController,
    DeliveryController,
    DashboardController,
    PlanApiController,
    PaymentApiController,
    CompanyRegisterApiController,
};

use App\Http\Controllers\Admin\{
    BusStopController,
    VehicleTypeController,
};

use App\Http\Controllers\DirectionsController;
// Public API Routes (No authentication required)


Route::post('user/login', [AuthController::class, 'authenticate']);

Route::post('driver-registration', [DriverApiController::class, 'store']);
Route::post('driver/login', [DriverApiController::class, 'authenticate']);

Route::get('get-brand', [DriverApiController::class, 'get_all_brand']);
Route::get('get-brand-model/{brandId}', [DriverApiController::class, 'get_brand_model']);
Route::controller(VehicleController::class)->group(function () {
    Route::get("/get-vehicle-types",'get_vehicle_types');
});

Route::get('/plans', [PlanApiController::class, 'index']);
Route::post('/razorpay/order', [PaymentApiController::class, 'createOrder']);

Route::post('/company/register', [CompanyRegisterApiController::class, 'register']);
Route::post('/company/login', [CompanyRegisterApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/razorpay/payment-success', [PaymentApiController::class, 'paymentSuccess']);

    Route::delete('delete-user-account', [AuthController::class, 'delete_user_account']);
    

    Route::post('user/register', [AuthController::class, 'register']);
    Route::post('user/profile-update', [AuthController::class, 'profile_update']);

    Route::controller(HomeController::class)->group(function () {
        Route::get('get-all','index');
        Route::post('search-vehicle','search');
        Route::get('get-nearby-bustop','get_nearby_busstop');
        Route::get('search-cab','search_cab');
        Route::post('driver/test-one-signal','sendNotificationToDriverTest');
        
        Route::get('get-offers','get_offers');

    });


    

    
    Route::controller(VehicleController::class)->group(function () {
        Route::get('/get-vehicle-timetables','get_vehicle_timings_by_driver_or_conductur');
        Route::get('/get-journey-bus-stops/{route_id}','get_journey_bus_stops');

        Route::get('get-journey-data','get_journey_data');
        Route::get('journeys/{vehicleIdid}','index');
        Route::post('journey/create','journey_create');
        // Route::get('journey/start/{journeyId}','journey_start');
        Route::post('journey/start','journey_start');
        Route::post('journey/stoppage-update','update_journey_departure');
        Route::post('journey/end','journey_end');

        Route::get('/trip-history','trip_history');
        Route::post('/update-vehicle-location','update_vehicle_location');

    });


    
    Route::controller(DriverApiController::class)->group(function () {
        Route::post('driver/update-location','update_driver_location');
        Route::post('driver/update-ride-mode','update_ride_mode');
        Route::get('driver/get-booking-request/{requestId}','getBookingRequest');
        Route::post('driver/booking-request-accept','acceptBookingRequest');
        Route::post('driver/verify-pickup-otp','verifyPickupOtp');
        Route::get('driver/get-all-booking-request','getAllBookingRequest');
        Route::post('driver/verify-drop-otp','verifyDropOtp');
        Route::post('driver/logout','logout');
        Route::post('driver/booking-request-rejected','rejectedBookingRequest');
        Route::post('driver/update-current-location','update_current_location');
        Route::post('driver/send-sos-message','sendSOS');
        Route::get('driver/logout','logout');
        Route::get('driver/login-logs','login_logs');
    });

    Route::post('driver-update-profile', [DriverApiController::class, 'update']);
    Route::get('get-driver-data', [DriverApiController::class, 'get_driver_data']);
    Route::post('vehicle-update', [DriverApiController::class, 'vehicle_update']);
    Route::get('search-bus-stop', [DriverApiController::class, 'bus_stop_search']);
    
    
    
    
    Route::controller(DeliveryController::class)->group(function () {
        Route::get('delivery/get-delivery-details','index');
        Route::get('delivery/get-delivery-invoice-data','get_delivery_invoice_data');
        Route::post('delivery/complete-delivery','complete_delivery');
        Route::post('delivery/cancel-delivery','cancel_delivery');
        Route::post('delivery/update-delivery','update_delivery');
        Route::post('delivery/update-delivery-serials','updateSerials');
        
        Route::get('delivery/get-delivery-history','delivery_history');
        Route::post('delivery/send-new-otp','sendNewOTPToShopWoner');
        Route::get('delivery/bill-generate','genrateBill');
    });
    
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard','dashboard');
        Route::get('dashboard/filter','filter');
        Route::get('dashboard-stats','dailyStats');
    });
    
   
});
