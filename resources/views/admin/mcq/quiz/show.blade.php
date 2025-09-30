@extends('layouts.admin.app', [$pageTitle => 'Page Title'])

@section('content')
<!-- Content Header -->
<div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.online.quiz.report') }}">Online Quiz Report</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Quiz Details' }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>
<!-- পরীক্ষার ফলাফল -->
<div class="row mb-3">
    <div class="col-lg-12 text-center">
        <div class="card shadow-sm border-success p-3">
            <h5 class="text-success fw-bold">পরীক্ষার ফলাফল</h5>
            <h6>স্কোর: {{ $correct }} এর মধ্যে {{ $totalQuestions }}</h6>
            <p>সঠিক: {{ $correct }} | ভুল: {{ $wrong }} | উত্তর দেননি: {{ $notAnswered }}</p>
        </div>
    </div>
</div>
<!-- Quiz Details Table -->
<div class="card card-primary card-outline shadow-lg mb-4">
    <div class="card-header border-bottom">
        <h5 class="card-title my-0">{{ $pageTitle ?? 'Quiz Details' }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>User Name</th>
                        <td>{{ $mcq->user->full_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>User Email</th>
                        <td>{{ $mcq->user->email ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Topics</th>
                        <td>{{ $mcq->question->mcq->title ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Submitted At</th>
                        <td>{{ $mcq->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $mcq->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
                        <!-- Question & Answers -->
<div class="card card-secondary card-outline shadow-sm mb-4">
    <div class="card-header">
        <h5 class="my-0">প্রশ্ন এবং উত্তর</h5>
    </div>
    <div class="card-body">
        @if ($mcq->question)
            <h6 class="fw-bold">প্রশ্ন:</h6>
            <p>{{ $mcq->question->question }}</p>

            <h6 class="fw-bold mt-3">সব অপশন:</h6>
            @foreach ($mcq->question->answers as $option)
                @php
                    $isCorrect = $option->is_correct;
                    $isSelected = $option->id == $mcq->answer_id;
                    $bgClass = '';

                    if ($isSelected && $isCorrect) {
                        $bgClass = 'bg-success text-white'; // সঠিক উত্তর, ইউজার সিলেক্ট করেছে
                    } elseif ($isSelected && !$isCorrect) {
                        $bgClass = 'bg-danger text-white'; // ভুল উত্তর
                    } elseif ($isCorrect) {
                        $bgClass = 'bg-success text-white'; // সঠিক উত্তর, ইউজার সিলেক্ট করেনি
                    } else {
                        $bgClass = 'bg-light'; // সাধারণ অপশন
                    }
                @endphp

                <p class="p-2 rounded {{ $bgClass }}">
                    {{ $option->answer }}
                    @if ($isSelected)
                        <span class="badge bg-dark">আপনার উত্তর</span>
                    @endif
                    @if ($isCorrect)
                        <span class="badge bg-success">সঠিক উত্তর</span>
                    @endif
                </p>
            @endforeach
        @else
            <p class="text-danger">প্রশ্ন খুঁজে পাওয়া যায়নি।</p>
        @endif
    </div>
</div>
</div>
@endsection
