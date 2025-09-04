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
        {{-- <div class="d-flex my-auto">
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
    </div> --}}
    </div>

    <div class="main-content-body">
        <div class="row row-sm">

            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <p class="card-title my-0">{{ $pageTitle ?? 'Page Title' }}</p>
                    <div class="d-flex">
                        <a href="{{ route('admin.balance.request.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> Balance Request List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.balance.request.update', $request->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-xl-12 col-lg-6 col-md-6">
                                <label for="status">Status:</label>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    <select name="status" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-paper-plane"></i> Update Balance Request
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
