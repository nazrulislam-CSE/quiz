@extends('layouts.admin.app', [$pageTitle => 'Edit MCQ'])

@section('content')
<div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.mcq.index') }}">MCQ List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit MCQ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="main-content-body">
    <div class="row row-sm">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <p class="card-title my-0">Edit MCQ Question</p>
                <div class="d-flex">
                    <a href="{{ route('admin.mcq.index')}}" class="btn btn-danger me-2">
                        <i class="fas fa-list d-inline"></i> MCQ List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mcq.update', $mcq->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Admission Selection -->
                        <div class="form-group col-xl-4 col-lg-4 col-md-6">
                            <label for="admission_id">Admission <span class="text-danger">*</span></label>
                            <select name="admission_id" id="admission_id" class="form-control select2 @error('admission_id') is-invalid @enderror" required>
                                <option value="">Select Admission</option>
                                @foreach ($admissions as $admission)
                                    <option value="{{ $admission->id }}" {{ old('admission_id', $mcq->admission_id) == $admission->id ? 'selected' : '' }}>
                                        {{ $admission->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('admission_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Department Selection -->
                        <div class="form-group col-xl-4 col-lg-4 col-md-6">
                            <label for="department_id">Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-control select2 @error('department_id') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $mcq->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div class="form-group col-xl-4 col-lg-4 col-md-6">
                            <label for="subject_id">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="form-control select2 @error('subject_id') is-invalid @enderror" required>
                                <option value="">Select Subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $mcq->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Topic Selection -->
                        <div class="form-group col-xl-4 col-lg-4 col-md-6">
                            <label for="topic_id">Topic <span class="text-danger">*</span></label>
                            <select name="topic_id" id="topic_id" class="form-control select2 @error('topic_id') is-invalid @enderror" required>
                                <option value="">Select Topic</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ old('topic_id', $mcq->topic_id) == $topic->id ? 'selected' : '' }}>
                                        {{ $topic->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('topic_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Questions Container -->
                        <div class="col-12 mt-4" id="questions-container">
                            <!-- Existing Question -->
                            <div class="card shadow-none border mb-3 question-card" data-question-index="0">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Question #1</h5>
                                    <button type="button" class="btn btn-danger btn-sm remove-question" data-question-id="{{ $mcq->id }}">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Question Text <span class="text-danger">*</span></label>
                                        <textarea name="questions[0][text]" class="form-control @error('questions.0.text') is-invalid @enderror" 
                                            rows="3" placeholder="Enter question text" required>{{ old('questions.0.text', $mcq->question) }}</textarea>
                                        <input type="hidden" name="questions[0][id]" value="{{ $mcq->id }}">
                                        @error('questions.0.text')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label>Options <span class="text-danger">*</span> <small class="text-muted">(Select the correct answer)</small></label>
                                        <div class="row">
                                            @foreach($mcq->answers as $index => $answer)
                                               <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="questions[0][answers][{{ $index }}][text]" 
                                                            class="form-control @error('questions.0.answers.'.$index.'.text') is-invalid @enderror" 
                                                            placeholder="Option {{ $index+1 }}" 
                                                            value="{{ old('questions.0.answers.'.$index.'.text', $answer->answer) }}" 
                                                            required>
                                                        <input type="hidden" name="questions[0][answers][{{ $index }}][id]" value="{{ $answer->id }}">
                                                        <div class="input-group-text">
                                                            <input type="radio" name="questions[0][correct_answer]" 
                                                                value="{{ $index }}" 
                                                                {{ old('questions.0.correct_answer', $answer->is_correct ? $index : '') == $index ? 'checked' : '' }}
                                                                class="form-check-input" style="cursor: pointer; margin-right:5px;">
                                                            Correct
                                                        </div>
                                                        @error('questions.0.answers.'.$index.'.text')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            @error('questions.0.correct_answer')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add More Question Button -->
                        <div class="col-12 mt-2">
                            <button type="button" id="add-question" class="btn btn-success">
                                <i class="fas fa-plus mr-2"></i> Add Another Question
                            </button>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save mr-2"></i> Update MCQ
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin')
<script>
    $(document).ready(function() {
        // Initialize select2
        $('.select2').select2({
            width: '100%'
        });

        // Load departments when admission changes
        $('#admission_id').on('change', function() {
            var admissionID = $(this).val();
            $('#department_id').html('<option value="">Loading...</option>');
            $('#subject_id').html('<option value="">Select Subject</option>');
            $('#topic_id').html('<option value="">Select Topic</option>');

            if (admissionID) {
                $.ajax({
                    url: "{{ url('/admin/mcq/get-departments') }}/" + admissionID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#department_id').html('<option value="">Select Department</option>');
                        $.each(data, function(key, value) {
                            $('#department_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function() {
                        $('#department_id').html('<option value="">Failed to load</option>');
                    }
                });
            } else {
                $('#department_id').html('<option value="">Select Department</option>');
            }
        });

        // Load subjects when department changes
        $('#department_id').on('change', function() {
            var departmentID = $(this).val();
            $('#subject_id').html('<option value="">Loading...</option>');
            $('#topic_id').html('<option value="">Select Topic</option>');

            if (departmentID) {
                $.ajax({
                    url: "{{ url('/admin/mcq/get-subjects') }}/" + departmentID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#subject_id').html('<option value="">Select Subject</option>');
                        $.each(data, function(key, value) {
                            $('#subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function() {
                        $('#subject_id').html('<option value="">Failed to load</option>');
                    }
                });
            } else {
                $('#subject_id').html('<option value="">Select Subject</option>');
            }
        });

        // Load topics when subject changes
        $('#subject_id').on('change', function() {
            var subjectID = $(this).val();
            $('#topic_id').html('<option value="">Loading...</option>');

            if (subjectID) {
                $.ajax({
                    url: "{{ url('/admin/mcq/get-topics') }}/" + subjectID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#topic_id').html('<option value="">Select Topic</option>');
                        $.each(data, function(key, value) {
                            $('#topic_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function() {
                        $('#topic_id').html('<option value="">Failed to load</option>');
                    }
                });
            } else {
                $('#topic_id').html('<option value="">Select Topic</option>');
            }
        });

        // Add new question
        $('#add-question').click(function() {
            const container = $('#questions-container');
            const questionCount = container.children('.question-card').length;
            const newIndex = questionCount;
            
            const newQuestion = `
                <div class="card shadow-none border mb-3 question-card" data-question-index="${newIndex}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Question #${newIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-question">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Question Text <span class="text-danger">*</span></label>
                            <textarea name="questions[${newIndex}][text]" class="form-control" 
                                rows="3" placeholder="Enter question text" required></textarea>
                        </div>

                        <div class="mt-4">
                            <label>Options <span class="text-danger">*</span> <small class="text-muted">(Select the correct answer)</small></label>
                            <div class="row">
                                ${Array(4).fill().map((_, i) => `
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <input type="text" name="questions[${newIndex}][answers][${i}][text]" 
                                                class="form-control" 
                                                placeholder="Option ${i+1}" 
                                                required>
                                            <div class="input-group-text">
                                                <input type="radio" name="questions[${newIndex}][correct_answer]" 
                                                    value="${i}" 
                                                    class="form-check-input" style="cursor: pointer; margin-right:5px;">
                                                Correct
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.append(newQuestion);
        });

        // Remove question
        $(document).on('click', '.remove-question', function() {
            const card = $(this).closest('.question-card');
            const questionId = $(this).data('question-id');
            
            if (questionId) {
                if (confirm('Are you sure you want to delete this question? This action cannot be undone.')) {
                    // Send AJAX request to delete the question
                    $.ajax({
                        url: "{{ route('admin.mcq.delete-question') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            question_id: questionId
                        },
                        success: function(response) {
                            if (response.success) {
                                card.remove();
                                renumberQuestions();
                            } else {
                                alert('Failed to delete question: ' + response.message);
                            }
                        },
                        error: function() {
                            alert('An error occurred while trying to delete the question.');
                        }
                    });
                }
            } else {
                // For newly added questions (not saved yet)
                card.remove();
                renumberQuestions();
            }
        });

        // Renumber questions after adding/removing
        function renumberQuestions() {
            $('#questions-container .question-card').each(function(index) {
                $(this).attr('data-question-index', index);
                $(this).find('.card-header h5').text('Question #' + (index + 1));
                
                // Update all input names
                $(this).find('[name*="questions["]').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(/questions\[\d+\]/, `questions[${index}]`);
                    $(this).attr('name', newName);
                });
            });
        }
    });
</script>
@endpush