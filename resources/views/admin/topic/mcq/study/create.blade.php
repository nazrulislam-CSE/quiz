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
                        <a href="{{ route('admin.topic.study.mcq.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> MCQ List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.topic.study.mcq.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- select admission --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="admission_id">Admission: <span class="text-danger">*</span></label>
                                @error('admission_id') <span class="text-danger">{{ $message }}</span> @enderror
                                <select name="admission_id" id="admission_id" class="form-control">
                                    <option value="">Select Admission</option>
                                    @foreach ($admissions as $admission)
                                        <option value="{{ $admission->id }}">{{ $admission->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- select department --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="department_id">Department: <span class="text-danger">*</span></label>
                                @error('department_id') <span class="text-danger">{{ $message }}</span> @enderror
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                </select>
                            </div>

                            {{-- select subject --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="subject_id">Subject: <span class="text-danger">*</span></label>
                                @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                                <select name="subject_id" id="subject_id" class="form-control">
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                            
                            {{-- select topic --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="topic_id">Topic: <span class="text-danger">*</span></label>
                                @error('topic_id') <span class="text-danger">{{ $message }}</span> @enderror
                                <select name="topic_id" id="topic_id" class="form-control">
                                    <option value="">Select Topic</option>
                                </select>
                            </div>

                            {{-- Number of Questions --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="total_questions">How many questions?</label>
                                <input type="number" id="total_questions" class="form-control" min="1" max="50" value="{{ old('total_questions') }}" placeholder="Enter number of questions" required>
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
            </div>
        </div>
    </div>
@endsection

@push('admin')
    <script>
        $(document).ready(function(){
            // Load departments when admission changes
            $('#admission_id').on('change', function () {
                var admissionID = $(this).val();
                $('#department_id').html('<option value="">Loading...</option>');
                $('#subject_id').html('<option value="">Select Subject</option>');
                $('#topic_id').html('<option value="">Select Topic</option>');

                if (admissionID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/study/mcq/get-departments') }}/" + admissionID,
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

            // Load subjects when department changes
            $('#department_id').on('change', function () {
                var departmentID = $(this).val();
                $('#subject_id').html('<option value="">Loading...</option>');
                $('#topic_id').html('<option value="">Select Topic</option>');

                if (departmentID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/study/mcq/get-subjects') }}/" + departmentID,
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

            // Load topics when subject changes
            $('#subject_id').on('change', function () {
                var subjectID = $(this).val();
                $('#topic_id').html('<option value="">Loading...</option>');
                if (subjectID) {
                    $.ajax({
                        url: "{{ url('/admin/topic/study/mcq/get-topics') }}/" + subjectID,
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

        });
    </script>
@endpush