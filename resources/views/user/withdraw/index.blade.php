@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">{{ $pageTitle }}</h3>
        <a href="{{ route('user.withdraw.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Create Withdraw
        </a>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Withdraw List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Method</th>
                            <th>Account Number</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdraws as $key => $withdraw)
                            <tr>
                                <td>{{ $withdraws->firstItem() + $key }}</td>
                                <td>{{ ucfirst($withdraw->method) }}</td>
                                <td>{{ $withdraw->account_number }}</td>
                                <td>{{ number_format($withdraw->amount, 2) }}</td>
                                <td>{{ $withdraw->created_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No Withdraw Requests Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $withdraws->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
