@extends('layouts.frontend.app')
@section('content')
    <!-- Hero Image Slider -->
    <section id="heroSlider" class="carousel slide" data-bs-ride="carousel">

        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            @foreach ($sliders as $slider)
                <div class="carousel-item active">
                    <img src="{{ !empty($slider->image) ? url('upload/slider/' . $slider->image) : url('upload/no_image.jpg') }}"
                        class="d-block w-100 img-fluid" alt="Slide 1">
                </div>
            @endforeach
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </section>


    <!-- Dynamic About Section -->
    <section class="py-5 bg-light" id="about">
        <div class="container">
            @foreach ($abouts as $about)
                <div class="row align-items-center mb-5">
                    <!-- Left: Image -->
                    <div class="col-md-6">
                        <img src="{{ !empty($about->image) ? url('upload/about/' . $about->image) : url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') }}"
                            alt="{{ $about->title }}" class="img-fluid rounded shadow">
                    </div>

                    <!-- Right: Content -->
                    <div class="col-md-6 mt-3">
                        <h2 class="fw-bold">{{ $about->title }}</h2>
                        <p class="mt-3">{!! $about->description !!}</p>

                        @if ($about->video_link && $about->video_link != '#')
                            <a href="{{ $about->video_link }}" class="btn btn-primary mt-3">ভিডিও দেখুন</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>


    <!-- MCQ Admission Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            {{-- <h4 class="mb-4 fw-bold reveal">MCQ এডমিশন এক প্লাটফর্মে</h4> --}}
            <div class="row g-4">

                @php
                    // Available colors for random assignment
                    $availableColors = [
                        'bg-primary text-white',
                        'bg-success text-white',
                        'bg-danger text-white',
                        'bg-warning text-dark',
                        'bg-info text-dark',
                        'bg-secondary text-white',
                        'bg-dark text-white',
                        'bg-light text-dark border',
                    ];
                @endphp

                @foreach ($admissions as $admission)
                    @php
                        // Random color for each card
                        $colorClass = $availableColors[array_rand($availableColors)];
                    @endphp

                    <div class="col-md-3 col-sm-6">
                        <div class="p-4 rounded shadow-sm h-100 {{ $colorClass }} reveal">
                            <h5 class="mb-0">{{ $admission->name }}</h5>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- Program Section with Slick Carousel -->
    <section class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0">
                <h2 class="fw-bold card-title mb-3 text-center">Program List</h2>

                @if ($programs->count() > 0)
                    <!-- Slick Slider Container -->
                    <div class="program-slider">
                        @foreach ($programs as $program)
                            <div class="px-2"> <!-- Optional spacing -->
                                <div class="card h-100 border-0 shadow-sm">
                                    <img src="{{ $program->image ? url('upload/program/' . $program->image) : url('upload/no_image.jpg') }}"
                                            class="img-fluid mb-3" alt="{{ $program->name }}">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $program->name }}</h4>
                                        <p>
                                            {!! Str::limit($program->description, 1000) !!}
                                        </p>
                                        <a href="{{ route('program.show', $program->slug) }}"
                                            class="btn btn-primary mt-3 w-100 text-center">
                                            বিস্তারিত দেখুন
                                        </a>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No programs found.</p>
                @endif

            </div>
        </div>
    </section>



    <!-- Teacher Section with Slick Carousel -->
    <section class="py-5 bg-light position-relative">
        <div class="container text-center">
            <h4 class="mb-4 fw-bold">আমাদের মেন্টর</h4>

            @if ($teachers->count() > 0)
                <div class="teacher-slider">
                    @foreach ($teachers as $teacher)
                        <div class="px-2">
                            <div class="card shadow rounded border-0 h-100">
                                <img src="{{ asset('upload/teacher/' . $teacher->image) }}" class="card-img-top"
                                    alt="{{ $teacher->name }}">
                                <div class="card-body">
                                    <h5 class="card-title mb-1">{{ $teacher->name }}</h5>
                                    <p class="text-muted mb-1">{{ $teacher->designation }}</p>
                                    @if ($teacher->versity)
                                        <p class="text-success mb-1">{{ $teacher->versity }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>


    @php
        $colors = ['text-primary', 'text-success', 'text-warning', 'text-danger'];
    @endphp

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4 reveal">কেন MCQ Admission বেছে নিবেন?</h2>
            <div class="row g-4">
                @foreach ($features as $index => $feature)
                    <div class="col-md-3">
                        <div class="p-4 border rounded shadow-sm h-100 reveal">
                            <i class="{{ $feature->icon }} {{ $colors[$index % count($colors)] }} fa-2x mb-3"></i>
                            <h5>{{ $feature->title }}</h5>
                            <p>{{ $feature->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <!-- Counter Section -->
    <section class="py-5 text-white" style="background:#1d3557;">
        <div class="container text-center">
            <div class="row g-4">
                @foreach ($counters as $counter)
                    <div class="col-md-3 reveal">
                        <h2 class="counter" data-target="{{ $counter->counter_no ?? '0' }}">
                            {{ $counter->counter_no ?? '0' }}</h2>
                        <p>{{ $counter->title ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Students Section with Bootstrap Card & Slick Carousel -->
    <section class="py-5 bg-light position-relative">
        <div class="container">
            <h2 class="mb-5 text-center text-primary fw-bold">শিক্ষার্থীদের মতামত</h2>

            @if ($students->count() > 0)
                <div class="student-slider">
                    @foreach ($students as $student)
                        <div class="px-2">
                            <div class="card border-0 shadow-sm h-100 text-center">
                                <div class="card-body p-4">
                                    <!-- Profile Image -->
                                    <img src="{{ !empty($student->image) ? url('upload/student/' . $student->image) : url('upload/no_image.jpg') }}"
                                        alt="{{ $student->name }}" class="rounded-circle mb-3 mx-auto"
                                        style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #4a90e2;">

                                    <!-- Student Info -->
                                    <h5 class="card-title text-dark fw-semibold mb-1">{{ $student->name }}</h5>

                                    @if ($student->versity)
                                        <p class="text-success small mb-2">{{ $student->versity }}</p>
                                    @endif

                                    <p class="card-text fst-italic text-muted small">{!! $student->description !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>


    <!-- Call to Action -->
    <section class="py-5 text-center text-white" style="background: linear-gradient(135deg, #6f42c1, #007bff);">
        <div class="container reveal">
            <h2>তোমার ভর্তি পরীক্ষার যাত্রা শুরু করো আজই</h2>
            <p class="mb-4">সেরা শিক্ষকদের গাইডলাইন ও স্মার্ট প্র্যাকটিস টুলস একসাথে।</p>
            <a href="#" class="btn btn-light btn-lg">এখনই রেজিস্ট্রেশন করুন</a>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter container text-center reveal">
        <h3>Subscribe Our Newsletter</h3>
        <p>Subscribed to our newsletter to get regular update about our courses.</p>
        <form class="d-flex justify-content-center mt-3">
            <input type="email" class="form-control w-50" placeholder="Your email here...">
            <button type="submit" class="btn btn-gradient">Subscribe</button>
        </form>
    </section>
@endsection
