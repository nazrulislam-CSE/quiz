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
            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}}</p>
            <div class="d-flex">
                <a href="{{ route('admin.topic.index')}}" class="btn btn-danger me-2">
                    <i class="fas fa-list d-inline"></i> Paper Final List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.paper.update',$paper->id)}}" method="post" enctype="multipart/form-data">
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

                    {{-- select group --}}
                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                        <label for="group_id">Group: <span class="text-danger">*</span></label>
                        @error('group_id') <span class="text-danger">{{ $message }}</span> @enderror
                        <select name="group_id" id="group_id" class="form-control">
                            <option value="">Select Group</option>
                        </select>
                    </div>

                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                     <div class="form-group">
                       <label for="name">Exam Duration/Time (In Minutes): <span class="text-danger">*</span></label>
                        <input type="number" min="0" name="exam_duration" value="{{ $paper->exam_duration }}" id="exam_duration" class="form-control" placeholder="Ex:10 minutes">
                        @error('exam_duration')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                    </div>

                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                        <div class="form-group">
                          <label for="exam_mark">Exam Mark: <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="exam_mark" value="{{ $paper->exam_mark }}" id="exam_mark" class="form-control" placeholder="Ex:25">
                            @error('exam_mark')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-xl-4 col-lg-4 col-md-4">
                        <div class="form-group">
                          <label for="fee">Exam Fee (Use Only Amount): <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="fee" value="{{ $paper->fee }}" id="fee" class="form-control" placeholder="Ex:100">
                            @error('fee')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                     <div class="form-group">
                       <label for="name">Paper Final Name: <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ $paper->name }}" id="name" class="form-control" placeholder="Enter Paper Final Name">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                    </div>

                    <div class="form-group col-xl-5 col-lg-5 col-md-5">
                        <label for="image">ICON <span class="text-danger font-weight-bolder">(Size:128,50px)</span>:</label>
                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Photo" id="basic-addon1"><i class="fas fa-photo-video"></i></span>
                            <input type="file" name="image" id="image" class="form-control bg-white">
                        </div>
                    </div>

                   <div class="form-group col-xl-1 col-lg-1 col-md-1">
                       <img id="showImage" src="{{ (!empty($paper->image)) ? url('upload/paper/'.$paper->image):url('upload/mcq.png') }}" alt="No ICON" style="width:100px; height: 100px;"  >
                   </div>
  
                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                        <button type="submit" class="add-to-cart btn btn-success btn-block"><i class="fas fa-paper-plane"></i> Update Paper Final</button>
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
        $(document).ready(function(){
            $('#image').change(function(e){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#showImage').attr('src',e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
        /* ============== Summernote Added ============ */
        jQuery(function(e){
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
        $(document).ready(function(){

            let oldAdmission = "{{ $paper->subject->department->admission_id ?? '' }}";
            let oldDepartment = "{{ $paper->subject->department_id ?? '' }}";
            let oldSubject = "{{ $paper->subject_id ?? '' }}";
            let oldGroup = "{{ $paper->group_id ?? '' }}";

            // Set admission value
            $('#admission_id').val(oldAdmission);

            // Load departments if editing
            if (oldAdmission) {
                loadDepartments(oldAdmission, oldDepartment);
            }

            // Load subjects if editing
            if (oldDepartment) {
                loadSubjects(oldDepartment, oldSubject);
            }

            // Load groups if editing
            if (oldDepartment) {
                loadGroups(oldDepartment, oldGroup);
            }

            // On admission change
            $('#admission_id').on('change', function(){
                let admissionID = $(this).val();
                $('#department_id').html('<option value="">Loading...</option>');
                $('#subject_id').html('<option value="">Select Subject</option>');
                if (admissionID) {
                    loadDepartments(admissionID, null);
                }
            });

            // On department change
            $('#department_id').on('change', function(){
                let departmentID = $(this).val();
                $('#subject_id').html('<option value="">Loading...</option>');
                if (departmentID) {
                    loadSubjects(departmentID, null);
                }
            });

            // On department change for groups
            $('#department_id').on('change', function(){
                let departmentID = $(this).val();
                $('#group_id').html('<option value="">Loading...</option>');
                if (departmentID) {
                    loadGroups(departmentID, null);
                } else {
                    $('#group_id').html('<option value="">Select Group</option>');
                }
            });

            function loadDepartments(admissionID, selectedDept){
                $.ajax({
                    url: "{{ url('/admin/paper/get-departments') }}/" + admissionID,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        $('#department_id').html('<option value="">Select Department</option>');
                        $.each(data, function(key, value){
                            let selected = (selectedDept == value.id) ? 'selected' : '';
                            $('#department_id').append('<option value="'+value.id+'" '+selected+'>'+value.name+'</option>');
                        });
                    }
                });
            }

            function loadGroups(departmentID, selectedGroup){
                $.ajax({
                    url: "{{ url('/admin/paper/get-groups') }}/" + departmentID,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        $('#group_id').html('<option value="">Select Group</option>');
                        $.each(data, function(key, value){
                            let selected = (selectedGroup == value.id) ? 'selected' : '';
                            $('#group_id').append('<option value="'+value.id+'" '+selected+'>'+value.name+'</option>');
                        });
                    }
                });
            }

            function loadSubjects(departmentID, selectedSubject){
                $.ajax({
                    url: "{{ url('/admin/paper/get-subjects') }}/" + departmentID,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        $('#subject_id').html('<option value="">Select Subject</option>');
                        $.each(data, function(key, value){
                            let selected = (selectedSubject == value.id) ? 'selected' : '';
                            $('#subject_id').append('<option value="'+value.id+'" '+selected+'>'+value.name+'</option>');
                        });
                    }
                });
            }

        });
    </script>
@endpush