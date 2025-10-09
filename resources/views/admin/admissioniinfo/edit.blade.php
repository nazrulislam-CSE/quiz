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
                        <a href="{{ route('admin.admission.info.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> Admission Info List
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.admission.info.update', $info->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>প্রতিষ্ঠানের নাম</label>
                                <input type="text" name="institute_name" class="form-control"
                                    value="{{ old('institute_name', $info->institute_name) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>সেশন</label>
                                <input type="text" name="session" class="form-control"
                                    value="{{ old('session', $info->session) }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label>ফর্ম ছাড়ার তারিখ</label>
                                <input type="date" name="form_start_date" class="form-control"
                                    value="{{ old('form_start_date', $info->form_start_date) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>আবেদনের শেষ তারিখ</label>
                                <input type="date" name="application_last_date" class="form-control"
                                    value="{{ old('application_last_date', $info->application_last_date) }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="image">ছবি <span
                                        class="text-danger font-weight-bolder">(Size:570,569px)</span>:</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <img src="{{ !empty($info->image) ? url($info->image) : url('upload/no_image.jpg') }}"
                                    style="width:100px; height:100px;" class="img-thumbnail">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">স্ট্যাটাস</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ $info->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $info->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                        </div>

                        <hr>
                        <h5>ইউনিট তথ্য</h5>
                        <table class="table table-bordered" id="unit-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>ইউনিট</th>
                                    <th>ডেসক্রিপশন</th>
                                    <th>নোট</th>
                                    <th>পরীক্ষার তারিখ</th>
                                    <th>পরীক্ষার সময়</th>
                                    <th>মার্ক</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($info->units as $key => $unit)
                                    <tr>
                                        <td><input type="text" name="units[{{ $key }}][unit]"
                                                class="form-control" value="{{ $unit->unit }}"></td>
                                        <td><input type="text" name="units[{{ $key }}][description]"
                                                class="form-control" value="{{ $unit->description }}"></td>
                                        <td><input type="text" name="units[{{ $key }}][note]"
                                                class="form-control" value="{{ $unit->note }}"></td>
                                        <td><input type="date" name="units[{{ $key }}][exam_date]"
                                                class="form-control" value="{{ $unit->exam_date }}"></td>
                                        <td><input type="time" name="units[{{ $key }}][exam_time]"
                                                class="form-control" value="{{ $unit->exam_time }}"></td>
                                        <td><input type="text" name="units[{{ $key }}][mark]"
                                                class="form-control" value="{{ $unit->mark }}"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-unit">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="button" id="add-unit" class="btn btn-success mb-3">
                            <i class="bi bi-plus-lg"></i> Add New
                        </button>

                        <button type="submit" class="btn btn-primary w-100">Update</button>
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
        let unitIndex = 1;
        document.getElementById('add-unit').addEventListener('click', function() {
            let tableBody = document.querySelector('#unit-table tbody');
            let newRow = `
        <tr>
            <td><input type="text" name="units[${unitIndex}][unit]" class="form-control" placeholder="ইউনিট"></td>
            <td><input type="text" name="units[${unitIndex}][description]" class="form-control" placeholder="ডেসক্রিপশন"></td>
            <td><input type="text" name="units[${unitIndex}][note]" class="form-control" placeholder="নোট"></td>
            <td><input type="date" name="units[${unitIndex}][exam_date]" class="form-control"></td>
            <td><input type="time" name="units[${unitIndex}][exam_time]" class="form-control"></td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm remove-unit">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);
            unitIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-unit')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endpush
