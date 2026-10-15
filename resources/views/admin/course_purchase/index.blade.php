@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')

{{-- ====== Breadcrumb ====== --}}
<div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            </ol>
        </nav>
    </div>
</div>

{{-- ============================================================
SUMMARY STATS
============================================================ --}}
<div class="row g-3 mb-3">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; background: #eef2ff; border-radius: 12px;">
                    <i class="fas fa-shopping-bag text-primary fa-lg"></i>
                </div>
                <div>
                    <div class="small text-muted">Total Orders</div>
                    <div class="fw-bold fs-5">{{ $stats['total_orders'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; background: #ecfdf5; border-radius: 12px;">
                    <i class="fas fa-check-circle text-success fa-lg"></i>
                </div>
                <div>
                    <div class="small text-muted">Paid Orders</div>
                    <div class="fw-bold fs-5">{{ $stats['paid_orders'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px;">
                    <i class="fas fa-wallet text-warning fa-lg"></i>
                </div>
                <div>
                    <div class="small text-muted">Total Revenue</div>
                    <div class="fw-bold fs-5">৳{{ number_format($stats['total_revenue'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; background: #fdf2f8; border-radius: 12px;">
                    <i class="fas fa-users text-danger fa-lg"></i>
                </div>
                <div>
                    <div class="small text-muted">Total Commission</div>
                    <div class="fw-bold fs-5">৳{{ number_format($stats['total_commission'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
FILTER SECTION
============================================================ --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.course.purchase.index') }}">
            <div class="row g-2 align-items-end">

                {{-- User --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">User</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">All Users</option>
                        @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id')==$u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Course --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Course</label>
                    <select name="course_id" class="form-select form-select-sm">
                        <option value="">All Courses</option>
                        @foreach ($courses as $c)
                        <option value="{{ $c->id }}" {{ request('course_id')==$c->id ? 'selected' : '' }}>
                            {{ Str::limit($c->title, 30) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Course Type --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Course Type</label>
                    <select name="course_type_id" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach ($courseTypes as $type)
                        <option value="{{ $type->id }}" {{ request('course_type_id')==$type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Payment Status --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Payment Status</label>
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="paid" {{ request('payment_status')==='paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('payment_status')==='pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="failed" {{ request('payment_status')==='failed' ? 'selected' : '' }}>Failed
                        </option>
                        <option value="cancelled" {{ request('payment_status')==='cancelled' ? 'selected' : '' }}>
                            Cancelled</option>
                    </select>
                </div>

                {{-- From Date --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">From</label>
                    <input type="date" name="from_date" class="form-control form-control-sm"
                        value="{{ request('from_date') }}">
                </div>

                {{-- To Date --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">To</label>
                    <input type="date" name="to_date" class="form-control form-control-sm"
                        value="{{ request('to_date') }}">
                </div>

                {{-- Search --}}
                <div class="col-md-8 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                        value="{{ request('search') }}" placeholder="Order no, Txn ID, User name/email/phone...">
                </div>

                {{-- Buttons --}}
                <div class="col-md-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.course.purchase.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ============================================================
PURCHASE LIST
============================================================ --}}
<div class="main-content-body">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <p class="card-title my-0">
                        {{ $pageTitle }}
                        <span class="badge bg-danger side-badge" style="font-size:17px;">
                            {{ $orders->count() }}
                        </span>
                    </p>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file-datatable"
                            class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">SL</th>
                                    <th class="border-bottom-0">Order No</th>
                                    <th class="border-bottom-0">User</th>
                                    <th class="border-bottom-0">Course</th>
                                    <th class="border-bottom-0">Type / Class</th>
                                    <th class="border-bottom-0">Paid Amount</th>
                                    <th class="border-bottom-0">Payment</th>
                                    <th class="border-bottom-0">Date</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $key => $order)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $order->order_no }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- User Image --}}
                                            <img src="{{ !empty($order->user->image) 
                        ? url('upload/user/' . $order->user->image) 
                        : url('upload/avater.png') }}" alt="{{ $order->user->full_name ?? 'User' }}" style="width: 42px; height: 42px; object-fit: cover; border-radius: 50%;
                    border: 2px solid #e5e7eb;">

                                            {{-- User Info --}}
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $order->user->full_name ?? 'N/A' }}</span>
                                                <small class="text-muted" style="font-size: 11px;">
                                                    {{ $order->user->email ?? '' }}
                                                </small>
                                                @if ($order->user->phone)
                                                <small class="text-muted" style="font-size: 11px;">
                                                    <i class="fas fa-phone"></i> {{ $order->user->phone }}
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ Str::limit($order->course->title ?? 'N/A', 40) }}
                                    </td>
                                    <td>
                                        <small class="text-primary">
                                            {{ $order->course->courseType->name ?? '—' }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            {{ $order->course->courseClass->name ?? '—' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            ৳{{ number_format($order->paid_amount, 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($order->payment_method)
                                        <span class="badge bg-info">
                                            {{ ucfirst($order->payment_method) }}
                                        </span>
                                        @endif
                                        <br>
                                        <small class="text-muted" style="font-size: 10px;">
                                            {{ Str::limit($order->merchant_transaction_id, 15) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="small">
                                            {{ $order->paid_at?->format('d M Y') ?? '—' }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $order->paid_at?->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if ($order->payment_status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                        @elseif ($order->payment_status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif ($order->payment_status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                        @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.course.purchase.show', $order->id) }}"
                                            class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Print Voucher --}}
                                        <a href="{{ route('admin.course.purchase.voucher', $order->id) }}"
                                            class="btn btn-sm btn-primary" title="Print Voucher" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        <a href="{{ route('admin.course.purchase.delete', $order->id) }}"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure to delete this purchase record?')"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                        No purchase records found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection