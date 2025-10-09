@extends('layouts.admin.app', [$pageTitle => 'Page Title'])

@section('content')
 <!-- Content Header (Page header) -->
 <div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        {{-- <h4 class="content-title mb-2">Hi, welcome back!</h4> --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Page Title' }}</li>
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

 <!-- Main content -->
 <div class="card card-primary card-outline shadow-lg mb-4">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <p class="card-title my-0">{{ $info->institute_name }} এর বিস্তারিত তথ্য</p>
        <a href="{{ route('admin.admission.info.index') }}" class="btn btn-danger btn-sm">
          <i class="fas fa-list d-inline"></i> Back to List
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="20%">ইন্সটিটিউট নাম</th>
                <td>{{ $info->institute_name }}</td>
            </tr>
            <tr>
                <th>সেশন</th>
                <td>{{ $info->session }}</td>
            </tr>
            <tr>
                <th>ফরম ছাড়ার তারিখ</th>
                <td>{{ \Carbon\Carbon::parse($info->form_release_date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>আবেদনের শেষ তারিখ</th>
                <td>{{ \Carbon\Carbon::parse($info->application_deadline)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>ছবি</th>
                <td>
                    <img src="{{ !empty($info->image) ? url($info->image) : url('upload/no_image.jpg') }}" 
                         alt="image" width="120" class="img-thumbnail">
                </td>
            </tr>
            <tr>
                <th>স্ট্যাটাস</th>
                <td>
                    @if ($info->status == 1)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
            </tr>
        </table>

        <hr>
        <h5>ইউনিট তথ্য</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>নং</th>
                        <th>ইউনিট</th>
                        <th>ডেসক্রিপশন</th>
                        <th>নোট</th>
                        <th>পরীক্ষার তারিখ</th>
                        <th>পরীক্ষার সময়</th>
                        <th>সাবজেক্টসমূহ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($info->units as $key => $unit)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $unit->unit }}</td>
                            <td>{{ $unit->description }}</td>
                            <td>{{ $unit->note }}</td>
                            <td>{{ \Carbon\Carbon::parse($unit->exam_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($unit->exam_time)->format('h:i A') }}</td>
                            <td>
                                @if($unit->subjects && $unit->subjects->count())
                                    <ul class="list-unstyled mb-0">
                                        @foreach($unit->subjects as $subject)
                                            <li>
                                                <strong>{{ $subject->subject }}</strong> — {{ $subject->mark }} মার্কস
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">কোন সাবজেক্ট নেই</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">কোন ইউনিট তথ্য পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
