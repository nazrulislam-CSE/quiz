@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/dashboard.css') }}">
    <!-- Welcome Card -->
    <div class="card text-white bg-primary mb-4">
        <div class="card-body">
            <h4 class="card-title">
                Welcome back, {{ ucfirst(Auth::user()->username ?? '') }}!
            </h4>
            <p class="card-text">Here's what's happening with your platform today.</p>
        </div>
    </div>


    <!-- Dashboard Buttons -->
    <div class="row mb-4 g-3">
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-primary w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                <h5 class="mb-0">Balance Request</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-info w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <h5 class="mb-0">Report</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-warning w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-list fa-2x mb-2"></i>
                <h5 class="mb-0">Porikhar List</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-success w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                <h5 class="mb-0">Withdraw</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-secondary w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5 class="mb-0">Generation Income</h5>
            </a>
        </div>
    </div>


    <!-- Total Balance & Quick Stats -->
    <div class="row mb-4 g-3">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Balance</h5>
                    <h2 class="text-primary">$12,567.89</h2>

                    <!-- Bootstrap Button Group -->
                    <div class="btn-group mt-3 w-100" role="group" aria-label="Balance Actions">
                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-plus me-2"></i>Add
                            Funds</button>
                        <button type="button" class="btn btn-sm btn-success"><i
                                class="fas fa-download me-2"></i>Withdraw</button>
                        <button type="button" class="btn btn-sm btn-info"><i
                                class="fas fa-exchange-alt me-2"></i>Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MCQ Content -->
    <div class="section active" id="selection-section">
        <div class="row mb-4 g-3">
            <div class="col-lg-12">
                <div class="exam-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-book-open me-2"></i>MCQ Exam Preparation</span>
                            <span class="badge bg-light text-primary" id="step-badge">Step 1 of 5</span>
                        </div>
                    </div>

                    @php
                        $currentStep = 1;

                        if ($selectedAdmission && !$selectedDepartment) {
                            $currentStep = 2;
                        } elseif ($selectedDepartment && !$selectedSubject) {
                            $currentStep = 3;
                        } elseif ($selectedSubject && !$selectedTopic) {
                            $currentStep = 4;
                        } elseif ($selectedTopic) {
                            $currentStep = 5;
                        }

                        // Map step to percentage
                        $progressPercent = match($currentStep) {
                            1 => 20,
                            2 => 40,
                            3 => 60,
                            4 => 80,
                            5 => 100,
                            default => 20
                        };
                    @endphp

                   <div class="progress-container mb-2">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                id="progress-bar" 
                                style="width: {{ $progressPercent }}%; padding: 0.25rem 0.5rem;">
                                {{ $progressPercent }}%
                            </div>
                        </div>
                    </div>


                    <div class="card-body p-4">
                        <!-- Step Indicator -->
                        <div class="step-indicator mb-3">
                            <div class="step {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'completed' : '') }}" id="step-1">1</div>
                            <div class="step {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'completed' : '') }}" id="step-2">2</div>
                            <div class="step {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'completed' : '') }}" id="step-3">3</div>
                            <div class="step {{ $currentStep == 4 ? 'active' : ($currentStep > 4 ? 'completed' : '') }}" id="step-4">4</div>
                            <div class="step {{ $currentStep == 5 ? 'active' : ($currentStep > 5 ? 'completed' : '') }}" id="step-4">5</div>
                        </div>

                        <form action="{{ route('user.user.home') }}" method="GET">
                            <!-- Step 1: Admission Selection -->
                            @if (!$selectedAdmission)
                                <div class="step-content active" id="step-admission">
                                    <h5 class="step-title">
                                        <i class="fas fa-university me-2"></i> Select Admission
                                    </h5>
                                    <div class="mb-4">
                                        @foreach ($admissions as $admission)
                                            <label for="admission{{ $admission->id }}"
                                                class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                style="cursor: pointer;"
                                                onclick="document.getElementById('admission{{ $admission->id }}').checked = true; this.closest('form').submit();">

                                                <img src="{{ !empty($admission->image) ? url('upload/admission/' . $admission->image) : url('upload/MCQ Logo.png') }}"
                                                    alt="ICON" class="me-2"
                                                    style="width:40px;height:40px;object-fit:cover;">

                                                <input type="radio" name="admission" id="admission{{ $admission->id }}"
                                                    value="{{ $admission->id }}" class="d-none"> {{-- hide radio --}}

                                                <span>{{ $admission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            <!-- Step 2: Department Selection -->
                            @if ($selectedAdmission && !$selectedDepartment)
                                <div class="step-content active" id="step-department">
                                    <h5 class="step-title"><i class="fas fa-building me-2"></i>Select Department</h5>
                                    <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                    <div class="mb-4">
                                        @foreach ($departments as $department)
                                            <label for="department{{ $department->id }}"
                                                class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                style="cursor: pointer;"
                                                onclick="document.getElementById('department{{ $department->id }}').checked = true; this.closest('form').submit();">
                                                <img src="{{ !empty($department->image) ? url('upload/department/' . $department->image) : url('upload/MCQ Logo.png') }}"
                                                    alt="ICON">
                                                <input type="radio" name="department"
                                                    id="department{{ $department->id }}" value="{{ $department->id }}"
                                                    onchange="this.form.submit()">
                                                <span>{{ $department->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Step 3: Subject Selection -->
                            @if ($selectedDepartment && !$selectedSubject)
                                <div class="step-content active" id="step-subject">
                                    <h5 class="step-title"><i class="fas fa-book me-2"></i>Select Subject</h5>
                                    <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                    <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                    <div class="mb-4">
                                        @foreach ($subjects as $subject)
                                            <label for="subject{{ $subject->id }}"
                                                class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                style="cursor: pointer;"
                                                onclick="document.getElementById('subject{{ $subject->id }}').checked = true; this.closest('form').submit();">
                                                <img src="{{ !empty($subject->image) ? url('upload/subject/' . $subject->image) : url('upload/MCQ Logo.png') }}"
                                                    alt="ICON">
                                                <input type="radio" name="subject" id="subject{{ $subject->id }}"
                                                    value="{{ $subject->id }}" onchange="this.form.submit()">
                                                <span>{{ $subject->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Step 4: Topic Selection -->
                            @if ($selectedSubject && !$selectedTopic)
                                <div class="step-content active" id="step-topic">
                                    <h5 class="step-title"><i class="fas fa-tag me-2"></i>Select Topic</h5>
                                    <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                    <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                    <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                                    <div class="mb-4">
                                        @foreach ($topics as $topic)
                                            <label for="topic{{ $topic->id }}"
                                                class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                style="cursor: pointer;"
                                                onclick="document.getElementById('topic{{ $topic->id }}').checked = true; this.closest('form').submit();">
                                                <img src="{{ !empty($topic->image) ? url('upload/topic/' . $topic->image) : url('upload/MCQ Logo.png') }}"
                                                    alt="ICON">
                                                <input type="radio" name="topic" id="topic{{ $topic->id }}"
                                                    value="{{ $topic->id }}" onchange="this.form.submit()">
                                                <label for="topic{{ $topic->id }}">{{ $topic->name }}</label>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </form>

                        <!-- ✅ Exam Notice & Subject Card (Only show after Topic is selected) -->
                        @if ($selectedTopic && $mcqs->isEmpty())
                            <div class="card border-success shadow-sm mb-4">
                                <div class="card-header bg-success text-white fw-bold">
                                    <i class="fa fa-info-circle me-2"></i> পরীক্ষার নোটিশ
                                </div>
                                <div class="card-body">

                                    <!-- পরীক্ষার বিষয়সমূহ -->
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fa fa-book text-success fs-4 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">পরীক্ষার বিষয়সমূহ</h6>
                                            <p class="mb-0 small">
                                                নিচের তালিকা থেকে আপনার গপছন্দ বিষয় নির্বাচন করুন এবং পরীক্ষা শুরু করুন।
                                            </p>
                                        </div>
                                    </div>

                                    <!-- পরীক্ষার সময় -->
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fa fa-clock text-success fs-4 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">পরীক্ষার সময়</h6>
                                            <p class="mb-0 small">
                                                প্রতিটি বিষয়ের জন্য আলাদা পরীক্ষার সময় নির্ধারিত আছে যা বিষয়ের পাশে দেখানো
                                                হয়েছে।
                                                নির্দিষ্ট সময়ের মধ্যে আপনাকে পরীক্ষা সম্পন্ন করতে হবে।
                                            </p>
                                        </div>
                                    </div>

                                    <!-- পরীক্ষার মার্ক -->
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fa fa-star text-success fs-4 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">পরীক্ষার মার্ক</h6>
                                            <p class="mb-0 small">
                                                প্রতিটি পরীক্ষার জন্য সর্বোচ্চ মার্ক নির্ধারিত আছে যা বিষয়ের পাশে দেখানো
                                                হয়েছে।
                                                পরীক্ষার ফলাফল এই মার্ক অনুযায়ী নির্ধারিত হবে।
                                            </p>
                                        </div>
                                    </div>

                                    <!-- পরীক্ষার ফি -->
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fa fa-money-bill text-success fs-4 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">পরীক্ষার ফি</h6>
                                            <p class="mb-0 small text-danger">
                                                এখনই বাটনে ক্লিক করলে পরীক্ষা শুরু হবে এবং আপনার ওয়ালেট থেকে স্বয়ংক্রিয়ভাবে
                                                পরীক্ষার ফি কেটে নেয়া হবে।
                                            </p>
                                            <p class="mb-0 small">
                                                প্রতিটি বিষয়ের জন্য আলাদা ফি নির্ধারিত আছে যা বিষয়ের পাশে দেখানো হয়েছে।
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Warning -->
                                    <div class="alert alert-warning mt-3 small mb-0">
                                        দয়া করে নিশ্চিত হয়ে নিন যে আপনার ওয়ালেটে পর্যাপ্ত ব্যালেন্স আছে।
                                        অন্যথায় পরীক্ষা শুরু করা সম্ভব হবে না।
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Card -->
                            <div class="card shadow-lg border-success mb-4">
                                <div class="card-body">
                                    <h6 class="fw-bold text-success mb-3 text-center">সিলেক্টেড টপিক → MCQ শুরু</h6>
                                    <div class="d-flex align-items-center border rounded p-3">
                                        <img src="{{ !empty($selectedTopic->image) ? url('upload/topic/' . $selectedTopic->image) : url('upload/mcq.png') }}"
                                            class="rounded-circle me-3" width="60" alt="topic icon">
                                        <div class="flex-grow-1">
                                            @php
                                                $selectedTopicData = $topics->where('id', $selectedTopic)->first();
                                            @endphp

                                            @if ($selectedTopicData)
                                                <h6 class="mb-1">{{ $selectedTopicData->name }}</h6>
                                                <small class="d-block">সময়: {{ $selectedTopicData->exam_duration }}
                                                    মিনিট</small>
                                                <small class="d-block">মার্ক: {{ $selectedTopicData->exam_mark }}</small>
                                                <small class="d-block">পরীক্ষার ফি:
                                                    {{ number_format($selectedTopicData->fee, 2) }} টাকা</small>
                                            @else
                                                <h6 class="mb-1">কোনো টপিক সিলেক্ট করা হয়নি</h6>
                                            @endif
                                        </div>
                                        <div>
                                            <button class="btn btn-danger btn-sm me-2">📖 স্টাডি</button>
                                            <button class="btn btn-success btn-sm">📝 এক্সাম</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($selectedTopic && $mcqs->isNotEmpty())
                            <div class="exam-card mt-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-book-open me-2"></i>{{ $topics->where('id',$selectedTopic)->first()->name }} - MCQ Exam</span>
                                    <span class="badge bg-light text-primary">Question <span id="current-question">1</span> of {{ $mcqs->count() }}</span>
                                </div>

                                <div class="progress-container mb-2">
                                    <div class="progress">
                                        <div class="progress-bar" id="question-progress" style="width: 0%;"></div>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                   @foreach($mcqs as $index => $mcq)
                                        <div class="question-container" data-question="{{ $index + 1 }}" @if($index != 0) style="display:none;" @endif>
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="question-number">{{ $index + 1 }}</div>
                                                <h5 class="m-0">{{ $mcq->question }}</h5>
                                            </div>

                                            <div class="options-container">
                                                @foreach($mcq->answers as $answer)
                                                    <div class="option-item p-2 rounded mb-2" data-correct="{{ $answer->is_correct ? '1' : '0' }}" style="cursor:pointer;">
                                                        <div class="d-flex align-items-center">
                                                            <input class="form-check-input me-2" type="radio" name="question_{{ $mcq->id }}" id="option{{ $answer->id }}">
                                                            <label class="form-check-label flex-grow-1 mb-0" for="option{{ $answer->id }}">
                                                                {{ $answer->answer }}
                                                            </label>
                                                            <span class="feedback ms-2" style="display:none;"></span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="navigation-buttons mt-4">
                                        <button class="btn btn-outline-secondary" id="prev-btn" disabled><i class="fas fa-arrow-left me-2"></i>Previous</button>
                                        <button class="btn btn-primary" id="next-btn">Next<i class="fas fa-arrow-right ms-2"></i></button>
                                        <button class="btn btn-success" id="submit-btn" style="display: none;">Submit Exam<i class="fas fa-check-circle ms-2"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Section -->
    <div class="section" id="exam-section">
        <div class="row mb-4 g-3">
            <div class="col-lg-12">
                <!-- Exam Timer -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="m-0">MCQ Examination</h3>
                    <div class="exam-timer" id="exam-timer">
                        <i class="fas fa-clock me-2"></i><span id="timer">30:00</span>
                    </div>
                </div>

                <div class="exam-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-book-open me-2"></i>Data Structures and Algorithms - Topic:
                                Arrays</span>
                            <span class="badge bg-light text-primary">Question <span id="current-question">1</span> of
                                20</span>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress">
                            <div class="progress-bar" id="question-progress" style="width: 5%"></div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Question Navigation -->
                        <div class="question-navigation" id="question-nav">
                            <!-- JS will generate question numbers here -->
                        </div>

                        <!-- Current Question -->
                        <div class="question-container">
                            <div class="d-flex align-items-center mb-4">
                                <div class="question-number" id="q-number">1</div>
                                <h5 class="m-0" id="question-text">Which of the following is a linear data structure?
                                </h5>
                            </div>

                            <div class="options-container">
                                <div class="option-item" data-option="1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="options" id="option1">
                                        <label class="form-check-label" for="option1">
                                            Tree
                                        </label>
                                    </div>
                                </div>
                                <div class="option-item" data-option="2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="options" id="option2">
                                        <label class="form-check-label" for="option2">
                                            Graph
                                        </label>
                                    </div>
                                </div>
                                <div class="option-item" data-option="3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="options" id="option3">
                                        <label class="form-check-label" for="option3">
                                            Array
                                        </label>
                                    </div>
                                </div>
                                <div class="option-item" data-option="4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="options" id="option4">
                                        <label class="form-check-label" for="option4">
                                            Network
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="navigation-buttons">
                            <button class="btn btn-outline-secondary" id="prev-btn" disabled>
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <button class="btn btn-primary" id="next-btn">
                                Next<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            <button class="btn btn-success" id="submit-btn" style="display: none;">
                                Submit Exam<i class="fas fa-check-circle ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="section" id="results-section">
        <div class="row mb-4 g-3">
            <div class="col-lg-12">
                <div class="exam-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-chart-bar me-2"></i>Exam Results</span>
                            <span class="badge bg-light text-primary">Score: <span id="score">15</span>/20</span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="score-display">
                            <span id="percentage">75%</span>
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
                            <!-- Result Item 1 -->
                            <div class="result-card result-correct">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="question-number">1</div>
                                        <h6 class="m-0">Which of the following is a linear data structure?</h6>
                                    </div>
                                    <div class="options-result">
                                        <div class="option-item selected">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" checked disabled>
                                                <label class="form-check-label">
                                                    Array
                                                </label>
                                            </div>
                                        </div>
                                        <div class="alert alert-success mt-2">
                                            <i class="fas fa-check-circle me-2"></i>Correct! Arrays are linear data
                                            structures.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Result Item 2 -->
                            <div class="result-card result-incorrect">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="question-number">2</div>
                                        <h6 class="m-0">What is the time complexity of accessing an element in an array?
                                        </h6>
                                    </div>
                                    <div class="options-result">
                                        <div class="option-item selected">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" checked disabled>
                                                <label class="form-check-label">
                                                    O(n)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="option-item" style="background-color: rgba(40, 167, 69, 0.1);">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" disabled>
                                                <label class="form-check-label">
                                                    O(1)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger mt-2">
                                            <i class="fas fa-times-circle me-2"></i>Incorrect! The correct answer is O(1) -
                                            constant time access.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- More result items would go here -->
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-outline-secondary" onclick="backToSelection()">
                                <i class="fas fa-redo me-2"></i>Start New Exam
                            </button>
                            <button class="btn btn-primary">
                                View Detailed Results<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentQuestion = 0;
        const questions = document.querySelectorAll('.question-container');
        const totalQuestions = questions.length;
        const progressBar = document.getElementById('question-progress');

        document.getElementById('next-btn').addEventListener('click', function() {
            if(currentQuestion < totalQuestions - 1) {
                questions[currentQuestion].style.display = 'none';
                currentQuestion++;
                questions[currentQuestion].style.display = 'block';
                updateProgress();
            }
            toggleButtons();
        });

        document.getElementById('prev-btn').addEventListener('click', function() {
            if(currentQuestion > 0) {
                questions[currentQuestion].style.display = 'none';
                currentQuestion--;
                questions[currentQuestion].style.display = 'block';
                updateProgress();
            }
            toggleButtons();
        });

        function updateProgress() {
            const percent = ((currentQuestion + 1) / totalQuestions) * 100;
            progressBar.style.width = percent + '%';
            document.getElementById('current-question').innerText = currentQuestion + 1;
        }

        function toggleButtons() {
            document.getElementById('prev-btn').disabled = currentQuestion === 0;
            document.getElementById('next-btn').style.display = (currentQuestion === totalQuestions - 1) ? 'none' : 'inline-block';
            document.getElementById('submit-btn').style.display = (currentQuestion === totalQuestions - 1) ? 'inline-block' : 'none';
        }
    </script>
   <script>
        document.querySelectorAll('.option-item').forEach(option => {
            option.addEventListener('click', function() {
                // If already answered, do nothing
                if(this.dataset.answered === "1") return;

                const isCorrect = this.dataset.correct === "1";
                const feedback = this.querySelector('.feedback');

                // Add background & text feedback
                if(isCorrect){
                    this.classList.add('bg-success', 'text-white');
                    feedback.innerHTML = '✔ Correct Answer';
                } else {
                    this.classList.add('bg-danger', 'text-white');
                    feedback.innerHTML = '✖ Wrong Answer';
                }
                feedback.style.display = 'inline';

                // Check the radio input
                this.querySelector('input[type="radio"]').checked = true;

                // Mark as answered
                this.dataset.answered = "1";

                // Disable all siblings for this question
                const siblings = this.closest('.options-container').querySelectorAll('.option-item');
                siblings.forEach(sib => {
                    if(sib !== this) sib.dataset.answered = "1";
                });
            });
        });
    </script>


@endsection
