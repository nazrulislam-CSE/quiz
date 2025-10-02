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
        <div class="row row-sm">
            
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}}</p>
            <div class="d-flex">
                <a href="{{ route('admin.branch.index')}}" class="btn btn-danger me-2">
                    <i class="fas fa-list d-inline"></i> Branch List
                </a>
            </div>
        </div>
        <div class="card-body">
           <form action="{{ route('admin.branch.store') }}" method="POST" enctype="multipart/form-data">
              @csrf 
                <div class="row m-4">

                  <div class="col-md-12">
                     <div class="form-group">
                       <label for="name">Branch Name: <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" value="{{ old('branch_name') }}" id="name" class="form-control" placeholder="Write branch name">
                        @error('branch_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                       <label for="name">Contact No: <span class="text-danger">*</span></label>
                        <input type="text" name="contact_no" value="{{ old('contact_no') }}" id="name" class="form-control" placeholder="Write Contact no name">
                        @error('contact_no')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                       <label for="name">Contract No Extra<span class="text-danger">*</span></label>
                        <input type="text" name="contact_no_optional" value="{{ old('contact_no_optional') }}" id="name" class="form-control" placeholder="Write Extra Contact No">
                        @error('contact_no_optional')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                       <label for="name">Area Link: <span class="text-danger">*</span></label>
                        <input type="text" name="area_link" value="{{ old('area_link') }}" id="name" class="form-control" placeholder="Please give Area Link">
                        @error('area_link')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="status">Status:</label>
                        <span class="text-danger">*</span>
                        <select name="status" id="status" class="form-control">
                           <option value="1">Active</option>
                           <option value="0">Disable</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12 text-right">
                     <div class="form-group">
                        <button class="btn btn-success" type="submit">Submit</button>
                     </div>
                  </div>
               </div>
            </form>
        </div>
    </div>
        </div>
    </div>
@endsection
@push('admin')
	
@endpush