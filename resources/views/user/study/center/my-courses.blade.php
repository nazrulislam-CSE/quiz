@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

{{-- ============ CUSTOM STYLES ============ --}}
<style>
    .mc-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        border-radius: 18px;
        padding: 26px 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .mc-hero::after {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,.12);
        border-radius: 50%;
    }
    .mc-hero h3 { font-weight: 700; margin-bottom: 6px; }
    .mc-hero p  { opacity: .9; margin-bottom: 0; }

    .mc-stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 4px 14px rgba(0,0,0,.05);
        display: flex;
        align-items: center;
        gap: 14px;
        height: 100%;
    }
    .mc-stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .mc-stat-icon.purple { background: #eef2ff; color: #4f46e5; }
    .mc-stat-icon.green  { background: #ecfdf5; color: #059669; }
    .mc-stat-icon.amber  { background: #fef3c7; color: #b45309; }
    .mc-stat-label { font-size: 12px; color: #94a3b8; margin-bottom: 2px; }
    .mc-stat-value { font-size: 18px; font-weight: 800; color: #1e293b; }

    .mc-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        transition: all .25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .mc-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(79,70,229,.18);
    }

    .mc-thumb {
        height: 160px;
        overflow: hidden;
        background: linear-gradient(135deg, #eef2ff, #fdf2f8);
        position: relative;
    }
    .mc-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }
    .mc-card:hover .mc-thumb img {
        transform: scale(1.06);
    }
    .mc-thumb .paid-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #10b981;
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .mc-body {
        padding: 14px 16px 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .mc-meta {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }
    .mc-meta .chip {
        font-size: 11px;
        padding: 3px 9px;
        border-radius: 20px;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 600;
    }
    .mc-meta .chip.type {
        background: #ecfdf5;
        color: #059669;
    }

    .mc-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 40px;
    }

    .mc-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #64748b;
        padding: 8px 0;
        border-top: 1px dashed #e5e7eb;
        border-bottom: 1px dashed #e5e7eb;
        margin-bottom: 12px;
    }
    .mc-info i { color: #94a3b8; margin-right: 3px; }

    .mc-btn {
        display: block;
        width: 100%;
        padding: 10px 16px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        text-align: center;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13.5px;
        text-decoration: none;
        border: none;
        transition: all .2s ease;
        margin-top: auto;
    }
    .mc-btn:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(124,58,237,.35);
    }
    .mc-btn i { margin-right: 6px; }

    .mc-empty {
        text-align: center;
        padding: 70px 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0,0,0,.05);
    }
    .mc-empty .icon-wrap {
        width: 90px;
        height: 90px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #eef2ff, #fdf2f8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mc-empty .icon-wrap i {
        font-size: 40px;
        color: #a5b4fc;
    }
    .mc-empty h4 {
        color: #334155;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .mc-empty p {
        color: #94a3b8;
        margin-bottom: 22px;
    }
    .mc-empty .btn-explore {
        display: inline-block;
        padding: 12px 28px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all .2s ease;
    }
    .mc-empty .btn-explore:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(124,58,237,.35);
    }
</style>

<div class="container py-4">

    {{-- ============ HERO ============ --}}
    <div class="mc-hero mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width: 62px; height: 62px; background: rgba(255,255,255,.2);
                        border-radius: 16px; backdrop-filter: blur(8px);">
                <i class="fas fa-book-reader fa-2x"></i>
            </div>
            <div>
                <h3>{{ $pageTitle }}</h3>
                <p>আপনার কেনা সব কোর্স এক জায়গায়</p>
            </div>
        </div>
    </div>

    @if ($orders->count() > 0)

        {{-- ============ SUMMARY STATS ============ --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-6">
                <div class="mc-stat-card">
                    <div class="mc-stat-icon purple">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="mc-stat-label">মোট কোর্স</div>
                        <div class="mc-stat-value">{{ $orders->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-6">
                <div class="mc-stat-card">
                    <div class="mc-stat-icon green">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <div class="mc-stat-label">মোট পরিশোধিত</div>
                        <div class="mc-stat-value">
                            ৳{{ number_format($orders->sum('paid_amount'), 0) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="mc-stat-card">
                    <div class="mc-stat-icon amber">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="mc-stat-label">সর্বশেষ ক্রয়</div>
                        <div class="mc-stat-value" style="font-size: 14px;">
                            {{ $orders->first()?->paid_at?->format('d M Y') ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ COURSE GRID ============ --}}
        <h5 class="mb-3 fw-bold">
            <i class="fas fa-th-large text-primary"></i>
            আমার কোর্সসমূহ
        </h5>

        <div class="row g-4">
            @foreach ($orders as $order)
                @php
                    $course = $order->course;
                @endphp
                @if ($course)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mc-card">

                            {{-- Thumbnail --}}
                            <div class="mc-thumb">
                                @if ($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                         alt="{{ $course->title }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80"
                                         alt="Default">
                                @endif

                                <span class="paid-badge">
                                    <i class="fas fa-check-circle"></i> কেনা হয়েছে
                                </span>
                            </div>

                            {{-- Body --}}
                            <div class="mc-body">

                                {{-- Meta --}}
                                <div class="mc-meta">
                                    @if ($course->courseType)
                                        <span class="chip type">{{ $course->courseType->name }}</span>
                                    @endif
                                    @if ($course->courseClass)
                                        <span class="chip">{{ $course->courseClass->name }}</span>
                                    @endif
                                </div>

                                {{-- Title --}}
                                <h6 class="mc-title">{{ $course->title }}</h6>

                                {{-- Info --}}
                                <div class="mc-info">
                                    <span>
                                        <i class="fas fa-receipt"></i>{{ $order->order_no }}
                                    </span>
                                    <span>
                                        <i class="fas fa-calendar-check"></i>
                                        {{ $order->paid_at?->format('d M Y') }}
                                    </span>
                                </div>

                                {{-- Paid --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="small text-muted">পরিশোধিত</span>
                                    <span class="fw-bold text-success">
                                        ৳{{ number_format($order->paid_amount, 0) }}
                                    </span>
                                </div>

                                {{-- Button --}}
                                <a href="{{ route('user.study.center.course.show', $course->slug) }}"
                                   class="mc-btn">
                                    <i class="fas fa-eye"></i>
                                    কোর্স দেখুন
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

    @else

        {{-- ============ EMPTY STATE ============ --}}
        <div class="mc-empty">
            <div class="icon-wrap">
                <i class="fas fa-book-open"></i>
            </div>
            <h4>এখনো কোনো কোর্স কেনা হয়নি</h4>
            <p>স্টাডি সেন্টার থেকে আপনার পছন্দের কোর্স কিনুন এবং পড়া শুরু করুন</p>
            <a href="{{ route('user.study.center') }}" class="btn-explore">
                <i class="fas fa-graduation-cap"></i> কোর্স দেখুন
            </a>
        </div>

    @endif

</div>
@endsection