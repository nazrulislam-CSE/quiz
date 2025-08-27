@extends('layouts.frontend.app', [$pageTitle => $page->title])
@section('content')

@if ($page->page_slug == 'about-us')
    <section class="py-5 bg-light" id="about">
        <div class="container">
            @foreach ($abouts as $about)
                <div class="row align-items-center mb-5">
                    <!-- Left: Image Card -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card shadow-sm border-0">
                            <img src="{{ !empty($about->image) ? url('upload/about/' . $about->image) : url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') }}"
                                class="card-img-top rounded" alt="{{ $about->title }}">
                        </div>
                    </div>

                    <!-- Right: Content Card -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 p-4 h-100">
                            <h2 class="fw-bold card-title">{{ $about->title }}</h2>
                            <p class="card-text mt-3">{!! $about->description !!}</p>
                            @if ($about->video_link && $about->video_link != '#')
                                <a href="{{ $about->video_link }}" class="btn btn-primary mt-3">ভিডিও দেখুন</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

@elseif($page->page_slug == 'contact-us')
<section class="py-5">
    <div class="container">
        <div class="card shadow-sm border-0 p-4">
            <h2 class="fw-bold card-title mb-3">Contact Us</h2>

            <div class="row mt-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="card border-0 shadow-sm p-3 h-100">
                        <h5 class="fw-bold">Address</h5>
                        <p>Malopara, Rajshahi</p>
                        <!-- Google Map Embed -->
                        <div class="mt-3">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.123456789!2d88.600000!3d24.366667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fb0abcdef12345%3A0xabcdef123456789!2sMalopara%2C%20Rajshahi!5e0!3m2!1sen!2sbd!4v1690000000000!5m2!1sen!2sbd"
                                width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-3 h-100">
                        <h5 class="fw-bold">Email & Phone</h5>
                        <p>Email: {{ get_setting('email')->value ?? ''}}</p>
                        <p>Phone: +8801316017328</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@else
    <section class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 p-4">
                <h2 class="fw-bold card-title mb-3">{{ $page->title ?? 'Page Title' }}</h2>
                <p class="card-text">This page does not have specific content yet. You can update it from the admin panel.</p>
            </div>
        </div>
    </section>
@endif

@endsection
