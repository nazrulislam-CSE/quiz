@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />
        {{-- ============ CUSTOM STYLES ============ --}}
        <style>
            .cd-hero {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
                border-radius: 18px;
                padding: 28px 26px;
                color: #fff;
                position: relative;
                overflow: hidden;
            }

            .cd-hero::after {
                content: '';
                position: absolute;
                top: -50px;
                right: -50px;
                width: 200px;
                height: 200px;
                background: rgba(255, 255, 255, .12);
                border-radius: 50%;
            }

            .cd-hero h2 {
                font-weight: 700;
                margin-bottom: 8px;
                line-height: 1.3;
                position: relative;
                z-index: 1;
            }

            .cd-breadcrumb {
                font-size: 13px;
                opacity: .9;
                margin-bottom: 12px;
            }

            .cd-breadcrumb a {
                color: #fff;
                text-decoration: none;
                opacity: .85;
            }

            .cd-breadcrumb a:hover {
                opacity: 1;
                text-decoration: underline;
            }

            .cd-badge {
                display: inline-block;
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                margin-right: 6px;
                margin-bottom: 6px;
            }

            .cd-badge.light {
                background: rgba(255, 255, 255, .2);
                color: #fff;
                backdrop-filter: blur(8px);
            }

            .cd-badge.gold {
                background: #f59e0b;
                color: #fff;
            }

            .cd-badge.green {
                background: #10b981;
                color: #fff;
            }

            .cd-card {
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
                padding: 22px;
                border: none;
            }

            .cd-image-wrap {
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
            }

            .cd-image-wrap img {
                width: 100%;
                height: auto;
                display: block;
            }

            .cd-price-box {
                background: linear-gradient(135deg, #f0f9ff, #eef2ff);
                border: 1px solid #c7d2fe;
                border-radius: 14px;
                padding: 20px;
                text-align: center;
            }

            .cd-price-old {
                font-size: 15px;
                color: #94a3b8;
                text-decoration: line-through;
            }

            .cd-price-new {
                font-size: 32px;
                font-weight: 800;
                color: #4f46e5;
                line-height: 1.2;
            }

            .cd-price-free {
                font-size: 32px;
                font-weight: 800;
                color: #10b981;
            }

            .cd-discount-tag {
                display: inline-block;
                background: #ef4444;
                color: #fff;
                font-size: 12px;
                padding: 4px 10px;
                border-radius: 20px;
                font-weight: 700;
                margin-bottom: 8px;
            }

            .cd-btn-primary {
                display: block;
                width: 100%;
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                color: #fff;
                border: none;
                padding: 13px 20px;
                border-radius: 12px;
                font-weight: 700;
                font-size: 15px;
                text-align: center;
                text-decoration: none;
                transition: all .2s ease;
                margin-bottom: 10px;
                cursor: pointer;
            }

            .cd-btn-primary:hover {
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 10px 22px rgba(124, 58, 237, .35);
            }

            .cd-btn-secondary {
                display: block;
                width: 100%;
                background: #f1f5f9;
                color: #334155;
                border: 1px solid #e2e8f0;
                padding: 11px 20px;
                border-radius: 12px;
                font-weight: 600;
                font-size: 14px;
                text-align: center;
                text-decoration: none;
                transition: all .2s ease;
            }

            .cd-btn-secondary:hover {
                background: #e2e8f0;
                color: #1e293b;
            }

            .cd-info-row {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .cd-info-row:last-child {
                border-bottom: none;
            }

            .cd-info-icon {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .cd-info-icon.i1 {
                background: #eef2ff;
                color: #4f46e5;
            }

            .cd-info-icon.i2 {
                background: #ecfdf5;
                color: #059669;
            }

            .cd-info-icon.i3 {
                background: #fef3c7;
                color: #b45309;
            }

            .cd-info-icon.i4 {
                background: #fdf2f8;
                color: #db2777;
            }

            .cd-info-label {
                font-size: 12px;
                color: #94a3b8;
                margin-bottom: 2px;
            }

            .cd-info-value {
                font-weight: 600;
                color: #1e293b;
                font-size: 14px;
            }

            .cd-description {
                background: #f8fafc;
                border-left: 4px solid #6366f1;
                padding: 18px;
                border-radius: 10px;
                color: #334155;
                line-height: 1.7;
                font-size: 14.5px;
            }

            .cd-section-title {
                font-size: 16px;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 16px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .cd-section-title i {
                color: #6366f1;
            }

            .related-card {
                border-radius: 12px;
                overflow: hidden;
                transition: all .25s ease;
                text-decoration: none;
                display: block;
                background: #fff;
                box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            }

            .related-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 24px rgba(79, 70, 229, .15);
            }

            .related-thumb {
                height: 110px;
                overflow: hidden;
                background: linear-gradient(135deg, #eef2ff, #fdf2f8);
            }

            .related-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .related-body {
                padding: 12px;
            }

            .related-title {
                font-size: 13px;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 6px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                min-height: 36px;
            }

            .related-price {
                font-size: 14px;
                font-weight: 800;
                color: #4f46e5;
            }

            /* Purchase For toggle */
            .btn-check:checked+.btn-outline-primary {
                background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
                color: #fff !important;
                border-color: #4f46e5 !important;
            }

            .btn-outline-primary {
                color: #4f46e5;
                border-color: #c7d2fe;
            }

            .btn-outline-primary:hover {
                background: #eef2ff;
                color: #4f46e5;
                border-color: #a5b4fc;
            }

            /* Disabled radio style */
            .btn-outline-secondary.disabled-radio {
                opacity: .55;
                cursor: not-allowed !important;
                pointer-events: none;
                background: #f1f5f9 !important;
                color: #94a3b8 !important;
                border-color: #e2e8f0 !important;
            }

            /* Select2 inside modal */
            .select2-container--bootstrap-5 .select2-selection {
                min-height: 42px;
                border-radius: 10px;
            }

            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
                line-height: 40px;
                font-size: 14px;
            }

            .select2-dropdown {
                z-index: 10000 !important;
            }
        </style>
    @endpush

    <div class="container py-4">

        {{-- ============ HERO ============ --}}
        <div class="cd-hero mb-4">

            {{-- Breadcrumb --}}
            <div class="cd-breadcrumb">
                <a href="{{ route('user.study.center') }}">
                    <i class="fas fa-graduation-cap"></i> স্টাডি সেন্টার
                </a>
                <i class="fas fa-chevron-right mx-1" style="font-size:10px;"></i>
                <span>{{ Str::limit($course->title, 40) }}</span>
            </div>

            {{-- Badges --}}
            <div class="mb-2">
                @if ($course->courseType)
                    <span class="cd-badge light">{{ $course->courseType->name }}</span>
                @endif
                @if ($course->courseClass)
                    <span class="cd-badge light">{{ $course->courseClass->name }}</span>
                @endif
                @if ($course->courseCategory)
                    <span class="cd-badge light">{{ $course->courseCategory->name }}</span>
                @endif
                @if ($course->is_featured)
                    <span class="cd-badge gold">
                        <i class="fas fa-star"></i> ফিচারড
                    </span>
                @endif
                @if ($course->is_free)
                    <span class="cd-badge green">ফ্রি</span>
                @endif
            </div>

            {{-- Title --}}
            <h2>{{ $course->title }}</h2>
        </div>

        {{-- ============ MAIN CONTENT ============ --}}
        <div class="row g-4">

            {{-- ===== LEFT ===== --}}
            <div class="col-lg-8">

                {{-- Image --}}
                <div class="cd-image-wrap mb-4">
                    @if ($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1000&q=80"
                            alt="Default Course Image">
                    @endif
                </div>

                {{-- Description --}}
                @if ($course->description)
                    <div class="cd-card mb-4">
                        <div class="cd-section-title">
                            <i class="fas fa-align-left"></i> কোর্স সম্পর্কে
                        </div>
                        <div class="cd-description">
                            {{ $course->description }}
                        </div>
                    </div>
                @endif

                {{-- Course Info --}}
                <div class="cd-card">
                    <div class="cd-section-title">
                        <i class="fas fa-info-circle"></i> কোর্সের তথ্য
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="cd-info-row">
                                <div class="cd-info-icon i1">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div>
                                    <div class="cd-info-label">কোর্স টাইপ</div>
                                    <div class="cd-info-value">{{ $course->courseType->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cd-info-row">
                                <div class="cd-info-icon i2">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <div class="cd-info-label">কোর্স ক্লাস</div>
                                    <div class="cd-info-value">{{ $course->courseClass->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cd-info-row">
                                <div class="cd-info-icon i3">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div>
                                    <div class="cd-info-label">ক্যাটাগরি</div>
                                    <div class="cd-info-value">{{ $course->courseCategory->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cd-info-row">
                                <div class="cd-info-icon i4">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div class="cd-info-label">মেয়াদ</div>
                                    <div class="cd-info-value">{{ $course->duration ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cd-info-row">
                                <div class="cd-info-icon i1">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <div class="cd-info-label">মোট ক্লাস</div>
                                    <div class="cd-info-value">{{ $course->total_class ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cd-info-row">
                                <div class="cd-info-icon i2">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div>
                                    <div class="cd-info-label">মোট ভিউ</div>
                                    <div class="cd-info-value">{{ number_format($course->view_count) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT ===== --}}
            <div class="col-lg-4">
                <div style="position: sticky; top: 20px;">

                    <div class="cd-card mb-3">
                        <div class="cd-price-box mb-3">

                            @if ($course->is_free)
                                <div class="cd-price-free">ফ্রি</div>
                                <div class="small text-muted mt-1">সম্পূর্ণ বিনামূল্যে</div>
                            @elseif ($course->has_discount)
                                <div class="cd-discount-tag">
                                    <i class="fas fa-bolt"></i> {{ $course->discount_percent }}% ছাড়
                                </div>
                                <div class="cd-price-old">৳{{ number_format($course->price, 0) }}</div>
                                <div class="cd-price-new">৳{{ number_format($course->discount_price, 0) }}</div>
                            @else
                                <div class="cd-price-new">৳{{ number_format($course->price, 0) }}</div>
                            @endif

                        </div>

                        {{-- ============ ACTION BUTTONS ============ --}}
                        @auth
                            @if ($alreadyOwned)
                                {{-- Already Owned → Gift only --}}
                                <div class="alert alert-info d-flex align-items-center mb-3 py-2" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>আপনি এই কোর্সটি ইতিমধ্যে কিনেছেন। <strong>অন্যকে গিফট</strong> করতে পারবেন।</small>
                                </div>

                                <a href="{{ route('user.study.center.my.courses') }}" class="cd-btn-secondary mb-2">
                                    <i class="fas fa-book-reader"></i>
                                    আমার কোর্সে যান
                                </a>

                                <button type="button" class="cd-btn-primary border-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#buyCourseModal">
                                    <i class="fas fa-gift"></i>
                                    অন্যকে গিফট করুন
                                </button>

                            @elseif ($course->is_free)
                                <a href="#" class="cd-btn-primary">
                                    <i class="fas fa-play-circle"></i>
                                    ফ্রি এনরোল করুন
                                </a>
                            @else
                                <button type="button" class="cd-btn-primary border-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#buyCourseModal">
                                    <i class="fas fa-shopping-cart"></i>
                                    এখনই কিনুন — ৳{{ number_format($course->final_price, 0) }}
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="cd-btn-primary">
                                <i class="fas fa-sign-in-alt"></i>
                                কিনতে লগইন করুন
                            </a>
                        @endauth

                        <a href="{{ route('user.study.center') }}" class="cd-btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            আরও কোর্স দেখুন
                        </a>
                    </div>

                    <div class="cd-card">
                        <div class="cd-section-title" style="font-size: 14px;">
                            <i class="fas fa-star"></i> হাইলাইটস
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small">লাইফটাইম অ্যাক্সেস</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small">এক্সপার্ট ইন্সট্রাক্টর</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small">সার্টিফিকেট প্রদান</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small">২৪/৭ সাপোর্ট</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ============ RELATED ============ --}}
        @if ($relatedCourses->count() > 0)
            <div class="mt-5">
                <h5 class="mb-3 fw-bold">
                    <i class="fas fa-th-large text-primary"></i>
                    সম্পর্কিত কোর্স
                </h5>

                <div class="row g-3">
                    @foreach ($relatedCourses as $related)
                        <div class="col-md-4 col-sm-6">
                            <a href="{{ route('user.study.center.course.show', $related->slug) }}" class="related-card">

                                <div class="related-thumb">
                                    @if ($related->thumbnail)
                                        <img src="{{ asset('storage/' . $related->thumbnail) }}"
                                            alt="{{ $related->title }}">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400&q=80"
                                            alt="Default">
                                    @endif
                                </div>

                                <div class="related-body">
                                    <div class="related-title">{{ $related->title }}</div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="related-price">
                                            @if ($related->is_free)
                                                ফ্রি
                                            @elseif ($related->has_discount)
                                                ৳{{ number_format($related->discount_price, 0) }}
                                            @else
                                                ৳{{ number_format($related->price, 0) }}
                                            @endif
                                        </span>
                                        <span class="small text-muted">
                                            <i class="fas fa-eye"></i> {{ $related->view_count }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- ============================================================
         BUY COURSE MODAL (Self / Gift)
         Already Owned → "self" disabled, "other" auto-selected
    ============================================================ --}}
    @auth
        @if (!$course->is_free)
            <div class="modal fade" id="buyCourseModal" tabindex="-1" aria-labelledby="buyCourseModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">

                        <form action="{{ route('user.study.center.course.buy', $course->id) }}" method="POST"
                            onsubmit="return validateStudentSelection();">
                            @csrf

                            <div class="modal-header bg-primary text-white" style="border-radius: 14px 14px 0 0;">
                                <h5 class="modal-title" id="buyCourseModalLabel">
                                    <i class="fas fa-shopping-cart"></i>
                                    {{ $alreadyOwned ? 'অন্যকে গিফট করুন' : 'কোর্স কিনুন' }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body p-4">

                                {{-- Course Info --}}
                                <div class="mb-4 p-3 rounded-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size:15px;">
                                                {{ $course->title }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $course->courseType->name ?? '' }}
                                                @if ($course->courseClass)
                                                    • {{ $course->courseClass->name }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            @if ($course->has_discount)
                                                <div class="text-muted text-decoration-line-through small">
                                                    ৳{{ number_format($course->price, 0) }}
                                                </div>
                                            @endif
                                            <div class="fw-bold text-primary" style="font-size:20px;">
                                                ৳{{ number_format($course->final_price, 0) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Purchase For Toggle --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">কেন কিনছেন?</label>
                                    <div class="row g-2">

                                        {{-- নিজের জন্য --}}
                                        <div class="col-6">
                                            @if ($alreadyOwned)
                                                {{-- Disabled (already owned) --}}
                                                <input type="radio" class="btn-check" name="purchase_for"
                                                    id="purchase_self" value="self" disabled>
                                                <label class="btn btn-outline-secondary w-100 py-3 disabled-radio"
                                                    for="purchase_self">
                                                    <i class="fas fa-user d-block mb-1" style="font-size:20px;"></i>
                                                    <div class="fw-bold">নিজের জন্য</div>
                                                    <small class="d-block text-danger">
                                                        <i class="fas fa-check-circle"></i> ইতিমধ্যে কিনেছেন
                                                    </small>
                                                </label>
                                            @else
                                                <input type="radio" class="btn-check" name="purchase_for"
                                                    id="purchase_self" value="self" checked>
                                                <label class="btn btn-outline-primary w-100 py-3"
                                                    for="purchase_self">
                                                    <i class="fas fa-user d-block mb-1" style="font-size:20px;"></i>
                                                    <div class="fw-bold">নিজের জন্য</div>
                                                    <small class="d-block text-warning">আমি নিজে পড়ব</small>
                                                </label>
                                            @endif
                                        </div>

                                        {{-- অন্য কারো জন্য --}}
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="purchase_for"
                                                id="purchase_other" value="other"
                                                {{ $alreadyOwned ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary w-100 py-3" for="purchase_other">
                                                <i class="fas fa-user-friends d-block mb-1" style="font-size:20px;"></i>
                                                <div class="fw-bold">অন্য কারো জন্য</div>
                                                <small class="d-block text-warning">Student-কে গিফট করব</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Student Info (show/hide) --}}
                                <div id="studentInfoBlock" style="{{ $alreadyOwned ? '' : 'display:none;' }}">

                                    {{-- Info Alert --}}
                                    <div class="alert alert-info d-flex align-items-start mb-3 py-2" role="alert">
                                        <i class="fas fa-info-circle me-2 mt-1"></i>
                                        <small>
                                            Student search করে select করুন (নাম / username / phone দিয়ে)।
                                            Student select না করলে পেমেন্ট করা যাবে না।
                                        </small>
                                    </div>

                                    {{-- Student Select (Select2) --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-user-graduate text-primary"></i>
                                            Student Select করুন <span class="text-danger">*</span>
                                        </label>
                                        <select id="studentSelect" name="student_id" class="form-select"
                                            style="width:100%;">
                                            <option value="">-- Search by name / username / phone --</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}"
                                                    data-name="{{ $student->full_name }}"
                                                    data-username="{{ $student->username }}"
                                                    data-email="{{ $student->email }}"
                                                    data-phone="{{ $student->phone }}">
                                                    {{ $student->full_name }}
                                                    @if ($student->username)
                                                        — {{ $student->username }}
                                                    @endif
                                                    @if ($student->phone)
                                                        — {{ $student->phone }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_id')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Selected Student Preview --}}
                                    <div id="selectedStudentInfo" style="display:none;">
                                        <div class="p-3 rounded-3" style="background:#f0fdf4; border:1px solid #bbf7d0;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <strong class="text-success">Selected Student</strong>
                                            </div>
                                            <div class="small">
                                                <div class="mb-1">
                                                    <i class="fas fa-user text-muted"></i>
                                                    <span id="previewName" class="fw-semibold"></span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-phone text-muted"></i>
                                                    <span id="previewPhone"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cashback --}}
                                <div class="alert alert-success mt-3 mb-0 py-2 d-flex align-items-center">
                                    <i class="fas fa-gift me-2 text-success"></i>
                                    <small>
                                        <strong>ক্যাশব্যাক:</strong> এই কোর্স কিনলে আপনি <strong>২০%</strong>
                                        ক্যাশব্যাক পাবেন
                                        (৳{{ number_format($course->final_price * 0.2, 0) }})
                                    </small>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-lock"></i> পেমেন্ট করুন
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function() {
            'use strict';

            var alreadyOwned = {{ $alreadyOwned ? 'true' : 'false' }};

            function initCoursePage() {
                if (!window.jQuery) {
                    console.error('❌ jQuery not loaded');
                    return;
                }

                if (!jQuery.fn.select2) {
                    console.error('❌ Select2 not loaded');
                    return;
                }

                console.log('✅ jQuery & Select2 ready | alreadyOwned:', alreadyOwned);

                /* ============================================================
                   ELEMENTS
                ============================================================ */
                var selfRadio  = document.getElementById('purchase_self');
                var otherRadio = document.getElementById('purchase_other');
                var block      = document.getElementById('studentInfoBlock');

                var previewBox   = document.getElementById('selectedStudentInfo');
                var previewName  = document.getElementById('previewName');
                var previewEmail = document.getElementById('previewEmail');
                var previewPhone = document.getElementById('previewPhone');

                /* ============================================================
                   PURCHASE FOR TOGGLE
                ============================================================ */
                function toggleBlock() {
                    if (!block) return;

                    // Already owned → force gift only
                    if (alreadyOwned) {
                        if (otherRadio) otherRadio.checked = true;
                        block.style.display = 'block';
                        if (window.jQuery && jQuery('#studentSelect').length) {
                            jQuery('#studentSelect').trigger('change.select2');
                        }
                        return;
                    }

                    if (otherRadio && otherRadio.checked) {
                        block.style.display = 'block';
                        if (window.jQuery && jQuery('#studentSelect').length) {
                            jQuery('#studentSelect').trigger('change.select2');
                        }
                    } else {
                        block.style.display = 'none';
                        if (window.jQuery && jQuery('#studentSelect').length) {
                            jQuery('#studentSelect').val(null).trigger('change');
                        }
                        if (previewBox) previewBox.style.display = 'none';
                    }
                }

                if (selfRadio)  selfRadio.addEventListener('change', toggleBlock);
                if (otherRadio) otherRadio.addEventListener('change', toggleBlock);
                toggleBlock();

                /* ============================================================
                   SELECT2 — Init on modal shown
                ============================================================ */
                var $modal  = jQuery('#buyCourseModal');
                var $select = jQuery('#studentSelect');

                if (!$modal.length || !$select.length) {
                    console.warn('Modal or select not found — skipping Select2 init');
                    return;
                }

                $modal.on('shown.bs.modal', function () {
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.select2('close');
                        return;
                    }

                    $select.select2({
                        theme: 'bootstrap-5',
                        placeholder: '-- Search by name / username / phone --',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $modal,
                        minimumInputLength: 0,
                        matcher: function (params, data) {
                            if (jQuery.trim(params.term) === '') return data;

                            var term = params.term.toLowerCase().trim();
                            var text = (data.text || '').toLowerCase();

                            if (text.indexOf(term) > -1) return data;

                            if (data.element) {
                                var $el      = jQuery(data.element);
                                var name     = ($el.attr('data-name')     || '').toLowerCase();
                                var username = ($el.attr('data-username') || '').toLowerCase();
                                var phone    = ($el.attr('data-phone')    || '').toLowerCase();
                                var email    = ($el.attr('data-email')    || '').toLowerCase();

                                if (
                                    name.indexOf(term) > -1 ||
                                    username.indexOf(term) > -1 ||
                                    phone.indexOf(term) > -1 ||
                                    email.indexOf(term) > -1
                                ) {
                                    return data;
                                }
                            }
                            return null;
                        }
                    });

                    console.log('✅ Select2 initialized on #studentSelect');
                });

                $modal.on('hidden.bs.modal', function () {
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.val(null).trigger('change');
                    }
                    if (previewBox) previewBox.style.display = 'none';
                });

                /* ============================================================
                   On select → preview
                ============================================================ */
                $select.on('select2:select', function (e) {
                    var data = e.params.data;
                    var $opt = jQuery(this).find('option[value="' + data.id + '"]');

                    var name  = $opt.attr('data-name')  || '';
                    var email = $opt.attr('data-email') || '';
                    var phone = $opt.attr('data-phone') || '';

                    console.log('✅ Student selected:', data.id, name);

                    if (previewBox) {
                        previewBox.style.display = 'block';
                        if (previewName)  previewName.textContent  = name  || '—';
                        if (previewEmail) previewEmail.textContent = email || '—';
                        if (previewPhone) previewPhone.textContent = phone || '—';
                    }
                });

                $select.on('select2:clear', function () {
                    if (previewBox) previewBox.style.display = 'none';
                });

                /* ============================================================
                   FORM SUBMIT SAFETY
                ============================================================ */
                window.validateStudentSelection = function () {
                    if (otherRadio && otherRadio.checked) {
                        var select = document.getElementById('studentSelect');
                        if (!select || !select.value) {
                            alert('অনুগ্রহ করে একজন Student select করুন।');
                            return false;
                        }
                    }
                    return true;
                };
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCoursePage);
            } else {
                initCoursePage();
            }

        })();
    </script>
@endpush