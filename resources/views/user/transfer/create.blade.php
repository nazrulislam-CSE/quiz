@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="card shadow-lg border-0">
        <div class="card-body">
            <p>
                <strong>Income Wallet:</strong> {{ number_format($user->income_wallet, 2) }} টাকা <br>
                <strong>Main Wallet:</strong> {{ number_format($user->main_wallet, 2) }} টাকা
            </p>

            <form action="{{ route('user.balance.transfer.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Amount to Transfer</label>
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Transfer Now</button>
            </form>
        </div>
    </div>
</div>
@endsection
