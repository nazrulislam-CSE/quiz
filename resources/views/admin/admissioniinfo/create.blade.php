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
                    <form action="{{ route('admin.admission.info.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="form-group col-xl-6 col-lg-6 col-md-6">
                                <label>প্রতিষ্ঠানের নাম</label>
                                <input type="text" name="institute_name" class="form-control"
                                    placeholder="প্রতিষ্ঠানের নাম লিখুন">
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6">
                                <label>সেশন</label>
                                <input type="text" name="session" class="form-control"
                                    placeholder="Example: 2025 - 2026">
                            </div>

                            <div class="form-group col-xl-6 col-lg-6 col-md-6">
                                <label>ফর্ম ছাড়ার তারিখ</label>
                                <input type="date" name="form_start_date" class="form-control">
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6">
                                <label>আবেদনের শেষ তারিখ</label>
                                <input type="date" name="application_last_date" class="form-control">
                            </div>

                            <div class="form-group col-xl-6 col-lg-6 col-md-6">
                                <label for="image">ছবি <span
                                        class="text-danger font-weight-bolder">(Size:570,569px)</span>:</label>
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
                                    src="{{ !empty($profile->image) ? url('upload/team/' . $profile->image) : url('upload/no_image.jpg') }}"
                                    alt="Admin" style="width:100px; height: 100px;">
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
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody id="unit-table-body">
                                <!-- Initial Unit Row -->
                            </tbody>
                        </table>

                        <!-- Add Button -->
                        <button type="button" id="add-unit" class="btn btn-success mb-3">
                            <i class="bi bi-plus-lg"></i> Add New Unit
                        </button>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary w-100">Submit</button>

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

    @push('admin')
<script>
    let unitIndex = 0;

    // Function to return a Unit Row with nested Subject Table
    function getUnitRow(index) {
        return `
<tr class="unit-block" data-unit-index="${index}">
    <tr>
        <td><input type="text" name="units[${index}][unit]" class="form-control" placeholder="ইউনিট"></td>
        <td><input type="text" name="units[${index}][description]" class="form-control" placeholder="ডেসক্রিপশন"></td>
        <td><input type="text" name="units[${index}][note]" class="form-control" placeholder="নোট"></td>
        <td><input type="date" name="units[${index}][exam_date]" class="form-control"></td>
        <td><input type="time" name="units[${index}][exam_time]" class="form-control"></td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm remove-unit">
                <i class="bi bi-x-lg"></i>
            </button>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <table class="table table-bordered subject-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Mark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="subject-body">
                    ${getSubjectRow(index, 0)}
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-success add-subject" data-unit-index="${index}">
                Add Subject
            </button>
        </td>
    </tr>
`;
    }

    // Function to return a Subject Row
    function getSubjectRow(unitIndex, subjectIndex) {
        return `
<tr>
    <td><input type="text" name="units[${unitIndex}][subjects][${subjectIndex}][subject]" class="form-control" placeholder="Subject Name"></td>
    <td><input type="number" name="units[${unitIndex}][subjects][${subjectIndex}][mark]" class="form-control" placeholder="Mark"></td>
    <td>
        <button type="button" class="btn btn-sm btn-danger remove-subject">Remove</button>
    </td>
</tr>`;
    }

    // Add Unit Button
    document.getElementById('add-unit').addEventListener('click', function () {
        const tbody = document.getElementById('unit-table-body');
        const unitRowHTML = getUnitRow(unitIndex);
        tbody.insertAdjacentHTML('beforeend', unitRowHTML);
        unitIndex++;
    });

    // Remove Unit
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-unit')) {
            const unitBlock = e.target.closest('tr').previousElementSibling?.classList?.contains('unit-block') 
                ? e.target.closest('tr').previousElementSibling 
                : null;
            if (unitBlock) {
                unitBlock.nextElementSibling.remove(); // remove subject table row
                unitBlock.remove(); // remove unit row
            } else {
                // fallback
                e.target.closest('tr').remove();
                e.target.closest('tr').nextElementSibling?.remove();
            }
        }
    });

    // Add Subject inside a unit
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-subject')) {
            const unitIndex = e.target.getAttribute('data-unit-index');
            const subjectTbody = e.target.previousElementSibling.querySelector('.subject-body');
            const subjectCount = subjectTbody.children.length;
            subjectTbody.insertAdjacentHTML('beforeend', getSubjectRow(unitIndex, subjectCount));
        }
    });

    // Remove subject
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-subject')) {
            e.target.closest('tr').remove();
        }
    });

    // Load first unit by default
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('add-unit').click();
    });
</script>
@endpush

@endpush
