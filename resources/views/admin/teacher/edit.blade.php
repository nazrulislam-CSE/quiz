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
                <a href="{{ route('admin.teacher.index')}}" class="btn btn-danger me-2">
                    <i class="fas fa-list d-inline"></i> Teacher List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.teacher.update',$teacher->id)}}" method="post" enctype="multipart/form-data">
                @csrf
              <div class="row">
                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                       <label for="name">Name: <span class="text-danger"></span></label>
                       @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Name" id="basic-addon1"><i class="fas fa-users"></i></span>
                            <input type="text" value="{{ $teacher->name }}" class=" form-control" name="name" placeholder="Enter Name">
                        </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="designation">Designation: <span class="text-danger"></span></label>
                        @error('designation') <span class="text-danger">{{ $message }}</span> @enderror
                         <div class="input-group">
                             <span class="input-group-text" title="Designation" id="basic-addon1"><i class="fas fa-building"></i></span>
                             <input type="text" value="{{ $teacher->designation }}" class=" form-control" name="designation" placeholder="Enter Designation">
                         </div>
                     </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="facebook_url">Facebook Url: <span class="text-danger"></span></label>
                        @error('facebook_url') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Facebook Url" id="basic-addon1"><i class="fab fa-facebook"></i></span>
                            <input type="text" value="{{ $teacher->facebook_url }}" class=" form-control" name="facebook_url" placeholder="Enter Facebok Url">
                        </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="linkedin_url">Linkedin Url: <span class="text-danger"></span></label>
                        @error('linkedin_url') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Linkedin Url" id="basic-addon1"><i class="fab fa-linkedin"></i></span>
                            <input type="text" value="{{ $teacher->linkedin_url }}" class=" form-control" name="linkedin_url" placeholder="Enter Linkedin Url">
                        </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="twitter_url">Twitter Url: <span class="text-danger"></span></label>
                        @error('twitter_url') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Twitter Url" id="basic-addon1"><i class="fab fa-twitter"></i></span>
                            <input type="text" value="{{ $teacher->twitter_url }}" class=" form-control" name="twitter_url" placeholder="Enter Twitter Url">
                        </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="whatsapp_url">Whatsapp Url: <span class="text-danger"></span></label>
                        @error('whatsapp_url') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Whatsapp Url" id="basic-addon1"><i class="fab fa-whatsapp"></i></span>
                            <input type="text" value="{{ $teacher->whatsapp_url }}" class=" form-control" name="whatsapp_url" placeholder="Enter Whatsapp Url">
                        </div>
                    </div>

                    <div class="form-group col-xl-6 col-lg-6 col-md-6">
                        <label for="image">Photo <span class="text-danger font-weight-bolder">(Size:300,300px)</span>:</label>
                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Photo" id="basic-addon1"><i class="fas fa-photo-video"></i></span>
                            <input type="file" name="image" id="image" class="form-control bg-white">
                        </div>
                   </div>

                   <div class="form-group col-xl-6 col-lg-6 col-md-6">
                       <img id="showImage" src="{{ (!empty($teacher->image)) ? url('upload/teacher/'.$teacher->image):url('upload/no_image.jpg') }}" alt="Admin" style="width:100px; height: 100px;"  >
                   </div>

                    <div class="form-group col-xl-12 col-lg-12  col-md-6">
                        <label for="description">Description:</label>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        <textarea name="description" id="description">{{ $teacher->description }}</textarea>
                    </div>


                    <div class="form-group col-xl-12 col-lg-6 col-md-6">
                       <label for="status">Status:</label>
                       @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        <div class="input-group">
                            <span class="input-group-text" title="Name" id="basic-addon1"><i class="fas fa-user-tie" title="Name"></i></span>
                            <select  name="status" class=" form-control">
                             <option value="">Select Status</option>
                                <option value="1" @if($teacher->status == 1) selected @endif>Active</option>
                                <option value="0" @if($teacher->status == 0) selected @endif>Deactive</option>
                            </select>
                        </div>
                    </div>
  
                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                        <button type="submit" class="add-to-cart btn btn-success btn-block"><i class="fas fa-paper-plane"></i> Update Teacher</button>
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
        /* ============== teacher Photo ============ */
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
