@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')

{{-- ====== Breadcrumb ====== --}}
<div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.course.purchase.index') }}">Purchases</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $order->order_no }}
                </li>
            </ol>
        </nav>
    </div>
    <div class="d-flex">
        <a href="{{ route('admin.course.purchase.index') }}" class="btn btn-sm btn-light">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="container-fluid px-0">

    {{-- ============ HERO HEADER ============ --}}
    <div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
                border-radius: 14px; overflow: hidden;">
        <div class="card-body p-4 text-white">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: rgba(255,255,255,.18);
                            border-radius: 14px; backdrop-filter: blur(8px);">
                    <i class="fas fa-shopping-bag fs-3"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Order #{{ $order->order_no }}</h5>
                    <small style="opacity: .9;">
                        {{ $order->paid_at?->format('d M Y, h:i A') ?? 'Pending' }}
                    </small>
                </div>
                <div class="ms-auto">
                    @if ($order->payment_status === 'paid')
                    <span class="badge bg-light text-success fw-bold" style="font-size: 13px;">
                        <i class="fas fa-check-circle"></i> PAID
                    </span>
                    @else
                    <span class="badge bg-light text-danger fw-bold" style="font-size: 13px;">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- ============ LEFT: Course Info ============ --}}
        <div class="col-lg-8">

            {{-- Course Card --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-graduation-cap text-primary"></i> Course Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3">
                        @if ($order->course->thumbnail)
                        <img src="{{ asset('storage/' . $order->course->thumbnail) }}" alt="{{ $order->course->title }}"
                            style="width: 120px; height: 90px; object-fit: cover; border-radius: 10px;">
                        @else
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=200&q=80" alt="Course"
                            style="width: 120px; height: 90px; object-fit: cover; border-radius: 10px;">
                        @endif

                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2">{{ $order->course->title }}</h6>

                            <div class="d-flex flex-wrap gap-2">
                                @if ($order->course->courseType)
                                <span class="badge bg-primary">
                                    {{ $order->course->courseType->name }}
                                </span>
                                @endif
                                @if ($order->course->courseClass)
                                <span class="badge bg-info">
                                    {{ $order->course->courseClass->name }}
                                </span>
                                @endif
                                @if ($order->course->courseCategory)
                                <span class="badge bg-secondary">
                                    {{ $order->course->courseCategory->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-credit-card text-primary"></i> Payment Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8fafc;">
                                <div class="small text-muted">Order No</div>
                                <div class="fw-bold">{{ $order->order_no }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8fafc;">
                                <div class="small text-muted">Payment Method</div>
                                <div class="fw-bold">
                                    {{ ucfirst($order->payment_method ?? 'EPS') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8fafc;">
                                <div class="small text-muted">Merchant Transaction ID</div>
                                <div class="fw-bold" style="font-size: 12px; word-break: break-all;">
                                    {{ $order->merchant_transaction_id ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #f8fafc;">
                                <div class="small text-muted">EPS Transaction ID</div>
                                <div class="fw-bold" style="font-size: 12px; word-break: break-all;">
                                    {{ $order->eps_transaction_id ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        {{-- ============ RIGHT: User Info + Amount ============ --}}
        <div class="col-lg-4">

            {{-- Amount Summary --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="p-3 rounded-3 mb-2" style="background: linear-gradient(135deg, #f0f9ff, #eef2ff);
                                border: 1px solid #c7d2fe;">
                        <div class="small text-muted mb-1">Paid Amount</div>
                        <div class="fs-3 fw-bold text-primary">
                            ৳{{ number_format($order->paid_amount, 0) }}
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2 rounded-2 text-center" style="background: #f8fafc;">
                                <div class="small text-muted">Original</div>
                                <div class="fw-bold">৳{{ number_format($order->price, 0) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-2 text-center" style="background: #f8fafc;">
                                <div class="small text-muted">Discount</div>
                                <div class="fw-bold">
                                    {{ $order->discount_price ? '৳' . number_format($order->discount_price, 0) : '—' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Info --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user text-primary"></i> Buyer Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        {{-- User Image --}}
                        <img src="{{ !empty($order->user->image) 
                    ? url('upload/user/' . $order->user->image) 
                    : url('upload/avater.png') }}" alt="{{ $order->user->name ?? 'User' }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;
                border: 3px solid #eef2ff; box-shadow: 0 4px 12px rgba(79,70,229,.15);">

                        <h6 class="mt-2 mb-0 fw-bold">{{ $order->user->name ?? 'N/A' }}</h6>
                        <small class="text-muted">User ID: #{{ $order->user_id }}</small>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @if ($order->user->email)
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-envelope text-muted" style="width: 18px;"></i>
                            <small>{{ $order->user->email }}</small>
                        </div>
                        @endif

                        @if ($order->user->phone)
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-phone text-muted" style="width: 18px;"></i>
                            <small>{{ $order->user->phone }}</small>
                        </div>
                        @endif

                        @if ($order->user->refer_by)
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-user-friends text-muted" style="width: 18px;"></i>
                            <small>Referred by: #{{ $order->user->refer_by }}</small>
                        </div>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Payment Status</span>
                        <span class="fw-bold">
                            @if ($order->payment_status === 'paid')
                            <span class="text-success">Paid</span>
                            @else
                            <span class="text-danger">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </span>
                    </div>

                    <div class="d-flex justify-content-between small mt-2">
                        <span class="text-muted">Order Status</span>
                        <span class="fw-bold">{{ ucfirst($order->status) }}</span>
                    </div>

                    <div class="d-flex justify-content-between small mt-2">
                        <span class="text-muted">Created At</span>
                        <span class="fw-bold">
                            {{ $order->created_at?->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection