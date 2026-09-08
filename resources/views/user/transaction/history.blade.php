@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container">

    {{-- Page Title --}}
    <h3 class="mb-4">
        <i class="fas fa-history me-2"></i>
        {{ $pageTitle }}
    </h3>


    {{-- Date Search --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET" action="{{ route('user.transaction.history') }}">

                <div class="row align-items-end">

                    {{-- From Date --}}
                    <div class="col-md-4 mb-3 mb-md-0">

                        <label class="form-label fw-bold">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                        >

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-4 mb-3 mb-md-0">

                        <label class="form-label fw-bold">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                        >

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-4">

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search me-1"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('user.transaction.history') }}"
                            class="btn btn-secondary"
                        >
                            <i class="fas fa-sync-alt me-1"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="row g-3 mb-4">

        {{-- Total Received --}}
        <div class="col-md-4 col-sm-6">

            <div
                class="card border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #198754, #20c997);"
            >

                <div class="card-body text-white d-flex align-items-center">

                    <div class="me-3">

                        <div
                            class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center"
                            style="width:60px;height:60px;"
                        >
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>

                    </div>

                    <div>

                        <small class="d-block">
                            মোট পাওয়া টাকা
                        </small>

                        <h4 class="mb-0 fw-bold">
                            {{ number_format($totalReceived, 2) }}
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Transaction --}}
        <div class="col-md-4 col-sm-6">

            <div
                class="card border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #0d6efd, #0dcaf0);"
            >

                <div class="card-body text-white d-flex align-items-center">

                    <div class="me-3">

                        <div
                            class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center"
                            style="width:60px;height:60px;"
                        >
                            <i class="fas fa-exchange-alt fa-2x"></i>
                        </div>

                    </div>

                    <div>

                        <small class="d-block">
                            মোট লেনদেন
                        </small>

                        <h4 class="mb-0 fw-bold">
                            {{ number_format($totalTransactions) }}
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Amount --}}
        <div class="col-md-4 col-sm-6">

            <div
                class="card border-0 shadow-sm h-100"
                style="background: linear-gradient(135deg, #6f42c1, #d63384);"
            >

                <div class="card-body text-white d-flex align-items-center">

                    <div class="me-3">

                        <div
                            class="bg-white text-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width:60px;height:60px;"
                        >
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>

                    </div>

                    <div>

                        <small class="d-block">
                            মোট লেনদেনের পরিমাণ
                        </small>

                        <h4 class="mb-0 fw-bold">
                            {{ number_format($totalAmount, 2) }}
                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>
    {{-- ================= END SUMMARY CARDS ================= --}}


    {{-- Transaction Table --}}
    <div class="card shadow border-0">

        {{-- Card Header --}}
        <div class="card-header bg-success text-white">

            <h5 class="mb-0">
                <i class="fas fa-exchange-alt me-2"></i>
                {{ $pageTitle }}
            </h5>

        </div>


        <div class="card-body">

            {{-- Table --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle text-center mb-0">

                    {{-- Table Header --}}
                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                তারিখ
                            </th>

                            <th>
                                ধরন
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                উদ্দেশ্য
                            </th>

                            <th>
                                পরিমাণ
                            </th>

                        </tr>

                    </thead>


                    {{-- Table Body --}}
                    <tbody>

                        @forelse($transactions as $transaction)

                        <tr>

                            {{-- Serial --}}
                            <td>
                                {{ $transactions->firstItem() + $loop->index }}
                            </td>


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

                                @if($transaction->out)

                                    <span class="badge bg-info text-dark">
                                        {{ $transaction->out }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($transaction->status === 'success')

                                    <span class="badge bg-success">
                                        সফল
                                    </span>

                                @elseif($transaction->status === 'pending')

                                    <span class="badge bg-warning text-dark">
                                        অপেক্ষমাণ
                                    </span>

                                @elseif($transaction->status === 'failed')

                                    <span class="badge bg-danger">
                                        ব্যর্থ
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $transaction->status ?? '-' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Purpose --}}
                            <td>
                                {{ $transaction->purpose ?? '-' }}
                            </td>


                            {{-- Amount --}}
                            <td class="text-nowrap">

                                <strong class="text-success">

                                    {{ number_format($transaction->amount ?? 0, 2) }}

                                </strong>

                            </td>

                        </tr>


                        @empty

                        {{-- No Transaction --}}
                        <tr>

                            <td colspan="6" class="py-5">

                                <div class="text-muted">

                                    <i class="fas fa-info-circle fa-2x mb-2"></i>

                                    <br>

                                    কোনো লেনদেন পাওয়া যায়নি।

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>


                    {{-- Table Total --}}
                    @if($transactions->count() > 0)

                    <tfoot>

                        <tr class="table-success fw-bold">

                            <td colspan="5" class="text-end">
                                মোট:
                            </td>

                            <td class="text-nowrap">

                                {{ number_format($totalAmount, 2) }}

                            </td>

                        </tr>

                    </tfoot>

                    @endif

                </table>

            </div>


            {{-- Pagination --}}
            @if($transactions->hasPages())

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">

                <div class="text-muted">

                    Showing

                    <strong>
                        {{ $transactions->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $transactions->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $transactions->total() }}
                    </strong>

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
