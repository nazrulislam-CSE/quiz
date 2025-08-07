@extends('layouts.admin.auth', ['pageTitle' => $pageTitle])
@section('content')
    <div class="container vh-100">
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <small class="text-danger">{{ $error }}</small>
            @endforeach
        @endif
        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Change Password!</h1>
                                    </div>
                                    <form class="user" action="{{ route('admin.password.change', Auth::guard('admin')->user()->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <input type="password" class="form-control rounded-0 form-control-user @error('current_password') is-invalid @enderror" placeholder="Enter Current Password" name="current_password" required autofocus>
                                            @error('current_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <small>{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control rounded-0 form-control-user" id="new_password" placeholder="New Password" name="new_password" value="{{ old('new_password') }}" required>
                                            @error('new_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <small>{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control rounded-0 form-control-user" id="retype_password" placeholder="Re-type Password" name="retype_password" value="{{ old('retype_password') }}" required>
                                            @error('retype_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <small>{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block">Change</button>
                                    </form>
                                    <div class="text-center mt-3">
                                        <a class="small" href="{{ route('admin.forget.password') }}" onclick="event.preventDefault(); document.getElementById('vendor-logout-form').submit();">Forgot Password?</a>
                                        <form id="vendor-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
