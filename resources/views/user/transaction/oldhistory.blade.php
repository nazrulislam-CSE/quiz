@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container">

    {{-- ================= Page Title ================= --}}
    <h3 class="mb-4">
        <i class="fas fa-history me-2"></i>
        {{ $pageTitle }}
    </h3>


    {{-- ================= Date Search ================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('user.transaction.history') }}">
                <div class="row align-items-end">

                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold">From Date</label>
                        <input type="date" name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold">To Date</label>
                        <input type="date" name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <a href="{{ route('user.transaction.history') }}"
                           class="btn btn-secondary">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>



    {{-- ================= TOP SUMMARY — LEFT: মোট ব্যালেন্স | RIGHT: মোট আয় ================= --}}
    <div class="row g-3 mb-4">

        {{-- 💰 মোট ব্যালেন্স — LEFT --}}
        <div class="col-md-6 col-6">
            <div class="card border-0 shadow-sm h-100"
                 style="background: linear-gradient(135deg, #198754, #20c997);">
                <div class="card-body text-white d-flex align-items-center">

                    <div class="me-3">
                        <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center"
                             style="width:55px;height:55px;">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>

                    <div>
                        <small class="d-block">মোট ব্যালেন্স</small>
                        <h4 class="mb-0 fw-bold">
                            ৳{{ number_format($mainWalletBalance ?? 0, 2) }}
                        </h4>
                    </div>

                </div>
            </div>
        </div>


        {{-- 💵 মোট আয় — RIGHT --}}
        <div class="col-md-6 col-6">
            <div class="card border-0 shadow-sm h-100"
                 style="background: linear-gradient(135deg, #0d6efd, #0dcaf0);">
                <div class="card-body text-white d-flex align-items-center">

                    <div class="me-3">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center"
                             style="width:55px;height:55px;">
                            <i class="fas fa-coins fa-2x"></i>
                        </div>
                    </div>

                    <div>
                        <small class="d-block">মোট আয়</small>
                        <h4 class="mb-0 fw-bold">
                            ৳{{ number_format($incomeWalletBalance ?? 0, 2) }}
                        </h4>
                    </div>

                </div>
            </div>
        </div>

    </div>
    {{-- ================= END TOP SUMMARY ================= --}}



    {{-- ================= Transaction Table ================= --}}
    <div class="card shadow border-0">

        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-exchange-alt me-2"></i>
                {{ $pageTitle }}
            </h5>
        </div>


        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0">

                    {{-- ============ Header ============ --}}
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>তারিখ</th>
                            <th>ধরন</th>
                            <th>Status</th>
                            <th>উদ্দেশ্য</th>
                            <th>Wallet</th>
                            <th>পরিমাণ</th>
                        </tr>
                    </thead>


                    {{-- ============ Body ============ --}}
                    <tbody>

                        @forelse($transactions as $transaction)

                            @php
                                $purpose = $transaction->purpose ?? '';
                                $out     = $transaction->out ?? '';

                                // ============================================
                                // 🎯 Type Determine (out column base)
                                // ============================================
                                if ($out === 'in') {
                                    // EPS Deposit + Deposit Commission
                                    $type         = 'Credit';
                                    $typeBadge    = 'success';
                                    $amountPrefix = '+';
                                } elseif ($out === 'referral') {
                                    // Referral Commission
                                    $type         = 'Credit';
                                    $typeBadge    = 'success';
                                    $amountPrefix = '+';
                                } elseif ($out === 'deposit') {
                                    // Recharge Commission
                                    $type         = 'Credit';
                                    $typeBadge    = 'success';
                                    $amountPrefix = '+';
                                } elseif ($out === 'transfer') {
                                    // Income → Main Transfer
                                    $type         = 'Transfer';
                                    $typeBadge    = 'info';
                                    $amountPrefix = '';
                                } else {
                                    // exam_fee, recharge, withdraw
                                    $type         = 'Debit';
                                    $typeBadge    = 'danger';
                                    $amountPrefix = '-';
                                }

                                // ============================================
                                // 🎯 Wallet Determine
                                // ============================================
                                if ($out === 'in') {
                                    // EPS Deposit + Deposit Commission → Main
                                    $wallet      = 'Main Wallet';
                                    $walletColor = 'success';
                                } elseif ($out === 'exam_fee') {
                                    // Exam Fee → Main
                                    $wallet      = 'Main Wallet';
                                    $walletColor = 'success';
                                } elseif (in_array($out, ['referral', 'deposit', 'withdraw', 'recharge'])) {
                                    // Income Wallet
                                    $wallet      = 'Income Wallet';
                                    $walletColor = 'primary';
                                } elseif ($out === 'transfer') {
                                    // Transfer: Income → Main
                                    $wallet      = 'Income → Main';
                                    $walletColor = 'info';
                                } else {
                                    $wallet      = '-';
                                    $walletColor = 'secondary';
                                }
                            @endphp

                            <tr>
                                {{-- Serial --}}
                                <td>{{ $transactions->firstItem() + $loop->index }}</td>


                                {{-- Date --}}
                                <td class="text-nowrap">
                                    {{ $transaction->created_at?->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $transaction->created_at?->format('h:i A') }}
                                    </small>
                                </td>


                                {{-- Type --}}
                                <td>
                                    <span class="badge bg-{{ $typeBadge }}">
                                        @if($type === 'Credit')
                                            🟢 Credit
                                        @elseif($type === 'Debit')
                                            🔴 Debit
                                        @else
                                            🔄 Transfer
                                        @endif
                                    </span>
                                </td>


                                {{-- Status --}}
                                <td>
                                    @if(in_array($transaction->status, ['success', 'approved']))
                                        <span class="badge bg-success">সফল</span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="badge bg-warning text-dark">অপেক্ষমাণ</span>
                                    @elseif($transaction->status === 'failed')
                                        <span class="badge bg-danger">ব্যর্থ</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $transaction->status ?? '-' }}
                                        </span>
                                    @endif
                                </td>


                                {{-- Purpose --}}
                                <td>
                                    <span class="fw-semibold">
                                        {{ $purpose ?: '-' }}
                                    </span>
                                </td>


                                {{-- Wallet --}}
                                <td>
                                    <span class="badge bg-{{ $walletColor }}">
                                        @if($wallet === 'Main Wallet')
                                            💰 {{ $wallet }}
                                        @elseif($wallet === 'Income Wallet')
                                            💵 {{ $wallet }}
                                        @elseif($wallet === 'Income → Main')
                                            🔄 {{ $wallet }}
                                        @else
                                            {{ $wallet }}
                                        @endif
                                    </span>
                                </td>


                                {{-- Amount --}}
                                <td class="text-nowrap">
                                    <strong class="text-{{ $typeBadge }}">
                                        {{ $amountPrefix }}৳{{ number_format($transaction->amount ?? 0, 2) }}
                                    </strong>
                                </td>
                            </tr>

                        @empty

                            {{-- No Transaction --}}
                            <tr>
                                <td colspan="7" class="py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                        <br>
                                        কোনো লেনদেন পাওয়া যায়নি।
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                    {{-- ❌ tfoot বাদ --}}

                </table>
            </div>


            {{-- ================= Pagination ================= --}}
            @if($transactions->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                    <div class="text-muted">
                        Showing
                        <strong>{{ $transactions->firstItem() }}</strong>
                        to
                        <strong>{{ $transactions->lastItem() }}</strong>
                        of
                        <strong>{{ $transactions->total() }}</strong>
                        transactions
                    </div>
                    <div>
                        {{ $transactions->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection