@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')

{{-- ====== Breadcrumb ====== --}}
<div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            </ol>
        </nav>
    </div>
</div>

{{-- ============================================================
     FILTER SECTION
============================================================ --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.course.index') }}" id="courseFilterForm">
            <div class="row g-2 align-items-end">

                {{-- Course Type --}}
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Course Type</label>
                    <select name="course_type_id" id="filter_course_type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach ($courseTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ request('course_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Course Class (dependent) --}}
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Course Class</label>
                    <select name="course_class_id" id="filter_course_class" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        @if (request('course_class_id'))
                            @php
                                $selectedClass = $courseClasses->firstWhere('id', request('course_class_id'));
                            @endphp
                            @if ($selectedClass)
                                <option value="{{ $selectedClass->id }}" selected>
                                    {{ $selectedClass->name }}
                                </option>
                            @endif
                        @endif
                    </select>
                </div>

                {{-- Course Category (dependent) --}}
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Course Category</label>
                    <select name="course_category_id" id="filter_course_category" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @if (request('course_category_id'))
                            @php
                                $selectedCategory = $courseCategories->firstWhere('id', request('course_category_id'));
                            @endphp
                            @if ($selectedCategory)
                                <option value="{{ $selectedCategory->id }}" selected>
                                    {{ $selectedCategory->name }}
                                </option>
                            @endif
                        @endif
                    </select>
                </div>

                {{-- Status --}}
                <div class="col-md-3 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           value="{{ request('search') }}" placeholder="Course title...">
                </div>

                {{-- Buttons --}}
                <div class="col-md-6 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.course.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ====== Main Body ====== --}}
<div class="main-content-body">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <p class="card-title my-0">
                        {{ $pageTitle }}
                        <span class="badge bg-danger side-badge" style="font-size:17px;">
                            {{ $courses->count() }}
                        </span>
                    </p>
                    <div class="d-flex">
                        <button type="button" class="btn btn-success me-2"
                                data-bs-toggle="modal" data-bs-target="#addCourseModal">
                            <i class="fas fa-plus d-inline"></i> Add New Course
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file-datatable"
                               class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">SL</th>
                                    <th class="border-bottom-0">Image</th>
                                    <th class="border-bottom-0">Title</th>
                                    <th class="border-bottom-0">Type</th>
                                    <th class="border-bottom-0">Class</th>
                                    <th class="border-bottom-0">Category</th>
                                    <th class="border-bottom-0">Price</th>
                                    <th class="border-bottom-0">Discount</th>
                                    <th class="border-bottom-0">Views</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($courses as $key => $course)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if ($course->thumbnail)
                                                <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                                     alt="{{ $course->title }}"
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                            @else
                                                <span class="badge bg-light text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $course->title }}
                                            @if ($course->is_featured)
                                                <span class="badge bg-warning text-dark">Featured</span>
                                            @endif
                                        </td>
                                        <td>{{ $course->courseType->name ?? 'N/A' }}</td>
                                        <td>{{ $course->courseClass->name ?? 'N/A' }}</td>
                                        <td>{{ $course->courseCategory->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($course->is_free)
                                                <span class="badge bg-info">Free</span>
                                            @else
                                                ৳ {{ number_format($course->price, 0) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($course->has_discount)
                                                <span class="text-success fw-bold">
                                                    ৳ {{ number_format($course->discount_price, 0) }}
                                                </span>
                                                <small class="text-muted">
                                                    ({{ $course->discount_percent }}% off)
                                                </small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-eye"></i> {{ $course->view_count }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($course->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Show --}}
                                            <button type="button" class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#showCourseModal-{{ $course->id }}"
                                                    title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            {{-- Edit --}}
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCourseModal-{{ $course->id }}"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- Delete --}}
                                            <a href="{{ route('admin.course.delete', $course->id) }}"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure to delete?')"
                                               title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">No Data Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     ADD MODAL (Bootstrap 5 + fade + image upload)
============================================================ --}}
<div class="modal fade" id="addCourseModal" tabindex="-1"
     aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.course.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        {{-- Course Type --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Course Type <span class="text-danger">*</span></label>
                            <select name="course_type_id" id="add_course_type" class="form-select" required>
                                <option value="">-- Select Type --</option>
                                @foreach ($courseTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('course_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_type_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Course Class --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Course Class <span class="text-danger">*</span></label>
                            <select name="course_class_id" id="add_course_class" class="form-select" required>
                                <option value="">-- Select Class --</option>
                            </select>
                        </div>

                        {{-- Course Category --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Course Category <span class="text-danger">*</span></label>
                            <select name="course_category_id" id="add_course_category" class="form-select" required>
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>

                        {{-- Title --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Course Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ old('title') }}"
                                   placeholder="e.g. HSC Bangla + English + ICT — 1st Year" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Short description (optional)">{{ old('description') }}</textarea>
                        </div>

                        {{-- Thumbnail --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Course Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*"
                                   onchange="previewAddImage(event)">
                            <small class="text-muted">Max 2MB • jpg, jpeg, png, webp</small>
                            @error('thumbnail')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                            <div class="mt-2">
                                <img id="addImagePreview" src="" alt="Preview"
                                     style="display:none; max-width: 180px; border-radius: 8px; border: 1px solid #e5e7eb;">
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Price (৳) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control"
                                   value="{{ old('price', 0) }}" min="0" step="1" required>
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Discount --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Discount Price (৳)</label>
                            <input type="number" name="discount_price" class="form-control"
                                   value="{{ old('discount_price') }}" min="0" step="1"
                                   placeholder="Leave empty if no discount">
                            @error('discount_price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Is Free --}}
                        <div class="col-md-4 mb-3 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_free"
                                       id="add_is_free" value="1" {{ old('is_free') ? 'checked' : '' }}>
                                <label class="form-check-label" for="add_is_free">Free Course</label>
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Duration</label>
                            <input type="text" name="duration" class="form-control"
                                   value="{{ old('duration') }}" placeholder="e.g. 3 months">
                        </div>

                        {{-- Total Class --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Class</label>
                            <input type="number" name="total_class" class="form-control"
                                   value="{{ old('total_class') }}" min="0" placeholder="e.g. 40">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active"   {{ old('status') === 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     EDIT MODALS (per row, image upload)
============================================================ --}}
@foreach ($courses as $course)
    <div class="modal fade" id="editCourseModal-{{ $course->id }}" tabindex="-1"
         aria-labelledby="editCourseModalLabel-{{ $course->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.course.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCourseModalLabel-{{ $course->id }}">
                            Edit Course
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            {{-- Course Type --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Course Type <span class="text-danger">*</span></label>
                                <select name="course_type_id" class="form-select edit-course-type"
                                        data-course-id="{{ $course->id }}" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach ($courseTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ $course->course_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Course Class --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Course Class <span class="text-danger">*</span></label>
                                <select name="course_class_id" class="form-select edit-course-class"
                                        id="edit_course_class_{{ $course->id }}" required>
                                    <option value="{{ $course->course_class_id }}">
                                        {{ $course->courseClass->name ?? '-- Select Class --' }}
                                    </option>
                                </select>
                            </div>

                            {{-- Course Category --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Course Category <span class="text-danger">*</span></label>
                                <select name="course_category_id" class="form-select edit-course-category"
                                        id="edit_course_category_{{ $course->id }}" required>
                                    <option value="{{ $course->course_category_id }}">
                                        {{ $course->courseCategory->name ?? '-- Select Category --' }}
                                    </option>
                                </select>
                            </div>

                            {{-- Title --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ $course->title }}" required>
                            </div>

                            {{-- Description --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                          rows="3">{{ $course->description }}</textarea>
                            </div>

                            {{-- Thumbnail --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Course Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*"
                                       onchange="previewEditImage(event, {{ $course->id }})">
                                <small class="text-muted">Max 2MB • jpg, jpeg, png, webp • Leave empty to keep current</small>
                                @error('thumbnail')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                                <div class="mt-2">
                                    @if ($course->thumbnail)
                                        <img id="editImagePreview_{{ $course->id }}"
                                             src="{{ asset('storage/' . $course->thumbnail) }}"
                                             alt="Current"
                                             style="max-width: 180px; border-radius: 8px; border: 1px solid #e5e7eb;">
                                    @else
                                        <img id="editImagePreview_{{ $course->id }}" src="" alt="Preview"
                                             style="display:none; max-width: 180px; border-radius: 8px; border: 1px solid #e5e7eb;">
                                    @endif
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control"
                                       value="{{ $course->price }}" min="0" step="1" required>
                            </div>

                            {{-- Discount --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Discount Price (৳)</label>
                                <input type="number" name="discount_price" class="form-control"
                                       value="{{ $course->discount_price }}" min="0" step="1">
                            </div>

                            {{-- Is Free --}}
                            <div class="col-md-4 mb-3 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_free"
                                           id="edit_is_free_{{ $course->id }}" value="1"
                                           {{ $course->is_free ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_is_free_{{ $course->id }}">
                                        Free Course
                                    </label>
                                </div>
                            </div>

                            {{-- Duration --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Duration</label>
                                <input type="text" name="duration" class="form-control"
                                       value="{{ $course->duration }}">
                            </div>

                            {{-- Total Class --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total Class</label>
                                <input type="number" name="total_class" class="form-control"
                                       value="{{ $course->total_class }}" min="0">
                            </div>

                            {{-- Status --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active"   {{ $course->status === 'active'   ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $course->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- ============================================================
     SHOW MODALS (per row, professional view + image)
============================================================ --}}
@foreach ($courses as $course)
    <div class="modal fade" id="showCourseModal-{{ $course->id }}" tabindex="-1"
         aria-labelledby="showCourseModalLabel-{{ $course->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">

                {{-- ===================== HERO HEADER ===================== --}}
                <div class="position-relative p-4 text-white"
                     style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);">
                    <button type="button" class="btn-close btn-close-white position-absolute"
                            style="top: 16px; right: 16px;" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width: 56px; height: 56px; background: rgba(255,255,255,.18);
                                    border-radius: 14px; backdrop-filter: blur(8px);">
                            <i class="fas fa-graduation-cap fs-3"></i>
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <span class="badge bg-light text-dark small fw-semibold">
                                    {{ $course->courseType->name ?? 'N/A' }}
                                </span>
                                @if ($course->is_featured)
                                    <span class="badge bg-warning text-dark small">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                @endif
                                @if ($course->is_free)
                                    <span class="badge bg-info small">Free</span>
                                @endif
                            </div>
                            <h5 class="mb-0 fw-bold">{{ $course->title }}</h5>
                        </div>
                    </div>

                    <div class="mt-3 d-flex align-items-center gap-2 small" style="opacity: .9;">
                        <i class="fas fa-layer-group"></i>
                        <span>{{ $course->courseClass->name ?? 'N/A' }}</span>
                        <i class="fas fa-chevron-right small"></i>
                        <span>{{ $course->courseCategory->name ?? 'N/A' }}</span>
                    </div>
                </div>

                {{-- ===================== BODY ===================== --}}
                <div class="modal-body p-4">

                    {{-- Thumbnail --}}
                    @if ($course->thumbnail)
                        <div class="mb-4 text-center">
                            <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                 alt="{{ $course->title }}"
                                 style="max-width: 100%; max-height: 280px; border-radius: 12px;
                                        box-shadow: 0 4px 12px rgba(0,0,0,.08);">
                        </div>
                    @endif

                    {{-- STATS CARDS --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 h-100 rounded-3" style="background: #f0f9ff; border: 1px solid #bae6fd;">
                                <div class="small text-muted mb-1">
                                    <i class="fas fa-tag text-primary"></i> Price
                                </div>
                                <div class="fs-5 fw-bold text-dark">
                                    @if ($course->is_free)
                                        <span class="text-info">Free</span>
                                    @else
                                        ৳{{ number_format($course->price, 0) }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 h-100 rounded-3" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                <div class="small text-muted mb-1">
                                    <i class="fas fa-percent text-success"></i> Discount
                                </div>
                                <div class="fs-5 fw-bold text-dark">
                                    @if ($course->has_discount)
                                        <span class="text-success">
                                            ৳{{ number_format($course->discount_price, 0) }}
                                        </span>
                                        <small class="text-muted fw-normal">
                                            ({{ $course->discount_percent }}%)
                                        </small>
                                    @else
                                        <span class="text-muted fw-normal fs-6">No discount</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 h-100 rounded-3"
                                 style="background: linear-gradient(135deg, #fef3c7, #fde68a);
                                        border: 1px solid #fcd34d;">
                                <div class="small text-muted mb-1">
                                    <i class="fas fa-bolt text-warning"></i> Final Price
                                </div>
                                <div class="fs-5 fw-bold text-dark">
                                    ৳{{ number_format($course->final_price, 0) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DETAILS GRID --}}
                    <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing: .5px;">
                        Course Information
                    </h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 rounded-2">
                                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 36px; height: 36px; background: #eef2ff; border-radius: 10px;">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Duration</div>
                                    <div class="fw-semibold">{{ $course->duration ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 rounded-2">
                                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 36px; height: 36px; background: #ecfeff; border-radius: 10px;">
                                    <i class="fas fa-book text-info"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Total Classes</div>
                                    <div class="fw-semibold">{{ $course->total_class ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 rounded-2">
                                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 36px; height: 36px; background: #fdf2f8; border-radius: 10px;">
                                    <i class="fas fa-eye text-danger"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Total Views</div>
                                    <div class="fw-semibold">{{ number_format($course->view_count) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 p-2 rounded-2">
                                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 36px; height: 36px; background: #f0fdf4; border-radius: 10px;">
                                    @if ($course->status === 'active')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="small text-muted">Status</div>
                                    <div class="fw-semibold">
                                        @if ($course->status === 'active')
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    @if ($course->description)
                        <h6 class="text-uppercase text-muted small fw-bold mb-2" style="letter-spacing: .5px;">
                            Description
                        </h6>
                        <div class="p-3 rounded-3 mb-3"
                             style="background: #f8fafc; border-left: 3px solid #6366f1;
                                    line-height: 1.6; color: #334155;">
                            {{ $course->description }}
                        </div>
                    @endif

                    {{-- TIMESTAMPS --}}
                    <div class="d-flex justify-content-between text-muted small pt-2 border-top">
                        <span>
                            <i class="fas fa-calendar-plus"></i>
                            Created: {{ $course->created_at?->format('d M Y, h:i A') }}
                        </span>
                        <span>
                            <i class="fas fa-clock"></i>
                            Updated: {{ $course->updated_at?->format('d M Y, h:i A') }}
                        </span>
                    </div>
                </div>

                {{-- ===================== FOOTER ===================== --}}
                <div class="modal-footer bg-light border-0 px-4 py-3">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>

            </div>
        </div>
    </div>
@endforeach

@endsection

{{-- ============================================================
     JS — Dependent dropdown + Image Preview
============================================================ --}}
@push('admin')
<script>
    /* ============ Image Preview (outside DOMContentLoaded) ============ */
    function previewAddImage(event) {
        const input = event.target;
        const preview = document.getElementById('addImagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    function previewEditImage(event, courseId) {
        const input = event.target;
        const preview = document.getElementById('editImagePreview_' + courseId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    /* ============ Dependent Dropdowns ============ */
    document.addEventListener('DOMContentLoaded', function () {

        const CLASS_URL    = "{{ url('admin/courses/get-classes') }}";
        const CATEGORY_URL = "{{ url('admin/courses/get-categories') }}";

        /* ---------- ADD MODAL ---------- */
        const addType     = document.getElementById('add_course_type');
        const addClass    = document.getElementById('add_course_class');
        const addCategory = document.getElementById('add_course_category');

        addType?.addEventListener('change', function () {
            const typeId = this.value;
            addClass.innerHTML    = '<option value="">Loading...</option>';
            addCategory.innerHTML = '<option value="">-- Select Category --</option>';

            if (!typeId) {
                addClass.innerHTML = '<option value="">-- Select Class --</option>';
                return;
            }

            fetch(`${CLASS_URL}/${typeId}`)
                .then(res => res.json())
                .then(data => {
                    addClass.innerHTML = '<option value="">-- Select Class --</option>';
                    data.forEach(item => {
                        addClass.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(() => {
                    addClass.innerHTML = '<option value="">Error loading</option>';
                });
        });

        addClass?.addEventListener('change', function () {
            const classId = this.value;
            addCategory.innerHTML = '<option value="">Loading...</option>';

            if (!classId) {
                addCategory.innerHTML = '<option value="">-- Select Category --</option>';
                return;
            }

            fetch(`${CATEGORY_URL}/${classId}`)
                .then(res => res.json())
                .then(data => {
                    addCategory.innerHTML = '<option value="">-- Select Category --</option>';
                    data.forEach(item => {
                        addCategory.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(() => {
                    addCategory.innerHTML = '<option value="">Error loading</option>';
                });
        });

        /* ---------- EDIT MODALS ---------- */
        document.querySelectorAll('.edit-course-type').forEach(function (typeSelect) {
            typeSelect.addEventListener('change', function () {
                const typeId   = this.value;
                const courseId = this.dataset.courseId;
                const classSelect    = document.getElementById('edit_course_class_' + courseId);
                const categorySelect = document.getElementById('edit_course_category_' + courseId);

                classSelect.innerHTML    = '<option value="">Loading...</option>';
                categorySelect.innerHTML = '<option value="">-- Select Category --</option>';

                if (!typeId) {
                    classSelect.innerHTML = '<option value="">-- Select Class --</option>';
                    return;
                }

                fetch(`${CLASS_URL}/${typeId}`)
                    .then(res => res.json())
                    .then(data => {
                        classSelect.innerHTML = '<option value="">-- Select Class --</option>';
                        data.forEach(item => {
                            classSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });
                    });
            });
        });

        document.querySelectorAll('.edit-course-class').forEach(function (classSelect) {
            classSelect.addEventListener('change', function () {
                const classId  = this.value;
                const courseId = this.id.replace('edit_course_class_', '');
                const categorySelect = document.getElementById('edit_course_category_' + courseId);

                categorySelect.innerHTML = '<option value="">Loading...</option>';

                if (!classId) {
                    categorySelect.innerHTML = '<option value="">-- Select Category --</option>';
                    return;
                }

                fetch(`${CATEGORY_URL}/${classId}`)
                    .then(res => res.json())
                    .then(data => {
                        categorySelect.innerHTML = '<option value="">-- Select Category --</option>';
                        data.forEach(item => {
                            categorySelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });
                    });
            });
        });

        /* ---------- FILTER ---------- */
        const filterType     = document.getElementById('filter_course_type');
        const filterClass    = document.getElementById('filter_course_class');
        const filterCategory = document.getElementById('filter_course_category');

        function loadFilterClasses(typeId, selectedClassId, selectedCategoryId) {
            if (!typeId) {
                filterClass.innerHTML    = '<option value="">All Classes</option>';
                filterCategory.innerHTML = '<option value="">All Categories</option>';
                return;
            }

            fetch(`${CLASS_URL}/${typeId}`)
                .then(res => res.json())
                .then(data => {
                    filterClass.innerHTML = '<option value="">All Classes</option>';
                    data.forEach(item => {
                        const selected = (selectedClassId && selectedClassId == item.id) ? 'selected' : '';
                        filterClass.innerHTML += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                    });

                    if (selectedClassId) {
                        loadFilterCategories(selectedClassId, selectedCategoryId);
                    }
                });
        }

        function loadFilterCategories(classId, selectedCategoryId) {
            if (!classId) {
                filterCategory.innerHTML = '<option value="">All Categories</option>';
                return;
            }

            fetch(`${CATEGORY_URL}/${classId}`)
                .then(res => res.json())
                .then(data => {
                    filterCategory.innerHTML = '<option value="">All Categories</option>';
                    data.forEach(item => {
                        const selected = (selectedCategoryId && selectedCategoryId == item.id) ? 'selected' : '';
                        filterCategory.innerHTML += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                    });
                });
        }

        const initialType     = "{{ request('course_type_id') }}";
        const initialClass    = "{{ request('course_class_id') }}";
        const initialCategory = "{{ request('course_category_id') }}";

        if (initialType) {
            loadFilterClasses(initialType, initialClass, initialCategory);
        }

        filterType?.addEventListener('change', function () {
            loadFilterClasses(this.value);
        });

        filterClass?.addEventListener('change', function () {
            loadFilterCategories(this.value);
        });

    });
</script>
@endpush