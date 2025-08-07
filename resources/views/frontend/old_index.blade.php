@extends('layouts.frontend.app')
@section('content')
<style>
    @media (min-width: 1400px) {
        .container {
            max-width: 1400px;
        }
    }
    .banner_section{
        padding: 0px !important;
    }
    .banner_section .carousel-inner {
       
    }

    @media (max-width: 768px) {
        .banner_section .carousel-inner {
            height: 150px; /* Adjust this value for smaller screens */
        }
        .about_section{
            font-size: 16px;
        }
    }
    .services_section{
        padding: 0px !important;
        padding-top: 20px !important;
    }
    section {
        padding: 64px 0 !important;
    }
    .animate-charcter{
        text-transform: uppercase;
        background-image: linear-gradient(
            -255deg,
            #231557 0%,
            #44107a 29%,
            #ff1361 67%,
            #fff800 100%
        );
        background-size: 200% auto;
        animation: textclip 3s linear infinite;
    }
    @keyframes textclip {
        to {
            background-position: 200% center;
        }
    }
    .card-style-03 .card-price {
        background-color: #e74860;
        bottom: -50px;
        top: -4px;
        border-radius: 50%;
        right: -2px;
        width: 80px;
        height: 80px;
    }
    .card-style-03 .price-before {
        padding: 12px 5px;
        height: 228px;
    }
    .carousel-control-next-icon, .carousel-control-prev-icon {
        width: 4rem;
        height: 4rem;
        background-color: red;
        border-radius: 50%;
    }
</style>
    <!-- =========== BANNER ============ -->
        <section class="banner_section">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @if(count($sliders) == 0)
                        @for($i=1;$i < 3;$i++)
                            <div class="carousel-item active">
                                <img src="{{ (!empty($slider->image)) ? url('upload/slider/'.$slider->image):url('upload/no_image.jpg') }}" class="d-block w-100" alt="...">
                            </div>
                        @endfor
                    @endif
                    @foreach($sliders as $slider)
                        <div class="carousel-item active">
                            <img src="{{ (!empty($slider->image)) ? url('upload/slider/'.$slider->image):url('upload/no_image.jpg') }}" class="d-block w-100" alt="...">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>
        
        {{-- <div class="container pt-1-9 pt-sm-6 pt-md-0 mt-n1-9">
            <div class="row align-items-center position-relative z-index-1 justify-content-center py-xxl-7">
                <div class="col-md-10 col-xl-11 text-center py-5">
                    <span class="text-primary text-uppercase small letter-spacing-4 d-block mb-2 font-weight-600 wow fadeInUp">Strategic . Innovate . Excellence</span>
                    <h1 class="display-9 display-md-4 display-lg-3 display-xl-1 mb-2-2 text-shadow text-white font-weight-800 wow fadeInUp" data-wow-delay="200ms">IT consulting service for your <span class="title-sm banner">business.</span></h1>                        
                    <a href="contact.html" class="btn-style2 primary wow fadeInUp" data-wow-delay="400ms"><span>Get Started <i class="fa-solid fa-arrow-right ps-2 display-30"></i></span></a>
                </div>
            </div>
        </div>
        <div class="position-absolute top-5 right-n20 right-lg-n15 right-xl-n5 ani-left-right z-index-9 d-none d-md-block">
            <img src="{{ asset('frontend/img/content/shape-09.png')}}" alt="...">
        </div> --}}
    </section>

    <!-- ========== ABOUT US ========== -->
    <section class="about-style-01">
        <div class="container">
            @foreach($abouts as $about)
                <div class="row align-items-xl-center mt-n7">
                    <div class="col-lg-6 mt-7">
                        <div class="position-relative z-index-9">
                            <div class="row">
                                <div class="col-6 wow fadeInDown" data-wow-delay="200ms">
                                    <div class="image-hover position-relative overflow-hidden">
                                        <img src="{{ (!empty($about->image)) ? url('upload/about/'.$about->image):url('upload/no_image.jpg') }}" alt="...">
                                    </div>
                                </div>
                                <div class="col-6 wow fadeInUp" data-wow-delay="400ms">
                                    <div class="image-hover position-relative overflow-hidden">
                                        <img src="{{ (!empty($about->image1)) ? url('upload/about/'.$about->image1):url('upload/no_image.jpg') }}" alt="..." class="mt-1-9 mt-sm-10">
                                    </div>
                                </div>
                            </div>
                            <div class="sm-box-wrapper z-index-9 wow fadeIn" data-wow-delay="600ms">
                                <div class="inner-box">
                                    <span class="exp-no">{{ $about->experience_no ?? "0" }}</span>
                                    <span class="exp-year">{{ $about->experience_title ?? "n/a" }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-7 wow fadeIn">
                        <div class="ps-lg-1-9 ps-xl-7">
                            <div class="mb-1-9 position-relative">
                                <h4 class="text-primary text-uppercase  letter-spacing-4 d-block mb-2 font-weight-bolder wow fadeInUp">About Us</h4>
                                <h4 class="about_section wow fadeInUp" data-wow-delay="200ms">Speak Up Bangladesh Doing Creative Works<span class=""> </span></h4>
                            </div>
                            <p class="fst-italic font-weight-900 w-90 wow fadeInUp" data-wow-delay="200ms">
                                {{ $about->title ?? 'n/a'}}
                            </p>
                            <div class="about-text" style="text-align:justify; ">
                                {!! $about->description ?? 'n/a'!!}
                            </div>
                            {{-- <div class="d-sm-flex align-items-center">
                                <div class="flex-shrink-0 mb-4 mb-sm-0">
                                    <img src="{{ asset('frontend/img/avatars/avatar-01.jpg')}}" class="w-55px rounded-circle wow fadeInRight" data-wow-delay="100ms" alt="...">
                                    <img src="{{ asset('frontend/img/avatars/avatar-02.jpg')}}" class="w-55px rounded-circle img1 wow fadeInRight" data-wow-delay="200ms" alt="...">
                                    <img src="{{ asset('frontend/img/avatars/avatar-03.jpg')}}" class="w-55px rounded-circle img2 wow fadeInRight" data-wow-delay="300ms" alt="...">
                                    <span class="bg-primary about-icon text-white wow fadeInRight" data-wow-delay="400ms"><i class="fa-solid fa-phone"></i></span>
                                </div>
                                <div class="flex-grow-1 ms-sm-4 wow fadeIn" data-wow-delay="600ms">
                                    <h4 class="mb-0 h5">Over 48,500 people work for us in more than 75 countries.</h4>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="position-absolute top-5 left-n5 ani-top-bottom d-none d-md-block">
            <img src="{{ asset('frontend/img/content/shape-05.png')}}" alt="...">
        </div>
    </section>


    <!-- =========== STUDY ABROAD  ============ -->
    <section class="bg-light services_section">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="{{ route('study.all') }}" class="animate-charcter btn-style2 md text-white"><i class="fa fa-share"></i> View All</a>
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase letter-spacing-4 d-block mb-2 font-weight-bolder">Study Abroad</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">We provide the best <span class="">Study Abroad.</span></h2> --}}
            </div>
           
            <div class="service-carousel owl-carousel owl-theme">
                @if(count($studys) == 0)
                    @for($i=1;$i < 3;$i++)
                        <div class="card card-style05">
                            <div class="card-img">
                                <img src="{{ asset('frontend/img/service/service-01.jpg')}}" alt="...">
                            </div>
                            <div class="card-body p-1-9">
                                <div class="d-flex">
                                    <div class="flex-grow-1 ps-4">
                                        <h3 class="h5"><a href="#"> Default Study Abroad {{$i+1}}</a></h3>
                                        <p> Default Study Abroad {{$i+1}}</p>
                                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
                
                @foreach($studys as $study)
                <div class="card card-style05">
                    <div class="card-img">
                        <img src="{{ (!empty($study->image)) ? url('upload/study/'.$study->image):url('upload/no_image.jpg') }}" alt="...">
                    </div>
                    <div class="card-body p-1-9">
                        <div class="d-flex">
                            {{-- <div class="flex-shrink-0">
                                <img src="{{ (!empty($study->image)) ? url('upload/study/'.$study->image):url('upload/no_image.jpg') }}" alt="...">
                            </div> --}}
                            <div class="flex-grow-1 ps-4">
                                <h3 class="h5"><a href="{{ route('single.study.page',$study->slug) }}">{{ $study->country_name ?? 'n/a'}}</a></h3>
                                <p>
                                    <?php $p_name_bn = strip_tags(html_entity_decode($study->documents)); ?>
                                    {{ Str::limit($p_name_bn, $limit = 20, $end = '. . .') }}
                                </p>
                                <a href="{{ route('single.study.page',$study->slug) }}">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- =========== EDUCATIONS  ============ -->
    <section class="bg-light services_section">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="{{ route('education.all') }}" class="animate-charcter btn-style2 md text-white"><i class="fa fa-share"></i> View All</a>
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase  letter-spacing-4 d-block mb-2 font-weight-bolder">EDUCATIONS</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">We provide the best <span class="">EDUCATIONS.</span></h2> --}}
            </div>
            <div class="service-carousel owl-carousel owl-theme">
                @if(count($educations) == 0)
                    @for($i=1;$i < 3;$i++)
                        <div class="card card-style05">
                            <div class="card-img">
                                <img src="{{ asset('frontend/img/service/service-01.jpg')}}" alt="...">
                            </div>
                            <div class="card-body p-1-9">
                                <div class="d-flex">
                                    <div class="flex-grow-1 ps-4">
                                        <h3 class="h5"><a href="#"> Default Education {{$i+1}}</a></h3>
                                        <p> Default Education {{$i+1}}</p>
                                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
                @foreach($educations as $education)
                <div class="card card-style05">
                    <div class="card-img">
                        <img src="{{ (!empty($education->image)) ? url('upload/education/'.$education->image):url('upload/no_image.jpg') }}" alt="...">
                    </div>
                    <div class="card-body p-1-9">
                        <div class="d-flex">
                            {{-- <div class="flex-shrink-0">
                                <img src="{{ (!empty($study->image)) ? url('upload/study/'.$study->image):url('upload/no_image.jpg') }}" alt="...">
                            </div> --}}
                            <div class="flex-grow-1 ps-4">
                                <h3 class="h5"><a href="{{ route('single.education.page',$education->slug) }}">{{ $education->course_name ?? 'n/a'}}</a></h3>
                                <p>
                                    <?php $p_name_bn = strip_tags(html_entity_decode($education->course_materials)); ?>
                                    {{ Str::limit($p_name_bn, $limit = 20, $end = '. . .') }}
                                </p>
                                <a href="{{ route('single.education.page',$education->slug) }}">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========== OUR UMRAH PACKAGE ========== -->
    <section class="bg-light services_section">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase letter-spacing-4 d-block mb-2 font-weight-bolder">Our Umrah Package</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">We provide  best Product.</span></h2> --}}
            </div>

            <div class="service-carousel owl-carousel owl-theme">
                @if(count($umrahs) == 0)
                @for($i=1;$i < 3;$i++)
                <div class="card card-style-03 border-0 text-center primary-shadow">
                    <div class="position-relative">
                        <img src="{{ asset('frontend/img/service/service-01.jpg')}}" class="card-img-top" alt="...">
                        <div class="price-before"><span>Default Umrah {{$i+1}}</span></div>
                    </div>
                    <div class="card-body pt-5 pb-1-9 px-1-6 px-sm-2-3">
                        <h4>Default Umrah {{$i+1}}</h4>
                        <p class="ps-product__price sale text-danger font-weight-bolder">
                            ৳40500
                            <del class="text-secondary font-weight-bolder">৳ 45000</del>
                        </p>
                        <ul class="list-style1 ps-0 mb-1-9">
                            <li>
                                Default Umrah {{$i+1}}
                            </li>
                        </ul>
                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                    </div>
                </div>
                @endfor
                @endif
                @foreach($umrahs as $umrah)
                    <div class="card card-style-03 border-0 text-center primary-shadow">
                        <div class="position-relative">
                            <img src="{{ (!empty($umrah->image)) ? url('upload/umrahtour/'.$umrah->image):url('upload/no_image.jpg') }}" class="card-img-top" alt="...">
                            <div class="price-before"><span>{{ $umrah->name ?? 'N/A'}}</span></div>
                            <div class="card-price text-white">
                                <h5 class="display-36 small mb-0 text-white font-weight-bolder">৳{{ $umrah->total_regular_price-$umrah->total_discount_price ?? 'N/A'}} off</h5>
                                {{-- <span>off</span> --}}
                            </div>
                        </div>
                        
                        <div class="card-body pt-5 pb-1-9 px-1-6 px-sm-2-3">
                            <h4>{{ $umrah->name ?? 'N/A'}}</h4>
                            <h5>{{ $umrah->day_night ?? 'N/A'}}</h5>
                            <p class="ps-product__price sale text-danger font-weight-bolder">
                                ৳{{ $umrah->total_discount_price ?? 'n/a'}}
                                <del class="text-secondary font-weight-bolder">৳{{ $umrah->total_regular_price ?? 'N/A'}}</del>
                            </p>
                            <ul class="list-style1 ps-0 mb-1-9">
                                <?php $p_name_bn = strip_tags(html_entity_decode($umrah->description)); ?>
                                <p>{{ Str::limit($p_name_bn, $limit = 40, $end = '. . .') }}</p>
                            </ul>
                            <a href="{{ route('single.umrah.page',$umrah->slug) }}">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========== OUR TOUR PACKAGE ========== -->
    <section class="bg-light services_section">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase letter-spacing-4 d-block mb-2 font-weight-bolder">Our Tour Package</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">We provide  best Product.</span></h2> --}}
            </div>

            <div class="service-carousel owl-carousel owl-theme">
                @if(count($tours) == 0)
                @for($i=1;$i < 3;$i++)
                <div class="card card-style-03 border-0 text-center primary-shadow">
                    <div class="position-relative">
                        <img src="{{ asset('frontend/img/service/service-01.jpg')}}" class="card-img-top" alt="...">
                        <div class="price-before"><span>Default Tour {{$i+1}}</span></div>
                    </div>
                    <div class="card-body pt-5 pb-1-9 px-1-6 px-sm-2-3">
                        <h4>Default Tour {{$i+1}}</h4>
                        <p class="ps-product__price sale text-danger font-weight-bolder">
                            ৳40500
                            <del class="text-secondary font-weight-bolder">৳ 45000</del>
                        </p>
                        <ul class="list-style1 ps-0 mb-1-9">
                            <li>
                                Default Tour {{$i+1}}
                            </li>
                        </ul>
                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                    </div>
                </div>
                @endfor
                @endif
                @foreach($tours as $tour)
                    <div class="card card-style-03 border-0 text-center primary-shadow">
                        <div class="position-relative">
                            <img src="{{ (!empty($tour->image)) ? url('upload/umrahtour/'.$tour->image):url('upload/no_image.jpg') }}" class="card-img-top" alt="...">
                            <div class="price-before"><span>{{ $tour->name ?? 'N/A'}}</span></div>
                            <div class="card-price text-white">
                                <h5 class="display-36 small mb-0 text-white font-weight-bolder">৳{{ $tour->total_regular_price-$tour->total_discount_price ?? 'N/A'}} off</h5>
                                {{-- <span>off</span> --}}
                            </div>
                        </div>
                        
                        <div class="card-body pt-5 pb-1-9 px-1-6 px-sm-2-3">
                            <h4>{{ $tour->name ?? 'N/A'}}</h4>
                            <h5>{{ $tour->day_night ?? 'N/A'}}</h5>
                            <p class="ps-product__price sale text-danger font-weight-bolder">
                                ৳{{ $tour->total_discount_price ?? 'n/a'}}
                                <del class="text-secondary font-weight-bolder">৳{{ $tour->total_regular_price ?? 'N/A'}}</del>
                            </p>
                            <ul class="list-style1 ps-0 mb-1-9">
                                <?php $p_name_bn = strip_tags(html_entity_decode($tour->description)); ?>
                                <p>{{ Str::limit($p_name_bn, $limit = 40, $end = '. . .') }}</p>
                            </ul>
                            <a href="{{ route('single.tour.page',$tour->slug) }}">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========== TOURIST VISA ========== -->
    <section class="bg-light services_section">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="{{ route('tourist.all') }}" class="animate-charcter btn-style2 md text-white"><i class="fa fa-share"></i> View All</a>
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase letter-spacing-4 d-block mb-2 font-weight-bolder">TOURIST VISA</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">Connected Our TOURIST Community.</h2> --}}
            </div>
            <div class="service-carousel owl-carousel owl-theme">
                @if(count($tourist_visa) == 0)
                    @for($i=1;$i < 3;$i++)
                        <div class="card card-style05">
                            <div class="card-img">
                                <img src="{{ asset('frontend/img/service/service-01.jpg')}}" alt="...">
                            </div>
                            <div class="card-body p-1-9">
                                <div class="d-flex">
                                    <div class="flex-grow-1 ps-4">
                                        <h3 class="h5"><a href="#"> Default Tourist Visa {{$i+1}}</a></h3>
                                        <p> Default Tourist Visa {{$i+1}}</p>
                                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
                @foreach($tourist_visa as $visa)
                <div class="card card-style05">
                    <div class="card-img">
                        <img src="{{ (!empty($visa->t_image)) ? url('upload/visa/'.$visa->t_image):url('upload/no_image.jpg') }}" alt="...">
                    </div>
                    <div class="card-body p-1-9">
                        <div class="d-flex">
                            {{-- <div class="flex-shrink-0">
                                <img src="{{ (!empty($study->image)) ? url('upload/study/'.$study->image):url('upload/no_image.jpg') }}" alt="...">
                            </div> --}}
                            <div class="flex-grow-1 ps-4">
                                <h3 class="h5"><a href="{{ route('single.tourist.page',$visa->slug) }}">{{ $visa->t_country_name ?? 'n/a'}}</a></h3>
                                <p>
                                    <?php $p_name_bn = strip_tags(html_entity_decode($visa->t_documents)); ?>
                                    {{ Str::limit($p_name_bn, $limit = 20, $end = '. . .') }}
                                </p>
                                <a href="{{ route('single.tourist.page',$visa->slug) }}">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========== WORK PARMIT  VISA ========== -->
    <section class="bg-light services_section">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="{{ route('workparmit.all') }}" class="animate-charcter btn-style2 md text-white"><i class="fa fa-share"></i> View All</a>
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase letter-spacing-4 d-block mb-2 font-weight-bolder">WORK PERMIT VISA</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">We provide the best <span class="title-sm">WORK PARMIT VISA.</span></h2> --}}
            </div>

            <div class="service-carousel owl-carousel owl-theme">
                @if(count($workparmit_visa) == 0)
                    @for($i=1;$i < 3;$i++)
                        <div class="card card-style05">
                            <div class="card-img">
                                <img src="{{ asset('frontend/img/service/service-01.jpg')}}" alt="...">
                            </div>
                            <div class="card-body p-1-9">
                                <div class="d-flex">
                                    <div class="flex-grow-1 ps-4">
                                        <h3 class="h5"><a href="#"> Default Work Permit Visa {{$i+1}}</a></h3>
                                        <p> Default Work Permit Visa {{$i+1}}</p>
                                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
                @foreach($workparmit_visa as $visa)
                <div class="card card-style05">
                    <div class="card-img">
                        <img src="{{ (!empty($visa->image)) ? url('upload/visa/'.$visa->image):url('upload/no_image.jpg') }}" alt="...">
                    </div>
                    <div class="card-body p-1-9">
                        <div class="d-flex">
                            {{-- <div class="flex-shrink-0">
                                <img src="{{ (!empty($study->image)) ? url('upload/study/'.$study->image):url('upload/no_image.jpg') }}" alt="...">
                            </div> --}}
                            <div class="flex-grow-1 ps-4">
                                <h3 class="h5"><a href="{{ route('single.tourist.page',$visa->slug) }}">{{ $visa->country_name ?? 'n/a'}}</a></h3>
                                <p>
                                    <?php $p_name_bn = strip_tags(html_entity_decode($visa->documents)); ?>
                                    {{ Str::limit($p_name_bn, $limit = 20, $end = '. . .') }}
                                </p>
                                <a href="{{ route('single.tourist.page',$visa->slug) }}">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========== OUR SERVICES ========== -->
    <section class="bg-light services_section">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="{{ route('service.all') }}" class="animate-charcter btn-style2 md text-white"><i class="fa fa-share"></i> View All</a>
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase letter-spacing-4 d-block mb-2 font-weight-bolder">Our Service</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">We provide the best <span class="title-sm">Service.</span></h2> --}}
            </div>
            <div class="service-carousel owl-carousel owl-theme">
                @if(count($services) == 0)
                    @for($i=1;$i < 3;$i++)
                        <div class="card card-style05">
                            <div class="card-img">
                                <img src="{{ asset('frontend/img/service/service-01.jpg')}}" alt="...">
                            </div>
                            <div class="card-body p-1-9">
                                <div class="d-flex">
                                    <div class="flex-grow-1 ps-4">
                                        <h3 class="h5"><a href="#"> Default Services {{$i+1}}</a></h3>
                                        <p> Default Services {{$i+1}}</p>
                                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
                @foreach($services as $service)
                <div class="card card-style05">
                    <div class="card-img">
                        <img src="{{ (!empty($service->image)) ? url('upload/service/'.$service->image):url('upload/no_image.jpg') }}" alt="...">
                    </div>
                    <div class="card-body p-1-9">
                        <div class="d-flex">
                            {{-- <div class="flex-shrink-0">
                                <img src="{{ (!empty($study->image)) ? url('upload/study/'.$study->image):url('upload/no_image.jpg') }}" alt="...">
                            </div> --}}
                            <div class="flex-grow-1 ps-4">
                                <h3 class="h5"><a href="{{ route('single.service.page',$service->slug) }}">{{ $service->title ?? 'n/a'}}</a></h3>
                                <p>
                                    <?php $p_name_bn = strip_tags(html_entity_decode($service->description)); ?>
                                    {{ Str::limit($p_name_bn, $limit = 20, $end = '. . .') }}
                                </p>
                                <a href="{{ route('single.service.page',$service->slug) }}">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========== OUR PROUDCTS ========== -->
    <section class="bg-light services_section">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="{{ route('product.all') }}" class="animate-charcter btn-style2 md text-white"><i class="fa fa-share"></i> View All</a>
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase letter-spacing-4 d-block mb-2 font-weight-bolder">Our IT Product</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">We provide  best Product.</span></h2> --}}
            </div>

            <div class="service-carousel owl-carousel owl-theme">
                @if(count($products) == 0)
                @for($i=1;$i < 3;$i++)
                <div class="card card-style-03 border-0 text-center primary-shadow">
                    <div class="position-relative">
                        <img src="{{ asset('frontend/img/service/service-01.jpg')}}" class="card-img-top" alt="...">
                        <div class="price-before"><span>Default Product {{$i+1}}</span></div>
                    </div>
                    <div class="card-body pt-5 pb-1-9 px-1-6 px-sm-2-3">
                        <h4>Default Product {{$i+1}}</h4>
                        <p class="ps-product__price sale text-danger font-weight-bolder">
                            ৳40500
                            <del class="text-secondary font-weight-bolder">৳ 45000</del>
                        </p>
                        <ul class="list-style1 ps-0 mb-1-9">
                            <li>
                                Default Product {{$i+1}}
                            </li>
                        </ul>
                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                    </div>
                </div>
                @endfor
                @endif
                @foreach($products as $product)
                <div class="card card-style-03 border-0 text-center primary-shadow">
                    <div class="position-relative">
                        <img src="{{ (!empty($product->image)) ? url('upload/product/'.$product->image):url('upload/no_image.jpg') }}" class="card-img-top" alt="...">
                        <div class="price-before"><span>{{ $product->title ?? 'n/a'}}</span></div>
                        <div class="card-price text-white">
                            <h5 class="display-36 small mb-0 text-white font-weight-bolder">৳{{ $product->discount_price ?? 'n/a'}} off</h5>
                            {{-- <span>off</span> --}}
                        </div>
                    </div>
                    
                    <div class="card-body pt-5 pb-1-9 px-1-6 px-sm-2-3">
                        <h4>{{ $product->title ?? 'n/a'}}</h4>
                        <p class="ps-product__price sale text-danger font-weight-bolder">
                            ৳{{ $product->gross_price ?? 'n/a'}}
                            <del class="text-secondary font-weight-bolder">৳{{ $product->regular_price ?? 'n/a'}}</del>
                        </p>
                        <ul class="list-style1 ps-0 mb-1-9">
                            {{-- <p>{!! $product->short_description !!}</p> --}}
                        </ul>
                        <a href="{{ route('single.product.page',$product->slug) }}">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                    </div>
                    <a class="btn btn-success" href="{{ route('single.product.page',$product->slug) }}">Order Now<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                </div>
                @endforeach
            </div>

        </div>
    </section>

     <!-- ========== OUR PRICING ========== -->
     <section class="bg-light services_section">
        <div class="container">
            {{-- <a href="{{ route('product.all') }}" class="btn-style1 small btn btn-danger" style="float: right;"><i class="fa fa-share"></i> View All</a> --}}
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <H4 class="text-primary text-uppercase  letter-spacing-4 d-block mb-2 font-weight-bolder">Our Software Package</H4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">We provide  best Package</h2> --}}
            </div>

            <div class="service-carousel owl-carousel owl-theme">
                @if(count($pricings) == 0)
                @for($i=1;$i < 3;$i++)
                <div class="card card-style-03 border-0 text-center primary-shadow">
                    <div class="position-relative d-flex justify-content-center align-items-center">
                        <div class="price-value bg-danger rounded-circle text-center" style="width: 150px; height:150px;">
                            <span class="d-block" style="margin-top: 50px;">
                                <span class="amount text-white font-weight-bolder">1000</span><br>
                                <span class="duration text-white">একক ফী</span>
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-1-9 px-1-6 px-sm-2-3">
                        <h4>Default Pricing {{$i+1}}</h4>
                        <p class="ps-product__price sale text-danger font-weight-bolder">
                            ৳40500
                        </p>
                        <ul class="list-style1 ps-0 mb-1-9">
                            <li>
                                Default Pricing {{$i+1}}
                            </li>
                        </ul>
                        <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a>
                    </div>
                </div>
                @endfor
                @endif
                @foreach($pricings as $pricing)
                <div class="card card-style-03 border-0 text-center primary-shadow">
                    <div class="position-relative d-flex justify-content-center align-items-center">
                        @if($pricing->pricing_type == 1)
                            <div class="price-value bg-primary rounded-circle text-center" style="width: 150px; height:150px;">
                                <span class="d-block" style="margin-top: 50px;">
                                    <span class="amount text-white font-weight-bolder">৳{{ $pricing->total_price ?? 'n/a'}}</span><br>
                                    <span class="duration text-white">একক ফী</span>
                                </span>
                            </div>
                        @elseif($pricing->pricing_type == 2)
                            <div class="price-value bg-success rounded-circle text-center" style="width: 150px; height:150px;">
                                <span class="d-block" style="margin-top: 50px;">
                                    <span class="amount text-white font-weight-bolder">৳{{ $pricing->total_price ?? 'n/a'}}</span><br>
                                    <span class="duration text-white">একক ফী</span>
                                </span>
                            </div>
                        @elseif($pricing->pricing_type == 3)
                            <div class="price-value bg-info rounded-circle text-center" style="width: 150px; height:150px;">
                                <span class="d-block" style="margin-top: 50px;">
                                    <span class="amount text-white font-weight-bolder">৳{{ $pricing->total_price ?? 'n/a'}}</span><br>
                                    <span class="duration text-white">একক ফী</span>
                                </span>
                            </div>
                        @elseif($pricing->pricing_type == 4)
                            <div class="price-value bg-danger rounded-circle text-center" style="width: 150px; height:150px;">
                                <span class="d-block" style="margin-top: 50px;">
                                    <span class="amount text-white font-weight-bolder">৳{{ $pricing->total_price ?? 'n/a'}}</span><br>
                                    <span class="duration text-white">একক ফী</span>
                                </span>
                            </div>
                        @elseif($pricing->pricing_type == 5)
                            <div class="price-value bg-secondary rounded-circle text-center" style="width: 150px; height:150px;">
                                <span class="d-block" style="margin-top: 50px;">
                                    <span class="amount text-white font-weight-bolder">৳{{ $pricing->total_price ?? 'n/a'}}</span><br>
                                    <span class="duration text-white">একক ফী</span>
                                </span>
                            </div>
                        @endif
                        <div class="price-before"><span>{{ $pricing->title ?? 'n/a'}}</span></div>
                    </div>
                    
                    <div class="card-body pt-5 pb-1-9 px-1-6 px-sm-2-3">
                        <h4>{{ $pricing->title ?? 'n/a'}}</h4>
                        <p class="ps-product__price sale text-danger font-weight-bolder">
                            ৳{{ $pricing->single_price ?? 'n/a'}}
                        </p>
                        <ul class="list-style1 ps-0 mb-1-9">
                            <p>{!! $pricing->description !!}</p>
                        </ul>
                        {{-- <a href="#">Read More<i class="fa-solid fa-arrow-right ps-2 display-30 text-primary"></i></a> --}}
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- WHAT WE DO
    ================================================== -->
    {{-- <section class="bg-secondary">
        <div class="container position-relative z-index-9">
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <span class="text-primary text-uppercase small letter-spacing-4 d-block mb-2 font-weight-600">What We Do.</span>
                <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 text-white mb-lg-1-6">Services that help <span class="title-sm">you grow.</span></h2>
            </div>
            <div class="row g-5 mt-n1-9">
                <div class="col-md-6 col-lg-4 mt-1-9 wow fadeInUp" data-wow-delay="400ms">
                    <div class="card card-style06 border-0">
                        <div class="card-front">
                            <div class="card-inner">
                                <div class="mb-4 border-bottom pb-4 border-color-light-white">
                                    <img src="{{ asset('frontend/img/icons/18.png')}}" alt="..." class="mb-4">
                                    <h3 class="h5 mb-0"><a href="services.html" class="text-white">Web Development</a></h3>
                                </div>
                                <a href="#!" class="text-white">Read More<i class="fa-solid fa-arrow-right ps-3 text-primary"></i></a>
                            </div>
                            <div class="card-body pe-4 bg-primary">
                                <p class="text-white display-27 lh-base">We focus on the best practices for it solutions and services</p>
                                <a href="services.html" class="text-white">Read More<i class="fa-solid fa-arrow-right ps-3 text-white"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mt-1-9 wow fadeInUp" data-wow-delay="600ms">
                    <div class="card card-style06 border-0">
                        <div class="card-front">
                            <div class="card-inner">
                                <div class="mb-4 border-bottom pb-4 border-color-light-white">
                                    <img src="{{ asset('frontend/img/icons/19.png')}}" alt="..." class="mb-4">
                                    <h3 class="h5 mb-0"><a href="services.html" class="text-white">Branding Services</a></h3>
                                </div>
                                <a href="#!" class="text-white">Read More<i class="fa-solid fa-arrow-right ps-3 text-primary"></i></a>
                            </div>
                            <div class="card-body pe-4 bg-primary">
                                <p class="text-white display-27 lh-base">We focus on the best practices for it solutions and services</p>
                                <a href="services.html" class="text-white">Read More<i class="fa-solid fa-arrow-right ps-3 text-white"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mt-1-9 wow fadeInUp" data-wow-delay="800ms">
                    <div class="card card-style06 border-0">
                        <div class="card-front">
                            <div class="card-inner">
                                <div class="mb-4 border-bottom pb-4 border-color-light-white">
                                    <img src="{{ asset('frontend/img/icons/20.png')}}" alt="..." class="mb-4">
                                    <h3 class="h5 mb-0"><a href="services.html" class="text-white">Digital Marketing</a></h3>
                                </div>
                                <a href="#!" class="text-white">Read More<i class="fa-solid fa-arrow-right ps-3 text-primary"></i></a>
                            </div>
                            <div class="card-body pe-4 bg-primary">
                                <p class="text-white display-27 lh-base">We focus on the best practices for it solutions and services</p>
                                <a href="services.html" class="text-white">Read More<i class="fa-solid fa-arrow-right ps-3 text-white"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="position-absolute top-0 left-0 opacity2 shape-img-one">
            <img src="img/bg/bg-05.png" alt="...">
        </div>
    </section> --}}

    <!-- PORTFOLIO
    ================================================== -->
    {{-- <section class="portfolio-style02 py-0">
        <div class="pt-6 pb-16 pt-sm-10 pb-sm-20 pb-xl-22 portfolio-bg bg-primary">
            <div class="container position-relative z-index-9">
                <div class="row mt-n1-9">
                    <div class="col-sm-6 col-lg-3 mt-1-9 text-center wow fadeInUp" data-wow-delay="100ms">
                        <h3 class="h1 text-white"><span class="countup">15</span>k</h3>
                        <span class="text-white text-uppercase letter-spacing-2">Customers</span>
                    </div>
                    <div class="col-sm-6 col-lg-3 mt-1-9 text-center wow fadeInUp" data-wow-delay="200ms">
                        <h3 class="h1 text-white"><span class="countup">78</span>+</h3>
                        <span class="text-white text-uppercase letter-spacing-2">Branches</span>
                    </div>
                    <div class="col-sm-6 col-lg-3 mt-1-9 text-center wow fadeInUp" data-wow-delay="300ms">
                        <h3 class="h1 text-white"><span class="countup">3</span>k</h3>
                        <span class="text-white text-uppercase letter-spacing-2">Employees</span>
                    </div>
                    <div class="col-sm-6 col-lg-3 mt-1-9 text-center wow fadeInUp" data-wow-delay="400ms">
                        <h3 class="h1 text-white"><span class="countup">8</span>+</h3>
                        <span class="text-white text-uppercase letter-spacing-2">Countries</span>
                    </div>
                </div>
            </div>
            <div class="position-absolute top-0 end-0">
                <img src="{{ asset('frontend/img/content/shape-07.png')}}" alt="...">
            </div>
        </div>
        <div class="mt-n10">
            <div class="container-fluid px-xxl-10">
                <div class="row g-0 portfolio-gallery">
                    <div class="col-sm-6 col-lg-3 wow fadeIn" data-wow-delay="200ms" data-src="{{ asset('frontend/img/portfolio/01.jpg')}}" data-sub-html="<h4 class='text-white'>SEO & Content Writing</h4><p>XD Design</p>">
                        <div class="portfolio-box position-relative">
                            <img src="{{ asset('frontend/img/portfolio/01.jpg')}}" alt="...">
                            <div class="position-absolute top-0 left-0 w-100">
                                <div class="portfolio-inner">
                                    <div class="py-1-6 py-xxl-1-9 px-1-6 px-xxl-2-9 position-relative z-index-9">
                                        <span class="display-28"><a href="portfolio-details.html" class="text-white">XD Design</a></span>
                                        <h4 class="mb-0 mt-2 h5"><a href="portfolio-details.html" class="text-white portfolio-link">SEO & Content Writing</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 wow fadeIn" data-wow-delay="400ms" data-src="{{ asset('frontend/img/portfolio/02.jpg')}}" data-sub-html="<h4 class='text-white'>Business Analytics</h4><p>Business</p>">
                        <div class="portfolio-box position-relative">
                            <img src="{{ asset('frontend/img/portfolio/02.jpg')}}" alt="...">
                            <div class="position-absolute top-0 left-0 w-100">
                                <div class="portfolio-inner">
                                    <div class="py-1-6 py-xxl-1-9 px-1-6 px-xxl-2-9 position-relative z-index-9">
                                        <span class="display-28"><a href="portfolio-details.html" class="text-white">Business</a></span>
                                        <h4 class="mb-0 mt-2 h5"><a href="portfolio-details.html" class="text-white portfolio-link">Business Analytics</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 wow fadeIn" data-wow-delay="600ms" data-src="{{ asset('frontend/img/portfolio/03.jpg')}}" data-sub-html="<h4 class='text-white'>Web Development</h4><p>Figma Design</p>">
                        <div class="portfolio-box position-relative">
                            <img src="{{ asset('frontend/img/portfolio/03.jpg')}}" alt="...">
                            <div class="position-absolute top-0 left-0 w-100">
                                <div class="portfolio-inner">
                                    <div class="py-1-6 py-xxl-1-9 px-1-6 px-xxl-2-9 position-relative z-index-9">
                                        <span class="display-28"><a href="portfolio-details.html" class="text-white">Figma Design</a></span>
                                        <h4 class="mb-0 mt-2 h5"><a href="portfolio-details.html" class="text-white portfolio-link">Web Development</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 wow fadeIn" data-wow-delay="800ms" data-src="{{ asset('frontend/img/portfolio/04.jpg')}}" data-sub-html="<h4 class='text-white'>Market Research</h4><p>Business Consulting</p>">
                        <div class="portfolio-box position-relative">
                            <img src="{{ asset('frontend/img/portfolio/04.jpg')}}" alt="...">
                            <div class="position-absolute top-0 left-0 w-100">
                                <div class="portfolio-inner">
                                    <div class="py-1-6 py-xxl-1-9 px-1-6 px-xxl-2-9 position-relative z-index-9">
                                        <span class="display-28"><a href="portfolio-details.html" class="text-white">Business Consulting</a></span>
                                        <h4 class="mb-0 mt-2 h5"><a href="portfolio-details.html" class="text-white portfolio-link">Market Research</a></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- MISSION & VISION
    ================================================== -->
    {{-- <section class="clients-style3">
        <div class="container position-relative z-index-9">
            <div class="row align-items-center mt-n5">
                <div class="col-lg-6 mt-5">
                    <div class="position-relative z-index-9">
                        <div class="row">
                            <div class="col-6 wow fadeInDown" data-wow-delay="200ms">
                                <div class="image-hover position-relative overflow-hidden">
                                    <img src="{{ asset('frontend/img/content/mission-img.jpg')}}" alt="...">
                                </div>
                            </div>
                            <div class="col-6 wow fadeInUp" data-wow-delay="400ms">
                                <div class="image-hover position-relative overflow-hidden">
                                    <img src="{{ asset('frontend/img/content/vision-img.jpg')}}" alt="..." class="mt-1-9 mt-sm-10">
                                </div>
                            </div>
                        </div>
                        <div class="sm-box-wrapper z-index-9 wow fadeIn" data-wow-delay="600ms">
                            <div class="inner-box">
                                <h3 class="h4 mb-0 text-white">Vision & Mission</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 wow fadeIn" data-wow-delay="400ms">
                    <div class="ps-lg-6 ps-xl-12">
                        <div class="mb-1-9 position-relative">
                            <span class="text-primary text-uppercase small letter-spacing-4 d-block mb-2 font-weight-600 wow fadeInUp">Mission & Vision.</span>
                            <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6 wow fadeInUp" data-wow-delay="200ms">We help to achieve <span class="title-sm">mutual goals.</span></h2>
                        </div>
                        <p class="mb-1-9 wow fadeInUp" data-wow-delay="200ms">Our Vision and Mission are both empowering our group to achieve the objective. Our mission is to be the business' head specialist co-op organization focused on satisfying the most extreme to our clients.</p>
                        <div class="progress-style1 mb-2-9">
                            <div class="progress-text">
                                <div class="row">
                                    <div class="col-6 fw-bold">Business Analytics</div>
                                    <div class="col-6 text-end">80%</div>
                                </div>
                            </div>
                            <div class="custom-progress progress rounded-3 mb-4">
                                <div class="animated custom-bar progress-bar slideInLeft" style="width:80%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="10" role="progressbar"></div>
                            </div>
                            <div class="progress-text">
                                <div class="row">
                                    <div class="col-6 fw-bold">Marketing Research</div>
                                    <div class="col-6 text-end">75%</div>
                                </div>
                            </div>
                            <div class="custom-progress progress rounded-3">
                                <div class="animated custom-bar progress-bar slideInLeft" style="width:75%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="10" role="progressbar"></div>
                            </div>
                        </div>
                        <div class="clients-style3 wow fadeInUp" data-wow-delay="400ms">
                            <div class="clients-carousel owl-carousel owl-theme">
                                <div class="text-center clients-img">
                                    <img src="{{ asset('frontend/img/clients/01')}}.png" alt="..." class="hover-img">
                                    <img src="{{ asset('frontend/img/clients/01')}}.png" alt="..." class="main-img">
                                </div>
                                <div class="text-center clients-img">
                                    <img src="{{ asset('frontend/img/clients/02')}}.png" alt="..." class="hover-img">
                                    <img src="{{ asset('frontend/img/clients/02')}}.png" alt="..." class="main-img">
                                </div>
                                <div class="text-center clients-img">
                                    <img src="{{ asset('frontend/img/clients/03')}}.png" alt="..." class="hover-img">
                                    <img src="{{ asset('frontend/img/clients/03')}}.png" alt="..." class="main-img">
                                </div>
                                <div class="text-center clients-img">
                                    <img src="{{ asset('frontend/img/clients/04')}}.png" alt="..." class="hover-img">
                                    <img src="{{ asset('frontend/img/clients/04')}}.png" alt="..." class="main-img">
                                </div>
                                <div class="text-center clients-img">
                                    <img src="{{ asset('frontend/img/clients/01')}}.png" alt="..." class="hover-img">
                                    <img src="{{ asset('frontend/img/clients/01')}}.png" alt="..." class="main-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="position-absolute top-n5 right-n10 right-xxl-n5 ani-top-bottom d-none d-xl-block">
            <img src="{{ asset('frontend/img/content/shape-05.png')}}" alt="...">
        </div>
    </section> --}}

     <!-- COUNTER
        ================================================== -->
        <section class="pt-5">
            <div class="container">
                <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                    <h4 class="text-primary text-uppercase  letter-spacing-4 d-block mb-2 font-weight-bolder">Our Achievement</h4>
                    {{-- <p>Achievements are things you did that had a lasting impact for your company or client. It is a result that we personally bring about while fulfilling a particular role. Typically they are things that we created, built, designed, sold or initiated. In these all aspects we've happily achieved from our honorable customers. This is our company's achievement.</span></p> --}}
                </div>
                <div class="row text-center mt-n1-9 position-relative z-index-1">
                    @if(count($counters) == 0)
                    @for($i=0;$i < 4;$i++)
                    <div class="col-6 col-lg-3 mt-1-9">
                        <div class="counter-style2 wow fadeInUp" data-wow-delay="200ms">
                            <h3 class="display-14 display-sm-12 display-lg-10 text-primary"><span class="countup">0</span>k</h3>
                            <span class="fw-bold text-uppercase">Default Happy Clients {{$i+1}}</span>
                        </div>
                    </div>
                    @endfor
                    @endif
                    @foreach($counters as $counter)
                    <div class="col-6 col-lg-3 mt-1-9">
                        <div class="counter-style2 wow fadeInUp" data-wow-delay="200ms">
                            <h3 class="display-14 display-sm-12 display-lg-10 text-primary"><span class="countup">{{ $counter->counter_no ?? '0'}}</span></h3>
                            <span class="fw-bold text-uppercase">{{ $counter->title ?? 'n/a'}}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

    <!-- =========== TESTIMONIAL ========= -->
    <section class="bg-secondary pb-0 testimonials-style01 pt-7 pt-lg-10 pb-lg-16">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-9">
                    <div class="section-title text-center mb-1-9 mb-md-2-3 wow fadeIn" data-wow-delay="200ms">
                        <span class="sm-title">Testimonials</span>
                        <h2 class="mb-0 h1 text-white">What Our Clients Say</h2>
                    </div>
                    <div class="position-relative">
                        <div class="testimonial-carousel1 owl-carousel owl-theme wow fadeIn" data-wow-delay="400ms">
                            @foreach($testimonials as $testimonial)
                            <div class="position-relative mt-4">
                                <img src="{{ (!empty($testimonial->image)) ? url('upload/testimonial/'.$testimonial->image):url('upload/no_image.jpg') }}" class="rounded-circle mb-3" alt="...">
                                <p class="mb-1-9 display-25 display-lg-23 text-white">
                                    {!! $testimonial->description ?? 'n/a' !!}
                                </p>
                                <h4 class="h5 mb-0 text-primary">{{ $testimonial->name ?? 'n/a'}}</h4>
                                <p class="mb-0 text-white">{{ $testimonial->designation ?? 'n/a'}}</p>
                            </div>
                            @endforeach
                        </div>
                        <h6 class="testimonial-quote">“</h6>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('frontend/img/content/04.png')}}" class="position-absolute top-n10 right" alt="...">
        <img src="{{ asset('frontend/img/content/06.png')}}" class="position-absolute bottom-0 left" alt="...">
    </section>
   

    <!-- =========== TEAM ========== -->
    <section>
        <div class="container">
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase  letter-spacing-4 d-block mb-2 font-weight-bolder">Our Team</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">Meet our team <span class="font-weight-bolder">members.</span></h2> --}}
            </div>
            <div class="row gx-5 mt-n2-9 position-relative z-index-9">
                @foreach($teams as $team)
                <div class="col-md-6 col-xl-3 mt-2-9 wow fadeInUp" data-wow-delay="200ms">
                    <div class="card card-style07 rounded-0 border-0 primary-shadow">
                        <img src="{{ (!empty($team->image)) ? url('upload/team/'.$team->image):url('upload/no_image.jpg') }}" alt="...">
                        <div class="card-body p-4 text-center position-relative">
                            <div class="text-end team-content">
                                <h3 class="h5 mb-0">{{ $team->name ?? 'n/a'}}</h3>
                                <span class="display-30">{{ $team->designation ?? 'n/a'}}</span></span>
                            </div>
                            <div class="social-icons position-absolute bottom-0 left-0">
                                <span class="icon"><i class="fas fa-share-alt"></i></span>
                                <ul class="social-link1">
                                    <li><a href="{{ $team->facebook_url ?? 'n/a'}}" class="linkedin shadow-none"><i class="fab fa-facebook"></i></a></li>
                                    <li><a href="{{ $team->linkedin_url ?? 'n/a'}}" class="instagram shadow-none"><i class="fab fa-linkedin"></i></a></li>
                                    <li><a href="{{ $team->twitter_url ?? 'n/a'}}" class="pinterest shadow-none"><i class="fab fa-twitter"></i></a></li>
                                </ul>
                                <ul class="social-link2 d-flex">
                                    <li><a target="_blank" href="{{ $team->facebook_url ?? 'n/a'}}" class="youtube"><i class="fab fa-facebook"></i></a></li>
                                    <li><a target="_blank" href="{{ $team->linkedin_url ?? 'n/a'}}" class="facebook"><i class="fab fa-linkedin"></i></a></li>
                                    <li><a target="_blank" href="{{ $team->twitter_url ?? 'n/a'}}" class="twitter"><i class="fab fa-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="position-absolute bottom-10 left-5 ani-left-right d-none d-md-block">
            <img src="{{ asset('frontend/img/content/shape-05.png')}}" alt="...">
        </div>
    </section>

    <!-- =========== CONTACT ============ -->
    {{-- <section class="calltoaction-style01 p-0">
        <div class="container">
            <div class="right-container">
                <div class="row position-relative z-index-9 align-items-center py-1-9 py-lg-0">
                    <div class="col-6 col-lg-4 d-none d-lg-block wow fadeInLeft" data-wow-delay="100ms">
                        <img src="{{ asset('frontend/img/content/call-to-action-img.jpg')}}" alt="...">
                    </div>
                    <div class="col-lg-7 offset-md-1 offset-lg-0 offset-xl-1 wow fadeInRight" data-wow-delay="300ms">
                        <div class="d-flex ps-4 ps-md-0">
                            <div class="flex-shrink-0">
                                <i class="icon-map-pin display-14 text-white opacity3 mt-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-0 text-white">We promise we can help you! <span class="title-sm">(+1) 12 345-678.</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- =========== STUDY ABROAD  ============ -->
    <section>
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="{{ route('agent.all')}}" class="animate-charcter btn-style2 md text-white"><i class="fa fa-share"></i> View All</a>
                </div>
            </div>
            <div class="mb-2-9 mb-lg-6 position-relative text-center wow fadeInUp" data-wow-delay="200ms">
                <h4 class="text-primary text-uppercase  letter-spacing-4 d-block mb-2 font-weight-bolder">Our Happy Partners</h4>
                {{-- <h2 class="display-22 display-sm-18 display-md-16 display-lg-14 display-xl-10 mb-lg-1-6">Meet our team <span class="font-weight-bolder">members.</span></h2> --}}
            </div>
            <div class="row gx-5 mt-n2-9 position-relative z-index-9">
                @foreach($agents->take(4) as $agent)
                <div class="col-md-6 col-xl-3 mt-2-9 wow fadeInUp" data-wow-delay="200ms">
                    <div class="card card-style07 rounded-0 border-0 primary-shadow">
                        <img src="{{ (!empty($agent->image)) ? url('upload/user_images/'.$agent->image):url('upload/no_image.jpg') }}" alt="...">
                        <div class="card-body p-4 text-center position-relative">
                            {{-- <div class="text-end team-content">
                                <h3 class="h5 mb-0">{{ $agent->owner_name ?? 'n/a'}}</h3>
                                <span class="display-30">{{ $agent->designation ?? 'n/a'}}</span></span>
                            </div> --}}
                            <div class="text-center">
                                <h3 class="h5 mb-2">{{ $agent->owner_name ?? 'N/A'}}</h3>
                                <h3 class="h5 mb-2 display-30">{{ $agent->designation ?? 'N/A'}}</h3>
                                <a href="#" class="btn btn-success btn-block">Agent Area: {{ $agent->city_name ?? 'N/A'}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="position-absolute bottom-10 left-5 ani-left-right d-none d-md-block">
            <img src="{{ asset('frontend/img/content/shape-05.png')}}" alt="...">
        </div>
    </section>
@endsection
