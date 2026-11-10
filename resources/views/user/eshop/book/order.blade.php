@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container py-4">

    {{-- Hero --}}
    <div class="sc-hero mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
                border-radius: 18px; padding: 28px 24px; color:#fff; position:relative; overflow:hidden;">
        <div class="d-flex align-items-center gap-3 flex-wrap" style="position: relative; z-index: 2;">
            <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                style="width:62px;height:62px;background:rgba(255,255,255,.2);border-radius:16px;backdrop-filter:blur(8px);">
                <i class="fas fa-shopping-bag fa-2x"></i>
            </div>
            <div class="flex-grow-1">
                <h3 class="fw-bold mb-1">{{ $pageTitle }}</h3>
                <p class="mb-0 opacity-75">আপনার কেনা সব বইয়ের তালিকা</p>
            </div>
            <div>
                <a href="{{ route('user.book.shop') }}" class="btn btn-light fw-semibold">
                    <i class="fas fa-arrow-left"></i> বুক শপে ফিরুন
                </a>
            </div>
        </div>
    </div>

    @if ($orders->count() > 0)
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">বই</th>
                            <th>অর্ডার নং</th>
                            <th>পরিমাণ</th>
                            <th>স্ট্যাটাস</th>
                            <th>তারিখ</th>
                            <th class="text-end pe-4">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    @if ($order->ebook && $order->ebook->book_photo)
                                    <img src="{{ asset('storage/' . $order->ebook->book_photo) }}"
                                        style="width:45px;height:60px;object-fit:cover;border-radius:6px;">
                                    @endif
                                    <div>
                                        <div class="fw-bold small">
                                            {{ $order->ebook->book_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-muted" style="font-size:11px;">
                                            {{ $order->ebook->author_name ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="small">{{ $order->order_no }}</code></td>
                            <td class="fw-bold text-primary">৳{{ number_format($order->paid_amount, 0) }}</td>
                            <td>
                                @if ($order->payment_status === 'paid')
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle">Paid</span>
                                @elseif ($order->payment_status === 'pending')
                                <span
                                    class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{
                                    ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $order->created_at->format('d M, Y') }}
                            </td>
                            <td class="text-end pe-4">
                                @if ($order->ebook)
                                <a href="{{ route('user.book.shop.show', $order->ebook->slug) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> দেখুন
                                </a>
                                @endif

                                @if ($order->payment_status === 'paid')
                                <a href="{{ route('user.book.orders.voucher', $order->order_no) }}"
                                    class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fas fa-receipt"></i> Voucher
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
    @else
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body text-center py-5">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
            <h5 class="fw-bold">এখনো কোনো বই কেনা হয়নি</h5>
            <p class="text-muted">বুক শপ থেকে আপনার পছন্দের বই কিনুন</p>
            <a href="{{ route('user.book.shop') }}" class="btn btn-primary mt-2">
                <i class="fas fa-book-open"></i> বুক শপে যান
            </a>
        </div>
    </div>
    @endif

</div>
@endsection