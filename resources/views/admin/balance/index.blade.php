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
        <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}} <span class="badge bg-danger side-badge" style="font-size:17px;">{{ count($requests) }}</span> </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file-datatable" class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">SL</th>
                                            <th class="border-bottom-0">Name</th>
                                            <th class="border-bottom-0">Date</th>
                                            <th class="border-bottom-0">Method</th>
                                            <th class="border-bottom-0">From Account</th>
                                            <th class="border-bottom-0">Amount</th>
                                            <th class="border-bottom-0">Trx ID</th>
                                            <th class="border-bottom-0">Screenshot</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests as $key => $request)
                                        <tr>
                                            <td class="col-1">{{ $key+1 }}</td>
                                            <td>{{ $request->user->full_name ?? ''}}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->created_at)->format('j F Y') }}</td>
                                            <td>{{ ucfirst($request->method) }}</td>
                                            <td>{{ $request->from_account }}</td>
                                            <td class="font-weight-bold">৳{{ number_format($request->amount, 2) }} ৳</td>
                                            <td>{{ $request->trx_id ?? '' }}</td>
                                            <td>
                                                @if($request->screenshot)
                                                    <a href="{{ (!empty($request->screenshot)) ? url('upload/balance/'.$request->screenshot):url('upload/mcq.png') }}" target="_blank">
                                                        <img src="{{ (!empty($request->screenshot)) ? url('upload/balance/'.$request->screenshot):url('upload/mcq.png') }}" alt="screenshot" class="img-thumbnail" style="width:80px; height:80px;">
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
                                                <a href="{{ route('admin.balance.request.show',$request->id)}}" class="btn btn-success btn-sm mr-2"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.balance.request.edit',$request->id)}}" class="btn btn-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.balance.request.delete',$request->id)}}" class="btn btn-danger btn-sm" title="Delete Data" id="delete"><i class="fa fa-trash"></i></a>
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
