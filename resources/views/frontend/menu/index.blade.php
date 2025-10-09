@extends('layouts.frontend.app', [$pageTitle => $page->title])
@section('content')
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/dashboard.css') }}">
      <style>
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
        $colors = [
            '#f2f2f2',  // Light Gray
            '#e6f7ff',  // Light Blue
            '#fff0f5',  // Lavender Blush
            '#fef9e7',  // Light Yellow
            '#e8f5e9',  // Light Green
            '#f3e5f5',  // Light Purple
            '#fff3e0',  // Light Orange
            '#f1f8e9',  // Light Lime
            '#e0f7fa',  // Light Cyan
            '#ede7f6',  // Light Indigo
            '#fce4ec',  // Light Pink
            '#f9fbe7',  // Light Lime-Yellow
            '#fbe9e7',  // Light Coral
            '#e0f2f1',  // Aqua Light
            '#f3f3f3',  // Extra Light Gray
        ];
    @endphp
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
                                <div class="mt-3 about-description">{!! $about->description !!}</div>
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
                                <p>Email: {{ get_setting('email')->value ?? '' }}</p>
                                <p>Phone: +8801316017328</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @elseif($page->page_slug == 'admission-info')
        <section class="py-5">
            <div class="container">
                <div class="card shadow-sm border-0 p-4">
                    <h2 class="fw-bold card-title mb-3 text-center text-light bg-danger p-2">ভর্তি তথ্য বিস্তারিত</h2>

                    <div class="accordion" id="admissionAccordion">
                        @foreach ($admissioninfos as $info)
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="heading{{ $info->id }}">
                                    <button class="accordion-button collapsed fw-bold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $info->id }}"
                                        aria-expanded="false" aria-controls="collapse{{ $info->id }}">
                                        {{ $info->institute_name }} (সেশন: {{ $info->session }})
                                    </button>
                                </h2>

                                <div id="collapse{{ $info->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $info->id }}" data-bs-parent="#admissionAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <img src="{{ !empty($info->image) ? url($info->image) : 'https://via.placeholder.com/600x400' }}"
                                                    class="img-fluid rounded shadow-sm mb-3"
                                                    alt="{{ $info->institute_name }}">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>ইউনিট</th>
                                                        <th>ডেসক্রিপশন</th>
                                                        <th>নোট</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($info->units as $unit)
                                                        @php
                                                            shuffle($colors);
                                                        @endphp
                                                        <tr>
                                                            <td style="background-color: {{ $colors[0] }}">{{ $unit->unit }}</td>
                                                            <td style="background-color: {{ $colors[1] }}">{{ $unit->description ?? '-' }}</td>
                                                            <td style="background-color: {{ $colors[2] }}">{{ $unit->note ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <p><b>সেশন:</b> {{ $info->session }}</p>
                                        <p><b>ফর্ম শুরুর তারিখ:</b> {{ $info->form_start_date }}</p>
                                        <p><b>আবেদনের শেষ তারিখ:</b> {{ $info->application_last_date }}</p>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>ইউনিট</th>
                                                        <th>পরীক্ষার তারিখ</th>
                                                        <th>পরীক্ষার সময়</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($info->units as $unit)
                                                        @php
                                                            shuffle($colors);
                                                        @endphp
                                                        <tr>
                                                            <td style="background-color: {{ $colors[0] }}">{{ $unit->unit }}</td>
                                                            <td style="background-color: {{ $colors[1] }}">{{ $unit->exam_date ?? '-' }}</td>
                                                            <td style="background-color: {{ $colors[2] }}">{{ $unit->exam_time ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Subject Mark Distribution Show -->
                                        <h4 class="fw-bold mt-4 mb-3">Mark Distribution</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>ইউনিট</th>
                                                        <th>সাবজেক্ট</th>
                                                        <th>মার্ক</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($info->units as $unit)
                                                        @if ($unit->subjects->count() > 0)
                                                            @foreach ($unit->subjects as $key => $subject)
                                                                <tr>
                                                                    {{-- ইউনিট নাম শুধু প্রথম সাবজেক্টের সময় দেখাবে --}}
                                                                    @if ($key == 0)
                                                                        <td rowspan="{{ $unit->subjects->count() }}" style="vertical-align: middle; background-color: {{ $colors[0] ?? '#e9ecef' }}">
                                                                            {{ $unit->unit }}
                                                                        </td>
                                                                    @endif
                                                                    <td style="background-color: {{ $colors[1] ?? '#f8f9fa' }}">
                                                                        {{ $subject->subject }}
                                                                    </td>
                                                                    <td style="background-color: {{ $colors[2] ?? '#f8f9fa' }}">
                                                                        {{ $subject->mark }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td style="background-color: {{ $colors[0] ?? '#e9ecef' }}">{{ $unit->unit }}</td>
                                                                <td colspan="2" class="text-muted">No subjects found</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </section>
    @elseif($page->page_slug == 'our-branch')
        <section class="py-5">
            <div class="container">
                <div class="card shadow-sm border-0 p-4">
                    <h2 class="fw-bold card-title mb-3 text-center text-light bg-danger p-2">শাখা সমূহ</h2>
                    <div class="row justify-content-center">
                        @foreach ($branches as $branch)
                            <div class="col-md-4 mt-4 mb-4">
                                <div class="card border-0 p-3  shadow-lg">
                                    <div class="card-block">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h4 class="card-title font-weight-bold">
                                                        {{ $branch->branch_name ?? '' }}</h4>
                                                </div>
                                                <div class="col-2 text-right">
                                                    <h4 class="card-title right"><a
                                                            href="https://www.google.com/search?q=google+translate&amp;oq=google&amp;gs_lcrp=EgZjaHJvbWUqDggAEEUYJxg7GIAEGIoFMg4IABBFGCcYOxiABBiKBTIYCAEQLhhDGIMBGMcBGLEDGNEDGIAEGIoFMgYIAhBFGDwyBggDEEUYQTIGCAQQRRhBMgYIBRAFGEAyBggGEEUYPDIGCAcQRRg80gEHODc0ajBqN6gCCLACAQ&amp;sourceid=chrome&amp;ie=UTF-8">
                                                            <img src="{{ asset('upload/mapicon.png') }}" alt="">
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
                                                    <p class="d-inline">{{ $branch->contact_no ?? '' }}</p>
                                                </div>
                                                @if ($branch->contact_no_optional)
                                                    <div class="col-6 text-left">
                                                        <h4 class="card-title d-inline">
                                                            <a href="facebook.com">
                                                                <i class="fa-solid fa-phone"></i>
                                                            </a>
                                                        </h4>
                                                        <p class="d-inline">{{ $branch->contact_no_optional ?? '' }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @elseif($page->page_slug == 'program-list')
        <section class="py-5">
            <div class="container">
                <div class="card shadow-sm border-0">
                    <h2 class="fw-bold card-title mb-3 text-center">আমাদের কোর্স সমূহ</h2>

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
                                            <div class="about-description reveal mt-3" style="max-height: 400px; overflow-y: auto;">
                                                {!! $program->description !!}
                                            </div>
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
    @elseif($page->page_slug == 'demo-exam')
        <section class="py-5">
            <div class="container">
                <div class="card shadow-sm border-0 p-4">
                    <h2 class="fw-bold card-title mb-3 text-center text-success font-weight-bold">ডেমো এক্সাম দিন</h2>
                    <div class="row mt-2">
                        <div class="col-md-12 mb-3 mb-md-0">
                            <!-- MCQ Content -->
                            <div class="section active" id="selection-section">
                                <div class="row mb-4 g-3">
                                    <div class="col-lg-12">
                                        <div class="exam-card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-book-open me-2"></i>MCQ Exam Preparation</span>
                                                    <span class="badge bg-light text-primary" id="step-badge">Step 1 of
                                                        5</span>
                                                </div>
                                            </div>

                                            @php
                                                $currentStep = 1;

                                                if ($selectedAdmission && !$selectedDepartment) {
                                                    $currentStep = 2;
                                                } elseif ($selectedDepartment && !$selectedSubject) {
                                                    $currentStep = 3;
                                                } elseif ($selectedSubject && !$selectedTopic) {
                                                    $currentStep = 4;
                                                } elseif ($selectedTopic) {
                                                    $currentStep = 5;
                                                }

                                                // Map step to percentage
                                                $progressPercent = match ($currentStep) {
                                                    1 => 20,
                                                    2 => 40,
                                                    3 => 60,
                                                    4 => 80,
                                                    5 => 100,
                                                    default => 20,
                                                };
                                            @endphp

                                            <div class="progress-container mb-2">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                        id="progress-bar"
                                                        style="width: {{ $progressPercent }}%; padding: 0.25rem 0.5rem;">
                                                        {{ $progressPercent }}%
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="card-body p-4">
                                                <!-- Step Indicator -->
                                                <div class="step-indicator mb-3">
                                                    <div class="step {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'completed' : '') }}"
                                                        id="step-1">1</div>
                                                    <div class="step {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'completed' : '') }}"
                                                        id="step-2">2</div>
                                                    <div class="step {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'completed' : '') }}"
                                                        id="step-3">3</div>
                                                    <div class="step {{ $currentStep == 4 ? 'active' : ($currentStep > 4 ? 'completed' : '') }}"
                                                        id="step-4">4</div>
                                                    <div class="step {{ $currentStep == 5 ? 'active' : ($currentStep > 5 ? 'completed' : '') }}"
                                                        id="step-4">5</div>
                                                </div>

                                                <form action="{{ route('menu.page', $page->page_slug) }}" id="exam-form"
                                                    method="GET">
                                                    <!-- Step 1: Admission Selection -->
                                                    @if (!$selectedAdmission)
                                                        <div class="step-content active" id="step-admission">
                                                            <h5 class="step-title">
                                                                <i class="fas fa-university me-2"></i> Select Admission
                                                            </h5>
                                                            <div class="mb-4">
                                                                @foreach ($admissions as $admission)
                                                                    <label for="admission{{ $admission->id }}"
                                                                        class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                                        style="cursor: pointer;"
                                                                        onclick="document.getElementById('admission{{ $admission->id }}').checked = true; this.closest('form').submit();">

                                                                        <img src="{{ !empty($admission->image) ? url('upload/admission/' . $admission->image) : url('upload/mcq.png') }}"
                                                                            alt="ICON" class="me-2"
                                                                            style="width:40px;height:40px;object-fit:cover;">

                                                                        <input type="radio" name="admission"
                                                                            id="admission{{ $admission->id }}"
                                                                            value="{{ $admission->id }}" class="d-none">
                                                                        {{-- hide radio --}}

                                                                        <span>{{ $admission->name }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Step 2: Department Selection -->
                                                    @if ($selectedAdmission && !$selectedDepartment)
                                                        <div class="step-content active" id="step-department">
                                                            <h5 class="step-title"><i
                                                                    class="fas fa-building me-2"></i>Select Department</h5>
                                                            <input type="hidden" name="admission"
                                                                value="{{ $selectedAdmission }}">
                                                            <div class="mb-4">
                                                                @foreach ($departments as $department)
                                                                    <label for="department{{ $department->id }}"
                                                                        class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                                        style="cursor: pointer;"
                                                                        onclick="document.getElementById('department{{ $department->id }}').checked = true; this.closest('form').submit();">
                                                                        <img src="{{ !empty($department->image) ? url('upload/department/' . $department->image) : url('upload/mcq.png') }}"
                                                                            alt="ICON">
                                                                        <input type="radio" name="department"
                                                                            id="department{{ $department->id }}"
                                                                            value="{{ $department->id }}"
                                                                            onchange="this.form.submit()">
                                                                        <span>{{ $department->name }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Step 3: Subject Selection -->
                                                    @if ($selectedDepartment && !$selectedSubject)
                                                        <div class="step-content active" id="step-subject">
                                                            <h5 class="step-title"><i class="fas fa-book me-2"></i>Select
                                                                Subject</h5>
                                                            <input type="hidden" name="admission"
                                                                value="{{ $selectedAdmission }}">
                                                            <input type="hidden" name="department"
                                                                value="{{ $selectedDepartment }}">
                                                            <div class="mb-4">
                                                                @foreach ($subjects as $subject)
                                                                    <label for="subject{{ $subject->id }}"
                                                                        class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                                        style="cursor: pointer;"
                                                                        onclick="document.getElementById('subject{{ $subject->id }}').checked = true; this.closest('form').submit();">
                                                                        <img src="{{ !empty($subject->image) ? url('upload/subject/' . $subject->image) : url('upload/mcq.png') }}"
                                                                            alt="ICON">
                                                                        <input type="radio" name="subject"
                                                                            id="subject{{ $subject->id }}"
                                                                            value="{{ $subject->id }}"
                                                                            onchange="this.form.submit()">
                                                                        <span>{{ $subject->name }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Step 4: Topic Selection -->
                                                    @if ($selectedSubject && !$selectedTopic)
                                                        <div class="step-content active" id="step-topic">
                                                            <h5 class="step-title"><i class="fas fa-tag me-2"></i>Select
                                                                Topic</h5>
                                                            <input type="hidden" name="admission"
                                                                value="{{ $selectedAdmission }}">
                                                            <input type="hidden" name="department"
                                                                value="{{ $selectedDepartment }}">
                                                            <input type="hidden" name="subject"
                                                                value="{{ $selectedSubject }}">
                                                            <div class="mb-4">
                                                                @foreach ($topics as $topic)
                                                                    <label for="topic{{ $topic->id }}"
                                                                        class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                                        style="cursor: pointer;"
                                                                        onclick="document.getElementById('topic{{ $topic->id }}').checked = true; this.closest('form').submit();">
                                                                        <img src="{{ !empty($topic->image) ? url('upload/topic/' . $topic->image) : url('upload/mcq.png') }}"
                                                                            alt="ICON">
                                                                        <input type="radio" name="topic"
                                                                            id="topic{{ $topic->id }}"
                                                                            value="{{ $topic->id }}"
                                                                            onchange="this.form.submit()">
                                                                        <label
                                                                            for="topic{{ $topic->id }}">{{ $topic->name }}</label>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($selectedTopic && $mcqs->isEmpty())
                                                        <div class="card border-success shadow-sm mb-4">
                                                            <div class="card-header bg-success text-white fw-bold">
                                                                <i class="fa fa-info-circle me-2"></i> পরীক্ষার নোটিশ
                                                            </div>
                                                            <div class="card-body">
                                                                <!-- পরীক্ষার বিষয়সমূহ -->
                                                                <div class="d-flex align-items-start mb-3">
                                                                    <i class="fa fa-book text-success fs-4 me-3"></i>
                                                                    <div>
                                                                        <h6 class="fw-bold mb-1">পরীক্ষার বিষয়সমূহ</h6>
                                                                        <p class="mb-0 small">
                                                                            নিচের তালিকা থেকে আপনার গপছন্দ বিষয় নির্বাচন
                                                                            করুন এবং পরীক্ষা শুরু করুন।
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- পরীক্ষার সময় -->
                                                                <div class="d-flex align-items-start mb-3">
                                                                    <i class="fa fa-clock text-success fs-4 me-3"></i>
                                                                    <div>
                                                                        <h6 class="fw-bold mb-1">পরীক্ষার সময়</h6>
                                                                        <p class="mb-0 small">
                                                                            প্রতিটি বিষয়ের জন্য আলাদা পরীক্ষার সময় নির্ধারিত
                                                                            আছে যা বিষয়ের পাশে দেখানো
                                                                            হয়েছে।
                                                                            নির্দিষ্ট সময়ের মধ্যে আপনাকে পরীক্ষা সম্পন্ন
                                                                            করতে হবে।
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- পরীক্ষার মার্ক -->
                                                                <div class="d-flex align-items-start mb-3">
                                                                    <i class="fa fa-star text-success fs-4 me-3"></i>
                                                                    <div>
                                                                        <h6 class="fw-bold mb-1">পরীক্ষার মার্ক</h6>
                                                                        <p class="mb-0 small">
                                                                            প্রতিটি পরীক্ষার জন্য সর্বোচ্চ মার্ক নির্ধারিত
                                                                            আছে যা বিষয়ের পাশে দেখানো
                                                                            হয়েছে।
                                                                            পরীক্ষার ফলাফল এই মার্ক অনুযায়ী নির্ধারিত হবে।
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- পরীক্ষার ফি -->
                                                                <div class="d-flex align-items-start mb-3">
                                                                    <i class="fa fa-money-bill text-success fs-4 me-3"></i>
                                                                    <div>
                                                                        <h6 class="fw-bold mb-1">পরীক্ষার ফি</h6>
                                                                        <p class="mb-0 small text-danger">
                                                                            এখনই বাটনে ক্লিক করলে পরীক্ষা শুরু হবে এবং আপনার
                                                                            ওয়ালেট থেকে স্বয়ংক্রিয়ভাবে
                                                                            পরীক্ষার ফি কেটে নেয়া হবে।
                                                                        </p>
                                                                        <p class="mb-0 small">
                                                                            প্রতিটি বিষয়ের জন্য আলাদা ফি নির্ধারিত আছে যা
                                                                            বিষয়ের পাশে দেখানো হয়েছে।
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- Warning -->
                                                                <div class="alert alert-warning mt-3 small mb-0">
                                                                    দয়া করে নিশ্চিত হয়ে নিন যে আপনার ওয়ালেটে পর্যাপ্ত
                                                                    ব্যালেন্স আছে।
                                                                    অন্যথায় পরীক্ষা শুরু করা সম্ভব হবে না।
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Subject Card -->
                                                        <div class="card shadow-lg border-success mb-4">
                                                            <div class="card-body">
                                                                <h6 class="fw-bold text-success mb-3 text-center">সিলেক্টেড
                                                                    টপিক → MCQ শুরু</h6>
                                                                <div class="d-flex align-items-center border rounded p-3">
                                                                    <img src="{{ !empty($selectedTopic->image) ? url('upload/topic/' . $selectedTopic->image) : url('upload/mcq.png') }}"
                                                                        class="rounded-circle me-3" width="60"
                                                                        alt="topic icon">
                                                                    <div class="flex-grow-1">
                                                                        @php
                                                                            $selectedTopicData = $topics
                                                                                ->where('id', $selectedTopic)
                                                                                ->first();
                                                                        @endphp

                                                                        @if ($selectedTopicData)
                                                                            <h6 class="mb-1">
                                                                                {{ $selectedTopicData->name }}</h6>
                                                                            <small class="d-block">সময়:
                                                                                {{ $selectedTopicData->exam_duration }}
                                                                                মিনিট</small>
                                                                            <small class="d-block">মার্ক:
                                                                                {{ $selectedTopicData->exam_mark }}</small>
                                                                            <small class="d-block">পরীক্ষার ফি:
                                                                                {{ number_format($selectedTopicData->fee, 2) }}
                                                                                টাকা</small>
                                                                        @else
                                                                            <h6 class="mb-1">কোনো টপিক সিলেক্ট করা হয়নি
                                                                            </h6>
                                                                        @endif
                                                                    </div>
                                                                    <div class="d-flex flex-wrap gap-2">
                                                                        <a href="{{ route('menu.page', [
                                                                            'url' => $page->page_slug,
                                                                            'admission' => $selectedAdmission,
                                                                            'department' => $selectedDepartment,
                                                                            'subject' => $selectedSubject,
                                                                            'topic' => $selectedTopic,
                                                                            'study' => 1,
                                                                        ]) }}"
                                                                            class="btn btn-danger btn-sm flex-fill text-center">
                                                                            📖 স্টাডি
                                                                        </a>
                                                                        <a href="{{ route('menu.page', [
                                                                            'url' => $page->page_slug,
                                                                            'admission' => $selectedAdmission,
                                                                            'department' => $selectedDepartment,
                                                                            'subject' => $selectedSubject,
                                                                            'topic' => $selectedTopic,
                                                                            'exam' => 1,
                                                                        ]) }}"
                                                                            class="btn btn-success btn-sm flex-fill text-center">📝
                                                                            এক্সাম</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                            </div>
    @endif
    </form>
    @if ($examStart && $mcqs->isNotEmpty())
        <!-- ✅ Exam Header with Countdown -->
        <div class="card shadow-sm border-success mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-clock me-2"></i> পরীক্ষার সময় চলছে</span>
                <span id="exam-timer" class="fw-bold">00:00</span>
            </div>
        </div>

        <!-- ✅ MCQ Form -->
        <form action="{{ route('user.exam.submit') }}" method="POST" id="mcq-form">
            @csrf
            <input type="hidden" name="time_taken" id="time_taken">
            <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
            <input type="hidden" name="department" value="{{ $selectedDepartment }}">
            <input type="hidden" name="subject" value="{{ $selectedSubject }}">
            <input type="hidden" name="topic" value="{{ $selectedTopic }}">

            @foreach ($mcqs as $index => $mcq)
                <div class="question-container" data-question="{{ $index + 1 }}"
                    @if ($index != 0) style="display:none;" @endif>
                    <div class="d-flex align-items-center mb-4">
                        <div class="question-number">{{ $index + 1 }}</div>
                        <h5 class="m-0">{{ $mcq->question }}</h5>
                    </div>
                    <div class="options-container">
                        @foreach ($mcq->answers as $answer)
                            <div class="option-item p-2 rounded mb-2" style="cursor:pointer;"
                                data-correct="{{ $answer->is_correct ? '1' : '0' }}">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-2" type="radio"
                                        name="answers[{{ $mcq->id }}]" id="option{{ $answer->id }}"
                                        value="{{ $answer->id }}" required hidden>
                                    <label class="form-check-label flex-grow-1 mb-0" for="option{{ $answer->id }}">
                                        {{ $answer->answer }}
                                    </label>
                                    <span class="feedback ms-2" style="display:none;"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Navigation Buttons -->
            <div class="navigation-buttons mt-4">
                <button type="button" class="btn btn-outline-secondary" id="prev-btn" disabled>
                    <i class="fas fa-arrow-left me-2"></i>Previous
                </button>
                <button type="button" class="btn btn-primary" id="next-btn">
                    Next<i class="fas fa-arrow-right ms-2"></i>
                </button>
                <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                    Submit Exam<i class="fas fa-check-circle ms-2"></i>
                </button>
            </div>
        </form>
    @endif

    {{-- Study Mode --}}
    @if ($studyMode && $mcqs->isNotEmpty())
        <div class="study-container">
            @foreach ($mcqs as $index => $mcq)
                <div class="card shadow-sm mb-4">

                    {{-- Card Header with color and shadow --}}
                    <div class="card-header bg-danger text-white shadow-sm">
                        প্রশ্ন নং {{ $index + 1 }}
                    </div>

                    <div class="card-body">
                        <p class="mb-3"><strong>প্রশ্ন:</strong> {{ $mcq->question }}</p>

                        <div class="options mb-3">
                            @foreach ($mcq->answers->take(4) as $answer)
                                <p class="mb-1 {{ $answer->is_correct ? 'text-success fw-bold' : '' }}">
                                    {{ chr(65 + $loop->index) }}. {{ $answer->answer }}
                                </p>
                            @endforeach
                        </div>

                        {{-- সঠিক উত্তর দেখানো --}}
                        @php
                            $correctAnswer = $mcq->answers->firstWhere('is_correct', 1);
                        @endphp
                        @if ($correctAnswer)
                            <p class="mt-2 text-success fw-bold"><strong>সঠিক উত্তর:</strong> {{ $correctAnswer->answer }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    </div>
    </div>
    </div>
    </div>
    </div>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'নোটিশ',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    </div>
    </div>
    </div>
    </div>
    </section>
@elseif($page->page_slug == 'online-quiz')
    <section class="py-5">
        <div class="container">
            <!-- ✅ Banner Image -->
            <div class="text-center mb-4">
                <img src="{{ !empty($page->image) ? url('upload/page/' . $page->image) : url('upload/page-title.jpg') }}"
                    alt="Quiz Banner" class="img-fluid rounded shadow-sm">
            </div>
            @auth
                <div class="card shadow-sm border-0 p-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 mt-3">
                            <div class="card shadow-lg border-0 text-center">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-2 text-primary fw-bold">আপনাকে স্বাগতম</h5>
                                    <div class="me-sm-auto w-100 w-sm-auto">
                                        <a href="{{ route('online.quiz') }}" class="btn btn-primary w-sm-auto"
                                            role="button">
                                            কুইজ শুরু করুন
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 p-4">
                    <h2 class="fw-bold card-title mb-3 text-center text-success">অনলাইন কুইজ এক্সাম দিন</h2>
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 mt-3">
                            <div class="card shadow-lg border-0 text-center">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-2 text-danger fw-bold">অনুগ্রহ করে লগ ইন করুন</h5>
                                    <p class="card-text text-muted mb-4">
                                        যেকোনো কুইজে অংশগ্রহণের আগে আপনাকে লগ ইন অথবা নিবন্ধন করতে হবে।
                                    </p>

                                    <!-- Buttons: responsive, left = Login, right = Register -->
                                    <div class="d-flex flex-column flex-sm-row align-items-center gap-2">
                                        <!-- Left side (login) -->
                                        <div class="me-sm-auto w-100 w-sm-auto">
                                            <a href="/login" class="btn btn-primary w-100 w-sm-auto" role="button">
                                                লগ ইন
                                            </a>
                                        </div>

                                        <!-- Right side (register) -->
                                        <div class="w-100 w-sm-auto">
                                            <a href="/register" class="btn btn-success w-100 w-sm-auto" role="button">
                                                রেজিস্টার
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </section>
@else
    <section class="py-5">
        <div class="container">
            <div class="card shadow-sm border-0 p-4">
                <h2 class="fw-bold card-title mb-3">{{ $page->title ?? 'Page Title' }}</h2>
                <p class="card-text">This page does not have specific content yet. You can update it from the admin panel.
                </p>
            </div>
        </div>
    </section>
    @endif
    <script>
        let currentQuestion = 0;
        const questions = document.querySelectorAll('.question-container');
        const totalQuestions = questions.length;
        const progressBar = document.getElementById('question-progress');

        document.getElementById('next-btn').addEventListener('click', function() {
            if (currentQuestion < totalQuestions - 1) {
                questions[currentQuestion].style.display = 'none';
                currentQuestion++;
                questions[currentQuestion].style.display = 'block';
                updateProgress();
            }
            toggleButtons();
        });

        document.getElementById('prev-btn').addEventListener('click', function() {
            if (currentQuestion > 0) {
                questions[currentQuestion].style.display = 'none';
                currentQuestion--;
                questions[currentQuestion].style.display = 'block';
                updateProgress();
            }
            toggleButtons();
        });

        function updateProgress() {
            const percent = ((currentQuestion + 1) / totalQuestions) * 100;
            progressBar.style.width = percent + '%';
            document.getElementById('current-question').innerText = currentQuestion + 1;
        }

        function toggleButtons() {
            document.getElementById('prev-btn').disabled = currentQuestion === 0;
            document.getElementById('next-btn').style.display = (currentQuestion === totalQuestions - 1) ? 'none' :
                'inline-block';
            document.getElementById('submit-btn').style.display = (currentQuestion === totalQuestions - 1) ?
                'inline-block' : 'none';
        }
    </script>
    <script>
        document.querySelectorAll('.option-item').forEach(option => {
            option.addEventListener('click', function() {
                if (this.dataset.answered === "1") return;

                const isCorrect = this.dataset.correct === "1";
                const feedback = this.querySelector('.feedback');
                const input = this.querySelector('input[type="radio"]');
                const questionId = input.name.match(/\d+/)[0]; // gets MCQ id
                const answerId = input.value;

                // Store answer in localStorage
                let savedAnswers = JSON.parse(localStorage.getItem('mcq_answers')) || {};
                savedAnswers[questionId] = answerId;
                localStorage.setItem('mcq_answers', JSON.stringify(savedAnswers));

                // Feedback logic
                if (isCorrect) {
                    this.classList.add('bg-success', 'text-white');
                    feedback.innerHTML = '✔ Correct';
                } else {
                    this.classList.add('bg-danger', 'text-white');
                    feedback.innerHTML = '✖ Wrong';
                }
                feedback.style.display = 'inline';

                input.checked = true;
                this.dataset.answered = "1";

                // Disable other options
                const siblings = this.closest('.options-container').querySelectorAll('.option-item');
                siblings.forEach(sib => {
                    sib.dataset.answered = "1";
                    sib.style.pointerEvents = "none";
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let duration = {{ $selectedTopicData->exam_duration ?? 10 }} * 60; // total seconds
            let startTime = Date.now(); // exam start timestamp
            let timerDisplay = document.getElementById("exam-timer");

            function updateTimer() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;
                timerDisplay.textContent =
                    `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;

                if (duration <= 0) {
                    clearInterval(timerInterval);
                    alert("⏳ Time's up! Your exam will be submitted automatically.");

                    // Calculate time taken in minutes
                    let endTime = Date.now();
                    let diffInSeconds = Math.floor((endTime - startTime) / 1000);
                    let diffInMinutes = (diffInSeconds / 60).toFixed(2); // fractional minutes
                    document.getElementById("time_taken").value = diffInMinutes;

                    document.getElementById("mcq-form").submit();
                }
                duration--;
            }

            let timerInterval = setInterval(updateTimer, 1000);

            document.getElementById("mcq-form").addEventListener("submit", function() {
                let endTime = Date.now();
                let diffInSeconds = Math.floor((endTime - startTime) / 1000);
                let diffInMinutes = (diffInSeconds / 60).toFixed(2); // fractional minutes
                document.getElementById("time_taken").value = diffInMinutes;
            });
        });
    </script>
@endsection
