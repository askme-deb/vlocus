<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark"> {{-- light --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/dashboard-assets/assets/images/favicon-32x32.png') }}" type="image/png">
    <!-- loader-->
    <link href="{{ asset('assets/dashboard-assets/assets/css/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/dashboard-assets/assets/js/pace.min.js') }}"></script>

    <!--plugins-->
    <link href="{{ asset('assets/dashboard-assets/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard-assets/assets/plugins/metismenu/metisMenu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard-assets/assets/plugins/metismenu/mm-vertical.css') }}">
    <!--bootstrap css-->
    <link href="{{ asset('assets/dashboard-assets/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <!--main css-->
    <link href="{{ asset('assets/dashboard-assets/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard-assets/sass/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard-assets/sass/dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard-assets/sass/blue-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard-assets/sass/responsive.css') }}" rel="stylesheet">
    @yield('style')
    <style>
        .gradient-text {
            background-image: linear-gradient(310deg, #7928ca, #ff0080);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold; /* Optional: Adjust for better visibility */
        }
    </style>
</head>

<body>
    <!--authentication-->
    <div class="mx-3 mx-lg-0">
        <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden p-4">
            <div class="row g-4">
                <div class="col-lg-6 d-flex">
                    <div class="card-body">
                        {{-- <img src="{{ asset('assets/dashboard-assets/assets/images/logo1.png') }}" class="mb-4" width="145" alt=""> --}}
                        <div class="">
                            <h1 class="logo-text gradient-text">{{ config('app.name', 'Laravel') }}</h1>
                        </div>
                        <h4 class="fw-bold">Get Started Now</h4>
                        <p class="mb-0">Enter your credentials to login your account</p>
                    
                        <div class="form-body mt-4">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-lg-flex d-none">
                    <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-primary">
                        <img src="{{ asset('assets/dashboard-assets/assets/images/auth/login1.jpg') }}" class="img-fluid" style=" width: 100%;height: 100%;object-fit: cover;border-radius: inherit;" alt="">
                    </div>
                </div>

            </div><!--end row-->
        </div>
    </div>




    <!--authentication-->




    <!--plugins-->
    <script src="{{ asset('assets/dashboard-assets/assets/js/jquery.min.js') }}"></script>
    
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bi-eye-slash-fill");
                    $('#show_hide_password i').removeClass("bi-eye-fill");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bi-eye-slash-fill");
                    $('#show_hide_password i').addClass("bi-eye-fill");
                }
            });
        });
    </script>
    @yield('script')
</body>

</html>