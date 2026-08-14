<footer class="text-dark py-5">
    <div class="container">
        <div class="row">
            <!-- About Us Section -->
            <div class="col-md-5 col-lg-5">
                <h1 class="footer_bold mb-4">Logo Here</h1>
                <p> Whether for public transport, corporate travel, or event transportation, a well-managed bus
                    agency is essential for smooth and hassle-free journeys.</p>
                <div class="d-flex">
                    <p class="footer_bold me-3">Follow Us</p>
                    <img src="{{ asset('assets/frontend_assets/img/home/facebook.png') }}" alt="" srcset="">
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
            <div class="col-md-5 col-lg-5 ">
                <div class="input-group mt-3">
                    <input type="email" class="border-0 footer_sizes" placeholder="Email" required>

                </div>
                <div class="text-end">
                    <button class="btn footer_size" type="submit">Subscribe</button>
                    <hr class="mb-5 mt-0">

                    <p class="mb-0" style="font-size: 20px;">Helpline No : 1234567890</p>
                    <p class="mb-0">Terms and Conditions | Prvacy Policy | Site Cookie</p>
                    <p class="mb-0">&copy; 2025 Your Company. All rights reserved.</p>
                </div>
            </div>
        </div>




    </div>
</footer>

<div class="modal fade" id="exampleModalCenter" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="bg" style="height: 100%;">
                        <img src="{{ asset('assets/frontend_assets/img/home/login.png') }}" alt=""
                            class="img_size_login">
                    </div>
                </div>
                <div class="col-lg-6">
                    <form class="px-3 py-3" id="otpForm">
                        @csrf
                 
                        <div id="sendOtpSection">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="close border-0" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="py-3">
                                <label for="phone" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobile_number" name="mobile_number">
                            </div>
                            <button type="button" id="sendOtpBtn" class="btn btn_style">Send OTP</button>
                        </div>

  
                        <div id="verifyOtpSection" style="display: none;">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="close border-0" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="py-3">
                                <label for="otp" class="form-label">Enter OTP</label>
                                <input type="text" class="form-control" id="otp" name="otp">
                            </div>
                            <input type="hidden" id="mobile_number_hidden" name="mobile_number">
                            <button type="submit" id="verifyOtpBtn" class="btn btn_style mt-3">Verify OTP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
