@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

<style>
    /* ============ HERO HEADER ============ */
    .product-hero {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 50%, #dc2626 100%);
        border-radius: 20px;
        padding: 32px 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .product-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,.15);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .product-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -40px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,.1);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .product-hero .hero-content {
        position: relative;
        z-index: 2;
    }
    .product-hero .hero-icon {
        width: 70px; height: 70px;
        background: rgba(255,255,255,.25);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(10px);
        flex-shrink: 0;
    }
    .product-hero h3 { font-weight: 800; margin-bottom: 4px; }
    .product-hero p  { opacity: .95; margin-bottom: 0; }
    .product-hero .btn-light { transition: all .2s ease; }
    .product-hero .btn-light:hover {
        transform: translateX(-4px);
        box-shadow: 0 6px 18px rgba(0,0,0,.2);
    }

    /* ============ BREADCRUMB ============ */
    .custom-breadcrumb {
        background: #fff;
        border-radius: 12px;
        padding: 10px 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        margin-bottom: 20px;
    }
    .custom-breadcrumb .breadcrumb { margin-bottom: 0; font-size: 13px; }
    .custom-breadcrumb a {
        color: #ea580c;
        font-weight: 600;
        text-decoration: none;
        transition: color .2s;
    }
    .custom-breadcrumb a:hover { color: #f59e0b; }
    .custom-breadcrumb .breadcrumb-item.active { color: #64748b; font-weight: 600; }

    /* ============ MAIN CARD ============ */
    .product-detail-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,.08);
        overflow: hidden;
        background: #fff;
    }

    .product-cover-wrap {
        background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%);
        padding: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 480px;
        position: relative;
    }
    .product-cover-wrap img {
        max-width: 100%;
        max-height: 440px;
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(0,0,0,.28);
        transition: transform .4s ease;
        object-fit: cover;
    }
    .product-cover-wrap img:hover { transform: scale(1.04) rotate(-1deg); }

    .badge-featured {
        position: absolute; top: 18px; right: 18px;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: #fff; font-size: 12px; padding: 6px 14px;
        border-radius: 20px; font-weight: 700;
        box-shadow: 0 4px 12px rgba(245,158,11,.4);
    }
    .badge-discount {
        position: absolute; top: 18px; left: 18px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff; font-size: 12px; padding: 6px 14px;
        border-radius: 20px; font-weight: 700;
        box-shadow: 0 4px 12px rgba(239,68,68,.4);
        animation: pulse-badge 2s infinite;
    }
    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.05); }
    }

    .product-name {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 10px;
        line-height: 1.3;
    }
    .product-brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff7ed;
        color: #ea580c;
        font-weight: 600;
        font-size: 13px;
        padding: 6px 16px;
        border-radius: 20px;
        margin-bottom: 20px;
        border: 1px solid #fed7aa;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin: 22px 0;
    }
    .info-box {
        background: linear-gradient(135deg, #fff7ed, #fef3c7);
        border-radius: 14px;
        padding: 14px 12px;
        text-align: center;
        border: 1px solid #fed7aa;
        transition: all .25s ease;
    }
    .info-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(234,88,12,.15);
        border-color: #fb923c;
    }
    .info-box i {
        font-size: 20px;
        color: #ea580c;
        margin-bottom: 8px;
        display: block;
    }
    .info-box .label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .5px;
    }
    .info-box .value {
        font-size: 15px;
        font-weight: 800;
        color: #1e293b;
        margin-top: 3px;
        word-break: break-word;
    }

    .price-section {
        background: linear-gradient(135deg, #fff7ed, #fef3c7);
        border-radius: 16px;
        padding: 20px 24px;
        margin: 22px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        border: 1px solid #fed7aa;
    }
    .price-section .old-price {
        font-size: 15px;
        color: #94a3b8;
        text-decoration: line-through;
        display: block;
    }
    .price-section .new-price {
        font-size: 34px;
        font-weight: 800;
        color: #ea580c;
        line-height: 1;
    }

    .stock-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .stock-badge.in  { background: #dcfce7; color: #15803d; }
    .stock-badge.out { background: #fee2e2; color: #b91c1c; }

    .btn-buy-now {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: #fff;
        border: none;
        padding: 16px 32px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 16px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all .25s ease;
        text-decoration: none;
        width: 100%;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(234,88,12,.3);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .btn-buy-now::before {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        width: 0; height: 0;
        background: rgba(255,255,255,.25);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width .5s, height .5s;
    }
    .btn-buy-now:hover::before { width: 400px; height: 400px; }
    .btn-buy-now:hover {
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(234,88,12,.45);
    }
    .btn-buy-now > * { position: relative; z-index: 1; }
    .btn-buy-now.disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        pointer-events: none;
        opacity: .85;
        box-shadow: none;
    }

    .description-box {
        background: #fff;
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: 0 6px 20px rgba(0,0,0,.06);
        margin-top: 28px;
        border-left: 4px solid #ea580c;
    }
    .description-box h5 {
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .description-box h5 i { color: #ea580c; }
    .description-box p { color: #475569; line-height: 1.9; margin-bottom: 0; }

    .related-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,.06);
        transition: transform .3s ease, box-shadow .3s ease;
        background: #fff;
        height: 100%;
    }
    .related-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(234,88,12,.18);
    }
    .related-card img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        transition: transform .35s ease;
    }
    .related-card:hover img { transform: scale(1.06); }
    .related-card .card-body { padding: 14px 16px; }
    .related-card h6 {
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 40px;
    }
    .related-card small { color: #ea580c; font-weight: 700; font-size: 14px; }

    /* ============ SHIPPING MODAL STYLES ============ */
    .delivery-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .delivery-option {
        cursor: pointer;
        position: relative;
        display: block;
    }
    .delivery-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .delivery-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all .2s ease;
        background: #fff;
    }
    .delivery-option input[type="radio"]:checked + .delivery-card {
        border-color: #ea580c;
        background: #fff7ed;
        box-shadow: 0 4px 12px rgba(234,88,12,.15);
    }
    .delivery-option:hover .delivery-card {
        border-color: #fb923c;
    }

    .summary-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px 18px;
    }

    /* ============================================================
       ✅ SHIPPING MODAL SCROLL FIX
    ============================================================ */
    #shippingModal .modal-dialog {
        max-height: calc(100vh - 2rem);
    }

    #shippingModal .modal-content {
        max-height: calc(100vh - 2rem);
        height: calc(100vh - 2rem);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    #shippingModal .modal-header {
        flex-shrink: 0;
        flex-grow: 0;
    }

    /* ✅ form ট্যাগকে flex container বানাতে হবে */
    #shippingModal .modal-content > form {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
    }

    #shippingModal .modal-body {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        flex: 1 1 auto;
        min-height: 0;
    }

    #shippingModal .modal-footer {
        flex-shrink: 0;
        flex-grow: 0;
    }

    /* Custom scrollbar */
    #shippingModal .modal-body::-webkit-scrollbar {
        width: 8px;
    }
    #shippingModal .modal-body::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    #shippingModal .modal-body::-webkit-scrollbar-thumb {
        background: #fb923c;
        border-radius: 10px;
    }
    #shippingModal .modal-body::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
    }

    /* Mobile */
    @media (max-width: 991.98px) {
        #shippingModal .modal-dialog {
            margin: 0.5rem;
            max-height: calc(100vh - 1rem);
        }
        #shippingModal .modal-content {
            max-height: calc(100vh - 1rem);
            height: calc(100vh - 1rem);
        }
    }

    @media (max-width: 768px) {
        .product-cover-wrap { min-height: auto; padding: 20px; }
        .info-grid { grid-template-columns: 1fr 1fr; }
        .product-name { font-size: 22px; }
        .price-section .new-price { font-size: 28px; }
    }
</style>

<div class="container py-4">

    {{-- ============ HERO HEADER ============ --}}
    <div class="product-hero">
        <div class="hero-content d-flex align-items-center gap-3 flex-wrap">
            <div class="hero-icon">
                <i class="fas fa-shopping-bag fa-2x"></i>
            </div>
            <div class="flex-grow-1">
                <h3>প্রোডাক্ট শপ</h3>
                <p>সেরা মানের পণ্য — ঘরে বসেই অর্ডার করুন</p>
            </div>
            <div>
                <a href="{{ route('user.eproduct.shop') }}" class="btn btn-light fw-semibold">
                    <i class="fas fa-arrow-left"></i> সব প্রোডাক্ট দেখুন
                </a>
            </div>
        </div>
    </div>

    {{-- ============ BREADCRUMB ============ --}}
    <div class="custom-breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.user.home') }}">
                        <i class="fas fa-home"></i> হোম
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('user.eproduct.shop') }}">
                        <i class="fas fa-shopping-bag"></i> প্রোডাক্ট শপ
                    </a>
                </li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 320px;"
                    aria-current="page">
                    {{ $product->product_name }}
                </li>
            </ol>
        </nav>
    </div>

    {{-- ============ MAIN DETAIL CARD ============ --}}
    <div class="card product-detail-card">
        <div class="row g-0">

            {{-- Cover --}}
            <div class="col-lg-5">
                <div class="product-cover-wrap">
                    @if ($product->product_photo)
                        <img src="{{ asset('storage/' . $product->product_photo) }}"
                             alt="{{ $product->product_name }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80"
                             alt="Default Product">
                    @endif

                    @if ($product->is_featured)
                        <span class="badge-featured">
                            <i class="fas fa-star"></i> ফিচারড
                        </span>
                    @endif

                    @if ($product->has_discount)
                        <span class="badge-discount">
                            {{ (int) $product->discount }}% ছাড়
                        </span>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-7">
                <div class="p-4 p-lg-4">

                    <h1 class="product-name">{{ $product->product_name }}</h1>

                    @if ($product->brand_name)
                        <span class="product-brand-badge">
                            <i class="fas fa-copyright"></i> {{ $product->brand_name }}
                        </span>
                    @endif

                    {{-- Info boxes --}}
                    <div class="info-grid">
                        <div class="info-box">
                            <i class="fas fa-ruler-combined"></i>
                            <div class="label">সাইজ</div>
                            <div class="value">{{ $product->size ?? '—' }}</div>
                        </div>
                        <div class="info-box">
                            <i class="fas fa-palette"></i>
                            <div class="label">রং</div>
                            <div class="value">{{ $product->color ?? '—' }}</div>
                        </div>
                        <div class="info-box">
                            <i class="fas fa-shield-alt"></i>
                            <div class="label">ওয়ারেন্টি</div>
                            <div class="value">{{ $product->warranty ?? '—' }}</div>
                        </div>
                    </div>

                    {{-- Price Section --}}
                    <div class="price-section">
                        <div>
                            @if ($product->has_discount)
                                <span class="old-price">৳{{ number_format($product->price, 0) }}</span>
                                <span class="new-price">৳{{ number_format($product->customer_price, 0) }}</span>
                            @else
                                <span class="new-price">৳{{ number_format($product->customer_price, 0) }}</span>
                            @endif
                        </div>

                        <div>
                            @if ($product->quantity > 0)
                                <span class="stock-badge in">
                                    <i class="fas fa-check-circle"></i>
                                    স্টকে আছে ({{ $product->quantity }})
                                </span>
                            @else
                                <span class="stock-badge out">
                                    <i class="fas fa-times-circle"></i>
                                    স্টক শেষ
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Buy Button --}}
                    @if ($product->quantity > 0)
                        <button type="button"
                                class="btn-buy-now"
                                data-bs-toggle="modal"
                                data-bs-target="#shippingModal">
                            <i class="fas fa-shopping-cart"></i>
                            <span>এখনই কিনুন</span>
                        </button>
                    @else
                        <button class="btn-buy-now disabled" disabled>
                            <i class="fas fa-times-circle"></i>
                            <span>স্টক শেষ — শীঘ্রই আসছে</span>
                        </button>
                    @endif

                    <p class="text-muted small text-center mt-3 mb-0">
                        <i class="fas fa-shield-alt"></i>
                        নিরাপদ পেমেন্ট — ঘরে বসেই পণ্য হাতে পাবেন
                    </p>

                    <div class="text-center mt-2">
                        <small class="text-muted">
                            <i class="fas fa-eye"></i> {{ $product->view_count }} বার দেখা হয়েছে
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ============ DESCRIPTION ============ --}}
    @if ($product->description)
        <div class="description-box">
            <h5><i class="fas fa-align-left"></i> প্রোডাক্টের বিবরণ</h5>
            <p>{!! nl2br(e($product->description)) !!}</p>
        </div>
    @endif

    {{-- ============ RELATED PRODUCTS ============ --}}
    @if ($relatedProducts->count() > 0)
        <div class="mt-5">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-layer-group text-warning"></i>
                একই ব্র্যান্ডের অন্যান্য প্রোডাক্ট
            </h5>
            <div class="row g-3">
                @foreach ($relatedProducts as $related)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <a href="{{ route('user.eproduct.shop.show', $related->slug) }}"
                           class="text-decoration-none">
                            <div class="related-card">
                                @if ($related->product_photo)
                                    <img src="{{ asset('storage/' . $related->product_photo) }}"
                                         alt="{{ $related->product_name }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80"
                                         alt="Default">
                                @endif
                                <div class="card-body">
                                    <h6>{{ $related->product_name }}</h6>
                                    <small>৳{{ number_format($related->customer_price, 0) }}</small>
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
     SHIPPING MODAL
============================================================ --}}
@if ($product->quantity > 0)
<div class="modal fade" id="shippingModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 text-white"
                 style="background:linear-gradient(135deg,#f59e0b,#ea580c);">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold">শিপিং তথ্য দিন</h5>
                        <small class="opacity-75">ডেলিভারির জন্য আপনার তথ্য</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('user.eproduct.checkout', $product->slug) }}" method="POST">
                @csrf
                <div class="modal-body p-4">

                    {{-- Receiver Name --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            প্রাপকের নাম <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="shipping_name" class="form-control"
                                   placeholder="যিনি গ্রহণ করবেন তার নাম"
                                   value="{{ auth()->user()->full_name ?? '' }}" required>
                        </div>
                    </div>

                    {{-- Mobile Number --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            মোবাইল নম্বর <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                            <input type="tel" name="shipping_phone" class="form-control"
                                   placeholder="01XXXXXXXXX"
                                   value="{{ auth()->user()->phone ?? '' }}" required>
                        </div>
                    </div>

                    {{-- Full Address --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            সম্পূর্ণ ঠিকানা <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-muted"></i></span>
                            <textarea name="shipping_address" class="form-control" rows="2"
                                      placeholder="বাসা নং, রোড, এলাকা..." required>{{ auth()->user()->address ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- City + Postcode --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-semibold">
                                শহর / জেলা <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-city text-muted"></i></span>
                                <input type="text" name="shipping_city" class="form-control"
                                       placeholder="যেমন: ঢাকা, চট্টগ্রাম"
                                       value="{{ auth()->user()->city ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">পোস্ট কোড</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-mail-bulk text-muted"></i></span>
                                <input type="text" name="shipping_postcode" class="form-control"
                                       placeholder="1000"
                                       value="{{ auth()->user()->postcode ?? '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- Delivery Type --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">
                            ডেলিভারি ধরন <span class="text-danger">*</span>
                        </label>

                        <div class="delivery-options">
                            <label class="delivery-option">
                                <input type="radio" name="delivery_type" value="inside_dhaka" checked
                                       data-charge="100">
                                <div class="delivery-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-truck text-warning"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small">ঢাকার ভিতরে</div>
                                            <small class="text-muted">Inside Dhaka</small>
                                        </div>
                                        <div class="fw-bold text-warning">৳100</div>
                                    </div>
                                </div>
                            </label>

                            <label class="delivery-option">
                                <input type="radio" name="delivery_type" value="outside_dhaka"
                                       data-charge="130">
                                <div class="delivery-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-shipping-fast text-warning"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small">ঢাকার বাইরে</div>
                                            <small class="text-muted">Outside Dhaka</small>
                                        </div>
                                        <div class="fw-bold text-warning">৳130</div>
                                    </div>
                                </div>
                            </label>

                            <label class="delivery-option">
                                <input type="radio" name="delivery_type" value="self_receive"
                                       data-charge="0">
                                <div class="delivery-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-store text-warning"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small">নিজে সংগ্রহ</div>
                                            <small class="text-muted">Self Receive</small>
                                        </div>
                                        <div class="fw-bold text-success">ফ্রি</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="summary-box mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">প্রোডাক্ট মূল্য</span>
                            <span class="fw-semibold">৳<span id="product_total">{{ number_format($product->customer_price, 0) }}</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">শিপিং চার্জ</span>
                            <span class="fw-semibold">৳<span id="shipping_charge">100</span></span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">সর্বমোট</span>
                            <span class="fw-bold text-warning fs-5">
                                ৳<span id="grand_total">{{ number_format($product->customer_price + 100, 0) }}</span>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> বাতিল
                    </button>
                    <button type="submit" class="btn btn-warning text-white fw-bold">
                        <i class="fas fa-lock"></i> পেমেন্ট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ============ TOTAL CALC SCRIPT ============ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productPrice   = {{ (float) $product->customer_price }};
    const shippingEl     = document.getElementById('shipping_charge');
    const grandTotalEl   = document.getElementById('grand_total');

    document.querySelectorAll('input[name="delivery_type"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const charge = parseFloat(this.dataset.charge) || 0;
            if (shippingEl) shippingEl.textContent = charge.toFixed(0);
            if (grandTotalEl) grandTotalEl.textContent = (productPrice + charge).toFixed(0);
        });
    });
});
</script>

@endsection