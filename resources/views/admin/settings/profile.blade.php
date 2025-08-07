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
 <!-- Main content -->
 <!-- Main content -->
 <div class="row row-sm">
    <!-- Col -->
    <div class="col-lg-4">
        <div class="card mg-b-20">
            <div class="card-body">
                <div class="ps-0">
                    <div class="main-profile-overview">
                        <div class="main-img-user profile-user">
                            <img alt="" id="image-preview" src="{{ (!empty($profile->image)) ? url('upload/admin_images/'.$profile->image):url('upload/no_image.jpg') }}" class="text-center">
                        </div>
                        <div class="d-flex justify-content-between mg-b-20 mt-2">
                            <div>
                                <div class="align-items-center">
                                    <h5 class="main-profile-name">Name: {{ $profile->name }} </h5>
                                    <h5 class="">Email: {{ $profile->email }} </h5>
                                    <h5 class="">Phone: {{ $profile->phone }} </h5>
                                    <h6 class="">Address: {{ $profile->address }} </h6>
                                    <h6 class="">Password: {{ $profile->show_password }} </h6>
                                    <h6 class="">Created At: {{ $profile->created_at }} </h6>
                                    <h6 class="">Status: 
                                        @if($profile->status == 1)
                                            <a href="#" class="badge bg-pill bg-success">Active</a>
                                        @else
                                            <a href="#" class="badge bg-pill bg-danger">Disable</a>
                                        @endif
                                    </h6>


                                </div>
                            </div>
                        </div>
                    </div><!-- main-profile-overview -->
                </div>
            </div>
        </div>
    </div>
    <!-- /Col -->

    <!-- Col -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.profile.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4 main-content-label d-flex justify-content-between">
                        <span>Personal Information</span>
                    </div>
                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center">
                                <label class="form-label mb-0">Name</label>
                            </div>
                            <div class="col-1 d-flex align-items-center"><span>:</span></div>
                            <div class="col-7">
                                <input type="text" name="name" id="name" class="form-control bg-white" placeholder="Enter Name" value="{{ $profile->name ?? 'Null'}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center">
                                <label class="form-label mb-0">Username</label>
                            </div>
                            <div class="col-1 d-flex align-items-center"><span>:</span></div>
                            <div class="col-7">
                                <input type="text" name="username" id="username" class="form-control bg-white" placeholder="Enter Username" value="{{ $profile->username ?? 'Null'}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center">
                                <label class="form-label mb-0">Email</label>
                            </div>
                            <div class="col-1 d-flex align-items-center"><span>:</span></div>
                            <div class="col-7">
                                <input type="email" name="email" id="email" class="form-control bg-white" placeholder="Email" value="{{ $profile->email ?? 'Null'}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center">
                                <label class="form-label mb-0">Address</label>
                            </div>
                            <div class="col-1 d-flex align-items-center"><span>:</span></div>
                            <div class="col-7">
                                <input type="text" name="address" id="address" class="form-control bg-white" placeholder="Enter Address" value="{{ $profile->address }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center">
                                <label class="form-label mb-0">Phone</label>
                            </div>
                            <div class="col-1 d-flex align-items-center"><span>:</span></div>
                            <div class="col-7">
                                <input type="number" name="phone" id="phone" class="form-control bg-white" placeholder="Phone" value="{{ $profile->phone }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center">
                                <label class="form-label mb-0">Photo</label>
                            </div>
                            <div class="col-1 d-flex align-items-center"><span>:</span></div>
                            <div class="col-7">
                                <input type="file" name="photo" id="photo" class="form-control bg-white">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-4 d-flex align-items-center">
                                <label class="form-label mb-0"></label>
                            </div>
                            <div class="col-1 d-flex align-items-center"><span></span></div>
                            <div class="col-7">
                                <img id="showImage" src="{{ (!empty($profile->image)) ? url('upload/admin_images/'.$profile->image):url('upload/no_image.jpg') }}" alt="Admin" style="width:100px; height: 100px;"  >
                            </div>
                        </div>
                    </div>
        
                    <div class="card-footer border-0 d-flex justify-content-end px-0">
                        <button type="submit" class="btn btn-success waves-effect waves-light" id="update-btn"><i class="fa fa-plus"></i> Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Col -->
</div>
 <!-- /.content -->

 @push('admin')
 <!--profile photo  Show -->
 <script type="text/javascript">
	$(document).ready(function(){
		$('#photo').change(function(e){
			var reader = new FileReader();
			reader.onload = function(e){
				$('#showImage').attr('src',e.target.result);
			}
			reader.readAsDataURL(e.target.files['0']);
		});
	});
</script>

 @endpush
@endsection
