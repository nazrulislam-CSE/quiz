@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            🏆 {{ $pageTitle }}
        </h3>

        <p class="text-muted mb-0">
            আপনার বর্তমান র‍্যাংক এবং পরবর্তী র‍্যাংকের লক্ষ্য দেখুন
        </p>
    </div>

    <!-- Current Rank -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 position-relative"
        style="background: linear-gradient(135deg, #fff8e1, #ffffff);">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <!-- Current Rank -->
                <div class="col-lg-4 mb-3 mb-lg-0">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle bg-warning text-white
                        d-flex align-items-center justify-content-center me-3" style="width:70px;height:70px;">

                            <i class="fas fa-trophy fa-2x"></i>

                        </div>

                        <div>

                            <small class="text-muted">
                                আপনার বর্তমান র‍্যাংক
                            </small>

                            <h4 class="fw-bold mb-0 text-dark">

                                @if($currentRank)

                                🎓 {{ $currentRank->rank_name }}

                                @else

                                🆓 NO RANK

                                @endif

                            </h4>

                        </div>

                    </div>

                </div>


                <!-- Own Deposit -->
                <div class="col-6 col-lg-2 mb-3 mb-lg-0">

                    <small class="text-muted">
                        নিজের Deposit
                    </small>

                    <h5 class="fw-bold text-success mb-0">
                        ৳{{ number_format($ownDeposit, 0) }}
                    </h5>

                </div>


                <!-- Direct -->
                <div class="col-6 col-lg-2 mb-3 mb-lg-0">

                    <small class="text-muted">
                        Direct Member
                    </small>

                    <h5 class="fw-bold text-primary mb-0">
                        {{ $directMemberCount }} জন
                    </h5>

                </div>


                <!-- Team -->
                <div class="col-6 col-lg-2">

                    <small class="text-muted">
                        Total Team
                    </small>

                    <h5 class="fw-bold text-info mb-0">
                        {{ $teamMemberCount }} জন
                    </h5>

                </div>


                <!-- Team Deposit -->
                <div class="col-6 col-lg-2">

                    <small class="text-muted">
                        Team Deposit
                    </small>

                    <h5 class="fw-bold text-danger mb-0">
                        ৳{{ number_format($teamDeposit, 0) }}
                    </h5>

                </div>

            </div>

        </div>


        <!-- Status -->

        <div class="position-absolute top-0 end-0 m-3">

            @if($currentRank && $currentRank->status == 1)

            <span class="badge bg-success rounded-pill px-3 py-2">
                <i class="fas fa-check-circle me-1"></i>
                Active
            </span>

            @else

            <span class="badge bg-secondary rounded-pill px-3 py-2">
                No Rank
            </span>

            @endif

        </div>

    </div>


    {{-- ============================= --}}
    {{-- NEXT RANK --}}
    {{-- ============================= --}}

    @if($nextRank)

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <small class="text-muted">
                        আপনার পরবর্তী লক্ষ্য
                    </small>

                    <div class="d-flex align-items-center gap-2">
                        <h4 class="fw-bold mb-0">
                            🎯 {{ $nextRank['name'] }}
                        </h4>

                        <a href="{{ route('user.balance.request') }}" class="btn btn-success btn-sm rounded-pill px-3">
                            <i class="fas fa-plus-circle me-1"></i> Deposit
                        </a>
                    </div>

                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">
                    NEXT RANK
                </span>

            </div>


            {{-- ================================= --}}
            {{-- LEARNER --}}
            {{-- ================================= --}}

            @if(isset($nextRank['requirement']['own_deposit']))

            @php

            $target = $nextRank['requirement']['own_deposit'];

            $progress = $target > 0
            ? min(100, ($ownDeposit / $target) * 100)
            : 100;

            $remainingDeposit = max(0, $target - $ownDeposit);

            @endphp


            <div>

                <div class="d-flex justify-content-between mb-1">

                    <span>
                        💰 নিজের Deposit
                    </span>

                    <strong>
                        ৳{{ number_format($ownDeposit, 0) }}
                        /
                        ৳{{ number_format($target, 0) }}
                    </strong>

                </div>


                <div class="progress mb-2" style="height:10px;">

                    <div class="progress-bar bg-success" style="width: {{ $progress }}%">
                    </div>

                </div>


                @if($remainingDeposit > 0)

                <div class="alert alert-light border mb-0 py-2">

                    🎯 <strong>{{ $nextRank['name'] }}</strong>
                    পেতে আরও
                    <strong>
                        ৳{{ number_format($remainingDeposit, 0) }}
                    </strong>
                    Deposit করতে হবে।

                </div>

                @else

                <div class="alert alert-success mb-0 py-2">

                    <i class="fas fa-check-circle me-1"></i>

                    <strong>{{ $nextRank['name'] }}</strong>
                    Referral-এর মাধ্যমে FREE MEMBER হওয়া যায়। আপনার Referral ID ব্যবহার করে অন্য কাউকে Refer করুন। আপনার
                    Referral-এর মাধ্যমে নতুন Member Join করলেই আপনার FREE MEMBER Status Active হয়ে যাবে।

                </div>

                @endif

            </div>


            {{-- ================================= --}}
            {{-- DREAMER --}}
            {{-- ================================= --}}

            @elseif($nextRank['name'] === 'DREAMER')

            @php

            $requiredCustomers = 50;

            $requiredTeamDeposit = 50000;

            $remainingCustomers = max(
            0,
            $requiredCustomers - $directPaidCustomer
            );

            $remainingTeamDeposit = max(
            0,
            $requiredTeamDeposit - $teamDeposit
            );

            $customerProgress = min(
            100,
            ($directPaidCustomer / $requiredCustomers) * 100
            );

            $teamProgress = min(
            100,
            ($teamDeposit / $requiredTeamDeposit) * 100
            );

            @endphp


            {{-- Direct Customer --}}

            <div class="mb-3">

                <div class="d-flex justify-content-between mb-1">

                    <span>
                        👥 Direct Paid Customer
                    </span>

                    <strong>
                        {{ $directPaidCustomer }} / {{ $requiredCustomers }}
                    </strong>

                </div>


                <div class="progress" style="height:10px;">

                    <div class="progress-bar bg-info" style="width: {{ $customerProgress }}%">
                    </div>

                </div>

            </div>


            {{-- Team Deposit --}}

            <div>

                <div class="d-flex justify-content-between mb-1">

                    <span>
                        💰 Team Deposit
                    </span>

                    <strong>
                        ৳{{ number_format($teamDeposit, 0) }}
                        /
                        ৳{{ number_format($requiredTeamDeposit, 0) }}
                    </strong>

                </div>


                <div class="progress" style="height:10px;">

                    <div class="progress-bar bg-warning" style="width: {{ $teamProgress }}%">
                    </div>

                </div>

            </div>


            {{-- Short Requirement --}}

            <div class="alert alert-light border mt-3 mb-0 py-2">

                🎯 <strong>DREAMER</strong> পেতে

                @if($remainingCustomers > 0)

                আরও
                <strong>{{ $remainingCustomers }} জন</strong>
                Direct Paid Customer

                @endif

                @if($remainingCustomers > 0 && $remainingTeamDeposit > 0)
                এবং
                @endif

                @if($remainingTeamDeposit > 0)

                আরও
                <strong>
                    ৳{{ number_format($remainingTeamDeposit, 0) }}
                </strong>
                Team Deposit

                @endif

                @if($remainingCustomers == 0 && $remainingTeamDeposit == 0)

                <strong>সব Requirement পূরণ হয়েছে!</strong>

                @endif

                প্রয়োজন।

            </div>


            {{-- ================================= --}}
            {{-- HIGHER RANK --}}
            {{-- ================================= --}}

            @elseif(isset($nextRank['requirement']['rank_name']))

            @php

            $requiredRank =
            $nextRank['requirement']['rank_name'];

            $currentCount =
            $rankCounts[$requiredRank] ?? 0;

            $requiredCount =
            $nextRank['requirement']['rank_count'];

            $teamTarget =
            $nextRank['requirement']['team_deposit'];

            $remainingPeople = max(
            0,
            $requiredCount - $currentCount
            );

            $remainingDeposit = max(
            0,
            $teamTarget - $teamDeposit
            );

            $rankProgress = min(
            100,
            ($currentCount / $requiredCount) * 100
            );

            $teamDepositProgress = min(
            100,
            ($teamDeposit / $teamTarget) * 100
            );

            @endphp


            {{-- Required Rank --}}

            <div class="mb-3">

                <div class="d-flex justify-content-between mb-1">

                    <span>
                        👥 Team {{ $requiredRank }}
                    </span>

                    <strong>
                        {{ $currentCount }}
                        /
                        {{ $requiredCount }}
                    </strong>

                </div>


                <div class="progress" style="height:10px;">

                    <div class="progress-bar bg-primary" style="width: {{ $rankProgress }}%">
                    </div>

                </div>

            </div>


            {{-- Team Deposit --}}

            <div>

                <div class="d-flex justify-content-between mb-1">

                    <span>
                        💰 Team Deposit
                    </span>

                    <strong>
                        ৳{{ number_format($teamDeposit, 0) }}
                        /
                        ৳{{ number_format($teamTarget, 0) }}
                    </strong>

                </div>


                <div class="progress" style="height:10px;">

                    <div class="progress-bar bg-warning" style="width: {{ $teamDepositProgress }}%">
                    </div>

                </div>

            </div>


            {{-- Short Requirement --}}

            <div class="alert alert-light border mt-3 mb-0 py-2">

                🎯 <strong>{{ $nextRank['name'] }}</strong> পেতে

                @if($remainingPeople > 0)

                আরও
                <strong>{{ $remainingPeople }} জন {{ $requiredRank }}</strong>

                @endif


                @if($remainingPeople > 0 && $remainingDeposit > 0)
                এবং
                @endif


                @if($remainingDeposit > 0)

                আরও
                <strong>
                    ৳{{ number_format($remainingDeposit, 0) }}
                </strong>
                Team Deposit

                @endif


                @if($remainingPeople == 0 && $remainingDeposit == 0)

                <strong>সব Requirement পূরণ হয়েছে!</strong>

                @endif

                প্রয়োজন।

            </div>

            @endif

        </div>

    </div>

    @endif

    <div class="row g-3 mb-4">

        <!-- Direct -->
        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Direct Member
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $directMemberCount }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-primary text-white
                        d-flex align-items-center justify-content-center" style="width:50px;height:50px;">

                            <i class="fas fa-user-plus"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Team -->
        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Team
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $teamMemberCount }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-info text-white
                        d-flex align-items-center justify-content-center" style="width:50px;height:50px;">

                            <i class="fas fa-users"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Own Deposit -->
        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                My Deposit
                            </small>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($ownDeposit, 0) }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-success text-white
                        d-flex align-items-center justify-content-center" style="width:50px;height:50px;">

                            <i class="fas fa-wallet"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Team Deposit -->
        <div class="col-md-3">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Team Deposit
                            </small>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($teamDeposit, 0) }}
                            </h3>

                        </div>

                        <div class="rounded-circle bg-warning text-white
                        d-flex align-items-center justify-content-center" style="width:50px;height:50px;">

                            <i class="fas fa-money-bill-wave"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


</div>
@endsection