@extends('frontend.layouts.app')

@section('title', $page->meta_title ?? 'Home')
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')

@section('content')
    <!--  <section class="top_banner">
        <div class="container">
            <div class="row mx-2">
                <div class="col-lg-6 col-md-6">
                    <h1>Quality Car Rental</h1>
                    <h1>In The Town,</h1>
                    <h2 class="d-flex">Safety
                        <button class="alignment rounded-circle">
                            <img src="{{ asset('assets/frontend_assets/img/home/img18.png') }}" alt="" srcset=""
                                class="top_banner_arrow">
                        </button>
                    </h2>
                </div>
                <div class="col-lg-6 col-md-6">
                    <img src="{{ asset('assets/frontend_assets/img/home/img1.png') }}" alt="" srcset=""
                        class="top_banner_img">
                </div>
            </div>
        </div>
    </section> -->
    {{-- <section class="topBanner py-5 px-3 ">
        <div class="container">
            <form action="{{ route('book-buss') }}" id="index-booking-form" method="GET" class="booking-form">
                <div class="row align-items-center">
                    <!-- From Location -->
                    <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                        <label for="from">From</label>
                        <input type="text" id="from" name="from" placeholder="Enter departure city">
                    </div>

                    <!-- Exchange Icon -->
                    <div class="col-lg-1 col-md-1 col-sm-12 text-center my-2 topBannerArrow">
                        <span class="alignment trans"><i class="fas fa-exchange-alt"></i></span>
                    </div>

                    <!-- To Location -->
                    <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                        <label for="to">To</label>
                        <input type="text" id="to" name="to" placeholder="Enter destination">
                    </div>

                    <!-- Date Picker -->
                    <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Search Button -->
                    <div class="col-lg-2 col-md-2 col-sm-12 mt-3 alignment">
                        <button
                            @auth type="submit" @else type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" @endauth
                            class="btn btn_style">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </section> --}}

    {{-- <section class="topBanner top_warp py-5 px-3 home_top_warp">
        <div class="container">
            <div class="row booking-form">
                <div class="col-lg-6 track_shipment" style="position: relative;">
                    <h1 class="mb-3">From Here to Anywhere Seamless Rides</h1>
                    <p class="mb-3">Enter your starting point, destination, and date to find the best routes.</p>
                    <form action="{{ route('book-buss') }}" id="index-booking-form" method="GET" class="">
                        <div class="align-items-center" style="position:relative;">
                            <!-- From Location -->

                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-4 position-relative">
                                <i class="fa fa-circle position-absolute"
                                    style="left: 10px; top: 50%; transform: translateY(-50%); color: #555;"></i>
                                <input type="text" id="from" name="from" placeholder="Enter Pickup location"
                                    style="padding-left: 40px; width: 100%; border-radius:10px;">
                            </div>
                            <!-- Exchange Icon -->
                            <!--<div class="col-lg-1 col-md-1 col-sm-12 text-center my-2 topBannerArrow">
                                <span class="alignment trans"><i class="fas fa-exchange-alt"></i></span>
                            </div>-->
                               
                            <!-- To Location -->
                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-4 position-relative">
                                <i class="fa fa-stop-circle position-absolute"
                                    style="left: 10px; top: 50%; transform: translateY(-50%); color: #555;"></i>
                                <input type="text" id="to" name="to" placeholder="Enter dropoff location"
                                    style="padding-left: 40px; width: 100%;border-radius:10px;">
                            </div>

                            <div class="d-flex flex-column css-jkbjYp">

                            </div>


                            <div class="d-flex col-lg-12 col-md-12 col-sm-12 ">
                                <!-- Date Picker -->
                                <div class="col-lg-8 col-md-8 col-sm-8 d-flex mb-4 flex-column">

                                    <input class="flatpickr-input flatpickr-mobile" tabindex="1" type="text" name="date" id="date"
                                        placeholder="Select Date" value="2025-03-25" style="border-radius:10px;">
                                </div>
                                <!--<div class="col-lg-8 col-md-8 col-sm-8 d-flex flex-column">
                                    <input id="datePicker" type="text" name="date" placeholder="Select Date" value="2025-03-25" style="border-radius:10px;">
                                </div> -->

                                <!-- Search Button -->
                                <div class="col-lg-4 col-md-4 col-sm-4 mx-2 alignment1">
                                    <button
                                        @auth type="submit" @else type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" @endauth
                                        class="btn_style1 mx-1" style="border-radius:10px;">Search Now</button>
                                </div>
                            </div>
                              
                    
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('assets/frontend_assets/img/home/img05.png') }}" alt="" srcset=""
                        style="width: 100%;border-radius: 15px">
                </div>
            </div>

        </div>
    </section> --}}

    <section class="topBanner py-5 px-3 search_warp">
        <div class="container-fluid">
            <div class="row booking-form">
                <div class="col-lg-12 fild_warp">
                    <div class="fild_search">
                        <h1 class="mb-3">From Here to Anywhere</h1>
                        <p>Enter your starting point, destination, and date to find the best routes.</p>
                      
                        
                          
                        <div class="tab_warp">
                       <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
      <img src="{{ asset('assets/frontend_assets/img/home/cab1.png') }}" alt="">
     </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">
         <img src="{{ asset('assets/frontend_assets/img/home/bus1.png') }}" alt="">
     </button>
  </li>
  
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">
         <img src="{{ asset('assets/frontend_assets/img/home/auto1.png') }}" alt="">
    </button>
  </li>

</ul>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
       <form action="{{ route('book-buss') }}" id="index-booking-form" method="GET" class="">
                            <div class="align-items-center">
                                <!-- From Location -->

                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-2 position-relative">
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
                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-2 position-relative">
                                    <i class="fa fa-stop-circle position-absolute"
                                        style="left: 10px; top: 50%; transform: translateY(-50%); color: #555;"></i>
                                    <input type="text" id="to" name="to"  value="{{ $to ?? '' }}" placeholder="Enter dropoff location"
                                        style="padding-left: 40px; width: 100%;border-radius:10px;">
                                </div>

                                <div class="d-flex flex-column css-jkbjYp">

                                </div>


                                <div class="d-flex col-lg-12 col-md-12 col-sm-12 ">
                                    <!-- Date Picker -->
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-8 d-flex flex-column">

                                        <input class="flatpickr-input flatpickr-mobile flatpickr-mobile" tabindex="1" type="text" id="date_date" placeholder="Select Date" name="date"value="2025-03-25" style="border-radius:10px;">
                                    </div>
                                    {{-- <div class="col-lg-8 col-md-8 col-sm-8 col-8 d-flex flex-column">
                                        <input id="datePicker" type="text" name="date" placeholder="Select Date" value="2025-03-25" style="border-radius:10px;">
                                    </div> --}}
                                    
                                    <!-- Search Button -->
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4 alignment">
                                        <button
                                            @auth type="submit" @else type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" @endauth
                                            class="btn btn_style mx-1" style="border-radius:10px;">Search Now</button>
                                    </div>
                                </div>
                            </div>
                        </form>
  </div>
  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
           <form action="{{ route('book-buss') }}" id="index-booking-form" method="GET" class="">
                            <div class="align-items-center">
                                <!-- From Location -->

                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-2 position-relative">
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
                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-2 position-relative">
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
                                    {{-- <div class="col-lg-8 col-md-8 col-sm-8 col-8 d-flex flex-column">
                                        <input id="datePicker" type="text" name="date" placeholder="Select Date" value="2025-03-25" style="border-radius:10px;">
                                    </div> --}}
                                    
                                    <!-- Search Button -->
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4 alignment ">
                                        <button
                                            @auth type="submit" @else type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" @endauth
                                            class="btn btn_style mx-1" style="border-radius:10px;">Search Now</button>
                                    </div>
                                </div>
                            </div>
                        </form>
  </div>
  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
         <form action="{{ route('book-buss') }}" id="index-booking-form" method="GET" class="">
                            <div class="align-items-center">
                                <!-- From Location -->

                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-2 position-relative">
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
                                <div class="col-lg-12 col-md-12 col-sm-12 d-flex mb-2 position-relative">
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
                                    {{-- <div class="col-lg-8 col-md-8 col-sm-8 col-8 d-flex flex-column">
                                        <input id="datePicker" type="text" name="date" placeholder="Select Date" value="2025-03-25" style="border-radius:10px;">
                                    </div> --}}
                                    
                                    <!-- Search Button -->
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4 alignment ">
                                        <button 
                                            @auth type="submit" @else type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" @endauth
                                            class="btn btn_style mx-1" style="border-radius:10px;">Search Now</button>
                                    </div>
                                </div>
                            </div>
                        </form>
  </div>
 

 
 
</div>
</div>
                        
                        
                        
                        <div class="viewBusesBox">
                            <div class="container">
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    
                  
                    
                    {{-- <img src="{{ asset('assets/frontend_assets/img/home/map.jpg') }}" alt="" srcset="" style="width: 100%;border-radius: 15px"> --}}
                    <div id="map" style="width: 100%; height: 400px; border-radius: 15px;"></div>
                </div>
            </div>
            
        </div>
        
        <div class="container">
            <div class="row mx-2">
        
        
        <div class="fild_search2" style="display: none">
                         
                        <div id="result-container"></div>
                        {{-- <div class="accordion-header mb-4 mt-0 p-1">
                            <div class="row">
                                <div class="col-lg-3">
                                    <h5>
                                        Howrah -
                                        Dum Dum
                                        Via Shyambazar
                                    </h5>
                                </div>
                                <div class="col-lg-3">
                                    <h5 class="departur-time">08:00 PM</h5>
                                    <p>From: Howrah</p>
                                </div>
                                <div class="col-lg-3">
                                    <h5>00h 59m</h5>
                                </div>
                                <div class="col-lg-3">
                                    <h5>08:59 PM</h5>
                                    <p>To: Dum Dum</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn_style view-seats-btn" type="button" data-layout-id="1" data-bus-id="1" data-journey-id="" data-timetable-id="5" data-vehicle-id="1" data-booked-seats="[]" data-partially-booked-seats="[]" data-bs-toggle="collapse" data-bs-target="#collapse1-5" aria-expanded="true" aria-controls="collapse1-5">
                                    VIEW SEATS
                                </button>
                            </div>

                            <div id="collapse1-5" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
                                <div class="accordion-body p-0 my-3">
                                    <div class="row p-5">
                                        <div class="d-flex justify-content-between">
                                            <h5>Select Your Seat</h5>
                                            <button type="button" class="btn btn-danger close-seats-btn" data-bs-toggle="collapse" data-bs-target="#collapse1-5">
                                                CLOSE
                                            </button>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <div id="seatLayoutContainer1-5" class="d-flex flex-wrap justify-content-center gap-2"></div>
                                        </div>
                                        <div class="col-md-9 mt-2">
                                            <div class="card">
                                                <div class="card-header how_works">
                                                    Partially Booked Seats &amp; Available From
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted">No partially booked seats available.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    
                    </div>
        
        </div></div>
        
    </section>

    <section class="track_shipment">
        <div class="container">
            <div class="row mx-2">
                <div class="col-lg-5 col-md-5">
                    <img src="{{ asset('assets/frontend_assets/img/home/img17.png') }}" alt="" srcset=""
                        class="track_shipment_img">
                </div>
                <div class="col-lg-7 col-md-7 alignment">
                    <div class=" track_shipment_padding">
                        <h1>Track your shipment through the application, At Any Time! </h1>
                        <p>No more uncertainty or endless waiting! Our user-friendly interface allows you to check
                            the status of your shipment anytime, from anywhere. Receive instant notifications about
                            dispatch, transit updates, and estimated delivery times, so you’re always in control.
                            Stay ahead and experience hassle-free shipping with our reliable tracking system today!
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>
    {{-- <section class="how_works py-5">
        <div class="container">
            <h1 class="text-center pb-3">How It <b>Works</b></h1>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 d-flex flex-column shadow-sm">
                        <img src="{{ asset('assets/frontend_assets/img/home/img2.png') }}"
                            class="card-img-top img-fluid fixed-img mx-auto" alt="Submit Request">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Submit Request For Shipment</h5>
                            <p class="card-text text-center">Some quick example text to build on the card title and
                                make up the bulk of the card's content.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 d-flex flex-column shadow-sm">
                        <img src="{{ asset('assets/frontend_assets/img/home/img3.png') }}"
                            class="card-img-top img-fluid fixed-img mx-auto" alt="Submit Request">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Track Your Shipment In-Real Time</h5>
                            <p class="card-text text-center">Some quick example text to build on the card title and
                                make up the bulk of the card's content.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 d-flex flex-column shadow-sm">
                        <img src="{{ asset('assets/frontend_assets/img/home/img4.png') }}"
                            class="card-img-top img-fluid fixed-img mx-auto" alt="Submit Request">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">Package Delivered To Your Doorstep</h5>
                            <p class="card-text text-center">Some quick example text to build on the card title and
                                make up the bulk of the card's content.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="home_points">
        <div class="container">
            <div class="row mx-2">
                <div class="col-lg-6 col-md-12 py-5">
                    <div class="d-flex gfrs">
                        <div class="col-lg-3 col-md-3 me-2">
                            <img src="{{ asset('assets/frontend_assets/img/home/img5.png') }}" alt=""
                                srcset="" class="home_points_icon mx-auto d-block">
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <h2>Virtual Geo Fence</h2>
                            <p>A Virtual Geo-Fence is a digitally defined boundary that uses GPS, RFID, Wi-Fi, or
                                cellular data to trigger specific actions when a device enters or exits a designated
                                area.</p>
                        </div>
                    </div>
                    <div class="d-flex gfrs">
                        <div class="col-lg-3 col-md-3 me-2">
                            <img src="{{ asset('assets/frontend_assets/img/home/img6.png') }}" alt=""
                                srcset="" class="home_points_icon mx-auto d-block">
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <h2>Digital Proof of delivery</h2>
                            <p>A Virtual Geo-Fence is a digitally defined boundary that uses GPS, RFID, Wi-Fi, or
                                cellular data to trigger specific actions when a device enters or exits a designated
                                area.</p>
                        </div>
                    </div>
                    <div class="d-flex gfrs">
                        <div class="col-lg-3 col-md-3 me-2">
                            <img src="{{ asset('assets/frontend_assets/img/home/img7.png') }}" alt=""
                                srcset="" class="home_points_icon mx-auto d-block">
                        </div>
                        <div class="col-lg-9 col-lg-9">
                            <h2>Route Optimization for Multiple Deliveries </h2>
                            <p>A Virtual Geo-Fence is a digitally defined boundary that uses GPS, RFID, Wi-Fi, or
                                cellular data to trigger specific actions when a device enters or exits a designated
                                area.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 py-5">
                    <img src="{{ asset('assets/frontend_assets/img/home/img8.png') }}" alt="" srcset=""
                        class="home_points_img">
                </div>
            </div>
        </div>
    </section>
    <!--<section class="home_solution">
        <div class="container">
            <div class="row mx-3 pb-3">
                <h1>Solution For Every<b> Business Need</b></h1>
                <p>Whatever you deliver we will help you improve your delivery wit</p>
            </div>
            <div class="row mx-4">
                <div class="col-lg-12 col-md-12 d-flex ps-0">
                    <div class="col-lg-4 d-flex">
                        <div class="solution_img">
                            <img src="{{ asset('assets/frontend_assets/img/home/img9.png') }}" alt=""
                                srcset="" class="mt-3 mx-auto d-block">
                        </div>
                        <p>Moovers & packers</p>
                    </div>
                    <div class="col-lg-4 col-md-4 d-flex">
                        <div class="solution_img">
                            <img src="{{ asset('assets/frontend_assets/img/home/img10.png') }}" alt=""
                                srcset="" class="mt-3 mx-auto d-block">
                        </div>
                        <p>Food Delivery </p>
                    </div>
                    <div class="col-lg-4 col-md-4 d-flex">
                        <div class="solution_img">
                            <img src="{{ asset('assets/frontend_assets/img/home/img11.png') }}" alt=""
                                srcset="" class="mt-3 mx-auto d-block">
                        </div>
                        <p>E-Commerce</p>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 d-flex ps-0">
                    <div class="col-lg-4 col-md-4 d-flex">
                        <div class="solution_img">
                            <img src="{{ asset('assets/frontend_assets/img/home/img12.png') }}" alt=""
                                srcset="" class="mt-3 mx-auto d-block">
                        </div>
                        <p>Pharma</p>
                    </div>
                    <div class="col-lg-4 col-md-4 d-flex">
                        <div class="solution_img">
                            <img src="{{ asset('assets/frontend_assets/img/home/img13.png') }}" alt=""
                                srcset="" class="mt-3 mx-auto d-block">
                        </div>
                        <p>E-Grocery</p>
                    </div>
                    <div class="col-lg-4 col-md-4 d-flex">
                        <div class="solution_img">
                            <img src="{{ asset('assets/frontend_assets/img/home/img14.png') }}" alt=""
                                srcset="" class="mt-3 mx-auto d-block">
                        </div>
                        <p>Retail</p>
                    </div>
                </div>

            </div>
        </div>
    </section> --> 
    <section class="testimonial">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <img src="{{ asset('assets/frontend_assets/img/home/img14.png') }}" class="img-fluid"
                        alt="">
                </div>

                <div class="col-lg-8 col-md-8 alignment">
                    <div class="mx-3 pb-3 d-flex justify-content-between align-items-center testimonial_header">
                        <h1>What Our <b>Customers Say</b></h1>


                    </div>
                    <!-- Slick Carousel Wrapper -->
                    <div class="testimonial-slider">
                        <!-- Testimonial Item 1 -->
                        <div class="testimonial-item col-lg-12 col-md-12">
                            <div class="d-flex">
                                <img src="{{ asset('assets/frontend_assets/img/home/img15.png') }}" class="me-3"
                                    alt="">
                                <div class="alignment">
                                    <h4>John Doe</h4>
                                    <p class="text-muted">Customer</p>
                                </div>
                            </div>
                            <p>Safety is my top priority when traveling, and this bus agency ensures
                                it. The drivers are well-trained, and the buses are well-maintained.
                                I appreciate the GPS tracking feature, which keeps my family
                                informed about my journey.</p>
                        </div>

                        <!-- Testimonial Item 2 -->
                        <div class="testimonial-item col-lg-12 col-md-12">
                            <div class="d-flex">
                                <img src="{{ asset('assets/frontend_assets/img/home/img15.png') }}" class="me-3"
                                    alt="">
                                <div class="alignment">
                                    <h4>Jane Smith</h4>
                                    <p class="text-muted">Customer</p>
                                </div>
                            </div>
                            <p>Safety is my top priority when traveling, and this bus agency ensures
                                it. The drivers are well-trained, and the buses are well-maintained.
                                I appreciate the GPS tracking feature, which keeps my family
                                informed about my journey.</p>
                        </div>

                        <!-- Testimonial Item 3 -->
                        <div class="testimonial-item col-lg-12 col-md-12">
                            <div class="d-flex">
                                <img src="{{ asset('assets/frontend_assets/img/home/img15.png') }}" class="me-3"
                                    alt="">
                                <div class="alignment">
                                    <h4>Michael Lee</h4>
                                    <p class="text-muted">Customer</p>
                                </div>
                            </div>
                            <p>Safety is my top priority when traveling, and this bus agency ensures
                                it. The drivers are well-trained, and the buses are well-maintained.
                                I appreciate the GPS tracking feature, which keeps my family
                                informed about my journey.</p>
                        </div>

                        <!-- Testimonial Item 4 -->
                        <div class="testimonial-item col-lg-12 col-md-12">
                            <div class="d-flex">
                                <img src="{{ asset('assets/frontend_assets/img/home/img15.png') }}" class="me-3"
                                    alt="">
                                <div class="alignment">
                                    <h4>Alice Brown</h4>
                                    <p class="text-muted">Customer</p>
                                </div>
                            </div>
                            <p>Safety is my top priority when traveling, and this bus agency ensures
                                it. The drivers are well-trained, and the buses are well-maintained.
                                I appreciate the GPS tracking feature, which keeps my family
                                informed about my journey.</p>
                        </div>

                        <!-- Testimonial Item 5 -->
                        <div class="testimonial-item col-lg-12 col-md-12">
                            <div class="d-flex">
                                <img src="{{ asset('assets/frontend_assets/img/home/img15.png') }}" class="me-3"
                                    alt="">
                                <div class="alignment">
                                    <h4>Robert White</h4>
                                    <p class="text-muted">Customer</p>
                                </div>
                            </div>
                            <p>Safety is my top priority when traveling, and this bus agency ensures
                                it. The drivers are well-trained, and the buses are well-maintained.
                                I appreciate the GPS tracking feature, which keeps my family
                                informed about my journey.</p>
                        </div>
                        <!-- Testimonial Item 6 -->
                        <div class="testimonial-item col-lg-12 col-md-12">
                            <div class="d-flex">
                                <img src="{{ asset('assets/frontend_assets/img/home/img15.png') }}" class="me-3"
                                    alt="">
                                <div class="alignment">
                                    <h4>Robert White</h4>
                                    <p class="text-muted">Customer</p>
                                </div>
                            </div>
                            <p>Safety is my top priority when traveling, and this bus agency ensures
                                it. The drivers are well-trained, and the buses are well-maintained.
                                I appreciate the GPS tracking feature, which keeps my family
                                informed about my journey.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($faqs->isNotEmpty())
    <section class="asked_question">
        <div class="container">
            <div class="row text-center mx-4 pb-3">
                <h1>Frequently <b>Asked Questions</b></h1>
            </div>
            <div class="row mx-4 pt-3 margin-zero">
                <div class="accordion" id="accordionExample">
                    @foreach($faqs as $faq)
                    <div class="">
                        <h2 class="accordion-header" id="heading{{ $loop->iteration }}">
                            <button class="accordion-button @if($loop->iteration > 1) collapsed @endif" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="true" aria-controls="collapse{{ $loop->iteration }}">
                                <span class="accordion_number">{{ $loop->iteration }}</span> <span>{{ $faq->question }}</span>

                            </button>
                        </h2>
                        <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse @if($loop->iteration == 1) show @endif" aria-labelledby="heading{{ $loop->iteration }}"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">{!! $faq->answer !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <section class="download_app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-offset-3 col-lg-9 col-md-9">
                    <div class="download_app_margin">
                        <h1>Download<b> The App</b></h1>
                    </div>
                    <div class="d-flex download_app_margin">
                        <div class="col-lg-2 col-sm-12 col-12 col-md-2 alignment me-2"><img
                                src="{{ asset('assets/frontend_assets/img/home/qrcode.png') }}" alt=""
                                srcset="" class="download_app_qr"></div>
                        <div class="col-lg-10 col-sm-10 col-md-10 download_app_m alignment">
                            <h5>Download the Delivery app</h5>
                            <p>Scan to download</p>
                            <div class="d-flex">
                                <button><img src="{{ asset('assets/frontend_assets/img/home/google_play.png') }}"
                                        alt="" srcset="" class="download_app_btn"></button>
                                {{-- <button class="ms-2"><img
                                        src="{{ asset('assets/frontend_assets/img/home/apple.png') }}" alt=""
                                        srcset="" class="download_app_btn "></button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 pt-5 download_app_bus">
                    <img src="{{ asset('assets/frontend_assets/img/home/img16.png') }}" alt="" srcset=""
                        class="download_app_img">
                </div>
            </div>
        </div>
    </section>
@endsection



@section('scripts')
<!-- Leaflet CSS & JS -->
<script src="https://maps.gomaps.pro/maps/api/js?key=AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl&libraries=places,marker"></script>

<script>
    let map, directionsService, directionsRenderer;

    $(document).on('click', '.vehicle-link', function(e) {
        e.preventDefault(); // Stop default link behavior
        var url = $(this).data('url');

        $.ajax({
            url: url,
            type: 'GET', // or 'POST' depending on your route
            success: function(response) {
                // Handle the response (you can update part of the page, show a modal, etc.)
                console.log(response);
                $('.fild_search2').show();
                $('#result-container').html(response.html);
                // Example: $('#result-container').html(response);
            },
            error: function(xhr) {
                // Handle error
                $('.fild_search2').hide();
                console.error(xhr.responseText);
            }
        });
    });

    $('#index-booking-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('book-buss') }}",
            method: "GET",
            data: $(this).serialize(),
            beforeSend: function() {
                // Optional: show loading spinner or clear results
                $('.viewBusesBox .container').html('<p>Loading...</p>');
            },
            success: function(response) {
                if (response.vehicleCounts) {
                    let container = $('.viewBusesBox .container');
                    container.empty();

                    console.log(response.from_stop.name);

                    $.each(response.vehicleCounts, function(type, data) {
                        // console.log(data);
                        let vehicleRoute = `{{ route('home.vehicle-search', ['ids' => '__IDS__', 'from' => '__FROM__', 'to' => '__TO__', 'date' => '__DATE__']) }}`;
                        vehicleRoute = vehicleRoute
                            .replace('__IDS__', data.ids.join(','))
                            .replace('__FROM__', response.from)
                            .replace('__TO__', response.to)
                            .replace('__DATE__', response.date);

                        //onclick="window.location.href='${vehicleRoute}'"
                        let vehicleHtml = `
                        <div class="row">
                            <div class="col-lg-12 m-auto booking-form d-flex vehicle-link" data-url="${vehicleRoute}" style="cursor: pointer; flex-wrap:wrap;"> 
                                <div class="col-lg-3 p-2">
                                    <img src="${data.image ?? ''}" alt="" width="100%">
                                </div>
                                <div class="col-lg-9 alignment p-2">
                                    <a href="javascript:void(0)" class="vehicle-link" data-url="${vehicleRoute}" style="text-decoration: none">
                                        ${type} (${data.count})
                                    </a>
                                    <p>Affordable compact rides</p>
                                </div>
                                <div class="col-lg-2"></div>
                            </div>
                        </div>
                        `;
                        container.append(vehicleHtml);
                    });

                    initMap(
                        response.from_stop.latitude,
                        response.from_stop.longitude,
                        response.to_stop.latitude,
                        response.to_stop.longitude,
                        response.from_stop.name,
                        response.to_stop.name
                    );

                    // You can also update direction map here if needed
                } else {
                    $('.viewBusesBox .container').html('<p>No buses found.</p>');
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors;
                if (errors) {
                    alert(Object.values(errors).flat().join("\n"));
                } else {
                    alert("Something went wrong.");
                }
            }
        });
    });

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

    function initMap(fromLat = null, fromLng = null, toLat = null, toLng = null, from_name = null, to_name = null) {
        console.log('comming to init');
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 22.5726, lng: 88.3639 }, // Default center
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({ map: map });

        console.log(fromLat, fromLng, toLat, toLng);
        // const fromLat = @json($from_bus_stop->latitude ?? null);
        // const fromLng = @json($from_bus_stop->longitude ?? null);
        // const toLat = @json($to_bus_stop->latitude ?? null);
        // const toLng = @json($to_bus_stop->longitude ?? null);

        // If both locations exist, draw route
        if (fromLat && fromLng && toLat && toLng) {
            calculateRoute(fromLat, fromLng, toLat, toLng, from_name, to_name);
        } else {
            // Show user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const userPos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        map.setCenter(userPos);
                        map.setZoom(17); // Zoom in closer

                        new google.maps.Marker({
                            position: userPos,
                            map: map,
                            title: "Your Location",
                            icon: {
                                url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png", // Red pin
                                scaledSize: new google.maps.Size(48, 48) // Bigger marker
                            }
                        });
                    },
                    () => {
                        console.error("Geolocation permission denied.");
                    }
                );
            } else {
                console.error("Geolocation not supported.");
            }
        }

        startRealTimeUpdates(); // Show moving vehicles if needed
    }


    function calculateRoute(fromLat, fromLng, toLat, toLng, from_name, to_name) {
        const origin = new google.maps.LatLng(fromLat, fromLng);
        const destination = new google.maps.LatLng(toLat, toLng);

        directionsService.route(
            { origin, destination, travelMode: 'DRIVING' },
            (result, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                    createMarkerWithText(origin, @json($from_bus_stop->name ?? "Start"), '#4285F4');
                    createMarkerWithText(destination, @json($to_bus_stop->name ?? "Destination"), '#EA4335');
                }
            }
        );
    }


    function drawRoute(fromLat, fromLng, toLat, toLng) {
        const origin = new google.maps.LatLng(fromLat, fromLng);
        const destination = new google.maps.LatLng(toLat, toLng);

        directionsService.route(
            { origin, destination, travelMode: 'DRIVING' },
            (result, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);

                    createMarkerWithText(origin, @json($from_bus_stop->name ?? "Start"), '#4285F4');
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