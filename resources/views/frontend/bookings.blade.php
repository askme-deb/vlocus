@extends('frontend.layouts.app')
@section('title','Booking')
@section('content')
<section class="fromTo shadow py-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-flex">
                <div class="d-flex flex-column me-3">
                    <label for="from">From</label>
                    <input type="text" id="from" class="border-bottom">
                </div>
                <span class="alignment me-3">
                    <i class="fas fa-exchange-alt"></i>

                </span>
                <div class="d-flex flex-column me-3">
                    <label for="to">To</label>
                    <input type="text" id="to" class="border-bottom">
                </div>
                <div class="d-flex flex-column me-3">
                    <label for="date">Date</label>
                    <input type="date" id="date" class="border-bottom">
                </div>
                <div class="d-flex flex-column" style="justify-content: end;">
                    <button class="btn btn_style">Search</button>
                </div>

            </div>
            <div class="col-lg-6">

            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div>
                    <h5>FILTERS</h5>
                    <hr>
                    <div class="d-flex flex-column">
                        <a href="">Live Tracking</a>
                        <a href="">Special Price</a>
                        <a href="">Primo Bus</a>
                    </div>
                </div>
                <div>
                    <h5>DEPARTURE TIME</h5>
                    <form action="form.php" class="d-flex flex-column">
                        <div>
                            <input type="checkbox" id="before">
                            <label for="before">Before 6am</label>
                        </div>
                        <div>
                            <input type="checkbox" id="morning">
                            <label for="morning">6am to 12pm</label>
                        </div>
                        <div>
                            <input type="checkbox" id="night">
                            <label for="night">12pm to 6pm</label>
                        </div>
                        <div>
                            <input type="checkbox" id="after">
                            <label for="after">After 6pm</label>
                        </div>

                    </form>
                </div>
                <div>
                    <h5>BUS TYPE</h5>
                    <form action="form.php" class="d-flex flex-column">
                        <div>
                            <input type="checkbox" id="before">
                            <label for="before">Seater</label>
                        </div>
                        <div>
                            <input type="checkbox" id="morning">
                            <label for="morning">Sleeper</label>
                        </div>
                        <div>
                            <input type="checkbox" id="night">
                            <label for="night">AC</label>
                        </div>
                        <div>
                            <input type="checkbox" id="after">
                            <label for="after">NonAC</label>
                        </div>

                    </form>
                </div>
                <div>
                    <h5>SEAT AVAILABILITY</h5>
                    <form action="form.php" class="d-flex flex-column">
                        <div>
                            <input type="checkbox" id="before">
                            <label for="before">Single Seats</label>
                        </div>
                    </form>
                </div>
                <div>
                    <h5>ARRIVAL TIME</h5>
                    <form action="form.php" class="d-flex flex-column">
                        <div>
                            <input type="checkbox" id="before">
                            <label for="before">Before 6am</label>
                        </div>
                        <div>
                            <input type="checkbox" id="morning">
                            <label for="morning">6am to 12pm</label>
                        </div>
                        <div>
                            <input type="checkbox" id="night">
                            <label for="night">12pm to 6pm</label>
                        </div>
                        <div>
                            <input type="checkbox" id="after">
                            <label for="after">After 6pm</label>
                        </div>

                    </form>
                </div>
                <div>

                    <form action="form.php">
                        <div class="d-flex flex-column">

                            <label for="before">BOARDING POINT </label>
                            <input type="text">
                        </div>
                    </form>
                </div>

                <div>

                    <form action="form.php">
                        <div class="d-flex flex-column">

                            <label for="before">DROPPING POINT </label>
                            <input type="text">
                        </div>
                    </form>
                </div>
                <div>

                    <form action="form.php">
                        <div class="d-flex flex-column">

                            <label for="before">OPERATOR</label>
                            <input type="text">
                        </div>
                    </form>
                </div>
                <div>
                    <h5>RTC BUS TYPE</h5>
                    <form action="form.php" class="d-flex flex-column">
                        <div>
                            <input type="checkbox" id="before">
                            <label for="before">EXPRESS</label>
                        </div>
                        <div>
                            <input type="checkbox" id="morning">
                            <label for="morning">HIM DHARA</label>
                        </div>
                        <div>
                            <input type="checkbox" id="night">
                            <label for="night">METRO</label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-10">
                <!-- <div class="autoplay">
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up
                                the bulk of the card's content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up
                                the bulk of the card's content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up
                                the bulk of the card's content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up
                                the bulk of the card's content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up
                                the bulk of the card's content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up
                                the bulk of the card's content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div> -->
                <section class="viewBusesBox">
                    <div class="container">
                        <div class="row">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                {{-- <div class="accordion-item">
                                    <div class="accordion-header shadow my-2" id="panelsStayOpen-headingOne">
                                        <div class="row align-items-center">
                                            <div class="col-lg-2 d-flex justify-content-center">
                                                <div style="width: 90px;">
                                                    <img src="{{ asset('assets/frontend_assets/img/home/WBTC.png') }}" alt=""
                                                        class="img_size1">
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <h5>WBTC CTC Buses
                                                </h5>
                                            </div>
                                            <div class="col-lg-4">
                                                <h5>Use Code FIRST to Get Upto ₹250 off on WBTC!</h5>
                                            </div>
                                            <div class="col-lg-2">
                                                <h5>13 Buses</h5>
                                            </div>
                                            <div class="col-lg-2">
                                                <h5>From INR 145</h5>

                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn_style" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseOne"
                                                aria-expanded="true"
                                                aria-controls="panelsStayOpen-collapseOne">VIEW BUSES
                                            </button>
                                        </div>

                                    </div>
                                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne" data-bs-parent="#accordionPanelsStayOpenExample">
                                            <div class="accordion-body p-0 my-3">
                                                <div class="shadow">
                                                    <div class="row p-2">
                                                        <div class="col-lg-4">
                                                            <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                            </h6>
                                                            <p>Non AC Seater (2+3)</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>06:00</p>
                                                            <p>Esplanade</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>04h 30m</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>10:30</p>
                                                            <p>Bus Stand</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>INR 155</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>
                                                                52 Seats available</p>
                                                            <p>18 Window</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn_style" onclick="toggleSeatBox()">VIEW SEATS</button>
                                                    </div>
                                                     <!-- Seat Selection Box (Initially Hidden) -->
                                                    <div id="seatSelectionBox" class="shadow p-3 mt-2" style="display: none; background-color: #fcf9eb;">
                                                        <div class="d-flex justify-content-between">
                                                            <h5>Select Your Seat</h5>
                                                            <button class="btn btn-danger btn-sm" onclick="toggleSeatBox()">Close</button>
                                                        </div>
                                                        <div class="d-flex gap-2 mt-2">
                                                            <button class="btn btn-outline-primary">Seat 1</button>
                                                            <button class="btn btn-outline-primary">Seat 2</button>
                                                            <button class="btn btn-outline-primary">Seat 3</button>
                                                            <button class="btn btn-outline-primary">Seat 4</button>
                                                        </div>
                                                    </div>
                                                </div>
                                          
                                         
                                                <div class="shadow">
                                                    <div class="row p-2">
                                                        <div class="col-lg-4">
                                                            <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                            </h6>
                                                            <p>Non AC Seater (2+3)</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>06:00</p>
                                                            <p>Esplanade</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>04h 30m</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>10:30</p>
                                                            <p>Bus Stand</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>INR 155</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>
                                                                52 Seats available</p>
                                                            <p>18 Window</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn_style">VIEW SEATS</button>
                                                    </div>
                                                </div>
                                      
                                                <div class="shadow p-2" style="background-color: #fcf9eb;">
                                                    <div class="row">
                                                        <h5>Select your preferred time</h5>
                                                    </div>
                                                    <div class="d-flex">
                                                        <button class="btn btn_style me-3">6am to 12pm</button>
                                                        <button class="btn btn_style me-3">6am to 12pm</button>
                                                        <button class="btn btn_style">12pm to 6pm</button>
                                                    </div>
                                                </div>
                                    
                                                <div class="shadow">
                                                    <div class="row p-2">
                                                        <div class="col-lg-4">
                                                            <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                            </h6>
                                                            <p>Non AC Seater (2+3)</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>06:00</p>
                                                            <p>Esplanade</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>04h 30m</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>10:30</p>
                                                            <p>Bus Stand</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>INR 155</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>
                                                                52 Seats available</p>
                                                            <p>18 Window</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn_style">VIEW SEATS</button>
                                                    </div>
                                                </div>
                                         
                                        
                                                <div class="shadow">
                                                    <div class="row p-2">
                                                        <div class="col-lg-4">
                                                            <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                            </h6>
                                                            <p>Non AC Seater (2+3)</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>06:00</p>
                                                            <p>Esplanade</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>04h 30m</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>10:30</p>
                                                            <p>Bus Stand</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>INR 155</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>
                                                                52 Seats available</p>
                                                            <p>18 Window</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn_style">VIEW SEATS</button>
                                                    </div>
                                                </div>
                                      
                                                <div class="shadow">
                                                    <div class="row p-2">
                                                        <div class="col-lg-4">
                                                            <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                            </h6>
                                                            <p>Non AC Seater (2+3)</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>06:00</p>
                                                            <p>Esplanade</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>04h 30m</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>10:30</p>
                                                            <p>Bus Stand</p>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <p>INR 155</p>

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p>
                                                                52 Seats available</p>
                                                            <p>18 Window</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn_style">VIEW SEATS</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <div class="accordion-header shadow my-2" id="panelsStayOpen-headingTwo">
                                        <div class="row align-items-center">
                                            <div class="col-lg-2 d-flex justify-content-center">
                                                <div style="width: 90px;">
                                                    <img src="{{ asset('assets/frontend_assets/img/home/WBTC.png') }}" alt=""
                                                        class="img_size1">
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <h5>WBTC CTC Buses
                                                </h5>
                                            </div>
                                            <div class="col-lg-4">
                                                <h5>Use Code FIRST to Get Upto ₹250 off on WBTC!</h5>
                                            </div>
                                            <div class="col-lg-2">
                                                <h5>13 Buses</h5>
                                            </div>
                                            <div class="col-lg-2">
                                                <h5>From INR 145</h5>

                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn_style" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseTwo"
                                                aria-expanded="true"
                                                aria-controls="panelsStayOpen-collapseTwo">VIEW BUSES
                                            </button>
                                        </div>
                                    </div>

                                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="panelsStayOpen-headingTwo" data-bs-parent="#accordionPanelsStayOpenExample">
                                        <div class="accordion-body p-0 my-3">
                                            <div class="shadow">
                                                <div class="row p-2">
                                                    <div class="col-lg-4">
                                                        <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                        </h6>
                                                        <p>Non AC Seater (2+3)</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>06:00</p>
                                                        <p>Esplanade</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>04h 30m</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>10:30</p>
                                                        <p>Bus Stand</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>INR 155</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>
                                                            52 Seats available</p>
                                                        <p>18 Window</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn_style">VIEW SEATS</button>
                                                </div>
                                            </div>
                                    
                                            <div class="shadow">
                                                <div class="row p-2">
                                                    <div class="col-lg-4">
                                                        <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                        </h6>
                                                        <p>Non AC Seater (2+3)</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>06:00</p>
                                                        <p>Esplanade</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>04h 30m</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>10:30</p>
                                                        <p>Bus Stand</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>INR 155</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>
                                                            52 Seats available</p>
                                                        <p>18 Window</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn_style">VIEW SEATS</button>
                                                </div>
                                            </div>
                                        
                                            <div class="shadow p-2" style="background-color: #fcf9eb;">
                                                <div class="row">
                                                    <h5>Select your preferred time</h5>
                                                </div>
                                                <div class="d-flex">
                                                    <button class="btn btn_style me-3">6am to 12pm</button>
                                                    <button class="btn btn_style me-3">6am to 12pm</button>
                                                    <button class="btn btn_style">12pm to 6pm</button>
                                                </div>
                                            </div>
                            
                                            <div class="shadow">
                                                <div class="row p-2">
                                                    <div class="col-lg-4">
                                                        <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                        </h6>
                                                        <p>Non AC Seater (2+3)</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>06:00</p>
                                                        <p>Esplanade</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>04h 30m</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>10:30</p>
                                                        <p>Bus Stand</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>INR 155</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>
                                                            52 Seats available</p>
                                                        <p>18 Window</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn_style">VIEW SEATS</button>
                                                </div>
                                            </div>
                            
                                            <div class="shadow">
                                                <div class="row p-2">
                                                    <div class="col-lg-4">
                                                        <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                        </h6>
                                                        <p>Non AC Seater (2+3)</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>06:00</p>
                                                        <p>Esplanade</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>04h 30m</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>10:30</p>
                                                        <p>Bus Stand</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>INR 155</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>
                                                            52 Seats available</p>
                                                        <p>18 Window</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn_style">VIEW SEATS</button>
                                                </div>
                                            </div>
                                        
                                            <div class="shadow">
                                                <div class="row p-2">
                                                    <div class="col-lg-4">
                                                        <h6>SBSTC-KOLKATA - DIGHA - 06:00 (HWH DEPOT) - 1001
                                                        </h6>
                                                        <p>Non AC Seater (2+3)</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>06:00</p>
                                                        <p>Esplanade</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>04h 30m</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>10:30</p>
                                                        <p>Bus Stand</p>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <p>INR 155</p>

                                                    </div>
                                                    <div class="col-lg-2">
                                                        <p>
                                                            52 Seats available</p>
                                                        <p>18 Window</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn_style">VIEW SEATS</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                @foreach($buses as $bus)
                                <div class="accordion-item">
                                    <div class="accordion-header shadow my-2 p-1" id="panelsStayOpen-headingThree">
                                        <div class="row p-2">
                                            <div class="col-lg-3">
                                                <h6>
                                                    {{ optional($bus->route->fromBusStop)->name }} - {{ optional($bus->route->toBusStop)->name }} {{ optional($bus->route->viaBusStop)->name ? 'Via ' . optional($bus->route->viaBusStop)->name : '' }}
                                                </h6>
                                            </div>
                                            <div class="col-lg-2">
                                                <p>{{ format_time($bus->departure_time) }}</p>
                                                <p>{{ optional($bus->route->fromBusStop)->name }}</p>
                                            </div>
                                            <div class="col-lg-2">
                                                <p>{{ calculate_duration($bus->departure_time,$bus->arrival_time) }}</p>
                                            </div>
                                            <div class="col-lg-2">
                                                <p>{{ format_time($bus->arrival_time) }}</p>
                                                <p>{{ optional($bus->route->toBusStop)->name }}</p>
                                            </div>
                                            <div class="col-lg-1">
                                                <p>INR 155</p>
                                            </div>
                                            <div class="col-lg-2">
                                                <p>52 Seats available</p>
                                                <p>18 Window</p>
                                            </div>
                                        </div>
                                    
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn_style" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapseThree"
                                                aria-expanded="true"
                                                aria-controls="panelsStayOpen-collapseThree">VIEW SEATS
                                            </button>
                                        </div>

                                    
                                        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree" data-bs-parent="#accordionPanelsStayOpenExample">
                                            <div class="accordion-body p-0 my-3">
                                                <div class="shadow">
                                                    <div class="row p-2">
                                                        <div class="d-flex justify-content-between">
                                                            <h5>Select Your Seat</h5>
                                                            <button class="btn btn-danger btn-sm" onclick="toggleSeatBox()">Close</button>
                                                        </div>
                                                        <div class="d-flex gap-2 mt-2">
                                                            <button class="btn btn-outline-primary">Seat 1</button>
                                                            <button class="btn btn-outline-primary">Seat 2</button>
                                                            <button class="btn btn-outline-primary">Seat 3</button>
                                                            <button class="btn btn-outline-primary">Seat 4</button>
                                                        </div>
                                                        <!-- Seat Selection Box (Initially Hidden) -->
                                                    <div id="seatSelectionBox" class="shadow p-3 mt-2" style="display: none; background-color: #fcf9eb;">
                                                        
                                                    </div>
                                                </div>
                                            
                                            
                                        
                                        
                                                <div class="shadow p-2" style="background-color: #fcf9eb;">
                                                    <div class="row">
                                                        <h5>Select your preferred time</h5>
                                                    </div>
                                                    <div class="d-flex">
                                                        <button class="btn btn_style me-3">6am to 12pm</button>
                                                        <button class="btn btn_style me-3">6am to 12pm</button>
                                                        <button class="btn btn_style">12pm to 6pm</button>
                                                    </div>
                                                </div>
                            
                                    
                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
@endsection