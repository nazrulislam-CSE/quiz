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
        
    </div>
</div>
    <div class="main-content-body">
        <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}} <span class="badge bg-danger side-badge" style="font-size:17px;">{{ count($requests) }}</span> </p>

                            <div class="d-flex">
                                <a href="{{ route('admin.admission.create')}}" class="btn btn-success me-2">
                                    <i class="fas fa-plus d-inline"></i> Add Now Admission
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Filter buttons and date form --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                               <div class="btn-group" role="group" aria-label="status-filter">

    <a href="{{ route('admin.balance.request.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
       class="btn btn-sm {{ ($status === 'pending') ? 'btn-warning text-white' : 'btn-outline-warning' }}">
       Pending
    </a>

    <a href="{{ route('admin.balance.request.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}"
       class="btn btn-sm {{ ($status === 'approved') ? 'btn-success text-white' : 'btn-outline-success' }}">
       Approved
    </a>

    <a href="{{ route('admin.balance.request.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}"
       class="btn btn-sm {{ ($status === 'rejected') ? 'btn-danger text-white' : 'btn-outline-danger' }}">
       Rejected
    </a>
</div>


                                <form method="GET" class="form-inline">
                                    @if(!empty($status))
                                        <input type="hidden" name="status" value="{{ $status }}">
                                    @endif

                                    <div class="input-group input-group-sm">
                                        <input type="date" name="date" class="form-control form-control-sm" value="{{ $date ?? '' }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                            <a href="{{ route('admin.balance.request.index') }}" class="btn btn-sm btn-light">Clear</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- Table --}}
                            <div class="table-responsive">
                                <table id="file-datatable" class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">নং</th>
                                            <th class="border-bottom-0">তারিখ</th>
                                            <th class="border-bottom-0">ইউজার</th>
                                            <th class="border-bottom-0">পেমেন্ট মেথড</th>
                                            <th class="border-bottom-0">একাউন্ট নম্বর</th>
                                            <th class="border-bottom-0">টাকার পরিমান</th>
                                            <th class="border-bottom-0">ভাউচার</th>
                                            <th class="border-bottom-0">স্টেটাস</th>
                                            <th class="border-bottom-0">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($requests as $key => $request)
                                        <tr>
                                            <td class="col-1">{{ $key+1 }}</td>
                        
                                            <td>{{ \Carbon\Carbon::parse($request->created_at)->format('j F Y') }}</td>
                                            <td>{{ $request->user->full_name ?? ''}}</td>
                                            <td>{{ ucfirst($request->method) }}</td>
                                            <td>{{ $request->from_account }}</td>
                                            <td class="font-weight-bold">৳{{ number_format($request->amount, 2) }}</td>
                                            <td>
                                                @if($request->screenshot)
                                                    <a href="{{ url('upload/balance/'.$request->screenshot) }}" target="_blank">
                                                        <img src="{{ url('upload/balance/'.$request->screenshot) }}" alt="screenshot" class="img-thumbnail" style="width:80px; height:80px;">
                                                    </a>
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->status == 'pending')
                                                    <span class="badge bg-pill bg-warning">Pending</span>
                                                @elseif($request->status == 'approved')
                                                    <span class="badge bg-pill bg-success">Approved</span>
                                                @elseif($request->status == 'rejected')
                                                    <span class="badge bg-pill bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.balance.request.show', $request->id) }}" class="btn btn-success btn-sm mr-2"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.balance.request.edit', $request->id) }}" class="btn btn-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.balance.request.delete', $request->id) }}" class="btn btn-danger btn-sm" title="Delete Data" id="delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No requests found.</td>
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