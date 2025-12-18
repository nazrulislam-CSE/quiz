@extends('layouts.admin.app', [$pageTitle => 'Page Title'])

@section('content')
    <!-- Content Header (Page header) -->
    <div class="breadcrumb-header justify-content-between">
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                    <li class="breadcrumb-item"><a href="#">Topic MCQs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Page Title' }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-auto">
            {{-- Additional buttons if needed --}}
        </div>
    </div>

    <!-- Main content -->
    <div class="card card-primary card-outline shadow-lg mb-4">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title' }}</p>
            <span class="badge bg-info align-self-center ms-2" style="font-size: 16px;">
                Total: {{ $questions->count() }} Questions
            </span>
            <div class="d-flex">
                <a href="{{ route('admin.paper.mcq.index') }}" class="btn btn-danger me-2">
                    <i class="fas fa-list d-inline"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Topic Information -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Admission</span>
                            <span class="info-box-number">{{ $topicInfo->admission->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Department</span>
                            <span class="info-box-number">{{ $topicInfo->department->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Group</span>
                            <span class="info-box-number">{{ $topicInfo->group->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Subject</span>
                            <span class="info-box-number">{{ $topicInfo->subject->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-3">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">Paper Final</span>
                            <span class="info-box-number">{{ $topicInfo->paperFinal->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">{{ $pageTitle }}</h5>
                </div>

                <div class="card-body table-responsive">
                    <table id="file-datatable" class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="5%">SL</th>
                                <th width="40%">Question</th>
                                <th width="35%">Options</th>
                                <th width="10%">Correct</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($questions as $key => $question)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $key + 1 }}
                                    </td>

                                    <td>
                                        {!! $question->question !!}
                                    </td>

                                    <td>
                                        @forelse ($question->answers as $answer)
                                            <div class="mb-1">
                                                <span
                                                    class="badge {{ $answer->is_correct ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ chr(64 + $loop->iteration) }}
                                                </span>
                                                {{ $answer->answer }}

                                                @if ($answer->is_correct)
                                                    <i class="fas fa-check-circle text-success ms-1"></i>
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-muted">No options</span>
                                        @endforelse
                                    </td>

                                    <td class="text-center">
                                        @php
                                            $correctAnswer = $question->answers->firstWhere('is_correct', 1);
                                        @endphp

                                        @if ($correctAnswer)
                                            <span class="badge bg-success fs-6">
                                                {{ chr(64 + $question->answers->values()->search($correctAnswer) + 1) }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">N/A</span>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        No questions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination if needed -->
        </div>
    </div>

    <style>
        .options-list .option-item {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .options-list .option-item:last-child {
            border-bottom: none;
        }

        .info-box {
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .info-box-text {
            font-size: 14px;
        }

        .info-box-number {
            font-size: 18px;
            font-weight: 600;
        }
    </style>
@endsection
