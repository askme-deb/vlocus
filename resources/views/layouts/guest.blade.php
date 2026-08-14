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
            font-weight: bold;
        }
        
        /* Background Image */
        body {
            background-image: url('{{ asset('assets/dashboard-assets/assets/images/auth/bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        /* Glassmorphism Login Card */
        .card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: #fff;
            animation: fadeInUp 0.8s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 500px;
        }

        /* Gradient Text */
        .gradient-text {
            background-image: linear-gradient(135deg, #00ff9d, #00b894);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            font-size: 2rem;
        }

        /* Headings & Text */
        h4.fw-bold {
            color: #eafff6;
            margin-bottom: 0.5rem;
        }

        p.mb-0 {
            color: #bfe9d4;
            font-size: 0.95rem;
        }

        /* Form Input Styling */
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 0.25rem rgba(0, 184, 148, 0.25);
            color: #fff;
        }

        /* .form-control::placeholder {
            color: rgb(255, 255, 255);
        } */

        .form-control::placeholder {
            color: #fff !important;
            opacity: 1 !important; /* some browsers reduce opacity by default */
        }

        /* Input Group Styling */
        .input-group {
            border-radius: 10px;
            overflow: hidden;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-left: none;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .input-group:focus-within .input-group-text {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
        }

        /* Labels */
        .form-label {
            color: #eafff6;
            font-weight: 500;
            margin-bottom: 8px;
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(135deg, #00b894, #00ff9d);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00ff9d, #00b894);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Form Check Styling */
        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-check-input:checked {
            background-color: #00b894;
            border-color: #00b894;
        }

        .form-check-label {
            color: #000000;
        }

        /* Links */
        a {
            color: #00ff9d;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #00b894;
        }

        /* Error Messages */
        .text-danger {
            color: #ff6b6b !important;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        /* Smooth Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Make login design mobile-friendly */
@media (max-width: 768px) {
    body {
        padding: 10px;
        align-items: flex-start; /* move card slightly up */
    }

    .card {
        width: 100%;
        min-height: auto;
        padding: 20px 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }

    .card-body {
        padding: 0 !important;
    }

    .card img {
        width: 80%;
        max-width: 250px;
        height: auto;
    }

    h4.fw-bold {
        font-size: 1.5rem;
    }

    .form-control {
        font-size: 0.9rem;
        padding: 10px 12px;
    }

    .btn-primary {
        font-size: 0.95rem;
        padding: 10px;
    }
}

/* Extra small phones (like iPhone SE) */
@media (max-width: 480px) {
    body {
        padding: 5px;
    }

    .card {
        border-radius: 15px;
        padding: 15px 10px;
    }

    .card img {
        width: 70%;
        max-width: 200px;
    }

    h4.fw-bold {
        font-size: 1.3rem;
    }

    .btn-primary {
        width: 100%;
    }
}

    </style>
</head>

<body>
    <!--authentication-->
    <div class="mx-3 mx-lg-0">
        <div class="card my-5 col-xl-6 col-xxl-6 mx-auto rounded-4 overflow-hidden p-4">
            <div class="row g-4">
                <div class="col-lg-12 d-flex">
                    <div class="card-body">
                        <img src="{{ asset('assets/dashboard-assets/assets/images/logo.png') }}" class="mb-4" width="345" alt="">
                        {{-- <div class="">
                            <h1 class="logo-text gradient-text">{{ config('app.name', 'Laravel') }}</h1>
                        </div> --}}
                        <h4 class="fw-bold">Get Started Now</h4>
                        {{-- <p class="mb-0">Enter your credentials to login your account</p> --}}
                    
                        <div class="form-body mt-4">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-6 d-lg-flex d-none">
                    <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-primary">
                        <img src="{{ asset('assets/dashboard-assets/assets/images/auth/login1.jpg') }}" class="img-fluid" style=" width: 100%;height: 100%;object-fit: cover;border-radius: inherit;" alt="">
                    </div>
                </div> --}}

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