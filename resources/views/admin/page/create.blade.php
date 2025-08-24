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
                <a href="{{ route('admin.page.index')}}" class="btn btn-danger me-2">
                    <i class="fas fa-list d-inline"></i> Page List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.page.store')}}" method="post" enctype="multipart/form-data">
                @csrf
              <div class="row">
                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                       <label for="page_name">Page Name: <span class="text-danger"></span></label>
                       @error('page_name') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Page Name" id="basic-addon1"><i class="fas fa-tags"></i></span>
                            <input type="text" value="{{ old('page_name') }}" class=" form-control" name="page_name" placeholder="Page Name">
                        </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="page_title">Page Title: <span class="text-danger"></span></label>
                        @error('page_title') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                             <span class="input-group-text" title="Page Title" id="basic-addon1"><i class="fas fa-tags"></i></span>
                             <input type="text" value="{{ old('page_title') }}" class=" form-control" name="page_title" placeholder="Page Title">
                         </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="meta_title">Meta Title: <span class="text-danger"></span></label>
                        @error('meta_title') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                             <span class="input-group-text" title="Meta Title" id="basic-addon1"><i class="fas fa-tags"></i></span>
                             <input type="text" value="{{ old('meta_title') }}" class=" form-control" name="meta_title" placeholder="Meta Title">
                         </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="keywords">Meta Keywords <small class="text-danger">( Write meta keywords Separated by Comma[,] )</small></label>
                        @error('keywords') <span class="text-danger">{{ $message }}</span> @enderror
                            <div class="text-wrap">
                                <div class="example">
                                <input type="text"  name="keywords[]" data-role="tagsinput" value="" class="form-control" placeholder="Enter type meta keywords here">
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xl-12 col-lg-12  col-md-6">
                        <label for="meta_description">Meta Description:</label>
                        @error('meta_description') <span class="text-danger">{{ $message }}</span> @enderror
                        <textarea name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
                    </div>

                    <div class="form-group col-xl-12 col-lg-12 col-md-12">
                        <label for="image">Page Banner Photo <span class="text-danger font-weight-bolder">(Size:1920,900px)</span>:</label>
                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Photo" id="basic-addon1"><i class="fas fa-photo-video"></i></span>
                            <input type="file" name="image" id="image" class="form-control bg-white">
                        </div>
                   </div>

                   <div class="form-group col-xl-12 col-lg-12 col-md-12">
                       <img id="showImage" src="{{ (!empty($profile->image)) ? url('upload/page/'.$profile->image):url('upload/page-title.jpg') }}" alt="photo" width="100%" >
                   </div>

                    <div class="form-group col-xl-4 col-lg-6 col-md-6">
                        <label for="position">Positions: (Top,Middle,Footer):</label>
                        @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                            <span class="input-group-text" title="Name" id="basic-addon1"><i class="fas fa-user-tie" title="Name"></i></span>
                            <select  name="position" class=" form-control">
                              <option value="">Select Positions</option>
                              <option value="1">Top Positions</option>
                              <option value="2">Bootom Positions</option>
                              <option value="3">Footer Positions</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-xl-4 col-lg-6 col-md-6">
                       <label for="status">Status:</label>
                       @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Name" id="basic-addon1"><i class="fas fa-user-tie" title="Name"></i></span>
                            <select  name="status" class=" form-control">
                             <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-xl-4 col-lg-6 col-md-6">
                        <label for="is_default">Is Default:</label>
                        @error('is_default') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                             <span class="input-group-text" title="Name" id="basic-addon1"><i class="fas fa-user-tie" title="Name"></i></span>
                             <select  name="is_default" class=" form-control">
                              <option value="">Select Is Default</option>
                                 <option value="1">Is Default</option>
                                 <option value="0">Not Is Default</option>
                             </select>
                         </div>
                    </div>

                    <div class="form-group col-xl-12 col-lg-12  col-md-6">
                        <label for="page_description">Page Description:</label>
                        @error('page_description') <span class="text-danger">{{ $message }}</span> @enderror
                        <textarea name="page_description" id="page_description">{{ old('page_description') }}</textarea>
                    </div>
  
                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                        <button type="submit" class="add-to-cart btn btn-success btn-block"><i class="fas fa-plus"></i>Add Page</button>
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
        /* ============== Team Photo ============ */
          $(document).ready(function(){
            $('#image').change(function(e){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#showImage').attr('src',e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
        /* ============== Summernote Added ============ */
        jQuery(function(e){
            'use strict';
            $(document).ready(function() {
                $('#meta_description').summernote({
                    placeholder: 'Please some content here'
                });
                $('#page_description').summernote({
                    placeholder: 'Please some content here'
                });
            });
        });
        /* ============== Summernote Added ============ */
    </script>
@endpush
