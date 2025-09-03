@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
<link rel="stylesheet" href="{{ asset('dashboard/auth/css/print.css') }}">
<div class="container my-4">
    <div class="card shadow-lg border-success">
        <div class="card-header bg-success text-white fw-bold">
            <i class="fas fa-clipboard-check me-2"></i> {{ $pageTitle }}
        </div>
        <div class="card-body">
            <h5 class="mb-3 text-center">
                <i class="fas fa-users me-2"></i> {{ $examResult->user->full_name ?? '' }}
            </h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <tbody>
                        <tr>
                            <th class="bg-light w-25"><i class="fas fa-id-badge me-2 text-success"></i>Admission</th>
                            <td>{{ $examResult->admission->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="fas fa-building me-2 text-success"></i>Department</th>
                            <td>{{ $examResult->department->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="fas fa-book-open me-2 text-success"></i>Subject</th>
                            <td>{{ $examResult->subject->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="fas fa-tags me-2 text-success"></i>Topic</th>
                            <td>{{ $examResult->topic->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="fas fa-star me-2 text-warning"></i>Score</th>
                            <td><span class="fw-bold text-primary">{{ $examResult->score }}%</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="fas fa-check-circle me-2 text-success"></i>Correct / Wrong
                            </th>
                            <td>
                                <span class="text-success fw-bold">{{ $examResult->correct }}</span> |
                                <span class="text-danger fw-bold">{{ $examResult->wrong }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light"><i class="fas fa-clock me-2 text-success"></i>Time Taken</th>
                            <td>
                                @php
                                    $totalSeconds = round($examResult->time_taken * 60);
                                @endphp
                                @if ($totalSeconds < 60)
                                    {{ $totalSeconds }} সেকেন্ড
                                @else
                                    {{ floor($totalSeconds / 60) }} মিনিট {{ $totalSeconds % 60 }} সেকেন্ড
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Questions with Answers -->
    <!-- MCQ Topic Name -->
    <div class="card shadow-lg border-primary mb-4 mt-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-tags me-2"></i> {{ $examResult->topic->name ?? 'MCQ Topic' }}
        </div>
    </div>

    <!-- Questions with Answers -->
    <div class="row">
        @foreach ($mcqs as $index => $mcq)
            @php
                $userAnswerId = $givenAnswers[$mcq->id] ?? null;
                $correctAnswer = $mcq->answers->where('is_correct', 1)->first();
                $isCorrect = $userAnswerId == ($correctAnswer->id ?? null);
            @endphp

            <div class="col-md-6">
                <div class="card mb-4 shadow-sm {{ $isCorrect ? 'border-success' : 'border-danger' }}">
                    <div class="card-header {{ $isCorrect ? 'bg-success text-white' : 'bg-danger text-white' }}">
                        Q{{ $index + 1 }}. {{ $mcq->question }}
                    </div>
                    <div class="card-body">
                        @foreach ($mcq->answers as $answer)
                            <div
                                class="p-2 rounded mb-2
                        @if ($answer->id == $userAnswerId && $isCorrect) bg-success text-white
                        @elseif($answer->id == $userAnswerId && !$isCorrect) bg-danger text-white
                        @elseif($answer->id == $correctAnswer->id) border border-success
                        @else border @endif">
                                {{ $answer->answer }}
                                @if ($answer->id == $correctAnswer->id)
                                    <span class="badge bg-success ms-2">Correct</span>
                                @endif
                                @if ($answer->id == $userAnswerId && !$isCorrect)
                                    <span class="badge bg-danger ms-2">Your Answer</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('user.user.home') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Exams
        </a>
        <button class="btn btn-success" onclick="printReport()">
            <i class="fas fa-download me-2"></i>Download Report
        </button>
    </div>

    <!-- Printable Report Wrapper -->
    <div id="print-area" style="display: none;">
        <div class="report-header text-center mb-4">
            <h2 class="fw-bold text-success">Exam Report</h2>
            <p class="mb-0">Generated on: {{ now()->format('d M, Y h:i A') }}</p>
            <hr>
        </div>

        <!-- Student Info -->
        <div class="mb-4">
            <h5>Student Information</h5>
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">Name</th>
                    <td>{{ $examResult->user->full_name ?? '' }}</td>
                </tr>
                <tr>
                    <th>Admission</th>
                    <td>{{ $examResult->admission->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $examResult->department->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Score</th>
                    <td>{{ $examResult->score }}%</td>
                </tr>
            </table>
        </div>

        <!-- Questions -->
        <h5 class="mb-3">Question Details</h5>
        @foreach ($mcqs->groupBy('topic.name') as $topicName => $topicQuestions)
        <div class="mb-4">
            <!-- Topic Title -->
            <h6 class="fw-bold text-primary border-bottom pb-1 mb-3">
                📘 Topic: {{ $topicName ?? 'General' }}
            </h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width:60%">Question</th>
                        <th style="width:40%">Answers</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mcqs as $index => $mcq)
                    <tr>
                        <td><strong>Q{{ $index + 1 }}:</strong> {{ $mcq->question }}</td>
                        <td>
                            <ul style="padding-left:18px;">
                                @foreach ($mcq->answers as $answer)
                                    <li @if ($answer->is_correct) style="color:green;font-weight:bold;" @endif>
                                        {{ $answer->answer }}
                                        @if ($answer->is_correct) ✅ @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
</div>
<!-- Print Script -->
<script>
    function printReport() {
        document.getElementById("print-area").style.display = "block"; 
        window.print();
        document.getElementById("print-area").style.display = "none"; 
    }
</script>
@endsection
