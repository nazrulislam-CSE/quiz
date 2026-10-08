@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

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
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.12);
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
        background: rgba(255,255,255,.2);
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
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        padding: 22px;
        border: none;
    }

    .cd-image-wrap {
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,.1);
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
    }
    .cd-btn-primary:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(124,58,237,.35);
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
    .cd-info-icon.i1 { background: #eef2ff; color: #4f46e5; }
    .cd-info-icon.i2 { background: #ecfdf5; color: #059669; }
    .cd-info-icon.i3 { background: #fef3c7; color: #b45309; }
    .cd-info-icon.i4 { background: #fdf2f8; color: #db2777; }
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

    /* Related courses */
    .related-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all .25s ease;
        text-decoration: none;
        display: block;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
    }
    .related-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(79,70,229,.15);
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
</style>

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

        {{-- ===== LEFT: Image + Description ===== --}}
        <div class="col-lg-8">

            {{-- Image --}}
            <div class="cd-image-wrap mb-4">
                @if ($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                         alt="{{ $course->title }}">
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

            {{-- Course Info (Mobile-friendly info list) --}}
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

        {{-- ===== RIGHT: Sticky Price Box + CTA ===== --}}
        <div class="col-lg-4">
            <div style="position: sticky; top: 20px;">

                {{-- Price Box --}}
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

                    {{-- Action Buttons --}}
                    <a href="#" class="cd-btn-primary"
                       onclick="return confirm('কেনার সিস্টেম শীঘ্রই আসছে!');">
                        <i class="fas fa-shopping-cart"></i>
                        এখনই কিনুন
                    </a>

                    <a href="{{ route('user.study.center') }}" class="cd-btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        আরও কোর্স দেখুন
                    </a>
                </div>

                {{-- Quick Info --}}
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

    {{-- ============ RELATED COURSES ============ --}}
    @if ($relatedCourses->count() > 0)
        <div class="mt-5">
            <h5 class="mb-3 fw-bold">
                <i class="fas fa-th-large text-primary"></i>
                সম্পর্কিত কোর্স
            </h5>

            <div class="row g-3">
                @foreach ($relatedCourses as $related)
                    <div class="col-md-4 col-sm-6">
                        <a href="{{ route('user.study.center.course.show', $related->slug) }}"
                           class="related-card">

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
@endsection