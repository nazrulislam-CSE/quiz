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
        <div class=" d-flex right-page">
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
        </div>
    </div>
</div>
 <!-- Main content -->
 <!-- Main content -->
 <div class="row row-sm">
    <!-- Col -->
    <div class="col-lg-12">
        <div class="card card-info shadow-lg">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}}</p>
                <div class="d-flex">
                    <a href="/admin/dashboard" class="btn btn-danger me-2">
                        <i class="fas fa-list d-inline"></i> Back To Home
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.password.update')}}" method="post">
                    @csrf
                    <label for="exampleInputEmail1">Old Password</label>
                    @error('old_password') <span class="text-danger">{{ $message }}</span> @enderror
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input name="old_password" type="password" value="{{ old('old_password')}}" class="form-control old_pass_id" placeholder="Enter Old Password">
                            <span class="input-group-text" style="cursor: pointer;"><i class="fas fa-eye old_password"></i></span>
                    </div>

                    <label for="exampleInputEmail1">New Password</label>
                    @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input name="new_password" type="password" value="{{ old('new_password')}}" class="form-control new_pass_id" placeholder="Enter New Password">
                            <span class="input-group-text" style="cursor: pointer;"><i class="fas fa-eye new_password"></i></span>
                    </div>

                    <label for="exampleInputEmail1">Confirm New Password</label>
                    @error('new_password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                    <div class="input-group-append mb-4">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input name="new_password_confirmation" type="password" value="{{ old('new_password_confirmation')}}" name="email" class="form-control new_confirm_pass_id" placeholder="Enter Confirm New Password">
                        <span class="input-group-text" style="cursor: pointer;"><i class="fas fa-eye new_confirm_password"></i></span>
                    </div>

                    <div class="input-group mb-1">
                        <div class="btn-group w-100">
                        </i><button type="submit" class="btn btn-success col fileinput-button dz-clickable"> <i class="fas fa-plus"></i> Update Password</button>
                    </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
            </div>
        <!-- /.card -->
    </div>
    <!-- /Col -->
</div>
 <!-- /.content -->

 @push('admin')
    <script>
        /* ======> old password show <====== */
        $(document).on('click', '.old_password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $(".old_pass_id");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });

        /* ======> New password show <====== */
        $(document).on('click', '.new_password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $(".new_pass_id");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });

        /* ======> New Confirm password show <====== */
        $(document).on('click', '.new_confirm_password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $(".new_confirm_pass_id");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });
</script>

 @endpush
@endsection
