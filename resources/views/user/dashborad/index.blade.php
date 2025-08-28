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
                            <span class="badge bg-light text-primary" id="step-badge">Step 1 of 4</span>
                        </div>
                    </div>
                    
                    <div class="progress-container">
                        <div class="progress">
                            <div class="progress-bar" id="progress-bar" style="width: 25%"></div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Step Indicator -->
                        <div class="step-indicator mb-5">
                            <div class="step active" id="step-1">1</div>
                            <div class="step" id="step-2">2</div>
                            <div class="step" id="step-3">3</div>
                            <div class="step" id="step-4">4</div>
                        </div>
                        
                        <!-- Step 1: Admission Selection -->
                        <div class="step-content active" id="step-content-1">
                            <h5 class="step-title"><i class="fas fa-university me-2"></i>Select Admission</h5>
                            
                            <div class="mb-4">
                                <div class="selection-item selected" data-value="1">
                                    <input type="radio" name="admission" id="admission1" checked>
                                    <label for="admission1" class="mb-0">Fall 2023 Admission</label>
                                </div>
                                <div class="selection-item" data-value="2">
                                    <input type="radio" name="admission" id="admission2">
                                    <label for="admission2" class="mb-0">Spring 2023 Admission</label>
                                </div>
                                <div class="selection-item" data-value="3">
                                    <input type="radio" name="admission" id="admission3">
                                    <label for="admission3" class="mb-0">Summer 2023 Admission</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button class="btn btn-primary" onclick="nextStep(2)">
                                    Next<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 2: Department Selection -->
                        <div class="step-content" id="step-content-2">
                            <h5 class="step-title"><i class="fas fa-building me-2"></i>Select Department</h5>
                            
                            <div class="mb-4">
                                <div class="selection-item" data-value="1">
                                    <input type="radio" name="department" id="dept1">
                                    <label for="dept1" class="mb-0">Computer Science & Engineering</label>
                                </div>
                                <div class="selection-item" data-value="2">
                                    <input type="radio" name="department" id="dept2">
                                    <label for="dept2" class="mb-0">Electrical & Electronic Engineering</label>
                                </div>
                                <div class="selection-item" data-value="3">
                                    <input type="radio" name="department" id="dept3">
                                    <label for="dept3" class="mb-0">Business Administration</label>
                                </div>
                                <div class="selection-item" data-value="4">
                                    <input type="radio" name="department" id="dept4">
                                    <label for="dept4" class="mb-0">Economics</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button class="btn btn-outline-secondary" onclick="prevStep(1)">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button class="btn btn-primary" onclick="nextStep(3)">
                                    Next<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 3: Subject Selection -->
                        <div class="step-content" id="step-content-3">
                            <h5 class="step-title"><i class="fas fa-book me-2"></i>Select Subject</h5>
                            
                            <div class="mb-4">
                                <div class="selection-item" data-value="1">
                                    <input type="radio" name="subject" id="subject1">
                                    <label for="subject1" class="mb-0">Data Structures and Algorithms</label>
                                </div>
                                <div class="selection-item" data-value="2">
                                    <input type="radio" name="subject" id="subject2">
                                    <label for="subject2" class="mb-0">Database Management Systems</label>
                                </div>
                                <div class="selection-item" data-value="3">
                                    <input type="radio" name="subject" id="subject3">
                                    <label for="subject3" class="mb-0">Computer Networks</label>
                                </div>
                                <div class="selection-item" data-value="4">
                                    <input type="radio" name="subject" id="subject4">
                                    <label for="subject4" class="mb-0">Software Engineering</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button class="btn btn-outline-secondary" onclick="prevStep(2)">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button class="btn btn-primary" onclick="nextStep(4)">
                                    Next<i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 4: Topic Selection -->
                        <div class="step-content" id="step-content-4">
                            <h5 class="step-title"><i class="fas fa-tag me-2"></i>Select Topic</h5>
                            
                            <div class="mb-4">
                                <div class="selection-item" data-value="1">
                                    <input type="radio" name="topic" id="topic1">
                                    <label for="topic1" class="mb-0">Arrays and Linked Lists</label>
                                </div>
                                <div class="selection-item" data-value="2">
                                    <input type="radio" name="topic" id="topic2">
                                    <label for="topic2" class="mb-0">Stacks and Queues</label>
                                </div>
                                <div class="selection-item" data-value="3">
                                    <input type="radio" name="topic" id="topic3">
                                    <label for="topic3" class="mb-0">Trees and Graphs</label>
                                </div>
                                <div class="selection-item" data-value="4">
                                    <input type="radio" name="topic" id="topic4">
                                    <label for="topic4" class="mb-0">Sorting Algorithms</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button class="btn btn-outline-secondary" onclick="prevStep(3)">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button class="btn btn-success" onclick="startExam()">
                                    Start Exam<i class="fas fa-play-circle ms-2"></i>
                                </button>
                            </div>
                        </div>
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
                            <span><i class="fas fa-book-open me-2"></i>Data Structures and Algorithms - Topic: Arrays</span>
                            <span class="badge bg-light text-primary">Question <span id="current-question">1</span> of 20</span>
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
                                <h5 class="m-0" id="question-text">Which of the following is a linear data structure?</h5>
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
                                            <i class="fas fa-check-circle me-2"></i>Correct! Arrays are linear data structures.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Result Item 2 -->
                            <div class="result-card result-incorrect">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="question-number">2</div>
                                        <h6 class="m-0">What is the time complexity of accessing an element in an array?</h6>
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
                                            <i class="fas fa-times-circle me-2"></i>Incorrect! The correct answer is O(1) - constant time access.
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
        // Current step tracker
        let currentStep = 1;
        
        // Function to navigate to next step
        function nextStep(step) {
            if (step > 4) return;
            
            // Hide current step
            document.getElementById(`step-content-${currentStep}`).classList.remove('active');
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            
            // Show next step
            document.getElementById(`step-content-${step}`).classList.add('active');
            document.getElementById(`step-${step}`).classList.add('active');
            
            // Update progress bar
            const progressPercentage = (step / 4) * 100;
            document.getElementById('progress-bar').style.width = `${progressPercentage}%`;
            
            // Update step badge
            document.getElementById('step-badge').textContent = `Step ${step} of 4`;
            
            // Update current step
            currentStep = step;
        }
        
        // Function to navigate to previous step
        function prevStep(step) {
            if (step < 1) return;
            
            // Hide current step
            document.getElementById(`step-content-${currentStep}`).classList.remove('active');
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            
            // Show previous step
            document.getElementById(`step-content-${step}`).classList.add('active');
            document.getElementById(`step-${step}`).classList.add('active');
            
            // Update progress bar
            const progressPercentage = (step / 4) * 100;
            document.getElementById('progress-bar').style.width = `${progressPercentage}%`;
            
            // Update step badge
            document.getElementById('step-badge').textContent = `Step ${step} of 4`;
            
            // Update current step
            currentStep = step;
        }
        
        // Simple interaction for selection items
        document.querySelectorAll('.selection-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove selected class from all items in the same group
                const groupName = this.querySelector('input').getAttribute('name');
                document.querySelectorAll(`.selection-item input[name="${groupName}"]`).forEach(input => {
                    input.parentElement.classList.remove('selected');
                });
                
                // Add selected class to clicked item
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });

        // Switch between sections
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        // Start the exam
        function startExam() {
            showSection('exam-section');
            initExam();
        }

        // Back to selection screen
        function backToSelection() {
            showSection('selection-section');
        }

        // Exam questions data
        const questions = [
            {
                id: 1,
                question: "Which of the following is a linear data structure?",
                options: ["Tree", "Graph", "Array", "Network"],
                correctAnswer: 3
            },
            {
                id: 2,
                question: "What is the time complexity of accessing an element in an array?",
                options: ["O(n)", "O(log n)", "O(1)", "O(n log n)"],
                correctAnswer: 3
            },
            {
                id: 3,
                question: "Which of the following is not a type of array?",
                options: ["Single-dimensional", "Double-dimensional", "Multi-dimensional", "Linked-dimensional"],
                correctAnswer: 4
            },
            // Add more questions here...
        ];

        // Initialize exam variables
        let currentQuestion = 1;
        const totalQuestions = 20;
        let userAnswers = new Array(totalQuestions).fill(null);
        let timeLeft = 30 * 60; // 30 minutes in seconds
        let timerInterval;

        // Initialize the exam
        function initExam() {
            // Generate question navigation
            generateQuestionNav();
            
            // Start the timer
            startTimer();
            
            // Display first question
            showQuestion(currentQuestion);
            
            // Set up event listeners
            document.getElementById('prev-btn').addEventListener('click', goToPreviousQuestion);
            document.getElementById('next-btn').addEventListener('click', goToNextQuestion);
            document.getElementById('submit-btn').addEventListener('click', submitExam);
            
            // Set up option selection
            document.querySelectorAll('.option-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectOption(this);
                });
            });
        }

        // Generate question navigation numbers
        function generateQuestionNav() {
            const navContainer = document.getElementById('question-nav');
            navContainer.innerHTML = '';
            
            for (let i = 1; i <= totalQuestions; i++) {
                const navItem = document.createElement('div');
                navItem.className = 'question-nav-item';
                if (i === 1) navItem.classList.add('current');
                navItem.textContent = i;
                navItem.setAttribute('data-question', i);
                
                navItem.addEventListener('click', function() {
                    goToQuestion(parseInt(this.getAttribute('data-question')));
                });
                
                navContainer.appendChild(navItem);
            }
        }

        // Show a specific question
        function showQuestion(questionNumber) {
            // Update current question indicator
            document.getElementById('current-question').textContent = questionNumber;
            document.getElementById('q-number').textContent = questionNumber;
            
            // Update progress bar
            const progressPercentage = (questionNumber / totalQuestions) * 100;
            document.getElementById('question-progress').style.width = `${progressPercentage}%`;
            
            // Update navigation buttons
            document.getElementById('prev-btn').disabled = (questionNumber === 1);
            
            if (questionNumber === totalQuestions) {
                document.getElementById('next-btn').style.display = 'none';
                document.getElementById('submit-btn').style.display = 'block';
            } else {
                document.getElementById('next-btn').style.display = 'block';
                document.getElementById('submit-btn').style.display = 'none';
            }
            
            // Update question navigation
            document.querySelectorAll('.question-nav-item').forEach(item => {
                item.classList.remove('current');
                if (parseInt(item.getAttribute('data-question')) === questionNumber) {
                    item.classList.add('current');
                }
                
                // Mark answered questions
                const qNum = parseInt(item.getAttribute('data-question'));
                if (userAnswers[qNum - 1] !== null) {
                    item.classList.add('answered');
                } else {
                    item.classList.remove('answered');
                }
            });
            
            // For demo purposes, we're using a limited set of questions
            const questionIndex = (questionNumber - 1) % questions.length;
            const question = questions[questionIndex];
            
            // Update question text
            document.getElementById('question-text').textContent = question.question;
            
            // Update options
            const options = document.querySelectorAll('.option-item');
            options.forEach((option, index) => {
                const optionText = option.querySelector('.form-check-label');
                optionText.textContent = question.options[index];
                
                // Clear previous selection
                const radio = option.querySelector('input[type="radio"]');
                radio.checked = false;
                option.classList.remove('selected');
                
                // Check if user has previously selected this option
                if (userAnswers[questionNumber - 1] === index + 1) {
                    radio.checked = true;
                    option.classList.add('selected');
                }
            });
        }

        // Select an option
        function selectOption(optionElement) {
            // Clear previous selection
            optionElement.parentElement.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
                item.querySelector('input[type="radio"]').checked = false;
            });
            
            // Select current option
            optionElement.classList.add('selected');
            optionElement.querySelector('input[type="radio"]').checked = true;
            
            // Save user's answer
            const selectedOption = parseInt(optionElement.getAttribute('data-option'));
            userAnswers[currentQuestion - 1] = selectedOption;
            
            // Mark question as answered in navigation
            document.querySelector(`.question-nav-item[data-question="${currentQuestion}"]`).classList.add('answered');
        }

        // Go to next question
        function goToNextQuestion() {
            if (currentQuestion < totalQuestions) {
                goToQuestion(currentQuestion + 1);
            }
        }

        // Go to previous question
        function goToPreviousQuestion() {
            if (currentQuestion > 1) {
                goToQuestion(currentQuestion - 1);
            }
        }

        // Go to specific question
        function goToQuestion(questionNumber) {
            currentQuestion = questionNumber;
            showQuestion(currentQuestion);
        }

        // Start the exam timer
        function startTimer() {
            clearInterval(timerInterval);
            
            timerInterval = setInterval(function() {
                timeLeft--;
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Change color when time is running out
                if (timeLeft <= 300) { // 5 minutes
                    document.getElementById('exam-timer').classList.add('timer-danger');
                } else if (timeLeft <= 600) { // 10 minutes
                    document.getElementById('exam-timer').classList.add('timer-warning');
                }
                
                // Auto submit when time is up
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    submitExam();
                }
            }, 1000);
        }

        // Submit the exam
        function submitExam() {
            clearInterval(timerInterval);
            
            // Calculate score (for demo, we're using a simple calculation)
            const score = calculateScore();
            const percentage = Math.round((score / totalQuestions) * 100);
            
            // Update results
            document.getElementById('score').textContent = score;
            document.getElementById('percentage').textContent = `${percentage}%`;
            
            // Show results section
            showSection('results-section');
        }

        // Calculate score (for demo purposes)
        function calculateScore() {
            let score = 0;
            for (let i = 0; i < totalQuestions; i++) {
                // For demo, every 3rd question is wrong, others are right
                if (i % 3 !== 0) {
                    score++;
                }
            }
            return score;
        }
    </script>
@endsection
