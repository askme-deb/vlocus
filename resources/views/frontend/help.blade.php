@extends('frontend.layouts.app')
@section('title', $page->meta_title ?? 'Help')
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
<section class="contact-support">
    <div class="container">

        <div class="row">
            <div class="col-lg-4 alignment py-3">
                <h3 class="font_bold_">Contact Our Support Team</h3>
                <p class="mb-0">Helpline No : 1234567890</p>
                <p class="mb-0">Email: support@[yourcompany].com</p>
                <p class="mb-0">Live Chat: Available 24/7 on our website</p>
                <hr>

                <h3 class="font_bold_">Service Hours</h3>
                <p class="mb-0">Monday to Sunday</p>
               
                <hr>
                
                <h3 class="font_bold_">Office Address</h3>
                <p class="mb-0">Company Name: XYZ Transport Services</p>
                <p class="mb-0">Address: 1234 Transport Avenue, Suite 567</p>                        
            </div>
            <div class="col-lg-8 py-3">
                <h2 class="text-center text-alignment">Get in Touch with Our Support Team</h2>
                <p class="text-center">Fill in the details below, and we will get back to you as soon as possible.</p>
                
                <form action="{{ route('web.contact-us.store') }}" method="POST" class="support-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter your name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email" required>
                        </div>
                    </div>
        
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Enter your phone number" required>
                        </div>
                        <div class="col-md-6">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="Enter your subject">
                        </div>
                    </div>
        
                    <div class="mt-3">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" class="form-control" rows="5" placeholder="Describe your issue or question...">{{ old('message') }}</textarea>
                    </div>
        
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn_style">Submit Request</button>
                    </div>
                </form>
            </div>
            
        </div>
       
    </div>
</section>

@endsection