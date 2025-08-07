@extends('layouts.admin.auth')

@section('content')
    <div class="col-md-10 px-0 shadow d-flex border-radius-20 border border-white overflow-hidden bg-white">
        <div class="wd-md-70p login d-none d-md-flex page-signin-style p-0 text-white border-radius-20 overflow-hidden">
            <div class="my-auto authentication-pages">
                <img src="{{ asset(get_setting('site_company_logo')->value ?? 'dashboard/img/template.png') }}" class="w-100 h-100" alt="logo">
            </div>
        </div>
        <div class="sign-up-body wd-md-30p d-flex justify-content-center align-items-center bg-white">
            <div>
                <div class="main-signin-header">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <img width="200" src="{{ asset(get_setting('site_logo')->value ?? '/frontend/img/logos/logo.png')}}" class="mb-4" alt="logo">
                        <h2 class="text-center">{{ __('Admin Login') }}</h2>
                    </div>
                    <h4 class="text-center">{{ __('Please sign in to continue') }}</h4>
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <label>{{ __('Email or Username') }}</label>
                                <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login', 'admin') }}" required autocomplete="login" autofocus placeholder="Enter email login or username">
                                @error('login')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="*******">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check my-0">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block mt-0">{{ __('Sign In') }}</button>
                    </form>
                </div>
                <div class="main-signin-footer mt-3 mg-t-5">
                    <p>
                        {{-- @if (Route::has('password.request'))
                            <a href="{{ route('admin.forget.password') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif --}}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // ajax setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // ajax setup
        $("#username").on("keyup", function(event) {
            var username = $("#username").val();
            var password = $("#inputChoosePassword").val();
            var url = '{{-- route('check.admin.password') --}}';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                type: "POST",
                url: url,
                data: {
                    username: username,
                    password: password,
                },
                success: function(data) {
                    if (data == 1) {
                        $('#passwordMatchedOrNot').text('Password Matched!');
                        $('#passwordMatchedOrNot').addClass('text-success');
                        $('#passwordMatchedOrNot').removeClass('text-danger');
                        $("#submit_button").removeAttr('disabled', 'disabled');
                        $("#userFalse").addClass('d-none');
                        $("#userTrue").removeClass('d-none');
                    } else {
                        $('#passwordMatchedOrNot').text('Please check password or username!');
                        $("#submit_button").attr('disabled', 'disabled');
                        $('#passwordMatchedOrNot').addClass('text-danger');
                        $('#passwordMatchedOrNot').removeClass('text-success');
                        $("#userFalse").removeClass('d-none');
                        $("#userTrue").addClass('d-none');
                    }
                }
            });
        });
        $("#inputChoosePassword").on("keyup", function(event) {
            var username = $("#username").val();
            var password = $("#inputChoosePassword").val();
            var url = '{{-- route('check.admin.password') --}}';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                type: "POST",
                url: url,
                data: {
                    username: username,
                    password: password,
                },
                success: function(data) {
                    if (data == 1) {
                        $('#passwordMatchedOrNot').text('Password Matched!');
                        $('#passwordMatchedOrNot').addClass('text-success');
                        $('#passwordMatchedOrNot').removeClass('text-danger');
                        $("#submit_button").removeAttr('disabled', 'disabled');
                        $("#userFalse").addClass('d-none');
                        $("#userTrue").removeClass('d-none');
                    } else {
                        $('#passwordMatchedOrNot').text('Please check password or username!');
                        $("#submit_button").attr('disabled', 'disabled');
                        $('#passwordMatchedOrNot').addClass('text-danger');
                        $('#passwordMatchedOrNot').removeClass('text-success');
                        $("#userFalse").removeClass('d-none');
                        $("#userTrue").addClass('d-none');
                    }
                }
            });
        });
    </script>
@endpush
