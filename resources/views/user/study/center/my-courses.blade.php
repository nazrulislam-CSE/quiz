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

    /* ============ Custom Tabs ============ */
    .custom-tabs {
        gap: 8px;
        border: none;
    }
    .custom-tabs .nav-link {
        background: #fff;
        color: #475569;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 700;
        font-size: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,.03);
        transition: all .2s ease;
    }
    .custom-tabs .nav-link:hover:not(.active) {
        background: #f8fafc;
        color: #4f46e5;
        border-color: #c7d2fe;
    }
    .custom-tabs .nav-link.active {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff !important;
        border-color: transparent;
        box-shadow: 0 6px 18px rgba(79,70,229,.3);
    }
    .custom-tabs .nav-link.active .badge {
        background: rgba(255,255,255,.25) !important;
        color: #fff !important;
    }
    .custom-tabs .nav-link .badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 20px;
    }

    /* ============ Stat Cards ============ */
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
    .mc-stat-icon.pink   { background: #fdf2f8; color: #db2777; }
    .mc-stat-label { font-size: 12px; color: #94a3b8; margin-bottom: 2px; }
    .mc-stat-value { font-size: 18px; font-weight: 800; color: #1e293b; }

    /* ============ Table ============ */
    .mc-table-wrapper {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .mc-table { width: 100%; margin-bottom: 0; vertical-align: middle; }
    .mc-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 15px 12px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .mc-table tbody td {
        padding: 14px 12px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    .mc-table tbody tr:hover { background: #fafaff; }

    .mc-course-info {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 220px;
    }
    .mc-course-info img {
        width: 58px; height: 48px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .mc-course-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .mc-teacher {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 180px;
    }
    .mc-teacher img {
        width: 36px; height: 36px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }
    .mc-teacher-name { font-size: 12px; font-weight: 700; color: #334155; }
    .mc-teacher-designation { font-size: 11px; color: #94a3b8; }

    .mc-order-no { font-weight: 600; color: #4f46e5; white-space: nowrap; }

    .mc-table-btn {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 8px;
        color: #fff;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }
    .mc-table-btn:hover { color: #fff; opacity: .9; }

    .mc-table .mc-chip {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 20px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 11px;
        white-space: nowrap;
    }

    /* ============ Recipient avatar ============ */
    .recipient-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef2ff, #fdf2f8);
        color: #4f46e5;
        font-weight: 800;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 2px solid #e0e7ff;
    }

    /* ============ Empty state ============ */
    .mc-empty {
        text-align: center;
        padding: 70px 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0,0,0,.05);
    }
    .mc-empty .icon-wrap {
        width: 90px; height: 90px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #eef2ff, #fdf2f8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mc-empty .icon-wrap i { font-size: 40px; color: #a5b4fc; }
    .mc-empty h4 { color: #334155; font-weight: 700; margin-bottom: 8px; }
    .mc-empty p  { color: #94a3b8; margin-bottom: 22px; }
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

    @media (max-width: 767px) {
        .mc-table-wrapper { overflow-x: auto; }
        .mc-table { min-width: 1100px; }
        .custom-tabs .nav-link { padding: 8px 14px; font-size: 13px; }
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
                <p>আপনার কেনা ও গিফট করা সব কোর্স এক জায়গায়</p>
            </div>
        </div>
    </div>

    {{-- ============ TABS ============ --}}
    <ul class="nav nav-pills custom-tabs mb-4" id="coursesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="my-courses-tab"
                    data-bs-toggle="pill" data-bs-target="#my-courses-pane"
                    type="button" role="tab" aria-controls="my-courses-pane" aria-selected="true">
                <i class="fas fa-book-reader me-1"></i>
                আমার কোর্সসমূহ
                <span class="badge bg-primary ms-1">{{ $orders->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gift-courses-tab"
                    data-bs-toggle="pill" data-bs-target="#gift-courses-pane"
                    type="button" role="tab" aria-controls="gift-courses-pane" aria-selected="false">
                <i class="fas fa-gift me-1"></i>
                গিফট করা কোর্স
                <span class="badge bg-success ms-1">{{ $giftOrders->count() }}</span>
            </button>
        </li>
    </ul>

    {{-- ============ TAB CONTENT ============ --}}
    <div class="tab-content" id="coursesTabsContent">

        {{-- ============================================================
             TAB 1: MY COURSES
        ============================================================ --}}
        <div class="tab-pane fade show active" id="my-courses-pane"
             role="tabpanel" aria-labelledby="my-courses-tab">

            @if ($orders->count() > 0)

                {{-- Stats --}}
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

                {{-- Table --}}
                <h5 class="mb-3 fw-bold">
                    <i class="fas fa-table text-primary"></i>
                    আমার কোর্সসমূহ
                </h5>

                <div class="mc-table-wrapper">
                    <div class="table-responsive">
                        <table class="table mc-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>কোর্সের নাম</th>
                                    <th>শিক্ষক</th>
                                    <th>কোর্সের ধরন</th>
                                    <th>ক্লাস</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>অর্ডার নম্বর</th>
                                    <th>ক্রয়ের তারিখ</th>
                                    <th>পরিশোধিত</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    @php $course = $order->course; @endphp
                                    @if ($course)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                <div class="mc-course-info">
                                                    <img src="{{ $course->thumbnail
                                                            ? asset('storage/' . $course->thumbnail)
                                                            : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80' }}"
                                                         alt="{{ $course->title }}">
                                                    <div>
                                                        <div class="mc-course-title">
                                                            {{ $course->title }}
                                                        </div>
                                                        <span class="mc-chip">
                                                            {{ $course->duration ?? 'সময় নির্ধারিত নয়' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="mc-teacher">
                                                    <img src="{{ $course->teacher_photo
                                                            ? asset('storage/' . $course->teacher_photo)
                                                            : 'https://ui-avatars.com/api/?name=' . urlencode($course->teacher_name ?: 'Teacher') . '&background=EEF2FF&color=4F46E5' }}"
                                                         alt="{{ $course->teacher_name ?? 'Teacher' }}">
                                                    <div>
                                                        <div class="mc-teacher-name">
                                                            {{ $course->teacher_name ?? 'শিক্ষকের তথ্য নেই' }}
                                                        </div>
                                                        <div class="mc-teacher-designation">
                                                            {{ $course->teacher_designation ?? 'Instructor' }}
                                                        </div>
                                                        @if ($course->teacher_experience)
                                                            <div class="text-muted" style="font-size: 11px;">
                                                                {{ $course->teacher_experience }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ $course->courseType->name ?? '—' }}</td>
                                            <td>{{ $course->courseClass->name ?? '—' }}</td>
                                            <td>{{ $course->courseCategory->name ?? '—' }}</td>

                                            <td>
                                                <span class="mc-order-no">{{ $order->order_no }}</span>
                                            </td>

                                            <td class="text-nowrap">
                                                {{ $order->paid_at?->format('d M Y') ?? '—' }}
                                            </td>

                                            <td class="text-nowrap">
                                                <span class="fw-bold text-success">
                                                    ৳{{ number_format($order->paid_amount, 2) }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="d-flex flex-row align-items-center gap-2">
                                                    <a href="{{ route('user.study.center.course.show', $course->slug) }}"
                                                       class="mc-table-btn"
                                                       style="width:auto; margin-top:0; white-space:nowrap;">
                                                        <i class="fas fa-eye me-1"></i> কোর্স দেখুন
                                                    </a>

                                                    <a href="{{ route('user.study.center.my.courses.voucher', $order->order_no) }}"
                                                       class="btn btn-sm btn-success"
                                                       style="border-radius: 8px; font-size: 12px; font-weight: 700; white-space:nowrap;">
                                                        <i class="fas fa-file-invoice me-1"></i> ভাউচার
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                {{-- Empty state --}}
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

        {{-- ============================================================
             TAB 2: GIFTED COURSES
        ============================================================ --}}
        <div class="tab-pane fade" id="gift-courses-pane"
             role="tabpanel" aria-labelledby="gift-courses-tab">

            @if ($giftOrders->count() > 0)

                {{-- Stats --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-6">
                        <div class="mc-stat-card">
                            <div class="mc-stat-icon pink">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div>
                                <div class="mc-stat-label">মোট গিফট করা</div>
                                <div class="mc-stat-value">{{ $giftOrders->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-6">
                        <div class="mc-stat-card">
                            <div class="mc-stat-icon green">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div>
                                <div class="mc-stat-label">মোট খরচ</div>
                                <div class="mc-stat-value">
                                    ৳{{ number_format($giftOrders->sum('paid_amount'), 0) }}
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
                                <div class="mc-stat-label">সর্বশেষ গিফট</div>
                                <div class="mc-stat-value" style="font-size: 14px;">
                                    {{ $giftOrders->first()?->paid_at?->format('d M Y') ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <h5 class="mb-3 fw-bold">
                    <i class="fas fa-gift text-primary"></i>
                    গিফট করা কোর্সসমূহ
                </h5>

                <div class="mc-table-wrapper">
                    <div class="table-responsive">
                        <table class="table mc-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>কোর্সের নাম</th>
                                    <th>গ্রহণকারী (Recipient)</th>
                                    <th>কোর্সের ধরন</th>
                                    <th>ক্লাস</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>অর্ডার নম্বর</th>
                                    <th>গিফটের তারিখ</th>
                                    <th>পরিশোধিত</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($giftOrders as $gift)
                                    @php
                                        $course = $gift->course;
                                        $recipientName  = $gift->student->full_name ?? $gift->student_name ?? 'N/A';
                                        $recipientEmail = $gift->student->email     ?? $gift->student_email ?? '';
                                        $recipientPhone = $gift->student->phone     ?? $gift->student_phone ?? '';
                                        $initial        = strtoupper(mb_substr($recipientName, 0, 1));
                                    @endphp
                                    @if ($course)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                <div class="mc-course-info">
                                                    <img src="{{ $course->thumbnail
                                                            ? asset('storage/' . $course->thumbnail)
                                                            : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80' }}"
                                                         alt="{{ $course->title }}">
                                                    <div>
                                                        <div class="mc-course-title">
                                                            {{ $course->title }}
                                                        </div>
                                                        <span class="mc-chip">
                                                            {{ $course->duration ?? 'সময় নির্ধারিত নয়' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Recipient --}}
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="recipient-avatar">{{ $initial }}</div>
                                                    <div>
                                                        <div class="fw-bold" style="font-size: 13px; color: #1e293b;">
                                                            {{ $recipientName }}
                                                        </div>
                                                        @if ($recipientEmail)
                                                            <div class="text-muted" style="font-size: 11px;">
                                                                <i class="fas fa-envelope"></i>
                                                                {{ $recipientEmail }}
                                                            </div>
                                                        @endif
                                                        @if ($recipientPhone)
                                                            <div class="text-muted" style="font-size: 11px;">
                                                                <i class="fas fa-phone"></i>
                                                                {{ $recipientPhone }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ $course->courseType->name ?? '—' }}</td>
                                            <td>{{ $course->courseClass->name ?? '—' }}</td>
                                            <td>{{ $course->courseCategory->name ?? '—' }}</td>

                                            <td>
                                                <span class="mc-order-no">{{ $gift->order_no }}</span>
                                            </td>

                                            <td class="text-nowrap">
                                                {{ $gift->paid_at?->format('d M Y') ?? '—' }}
                                            </td>

                                            <td class="text-nowrap">
                                                <span class="fw-bold text-success">
                                                    ৳{{ number_format($gift->paid_amount, 2) }}
                                                </span>
                                            </td>

                                            <td>
                                                <a href="{{ route('user.study.center.my.courses.voucher', $gift->order_no) }}"
                                                   class="btn btn-sm btn-success"
                                                   style="border-radius: 8px; font-size: 12px; font-weight: 700; white-space:nowrap;">
                                                    <i class="fas fa-file-invoice me-1"></i> ভাউচার
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                {{-- Empty state --}}
                <div class="mc-empty">
                    <div class="icon-wrap">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h4>এখনো কাউকে গিফট করা হয়নি</h4>
                    <p>স্টাডি সেন্টার থেকে কোনো কোর্স কিনে অন্য কাউকে গিফট করতে পারবেন</p>
                    <a href="{{ route('user.study.center') }}" class="btn-explore">
                        <i class="fas fa-graduation-cap"></i> কোর্স দেখুন
                    </a>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection