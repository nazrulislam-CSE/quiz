@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/dashboard.css') }}">
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
                        $progressPercent = match ($currentStep) {
                            1 => 20,
                            2 => 40,
                            3 => 60,
                            4 => 80,
                            5 => 100,
                            default => 20,
                        };
                    @endphp

                    <div class="progress-container mb-2">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar"
                                style="width: {{ $progressPercent }}%; padding: 0.25rem 0.5rem;">
                                {{ $progressPercent }}%
                            </div>
                        </div>
                    </div>


                    <div class="card-body p-4">
                        <!-- Step Indicator -->
                        <div class="step-indicator mb-3">
                            <div class="step {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'completed' : '') }}"
                                id="step-1">1</div>
                            <div class="step {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'completed' : '') }}"
                                id="step-2">2</div>
                            <div class="step {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'completed' : '') }}"
                                id="step-3">3</div>
                            <div class="step {{ $currentStep == 4 ? 'active' : ($currentStep > 4 ? 'completed' : '') }}"
                                id="step-4">4</div>
                            <div class="step {{ $currentStep == 5 ? 'active' : ($currentStep > 5 ? 'completed' : '') }}"
                                id="step-4">5</div>
                        </div>

                        <form action="{{ route('user.mcq.exam') }}" id="exam-form" method="GET">
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

                                                <img src="{{ !empty($admission->image) ? url('upload/admission/' . $admission->image) : url('upload/mcq.png') }}"
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
                                                <img src="{{ !empty($department->image) ? url('upload/department/' . $department->image) : url('upload/mcq.png') }}"
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
                                                <img src="{{ !empty($subject->image) ? url('upload/subject/' . $subject->image) : url('upload/mcq.png') }}"
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
                                                <img src="{{ !empty($topic->image) ? url('upload/topic/' . $topic->image) : url('upload/mcq.png') }}"
                                                    alt="ICON">
                                                <input type="radio" name="topic" id="topic{{ $topic->id }}"
                                                    value="{{ $topic->id }}" onchange="this.form.submit()">
                                                <label for="topic{{ $topic->id }}">{{ $topic->name }}</label>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
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
                                                    <a href="{{ route('user.mcq.exam', [
                                                        'admission' => $selectedAdmission,
                                                        'department' => $selectedDepartment,
                                                        'subject' => $selectedSubject,
                                                        'topic' => $selectedTopic,
                                                        'exam' => 1
                                                    ]) }}" class="btn btn-success btn-sm">📝 এক্সাম</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                        @if ($examStart && $mcqs->isNotEmpty())
                            <!-- ✅ Exam Header with Countdown -->
                            <div class="card shadow-sm border-success mb-4">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-clock me-2"></i> পরীক্ষার সময় চলছে</span>
                                    <span id="exam-timer" class="fw-bold">00:00</span>
                                </div>
                            </div>

                            <!-- ✅ MCQ Form -->
                            <form action="{{ route('user.exam.submit') }}" method="POST" id="mcq-form">
                                @csrf
                                <input type="hidden" name="time_taken" id="time_taken">
                                <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                                <input type="hidden" name="topic" value="{{ $selectedTopic }}">

                                @foreach ($mcqs as $index => $mcq)
                                    <div class="question-container" data-question="{{ $index + 1 }}" @if ($index != 0) style="display:none;" @endif>
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="question-number">{{ $index + 1 }}</div>
                                            <h5 class="m-0">{{ $mcq->question }}</h5>
                                        </div>
                                        <div class="options-container">
                                            @foreach ($mcq->answers as $answer)
                                                <div class="option-item p-2 rounded mb-2" style="cursor:pointer;"
                                                    data-correct="{{ $answer->is_correct ? '1' : '0' }}">
                                                    <div class="d-flex align-items-center">
                                                        <input class="form-check-input me-2" type="radio"
                                                            name="answers[{{ $mcq->id }}]"
                                                            id="option{{ $answer->id }}"
                                                            value="{{ $answer->id }}" required hidden>
                                                        <label class="form-check-label flex-grow-1 mb-0"
                                                            for="option{{ $answer->id }}">
                                                            {{ $answer->answer }}
                                                        </label>
                                                        <span class="feedback ms-2" style="display:none;"></span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Navigation Buttons -->
                                <div class="navigation-buttons mt-4">
                                    <button type="button" class="btn btn-outline-secondary" id="prev-btn" disabled>
                                        <i class="fas fa-arrow-left me-2"></i>Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" id="next-btn">
                                        Next<i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                                        Submit Exam<i class="fas fa-check-circle ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        @endif
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
            if (currentQuestion < totalQuestions - 1) {
                questions[currentQuestion].style.display = 'none';
                currentQuestion++;
                questions[currentQuestion].style.display = 'block';
                updateProgress();
            }
            toggleButtons();
        });

        document.getElementById('prev-btn').addEventListener('click', function() {
            if (currentQuestion > 0) {
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
            document.getElementById('next-btn').style.display = (currentQuestion === totalQuestions - 1) ? 'none' :
                'inline-block';
            document.getElementById('submit-btn').style.display = (currentQuestion === totalQuestions - 1) ?
                'inline-block' : 'none';
        }
    </script>
    <script>
        document.querySelectorAll('.option-item').forEach(option => {
            option.addEventListener('click', function () {
                if (this.dataset.answered === "1") return;

                const isCorrect = this.dataset.correct === "1";
                const feedback = this.querySelector('.feedback');
                const input = this.querySelector('input[type="radio"]');
                const questionId = input.name.match(/\d+/)[0]; // gets MCQ id
                const answerId = input.value;

                // Store answer in localStorage
                let savedAnswers = JSON.parse(localStorage.getItem('mcq_answers')) || {};
                savedAnswers[questionId] = answerId;
                localStorage.setItem('mcq_answers', JSON.stringify(savedAnswers));

                // Feedback logic
                if (isCorrect) {
                    this.classList.add('bg-success', 'text-white');
                    feedback.innerHTML = '✔ Correct';
                } else {
                    this.classList.add('bg-danger', 'text-white');
                    feedback.innerHTML = '✖ Wrong';
                }
                feedback.style.display = 'inline';

                input.checked = true;
                this.dataset.answered = "1";

                // Disable other options
                const siblings = this.closest('.options-container').querySelectorAll('.option-item');
                siblings.forEach(sib => {
                    sib.dataset.answered = "1";
                    sib.style.pointerEvents = "none";
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let duration = {{ $selectedTopicData->exam_duration ?? 10 }} * 60; // total seconds
            let startTime = Date.now(); // exam start timestamp
            let timerDisplay = document.getElementById("exam-timer");

            function updateTimer() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;
                timerDisplay.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;

                if (duration <= 0) {
                    clearInterval(timerInterval);
                    alert("⏳ Time's up! Your exam will be submitted automatically.");
                    
                    // Calculate time taken in minutes
                    let endTime = Date.now();
                    let diffInSeconds = Math.floor((endTime - startTime) / 1000);
                    let diffInMinutes = (diffInSeconds / 60).toFixed(2); // fractional minutes
                    document.getElementById("time_taken").value = diffInMinutes;

                    document.getElementById("mcq-form").submit();
                }
                duration--;
            }

            let timerInterval = setInterval(updateTimer, 1000);

            document.getElementById("mcq-form").addEventListener("submit", function () {
                let endTime = Date.now();
                let diffInSeconds = Math.floor((endTime - startTime) / 1000);
                let diffInMinutes = (diffInSeconds / 60).toFixed(2); // fractional minutes
                document.getElementById("time_taken").value = diffInMinutes;
            });
        });
    </script>
@endsection
