@extends('frontend.layouts.app')
@section('title', 'Privacy')
@section('title', $page->meta_title ?? 'Privacy Policy')
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')
@section('css')
<style>
section.top_banner.blog.pt-5.pb-0.bg-light{
text-align: center;
}
</style>
   

@section('content')

<section class="top_banner blog pt-5 pb-0 bg-light">
    <div class="container">
        <h1 class="mb-3">Our Privacy</h1>
        <p>By using our website or services, you consent to the practices described in this policy.</p>
    </div>
</section>
<section class="track_shipment">
    <div class="container">
        <div class="row mx-2">
            @if (!empty($page))
                @if($page->hasMedia('page-image'))
                <div class="col-lg-5 col-md-5">
                    <img src="{{ $page->getFirstMediaUrl('page-image') }}" alt="" class="track_shipment_img">
                </div>
                @endif
            <div class="col-lg-7 col-md-7 alignment">
                <div class=" track_shipment_padding">
                    {!! $page->content !!}
                </div>

            </div>
            @endif
        </div>
    </div>
</section>
@endsection