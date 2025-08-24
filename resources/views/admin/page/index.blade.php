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
                            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}} <span class="badge bg-danger side-badge" style="font-size:17px;">{{ count($pages) }}</span> </p>

                            <div class="d-flex">
                                <a href="{{ route('admin.page.create')}}" class="btn btn-success me-2">
                                    <i class="fas fa-plus d-inline"></i> Add Now Page
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file-datatable" class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">SL</th>
                                            <th class="border-bottom-0">Page Banner</th>
                                            <th class="border-bottom-0">Page Name</th>
                                            <th class="border-bottom-0">Page Title</th>
                                            <th class="border-bottom-0">Page Link</th>
                                            <th class="border-bottom-0">Page Position</th>
                                            <th class="border-bottom-0">Is Default</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pages as $key=> $page)
                                        <tr>
                                            <td class="col-1">{{ $key+1 }}</td>
                                            <td>
                                                <img src="{{ (!empty($page->image)) ? url('upload/page/'.$page->image):url('upload/page-title.jpg') }}" width="100%" alt="image" class="img-fluid">
                                            </td>
                                            <td>{{ $page->page_name ?? 'Null'}}</td>
                                            <td>{{ $page->page_title ?? 'Null'}}</td>
                                            <td>
                                                <a class="btn btn-success btn-sm text-light mt-2" href="{{url(($page->page_slug == 'home-page') ? '/' :  $page->page_slug)}}" target="_blank">Copy Link <i class="far fa-copy"></i> </a>
                                            </td>
                                            <td>
                                                @if($page->position == 1)
                                                    <a href="#" class="badge bg-pill bg-success text-light">Top Bar</a>
                                                @elseif($page->position == 2)
                                                    <a href="#" class="badge bg-pill bg-danger text-light">Bootom Bar</a>
                                                @elseif($page->position == 3)
                                                    <a href="#" class="badge bg-pill bg-primary text-light">Footer Bar</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($page->is_default ==1)
                                                    <span class="badge bg-pill bg-warning text-light">Default</span>
                                                @else
                                                    <span class="badge bg-pill bg-info text-light">Custom</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($page->status == 1)
                                                    <a href="#" class="badge bg-pill bg-success">Active</a>
                                                @else
                                                    <a href="#" class="badge bg-pill bg-danger">Disable</a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.page.show',$page->id)}}" class="btn btn-success btn-sm mr-2"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.page.edit',$page->id)}}" class="btn btn-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.page.delete',$page->id)}}" class="btn btn-danger btn-sm" title="Delete Data" id="delete"><i class="fa fa-trash"></i></a>
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
