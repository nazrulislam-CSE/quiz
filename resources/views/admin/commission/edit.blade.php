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
                        <a href="{{ route('admin.commission.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> Commission List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.commission.update', $commission->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-xl-12 col-lg-6 col-md-6">
                                <label for="refer1">Direct Refer: <span class="text-danger"></span></label>
                                @error('refer1')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="input-group">
                                    <span class="input-group-text" title="Direct Refer" id="basic-addon1"><i
                                            class="fas fa-users"></i></span>
                                    <input type="number" min="0" value="{{ $commission->refer1 }}"
                                        class=" form-control" name="refer1" placeholder="Direct Refer">
                                </div>
                            </div>
                            <div class="form-group col-xl-12 col-lg-6 col-md-6">
                                <label for="refer2">1st Generation: <span class="text-danger"></span></label>
                                @error('refer2')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="input-group">
                                    <span class="input-group-text" title="Direct Refer" id="basic-addon1"><i
                                            class="fas fa-users"></i></span>
                                    <input type="number" min="0" value="{{ $commission->refer2 }}"
                                        class=" form-control" name="refer2" placeholder="1st Generation">
                                </div>
                            </div>
                            <div class="form-group col-xl-12 col-lg-6 col-md-6">
                                <label for="refer3">2nd Generation: <span class="text-danger"></span></label>
                                @error('refer3')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="input-group">
                                    <span class="input-group-text" title="Direct Refer" id="basic-addon1"><i
                                            class="fas fa-users"></i></span>
                                    <input type="number" min="0" value="{{ $commission->refer3 }}"
                                        class=" form-control" name="refer3" placeholder="2nd Generation">
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                                <button type="submit" class="add-to-cart btn btn-success btn-block"><i
                                        class="fas fa-paper-plane"></i> Update Commission</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
