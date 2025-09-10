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
                                style="font-size:17px;">{{ count($users) }}</span> </p>
                    </div>
                    <div class="card-body">
                        <!-- 🔎 Search Form -->
                        <form action="{{ route('admin.user.index') }}" method="GET" id="filter-form" class="mb-3">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="username" value="{{ request('username') }}"
                                        class="form-control" placeholder="Search Username">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="phone" value="{{ request('phone') }}" class="form-control"
                                        placeholder="Search Phone">
                                </div>
                                <div class="col-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-danger w-50">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary w-50">
                                        <i class="fa fa-times"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                        <!-- End Search Form -->


                        <div class="table-responsive">
                            <table id="file-datatable"
                                class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                <thead class="bg-dark text-white text-center">
                                    <tr>
                                        <th>নং</th>
                                        <th>ইউজার তথ্য</th>
                                        <th>আইডি তথ্য</th>
                                        <th>সদস্য ছবি</th>
                                        <th>রেফারেন্স তথ্য</th>
                                        <th>ব্যক্তিগত ক্যাশ</th>
                                        <th>ইনকাম তথ্য</th>
                                        <th>স্ট্যাটাস তথ্য</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $key => $user)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>
                                                নাম: {{ $user->full_name ?? 'N/A' }} <br>
                                                আইডি: {{ $user->id }} <br>
                                                যোগদান: {{ $user->created_at->format('d-M-Y') }} <br>
                                                মোবাইল: {{ $user->phone ?? '---' }} <br>
                                                পাসওয়ার্ড: {{ $user->show_password ?? '---' }}
                                            </td>

                                            <td>
                                                ইউজারনেম: {{ $user->username ?? '---' }} <br>
                                                পাসওয়ার্ড: {{ $user->show_password ?? '---' }} <br>
                                                বিভাগ: {{ $user->division->name_bn ?? '---' }}
                                            </td>
                                            <td>
                                                <img src="{{ !empty($user->image) ? url('upload/user/' . $user->image) : url('upload/avater.png') }}"
                                                    width="60" class="img-thumbnail">
                                            </td>
                                            <td>
                                                নাম: {{ optional($user->refer)->full_name ?? '---' }} <br>
                                                আইডি: {{ $user->refer_by ?? '---' }}
                                            </td>
                                            <td>
                                                সচল ব্যালেন্স:  {{ number_format($user->main_wallet, 2) }} <br>
                                                উত্তোলন: {{ number_format($user->withdraw_wallet, 2) }} <br>
                                            </td>
                                            <td>
                                                উপার্জিত আয়: {{ number_format($user->income_wallet, 2) }}
                                            </td>
                                            <td>
                                                @if ($user->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Disable</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.user.show',$user->id)}}" class="btn btn-success btn-sm mr-2"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.user.edit',$user->id)}}" class="btn btn-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.user.delete',$user->id)}}" class="btn btn-danger btn-sm" title="Delete Data" id="delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-danger">No Users Found</td>
                                        </tr>
                                    @endforelse
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
