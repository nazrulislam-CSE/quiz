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
                <a href="{{ route('admin.feature.index')}}" class="btn btn-danger me-2">
                    <i class="fas fa-list d-inline"></i> Feature List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.feature.store')}}" method="post" enctype="multipart/form-data">
                @csrf
              <div class="row">
                    <div class="form-group col-xl-12 col-lg-12 col-md-6">
                        <label for="icon">Font Awesome ICON: <span class="text-danger"></span></label>
                        @error('icon') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                             <span class="input-group-text" title="ICON" id="basic-addon1"><i class="fas fa-users"></i></span>
                             <input type="text" value="{{ old('icon') }}" class=" form-control" name="icon" placeholder="Enter Font Awesome ICON">
                         </div>
                    </div>

                    <div class="form-group col-xl-12 col-lg-12 col-md-6">
                        <label for="title">Title: <span class="text-danger"></span></label>
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                             <span class="input-group-text" title="Title" id="basic-addon1"><i class="fas fa-users"></i></span>
                             <input type="text" value="{{ old('title') }}" class=" form-control" name="title" placeholder="Enter Title">
                         </div>
                     </div>

                    <div class="form-group col-xl-12 col-lg-12  col-md-6">
                        <label for="description">Description:</label>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        <textarea name="description" id="description"></textarea>
                    </div>

                    <div class="form-group col-xl-12 col-lg-6 col-md-6">
                       <label for="status">Status:</label>
                       @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Name" id="basic-addon1"><i class="fas fa-user-tie" title="Name"></i></span>
                            <select  name="status" class=" form-control">
                             <option value="">Select Status</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
                    </div>
  
                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                        <button type="submit" class="add-to-cart btn btn-success btn-block"><i class="fas fa-plus"></i> Add Feature</button>
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
                $('#description').summernote({
                    placeholder: 'Please some content here'
                });
            });
        });
        /* ============== Summernote Added ============ */
    </script>
@endpush
