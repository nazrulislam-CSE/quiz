@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span>ব্যালান্স রিকোয়েস্ট করুন</span>
            <a href="{{ route('user.balance.request.report') }}" class="btn btn-light btn-sm">রিপোর্ট দেখুন</a>
        </div>
        <div class="card-body">

            <div class="alert bg-light p-3 mb-4">
                <p class="fw-bold mb-2">টাকা পাঠাবেন যে নম্বর এ……….</p>

                <p class="text-danger fw-bold">
                    বিকাশ নম্বর 01316017328 (পার্সোনাল) 
                    <button type="button" class="btn btn-success btn-sm" onclick="copyText('01316017328')">কপি করুন</button>
                </p>

                <p class="text-danger fw-bold">
                    নগদ নম্বর 01521420274 (পার্সোনাল) 
                    <button type="button" class="btn btn-success btn-sm" onclick="copyText('01521420274')">কপি করুন</button>
                </p>

                <p class="text-warning fw-bold">
                    রকেট লিংক 
                    <button type="button" class="btn btn-success btn-sm" onclick="copyText('rocket-link')">কপি করুন</button>
                </p>
            </div>


            <form action="{{ route('user.balance.request.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">টাকা পাঠানোর মাধ্যম সিলেক্ট করুন</label>
                    <select name="method" class="form-control" required>
                        <option value="">সিলেক্ট করুন</option>
                        <option value="bkash">বিকাশ</option>
                        <option value="nagad">নগদ</option>
                        <option value="rocket">রকেট</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">যে একাউন্ট থেকে টাকা পাঠিয়েছেন</label>
                    <input type="text" name="from_account" class="form-control" placeholder="যে একাউন্ট থেকে টাকা পাঠিয়েছেন" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">টাকার পরিমান</label>
                    <input type="number" name="amount" class="form-control" placeholder="টাকার পরিমান" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ট্রান্সেকশন আইডি (ঐচ্ছিক)</label>
                    <input type="text" name="trx_id" class="form-control" placeholder="ট্রান্সেকশন আইডি (ঐচ্ছিক)">
                </div>

                <div class="mb-3">
                    <label class="form-label">স্ক্রিনশট দিন</label>
                    <input type="file" name="screenshot" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">রিকোয়েস্ট দিন</button>
            </form>
        </div>
    </div>
</div>
<script>
    function copyText(text) {
        navigator.clipboard.writeText(text).then(function () {
            toastr.success(text + ' কপি হয়েছে!', 'Success');
        }).catch(function (err) {
            toastr.error('কপি করতে সমস্যা হয়েছে');
        });
    }
</script>
@endsection
