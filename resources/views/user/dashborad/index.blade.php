@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/dashboard.css') }}">
    <!-- Welcome Card -->
    <div class="card text-white bg-primary mb-4">
        <div class="card-body">
            <h4 class="card-title">
                Welcome back, {{ ucfirst(Auth::user()->username ?? '') }}!
            </h4>
            <p class="card-text">Here's what's happening with your platform today.</p>
        </div>
    </div>

    <!-- Refer Link Card -->
    @php
        // Generate full refer link
        $referLink = route('register', ['refer' => Auth::user()->username]);
    @endphp

    <div class="card border-success shadow-sm mb-4">
        <div class="card-header bg-success text-white fw-bold">
            Your Refer Link
        </div>
        <div class="card-body">
            <p class="mb-2">Share this link and earn rewards!</p>
            <div class="input-group">
                <input type="text" id="referLinkInput" class="form-control"
                    value="{{ url('register') }}?refer_id={{ Auth::user()->username }}" readonly>
                <button class="btn btn-success" type="button" onclick="copyReferLink()">
                    <i class="fas fa-copy me-1"></i> Copy
                </button>
            </div>
        </div>
    </div>

    <!-- Total Balance & Quick Stats -->
    <div class="row mb-4 g-3">
        <div class="col-md-12">
            <div class="card h-100">
               <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">মোট ব্যালেন্স: <span class="text-success fw-bold">৳{{ number_format($mainWallet, 2) }}</span></h5>
                        <a href="{{ route('user.balance.request') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> রিকোয়েস্ট করুন
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Buttons -->
    <div class="row mb-4 g-3">
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.balance.request.report') }}" class="btn btn-primary w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                <h5 class="mb-0 fw-bold">ব্যালেন্স ট্রান্সফার রিপোর্ট</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.mcq.exam') }}"
                class="btn btn-danger w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-question-circle fa-2x mb-2"></i>
                <h5 class="mb-0 fw-bold">এমসিকিউ এক্সাম</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.exam.reports')}}" class="btn btn-warning w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-list fa-2x mb-2"></i>
                <h5 class="mb-0 fw-bold">এক্সাম লিস্ট</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.refer.list') }}"
                class="btn btn-warning w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5 class="mb-0 fw-bold">রেফার লিস্ট</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.generation.list') }}" class="btn btn-primary w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5 class="mb-0 fw-bold">জেনারেশন ইনকাম</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.withdraw.list') }}" 
            class="btn btn-success w-100 text-white d-flex flex-column align-items-center py-4 shadow-sm rounded-3">
                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                <h5 class="mb-0 fw-bold">উইথড্র লিস্ট</h5>
            </a>
        </div>

        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.balance.transfer.index') }}" class="btn btn-success w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                <h5 class="mb-0 fw-bold">ব্যালেন্স ট্রান্সফার লিস্ট</h5>
            </a>
        </div>

       {{-- <!-- 1st & 2nd Gen Income (Refer Bonus) -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="btn btn-info w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h5 class="mb-0">Refer Bonus:</h5>
                <span>{{ number_format($referBonus, 2) }}</span>
            </div>
        </div>

        <!-- Direct Referral Income -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="btn btn-success w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-user-plus fa-2x mb-2"></i>
                <h5 class="mb-0">Direct Income:</h5>
                <span>{{ number_format($directIncome, 2) }}</span>
            </div>
        </div> --}}

    </div>

    <script>
        function copyReferLink() {
            var copyText = document.getElementById("referLinkInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText.value).then(function() {
                toastr.success("Refer link copied to clipboard!");
            }).catch(function() {
                toastr.error("Failed to copy refer link.");
            });
        }

        // Optional: Toastr config
        toastr.options = {
            "positionClass": "toast-top-right",
            "timeOut": "2500",
        };
    </script>
@endsection
