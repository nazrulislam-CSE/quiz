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
        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <p class="card-title my-0">{{ $pageTitle ?? 'Page Title' }} <span class="badge bg-danger side-badge"
                                style="font-size:17px;">{{ count($infos) }}</span> </p>

                        <div class="d-flex">
                            <a href="{{ route('admin.admission.info.create') }}" class="btn btn-success me-2">
                                <i class="fas fa-plus d-inline"></i> Add Now Admission Info
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="file-datatable"
                                class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">নং</th>
                                        <th class="border-bottom-0">প্রতিষ্ঠানের নাম</th>
                                        <th class="border-bottom-0">ছবি</th>
                                        <th class="border-bottom-0">এডমিশন তথ্য</th>
                                        <th class="border-bottom-0">অনন্যা তথ্য</th>
                                        <th class="border-bottom-0">স্টেটাস</th>
                                        <th class="border-bottom-0">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($infos as $key => $info)
                                        <tr>
                                            <td class="col-1">{{ $key + 1 }}</td>
                                            <td class="col-1">{{ $info->institute_name }}</td>
                                            <td>
                                                <img src="{{ !empty($info->image) ? url($info->image) : url('upload/no_image.jpg') }}"
                                                    width="50" alt="image" class="img-fluid">
                                            </td>
                                            <td>
                                                সেশন: {{ $info->session }} <br>
                                                ফরম ছাড়ার তারিখ:
                                                {{ \Carbon\Carbon::parse($info->form_release_date)->format('d/m/Y') }} <br>
                                                আবেদনের শেষ তারিখ:
                                                {{ \Carbon\Carbon::parse($info->application_deadline)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @if ($info->units->count() > 0)
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead>
                                                            <tr class="table-light">
                                                                <th>ইউনিট</th>
                                                                <th>বিবরণ</th>
                                                                <th>পরীক্ষার তারিখ</th>
                                                                <th>সময়</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($info->units as $unit)
                                                                <tr>
                                                                    <td>{{ $unit->unit }}</td>
                                                                    <td>{{ $unit->description ?? '-' }}</td>
                                                                    <td>{{ $unit->exam_date ? \Carbon\Carbon::parse($unit->exam_date)->format('d/m/Y') : '-' }}
                                                                    </td>
                                                                    <td>{{ $unit->exam_time ? \Carbon\Carbon::parse($unit->exam_time)->format('h:i A') : '-' }}
                                                                    </td>
                                                                    <td>{{ $unit->mark ?? '' }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <span class="text-muted">No Unit Info</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($info->status == 1)
                                                    <a href="#" class="badge bg-pill bg-success">Active</a>
                                                @else
                                                    <a href="#" class="badge bg-pill bg-danger">Disable</a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.admission.info.show', $info->id) }}"
                                                    class="btn btn-success btn-sm mr-2"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.admission.info.edit', $info->id) }}"
                                                    class="btn btn-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.admission.info.delete', $info->id) }}"
                                                    class="btn btn-danger btn-sm" title="Delete Data" id="delete"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
