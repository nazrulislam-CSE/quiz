@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/exam.css') }}">
    <div class="row mb-4 g-3">
        <div class="col-lg-12">
            <div class="exam-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-chart-pie me-2"></i>Detailed Performance Analysis</span>
                        <span class="badge bg-light text-primary">Score: {{ $examResult->correct }}/{{ $examResult->total }} ({{ $examResult->score }}%)</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Performance Overview -->
                    <h5 class="step-title mb-4">Performance Overview</h5>
                    <div class="row mb-5">
                        <div class="col-md-3 mb-4">
                            <div class="stats-card">
                                <div class="stats-icon text-primary">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stats-number text-primary">{{ $examResult->correct }}</div>
                                <div class="stats-label">Correct Answers</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stats-card">
                                <div class="stats-icon text-danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="stats-number text-danger">{{ $examResult->wrong  }}</div>
                                <div class="stats-label">Incorrect Answers</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stats-card">
                                <div class="stats-icon text-info">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stats-number text-info">
                                    {{ $examResult->time_taken   }}
                                </div>
                                <div class="stats-label">Time Taken</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stats-card">
                                <div class="stats-icon text-warning">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stats-number text-warning">{{ $examResult->score }}%</div>
                                <div class="stats-label">Overall Score</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="score-display">
                            <span id="percentage">{{ $examResult->score }}%</span>
                        </div>
                        
                        <div class="result-legend">
                            <div class="legend-item">
                                <div class="legend-color legend-correct"></div>
                                <span>Correct Answers</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color legend-incorrect"></div>
                                <span>Incorrect Answers</span>
                            </div>
                        </div>
                        
                        <h5 class="step-title mb-4">Question Review</h5>
                        
                        <div class="results-container">
                            @php
                                $incorrectTopics = [];
                                $questionNumber = 1;
                            @endphp
                            
                            @foreach($mcqs as $mcq)
                                @php
                                    $givenAnswerId = $answers[$mcq->id] ?? null;
                                    $correctAnswer = $mcq->answers->where('is_correct', 1)->first();
                                    $isCorrect = $givenAnswerId && $correctAnswer && $givenAnswerId == $correctAnswer->id;
                                    
                                    if (!$isCorrect && $mcq->topic) {
                                        $incorrectTopics[$mcq->topic->id] = $mcq->topic->name;
                                    }
                                @endphp
                                
                                <div class="result-card {{ $isCorrect ? 'result-correct' : 'result-incorrect' }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="question-number">{{ $questionNumber++ }}</div>
                                            <h6 class="m-0">{{ $mcq->question }}</h6>
                                        </div>
                                        <div class="options-result">
                                            @foreach($mcq->answers as $answer)
                                                @php
                                                    $isSelected = $givenAnswerId == $answer->id;
                                                    $isCorrectAnswer = $answer->is_correct;
                                                    $bgColor = '';
                                                    
                                                    if ($isSelected && $isCorrectAnswer) {
                                                        $bgColor = 'rgba(40, 167, 69, 0.1)';
                                                    } elseif ($isSelected && !$isCorrectAnswer) {
                                                        $bgColor = 'rgba(220, 53, 69, 0.1)';
                                                    } elseif (!$isSelected && $isCorrectAnswer) {
                                                        $bgColor = 'rgba(40, 167, 69, 0.1)';
                                                    }
                                                @endphp
                                                
                                                <div class="option-item {{ $isSelected ? 'selected' : '' }}" style="{{ $bgColor ? "background-color: $bgColor;" : '' }}">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" 
                                                            {{ $isSelected ? 'checked' : '' }} disabled>
                                                        <label class="form-check-label">
                                                            {{ $answer->answer }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            @if($isCorrect)
                                                <div class="alert alert-success mt-2">
                                                    <i class="fas fa-check-circle me-2"></i>Correct!
                                                    @if($correctAnswer->explanation)
                                                        {{ $correctAnswer->explanation }}
                                                    @endif
                                                </div>
                                            @else
                                                <div class="alert alert-danger mt-2">
                                                    <i class="fas fa-times-circle me-2"></i>Incorrect! 
                                                    @if($correctAnswer)
                                                        The correct answer is: {{ $correctAnswer->answer }}
                                                        @if($correctAnswer->explanation)
                                                            - {{ $correctAnswer->explanation }}
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recommendations -->
                    @if(count($incorrectTopics) > 0)
                        <h5 class="step-title mb-4 mt-5">Recommendations for Improvement</h5>
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>Areas to Focus On:</h6>
                            <ul class="mb-0">
                                @foreach($incorrectTopics as $topicId => $topicName)
                                    <li>{{ $topicName }} (needs improvement)</li>
                                @endforeach
                                <li>Review questions you answered incorrectly</li>
                                <li>Practice more questions on these topics</li>
                            </ul>
                        </div>
                    @elseif($score == 100)
                        <h5 class="step-title mb-4 mt-5">Outstanding Performance!</h5>
                        <div class="alert alert-success">
                            <h6><i class="fas fa-trophy me-2"></i>Perfect Score!</h6>
                            <p class="mb-0">You answered all questions correctly. Keep up the excellent work!</p>
                        </div>
                    @else
                        <h5 class="step-title mb-4 mt-5">Good Performance!</h5>
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>Keep Improving:</h6>
                            <ul class="mb-0">
                                <li>Review the questions you got wrong</li>
                                <li>Focus on consistent practice</li>
                                <li>Work on time management</li>
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <!-- Left Side -->
                        <a href="{{ route('user.user.home') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Exams
                        </a>

                        <!-- Right Side Buttons -->
                        <div class="d-flex ms-auto gap-2">
                            <a href="{{ route('user.exam.view', $examResult->id) }}" class="btn btn-success">
                                <i class="fas fa-eye me-2"></i>Exam View
                            </a>
                            <button class="btn btn-success" onclick="window.print()">
                                <i class="fas fa-download me-2"></i>Download Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection