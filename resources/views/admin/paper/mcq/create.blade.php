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
                    <a href="{{ route('admin.paper.index') }}" class="btn btn-danger me-2">
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
                        <button class="nav-link" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel-import"
                            type="button" role="tab">
                            <i class="fas fa-file-excel"></i> Excel Import
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Manual Entry Tab -->
                    <div class="tab-pane fade show active" id="manual-entry" role="tabpanel">
                        <form action="{{ route('admin.paper.mcq.store') }}" method="post" enctype="multipart/form-data">
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

                                {{-- select group --}}
                                <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                    <label for="group_id">Group: <span class="text-danger">*</span></label>
                                    @error('group_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    <select name="group_id" id="group_id" class="form-control">
                                        <option value="">Select Group</option>
                                        @if(old('group_id'))
                                            @foreach($groups ?? [] as $group)
                                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
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

                                {{-- select paper --}}
                                <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                    <label for="paper_final_id">Paper: <span class="text-danger">*</span></label>
                                    @error('paper_final_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    <select name="paper_final_id" id="paper_final_id" class="form-control">
                                        <option value="">Select Paper</option>
                                        @if(old('paper_final_id'))
                                            @foreach($papers ?? [] as $paper)
                                                <option value="{{ $paper->id }}" {{ old('paper_final_id') == $paper->id ? 'selected' : '' }}>{{ $paper->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                {{-- Number of Questions --}}
                                <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                    <label for="total_questions">How many questions?</label>
                                    <input type="number" id="total_questions" class="form-control" min="1" max="50"
                                        value="{{ old('total_questions') }}" placeholder="Enter number of questions">
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
                                                    <input type="text"
                                                        name="questions[{{ $qIndex }}][answers][{{ $i }}][answer]"
                                                        class="form-control" placeholder="Option {{ $i+1 }}"
                                                        value="{{ $qData['answers'][$i]['answer'] ?? '' }}" required>
                                                    <div class="input-group-text">
                                                        <input type="radio" name="questions[{{ $qIndex }}][correct_answer]"
                                                            value="{{ $i }}" {{ old("questions.$qIndex.correct_answer")==$i
                                                            ? 'checked' : '' }} style="cursor: pointer; margin-right:5px;">
                                                        Correct
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
                        <form action="{{ route('admin.paper.mcq.import') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- select admission --}}
                                <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                    <label for="import_admission_id">Admission: <span class="text-danger">*</span></label>
                                    @error('admission_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    <select name="admission_id" id="import_admission_id" class="form-control" required>
                                        <option value="">Select Admission</option>
                                        @foreach ($admissions as $admission)
                                        <option value="{{ $admission->id }}">{{ $admission->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- select department --}}
                                <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                    <label for="import_department_id">Department: <span class="text-danger">*</span></label>
                                    @error('department_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    <select name="department_id" id="import_department_id" class="form-control" required>
                                        <option value="">Select Department</option>
                                    </select>
                                </div>

                                {{-- select group --}}
                                <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                    <label for="import_group_id">Group: <span class="text-danger">*</span></label>
                                    @error('group_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    <select name="group_id" id="import_group_id" class="form-control" required>
                                        <option value="">Select Group</option>
                                    </select>
                                </div>

                                {{-- select subject --}}
                                <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                    <label for="import_subject_id">Subject: <span class="text-danger">*</span></label>
                                    @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    <select name="subject_id" id="import_subject_id" class="form-control" required>
                                        <option value="">Select Subject</option>
                                    </select>
                                </div>

                                {{-- select paper --}}
                                <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                    <label for="import_paper_final_id">Paper: <span class="text-danger">*</span></label>
                                    @error('paper_final_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    <select name="paper_final_id" id="import_paper_final_id" class="form-control" required>
                                        <option value="">Select Paper</option>
                                    </select>
                                </div>

                                {{-- Excel file upload --}}
                                <div class="form-group col-xl-8 col-lg-8 col-md-8">
                                    <label for="excel_file">Upload Excel File: <span class="text-danger">*</span></label>
                                    @error('excel_file') <span class="text-danger">{{ $message }}</span> @enderror
                                    <input type="file" name="excel_file" id="excel_file" class="form-control"
                                        accept=".xlsx,.xls,.csv" required>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        Excel format: Question, Option 1, Option 2, Option 3, Option 4, Correct Answer (0-3)
                                        <br>
                                        <strong>Note:</strong> Correct Answer uses 0-based indexing (0=Option 1, 1=Option 2, 2=Option 3, 3=Option 4)
                                        <br>
                                        <a href="{{ route('admin.paper.mcq.download-sample') }}"
                                            class="btn btn-sm btn-info mt-2">
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
    $(document).ready(function () {
        // ============ Manual Entry Cascading Dropdowns ============
        // Load departments when admission changes
        $('#admission_id').on('change', function () {
            var admissionID = $(this).val();
            $('#department_id').html('<option value="">Loading...</option>');
            $('#group_id').html('<option value="">Loading...</option>');
            $('#subject_id').html('<option value="">Loading...</option>');
            $('#paper_final_id').html('<option value="">Loading...</option>');

            if (admissionID) {
                $.ajax({
                    url: "{{ url('/admin/paper/mcq/get-departments') }}/" + admissionID,
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
                $('#group_id').html('<option value="">Select Group</option>');
                $('#subject_id').html('<option value="">Select Subject</option>');
                $('#paper_final_id').html('<option value="">Select Paper</option>');
            }
        });

        // Load groups when department changes
        $('#department_id').on('change', function () {
            var departmentID = $(this).val();
            $('#group_id').html('<option value="">Loading...</option>');
            $('#subject_id').html('<option value="">Loading...</option>');
            $('#paper_final_id').html('<option value="">Loading...</option>');
            if (departmentID) {
                $.ajax({
                    url: "{{ url('/admin/paper/mcq/get-groups') }}/" + departmentID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#group_id').html('<option value="">Select Group</option>');
                        $.each(data, function (key, value) {
                            $('#group_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#group_id').html('<option value="">Select Group</option>');
                $('#subject_id').html('<option value="">Select Subject</option>');
                $('#paper_final_id').html('<option value="">Select Paper</option>');
            }
        });

        // Load subjects when group changes
        $('#group_id').on('change', function () {
            var groupID = $(this).val();
            $('#subject_id').html('<option value="">Loading...</option>');
            $('#paper_final_id').html('<option value="">Loading...</option>');
            if (groupID) {
                $.ajax({
                    url: "{{ url('/admin/paper/mcq/get-subjects') }}/" + groupID,
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
                $('#paper_final_id').html('<option value="">Select Paper</option>');
            }
        });

        // Load papers when subject changes
        $('#subject_id').on('change', function () {
            var subjectID = $(this).val();
            $('#paper_final_id').html('<option value="">Loading...</option>');
            if (subjectID) {
                $.ajax({
                    url: "{{ url('/admin/paper/mcq/get-papers') }}/" + subjectID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#paper_final_id').html('<option value="">Select Paper</option>');
                        $.each(data, function (key, value) {
                            $('#paper_final_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#paper_final_id').html('<option value="">Select Paper</option>');
            }
        });

        // ============ Excel Import Cascading Dropdowns ============
        // Load departments when admission changes
        $('#import_admission_id').on('change', function () {
            var admissionID = $(this).val();
            $('#import_department_id').html('<option value="">Loading...</option>');
            $('#import_group_id').html('<option value="">Loading...</option>');
            $('#import_subject_id').html('<option value="">Loading...</option>');
            $('#import_paper_final_id').html('<option value="">Loading...</option>');

            if (admissionID) {
                $.ajax({
                    url: "{{ url('/admin/paper/mcq/get-departments') }}/" + admissionID,
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
                $('#import_group_id').html('<option value="">Select Group</option>');
                $('#import_subject_id').html('<option value="">Select Subject</option>');
                $('#import_paper_final_id').html('<option value="">Select Paper</option>');
            }
        });

        // Load groups when department changes
        $('#import_department_id').on('change', function () {
            var departmentID = $(this).val();
            $('#import_group_id').html('<option value="">Loading...</option>');
            $('#import_subject_id').html('<option value="">Loading...</option>');
            $('#import_paper_final_id').html('<option value="">Loading...</option>');
            if (departmentID) {
                $.ajax({
                    url: "{{ url('/admin/paper/mcq/get-groups') }}/" + departmentID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#import_group_id').html('<option value="">Select Group</option>');
                        $.each(data, function (key, value) {
                            $('#import_group_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#import_group_id').html('<option value="">Select Group</option>');
                $('#import_subject_id').html('<option value="">Select Subject</option>');
                $('#import_paper_final_id').html('<option value="">Select Paper</option>');
            }
        });

        // Load subjects when group changes
        $('#import_group_id').on('change', function () {
            var groupID = $(this).val();
            $('#import_subject_id').html('<option value="">Loading...</option>');
            $('#import_paper_final_id').html('<option value="">Loading...</option>');
            if (groupID) {
                $.ajax({
                    url: "{{ url('/admin/paper/mcq/get-subjects') }}/" + groupID,
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
                $('#import_paper_final_id').html('<option value="">Select Paper</option>');
            }
        });

        // Load papers when subject changes
        $('#import_subject_id').on('change', function () {
            var subjectID = $(this).val();
            $('#import_paper_final_id').html('<option value="">Loading...</option>');
            if (subjectID) {
                $.ajax({
                    url: "{{ url('/admin/paper/mcq/get-papers') }}/" + subjectID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#import_paper_final_id').html('<option value="">Select Paper</option>');
                        $.each(data, function (key, value) {
                            $('#import_paper_final_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#import_paper_final_id').html('<option value="">Select Paper</option>');
            }
        });
    });

    /* ============== Dynamic Question Generation ============ */
    document.getElementById('total_questions').addEventListener('input', function () {
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
                        ${[0, 1, 2, 3].map(i => `
                            <div class="col-md-6">
                                <div class="input-group mb-2 option-item">
                                    <input type="text" name="questions[${q}][answers][${i}][answer]" 
                                        class="form-control" placeholder="Option ${i + 1}" required>
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
</script>
@endpush