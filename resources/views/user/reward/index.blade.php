@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            🎁 {{ $pageTitle }}
        </h3>

        <p class="text-muted mb-0">
            আপনি যে র‍্যাংকগুলো অর্জন করেছেন, সেগুলোর রিওয়ার্ড এখানে দেখতে পারবেন।
        </p>

    </div>


    {{-- ================================================= --}}
    {{-- NO REWARD --}}
    {{-- ================================================= --}}

    @if($rewards->isEmpty())

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body text-center py-5">

                <div class="mb-3">

                    <div class="rounded-circle bg-light
                        d-inline-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;">

                        <i class="fas fa-gift fa-2x text-muted"></i>

                    </div>

                </div>

                <h5 class="fw-bold">
                    এখনো কোনো Reward অর্জন করেননি
                </h5>

                <p class="text-muted mb-0">
                    Rank অর্জন করলে আপনার Reward এখানে দেখা যাবে।
                </p>

            </div>

        </div>

    @else


        {{-- ================================================= --}}
        {{-- REWARD LIST --}}
        {{-- ================================================= --}}

        <div class="row g-4">

            @foreach($rewards as $reward)

                @php

                    $rankIcons = [

                        'LEARNER' => 'fa-graduation-cap',

                        'DREAMER' => 'fa-rocket',

                        'EDTECH ENTREPRENEUR' => 'fa-briefcase',

                        'PLAN MASTER' => 'fa-chart-line',

                        'MERIT STAR' => 'fa-star',

                        'CAMPUS CHAMPION' => 'fa-medal',

                        'LEADER' => 'fa-crown',

                        'EDTECH BRAND BUILDER' => 'fa-building',

                        'TOP EDTECH CONSULT' => 'fa-user-tie',

                        'NATIONAL RANK ACHIEVER' => 'fa-flag',

                    ];

                    $rankColors = [

                        'LEARNER' => 'secondary',

                        'DREAMER' => 'info',

                        'EDTECH ENTREPRENEUR' => 'primary',

                        'PLAN MASTER' => 'warning',

                        'MERIT STAR' => 'danger',

                        'CAMPUS CHAMPION' => 'success',

                        'LEADER' => 'dark',

                        'EDTECH BRAND BUILDER' => 'primary',

                        'TOP EDTECH CONSULT' => 'secondary',

                        'NATIONAL RANK ACHIEVER' => 'warning',

                    ];

                    $icon = $rankIcons[$reward->rank_name]
                        ?? 'fa-trophy';

                    $color = $rankColors[$reward->rank_name]
                        ?? 'primary';

                @endphp


                <div class="col-lg-6 col-xl-4">

                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        <div class="card-body p-4">


                            {{-- ============================= --}}
                            {{-- Rank Header --}}
                            {{-- ============================= --}}

                            <div class="d-flex align-items-center mb-3">


                                <div class="rounded-circle bg-{{ $color }} text-white
                                    d-flex align-items-center justify-content-center me-3"
                                    style="width:60px;height:60px;flex-shrink:0;">

                                    <i class="fas {{ $icon }} fa-lg"></i>

                                </div>


                                <div class="flex-grow-1">

                                    <small class="text-muted">
                                        ACHIEVED RANK
                                    </small>

                                    <h5 class="fw-bold mb-0">
                                        🏆 {{ $reward->rank_name }}
                                    </h5>

                                </div>


                                <span class="badge rounded-pill bg-success px-3 py-2">

                                    <i class="fas fa-check-circle me-1"></i>

                                    ACTIVE

                                </span>

                            </div>


                            <hr>


                            {{-- ============================= --}}
                            {{-- Cash Reward --}}
                            {{-- ============================= --}}

                            @if($reward->rank_reward > 0)

                                <div class="mb-3">

                                    <small class="text-muted d-block mb-1">

                                        💰 Cash Reward

                                    </small>

                                    <h4 class="fw-bold text-success mb-0">

                                        ৳{{ number_format($reward->rank_reward, 0) }}

                                    </h4>

                                </div>

                            @endif


                            {{-- ============================= --}}
                            {{-- Reward Text --}}
                            {{-- ============================= --}}

                            @if($reward->reward_text)

                                <div class="p-3 rounded-3 bg-light">

                                    <small class="text-muted d-block mb-1">

                                        🎁 Reward Option

                                    </small>

                                    <strong class="text-dark">

                                        {{ $reward->reward_text }}

                                    </strong>

                                </div>

                            @endif


                            {{-- ============================= --}}
                            {{-- Achieved Date --}}
                            {{-- ============================= --}}

                            <div class="mt-3">

                                <small class="text-muted">

                                    <i class="far fa-calendar-alt me-1"></i>

                                    Achieved:
                                    {{ $reward->created_at->format('d M Y') }}

                                </small>

                            </div>


                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>

@endsection