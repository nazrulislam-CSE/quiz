@extends('layouts.admin.auth', ['pageTitle' => 'Reset Password'])
@section('auth-content')
    <div class="container vh-100">

        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Reset Password!</h1>
                                    </div>
                                    @if (count($errors) > 0)
                                        @foreach ($errors->all() as $error)
                                            <small class="text-danger">{{ $error }}</small>
                                        @endforeach
                                    @endif
                                    <form method="POST" action="{{ route('admin.reset.password.store') }}">
                                        @csrf

                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="form-group">
                                            <input id="email" type="email" class="form-control rounded-0 form-control-user py-4 @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input id="password" type="password" class="form-control rounded-0 form-control-user py-4 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Choose strong password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input id="password-confirm" type="password" class="form-control rounded-0 form-control-user py-4" name="password_confirmation" required autocomplete="new-password" placeholder="Re-type Password">
                                        </div>

                                        <button type="submit" class="btn btn-success btn-block">Reset Password</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('admin.forget.password') }}">Forgot Password?</a>
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
