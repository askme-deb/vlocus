@extends('frontend.layouts.app')
@section('title','Booking')
@section('content')
    <section class="fromTo py-3">
        <div class="container">
            <div class="row">
                <form action="{{ route('book-buss') }}" method="GET" class="booking-form">
                    <div class="row align-items-center">
                        <!-- From Location -->
                        <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                            <label for="from">From</label>
                            <input type="text" id="from" name="from" value="{{ $from ?? '' }}" placeholder="Enter departure city">
                        </div>

                        <!-- Exchange Icon -->
                        <div class="col-lg-1 col-md-1 col-sm-12 text-center my-2 topBannerArrow">
                            <span class="alignment trans"><i class="fas fa-exchange-alt"></i></span>
                        </div>

                        <!-- To Location -->
                        <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                            <label for="to">To</label>
                            <input type="text" id="to" name="to"  value="{{ $to ?? '' }}" placeholder="Enter destination">
                        </div>

                        <!-- Date Picker -->
                        <div class="col-lg-3 col-md-3 col-sm-12 d-flex flex-column">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" value="{{ $date ?? date('Y-m-d') }}">
                        </div>

                        <!-- Search Button -->
                        <div class="col-lg-2 col-md-2 col-sm-12 mt-3 alignment">
                            <button type="submit" class="btn btn_style">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="viewBusesBox">
        <div class="container">
            @foreach ($vehicleCounts as $type => $data)
                <div class="row">
                    <div class="col-lg-8 m-auto booking-form d-flex mb-2"
                        onclick="window.location.href='{{ route('home.vehicle-search', ['ids' => implode(',', $data['ids']),'from'=>$from,'to'=>$to,'date'=>$date]) }}'"
                        style="cursor: pointer; flex-wrap:wrap;">
                        <div class="col-lg-2 p-2">
                            <img src="{{ $data['image'] }}" alt="" width="100%">
                        </div>
                        <div class="col-lg-8 alignment p-2 ps-5">
                            <a href="{{ route('home.vehicle-search', ['ids' => implode(',', $data['ids']),'from'=>$from,'to'=>$to,'date'=>$date]) }}"
                                style="text-decoration: none"> {{ $type }} ({{ $data['count'] }})
                            </a>
                            <p class="my-0">6 mins away</p>
                            <p>Affordable compact rides</p>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </div>
            @endforeach

        </div>
    </section>


    {{-- @foreach ($vehicleCounts as $type => $data)
        <div class="accordion-item">
            <div class="accordion-header shadow my-2 p-1" id="panelsStayOpen-headingThree">
                <div class="row p-2">
                    <div class="col-lg-3">
                        <h6>

                        </h6>
                    </div>

                    <div class="col-lg-1">
                        <a href="{{ route('home.vehicle-search', ['ids' => implode(',', $data['ids'])]) }}">
                            {{ $type }} ({{ $data['count'] }})
                        </a>
                    </div>

                </div>
            </div>
    @endforeach --}}


    </div>
    </div>
    </section>
@endsection
