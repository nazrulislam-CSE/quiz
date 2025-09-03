@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span>ব্যালান্স রিকোয়েস্ট রিপোর্ট</span>
            <a href="{{ route('user.balance.request') }}" class="btn btn-light btn-sm">নতুন রিকোয়েস্ট</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>Sl</th>
                            <th>তারিখ</th>
                            <th>পেমেন্ট মেথড</th>
                            <th>একাউন্ট নাম্বার</th>
                            <th>টাকার পরিমাণ</th>
                            <th>স্ক্রিনশট</th>
                            <th>স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $key => $request)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d-m-Y') }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ ucfirst($request->method) }}</span>
                            </td>
                            <td>{{ $request->from_account }}</td>
                            <td class="fw-bold text-success">{{ number_format($request->amount) }} ৳</td>
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
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($request->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-muted">কোনো রিকোয়েস্ট পাওয়া যায়নি</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
