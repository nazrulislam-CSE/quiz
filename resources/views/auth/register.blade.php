@extends('layouts.user.auth')
<style>
    .border-radius-20 {
        border-radius: 20px;
    }
    .box-color{
        background: #833AB4;
        background: linear-gradient(90deg, rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 50%, rgba(252, 176, 69, 1) 100%);
    }
   .box-color form label,
    .box-color form input {
        color: white;
    }

</style>
@section('content')
<div class="col-md-10 px-0 shadow d-flex border-radius-20 border border-white overflow-hidden bg-white">
    <div class="wd-md-50p login d-none d-md-flex page-signin-style p-0 text-white border-radius-20 overflow-hidden">
        <div class="my-auto authentication-pages">
            <img src="{{ asset(get_setting('site_company_logo')->value ?? 'dashboard/img/template.png') }}" class="w-100 h-100" alt="logo">
        </div>
    </div>
    <div class="p-lg-5 p-3 wd-md-70p row justify-content-center align-items-center box-color">
        <div>
            <div class="main-signin-header">
                <a href="{{ route('frontend.home')}}">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <img width="200" src="{{ asset(get_setting('site_logo')->value ?? '/frontend/img/logos/logo.png')}}" class="mb-4" alt="logo">
                        <h3 class="text-center text-light  animate-charcter">Welcome To {{ get_setting('site_name')->value ?? 'null'}}</h3>
                    </div>
                </a>
               
                <h4 class="text-center text-light">{{ __('Please sign up to continue') }}</h4>
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bolder">{{ __('Company Name') }}</label>
                                <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required autofocus placeholder="Enter Company Name">
                                @error('company_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bolder">{{ __('Company Owener Name') }}</label>
                                <input id="owner_name" type="text" class="form-control @error('owner_name') is-invalid @enderror" name="owner_name" value="{{ old('owner_name') }}" required autofocus placeholder="Enter Company Owner Name">
                                @error('owner_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Username') }}</label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required placeholder="Enter username">
                                @error('username')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bolder">{{ __('Email') }}</label>
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Enter email">
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bolder">{{ __('Phone') }}</label>
                                <input id="phone" type="number" min="0" step="any" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Enter phone">
                                @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bolder">{{ __('City Name') }}</label>
                                <input id="city_name" type="text" class="form-control @error('city_name') is-invalid @enderror" name="city_name" value="{{ old('city_name') }}" required autofocus placeholder="Enter City Name">
                                @error('city_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bolder">{{ __('Established Year') }}</label>
                                <input id="established_year" type="number" min="0" class="form-control @error('established_year') is-invalid @enderror" name="established_year" value="{{ old('established_year') }}" required autofocus placeholder="Enter Established Year">
                                @error('established_year')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bolder">{{ __('Nid Number') }}</label>
                                <input id="nid_number" type="text" class="form-control @error('nid_number') is-invalid @enderror" name="nid_number" value="{{ old('nid_number') }}" required autofocus placeholder="Enter Nid Number">
                                @error('nid_number')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bolder">{{ __('Designation') }}</label>
                                <input id="designation" type="text" class="form-control @error('designation') is-invalid @enderror" name="designation" value="{{ old('designation') }}" required autofocus placeholder="Enter Designation">
                                @error('designation')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Choose strong password">
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Re type password">
                                @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block">{{ __('Sign In') }}</button>
                </form>
            </div>
            <div class="main-signin-footer mt-3 mg-t-5">
                <p class="text-light font-weight-bolder">{{ __('Already have an account?') }} <a class="text-light font-weight-bolder" href="{{ route('login') }}">{{ __('Signin to account!') }}</a></p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#userType').change(function () {
            var userType = $(this).val();

            // Hide all fields initially
            $('.agentFields, .userFields').hide();

            // Show fields based on the selected type
            if (userType === '2') {
                $('.agentFields').show();
            } else if (userType === '1') {
                $('.userFields').show();
            }
        });
    });
</script>
@endsection