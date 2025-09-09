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
                        <a href="{{ route('admin.user.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> User List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">পূর্ণ নাম</label>
                                <input type="text" name="full_name" class="form-control"
                                    value="{{ old('full_name', $user->full_name) }}" required>
                                @error('full_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ইউজারনেম</label>
                                <input type="text" name="username" class="form-control"
                                    value="{{ old('username', $user->username) }}" required>
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">মোবাইল</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ইমেইল</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">নতুন পাসওয়ার্ড (Optional)</label>
                                <input type="password" name="password" class="form-control">
                                <small class="text-muted">খালি রাখলে আগের পাসওয়ার্ডই থাকবে।</small>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">স্ট্যাটাস</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Disable</option>
                                </select>
                            </div>

                            <!-- Profile Image -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">প্রোফাইল ছবি</label>
                                <input type="file" name="image" class="form-control" id="imageInput">

                                <!-- Preview Image -->
                                <div class="mt-2">
                                    <a id="imageLink" href="{{ $user->image ? url('upload/user/' . $user->image) : '#' }}" target="_blank">
                                        <img id="previewImage" 
                                            src="{{ $user->image ? url('upload/user/' . $user->image) : 'https://via.placeholder.com/80x80?text=No+Image' }}" 
                                            alt="Profile Image" 
                                            width="80" 
                                            class="img-thumbnail">
                                    </a>
                                </div>

                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <!-- Submit -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('admin')
<!-- Script for Live Preview -->
<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function(e) {
            // Change preview image
            document.getElementById('previewImage').src = e.target.result;
            // Change anchor link
            document.getElementById('imageLink').href = e.target.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    });
</script>
@endpush
