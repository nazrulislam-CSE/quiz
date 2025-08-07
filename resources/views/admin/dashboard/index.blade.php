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
                                    <h4>{{ __('Total Study Abroad') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($studys??'0')}}</strong></span>
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
                                    <h4>{{ __('Total Education') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($educations??'0')}}</strong></span>
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
                                    <h4>{{ __('Total Tourist Visa') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($tourist_visas??'0')}}</strong></span>
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
                                    <h4>{{ __('Total Work Permit Visa') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($permit_visas??'0')}}</strong></span>
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
                    <div class="card  overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/deposit.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Page') }}</h4> 
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($pages??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/total-expense.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Team') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($teams??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Menu Builder') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($menuitems??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/due.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Menus') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($menus??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card  overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/deposit.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Sections') }}</h4> 
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($sections??'0')}}</strong></span>
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
                                    <img src="{{ asset('dashboard/img/icons/total-expense.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Partners') }}</h4>
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
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Testimonials') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($testimonials??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Settings') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($settings??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Slider') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($sliders ??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total About') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($abouts ?? '0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Total Pertners') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($pertners??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Online Applications') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">{{ count($ielts??'0')}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Student Visa Commission') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">৳{{ $student_visa_commission??'0'}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Work Permit Visa Commission') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">৳{{ $work_visa_commission??'0'}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Medical Visa Commission') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">৳{{ $medical_visa_commission??'0'}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Tourist Visa Commission') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">৳{{ $tourist_visa_commission??'0'}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Software Sale Commission') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">৳{{ $software_visa_commission??'0'}}</strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <a href="javascript:;" class="text-dark">
                    <div class="card overflow-hidden project-card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="my-auto">
                                    <img src="{{ asset('dashboard/img/icons/calendar.png') }}" alt="" class="me-5 ht-70 wd-70 my-auto border shadow-sm rounded-lg p-2 bg-light">
                                </div>
                                <div class="project-content d-grid align-items-center">
                                    <h4>{{ __('Advance Payment') }}</h4>
                                    <ul>
                                        <li>
                                            <strong class="d-inline-flex mb-0" style="font-size: 15px !important;">Total:</strong>
                                            <span><strong style="font-size: 15px !important;">৳{{ $advance_payment??'0'}}</strong></span>
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
