@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Generation Commissions</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>SL</th>
                        <th>From User</th>
                        <th>Level</th>
                        <th>Commission</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($generations as $key => $gen)
                        <tr>
                            <td>{{ $generations->firstItem() + $key }}</td>
                            <td>{{ optional($gen->fromUser)->username ?? 'N/A' }}</td>
                            <td>{{ $gen->level }}</td>
                            <td>{{ number_format($gen->commission, 2) }}</td>
                            <td>{{ number_format($gen->total_amount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($gen->date)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No generation commissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $generations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
