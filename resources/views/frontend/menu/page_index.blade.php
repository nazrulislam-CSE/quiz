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
    </style>
    @php
        $currentUrl = url()->current();
        $branches = App\Models\Branch::where('status', 1)->latest()->get();
    @endphp
    <!--================ PAGE TITLE  ================== -->
    <!--================ POPULAR PAGES ================== -->
    <section class="single_container">
        <div class="container">
            <div class="row">
                @if (\Str::contains($currentUrl, 'contact-us'))
                    <div class="col-lg-12">
                        <!--============= CONTACT INFO ============= -->
                        <section>
                            <div class="container">
                                <div class="row mt-n1-9">
                                    <div class="col-md-6 col-xl-4 mt-1-9 wow fadeIn" data-wow-delay="200ms">
                                        <div class="card-style-02 h-100">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="card-icon">
                                                        <i class="ti-mobile text-primary display-20"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3 ms-lg-4">
                                                    <h3 class="h5">Phone Number</h3>
                                                    <p class="mb-0">{{ get_setting('phone')->value ?? 'null' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-4 mt-1-9 wow fadeIn" data-wow-delay="400ms">
                                        <div class="card-style-02 h-100">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="card-icon">
                                                        <i class="ti-location-pin text-primary display-20"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3 ms-lg-4">
                                                    <h3 class="h5">Location</h3>
                                                    <p class="mb-0 w-lg-80">
                                                        {{ get_setting('business_address')->value ?? 'null' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-4 mt-1-9 wow fadeIn" data-wow-delay="600ms">
                                        <div class="card-style-02 h-100">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="card-icon">
                                                        <i class="ti-email text-primary display-20"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3 ms-lg-4">
                                                    <h3 class="h5">Email Address</h3>
                                                    <p class="mb-0">{{ get_setting('email')->value ?? 'null' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- ============= CONTACT FORM =========== -->
                        <section class="p-0">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xl-5 mb-1-6 mb-xl-0 wow fadeIn" data-wow-delay="200ms">
                                        <div class="pe-xl-1-9">
                                            <div class="section-title left mb-1-9">
                                                <span class="sm-title">Contact Us</span>
                                                <h2 class="mb-0 h1 mt-2">Write Us Any Message</h2>
                                            </div>
                                            <p class="mb-1-9">These are the phrases we stay via way of means of in the whole
                                                lot we do. Each brand we build, and we create.</p>
                                            <ul class="social-icon-style3 ps-0">
                                                <li class="me-1"><a target="_blank"
                                                        href="{{ get_setting('facebook_url')->value ?? 'null' }}"><i
                                                            class="fab fa-facebook-f"></i></a></li>
                                                <li class="me-1"><a target="_blank"
                                                        href="{{ get_setting('twitter_url')->value ?? 'null' }}"><i
                                                            class="fab fa-twitter"></i></a></li>
                                                <li class="me-1"><a target="_blank"
                                                        href="{{ get_setting('instagram_url')->value ?? 'null' }}"><i
                                                            class="fab fa-instagram"></i></a></li>
                                                <li class="me-0"><a target="_blank"
                                                        href="{{ get_setting('linkedin_url')->value ?? 'null' }}"><i
                                                            class="fab fa-linkedin-in"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-7 wow fadeIn" data-wow-delay="400ms">
                                        <form class="" action="{{ route('contact.store') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="quform-elements">
                                                <div class="row">

                                                    <!-- Begin Text input element -->
                                                    <div class="col-md-6">
                                                        <div class="quform-element form-group">
                                                            <label for="name">Your Name <span
                                                                    class="quform-required">*</span></label>
                                                            <div class="quform-input">
                                                                <input class="form-control" id="name" type="text"
                                                                    name="name" placeholder="Your name here" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Text input element -->

                                                    <!-- Begin Text input element -->
                                                    <div class="col-md-6">
                                                        <div class="quform-element form-group">
                                                            <label for="email">Your Email <span
                                                                    class="quform-required">*</span></label>
                                                            <div class="quform-input">
                                                                <input class="form-control" id="email" type="email"
                                                                    name="email" placeholder="Your email here" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Text input element -->

                                                    <!-- Begin Text input element -->
                                                    <div class="col-md-6">
                                                        <div class="quform-element form-group">
                                                            <label for="subject">Your Subject <span
                                                                    class="quform-required">*</span></label>
                                                            <div class="quform-input">
                                                                <input class="form-control" id="subject" type="text"
                                                                    name="subject" placeholder="Your subject here"
                                                                    required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Text input element -->

                                                    <!-- Begin Text input element -->
                                                    <div class="col-md-6">
                                                        <div class="quform-element form-group">
                                                            <label for="phone">Contact Number</label>
                                                            <div class="quform-input">
                                                                <input class="form-control" id="phone" type="text"
                                                                    name="phone" placeholder="Your phone here"
                                                                    required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Text input element -->

                                                    <!-- Begin Textarea element -->
                                                    <div class="col-md-12">
                                                        <div class="quform-element form-group">
                                                            <label for="message">Message <span
                                                                    class="quform-required">*</span></label>
                                                            <div class="quform-input">
                                                                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Tell us a few words"
                                                                    required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Textarea element -->

                                                    <!-- Begin Submit button -->
                                                    <div class="col-md-12">
                                                        <div class="quform-submit-inner">
                                                            <button class="btn-style1 border-0" type="submit"><span>Send
                                                                    Message</span></button>
                                                        </div>
                                                        <div class="quform-loading-wrap text-start"><span
                                                                class="quform-loading"></span></div>
                                                    </div>
                                                    <!-- End Submit button -->

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- ============= MAP ================ -->
                        <section class="pb-0">
                            <div class="container-fuild">
                                <iframe class="contact-map" id="gmap_canvas"
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d58144.75383524952!2d88.56496025705981!3d24.37966436014817!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fbefa96a38d031%3A0x10f93a950ed6f410!2sRajshahi!5e0!3m2!1sen!2sbd!4v1710832605416!5m2!1sen!2sbd"
                                    style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </section>
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
                            <div class="row justify-content-center">
                                @foreach ($branches as $branch)
                                    <div class="col-md-4 mt-4">
                                        <div class="card p-3  shadow-lg">
                                            <div class="card-block">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-10">
                                                            <h4 class="card-title font-weight-bold">{{ $branch->branch_name }}</h4>
                                                        </div>
                                                        <div class="col-2 text-right">
                                                            <h4 class="card-title right"><a
                                                                    href="https://www.google.com/search?q=google+translate&amp;oq=google&amp;gs_lcrp=EgZjaHJvbWUqDggAEEUYJxg7GIAEGIoFMg4IABBFGCcYOxiABBiKBTIYCAEQLhhDGIMBGMcBGLEDGNEDGIAEGIoFMgYIAhBFGDwyBggDEEUYQTIGCAQQRRhBMgYIBRAFGEAyBggGEEUYPDIGCAcQRRg80gEHODc0ajBqN6gCCLACAQ&amp;sourceid=chrome&amp;ie=UTF-8">
                                                                    <img src="https://unicarebd.org/mapicon.png"
                                                                        alt="">
                                                                </a></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <h4 class="card-title d-inline">
                                                                <a href="#">
                                                                    <i class="fa-solid fa-phone"></i>
                                                                </a>
                                                            </h4>
                                                            <p class="d-inline">{{ $branch->contact_no }}</p>
                                                        </div>
                                                        @if ($branch->contact_no_optional)
                                                            <div class="col-6 text-left">
                                                                <h4 class="card-title d-inline">
                                                                    <a href="facebook.com">
                                                                        <i class="fa-solid fa-phone"></i>
                                                                    </a>
                                                                </h4>
                                                                <p class="d-inline">{{ $branch->contact_no_optional }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6 col-lg-3 mb-4">
                                        <div class="branch-card text-center shadow-sm border-0 p-3 rounded-4">
                                            <h6 class="fw-bold mb-3">{{ $branch->branch_name }}</h6>
                                            <div class="d-flex justify-content-between align-items-center mb-2 px-3">
                                                <a href="tel:{{ $branch->contact_no }}"
                                                    class="text-decoration-none text-dark d-flex align-items-center">
                                                    <i class="fas fa-phone-alt text-primary me-2"></i>
                                                    {{ $branch->contact_no }}
                                                </a>
                                                <i class="fas fa-map-marker-alt text-danger fs-5"></i>
                                            </div>
                                            @if ($branch->contact_no_optional)
                                                <div class="d-flex justify-content-between align-items-center px-3">
                                                    <a href="tel:{{ $branch->contact_no_optional }}"
                                                        class="text-decoration-none text-dark d-flex align-items-center">
                                                        <i class="fas fa-phone-alt text-primary me-2"></i>
                                                        {{ $branch->contact_no_optional }}
                                                    </a>
                                                    <i class="fas fa-map-marker-alt text-danger fs-5"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div> --}}
                                @endforeach
                            </div>

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('frontend-js')
    @endpush
@endsection
