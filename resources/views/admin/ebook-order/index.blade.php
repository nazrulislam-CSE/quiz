@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container-fluid py-3">

    {{-- ============ HEADER ============ --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-light">{{ $pageTitle }}</h4>
            <p class="text-light small mb-0">সব ই-বুক অর্ডারের তালিকা</p>
        </div>
    </div>

    {{-- ============ STATS CARDS ============ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Total Orders</div>
                            <div class="fw-bold fs-4 mt-1">{{ number_format($stats['total_orders']) }}</div>
                        </div>
                        <div class="bg-primary-subtle rounded-3 d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fas fa-shopping-bag text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Paid Orders</div>
                            <div class="fw-bold fs-4 mt-1 text-success">{{ number_format($stats['paid_orders']) }}</div>
                        </div>
                        <div class="bg-success-subtle rounded-3 d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Total Revenue</div>
                            <div class="fw-bold fs-4 mt-1">৳{{ number_format($stats['total_revenue'], 0) }}</div>
                        </div>
                        <div class="bg-info-subtle rounded-3 d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fas fa-money-bill-wave text-info fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">Today Revenue</div>
                            <div class="fw-bold fs-4 mt-1 text-success">৳{{ number_format($stats['today_revenue'], 0) }}
                            </div>
                        </div>
                        <div class="bg-warning-subtle rounded-3 d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fas fa-calendar-day text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ FILTER FORM ============ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ebook.order.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="{{ request('search') }}" placeholder="Order no, txn id, buyer, book...">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('status')=='paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Book</label>
                        <select name="book_id" class="form-select form-select-sm">
                            <option value="">All Books</option>
                            @foreach ($books as $b)
                            <option value="{{ $b->id }}" {{ request('book_id')==$b->id ? 'selected' : '' }}>
                                {{ Str::limit($b->book_name, 30) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">From</label>
                        <input type="date" name="from_date" class="form-control form-control-sm"
                            value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">To</label>
                        <input type="date" name="to_date" class="form-control form-control-sm"
                            value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search"></i>
                        </button>
                        @if (request()->hasAny(['search', 'status', 'book_id', 'from_date', 'to_date']))
                        <a href="{{ route('admin.ebook.order.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ TABLE ============ --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width:40px;">#</th>
                            <th>Book</th>
                            <th>Buyer</th>
                            <th>Order No</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td class="ps-4">{{ $orders->firstItem() + $loop->index }}</td>

                            {{-- Book --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($order->ebook && $order->ebook->book_photo)
                                    <img src="{{ asset('storage/' . $order->ebook->book_photo) }}"
                                        style="width:38px;height:52px;object-fit:cover;border-radius:5px;">
                                    @else
                                    <div
                                        style="width:38px;height:52px;background:#eef2ff;border-radius:5px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold small">
                                            {{ Str::limit($order->ebook->book_name ?? 'N/A', 30) }}
                                        </div>
                                        <div class="text-muted" style="font-size:11px;">
                                            {{ $order->ebook->author_name ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Buyer --}}
                            <td>
                                <div class="small fw-semibold">{{ $order->buyer_name }}</div>
                                <div class="text-muted" style="font-size:11px;">
                                    {{ $order->buyer_email }}
                                </div>
                                @if ($order->buyer_phone)
                                <div class="text-muted" style="font-size:11px;">
                                    {{ $order->buyer_phone }}
                                </div>
                                @endif
                            </td>

                            {{-- Order No --}}
                            <td>
                                <code class="small">{{ $order->order_no }}</code>
                            </td>

                            {{-- Amount --}}
                            <td class="fw-bold text-primary">
                                ৳{{ number_format($order->paid_amount, 0) }}
                            </td>

                            {{-- Payment Method --}}
                            <td>
                                <span class="badge bg-light text-dark border small">
                                    {{ strtoupper($order->payment_method ?? 'EPS') }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if ($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="fas fa-check-circle"></i> Paid
                                </span>
                                @elseif ($order->payment_status === 'pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                                @elseif ($order->payment_status === 'failed')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    <i class="fas fa-times-circle"></i> Failed
                                </span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary border">
                                    Cancelled
                                </span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td class="small text-muted">
                                {{ $order->created_at->format('d M, Y') }}
                                <div style="font-size:11px;">{{ $order->created_at->format('h:i A') }}</div>
                            </td>

                            {{-- Actions --}}
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.ebook.order.show', $order->id) }}"
                                        class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($order->status == 'completed')

                                    @else
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                        data-bs-target="#statusModal{{ $order->id }}" title="Change Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endif

                                    <form action="{{ route('admin.ebook.order.delete', $order->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure to delete this order?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- Status Modal --}}
                                <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.ebook.order.updateStatus', $order->id) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Status — {{ $order->order_no }}</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Payment Status</label>
                                                        <select name="payment_status" class="form-select" required>
                                                            <option value="pending" {{ $order->payment_status ==
                                                                'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="paid" {{ $order->payment_status == 'paid' ?
                                                                'selected' : '' }}>Paid</option>
                                                            <option value="failed" {{ $order->payment_status == 'failed'
                                                                ? 'selected' : '' }}>Failed</option>
                                                            <option value="cancelled" {{ $order->payment_status ==
                                                                'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Order Status</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="pending" {{ $order->status == 'pending' ?
                                                                'selected' : '' }}>Pending</option>
                                                            <option value="completed" {{ $order->status == 'completed' ?
                                                                'selected' : '' }}>Completed</option>
                                                            <option value="cancelled" {{ $order->status == 'cancelled' ?
                                                                'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-3x text-muted mb-3 d-block"></i>
                                <h6 class="fw-bold">No orders found</h6>
                                <p class="text-muted small mb-0">
                                    @if (request()->hasAny(['search', 'status', 'book_id', 'from_date', 'to_date']))
                                    Try clearing your filters
                                    @else
                                    Orders will appear here when customers buy books
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($orders->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                of {{ $orders->total() }} orders
            </small>
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection