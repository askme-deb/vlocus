@extends('frontend.layouts.app')

@section('title', $page->meta_title ?? "FAQ's")
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')

@section('content')

@if (!empty($page))
<section class="top_banner py-0">
    <div class="container">
        <div class="row mx-2">
            @if($page->hasMedia('page-image'))
            <div class="col-lg-5">
                <img src="{{ $page->getFirstMediaUrl('page-image') }}" alt="" srcset="" class="img_size">
            </div>
            @endif
            <div class="col-lg-7 alignment lh-lg">
                <div class="">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@if($faqs->isNotEmpty())
<section class="asked_question gradient">
    <div class="container">
        <div class="row text-center mx-4 pb-3">
            <h1>Frequently <b>Asked Questions</b></h1>
        </div>
        <div class="row mx-4 pt-3">
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
                {{-- <div class="">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <span class="accordion_number">2</span> <span> Can I have a lost item delivered to
                                me?</span>
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>This is the second item's accordion body.</strong> It is hidden by default,
                            until the collapse plugin adds the appropriate classes that we use to style each
                            element. These classes control the overall appearance, as well as the showing and
                            hiding via CSS transitions. You can modify any of this with custom CSS or overriding
                            our default variables. It's also worth noting that just about any HTML can go within
                            the, though the transition does limit overflow.
                        </div>
                    </div>
                </div>
                <div class="">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <span class="accordion_number">3</span> <span> Can I have a lost item delivered to
                                me?</span>
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>This is the third item's accordion body.</strong> It is hidden by default,
                            until the collapse plugin adds the appropriate classes that we use to style each
                            element. These classes control the overall appearance, as well as the showing and
                            hiding via CSS transitions. You can modify any of this with custom CSS or overriding
                            our default variables. It's also worth noting that just about any HTML can go within
                            the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                    </div>
                </div>
                <div class="">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <span class="accordion_number">4</span> <span> Can I have a lost item delivered to
                                me?</span>
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>This is the third item's accordion body.</strong> It is hidden by default,
                            until the collapse plugin adds the appropriate classes that we use to style each
                            element. These classes control the overall appearance, as well as the showing and
                            hiding via CSS transitions. You can modify any of this with custom CSS or overriding
                            our default variables. It's also worth noting that just about any HTML can go within
                            the, though the transition does limit overflow.
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>
@endif

@endsection