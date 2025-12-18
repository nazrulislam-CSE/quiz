@extends('layouts.admin.app', [$pageTitle ?? 'MCQ' => 'Edit Topic Study MCQ'])

@section('content')
    <div class="breadcrumb-header justify-content-between">
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Topic MCQs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Edit MCQ' }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-auto"></div>
    </div>

    <div class="main-content-body">
        <div class="row row-sm">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <p class="card-title my-0">{{ $pageTitle ?? 'Edit Topic MCQ Questions' }}</p>
                    <div class="d-flex">
                        <a href="{{ route('admin.topic.study.mcq.show', [
                            'admission' => $topicInfo->admission_id,
                            'department' => $topicInfo->department_id,
                            'subject' => $topicInfo->subject_id,
                            'topic' => $topicInfo->topic_id,
                        ]) }}"
                            class="btn btn-info me-2">
                            <i class="fas fa-eye"></i> View Questions
                        </a>
                        <a href="{{ route('admin.topic.study.mcq.index') }}" class="btn btn-danger">
                            <i class="fas fa-list d-inline"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Topic Information -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Admission:</strong> {{ $topicInfo->admission->name ?? '-' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Department:</strong> {{ $topicInfo->department->name ?? '-' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Subject:</strong> {{ $topicInfo->subject->name ?? '-' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Topic:</strong> {{ $topicInfo->topic->name ?? '-' }}
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Total Questions:</strong> {{ $questions->count() }}
                        </div>
                    </div>

                    <form
                        action="{{ route('admin.topic.study.mcq.update', [
                            'admission' => $topicInfo->admission_id,
                            'department' => $topicInfo->department_id,
                            'subject' => $topicInfo->subject_id,
                            'topic' => $topicInfo->topic_id,
                        ]) }}"
                        method="post">
                        @csrf
                        @method('PUT')

                        <div class="questions-wrapper">
                            @foreach ($questions as $index => $question)
                                <div class="card mb-4 question-block">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Question {{ $index + 1 }}</h5>
                                        <span class="badge bg-primary">ID: {{ $question->id }}</span>
                                    </div>
                                    <div class="card-body">
                                        <!-- Question Text -->
                                        <div class="form-group mb-3">
                                            <label class="form-label">Question Text:</label>
                                            <textarea name="questions[{{ $question->id }}][text]" class="form-control" rows="3" required>{{ old("questions.$question->id.text", $question->question) }}</textarea>
                                            @error("questions.$question->id.text")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Options (Using answers table) -->
                                        @if ($question->answers->count() > 0)
                                            <div class="row">
                                                @foreach ($question->answers as $answer)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="input-group">
                                                            <span
                                                                class="input-group-text">{{ chr(65 + $loop->index) }})</span>
                                                            <input type="text"
                                                                name="questions[{{ $question->id }}][answers][{{ $answer->id }}]"
                                                                class="form-control"
                                                                value="{{ old("questions.$question->id.answers.$answer->id", $answer->answer) }}"
                                                                placeholder="Option {{ $loop->iteration }}" required>
                                                            <div class="input-group-text">
                                                                <input type="radio"
                                                                    name="questions[{{ $question->id }}][correct_answer]"
                                                                    value="{{ $answer->id }}"
                                                                    {{ old("questions.$question->id.correct_answer", $answer->is_correct) ? 'checked' : '' }}
                                                                    class="form-check-input">
                                                                <label class="form-check-label ms-2">Correct</label>
                                                            </div>
                                                        </div>
                                                        @error("questions.$question->id.answers.$answer->id")
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- Delete existing question button -->
                                            <div class="mt-3 d-flex justify-content-end">
                                                <a href="{{ route('admin.single.topic.study.mcq.destroy', $question->id) }}" id="delete"
                                                class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Delete Question
                                                </a>
                                            </div>
                                        @else
                                            <!-- Options (Using direct columns - option_a, option_b, etc.) -->
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">A)</span>
                                                        <input type="text"
                                                            name="questions[{{ $question->id }}][option_a]"
                                                            class="form-control"
                                                            value="{{ old("questions.$question->id.option_a", $question->option_a) }}"
                                                            placeholder="Option A" required>
                                                        <div class="input-group-text">
                                                            <input type="radio"
                                                                name="questions[{{ $question->id }}][correct_answer]"
                                                                value="a"
                                                                {{ old("questions.$question->id.correct_answer", $question->correct_answer) == 'a' ? 'checked' : '' }}
                                                                class="form-check-input">
                                                            <label class="form-check-label ms-2">Correct</label>
                                                        </div>
                                                    </div>
                                                    @error("questions.$question->id.option_a")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">B)</span>
                                                        <input type="text"
                                                            name="questions[{{ $question->id }}][option_b]"
                                                            class="form-control"
                                                            value="{{ old("questions.$question->id.option_b", $question->option_b) }}"
                                                            placeholder="Option B" required>
                                                        <div class="input-group-text">
                                                            <input type="radio"
                                                                name="questions[{{ $question->id }}][correct_answer]"
                                                                value="b"
                                                                {{ old("questions.$question->id.correct_answer", $question->correct_answer) == 'b' ? 'checked' : '' }}
                                                                class="form-check-input">
                                                            <label class="form-check-label ms-2">Correct</label>
                                                        </div>
                                                    </div>
                                                    @error("questions.$question->id.option_b")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">C)</span>
                                                        <input type="text"
                                                            name="questions[{{ $question->id }}][option_c]"
                                                            class="form-control"
                                                            value="{{ old("questions.$question->id.option_c", $question->option_c) }}"
                                                            placeholder="Option C" required>
                                                        <div class="input-group-text">
                                                            <input type="radio"
                                                                name="questions[{{ $question->id }}][correct_answer]"
                                                                value="c"
                                                                {{ old("questions.$question->id.correct_answer", $question->correct_answer) == 'c' ? 'checked' : '' }}
                                                                class="form-check-input">
                                                            <label class="form-check-label ms-2">Correct</label>
                                                        </div>
                                                    </div>
                                                    @error("questions.$question->id.option_c")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">D)</span>
                                                        <input type="text"
                                                            name="questions[{{ $question->id }}][option_d]"
                                                            class="form-control"
                                                            value="{{ old("questions.$question->id.option_d", $question->option_d) }}"
                                                            placeholder="Option D" required>
                                                        <div class="input-group-text">
                                                            <input type="radio"
                                                                name="questions[{{ $question->id }}][correct_answer]"
                                                                value="d"
                                                                {{ old("questions.$question->id.correct_answer", $question->correct_answer) == 'd' ? 'checked' : '' }}
                                                                class="form-check-input">
                                                            <label class="form-check-label ms-2">Correct</label>
                                                        </div>
                                                    </div>
                                                    @error("questions.$question->id.option_d")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Individual question actions -->
                                        <div class="mt-3 d-flex justify-content-end">

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- ============ ADD NEW QUESTIONS SECTION ============ -->
                        <div class="section-title mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4><i class="fas fa-plus-circle text-success"></i> Add New Questions</h4>
                                <button type="button" class="btn btn-success" id="addNewQuestionBtn">
                                    <i class="fas fa-plus"></i> Add New Question
                                </button>
                            </div>
                            <p class="text-muted">Click "Add New Question" to add more questions to this topic.</p>
                        </div>

                        <div id="newQuestionsWrapper" class="mb-5">
                            <!-- New questions will be added here dynamically -->
                            @if(old('new_questions'))
                                @foreach(old('new_questions') as $index => $newQuestion)
                                    <div class="card mb-4 new-question-block" data-index="{{ $index }}">
                                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">New Question {{ $loop->iteration }}</h5>
                                            <button type="button" class="btn btn-sm btn-light remove-new-question">
                                                <i class="fas fa-times"></i> Remove
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <!-- Question Text -->
                                            <div class="form-group mb-3">
                                                <label class="form-label">Question Text:</label>
                                                <textarea name="new_questions[{{ $index }}][text]" class="form-control" rows="3" required>{{ $newQuestion['text'] ?? '' }}</textarea>
                                                @error("new_questions.$index.text")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Options -->
                                            <div class="row">
                                                @php
                                                    $newCorrectAnswer = $newQuestion['correct_answer'] ?? '';
                                                @endphp
                                                <div class="col-md-6 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">A)</span>
                                                        <input type="text"
                                                            name="new_questions[{{ $index }}][option_a]"
                                                            class="form-control"
                                                            value="{{ $newQuestion['option_a'] ?? '' }}"
                                                            placeholder="Option A" required>
                                                        <div class="input-group-text">
                                                            <input type="radio"
                                                                name="new_questions[{{ $index }}][correct_answer]"
                                                                value="a"
                                                                @if($newCorrectAnswer == 'a') checked @endif
                                                                class="correct-radio">
                                                            <label class="form-check-label ms-2">Correct</label>
                                                        </div>
                                                    </div>
                                                    @error("new_questions.$index.option_a")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">B)</span>
                                                        <input type="text"
                                                            name="new_questions[{{ $index }}][option_b]"
                                                            class="form-control"
                                                            value="{{ $newQuestion['option_b'] ?? '' }}"
                                                            placeholder="Option B" required>
                                                        <div class="input-group-text">
                                                            <input type="radio"
                                                                name="new_questions[{{ $index }}][correct_answer]"
                                                                value="b"
                                                                @if($newCorrectAnswer == 'b') checked @endif
                                                                class="correct-radio">
                                                            <label class="form-check-label ms-2">Correct</label>
                                                        </div>
                                                    </div>
                                                    @error("new_questions.$index.option_b")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">C)</span>
                                                        <input type="text"
                                                            name="new_questions[{{ $index }}][option_c]"
                                                            class="form-control"
                                                            value="{{ $newQuestion['option_c'] ?? '' }}"
                                                            placeholder="Option C" required>
                                                        <div class="input-group-text">
                                                            <input type="radio"
                                                                name="new_questions[{{ $index }}][correct_answer]"
                                                                value="c"
                                                                @if($newCorrectAnswer == 'c') checked @endif
                                                                class="correct-radio">
                                                            <label class="form-check-label ms-2">Correct</label>
                                                        </div>
                                                    </div>
                                                    @error("new_questions.$index.option_c")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">D)</span>
                                                        <input type="text"
                                                            name="new_questions[{{ $index }}][option_d]"
                                                            class="form-control"
                                                            value="{{ $newQuestion['option_d'] ?? '' }}"
                                                            placeholder="Option D" required>
                                                        <div class="input-group-text">
                                                            <input type="radio"
                                                                name="new_questions[{{ $index }}][correct_answer]"
                                                                value="d"
                                                                @if($newCorrectAnswer == 'd') checked @endif
                                                                class="correct-radio">
                                                            <label class="form-check-label ms-2">Correct</label>
                                                        </div>
                                                    </div>
                                                    @error("new_questions.$index.option_d")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mt-4 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Update All Questions
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .question-block {
            border-left: 4px solid #007bff;
        }

        .input-group-text {
            background-color: #f8f9fa;
        }

        .form-check-input {
            margin-top: 0;
        }

          .question-block {
            border-left: 4px solid #007bff;
        }
        .new-question-block {
            border-left: 4px solid #28a745;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
        .correct-radio {
            margin-top: 0;
            cursor: pointer;
        }
        .section-title {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@push('admin')
    <script>
        $(document).ready(function() {
            // Auto focus first textarea
            $('textarea').first().focus();

            // Make radio buttons easier to click
            $('.form-check-input').on('click', function() {
                $(this).closest('.input-group').find('.form-check-input').prop('checked', false);
                $(this).prop('checked', true);
            });

            // Confirmation before submit
            $('form').on('submit', function() {
                return confirm('Are you sure you want to update all questions?');
            });
        });
    </script>
     <script>
        $(document).ready(function() {
            let newQuestionCount = {{ count(old('new_questions') ?? []) }};
            updateNewQuestionCount();

            // Auto focus first textarea
            $('textarea').first().focus();

            // Radio button group handling
            $(document).on('click', '.correct-radio', function() {
                let questionBlock = $(this).closest('.card-body');
                questionBlock.find('.correct-radio').prop('checked', false);
                $(this).prop('checked', true);
            });

            // Add new question functionality
            $('#addNewQuestionBtn').click(function() {
                let index = newQuestionCount;
                let newQuestionHtml = `
                    <div class="card mb-4 new-question-block" data-index="${index}">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">New Question ${newQuestionCount + 1}</h5>
                            <button type="button" class="btn btn-sm btn-light remove-new-question">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Question Text -->
                            <div class="form-group mb-3">
                                <label class="form-label">Question Text:</label>
                                <textarea name="new_questions[${index}][text]" class="form-control" rows="3" required placeholder="Enter question text"></textarea>
                            </div>

                            <!-- Options -->
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text">A)</span>
                                        <input type="text"
                                            name="new_questions[${index}][option_a]"
                                            class="form-control"
                                            placeholder="Option A" required>
                                        <div class="input-group-text">
                                            <input type="radio"
                                                name="new_questions[${index}][correct_answer]"
                                                value="a"
                                                class="correct-radio">
                                            <label class="form-check-label ms-2">Correct</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text">B)</span>
                                        <input type="text"
                                            name="new_questions[${index}][option_b]"
                                            class="form-control"
                                            placeholder="Option B" required>
                                        <div class="input-group-text">
                                            <input type="radio"
                                                name="new_questions[${index}][correct_answer]"
                                                value="b"
                                                class="correct-radio">
                                            <label class="form-check-label ms-2">Correct</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text">C)</span>
                                        <input type="text"
                                            name="new_questions[${index}][option_c]"
                                            class="form-control"
                                            placeholder="Option C" required>
                                        <div class="input-group-text">
                                            <input type="radio"
                                                name="new_questions[${index}][correct_answer]"
                                                value="c"
                                                class="correct-radio">
                                            <label class="form-check-label ms-2">Correct</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text">D)</span>
                                        <input type="text"
                                            name="new_questions[${index}][option_d]"
                                            class="form-control"
                                            placeholder="Option D" required>
                                        <div class="input-group-text">
                                            <input type="radio"
                                                name="new_questions[${index}][correct_answer]"
                                                value="d"
                                                class="correct-radio">
                                            <label class="form-check-label ms-2">Correct</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#newQuestionsWrapper').append(newQuestionHtml);
                newQuestionCount++;
                updateNewQuestionCount();
                
                // Scroll to new question
                $('html, body').animate({
                    scrollTop: $('.new-question-block').last().offset().top - 100
                }, 500);
            });

            // Remove new question
            $(document).on('click', '.remove-new-question', function() {
                $(this).closest('.new-question-block').remove();
                newQuestionCount--;
                updateNewQuestionCount();
                renumberNewQuestions();
            });

            // SIMPLIFIED VALIDATION - Only check new questions
            $('form').on('submit', function(e) {
                let hasError = false;
                
                // Check ONLY new questions for correct answer
                $('.new-question-block').each(function() {
                    let block = $(this);
                    let hasData = false;
                    let hasCorrect = false;
                    
                    // Check if any data is entered
                    if (block.find('textarea').val().trim() !== '' || 
                        block.find('input[name*="[option_a]"]').val().trim() !== '') {
                        hasData = true;
                    }
                    
                    // Check if correct answer is selected
                    hasCorrect = block.find('.correct-radio:checked').length > 0;
                    
                    // If data is entered but no correct answer selected
                    if (hasData && !hasCorrect) {
                        e.preventDefault();
                        alert('Please select correct answer for all NEW questions!');
                        hasError = true;
                        return false;
                    }
                });
                
                if (!hasError) {
                    return confirm('Are you sure you want to update all questions?');
                }
            });

            // Helper functions
            function updateNewQuestionCount() {
                $('#newQuestionCount').text('New: ' + newQuestionCount);
            }

            function renumberNewQuestions() {
                $('.new-question-block').each(function(index) {
                    $(this).find('.card-header h5').text('New Question ' + (index + 1));
                });
            }
            
        });
    </script>
@endpush
