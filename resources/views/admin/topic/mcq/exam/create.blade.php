@extends('layouts.admin.app', [$pageTitle ?? 'MCQ' => 'Create MCQ'])

@section('content')
    <div class="breadcrumb-header justify-content-between">
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-auto"></div>
    </div>

    <div class="main-content-body">
        <div class="row row-sm">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <p class="card-title my-0">{{ $pageTitle ?? 'Create MCQ' }}</p>
                    <div class="d-flex">
                        <a href="{{ route('admin.topic.mcq.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> MCQ List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabs for Manual Entry and Excel Import -->
                    <ul class="nav nav-tabs mb-4" id="mcqTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" 
                                    data-bs-target="#manual-entry" type="button" role="tab">
                                <i class="fas fa-pencil-alt"></i> Manual Entry
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="excel-tab" data-bs-toggle="tab" 
                                    data-bs-target="#excel-import" type="button" role="tab">
                                <i class="fas fa-file-excel"></i> Excel Import
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Manual Entry Tab -->
                        <div class="tab-pane fade show active" id="manual-entry" role="tabpanel">
                            <form action="{{ route('admin.topic.mcq.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    {{-- select admission --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="admission_id">Admission: <span class="text-danger">*</span></label>
                                        @error('admission_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        <select name="admission_id" id="admission_id" class="form-control">
                                            <option value="">Select Admission</option>
                                            @foreach ($admissions as $admission)
                                                <option value="{{ $admission->id }}" {{ old('admission_id') == $admission->id ? 'selected' : '' }}>{{ $admission->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- select department --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="department_id">Department: <span class="text-danger">*</span></label>
                                        @error('department_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        <select name="department_id" id="department_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @if(old('department_id'))
                                                @foreach($departments ?? [] as $department)
                                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    {{-- select subject --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="subject_id">Subject: <span class="text-danger">*</span></label>
                                        @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        <select name="subject_id" id="subject_id" class="form-control">
                                            <option value="">Select Subject</option>
                                            @if(old('subject_id'))
                                                @foreach($subjects ?? [] as $subject)
                                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    
                                    {{-- select topic --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="topic_id">Topic: <span class="text-danger">*</span></label>
                                        @error('topic_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        <select name="topic_id" id="topic_id" class="form-control">
                                            <option value="">Select Topic</option>
                                            @if(old('topic_id'))
                                                @foreach($topics ?? [] as $topic)
                                                    <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    {{-- Number of Questions --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="total_questions">How many questions?</label>
                                        <input type="number" id="total_questions" class="form-control" min="1" max="50" value="{{ old('total_questions') }}" placeholder="Enter number of questions">
                                    </div>

                                    {{-- Questions will be generated here --}}
                                    <div id="questions-wrapper" class="col-12 mt-3">
                                        @if(old('questions'))
                                            @foreach(old('questions') as $qIndex => $qData)
                                                <div class="card mb-4 p-3 question-block">
                                                    <h5>Question {{ $qIndex + 1 }}</h5>
                                                    <div class="form-group mb-2">
                                                        <label>Question:</label>
                                                        <textarea name="questions[{{ $qIndex }}][text]" class="form-control" 
                                                                  placeholder="Enter question" required>{{ $qData['text'] }}</textarea>
                                                        @error("questions.$qIndex.text")
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="row">
                                                        @foreach([0,1,2,3] as $i)
                                                            <div class="col-md-6">
                                                                <div class="input-group mb-2 option-item">
                                                                    <input type="text" name="questions[{{ $qIndex }}][answers][{{ $i }}][answer]" 
                                                                           class="form-control" placeholder="Option {{ $i+1 }}" 
                                                                           value="{{ $qData['answers'][$i]['answer'] ?? '' }}" required>
                                                                    <div class="input-group-text">
                                                                        <input type="radio" name="questions[{{ $qIndex }}][correct_answer]" 
                                                                               value="{{ $i }}" {{ old("questions.$qIndex.correct_answer") == $i ? 'checked' : '' }}
                                                                               style="cursor: pointer; margin-right:5px;"> Correct
                                                                    </div>
                                                                </div>
                                                                @error("questions.$qIndex.answers.$i.answer")
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                                        <button type="submit" class="add-to-cart btn btn-success btn-block">
                                            <i class="fas fa-plus"></i> Add MCQ
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Excel Import Tab -->
                        <div class="tab-pane fade" id="excel-import" role="tabpanel">
                            <form action="{{ route('admin.topic.study.mcq.import') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    {{-- select admission --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="import_admission_id">Admission: <span class="text-danger">*</span></label>
                                        @error('import_admission_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        <select name="admission_id" id="import_admission_id" class="form-control" required>
                                            <option value="">Select Admission</option>
                                            @foreach ($admissions as $admission)
                                                <option value="{{ $admission->id }}" {{ old('admission_id') == $admission->id ? 'selected' : '' }}>{{ $admission->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- select department --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="import_department_id">Department: <span class="text-danger">*</span></label>
                                        @error('import_department_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        <select name="department_id" id="import_department_id" class="form-control" required>
                                            <option value="">Select Department</option>
                                            @if(old('department_id'))
                                                @foreach($departments ?? [] as $department)
                                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    {{-- select subject --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="import_subject_id">Subject: <span class="text-danger">*</span></label>
                                        @error('import_subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        <select name="subject_id" id="import_subject_id" class="form-control" required>
                                            <option value="">Select Subject</option>
                                            @if(old('subject_id'))
                                                @foreach($subjects ?? [] as $subject)
                                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    
                                    {{-- select topic --}}
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                        <label for="import_topic_id">Topic: <span class="text-danger">*</span></label>
                                        @error('import_topic_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        <select name="topic_id" id="import_topic_id" class="form-control" required>
                                            <option value="">Select Topic</option>
                                            @if(old('topic_id'))
                                                @foreach($topics ?? [] as $topic)
                                                    <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    {{-- Excel file upload --}}
                                    <div class="form-group col-xl-8 col-lg-8 col-md-8">
                                        <label for="excel_file">Upload Excel File: <span class="text-danger">*</span></label>
                                        @error('excel_file') <span class="text-danger">{{ $message }}</span> @enderror
                                        <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-info-circle"></i> 
                                            Excel format: Question, Option 1, Option 2, Option 3, Option 4, Correct Answer (0-3)
                                            <br>
                                            <strong>Note:</strong> Correct Answer uses 0-based indexing (0=Option 1, 1=Option 2, 2=Option 3, 3=Option 4)
                                            <br>
                                            <a href="{{ route('admin.topic.study.mcq.download-sample') }}" class="btn btn-sm btn-info mt-2">
                                                <i class="fas fa-download"></i> Download Sample Excel
                                            </a>
                                        </small>
                                    </div>

                                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-file-import"></i> Import MCQs
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('admin')
    <script>
        $(document).ready(function(){
            // ============ Manual Entry - Load departments ============
            $('#admission_id').on('change', function () {
                var admissionID = $(this).val();
                $('#department_id').html('<option value="">Loading...</option>');
                $('#subject_id').html('<option value="">Select Subject</option>');
                $('#topic_id').html('<option value="">Select Topic</option>');

                if (admissionID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/mcq/get-departments') }}/" + admissionID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#department_id').html('<option value="">Select Department</option>');
                            $.each(data, function (key, value) {
                                $('#department_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#department_id').html('<option value="">Select Department</option>');
                    $('#subject_id').html('<option value="">Select Subject</option>');
                    $('#topic_id').html('<option value="">Select Topic</option>');
                }
            });

            // ============ Manual Entry - Load subjects ============
            $('#department_id').on('change', function () {
                var departmentID = $(this).val();
                $('#subject_id').html('<option value="">Loading...</option>');
                $('#topic_id').html('<option value="">Select Topic</option>');

                if (departmentID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/mcq/get-subjects') }}/" + departmentID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#subject_id').html('<option value="">Select Subject</option>');
                            $.each(data, function (key, value) {
                                $('#subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subject_id').html('<option value="">Select Subject</option>');
                    $('#topic_id').html('<option value="">Select Topic</option>');
                }
            });

            // ============ Manual Entry - Load topics ============
            $('#subject_id').on('change', function () {
                var subjectID = $(this).val();
                $('#topic_id').html('<option value="">Loading...</option>');
                if (subjectID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/mcq/get-topics') }}/" + subjectID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#topic_id').html('<option value="">Select Topic</option>');
                            $.each(data, function (key, value) {
                                $('#topic_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#topic_id').html('<option value="">Select Topic</option>');
                }
            });

            // ============ Excel Import - Load departments ============
            $('#import_admission_id').on('change', function () {
                var admissionID = $(this).val();
                $('#import_department_id').html('<option value="">Loading...</option>');
                $('#import_subject_id').html('<option value="">Select Subject</option>');
                $('#import_topic_id').html('<option value="">Select Topic</option>');

                if (admissionID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/mcq/get-departments') }}/" + admissionID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#import_department_id').html('<option value="">Select Department</option>');
                            $.each(data, function (key, value) {
                                $('#import_department_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#import_department_id').html('<option value="">Select Department</option>');
                    $('#import_subject_id').html('<option value="">Select Subject</option>');
                    $('#import_topic_id').html('<option value="">Select Topic</option>');
                }
            });

            // ============ Excel Import - Load subjects ============
            $('#import_department_id').on('change', function () {
                var departmentID = $(this).val();
                $('#import_subject_id').html('<option value="">Loading...</option>');
                $('#import_topic_id').html('<option value="">Select Topic</option>');

                if (departmentID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/mcq/get-subjects') }}/" + departmentID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#import_subject_id').html('<option value="">Select Subject</option>');
                            $.each(data, function (key, value) {
                                $('#import_subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#import_subject_id').html('<option value="">Select Subject</option>');
                    $('#import_topic_id').html('<option value="">Select Topic</option>');
                }
            });

            // ============ Excel Import - Load topics ============
            $('#import_subject_id').on('change', function () {
                var subjectID = $(this).val();
                $('#import_topic_id').html('<option value="">Loading...</option>');
                if (subjectID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/mcq/get-topics') }}/" + subjectID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#import_topic_id').html('<option value="">Select Topic</option>');
                            $.each(data, function (key, value) {
                                $('#import_topic_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#import_topic_id').html('<option value="">Select Topic</option>');
                }
            });

            // ============ Dynamic Question Generation ============
            $('#total_questions').on('input', function() {
                let total = parseInt(this.value) || 0;
                let wrapper = document.getElementById('questions-wrapper');
                wrapper.innerHTML = ""; // clear old questions

                if (total > 50) {
                    this.value = 50;
                    total = 50;
                    alert('Maximum 50 questions allowed!');
                }

                for (let q = 0; q < total; q++) {
                    let block = `
                        <div class="card mb-4 p-3 question-block">
                            <h5>Question ${q + 1}</h5>
                            <div class="form-group mb-2">
                                <label>Question:</label>
                                <textarea name="questions[${q}][text]" class="form-control" placeholder="Enter question" required></textarea>
                            </div>
                            <div class="row">
                                ${[0,1,2,3].map(i => `
                                    <div class="col-md-6">
                                        <div class="input-group mb-2 option-item">
                                            <input type="text" name="questions[${q}][answers][${i}][answer]" 
                                                class="form-control" placeholder="Option ${i+1}" required>
                                            <div class="input-group-text">
                                                <input type="radio" name="questions[${q}][correct_answer]" value="${i}" 
                                                    style="cursor: pointer; margin-right:5px;" required> Correct
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                    wrapper.insertAdjacentHTML('beforeend', block);
                }
            });

            // Initialize questions if old values exist
            @if(old('questions') && count(old('questions')) > 0)
                document.getElementById('total_questions').value = {{ count(old('questions')) }};
                document.getElementById('total_questions').dispatchEvent(new Event('input'));
            @endif
        });
    </script>
@endpush