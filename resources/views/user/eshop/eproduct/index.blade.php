@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

{{-- ============ CUSTOM STYLES ============ --}}
<style>
    .sc-hero {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 50%, #dc2626 100%);
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
        background: rgba(255, 255, 255, .15);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    .sc-hero .hero-content {
        position: relative;
        z-index: 2;
    }

    .sc-hero h3 { font-weight: 700; margin-bottom: 6px; }
    .sc-hero p  { opacity: .95; margin-bottom: 0; }

    .sc-hero .btn-light {
        transition: all .2s ease;
    }
    .sc-hero .btn-light:hover {
        transform: translateX(-4px);
        box-shadow: 0 6px 18px rgba(0,0,0,.2);
    }

    /* ===== Product Card ===== */
    .product-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
        transition: transform .25s ease, box-shadow .25s ease;
        height: 100%;
        background: #fff;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(234, 88, 12, .18);
    }

    .product-thumb {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #fff7ed, #fef3c7);
    }
    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }
    .product-card:hover .product-thumb img { transform: scale(1.06); }

    .product-thumb .badge-featured {
        position: absolute; top: 10px; right: 10px;
        background: #f59e0b; color: #fff;
        font-size: 11px; padding: 4px 10px;
        border-radius: 20px; font-weight: 600;
    }
    .product-thumb .badge-discount {
        position: absolute; bottom: 10px; left: 10px;
        background: #ef4444; color: #fff;
        font-size: 11px; padding: 4px 10px;
        border-radius: 20px; font-weight: 700;
    }
    .product-thumb .badge-out {
        position: absolute; bottom: 10px; right: 10px;
        background: #64748b; color: #fff;
        font-size: 11px; padding: 4px 10px;
        border-radius: 20px; font-weight: 700;
    }
    .product-thumb .badge-warranty {
        position: absolute; top: 10px; left: 10px;
        background: rgba(15, 23, 42, .75); color: #fff;
        font-size: 11px; padding: 4px 10px;
        border-radius: 20px; font-weight: 600;
        backdrop-filter: blur(4px);
    }

    .product-body { padding: 14px 16px 16px; }

    .product-title {
        font-weight: 700;
        font-size: 15px;
        color: #1e293b;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
    }
    .product-brand {
        font-size: 12px;
        color: #ea580c;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .product-brand i { margin-right: 4px; }

    .product-variants {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .product-variants .chip {
        font-size: 11px;
        padding: 3px 9px;
        border-radius: 20px;
        background: #fff7ed;
        color: #ea580c;
        font-weight: 600;
        border: 1px solid #fed7aa;
    }

    .product-stats {
        display: flex;
        gap: 12px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
    }
    .product-stats i { color: #94a3b8; margin-right: 3px; }

    .price-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 10px;
        border-top: 1px dashed #e5e7eb;
    }
    .price-block .old-price {
        font-size: 12px; color: #94a3b8;
        text-decoration: line-through; display: block;
    }
    .price-block .new-price {
        font-size: 18px; font-weight: 800; color: #ea580c;
    }

    .btn-buy {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: #fff; border: none;
        padding: 8px 16px; border-radius: 10px;
        font-weight: 600; font-size: 13px;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s ease;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-buy:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(234, 88, 12, .35);
    }
    .btn-buy.disabled {
        background: #cbd5e1;
        pointer-events: none;
        opacity: .8;
    }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 60px; color: #cbd5e1; margin-bottom: 16px; }
    .empty-state h5 { color: #475569; font-weight: 600; }
    .empty-state p  { color: #94a3b8; }
</style>

<div class="container py-4">

    {{-- ============ HERO ============ --}}
    <div class="sc-hero mb-4">
        <div class="hero-content d-flex align-items-center gap-3 flex-wrap">
            <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                style="width:62px;height:62px;background:rgba(255,255,255,.25);border-radius:16px;backdrop-filter:blur(8px);">
                <i class="fas fa-shopping-bag fa-2x"></i>
            </div>
            <div class="flex-grow-1">
                <h3>{{ $pageTitle }}</h3>
                <p>সেরা মানের পণ্য — ঘরে বসেই অর্ডার করুন</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="#" class="btn btn-light fw-semibold">
                    <i class="fas fa-shopping-bag"></i> আমার অর্ডার
                </a>
            </div>
        </div>
    </div>

    {{-- ============ PRODUCT GRID ============ --}}
    @if ($products->count() > 0)
        <div class="row g-4">
            @foreach ($products as $product)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="product-card">

                        {{-- Thumbnail --}}
                        <div class="product-thumb">
                            @if ($product->product_photo)
                                <img src="{{ asset('storage/' . $product->product_photo) }}"
                                     alt="{{ $product->product_name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80"
                                     alt="Default Product Image">
                            @endif

                            @if ($product->is_featured)
                                <span class="badge-featured">
                                    <i class="fas fa-star"></i> ফিচারড
                                </span>
                            @endif

                            @if ($product->warranty)
                                <span class="badge-warranty">
                                    <i class="fas fa-shield-alt"></i> {{ $product->warranty }}
                                </span>
                            @endif

                            @if ($product->has_discount)
                                <span class="badge-discount">
                                    {{ (int) $product->discount }}% ছাড়
                                </span>
                            @endif

                            @if ($product->quantity <= 0)
                                <span class="badge-out">
                                    <i class="fas fa-times-circle"></i> স্টক শেষ
                                </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="product-body">

                            <h6 class="product-title">{{ $product->product_name }}</h6>

                            @if ($product->brand_name)
                                <div class="product-brand">
                                    <i class="fas fa-copyright"></i> {{ $product->brand_name }}
                                </div>
                            @endif

                            {{-- Size & Color chips --}}
                            @if ($product->size || $product->color)
                                <div class="product-variants">
                                    @if ($product->size)
                                        <span class="chip">
                                            <i class="fas fa-ruler-combined"></i> {{ $product->size }}
                                        </span>
                                    @endif
                                    @if ($product->color)
                                        <span class="chip">
                                            <i class="fas fa-palette"></i> {{ $product->color }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="product-stats">
                                <span><i class="fas fa-eye"></i>{{ $product->view_count }}</span>
                                @if ($product->quantity > 0)
                                    <span><i class="fas fa-cubes"></i>{{ $product->quantity }} স্টকে</span>
                                @endif
                            </div>

                            <div class="price-row">
                                <div class="price-block">
                                    @if ($product->has_discount)
                                        <span class="old-price">৳{{ number_format($product->price, 0) }}</span>
                                        <span class="new-price">৳{{ number_format($product->customer_price, 0) }}</span>
                                    @else
                                        <span class="new-price">৳{{ number_format($product->customer_price, 0) }}</span>
                                    @endif
                                </div>

                                <a href="{{ route('user.eproduct.shop.show', $product->slug) }}"
                                   class="btn-buy {{ $product->quantity <= 0 ? 'disabled' : '' }}">
                                    <i class="fas fa-info-circle"></i>
                                    বিস্তারিত
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @else
        {{-- ============ EMPTY STATE ============ --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body empty-state">
                <i class="fas fa-box-open"></i>
                <h5>কোনো প্রোডাক্ট পাওয়া যায়নি</h5>
                <p>খুব শীঘ্রই নতুন প্রোডাক্ট যোগ করা হবে</p>
            </div>
        </div>
    @endif

</div>
@endsection