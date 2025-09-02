@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
<div class="container my-4">
    <div class="card shadow-lg border-success">
        <div class="card-header bg-success text-white fw-bold">
            <i class="fas fa-clipboard-check me-2"></i> {{ $pageTitle }}
        </div>
        <div class="card-body">
            <h5 class="mb-3">👤 {{ $examResult->user->name }}</h5>
            <p><strong>Admission:</strong> {{ $examResult->admission->name ?? '-' }}</p>
            <p><strong>Department:</strong> {{ $examResult->department->name ?? '-' }}</p>
            <p><strong>Subject:</strong> {{ $examResult->subject->name ?? '-' }}</p>
            <p><strong>Topic:</strong> {{ $examResult->topic->name ?? '-' }}</p>
            <p><strong>Score:</strong> {{ $examResult->score }}%</p>
            <p><strong>Correct:</strong> {{ $examResult->correct }} | <strong>Wrong:</strong> {{ $examResult->wrong }}</p>
            <p><strong>Time Taken:</strong> {{ $examResult->time_taken ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Questions with Answers -->
    <div class="mt-4">
        @foreach($mcqs as $index => $mcq)
            @php
                $userAnswerId = $givenAnswers[$mcq->id] ?? null;
                $correctAnswer = $mcq->answers->where('is_correct',1)->first();
                $isCorrect = $userAnswerId == ($correctAnswer->id ?? null);
            @endphp

            <div class="card mb-3 shadow-sm {{ $isCorrect ? 'border-success' : 'border-danger' }}">
                <div class="card-header {{ $isCorrect ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    Q{{ $index+1 }}. {{ $mcq->question }}
                </div>
                <div class="card-body">
                    @foreach($mcq->answers as $answer)
                        <div class="p-2 rounded mb-2 
                            @if($answer->id == $userAnswerId && $isCorrect) bg-success text-white
                            @elseif($answer->id == $userAnswerId && !$isCorrect) bg-danger text-white
                            @elseif($answer->id == $correctAnswer->id) border border-success
                            @else border @endif">
                            {{ $answer->answer }}
                            @if($answer->id == $correctAnswer->id)
                                <span class="badge bg-success ms-2">Correct</span>
                            @endif
                            @if($answer->id == $userAnswerId && !$isCorrect)
                                <span class="badge bg-danger ms-2">Your Answer</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('user.user.home') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Exams
        </a>
        <button class="btn btn-success" onclick="window.print()">
            <i class="fas fa-download me-2"></i>Download Report
        </button>
    </div>
</div>
@endsection
