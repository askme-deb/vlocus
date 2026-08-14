@extends('frontend.layouts.app')
@section('title', $page->meta_title ?? 'About')
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')
@section('content')

<section class="top_banner py-0 about_warp">
    <div class="container">
        <div class="row mx-2">
            <div class="col-lg-5">
                <img src="{{ asset('assets/frontend_assets/img/home/team.png') }}" alt="" srcset="" class="img_size">
            </div>
            <div class="col-lg-7 alignment lh-lg">
                <div class="">
                    <h1>About Us</h1>
                    <p>At [Your Company Name], our success is driven by a passionate and experienced team that
                        works tirelessly to ensure seamless transportation and logistics services. Meet the
                        professionals who make it all happen!
                    </p>
                </div>

            </div>

        </div>
    </div>
</section>
<section class="track_shipment">
    <div class="container">
        <div class="row mx-2">

            <div class="col-lg-7 alignment">
                <div class=" track_shipment_padding">
                    <h1>Why Choose Us?</h1>
                    <ul>
                        <li class="mb-2"><strong>Experience & Expertise – </strong>Years of industry experience
                            with skilled professionals.</li>
                        <li class="mb-2"><strong>On-Time & Reliable – </strong>Advanced tracking systems ensure
                            timely deliveries.</li>
                        <li class="mb-2"><strong>Safety First – </strong>Committed to the highest safety and
                            compliance standards.</li>
                        <li class="mb-2"><strong>Eco-Friendly Approach – </strong>Investing in fuel-efficient
                            and sustainable transport solutions.</li>

                    </ul>
                </div>

            </div>
            <div class="col-lg-5">
                <img src="{{ asset('assets/frontend_assets/img/home/9631.png') }}" alt="" srcset="" class="img_size">
            </div>
        </div>
    </div>
</section>
<section class="how_works">
    <div class="container">
        <h1 class="text-center pb-3">Our <b>Team Management</b></h1>
        <div class="row mx-2">
              <div class="how_works-slider">
            <div class="col-lg-4 how_works_padding">
                <div class="card">
                    <img src="{{ asset('assets/frontend_assets/img/home/blank_img.png') }}" class="card-img-top img_size  mx-auto d-block"
                        alt="...">
                    <div class="card-body">
                        <h5 class="card-title text-center font_bold_">Name , designation</h5>
                        <p class="card-text text-center">With over 20 years in the logistics industry, John is
                            the driving force behind our company's vision. His leadership ensures that we
                            deliver efficient, reliable, and customer-focused transport solutions.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 how_works_padding">
                <div class="card">
                    <img src="{{ asset('assets/frontend_assets/img/home/blank_img.png') }}" class="card-img-top img_size  mx-auto d-block"
                        alt="...">
                    <div class="card-body">
                        <h5 class="card-title text-center font_bold_">Name , designation</h5>
                        <p class="card-text text-center">Managing a fleet of trucks and transport vehicles is no
                            small task, but Michael does it with precision. He ensures our vehicles are
                            well-maintained, safe, and compliant with industry standards.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 how_works_padding">
                <div class="card">
                    <div>

                    </div>
                    <img src="{{ asset('assets/frontend_assets/img/home/blank_img.png') }}" class="card-img-top img_size  mx-auto d-block"
                        alt="...">
                    <div class="card-body">
                        <h5 class="card-title text-center font_bold_">Name , designation</h5>
                        <p class="card-text text-center">Efficiency is key in transport, and David ensures that
                            our routes are optimized for timely deliveries. His expertise in logistics minimizes
                            delays and maximizes customer satisfaction.</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>

@endsection