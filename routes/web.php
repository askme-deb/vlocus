<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\{
    RoleController,
    PermissionController,
    Dashboard,
    SystemUserController,
    VehicleTypeController,
    VehicleController,
    DriverController,
    BrandController,
    ModelsController,
    ColorController,
    UsersController,
    ShopController,
    DeliveryScheduleController,
    ContactUSController,
    SettingsController,
    SOSController,
    TrackingController,
    CompanyController,
    BranchController,
    EmployeeController,
    ReportController,
    RoutePlaybackController,
    PlanController,
    DeliveryScheduleBulkController,
    ShopBulkController,
    VehicleBulkController,
    DriverBulkController,
};
use App\Http\Controllers\{
    HomeController,
    UserRegisterController,
    UserLoginController,
    UserController,
    WalletController,
    BookingController,
    HomeFAQController,
};

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/vehicles/realtime', function (Request $request) {
    return response()->json(generateVehicleData());
});



 Route::get('/order/{delivery_id}/{shop_id}/track', [TrackingController::class, 'order_tracking'])->name('order.tracking');
 Route::get('/driver/{id}/location', [TrackingController::class, 'get_driver_location'])->name('driver.location');

 Route::get('/track-delivery', [DeliveryScheduleController::class, 'track_delivery'])->name('track.delivery');
 Route::get('/invoice/{deliveryId}/{shop_id}', [DeliveryScheduleController::class, 'deliveryInvoice'])->name('delivery.invoice');
 Route::get('/get-driver-location', [DeliveryScheduleController::class, 'get_driver_location'])->name('get.driver.locaion');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard',[Dashboard::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->group(function (){

    Route::post('/update-delivery',[Dashboard::class,'update_delivery'])->middleware(['auth', 'verified'])->name('delivery.update');
    Route::post('/resend-delivery-otp',[Dashboard::class,'resend_delivery_otp'])->middleware(['auth', 'verified'])->name('delivery.resend-otp');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


        Route::controller(RoleController::class)->group(function () {
            Route::prefix('role')->group(function () {
                Route::get("/",'roles')->name('roles');
                Route::post("/create-role",'create_role')->name('role.create');
                Route::post("{roleId?}/update-role",'update_role')->name('role.update');
                Route::delete("/{roleId}/destroy-role",'destroy_role')->name('role.destroy');
                Route::get("/{roleId}/add-permission-to-role",'addPermissionToRole')->name('role.addPermissionToRole');
                Route::post("/{roleId}/give-permissions",'givePermissionToRole')->name('role.give-permissions');
            });
        });

        Route::controller(PermissionController::class)->group(function () {
            Route::prefix('permission')->group(function () {
                Route::get("/",'permission')->name('permission');
                Route::post("/create-permission",'create_permission')->name('permission.create');
                Route::post("{permissionId?}/update-permission",'update_permission')->name('permission.update');
                Route::delete("/{permissionId}/destroy-permission",'destroy_permission')->name('permission.destroy');
            });
        });


        Route::resource('system-user', SystemUserController::class);

        Route::resource('shop', ShopController::class);

        Route::get('shop/template/download', [ShopBulkController::class, 'downloadTemplate'])
            ->name('shop.template.download');

        Route::post('shop/bulk-upload', [ShopBulkController::class, 'bulkUpload'])
            ->name('shop.bulk.upload');


        Route::controller(VehicleTypeController::class)->group(function () {
            Route::get("/vehicle-types",'index')->name('vehicle-type.index');
            Route::prefix('vehicle-type')->name('vehicle-type.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');

            });
        });


        Route::controller(VehicleController::class)->group(function () {
            Route::get("/vehicles",'index')->name('vehicle.index');
            Route::prefix('vehicle')->name('vehicle.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::post("/storefrommodal",'storeFromModal')->name('storeFromModal');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');
                Route::post("/verify-rc",'verifyRc')->name('verifyRc');
                Route::get('/get-models/{brandId}','getModels')->name('get-models');
                Route::get("booking-list/{id}",'booking_list')->name('booking_list');
                Route::get("journey-list/{id}",'journey')->name('journey');
                Route::post("journey-store",'journey_store')->name('journey.store');
                Route::get("view-map",'journey_view_map')->name('view.map');
                Route::delete("/journey-delete/{journeyId}",'journey_delete')->name('journey.delete');
                Route::get("journey-start/{journeyId}",'journey_start')->name('journey.start');
            });
        });

        Route::get('vehicle/template/download', [VehicleBulkController::class, 'downloadTemplate'])
            ->name('vehicle.template.download');

        Route::post('vehicle/bulk-upload', [VehicleBulkController::class, 'bulkUpload'])
            ->name('vehicle.bulk.upload');


        Route::controller(DriverController::class)->group(function () {
            Route::get("/drivers",'index')->name('driver.index');
            Route::prefix('driver')->name('driver.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::post("/storefrommodal",'storeFromModal')->name('storeFromModal');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');
                Route::post("/add-company",'add_company')->name('add_company');
                Route::post("/verify-license",'verifyDrivingLicense')->name('verifyLicense');
                Route::post("/verify-pan",'verifyPan')->name('verifyPan');
                Route::post("/aadhaar/send-otp",'sendAadhaarOtp')->name('aadhaarSendOtp');
                Route::post("/aadhaar/verify-otp",'verifyAadhaarOtp')->name('aadhaarVerifyOtp');
            });
        });

        Route::get('driver/template/download', [DriverBulkController::class, 'downloadTemplate'])
            ->name('driver.template.download');

        Route::post('driver/bulk-upload', [DriverBulkController::class, 'bulkUpload'])
            ->name('driver.bulk.upload');

        Route::controller(CompanyController::class)->group(function () {
            Route::get("/companys",'index')->name('company.index');
            Route::prefix('company')->name('company.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');
                Route::post("/verify-pan",'verifyPan')->name('verifyPan');
                Route::post("/aadhaar/send-otp",'sendAadhaarOtp')->name('aadhaarSendOtp');
                Route::post("/aadhaar/verify-otp",'verifyAadhaarOtp')->name('aadhaarVerifyOtp');

            });
        });

        Route::controller(BranchController::class)->group(function () {
            Route::get("/branchs",'index')->name('branch.index');
            Route::prefix('branch')->name('branch.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');
                Route::post("/verify-pan",'verifyPan')->name('verifyPan');
                Route::post("/aadhaar/send-otp",'sendAadhaarOtp')->name('aadhaarSendOtp');
                Route::post("/aadhaar/verify-otp",'verifyAadhaarOtp')->name('aadhaarVerifyOtp');

            });
        });

        Route::controller(UsersController::class)->group(function () {
            Route::prefix('users')->name('user.')->group(function () {
                Route::get("/",'index')->name('index');
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("/{id}/show",'show')->name('show');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('destroy');
            });
        });

        Route::controller(EmployeeController::class)->group(function () {
            Route::prefix('employees')->name('employee.')->group(function () {
                Route::get("/",'index')->name('index');
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("/{id}/show",'show')->name('show');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');
                Route::post("/verify-pan",'verifyPan')->name('verifyPan');
                Route::post("/aadhaar/send-otp",'sendAadhaarOtp')->name('aadhaarSendOtp');
                Route::post("/aadhaar/verify-otp",'verifyAadhaarOtp')->name('aadhaarVerifyOtp');
            });
        });

        Route::controller(BrandController::class)->group(function () {
            Route::get("/brands",'index')->name('brand.index');
            Route::prefix('brand')->name('brand.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');

            });
        });
        Route::controller(BrandController::class)->group(function () {
            Route::get("/brands",'index')->name('brand.index');
            Route::prefix('brand')->name('brand.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');

            });
        });
        Route::controller(ModelsController::class)->group(function () {
            Route::get("/models",'index')->name('model.index');
            Route::prefix('model')->name('model.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');

            });
        });
        Route::controller(ColorController::class)->group(function () {
            Route::get("/colors",'index')->name('color.index');
            Route::prefix('color')->name('color.')->group(function () {
                Route::get("/create",'create')->name('create');
                Route::post("/store",'store')->name('store');
                Route::get("edit/{id}",'edit')->name('edit');
                Route::post("/update",'update')->name('update');
                Route::delete("/delete/{routeId}",'destroy')->name('delete');

            });
        });






        // Route::resource('delivery-schedule', DeliveryScheduleController::class);

        Route::prefix('delivery-schedule')->controller(DeliveryScheduleController::class)->group(function () {
            Route::get('/search', 'search')->name('shop.search');
            Route::get('/', 'index')->name('delivery-schedule.index');
            Route::get('/create', 'create')->name('delivery-schedule.create');
            Route::post('/', 'store')->name('delivery-schedule.store');
            Route::get('/{delivery_schedule}', 'show')->name('delivery-schedule.show');
            Route::get('/{delivery_schedule}/edit', 'edit')->name('delivery-schedule.edit');
            Route::put('/{delivery_schedule}', 'update')->name('delivery-schedule.update');
            Route::delete('/{delivery_schedule}', 'destroy')->name('delivery-schedule.destroy');

            Route::post('create-shop', 'add_shop')->name('delivery-schedule.add_shop');
            Route::post('shop/{delivery_schedule_shop}/reject', 'reject_shop')->name('delivery-schedule.shop.reject');
        });

        Route::get('delivery-schedule/template/download', [DeliveryScheduleBulkController::class, 'downloadTemplate'])
            ->name('delivery-schedule.template.download');

        Route::post('delivery-schedule/bulk-upload', [DeliveryScheduleBulkController::class, 'bulkUpload'])
            ->name('delivery-schedule.bulk.upload');

        Route::prefix('settings')->controller(SettingsController::class)->group(function () {
            Route::get('/', 'create')->name('settings');
            Route::post('/settings-update', 'update')->name('settings.update');

        });

        Route::controller(SOSController::class)->group(function () {
            Route::get("/sos_alerts",'index')->name('sos_alert.index');
            Route::delete("/sos_alerts-delete/{id}",'delete')->name('sos_alert.delete');
        });

        Route::controller(TrackingController::class)->group(function () {
            Route::get("/tracking",'index')->name('tracking.index');
        });

        Route::prefix('reports')->group(function () {
            Route::get('/trip-summary', [ReportController::class, 'tripSummary'])->name('reports.tripSummary');
            Route::get('/route-history', [ReportController::class, 'routeHistory'])->name('reports.routeHistory');
            Route::get('/run-idle', [ReportController::class, 'runIdle'])->name('reports.runIdle');
            Route::get('/distance', [ReportController::class, 'distance'])->name('reports.distance');
            Route::get('/geofence', [ReportController::class, 'geofence'])->name('reports.geofence');
            Route::get('/overstay', [ReportController::class, 'overstay'])->name('reports.overstay');
            Route::get('/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
            Route::get('/login-logout', [ReportController::class, 'loginLogout'])->name('reports.loginLogout');
            Route::get('/login-time', [ReportController::class, 'loginTime'])->name('reports.loginTime');
            Route::get('/emergency-sos', [ReportController::class, 'emergencySos'])->name('reports.emergencySos');
            Route::get('/task-report', [ReportController::class, 'taskReport'])->name('reports.taskReport');
            Route::get('/dispatch-details', [ReportController::class, 'dispatchDetails'])->name('reports.dispatchDetails');
        });


        Route::controller(ContactUSController::class)->group(function () {
            Route::get("/contact-us",'index')->name('contact-us.index');
            Route::prefix('contact-us')->name('contact-us.')->group(function () {
                // Route::get("/create",'create')->name('create');
                // Route::get("edit/{id}",'edit')->name('edit');
                // Route::post("/update",'update')->name('update');
                Route::delete("/{id}",'destroy')->name('destroy');

            });
        });

        Route::get('/route-playback', [RoutePlaybackController::class, 'index'])->name('route.playback');
        Route::post('/route-playback/data', [RoutePlaybackController::class, 'getRouteData'])->name('route.playback.data');
        // Route::match(['get', 'post'], '/route-playback/data', [RoutePlaybackController::class, 'getRouteData'])->name('route.playback.data');


        Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

        // Plan CRUD
        Route::post('/plans/store', [PlanController::class, 'storePlan'])->name('plans.store');
        Route::get('/plans/{id}/edit', [PlanController::class, 'editPlan'])->name('plans.edit');
        Route::post('/plans/{id}/update', [PlanController::class, 'updatePlan'])->name('plans.update');
        Route::delete('/plans/{id}', [PlanController::class, 'destroyPlan'])->name('plans.destroy');

        // Feature CRUD
        Route::post('/features/store', [PlanController::class, 'storeFeature'])->name('features.store');
        Route::get('/features/{id}/edit', [PlanController::class, 'editFeature'])->name('features.edit');
        Route::post('/features/{id}/update', [PlanController::class, 'updateFeature'])->name('features.update');
        Route::delete('/features/{id}', [PlanController::class, 'destroyFeature'])->name('features.destroy');

        // Plan-Feature mapping
        Route::post('/plans/update-mapping', [PlanController::class, 'updateMapping'])->name('plans.updateMapping');

    });
});

Route::middleware(['auth', 'user-access:User'])->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::get("/profile",'profile')->name('front.user.profile');
        Route::post("/profile-update",'profile_update')->name('front.user.profile.update');

    });
    Route::controller(WalletController::class)->group(function () {
        Route::get('/wallet/get-transactions','all_transaction_of_user')->name('wallet.transactions');
        Route::post('/wallet/create-order', 'createOrder')->name('front.wallet.create_order');
        Route::post('/wallet/recharge', 'store')->name('front.wallet.recharge');
    });

});
// Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/faq', [HomeFAQController::class, 'index'])->name('home.faq');

Route::post('/contact-us-store', [ContactUSController::class, 'store'])->name('web.contact-us.store');
Route::get('/about', function () {
    return view('frontend.about');
})->name('home.about');

Route::get('/help', function () {
    return view('frontend.help');
})->name('home.help');

Route::get('/blog', function () {
    return view('frontend.blog');
})->name('home.blog');

Route::get('/bookings', function () {
    return view('frontend.bookings');
})->name('home.bookings');


Route::post('/send-otp', [UserLoginController::class, 'sendOTP'])->name('home.user.send.otp');
Route::post('/verify-otp', [UserLoginController::class, 'verifyOTP'])->name('home.user.verify.otp');
Route::get('/logout', [UserLoginController::class, 'logout'])->name('home.user.logout');


Route::get('/bus-books/search', [BookingController::class, 'index'])->name('book-buss');
Route::get('/vehicles', [BookingController::class, 'vehicle_list'])->name('home.vehicle-search');


Route::get('/vehicles/direct-search', [BookingController::class, 'directVehicleSearch'])->name('vehicles.direct-search');



Route::get('/seat-booking/{vehicleId}', [BookingController::class, 'seat_booking'])->name('home.seat.booking');
Route::post('/proceed-to-booking', [BookingController::class, 'proceed_booking'])->name('home.proceed.booking');
Route::get('/print-ticket/{bookingId}', [BookingController::class, 'print_ticket'])->name('home.print.ticket');
Route::get('/term-condition', [HomeController::class, 'term_condition'])->name('home.term_condition');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('home.privacy');
Route::controller(HomeController::class)->group(function () {
    Route::get('/company-registrations','company_registration')->name('home.company_registration');
    Route::post('company-registrations', 'company_registration_submit')->name('front.company_registration.submit');

});
Route::post('/get-available-bus-stop', [BookingController::class, 'getAvailableBusStop'])->name('home.getAvailableBusStop');


Route::get('/storage-link', function () {
    Artisan::call('storage:link');

    return 'Storage link created successfully!';
});


Route::get('/clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'Application cache cleared successfully!';
});

Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear'); // clears cache, config, route, view
    Artisan::call('config:cache');   // rebuild config cache

    return 'Optimization cleared & config cached!';
});


require __DIR__.'/auth.php';
require __DIR__.'/web_api.php';
