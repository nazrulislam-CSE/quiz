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
 <!-- Main content -->
 <!-- Main content -->
 <div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}}</p>
        <div class="d-flex">
            <a href="/admin/dashboard" class="btn btn-danger me-2">
                <i class="fas fa-list d-inline"></i> Dashboard
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="col-sm-11">
            <form method="post" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-7">
                        <div class="card card-danger card-outline shadow-lg">
                            <div class="card-header">
                                <h3>Site Settings</h3><hr>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="site_name" class="col-form-label" style="font-weight: bold;">Site Name :</label>
                                        <input type="hidden" name="types[]" value="site_name">
                                        <input class="form-control" type="text" name="site_name" id="site_name" placeholder="Write Site name" value="{{ get_setting('site_name')->value ?? ''}}">
                                        @error('site_name')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="business_name" class="col-form-label" style="font-weight: bold;">Business Name :</label>
                                        <input type="hidden" name="types[]" value="business_name">
                                        <input class="form-control" type="text" name="business_name" id="business_name" placeholder="Write Site name" value="{{ get_setting('business_name')->value ?? ''}}">
                                        @error('business_name')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="phone" class="col-form-label" style="font-weight: bold;">Phone :</label>
                                        <input type="hidden" name="types[]" value="phone">
                                        <input class="form-control" type="text" name="phone" id="phone" placeholder="Write phone" value="{{ get_setting('phone')->value ?? ''}}">
                                        @error('phone')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="email" class="col-form-label" style="font-weight: bold;">Email :</label>
                                        <input type="hidden" name="types[]" value="email">
                                        <input class="form-control" type="text" name="email" id="email" placeholder="Write email" value="{{ get_setting('email')->value ?? ''}}">
                                        @error('email')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Row End -->
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="business_hours" class="col-form-label" style="font-weight: bold;">Business Hours</label>
                                        <input type="hidden" name="types[]" value="business_hours">
                                        <input class="form-control" type="text" name="business_hours" placeholder="business hours" value="{{ get_setting('business_hours')->value ?? ''}}">
                                        @error('business_hours')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="copy_right" class="col-form-label" style="font-weight: bold;">Copy Right</label>
                                        <input type="hidden" name="types[]" value="copy_right">
                                        <input class="form-control" type="text" name="copy_right" placeholder="copy right" value="{{ get_setting('copy_right')->value ?? ''}}">
                                        @error('copy_right')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-12 mb-3">
                                        <label for="business_address" class="col-form-label" style="font-weight: bold;">Head office Address</label>
                                        <input type="hidden" name="types[]" value="business_address">
                                        <textarea class="form-control" id="business_address" cols="2" name="business_address" placeholder="Write address here">{{ get_setting('business_address')->value ?? ''}}</textarea>
                                        @error('business_address')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-12 mb-3">
                                        <label for="about" class="col-form-label" style="font-weight: bold;">About</label>
                                        <input type="hidden" name="types[]" value="about">
                                        <textarea class="form-control" id="about" cols="2" name="about" placeholder="Write about here">{{ get_setting('about')->value ?? ''}}</textarea>
                                        @error('about')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-sm-12 mb-3">
                                        <label for="breaking_news" class="col-form-label" style="font-weight: bold;">Breaking News</label>
                                        <input type="hidden" name="types[]" value="breaking_news">
                                        <textarea class="form-control" id="breaking_news" cols="2" name="breaking_news" placeholder="Write breaking news here">{{ get_setting('breaking_news')->value ?? 'null'}}</textarea>
                                        @error('breaking_news')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div> --}}
                                </div>
                                <!-- Row End// -->
                            </div>
                            <!-- card body .// -->
                        </div>
                        <!-- card .// --> 

                        <div class="card card-primary card-outline shadow-lg mt-3">
                            <div class="card-header">
                                <h3>Social Link Settings</h3><hr>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="facebook_url" class="col-form-label" style="font-weight: bold;">Facebook link :</label>
                                        <input type="hidden" name="types[]" value="facebook_url">
                                        <input class="form-control" type="text" name="facebook_url" id="facebook_url" placeholder="Write facebook url" value="{{ get_setting('facebook_url')->value ?? ''}}">
                                        @error('facebook_url')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="twitter_url" class="col-form-label" style="font-weight: bold;">Twitter link :</label>
                                        <input type="hidden" name="types[]" value="twitter_url">
                                        <input class="form-control" type="text" name="twitter_url" id="twitter_url" placeholder="Write twitter url" value="{{ get_setting('twitter_url')->value ?? ''}}">
                                        @error('twitter_url')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label for="linkedin_url" class="col-form-label" style="font-weight: bold;">Linkedin Link :</label>
                                        <input type="hidden" name="types[]" value="linkedin_url">
                                        <input class="form-control" type="text" name="linkedin_url" id="linkedin_url" placeholder="Write linkedin url" value="{{ get_setting('linkedin_url')->value ?? ''}}">
                                        @error('linkedin_url')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="youtube_url" class="col-form-label" style="font-weight: bold;">Youtube Link :</label>
                                        <input type="hidden" name="types[]" value="youtube_url">
                                        <input class="form-control" type="text" name="youtube_url" id="youtube_url" placeholder="Write youtube url" value="{{ get_setting('youtube_url')->value ?? ''}}">
                                        @error('youtube_url')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-sm-6 mb-3">
                                        <label for="instagram_url" class="col-form-label" style="font-weight: bold;">Instagram Link :</label>
                                        <input type="hidden" name="types[]" value="instagram_url">
                                        <input class="form-control" type="text" name="instagram_url" id="instagram_url" placeholder="Write instagram url" value="{{ get_setting('instagram_url')->value ?? ''}}">
                                        @error('instagram_url')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="pinterest_url" class="col-form-label" style="font-weight: bold;">Pinterest Link :</label>
                                        <input type="hidden" name="types[]" value="pinterest_url">
                                        <input class="form-control" type="text" name="pinterest_url" id="pinterest_url" placeholder="Write pinterest url" value="{{ get_setting('pinterest_url')->value ?? ''}}">
                                        @error('pinterest_url')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="whatsapp_url" class="col-form-label" style="font-weight: bold;">Whatsapp Link :</label>
                                        <input type="hidden" name="types[]" value="whatsapp_url">
                                        <input class="form-control" type="text" name="whatsapp_url" id="whatsapp_url" placeholder="Write Whatsapp url" value="{{ get_setting('whatsapp_url')->value ?? ''}}">
                                        @error('whatsapp_url')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- card //-->

                    </div>
                    <!-- col-6 //-->
                    <div class="col-md-5">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header mb-4">
                                <h3>Logo Settings</h3><hr>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <div class="mb-2">
                                            <img id="showFavicon" class="rounded avatar-lg" src="{{ asset(optional(get_setting('site_favicon'))->value ?? 'upload/MCQ Logo.png') }}" alt="No Image" width="100" height="">
                                        </div>
                                        <div class="mb-2">
                                            <label for="site_favicon" class="col-form-label" style="font-weight: bold;">Site Favicon <span class="text-danger">(Size:32,32px):</span></label>
                                            <input name="site_favicon" class="form-control" type="file" id="site_favicon">
                                            @error('site_favicon')
                                                <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12 mb-3">
                                        <div class="mb-2">
                                            <img id="showsitelogo" class="rounded avatar-lg" src="{{ asset(get_setting('site_logo')->value ?? 'upload/MCQ Logo.png') }}" alt="No Image" width="100" height="">
                                        </div>
                                        <div class="mb-2">
                                            <label for="image" class="col-form-label" style="font-weight: bold;">Site Logo <span class="text-danger">(Size:1026,538px):</span></label>

                                            <input name="site_logo" class="form-control" type="file" id="sitelogo">
                                            @error('site_logo')
                                                <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12 mb-3">
                                        <div class="mb-2">
                                            <img id="showFooter" class="rounded avatar-lg" src="{{ asset(get_setting('site_footer_logo')->value ?? 'upload/MCQ Logo.png') }}" alt="No Image" width="100" height="">
                                        </div>
                                        <div class="mb-2">
                                            <label for="site_footer_logo" class="col-form-label" style="font-weight: bold;">Site Footer Logo <span class="text-danger">(Size:1026,538px):</label>

                                            <input name="site_footer_logo" class="form-control" type="file" id="site_footer_logo">
                                            @error('site_footer_logo')
                                                <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-sm-12 mb-3">
                                        <div class="mb-2">
                                            <img id="showContact" class="rounded avatar-lg" src="{{ asset(get_setting('site_contact_logo')->value ?? 'Null') }}" alt="No Image" width="100" height="">
                                        </div>
                                        <div class="mb-2">
                                            <label for="site_contact_logo" class="col-form-label" style="font-weight: bold;">Site Contact Logo <span class="text-danger">(Size:380,106px):</label>

                                            <input name="site_contact_logo" class="form-control" type="file" id="site_contact_logo">
                                            @error('site_contact_logo')
                                                <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-sm-12 mb-3">
                                        <div class="mb-2">
                                            <img id="showCompany" class="rounded avatar-lg" src="{{ asset(get_setting('site_company_logo')->value ?? 'Null') }}" alt="No Image" width="100%" height="">
                                        </div>
                                        <div class="mb-2">
                                            <label for="site_company_logo" class="col-form-label" style="font-weight: bold;">Company Banner <span class="text-danger">(Size:2913,1024px):</label>

                                            <input name="site_company_logo" class="form-control" type="file" id="site_company_logo">
                                            @error('site_company_logo')
                                                <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div> --}}

                                </div>
                                <!-- row //-->
                            </div>
                        </div>
                        <!-- card //-->

                        {{-- <div class="card card-primary card-outline shadow-lg mt-3">
                            <div class="card-header">
                                <h3>Meta Settings:</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="meta_title" class="col-form-label" style="font-weight: bold;">Meta Title :</label>
                                        <input type="hidden" name="types[]" value="meta_title">
                                        <input class="form-control" type="text" name="meta_title" id="meta_title" placeholder="Write meta  name" value="{{ get_setting('meta_title')->value ?? 'null'}}">
                                        @error('meta_title')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 mb-3">
                                        <label for="meta_keyword" class="col-form-label" style="font-weight: bold;">Meta Keyword :</label>
                                        <input type="hidden" name="types[]" value="meta_keyword">
                                        <input class="form-control" type="text" name="meta_keyword" id="linkedin_url" placeholder="Write meta keyword " value="{{ get_setting('meta_keyword')->value ?? 'null'}}">
                                        @error('meta_keyword')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="col-sm-12 mb-3">
                                        <label for="meta_description" class="col-form-label" style="font-weight: bold;">Meta Description :</label>
                                        <input type="hidden" name="types[]" value="meta_description">

                                        <textarea class="form-control" name="meta_description" rows="3" placeholder="Enter ">{{ get_setting('meta_description')->value ?? 'null'}}</textarea>
                                        @error('meta_description')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <!-- card //-->
                    </div>
                    {{-- <div class="col-sm-6 mb-3">
                        <div class="mb-2">
                            <img id="showCompany1" class="rounded avatar-lg" src="{{ asset(get_setting('top_banner')->value ?? '') }}" alt="No Image" width="100%" height="">
                        </div>
                        <div class="mb-2">
                            <label for="top_banner" class="col-form-label" style="font-weight: bold;">Top Banner <span class="text-danger">(Size:710,400px):</label>
                            <input name="top_banner" class="form-control" type="file" id="top_banner">
                            @error('top_banner')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="mb-2">
                            <img id="showCompany2" class="rounded avatar-lg" src="{{ asset(get_setting('top_banner1')->value ?? '') }}" alt="No Image" width="100%" height="">
                        </div>
                        <div class="mb-2">
                            <label for="top_banner1" class="col-form-label" style="font-weight: bold;">Top Banner 1 <span class="text-danger">(Size:710,400px):</label>
                            <input name="top_banner1" class="form-control" type="file" id="top_banner1">
                            @error('top_banner1')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div> --}}
                    {{-- <div class="col-sm-6 mb-3">
                        <div class="mb-2">
                            <img id="showCompany3" class="rounded avatar-lg" src="{{ asset(get_setting('middle_banner')->value ?? '') }}" alt="No Image" width="100%" height="">
                        </div>
                        <div class="mb-2">
                            <label for="middle_banner" class="col-form-label" style="font-weight: bold;">Middle Banner <span class="text-danger">(Size:710,400px):</label>
                            <input name="middle_banner" class="form-control" type="file" id="middle_banner">
                            @error('middle_banner')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div> --}}
                    {{-- <div class="col-sm-6 mb-3">
                        <div class="mb-2">
                            <img id="showCompany4" class="rounded avatar-lg" src="{{ asset(get_setting('middle_banner1')->value ?? '') }}" alt="No Image" width="100%" height="">
                        </div>
                        <div class="mb-2">
                            <label for="middle_banner1" class="col-form-label" style="font-weight: bold;">Middle Banner 1 <span class="text-danger">(Size:710,400px):</label>
                            <input name="middle_banner1" class="form-control" type="file" id="middle_banner1">
                            @error('middle_banner1')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div> --}}
                </div>

                <div class="input-group mb-1">
                    <div class="btn-group w-100">
                    </i><button type="update" class="btn btn-success col fileinput-button dz-clickable">
                    <div wire:loading wire:target="profileStore">
                            <div class="la-ball-clip-rotate-multiple la-dark la-sm">
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    <i class="fas fa-plus"></i> Update</button>
                    </div>
                </div>
            </form>
        </div>
    <div>   
</div>
 <!-- /.content -->

 @push('admin')
 <!--Site favicon Show -->
<script type="text/javascript">
   $(document).ready(function(){
       $('#site_favicon').change(function(e){
           var reader = new FileReader();
           reader.onload = function(e){
               $('#showFavicon').attr('src',e.target.result);
           }
           reader.readAsDataURL(e.target.files['0']);
       });
   });
</script>

 <!--Site Header logo Show -->
 <script type="text/javascript">
   $(document).ready(function(){
       $('#sitelogo').change(function(e){
           var reader = new FileReader();
           reader.onload = function(e){
               $('#showsitelogo').attr('src',e.target.result);
           }
           reader.readAsDataURL(e.target.files['0']);
       });
   });
</script>

<!--Site footer logo Show -->
<script type="text/javascript">
   $(document).ready(function(){
       $('#site_footer_logo').change(function(e){
           var reader = new FileReader();
           reader.onload = function(e){
               $('#showFooter').attr('src',e.target.result);
           }
           reader.readAsDataURL(e.target.files['0']);
       });
   });
</script>

<!--Site Contact logo Show -->
<script type="text/javascript">
   $(document).ready(function(){
       $('#site_contact_logo').change(function(e){
           var reader = new FileReader();
           reader.onload = function(e){
               $('#showContact').attr('src',e.target.result);
           }
           reader.readAsDataURL(e.target.files['0']);
       });
   });
</script>

<!--Company Banner Show -->
<script type="text/javascript">
   $(document).ready(function(){
       $('#site_company_logo').change(function(e){
           var reader = new FileReader();
           reader.onload = function(e){
               $('#showCompany').attr('src',e.target.result);
           }
           reader.readAsDataURL(e.target.files['0']);
       });
   });
</script>
<script type="text/javascript">
    // banner 1
    $(document).ready(function(){
        $('#top_banner').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showCompany1').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
    // banner 2
    $(document).ready(function(){
        $('#top_banner1').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showCompany2').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
    // banner 3
    $(document).ready(function(){
        $('#middle_banner').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showCompany3').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
    // banner 4
    $(document).ready(function(){
        $('#middle_banner1').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showCompany4').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
 </script>
 @endpush
@endsection
