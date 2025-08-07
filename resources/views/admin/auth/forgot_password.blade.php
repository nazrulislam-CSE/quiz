@extends('layouts.admin.auth', ['pageTitle' => 'Forgot Password'])
<style>
    .border-radius-20 {
        border-radius: 20px;
    }
</style>
@section('content')

<div class="col-md-10 px-0 shadow d-flex align-items-center border-radius-20 border border-white overflow-hidden bg-white">
    <div class="wd-md-70p login d-none d-md-flex page-signin-style p-0 text-white border-radius-20 overflow-hidden">
        <div class="my-auto authentication-pages">
            <img src="{{ asset('dashboard/img/bhisab_banner.png') }}" class="w-100 h-100" alt="logo">
        </div>
    </div>
    <div class="p-5 wd-md-50p">
        <div class="main-signin-header">
            <h2>{{ __('Forgot Password!') }}</h2>
            <h4>{{ __('Please Enter Your Email') }}</h4>
            <form method="POST" action="{{ route('admin.forget.password.store') }}">
                @csrf
                <div class="row">
                    <div class="form-group mb-1">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Confirmation Email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-3">{{ __('Send Password Reset Link') }}</button>
            </form>
        </div>
        <div class="main-signup-footer mg-t-10">
            <p>Forget it, <a href="{{ route('admin.login.view') }}"> Send me back</a> to the sign in screen.</p>
        </div>
    </div>
</div>
@endsection