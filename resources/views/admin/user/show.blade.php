@extends('layouts.admin.app')

@section('content')
 <!-- Breadcrumb -->
 <div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'User Details' }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex my-auto">
        <a href="{{ route('admin.user.index')}}" class="btn btn-danger">
            <i class="fas fa-list"></i> User List
        </a>
    </div>
</div>

 <!-- User Details -->
 <div class="card card-primary card-outline shadow-lg mb-4">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
       <h5 class="card-title my-0">{{ $user->full_name ?? 'N/A' }} - বিস্তারিত তথ্য</h5>
    </div>
    <div class="card-body">
       <div class="table-responsive">
          <table class="table table-bordered table-striped">
             <tr>
                <th width="20%">ছবি</th>
                <td>
                    <img src="{{ !empty($user->image) ? url('upload/user/'.$user->image) : url('upload/avater.png') }}" 
                         width="120" class="img-thumbnail">
                </td>
             </tr>
             <tr>
                <th>পূর্ণ নাম</th>
                <td>{{ $user->full_name ?? '---' }}</td>
             </tr>
             <tr>
                <th>ইউজারনেম</th>
                <td>{{ $user->username ?? '---' }}</td>
             </tr>
             <tr>
                <th>ইমেইল</th>
                <td>{{ $user->email ?? '---' }}</td>
             </tr>
             <tr>
                <th>মোবাইল</th>
                <td>{{ $user->phone ?? '---' }}</td>
             </tr>
             <tr>
                <th>পাসওয়ার্ড (Show)</th>
                <td>{{ $user->show_password ?? '---' }}</td>
             </tr>
             <tr>
                <th>এনআইডি নম্বর</th>
                <td>{{ $user->nid_number ?? '---' }}</td>
             </tr>
             <tr>
                <th>রেফারেন্স</th>
                <td>
                    নাম: {{ optional($user->refer)->full_name ?? '---' }} <br>
                    আইডি: {{ $user->refer_by ?? '---' }}
                </td>
             </tr>
             <tr>
                <th>বিভাগ</th>
                <td>{{ $user->division->name_bn ?? '---' }}</td>
             </tr>
             <tr>
                <th>স্ট্যাটাস</th>
                <td>
                    @if($user->status == 1)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Disable</span>
                    @endif
                </td>
             </tr>
             <tr>
                <th>যোগদান তারিখ</th>
                <td>{{ $user->created_at->format('d M, Y h:i A') }}</td>
             </tr>
             <tr>
                <th>আপডেট তারিখ</th>
                <td>{{ $user->updated_at->format('d M, Y h:i A') }}</td>
             </tr>
          </table>
       </div>
    </div>
 </div>
@endsection
