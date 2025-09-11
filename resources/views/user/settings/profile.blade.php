@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container my-4">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="row">
        <!-- Left Side Profile Info -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img id="previewImage" 
                         src="{{ (!empty($profile->image)) ? url('upload/user_images/'.$profile->image) : url('upload/user.png') }}" 
                         class="rounded-circle mb-3" width="120" height="120" alt="Profile Photo">
                    <h5 class="mb-0">{{ $profile->name }}</h5>
                    <small class="text-muted">{{ $profile->email }}</small>
                </div>
            </div>
        </div>

        <!-- Right Side Update Form -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    Update Profile
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $profile->full_name) }}" class="form-control"  placeholder="Enter full name" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $profile->email) }}" class="form-control"  placeholder="Enter email" required>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" class="form-control" placeholder="Enter phone" required>
                        </div>

                        <!-- Present Address -->
                        <div class="mb-3">
                            <label class="form-label">Present Address</label>
                            <textarea name="present_address" class="form-control" rows="3" placeholder="Enter present address" required>{{ old('present_address', $profile->present_address) }}</textarea>
                        </div>

                        <!-- Photo -->
                        <div class="mb-3">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="photo" class="form-control" onchange="previewFile(this)">
                        </div>

                        <button type="submit" class="btn btn-success">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
function previewFile(input) {
    let file = input.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById("previewImage").setAttribute("src", event.target.result);
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
