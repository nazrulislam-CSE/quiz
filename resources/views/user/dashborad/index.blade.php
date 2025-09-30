@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/dashboard.css') }}">
    <!-- Welcome Card -->
    <div class="card text-white bg-success mb-4">
        <div class="card-body">
            <h4 class="card-title">
                চকবোর্ড এক্সাম সেন্টার এ স্বাগতম <br>{{ ucfirst(Auth::user()->username ?? '') }}!
            </h4>
            {{-- <p class="card-text">Here's what's happening with your platform today.</p> --}}
        </div>
    </div>

    <!-- Refer Link Card -->
    @php
        // Generate full refer link
        $referLink = route('register', ['refer' => Auth::user()->username]);
    @endphp

    <div class="card border-success shadow-sm mb-4">
        <div class="card-header bg-success text-white fw-bold">
            আপনার রেফার লিংক
        </div>
        <div class="card-body">
            <p class="mb-2">Share this link and earn rewards!</p>
            <div class="input-group">
                <input type="text" id="referLinkInput" class="form-control"
                    value="{{ url('register') }}?refer_id={{ Auth::user()->username }}" readonly>
                <button class="btn btn-success" type="button" onclick="copyReferLink()">
                    <i class="fas fa-copy me-1"></i> কপি
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
                        <h5 class="card-title mb-0">মোট ব্যালেন্স: <span
                                class="text-success fw-bold">৳{{ number_format($mainWallet, 2) }}</span></h5>
                        <a href="{{ route('user.balance.request') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> রিকোয়েস্ট করুন
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row mb-4 g-3">

        <!-- Total Income -->
        {{-- <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width:60px; height:60px;">
                            <i class="fas fa-hand-holding-usd fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Total Income</h6>
                        <p class="mb-0 text-muted">Total: <span class="fw-bold text-dark">৳
                                {{ number_format($totalIncome ?? 0, 2) }}</span></p>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- ব্যালেন্স ট্রান্সফার রিপোর্ট -->
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.balance.request.report') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:60px; height:60px;">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">ব্যালেন্স ট্রান্সফার রিপোর্ট</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- এমসিকিউ এক্সাম -->
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.mcq.exam') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:60px; height:60px;">
                                <i class="fas fa-question-circle fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">এমসিকিউ এক্সাম</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- অনলাইন কুইজ -->
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('online.quiz') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:60px; height:60px;">
                                <i class="fas fa-question-circle fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">অনলাইন কুইজ</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- এক্সাম লিস্ট -->
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.exam.reports') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:60px; height:60px;">
                                <i class="fas fa-list fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">এক্সাম লিস্ট</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- রেফার লিস্ট -->
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.refer.list') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:60px; height:60px;">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">রেফার লিস্ট</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- জেনারেশন ইনকাম -->
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.generation.list') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:60px; height:60px;">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">জেনারেশন ইনকাম</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- উইথড্র লিস্ট -->
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.withdraw.list') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:60px; height:60px;">
                                <i class="fas fa-hand-holding-usd fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">উইথড্র লিস্ট</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- ব্যালেন্স ট্রান্সফার লিস্ট -->
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('user.balance.transfer.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 rounded-3 h-100" style="background:#ffbfbfdd !important;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:60px; height:60px;">
                                <i class="fas fa-hand-holding-usd fa-2x"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">ব্যালেন্স ট্রান্সফার লিস্ট</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>

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
