@extends('layouts.frontend.app', [$pageTitle => 'Page Title'])
@section('content')
    <style>
        .banner_image {
            padding-top: 0px !important;
        }

        .single_container {
            padding-top: 0px !important;
        }

        .branch-card {
            background: #fff;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .branch-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .branch-card h6 {
            color: #000;
            font-size: 1.1rem;
        }

        .branch-card i.fa-map-marker-alt {
            color: #e74c3c;
        }

        .branch-card i.fa-phone-alt {
            color: #0d6efd;
        }
          .about-description ul {
            list-style: none;
            padding-left: 0;
        }

        .about-description ul li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 10px;
        }

        .about-description ul li::before {
            content: '✔️';
            position: absolute;
            left: 0;
            color: green; /* or your preferred color */
        }
    </style>
    @php
        $currentUrl = url()->current();
        $abouts = App\Models\About::where('status',1)->latest()->get();
    @endphp
    <!--================ PAGE TITLE  ================== -->
    <!--================ POPULAR PAGES ================== -->
    <section class="single_container">
        <div class="container">
            <div class="row">
                @if (\Str::contains($currentUrl, 'contact-us'))
                  <div class="container py-5">
    <!-- Contact Info Section -->
    <div class="row text-center mb-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-lg border-0">
                <div class="card-body">
                    <div class="mb-3 text-primary fs-1">
                        <i class="ti-mobile"></i>
                    </div>
                    <h5 class="card-title fw-bold">Phone Number</h5>
                    <p class="card-text">{{ get_setting('phone')->value ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-lg border-0">
                <div class="card-body">
                    <div class="mb-3 text-success fs-1">
                        <i class="ti-location-pin"></i>
                    </div>
                    <h5 class="card-title fw-bold">Location</h5>
                    <p class="card-text">{{ get_setting('business_address')->value ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-lg border-0">
                <div class="card-body">
                    <div class="mb-3 text-danger fs-1">
                        <i class="ti-email"></i>
                    </div>
                    <h5 class="card-title fw-bold">Email Address</h5>
                    <p class="card-text">{{ get_setting('email')->value ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <h2 class="fw-bold">Write Us Any Message</h2>
            <p class="text-muted">Let us know how we can help you. Fill in the form and we’ll get back to you shortly.</p>

            <!-- Social Media Links -->
            <div class="d-flex mt-3">
                <a href="{{ get_setting('facebook_url')->value ?? '#' }}" class="me-3 text-primary fs-4"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ get_setting('twitter_url')->value ?? '#' }}" class="me-3 text-info fs-4"><i class="fab fa-twitter"></i></a>
                <a href="{{ get_setting('instagram_url')->value ?? '#' }}" class="me-3 text-danger fs-4"><i class="fab fa-instagram"></i></a>
                <a href="{{ get_setting('linkedin_url')->value ?? '#' }}" class="text-primary fs-4"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <div class="col-lg-7">
            <form action="{{ route('contact.store') }}" method="POST" class="bg-light p-4 shadow-sm rounded">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Your Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your name">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Your Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
                    </div>
                    <div class="col-md-6">
                        <label for="subject" class="form-label">Your Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required placeholder="Enter subject">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number">
                    </div>
                    <div class="col-12">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Your message here..."></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">Send Message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Google Map Section -->
    <div class="row">
        <div class="col-12">
            <div class="ratio ratio-16x9">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d58144.75383524952!2d88.56496025705981!3d24.37966436014817!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fbefa96a38d031%3A0x10f93a950ed6f410!2sRajshahi!5e0!3m2!1sen!2sbd!4v1710832605416!5m2!1sen!2sbd"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>

                @else
                    <div class="col-lg-12 mb-5 mb-lg-0 mt-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="wow fadeIn" data-wow-delay="200ms">
                                    <h2 class="mb-3 h3">{{ $page->page_title ?? '' }}</h2>
                                    </h2>
                                    <p class="mb-1-9" style="text-align:justify;">{!! $page->page_description ?? '' !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                              <div class="mt-3 about-description">{!! $about->description !!}</div>
                                            @if ($about->video_link && $about->video_link != '#')
                                                <a href="{{ $about->video_link }}" class="btn btn-primary mt-3">ভিডিও দেখুন</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('frontend-js')
    @endpush
@endsection
