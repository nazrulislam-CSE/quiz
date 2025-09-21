@extends('layouts.frontend.app', [$pageTitle => $pageTitle])
@section('content')
    <div class="container">
        <!-- Exam Header -->
        <div class="row mb-4 g-3">
            <div class="col-lg-12">
                <h3 class="text-center text-success fw-bold mt-5 mb-4">পরীক্ষার ফলাফল</h3>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12 text-center">
                <div class="card shadow-sm border-success p-3">
                    <h5>স্কোর: {{ $correct }} এর মধ্যে {{ $totalQuestions }}</h5>
                    <p>সঠিক: {{ $correct }} | ভুল: {{ $wrong }} | উত্তর দেয়া হয়নি: {{ $notAnswered }}</p>
                </div>
            </div>
        </div>

        @foreach ($results as $res)
            <div class="row mb-2">
                <div class="col-lg-12">
                    <div
                        class="card shadow-sm 
                @if ($res['status'] == 'correct') border-success 
                @elseif($res['status'] == 'wrong') border-danger 
                @else border-secondary @endif
                ">
                        <div class="card-body">
                            <h6>{{ $res['question'] }}</h6>
                            <span
                                class="badge 
                        @if ($res['status'] == 'correct') bg-success 
                        @elseif($res['status'] == 'wrong') bg-danger 
                        @else bg-secondary @endif
                        ">
                                @if ($res['status'] == 'correct')
                                    সঠিক
                                @elseif($res['status'] == 'wrong')
                                    ভুল
                                @else
                                    উত্তর দেয়া হয়নি
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="row mt-4">
            <div class="col-lg-6 text-center mb-2">
                <a href="{{ route('online.quiz') }}" class="btn btn-primary">পরীক্ষায় ফিরে যান</a>
            </div>
            <div class="col-lg-6 text-center mb-2">
                <button class="btn btn-success" onclick="window.print()">Print Result</button>
            </div>
        </div>
    @endsection
