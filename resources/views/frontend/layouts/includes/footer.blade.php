<footer class="text-dark py-5">
    <div class="container-fluid">
        <div class="row">
            <!-- About Us Section -->
            <div class="col-md-5 col-lg-5">
                <a class="navbar-brand d-flex justify-content-start" href="{{ route('home.index') }}">
                    <img src="{{ asset('assets/frontend_assets/img/home/logo-final.png') }}" width="250px"
                        style="height: 60px !important;" alt="Website Logo">
                </a>
                <p> Whether for public transport, corporate travel, or event transportation, a well-managed bus
                    agency is essential for smooth and hassle-free journeys.</p>
                    
                <div class="d-flex">
                    <p class="footer_bold me-3">Follow Us</p>
                    <img src="{{ asset('assets/frontend_assets/img/home/facebook.png') }}" alt=""
                        srcset="">
                    <img src="{{ asset('assets/frontend_assets/img/home/instagram.png') }}" alt=""
                        srcset="">
                    <img src="{{ asset('assets/frontend_assets/img/home/twitter.png') }}" alt="" srcset="">
                </div>
            </div>


            <!-- Newsletter Subscription -->
            <div class="col-md-2 col-lg-2">
                <form>

                </form>
            </div>

            <!-- Social Media Links -->
            <div class="col-md-5 col-lg-5 d-flex" style=" align-items:flex-end;">
                {{-- <div class="input-group mt-3">
                    <label for="email" class="footer_sizes">Email</label>

                </div> --}}
                <div class="text-end">
                    {{-- <div class="input-group mt-3">
                        <input type="email" class="border-0 footer_sizes" placeholder="" required=""
                            id="email">
                        <button class="btn footer_size" type="submit">Subscribe</button>

                    </div>
                    <hr class="mb-5 mt-0"> --}}



  <div class="foot_link">
                        <ul>
                      <li class="nav-item border_style me-1"><a class="nav-link active" href="{{ route('home.about') }}">About</a></li>
                    <li class="nav-item border_style me-1"><a class="nav-link" href="{{ route('home.help') }}">Help</a></li>
                    <li class="nav-item border_style me-1"><a class="nav-link" href="{{ route('home.faq') }}">Faq</a></li>
                    <li class="nav-item border_style me-1"><a class="nav-link" href="{{ route('home.blog') }}">Blog</a></li>
                    <li class="nav-item border_style me-1"><a class="nav-link"
                            href="{{ route('home.company_registration') }}">Company Registrations</a></li>
                            </ul>
                    </div>

                    <p class="mb-0" style="font-size: 20px;">Helpline No : 1234567890</p>
                    <p class="mb-0">
                        <a href="{{ route('home.term_condition') }}">Terms and Conditions</a> | <a
                            href="{{ route('home.privacy') }}">Privacy Policy</a> | Site Cookie
                    </p>
                    <p class="mb-0">&copy; 2025 Sarothi. All rights reserved.</p>
                </div>
            </div>
        </div>




    </div>
</footer>

<div class="modal fade pe-0" id="exampleModalCenter" data-bs-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="d-flex justify-content-end px-4 pt-3">
                    <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="bg" style="height: 100%;border-radius: 12px">
                        <img src="{{ asset('assets/frontend_assets/img/home/loginz.jpg') }}" alt=""
                            class="img_size_login" style="border-radius: 12px">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <form class="px-3 py-2" id="otpForm">
                        @csrf

                        <div>

                            <a class="navbar-brand d-flex justify-content-center" href="{{ route('home.index') }}">
                                <img src="{{ asset('assets/frontend_assets/img/home/logo.png') }}" width="75px"
                                    alt="Website Logo">
                            </a>
                            <h5 class="text-center">Sign in to avail exciting discounts and cashbacks!!</h5>

                            <div id="sendOtpSection">
                                <div class="py-3">
                                    <div class="input-group">
                                        <select class="form-select" id="country_code" name="country_code"
                                            style="width: 27%">
                                            <option value="+91">+91</option>
                                            <!-- Add other country codes as needed -->
                                            <option value="+1">+1</option>
                                            <option value="+44">+44</option>
                                            <!-- More options... -->
                                        </select>
                                        <input type="tel" class="form-control text-center" id="mobile_number"
                                            name="mobile_number" placeholder="Enter your Phone Number"
                                            style="width: 73%">
                                    </div>
                                </div>

                                <button type="button" id="sendOtpBtn" class="btn btn_style w-100 fs p-3">Generate OTP
                                    (One Time Password)</button>
                                <p class="fs my-3">By signing up, you agree to our <a
                                        href="{{ route('home.term_condition') }}">Terms & Conditions</a> and <a
                                        href="{{ route('home.privacy') }}">Privacy Policy</a></p>
                            </div>

                        </div>


                        <div id="verifyOtpSection" style="display: none;">
                            {{-- <div class="d-flex justify-content-end">
                                <button type="button" class="close border-0" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> --}}
                            <div class="w-100 mt-3 mb-3">
                                <div class="float-left">Mobile Number</div>
                                <div class="d-flex justify-content-between w-100">
                                    <div class="mobile-data-part">
                                        <span class="float-left mr-1">+ </span>
                                        <span class="float-left w-25">91</span>
                                        <span class="float-left mr-1" id="show-phone-number-on-otp"></span>
                                    </div>
                                    <div id="chnage-phone-number"
                                        style="cursor: pointer;font-size: 14px;font-weight: 700;color: #1034d9;">CHANGE
                                    </div>
                                </div>
                            </div>
                            <div class="py-2">
                                <input type="text" class="form-control" id="otp" placeholder="Enter OTP"
                                    name="otp">
                            </div>
                            <input type="hidden" id="mobile_number_hidden" name="mobile_number">
                            <button type="submit" id="verifyOtpBtn" class="btn btn_style mt-1 w-100">Verify
                                OTP</button>
                            <div class="text-center mt-2 mb-2">
                                <button id="resendOtpBtn" class="btn btn-secondary">Resend OTP</button>
                                <span id="timerText"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
