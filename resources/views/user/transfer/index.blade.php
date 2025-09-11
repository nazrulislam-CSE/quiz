@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>{{ $pageTitle }}</h3>
        <a href="{{ route('user.balance.transfer.create') }}" class="btn btn-success">New Transfer</a>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-success">
                    <tr>
                        <th>Sl</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transfers as $key => $transfer)
                        <tr>
                            <td>{{ $transfers->firstItem() + $key }}</td>
                            <td>{{ number_format($transfer->amount, 2) }}</td>
                            <td><span class="badge bg-success">{{ ucfirst($transfer->status) }}</span></td>
                            <td>{{ $transfer->created_at->format('d M, Y h:i:s A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No transfers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $transfers->links() }}
        </div>
    </div>
</div>
@endsection
