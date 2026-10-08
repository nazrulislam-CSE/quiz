@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

{{-- ============ CUSTOM STYLES ============ --}}
<style>
    .sc-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        border-radius: 18px;
        padding: 28px 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .sc-hero::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, .12);
        border-radius: 50%;
    }

    .sc-hero h3 {
        font-weight: 700;
        margin-bottom: 6px;
    }

    .sc-hero p {
        opacity: .9;
        margin-bottom: 0;
    }

    .course-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
        transition: transform .25s ease, box-shadow .25s ease;
        height: 100%;
        background: #fff;
    }

    .course-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(79, 70, 229, .18);
    }

    .course-thumb {
        position: relative;
        height: 170px;
        overflow: hidden;
        background: linear-gradient(135deg, #eef2ff, #fdf2f8);
    }

    .course-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }

    .course-card:hover .course-thumb img {
        transform: scale(1.06);
    }

    .course-thumb .badge-free {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #10b981;
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .course-thumb .badge-featured {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #f59e0b;
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .course-thumb .badge-discount {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background: #ef4444;
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
    }

    .course-body {
        padding: 14px 16px 16px;
    }

    .course-title {
        font-weight: 700;
        font-size: 15px;
        color: #1e293b;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
    }

    .course-meta {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .course-meta .chip {
        font-size: 11px;
        padding: 3px 9px;
        border-radius: 20px;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 600;
    }

    .course-meta .chip.type {
        background: #ecfdf5;
        color: #059669;
    }

    .course-meta .chip.class {
        background: #fef3c7;
        color: #b45309;
    }

    .price-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #e5e7eb;
    }

    .price-block .old-price {
        font-size: 12px;
        color: #94a3b8;
        text-decoration: line-through;
        display: block;
    }

    .price-block .new-price {
        font-size: 18px;
        font-weight: 800;
        color: #4f46e5;
    }

    .price-block .free-tag {
        font-size: 18px;
        font-weight: 800;
        color: #10b981;
    }

    .btn-buy {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .2s ease;
        text-decoration: none;
    }

    .btn-buy:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(124, 58, 237, .35);
    }

    .course-stats {
        display: flex;
        gap: 12px;
        font-size: 12px;
        color: #64748b;
        margin-top: 8px;
    }

    .course-stats i {
        color: #94a3b8;
        margin-right: 3px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 60px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state h5 {
        color: #475569;
        font-weight: 600;
    }

    .empty-state p {
        color: #94a3b8;
    }
</style>

<div class="container py-4">

    {{-- ============ HERO ============ --}}
    <div class="sc-hero mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 62px; height: 62px; background: rgba(255,255,255,.2);
                        border-radius: 16px; backdrop-filter: blur(8px);">
                <i class="fas fa-graduation-cap fa-2x"></i>
            </div>
            <div>
                <h3>{{ $pageTitle }}</h3>
                <p>পড়াশোনার জন্য আপনার পছন্দের কোর্সটি বেছে নিন</p>
            </div>
        </div>
    </div>

    {{-- ============ COURSE GRID ============ --}}
    @if ($courses->count() > 0)
    <div class="row g-4">
        @foreach ($courses as $course)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="course-card">

                {{-- Thumbnail --}}
                <div class="course-thumb">
                    @if ($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                    @else
                    {{-- Default online thumbnail --}}
                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80"
                        alt="Default Course Image">
                    @endif

                    @if ($course->is_free)
                    <span class="badge-free">ফ্রি</span>
                    @endif

                    @if ($course->is_featured)
                    <span class="badge-featured">
                        <i class="fas fa-star"></i> ফিচারড
                    </span>
                    @endif

                    @if ($course->has_discount)
                    <span class="badge-discount">
                        {{ $course->discount_percent }}% ছাড়
                    </span>
                    @endif
                </div>

                {{-- Body --}}
                <div class="course-body">

                    {{-- Meta chips --}}
                    <div class="course-meta">
                        @if ($course->courseType)
                        <span class="chip type">
                            {{ $course->courseType->name }}
                        </span>
                        @endif
                        @if ($course->courseClass)
                        <span class="chip class">
                            {{ $course->courseClass->name }}
                        </span>
                        @endif
                        @if ($course->courseCategory)
                        <span class="chip">
                            {{ $course->courseCategory->name }}
                        </span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h6 class="course-title">{{ $course->title }}</h6>

                    {{-- Stats --}}
                    <div class="course-stats">
                        @if ($course->duration)
                        <span>
                            <i class="fas fa-clock"></i>{{ $course->duration }}
                        </span>
                        @endif
                        @if ($course->total_class)
                        <span>
                            <i class="fas fa-book"></i>{{ $course->total_class }} ক্লাস
                        </span>
                        @endif
                        <span>
                            <i class="fas fa-eye"></i>{{ $course->view_count }}
                        </span>
                    </div>

                    {{-- Price + Buy --}}
                    <div class="price-row">
                        <div class="price-block">
                            @if ($course->is_free)
                            <span class="free-tag">ফ্রি</span>
                            @elseif ($course->has_discount)
                            <span class="old-price">৳{{ number_format($course->price, 0) }}</span>
                            <span class="new-price">৳{{ number_format($course->discount_price, 0) }}</span>
                            @else
                            <span class="new-price">৳{{ number_format($course->price, 0) }}</span>
                            @endif
                        </div>

                        <a href="{{ route('user.study.center.course.show', $course->slug) }}" class="btn-buy">
                            <i class="fas fa-info-circle"></i>
                            বিস্তারিত দেখুন
                        </a>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- ============ EMPTY STATE ============ --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body empty-state">
            <i class="fas fa-inbox"></i>
            <h5>কোনো কোর্স পাওয়া যায়নি</h5>
            <p>খুব শীঘ্রই নতুন কোর্স যোগ করা হবে</p>
        </div>
    </div>
    @endif

</div>
@endsection