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
                        <a href="{{ route('admin.program.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> Program List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.program.update', $program->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-xl-6 col-lg-6 col-md-6">
                                <label for="name">Name: <span class="text-danger"></span></label>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="input-group">
                                    <span class="input-group-text" title="Name" id="basic-addon1"><i
                                            class="fas fa-users"></i></span>
                                    <input type="text" value="{{ $program->name }}" class=" form-control" name="name"
                                        placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6">
                                <label for="image">Photo <span
                                        class="text-danger font-weight-bolder">(Size:1200,500px)</span>:</label>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="input-group">
                                    <span class="input-group-text" title="Photo" id="basic-addon1"><i
                                            class="fas fa-photo-video"></i></span>
                                    <input type="file" name="image" id="image" class="form-control bg-white">
                                </div>
                            </div>

                            <div class="form-group col-xl-6 col-lg-6 col-md-6">
                                <img id="showImage"
                                    src="{{ !empty($program->image) ? url('upload/program/' . $program->image) : url('upload/no_image.jpg') }}"
                                    alt="Admin" style="width:100px; height: 100px;">
                            </div>

                            <div class="form-group col-xl-12 col-lg-12  col-md-6">
                                <label for="description">Description:</label>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <textarea name="description" id="description">{{ $program->description }}</textarea>
                            </div>

                            <!-- Subjects Section -->
                            <div class="form-group col-xl-12 mt-4">
                                <label>Program Subjects:</label>
                                <table class="table table-bordered" id="subjects_table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Subject</th>
                                            <th>Topic Name</th>
                                            <th>Total MCQ</th>
                                            <th>Time (mins)</th>
                                            <th>Exam Fee</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $rowIndex = 0; @endphp
                                        @forelse($program->topics ?? [] as $topic)
                                            <tr>
                                                <td>
                                                    <select name="subjects[{{ $rowIndex }}][program_subject_id]"
                                                        class="form-control">
                                                        <option value="">Select Subject</option>
                                                        @foreach ($subjects as $subject)
                                                            <option value="{{ $subject->id }}"
                                                                @if ($topic->program_subject_id == $subject->id) selected @endif>
                                                                {{ $subject->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" name="subjects[{{ $rowIndex }}][topic_name]"
                                                        class="form-control" value="{{ $topic->topic_name }}"></td>
                                                <td><input type="number" name="subjects[{{ $rowIndex }}][total_mcq]"
                                                        class="form-control" value="{{ $topic->total_mcq }}"></td>
                                                <td><input type="number" name="subjects[{{ $rowIndex }}][time]"
                                                        class="form-control" value="{{ $topic->time }}"></td>
                                                <td><input type="number" name="subjects[{{ $rowIndex }}][exam_fee]"
                                                        class="form-control" value="{{ $topic->exam_fee }}"></td>
                                                <td class="text-left">
                                                    @if ($rowIndex == 0)
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            id="addRow"><i class="fas fa-plus"></i></button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-success addRow"><i
                                                                class="fas fa-plus"></i></button>
                                                        <button type="button" class="btn btn-sm btn-danger removeRow"><i
                                                                class="fas fa-minus"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $rowIndex++; @endphp
                                        @empty
                                            <tr>
                                                <td>
                                                    <select name="subjects[0][program_subject_id]" class="form-control">
                                                        <option value="">Select Subject</option>
                                                        @foreach ($subjects as $subject)
                                                            <option value="{{ $subject->id }}">{{ $subject->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" name="subjects[0][topic_name]"
                                                        class="form-control"></td>
                                                <td><input type="number" name="subjects[0][total_mcq]"
                                                        class="form-control"></td>
                                                <td><input type="number" name="subjects[0][time]" class="form-control">
                                                </td>
                                                <td><input type="number" name="subjects[0][exam_fee]"
                                                        class="form-control"></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        id="addRow"><i class="fas fa-plus"></i></button>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group col-xl-12 col-lg-6 col-md-6">
                                <label for="status">Status:</label>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="input-group">
                                    <span class="input-group-text" title="Name" id="basic-addon1"><i
                                            class="fas fa-user-tie" title="Name"></i></span>
                                    <select name="status" class=" form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" @if ($program->status == 1) selected @endif>Active
                                        </option>
                                        <option value="0" @if ($program->status == 0) selected @endif>Deactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                                <button type="submit" class="add-to-cart btn btn-success btn-block"><i
                                        class="fas fa-paper-plane"></i> Update Program</button>
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
    <!-- Script for Dynamic Row -->
    <script>
        let rowIndex = {{ $rowIndex }};
        document.getElementById('addRow').addEventListener('click', function() {
            let tableBody = document.querySelector('#subjects_table tbody');
            let newRow = `
            <tr>
                <td>
                    <select name="subjects[${rowIndex}][program_subject_id]" class="form-control">
                        <option value="">Select Subject</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="subjects[${rowIndex}][topic_name]" class="form-control"></td>
                <td><input type="number" name="subjects[${rowIndex}][total_mcq]" class="form-control"></td>
                <td><input type="number" name="subjects[${rowIndex}][time]" class="form-control"></td>
                <td><input type="number" name="subjects[${rowIndex}][exam_fee]" class="form-control"></td>
                <td class="text-left">
                    <button type="button" class="btn btn-sm btn-success addRow"><i class="fas fa-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-danger removeRow"><i class="fas fa-minus"></i></button>
                </td>
            </tr>
        `;
            tableBody.insertAdjacentHTML('beforeend', newRow);
            rowIndex++;
        });

        // Dynamic delegation for add/remove buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.addRow')) {
                document.getElementById('addRow').click();
            }
            if (e.target.closest('.removeRow')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endpush
