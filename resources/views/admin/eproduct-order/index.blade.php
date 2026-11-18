@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container-fluid py-3">

    {{-- ============ HEADER ============ --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ $pageTitle }}</h4>
            <p class="text-muted small mb-0">সব ই-প্রোডাক্ট অর্ডারের তালিকা</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.eproduct.order.export', request()->only(['status', 'delivery_type', 'from_date', 'to_date'])) }}"
               class="btn btn-outline-success">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- ============ FLASH ============ --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="fas fa-check-circle fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                            <div class="fw-bold fs-4 mt-1 text-warning">৳{{ number_format($stats['today_revenue'], 0) }}</div>
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
            <form method="GET" action="{{ route('admin.eproduct.order.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                               value="{{ request('search') }}"
                               placeholder="Order no, txn id, buyer, product...">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="paid"      {{ request('status') == 'paid'      ? 'selected' : '' }}>Paid</option>
                            <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="failed"    {{ request('status') == 'failed'    ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-semibold mb-1">Delivery</label>
                        <select name="delivery_type" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <option value="inside_dhaka"  {{ request('delivery_type') == 'inside_dhaka'  ? 'selected' : '' }}>Inside Dhaka</option>
                            <option value="outside_dhaka" {{ request('delivery_type') == 'outside_dhaka' ? 'selected' : '' }}>Outside Dhaka</option>
                            <option value="self_receive"  {{ request('delivery_type') == 'self_receive'  ? 'selected' : '' }}>Self Receive</option>
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
                        @if (request()->hasAny(['search', 'status', 'delivery_type', 'from_date', 'to_date']))
                            <a href="{{ route('admin.eproduct.order.index') }}" class="btn btn-outline-secondary btn-sm">
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
                            <th>Product</th>
                            <th>Buyer</th>
                            <th>Order No</th>
                            <th>Delivery</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="ps-4">{{ $orders->firstItem() + $loop->index }}</td>

                                {{-- Product --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($order->eproduct && $order->eproduct->product_photo)
                                            <img src="{{ asset('storage/' . $order->eproduct->product_photo) }}"
                                                 style="width:42px;height:42px;object-fit:cover;border-radius:6px;">
                                        @else
                                            <div style="width:42px;height:42px;background:#fff7ed;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                                <i class="fas fa-box text-warning"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold small">
                                                {{ Str::limit($order->eproduct->product_name ?? 'N/A', 30) }}
                                            </div>
                                            <div class="text-muted" style="font-size:11px;">
                                                {{ $order->eproduct->brand_name ?? '' }}
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

                                {{-- Delivery --}}
                                <td>
                                    <span class="badge bg-light text-dark border small">
                                        {{ $order->delivery_label }}
                                    </span>
                                    @if ($order->shipping_charge > 0)
                                        <div class="text-muted" style="font-size:11px;">
                                            ৳{{ number_format($order->shipping_charge, 0) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Amount --}}
                                <td class="fw-bold" style="color:#ea580c;">
                                    ৳{{ number_format($order->paid_amount, 0) }}
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
                                        <a href="{{ route('admin.eproduct.order.show', $order->id) }}"
                                           class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- <button type="button" class="btn btn-outline-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $order->id }}"
                                                title="Change Status">
                                            <i class="fas fa-edit"></i>
                                        </button> --}}

                                        {{-- <form action="{{ route('admin.eproduct.order.delete', $order->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this order permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form> --}}
                                    </div>

                                    {{-- Status Modal --}}
                                    <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.eproduct.order.updateStatus', $order->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Status — {{ $order->order_no }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Payment Status</label>
                                                            <select name="payment_status" class="form-select" required>
                                                                <option value="pending"   {{ $order->payment_status == 'pending'   ? 'selected' : '' }}>Pending</option>
                                                                <option value="paid"      {{ $order->payment_status == 'paid'      ? 'selected' : '' }}>Paid</option>
                                                                <option value="failed"    {{ $order->payment_status == 'failed'    ? 'selected' : '' }}>Failed</option>
                                                                <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Order Status</label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="pending"   {{ $order->status == 'pending'   ? 'selected' : '' }}>Pending</option>
                                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3 d-block"></i>
                                    <h6 class="fw-bold">No orders found</h6>
                                    <p class="text-muted small mb-0">
                                        @if (request()->hasAny(['search', 'status', 'delivery_type', 'from_date', 'to_date']))
                                            Try clearing your filters
                                        @else
                                            Orders will appear here when customers buy products
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