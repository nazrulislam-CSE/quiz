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
                            <th>Sl</th>
                            <th>From User</th>
                            <th>To User</th>
                            <th>Date</th>
                            <th>Income</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myGenerations  as $key => $gen)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ ucfirst($gen->fromUser->username ?? 'N/A') }}</td>
                                <td>{{ ucfirst($gen->toUser->username ?? 'N/A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($gen->date)->format('d M Y h:i:s A') }}</td>
                                <td>{{ number_format($gen->commission, 2) }} টাকা</td>
                                <td>{{ number_format($gen->total_amount, 2) }} টাকা</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No uplines found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
