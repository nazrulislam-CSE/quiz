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
       <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}}</p>
       <div class="d-flex">
           <a href="{{ route('admin.teacher.index')}}" class="btn btn-danger me-2">
               <i class="fas fa-list d-inline"></i> Teacher List
           </a>
       </div>
   </div>
    <div class="card-body">
       <div class="table-responsive">
          <table class="table table-bordered">
             <tr>
                <td>Name</td>
                <td>{{ $teacher->name ?? '' }}</td>
             </tr>
             <tr>
                <td>Designation</td>
                <td>{{ $teacher->designation ?? '' }}</td>
             </tr>
             <tr>
                <td>Facebook Url</td>
                <td>{{ $teacher->facebook_url ?? '' }}</td>
             </tr>
             <tr>
                <td>Linkedin Url</td>
                <td>{{ $teacher->linkedin_url ?? '' }}</td>
             </tr>
             <tr>
                <td>Twitter Url</td>
                <td>{{ $teacher->twitter_url ?? '' }}</td>
             </tr>
             <tr>
                <td>Whatsapp Url</td>
                <td>{{ $teacher->whatsapp_url ?? '' }}</td>
             </tr>
             <tr>
                <td>Description</td>
                <td>{!! $teacher->description ?? '' !!}</td>
             </tr>
             <tr>
                <td>Status</td>
                <td>
                    @if ($teacher->status == 1)
                    <span class="badge bg-pill bg-success">Active</span>
                    @else
                    <span class="badge bg-pill bg-success">Disable</span>
                    @endif
   
                </td>
             </tr>
             <tr>
                <td>Photo</td>
                <td>
                    <img src="{{ (!empty($teacher->image)) ? url('upload/teacher/'.$teacher->image):url('upload/no_image.jpg') }}" width="80" alt="image" class="img-fluid">
                </td>
             </tr>
          </table>
       </div>
    </div>
 </div>
@endsection
