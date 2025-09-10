@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container">
    <h3 class="mb-4 text-center fw-bold">{{ $pageTitle }}</h3>

    <!-- Balance Info -->
    <div class="text-center mb-3">
        <h5 class="fw-semibold">
            আপনার বর্তমান ক্যাশ আউটযোগ্য ব্যালান্স রয়েছে - 
            <span class="text-success">{{ number_format(auth()->user()->main_wallet, 2) }} টাকা</span>
        </h5>
    </div>

    <!-- Marquee Notice -->
    <div class="bg-success text-light rounded py-2 mb-4">
        <marquee behavior="scroll" direction="left" scrollamount="5">
            সর্বনিম্ন ক্যাশআউট এর পরিমাণ - ২০০ টাকা
        </marquee>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-info text-white text-center fw-bold rounded-top-4">
            আপনার সর্বমোট ক্যাশ আউট পরিমাণ - 
            {{ number_format(auth()->user()->withdraws()->sum('amount'), 2) }} টাকা
        </div>

        <div class="card-body p-4">
            <form action="{{ route('user.withdraw.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="fw-semibold">ক্যাশ আউটের পদ্ধতি</label>
                    <select name="method" class="form-select" required>
                        <option value="bkash">Bkash</option>
                        <option value="nagad">Nagad</option>
                        <option value="bank">Bank</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Account Number</label>
                    <input type="text" name="account_number" class="form-control" placeholder="Enter Account Number" required>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">ক্যাশ আউট পরিমাণ</label>
                    <input type="number" name="amount" min="200" class="form-control" placeholder="Enter Amount" required>
                </div>

                <button type="submit" class="btn btn-info text-white w-100 fw-bold py-2 rounded-pill">
                    সাবমিট করুন
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
