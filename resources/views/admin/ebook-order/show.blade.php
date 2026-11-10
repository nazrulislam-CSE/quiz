@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-light">Order Details</h4>
            <p class="text-light small mb-0">Order No: <strong>{{ $order->order_no }}</strong></p>
        </div>
        <a href="{{ route('admin.ebook.order.index') }}" class="btn btn-danger">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row g-3">

        {{-- Left: Book + Payment --}}
        <div class="col-lg-8">
            {{-- Book Info --}}
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0"><i class="fas fa-book text-primary"></i> Book Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3">
                        @if ($order->ebook && $order->ebook->book_photo)
                        <img src="{{ asset('storage/' . $order->ebook->book_photo) }}"
                            style="width:90px;height:120px;object-fit:cover;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.15);">
                        @endif
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">{{ $order->ebook->book_name ?? 'N/A' }}</h5>
                            <p class="text-primary small fw-semibold mb-2">
                                <i class="fas fa-user-edit"></i> {{ $order->ebook->author_name ?? '' }}
                            </p>
                            <div class="d-flex gap-3 small text-muted flex-wrap">
                                @if ($order->ebook && $order->ebook->total_page)
                                <span><i class="fas fa-file-alt"></i> {{ $order->ebook->total_page }} pages</span>
                                @endif
                                <span><i class="fas fa-eye"></i> {{ $order->ebook->view_count ?? 0 }} views</span>
                                @if ($order->ebook)
                                <a href="{{ route('user.book.shop.show', $order->ebook->slug) }}" target="_blank"
                                    class="text-decoration-none">
                                    <i class="fas fa-external-link-alt"></i> View on front
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0"><i class="fas fa-credit-card text-primary"></i> Payment Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width:200px;">Payment Method</td>
                            <td class="fw-semibold">{{ strtoupper($order->payment_method ?? 'EPS') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Merchant Transaction ID</td>
                            <td class="fw-semibold" style="word-break:break-all;">
                                <code>{{ $order->merchant_transaction_id ?? '—' }}</code>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">EPS Transaction ID</td>
                            <td class="fw-semibold" style="word-break:break-all;">
                                <code>{{ $order->eps_transaction_id ?? '—' }}</code>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Payment Status</td>
                            <td>
                                @if ($order->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                                @elseif ($order->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Order Status</td>
                            <td>
                                <span class="badge bg-info text-light">{{ ucfirst($order->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Paid At</td>
                            <td class="fw-semibold">
                                {{ $order->paid_at?->format('d M Y, h:i A') ?? '—' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: Buyer + Summary --}}
        <div class="col-lg-4">

            {{-- Buyer --}}
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0"><i class="fas fa-user text-primary"></i> Buyer</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ $order->user && $order->user->image
                                        ? url('upload/user/' . $order->user->image)
                                        : url('upload/avater.png') }}"
                            style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;">
                        <div>
                            <div class="fw-bold">{{ $order->buyer_name }}</div>
                            <small class="text-muted">User #{{ $order->user_id }}</small>
                        </div>
                    </div>
                    <div class="small">
                        <div class="mb-1">
                            <i class="fas fa-envelope text-muted"></i>
                            {{ $order->buyer_email }}
                        </div>
                        @if ($order->buyer_phone)
                        <div class="mb-1">
                            <i class="fas fa-phone text-muted"></i>
                            {{ $order->buyer_phone }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0"><i class="fas fa-receipt text-primary"></i> Summary</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Original Price</td>
                            <td class="text-end">৳{{ number_format($order->price, 2) }}</td>
                        </tr>
                        @if ($order->discount > 0)
                        <tr>
                            <td class="text-muted">Discount ({{ (int) $order->discount }}%)</td>
                            <td class="text-end text-success">− ৳{{ number_format($order->price - $order->paid_amount,
                                2) }}</td>
                        </tr>
                        @endif
                        <tr class="border-top">
                            <td class="fw-bold">Total Paid</td>
                            <td class="text-end fw-bold text-primary fs-5">
                                ৳{{ number_format($order->paid_amount, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($order->status == 'completed')
            @else
            {{-- Actions --}}
            <div class="mt-3 d-grid gap-2">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#updateStatusModal">
                    <i class="fas fa-edit"></i> Update Status
                </button>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- Update Status Modal --}}
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.ebook.order.updateStatus', $order->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select" required>
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed
                            </option>
                            <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : ''
                                }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection