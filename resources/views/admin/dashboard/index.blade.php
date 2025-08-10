@extends('layouts.admin.app', ['pageTitle' => 'Dashboard'])

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
        {{-- <div class=" d-flex right-page">
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
        </div> --}}
    </div>
</div>
    <div class="main-content-body">
        <div class="row row-sm">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark" onclick="comingSoon();">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/total-sales.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Admission') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">0</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark" onclick="comingSoon();">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/due.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Department') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">0</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark" onclick="comingSoon();">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Subject') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">0</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark" onclick="comingSoon();">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Topics') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">0</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark" onclick="comingSoon();">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total MCQ') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">0</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
