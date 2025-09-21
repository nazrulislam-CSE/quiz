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
                        <th>Quiz Title</th>
                        <td>{{ $mcq->quiz->title ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Score</th>
                        <td>{{ $mcq->score ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($mcq->score >= ($mcq->quiz->pass_mark ?? 0))
                                <span class="badge bg-success">Pass</span>
                            @else
                                <span class="badge bg-danger">Fail</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Submitted At</th>
                        <td>{{ $mcq->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $mcq->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @if($mcq->answers)
                        <tr>
                            <th>Answers</th>
                            <td>
                                <ul>
                                    @foreach($mcq->answers as $key => $answer)
                                        <li><strong>Q{{ $key + 1 }}:</strong> {{ $answer['question'] ?? '' }} <br>
                                            <strong>Answer:</strong> {{ $answer['answer'] ?? '' }} <br>
                                            <strong>Correct:</strong> {{ $answer['is_correct'] ? 'Yes' : 'No' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
