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
    };


    Route::controller(DeliveryController::class)->group(function () {
        Route::get('delivery/get-delivery-details','index');
    });
    
    Route::controller(DriverApiController::class)->group(function () {
        Route::get('driver/position/{driver_id?}','get_driver_current_location');
    });