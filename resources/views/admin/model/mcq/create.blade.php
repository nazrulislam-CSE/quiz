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
                        <a href="{{ route('admin.model.mcq.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> MCQ List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.model.mcq.store') }}" method="post" enctype="multipart/form-data">
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

                            {{-- select group --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="group_id">Group: <span class="text-danger">*</span></label>
                                @error('group_id') <span class="text-danger">{{ $message }}</span> @enderror
                                <select name="group_id" id="group_id" class="form-control">
                                    <option value="">Select Group</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- select model test --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="model_test_id">Model Test: <span class="text-danger">*</span></label>
                                @error('model_test_id') <span class="text-danger">{{ $message }}</span> @enderror
                                <select name="model_test_id" id="model_test_id" class="form-control">
                                    <option value="">Select Model Test</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach
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
                $('#group_id').html('<option value="">Loading...</option>');
                $('#model_test_id').html('<option value="">Loading...</option>');
                if (admissionID) {
                    $.ajax({
                        url: "{{ url('/admin/model/mcq/get-departments') }}/" + admissionID,
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
                    $('#group_id').html('<option value="">Select Department</option>');
                    $('#model_test_id').html('<option value="">Select Department</option>');
                }
            });
            // Load groups when department changes
            $('#department_id').on('change', function () {
                var departmentID = $(this).val();
                $('#group_id').html('<option value="">Loading...</option>');
                $('#model_test_id').html('<option value="">Loading...</option>');
                if (departmentID) {
                    $.ajax({
                        url: "{{ url('/admin/model/mcq/get-groups') }}/" + departmentID,
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
                    $('#model_test_id').html('<option value="">Select Model Test</option>');
                }
            });
            // Load model tests when group changes
            $('#group_id').on('change', function () {
                var groupID = $(this).val();
                $('#model_test_id').html('<option value="">Loading...</option>');
                if (groupID) {
                    $.ajax({
                        url: "{{ url('/admin/model/mcq/get-model-tests') }}/" + groupID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#model_test_id').html('<option value="">Select Model Test</option>');
                            $.each(data, function (key, value) {
                                $('#model_test_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#model_test_id').html('<option value="">Select Model Test</option>');
                }
            });
        });
    </script>
@endpush