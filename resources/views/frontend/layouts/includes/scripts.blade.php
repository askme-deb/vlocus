<script src="{{ asset('assets/frontend_assets/bundle/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
    integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"
    integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@include('frontend.layouts.includes._notification')

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.testimonial-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            prevArrow: '<button class="slick-prev"><i class="fa-solid fa-arrow-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="fa-solid fa-arrow-right"></i></button>',
            responsive: [{
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 1
                    }
                },


            ]
        });

  $('.how_works-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            prevArrow: '<button class="slick-prev"><i class="fa-solid fa-arrow-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="fa-solid fa-arrow-right"></i></button>',
            responsive: [{
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 1
                    }
                },


            ]
        });


        $('#sendOtpBtn, #resendOtpBtn').on('click', function() {
            let mobileNumber = $('#mobile_number').val().trim();

            if (mobileNumber.length !== 10) {
                round_warning_noti('Please enter a valid 10-digit mobile number.');
                return;

            }

            $.ajax({
                url: '{{ route('home.user.send.otp') }}',
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    mobile_number: mobileNumber
                },
                beforeSend: function() {
                    $('#sendOtpBtn, #resendOtpBtn').prop('disabled', true).text('Sending...');
                },
                success: function(response) {
                    if (response.success) {
                        round_success_noti(response.message);
                        $('#sendOtpSection').hide();
                        $('#show-phone-number-on-otp').text(mobileNumber);
                        start_timer();
                        $('#verifyOtpSection').show();
                        $('#mobile_number_hidden').val(mobileNumber);
                    } else {
                        round_error_noti('Error sending OTP. Try again.');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        round_error_noti(xhr.responseJSON.message);
                    } else {
                        round_error_noti('An unexpected error occurred!');
                    }
    
                },
                complete: function() {
                    $('#sendOtpBtn').prop('disabled', false).text('Send OTP');
                }
            });
        });

        $('#chnage-phone-number').on('click', function(){
            $('#sendOtpSection').show();
            $('#verifyOtpSection').hide();
        });

        function start_timer() {
            let timer = 30; // 30 seconds
            const resendBtn = $('#resendOtpBtn');
            resendBtn.prop('disabled', true).addClass('btn-secondary').removeClass('btn-primary');

            const countdown = setInterval(function() {
                let minutes = Math.floor(timer / 60);
                let seconds = timer % 60;

                // Format to always show two digits for seconds (like 0:09, 0:08)
                if (seconds < 10) {
                    seconds = '0' + seconds;
                }

                resendBtn.text(`Resend OTP (${minutes}:${seconds})`);

                if (timer <= 0) {
                    clearInterval(countdown);
                    resendBtn.prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
                    resendBtn.text('Resend OTP');
                }

                timer--;
            }, 1000);
        }

        $('#otpForm').on('submit', function(e) {
            e.preventDefault();

            let mobileNumber = $('#mobile_number_hidden').val();
            let otp = $('#otp').val().trim();

            if (otp.length === 0) {
                round_warning_noti("Please enter the OTP.");
                return;
            }

            $.ajax({
                url: '{{ route('home.user.verify.otp') }}',
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    mobile_number: mobileNumber,
                    otp: otp
                },
                beforeSend: function() {
                    $('#verifyOtpBtn').prop('disabled', true).text('Verifying...');
                },
                success: function(response) {
                    if (response.success) {
                        round_success_noti(response.message);
                        // console.log(response);
                        $('#exampleModalCenter').modal('hide');
                        if($('#from').val() && $('#to').val()){
                            $('#index-booking-form').trigger('submit');
                        }else{
                            location.reload();
                        }
                        // window.location.href = response.redirect_url
                       
                    } else {
    
                        round_success_noti(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        round_error_noti(xhr.responseJSON.message);
                    } else {
                        round_error_noti('An unexpected error occurred!');
                    }
    
                },
                complete: function() {
                    $('#verifyOtpBtn').prop('disabled', false).text('Verify OTP');
                }
            });
        });



    });
</script>

<script>
    

    flatpickr("#date", {
        dateFormat: "Y-m-d",
        defaultDate: new Date()
    
    });

    flatpickr("#date_date", {
        dateFormat: "Y-m-d",
        defaultDate: new Date()
    
    });



</script>
@yield('scripts')