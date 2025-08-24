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
           <a href="{{ route('admin.page.index')}}" class="btn btn-danger me-2">
               <i class="fas fa-list d-inline"></i> Page List
           </a>
       </div>
   </div>
    <div class="card-body">
       <div class="table-responsive">
          <table class="table table-bordered">
             <tr>
                <td>Page Name</td>
                <td>{{ $page->page_name ?? 'NULL' }}</td>
             </tr>
             <tr>

                <td>Page Slug</td>
                <td>{{ $page->page_slug?? 'NULL' }}</td>
             </tr>
             <tr>
                <td>Page Title</td>
                <td>{{ $page->page_title ?? 'NULL' }}</td>
             </tr>
             <tr>
                <td>Page Description</td>
                <td>{!! $page->page_description ?? 'NULL' !!}</td>
             </tr>
             <tr>
                <td>Meta Title</td>
                <td>{{ $page->meta_title ?? 'NULL' }}</td>
             </tr>
             <tr>
                <td>Meta Keywords</td>
                <td>{{ $page->keywords ?? 'NULL' }}</td>
             </tr>
             <tr>
                <td>Meta Description</td>
                <td>{!! $page->meta_description ?? 'NULL' !!}</td>
             </tr>
            </tr>
            <td>position</td>
            <td>
                @if($page->position == 1)
                    <a href="#" class="badge bg-pill bg-success text-light">Top Bar</a>
                @elseif($page->position == 2)
                    <a href="#" class="badge bg-pill bg-danger text-light">Bootom Bar</a>
                @elseif($page->position == 3)
                    <a href="#" class="badge bg-pill bg-primary text-light">Footer Bar</a>
                @endif

            </td>
            </tr>
        </tr>
        <td>Is Default</td>
        <td>
            @if($page->is_default ==1)
            <span class="badge bg-pill bg-warning text-light">Default</span>
        @else
            <span class="badge bg-pill bg-info text-light">Custom</span>
        @endif

        </td>
        </tr>
             </tr>
             <td>Status</td>
             <td>
                   @if ($page->status == 1)
                   <span class="badge bg-pill bg-success">Active</span>
                   @else
                   <span class="badge bg-pill bg-success">Disable</span>
                   @endif

             </td>
             </tr>
             <tr>
                <td>Page Banner</td>
                <td>
                    <img src="{{ (!empty($page->image)) ? url('upload/page/'.$page->image):url('upload/page-title.jpg') }}" width="100%" alt="image" class="img-fluid">
                </td>
             </tr>
          </table>
       </div>
    </div>
 </div>
@endsection
