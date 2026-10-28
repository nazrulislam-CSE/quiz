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
        pointer-events: none;   /* ✅ বাটন ক্লিক ব্লক আটকাতে */
        z-index: 0;             /* ✅ */
    }

    .sc-hero .hero-content {
        position: relative;
        z-index: 2;             /* ✅ পুরো কনটেন্ট বৃত্তের উপরে */
    }

    .sc-hero h3 { font-weight: 700; margin-bottom: 6px; }
    .sc-hero p  { opacity: .9; margin-bottom: 0; }

    .sc-hero .btn-light {
        transition: all .2s ease;
    }
    .sc-hero .btn-light:hover {
        transform: translateX(-4px);
        box-shadow: 0 6px 18px rgba(0,0,0,.2);
    }

    /* ===== Book Card ===== */
    .book-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
        transition: transform .25s ease, box-shadow .25s ease;
        height: 100%;
        background: #fff;
    }
    .book-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(79, 70, 229, .18);
    }

    .book-thumb {
        position: relative;
        height: 240px;
        overflow: hidden;
        background: linear-gradient(135deg, #eef2ff, #fdf2f8);
    }
    .book-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }
    .book-card:hover .book-thumb img { transform: scale(1.06); }

    .book-thumb .badge-featured {
        position: absolute; top: 10px; right: 10px;
        background: #f59e0b; color: #fff;
        font-size: 11px; padding: 4px 10px;
        border-radius: 20px; font-weight: 600;
    }
    .book-thumb .badge-discount {
        position: absolute; bottom: 10px; left: 10px;
        background: #ef4444; color: #fff;
        font-size: 11px; padding: 4px 10px;
        border-radius: 20px; font-weight: 700;
    }
    .book-thumb .badge-free {
        position: absolute; top: 10px; left: 10px;
        background: #10b981; color: #fff;
        font-size: 11px; padding: 4px 10px;
        border-radius: 20px; font-weight: 600;
    }
    .book-thumb .badge-out {
        position: absolute; bottom: 10px; right: 10px;
        background: #64748b; color: #fff;
        font-size: 11px; padding: 4px 10px;
        border-radius: 20px; font-weight: 700;
    }

    .book-body { padding: 14px 16px 16px; }

    .book-title {
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
    .book-author {
        font-size: 12px;
        color: #7c3aed;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .book-author i { margin-right: 4px; }

    .book-stats {
        display: flex;
        gap: 12px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
    }
    .book-stats i { color: #94a3b8; margin-right: 3px; }

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
        font-size: 18px; font-weight: 800; color: #4f46e5;
    }
    .price-block .free-tag {
        font-size: 18px; font-weight: 800; color: #10b981;
    }

    .btn-buy {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
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
        box-shadow: 0 8px 18px rgba(124, 58, 237, .35);
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
                style="width:62px;height:62px;background:rgba(255,255,255,.2);border-radius:16px;backdrop-filter:blur(8px);">
                <i class="fas fa-book-open fa-2x"></i>
            </div>
            <div class="flex-grow-1">
                <h3>{{ $pageTitle }}</h3>
                <p>ডিজিটাল বই — ঘরে বসেই অর্ডার করুন</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="#" onclick="showOrdersSoon()" class="btn btn-light fw-semibold">
                    <i class="fas fa-shopping-bag"></i> আমার অর্ডার
                </a>
            </div>
        </div>
    </div>

    {{-- ============ BOOK GRID ============ --}}
    @if ($books->count() > 0)
        <div class="row g-4">
            @foreach ($books as $book)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="book-card">

                        {{-- Thumbnail --}}
                        <div class="book-thumb">
                            @if ($book->book_photo)
                                <img src="{{ asset('storage/' . $book->book_photo) }}"
                                     alt="{{ $book->book_name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&q=80"
                                     alt="Default Book Image">
                            @endif

                            @if ($book->is_featured)
                                <span class="badge-featured">
                                    <i class="fas fa-star"></i> ফিচারড
                                </span>
                            @endif

                            @if ($book->sell_price == 0 && $book->price == 0)
                                <span class="badge-free">ফ্রি</span>
                            @elseif ($book->discount > 0)
                                <span class="badge-discount">
                                    {{ (int) $book->discount }}% ছাড়
                                </span>
                            @endif

                            @if ($book->quantity <= 0)
                                <span class="badge-out">
                                    <i class="fas fa-times-circle"></i> স্টক শেষ
                                </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="book-body">

                            <h6 class="book-title">{{ $book->book_name }}</h6>

                            <div class="book-author">
                                <i class="fas fa-user-edit"></i> {{ $book->author_name }}
                            </div>

                            <div class="book-stats">
                                @if ($book->total_page)
                                    <span><i class="fas fa-file-alt"></i>{{ $book->total_page }} পৃষ্ঠা</span>
                                @endif
                                <span><i class="fas fa-eye"></i>{{ $book->view_count }}</span>
                            </div>

                            <div class="price-row">
                                <div class="price-block">
                                    @if ($book->price == 0)
                                        <span class="free-tag">ফ্রি</span>
                                    @elseif ($book->discount > 0)
                                        <span class="old-price">৳{{ number_format($book->price, 0) }}</span>
                                        <span class="new-price">৳{{ number_format($book->sell_price, 0) }}</span>
                                    @else
                                        <span class="new-price">৳{{ number_format($book->price, 0) }}</span>
                                    @endif
                                </div>

                                <a href="{{ route('user.book.shop.show', $book->slug) }}"
                                   class="btn-buy {{ $book->quantity <= 0 ? 'disabled' : '' }}">
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
            {{ $books->links() }}
        </div>
    @else
        {{-- ============ EMPTY STATE ============ --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body empty-state">
                <i class="fas fa-book"></i>
                <h5>কোনো বই পাওয়া যায়নি</h5>
                <p>খুব শীঘ্রই নতুন বই যোগ করা হবে</p>
            </div>
        </div>
    @endif

</div>
@push('scripts')
<script>
    function showOrdersSoon() {
        if (typeof toastr !== 'undefined') {
            toastr.info('শীঘ্রই আসছে! অর্ডার লিস্ট ফিচারটি তৈরি হচ্ছে 🚀');
        } else {
            alert('শীঘ্রই আসছে!');
        }
    }
</script>
@endpush
@endsection