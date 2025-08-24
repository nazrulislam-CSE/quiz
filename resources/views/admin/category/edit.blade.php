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
    </div>
</div>

    <div class="main-content-body">
        <div class="row row-sm">
            
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}}</p>
            <div class="d-flex">
                <a href="{{ route('admin.category.index')}}" class="btn btn-danger me-2">
                    <i class="fas fa-list d-inline"></i> Category List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.category.update',$category->id)}}" method="post" enctype="multipart/form-data">
                @csrf
              <div class="row">
                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                       <label for="name">Category Name: <span class="text-danger"></span></label>
                       @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Category Name" id="basic-addon1"><i class="fas fa-tags" title="Category Name"></i></span>
                            <input type="text" value="{{ $category->name }}" class=" form-control" name="name" placeholder="Category Name">
                        </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="meta_title">Meta Title: <span class="text-danger"></span></label>
                        @error('meta_title') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                             <span class="input-group-text" title="Meta Title" id="basic-addon1"><i class="fas fa-tags"></i></span>
                             <input type="text" value="{{ $category->meta_title }}" class=" form-control" name="meta_title" placeholder="Meta Title">
                         </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="keywords">Meta Keywords <small class="text-danger">( Write meta keywords Separated by Comma[,] )</small></label>
                        @error('keywords') <span class="text-danger">{{ $message }}</span> @enderror
                            <div class="text-wrap">
                                <div class="example">
                                <input type="text" name="keywords[]" data-role="tagsinput" value="{{ $category->keywords}}" class="form-control" placeholder="Enter type meta keywords here">
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xl-12 col-lg-12  col-md-6">
                        <label for="meta_description">Meta Description:</label>
                        @error('meta_description') <span class="text-danger">{{ $message }}</span> @enderror
                        <textarea name="meta_description" id="meta_description">{{ $category->meta_description }}</textarea>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="type">Category Type For:</label>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                             <span class="input-group-text" title="Type" id="basic-addon1"><i class="fas fa-user-tie" title="Name"></i></span>
                             <select  name="type" class=" form-control">
                              <option value="">Select type</option>
                                    <option value="1" @if($category->type == 1) selected @endif>Category</option>
                                    <option value="2" @if($category->type == 2) selected @endif>Blog</option>
                                    <option value="3" @if($category->type == 3) selected @endif>Portfolio</option>
                             </select>
                         </div>
                     </div>
              
                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                       <label for="status">Status:</label>
                       @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Name" id="basic-addon1"><i class="fas fa-user-tie" title="Name"></i></span>
                            <select  name="status" class=" form-control">
                             <option value="">Select Status</option>
                                <option value="1" @if($category->status == 1) selected @endif> Active</option>
                                <option value="0" @if($category->status == 1) selected @endif>Deactive</option>
                            </select>
                        </div>
                    </div>
  
                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                        <button type="submit" class="add-to-cart btn btn-success btn-block"><i class="fas fa-plus"></i> Update Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
        </div>
    </div>
@endsection

@push('admin')
	<script>
        /* ============== Summernote Added ============ */
        jQuery(function(e){
            'use strict';
            $(document).ready(function() {
                $('#meta_description').summernote({
                    placeholder: 'Please some content here'
                });
            });
        });
        /* ============== Summernote Added ============ */
    </script>
@endpush
