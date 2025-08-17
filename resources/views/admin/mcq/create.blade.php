@extends('layouts.admin.app', [$pageTitle => 'Page Title'])
@section('content')
    <div class="breadcrumb-header justify-content-between">
        <div class="d-flex align-items-center">
            {{-- <h4 class="content-title mb-2">Hi, welcome back!</h4> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-auto">
            {{-- <div class=" d-flex right-page">
            <div class="d-flex justify-content-center me-5">
                <div class="">
                    <span class="d-block">
                        <span class="label ">EXPENSES</span>
                    </span>
                    <span class="value">
                        $53,000
                    </span>
                </div>
                <div class="ms-3 mt-2">
                    <span class="sparkline_bar"></span>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="">
                    <span class="d-block">
                        <span class="label">PROFIT</span>
                    </span>
                    <span class="value">
                        $34,000
                    </span>
                </div>
                <div class="ms-3 mt-2">
                    <span class="sparkline_bar31"></span>
                </div>
            </div>
        </div> --}}
        </div>
    </div>

    <div class="main-content-body">
        <div class="row row-sm">

            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <p class="card-title my-0">{{ $pageTitle ?? 'Page Title' }}</p>
                    <div class="d-flex">
                        <a href="{{ route('admin.mcq.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> MCQ List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mcq.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- select admission --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="admission_id">Admission: <span class="text-danger">*</span></label>
                                @error('admission_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                                @error('department_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                </select>
                            </div>

                            {{-- select subject --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="subject_id">Subject: <span class="text-danger">*</span></label>
                                @error('subject_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <select name="subject_id" id="subject_id" class="form-control">
                                    <option value="">Select Subject</option>
                                </select>
                            </div>

                            {{-- select topic --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="topic_id">Topic: <span class="text-danger">*</span></label>
                                @error('topic_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <select name="topic_id" id="topic_id" class="form-control">
                                    <option value="">Select Topic</option>
                                </select>
                            </div>

                            {{-- Number of Questions --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="total_questions">How many questions?</label>
                                <input type="number" id="total_questions" class="form-control" min="1" max="50">
                            </div>

                            {{-- Questions will be generated here --}}
                            <div id="questions-wrapper" class="col-12 mt-3"></div>

                            <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                                <button type="submit" class="add-to-cart btn btn-success btn-block"><i
                                        class="fas fa-plus"></i> Add MCQ</button>
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
        /* ============== Team Photo ============ */
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
        /* ============== Summernote Added ============ */
        jQuery(function(e) {
            'use strict';
            $(document).ready(function() {
                $('#description').summernote({
                    placeholder: 'Please some content here'
                });
            });
        });
        /* ============== Summernote Added ============ */
    </script>
    <script>
        $(document).ready(function() {
            // Load departments when admission changes
            $('#admission_id').on('change', function() {
                var admissionID = $(this).val();
                $('#department_id').html('<option value="">Loading...</option>');
                $('#subject_id').html('<option value="">Select Subject</option>');

                if (admissionID) {
                    $.ajax({
                        url: "{{ url('/admin/mcq/get-departments') }}/" + admissionID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#department_id').html(
                                '<option value="">Select Department</option>');
                            $.each(data, function(key, value) {
                                $('#department_id').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#department_id').html('<option value="">Select Department</option>');
                    $('#subject_id').html('<option value="">Select Subject</option>');
                }
            });

            // Load subjects when department changes
            $('#department_id').on('change', function() {
                var departmentID = $(this).val();
                $('#subject_id').html('<option value="">Loading...</option>');

                if (departmentID) {
                    $.ajax({
                        url: "{{ url('/admin/mcq/get-subjects') }}/" + departmentID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#subject_id').html('<option value="">Select Subject</option>');
                            $.each(data, function(key, value) {
                                $('#subject_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
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
                                $('#topic_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#topic_id').html('<option value="">Select Topic</option>');
                }
            });

        });
    </script>
    <script>
        document.getElementById('total_questions').addEventListener('input', function () {
            let total = parseInt(this.value) || 0;
            let wrapper = document.getElementById('questions-wrapper');
            wrapper.innerHTML = ""; // clear old questions

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
                                            <input type="radio" name="questions[${q}][correct_answer]" value="${i}"  style="cursor: pointer; margin-right:5px;"> Correct
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
    </script>

@endpush
