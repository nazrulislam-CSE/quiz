@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container my-4">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    Change Your Password
                </div>
                <div class="card-body">
                    <!-- Flash Messages -->
                    @if(session()->has('flash_notification'))
                        {!! session('flash_notification')->toHtml() !!}
                    @endif

                    <form method="POST" action="{{ route('user.password.update') }}">
                        @csrf

                        <!-- Old Password -->
                        <div class="mb-3">
                            <label class="form-label">Old Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                            @error('old_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                            @error('new_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
