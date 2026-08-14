@extends('frontend.layouts.app')

@section('title', $page->meta_title ?? 'Terms & Condition')
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')

@section('content')

    <section class="top_banner py-0">
        <div class="container">
            <div class="row mx-2">
                @if (!empty($page))
                @if ($page->hasMedia('page-image'))
                <div class="col-lg-5">
                    <img src="{{ $page->getFirstMediaUrl('page-image') }}" alt="" srcset="" class="img_size">
                </div>
                @endif
                <div class="col-lg-7 alignment lh-lg">
                    <div class="">
                        {!! $page->content !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection
