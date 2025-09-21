@extends('layouts.frontend.app', [$pageTitle => $pageTitle])
@section('content')
    <div class="container">
        <!-- Exam Header -->
        <div class="row mb-4 g-3">
            <div class="col-lg-12">
                <h3 class="text-center text-success fw-bold mt-5 mb-4">পরীক্ষা শুরু</h3>
            </div>
        </div>

        <!-- Quiz Info Card -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow-sm border-success">
                    <div class="card-body">
                        <h5 class="fw-bold text-success mb-3 text-center">{{ $mcq->title }}</h5>
                        <div class="d-flex justify-content-around flex-wrap text-center">
                            <div class="p-2">
                                <i class="fas fa-clock text-success me-1"></i> সময়: {{ $mcq->exam_duration }} মিনিট
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timer -->
        <div class="row mb-4">
            <div class="col-lg-12 text-center">
                <div class="card shadow-sm border-success">
                    <div class="card-body">
                        <h5 class="fw-bold text-danger">সময় চলছে: <span id="exam-timer">00:00</span></h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- MCQ Form -->
       <form action="{{ route('exam.submit') }}" method="POST" id="mcq-form">
            @csrf
            <input type="hidden" name="quiz_id" value="{{ $mcq->id }}">
            <input type="hidden" name="time_taken" id="time_taken">

            <div class="card shadow-sm mb-4 question-container">
                <div class="card-header bg-success text-white">
                    প্রশ্ন {{ $mcq->id }}
                </div>
                <div class="card-body">
                    <p class="fw-bold">{{ $mcq->question }}</p>
                    <div class="options-container">
                        @foreach ($mcq->answers as $answer)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="answers[{{ $mcq->id }}]"
                                    id="option{{ $answer->id }}" value="{{ $answer->id }}" required>
                                <label class="form-check-label" for="option{{ $answer->id }}">
                                    {{ $answer->answer }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <!-- Navigation -->
            <div class="d-flex justify-content-between mb-5">
                <button type="button" class="btn btn-outline-secondary" id="prev-btn" disabled>পূর্ববর্তী</button>
                <button type="button" class="btn btn-primary" id="next-btn">পরবর্তী</button>
                <button type="submit" class="btn btn-success" id="submit-btn" style="display:none;">পরীক্ষা জমা
                    দিন</button>
            </div>
        </form>
    </div>

    <!-- JS: Timer & Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Timer functionality
            let duration = {{ $mcq->exam_duration }} * 60; // Convert minutes to seconds
            const timerEl = document.getElementById('exam-timer');
            const timeTakenEl = document.getElementById('time_taken');
            const form = document.getElementById('mcq-form');
            
            // Function to update timer display
            function updateTimer() {
                if (duration <= 0) {
                    clearInterval(timerInterval);
                    // Auto-submit the form when time is up
                    form.submit();
                    return;
                }
                
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                
                // Update timer display
                timerEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Add warning class when less than 5 minutes remain
                if (minutes < 5) {
                    timerEl.classList.add('timer-warning');
                }
                
                // Update the hidden time_taken field
                timeTakenEl.value = ({{ $mcq->exam_duration }} * 60) - duration;
                
                duration--; // Decrement the timer
            }
            
            // Initial call to set the timer
            updateTimer();
            
            // Set interval to update timer every second
            const timerInterval = setInterval(updateTimer, 1000);
            
            // Question navigation (kept from original code)
            let currentQuestion = 0;
            const questions = document.querySelectorAll('.question-container');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');

            function showQuestion(index) {
                questions.forEach((q, i) => q.style.display = i === index ? 'block' : 'none');
                prevBtn.disabled = index === 0;
                nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
                submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
            }

            prevBtn.addEventListener('click', () => {
                if (currentQuestion > 0) {
                    currentQuestion--;
                    showQuestion(currentQuestion);
                }
            });
            
            nextBtn.addEventListener('click', () => {
                if (currentQuestion < questions.length - 1) {
                    currentQuestion++;
                    showQuestion(currentQuestion);
                }
            });

            // Initialize question display
            showQuestion(currentQuestion);
            
            // Form submission handler
            form.addEventListener('submit', function(e) {
                // Stop the timer when form is submitted
                clearInterval(timerInterval);
                
                // Make sure time_taken is updated with the final value
                timeTakenEl.value = ({{ $mcq->exam_duration }} * 60) - duration;
            });
        });
    </script>
@endsection
