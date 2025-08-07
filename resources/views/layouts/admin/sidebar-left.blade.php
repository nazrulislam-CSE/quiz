@php
    $sections = App\Models\Section::latest()->get();   
    $sliders = App\Models\Slider::latest()->get();   
    $pages = App\Models\Page::latest()->get();   
    $menuitems = App\Models\Menuitem::latest()->get();   
    $studys = App\Models\StudyAbroad::latest()->get();   
    $educations = App\Models\Education::latest()->get();   
    $visas = App\Models\Visa::latest()->get();   
    $teams = App\Models\Team::latest()->get();   
    $testimonials = App\Models\Testimonial::latest()->get();   
    $settings = App\Models\Setting::latest()->get();   
    $abouts = App\Models\About::latest()->get();   
    $partners_logo = App\Models\Partner::latest()->get();   
    $requestvisa = App\Models\RequestVisa::latest()->get();   
    $ielts = App\Models\Ielt::latest()->get();   
    $results = App\Models\Result::latest()->get();   
    $counters = App\Models\Counter::latest()->get();   
    $services = App\Models\Service::latest()->get();   
    $agents = App\Models\User::latest()->get();   
    $products = App\Models\Product::latest()->get();   
    $pricing = App\Models\Pricing::latest()->get();   
    $varrsitys = App\Models\Versity::latest()->get();   
    $videos = App\Models\Video::latest()->get();   
    $passports = App\Models\Passport::latest()->get();   
    $vouchers = App\Models\Voucher::where('admin_id', Auth::guard('admin')->user()->id)->latest()->get();   
    $refunds = App\Models\Refund::where('admin_id', Auth::guard('admin')->user()->id)->latest()->get();
    $orders = App\Models\Order::latest()->get();      
    $branchs = App\Models\Branch::latest()->get();      
@endphp
<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<div class="sticky">
    <aside class="app-sidebar sidebar-scroll">
        <div class="main-sidebar-header active">
            <a class="desktop-logo logo-light active" href="{{ route('user.home') }}">
               <h3 class="text-uppercase font-weight-bolder">Speak Up BD</h3>
            </a>
            {{-- <a class="desktop-logo logo-dark active" href="{{ route('user.home') }}"><img src="{{ asset('dashboard/img/brand/logo-white.png') }}" class="main-logo" alt="logo"></a>
            <a class="logo-icon mobile-logo icon-light active" href="{{ route('user.home') }}"><img src="{{ asset('dashboard/img/brand/favicon.png') }}" alt="logo"></a>
            <a class="logo-icon mobile-logo icon-dark active" href="{{ route('user.home') }}"><img src="{{ asset('dashboard/img/brand/favicon-white.png') }}" alt="logo"></a> --}}
        </div>
        <div class="main-sidemenu">
            <div class="main-sidebar-loggedin">
                <div class="app-sidebar__user">
                    <div class="dropdown user-pro-body text-center">
                        <div class="user-pic">
                            <img src="{{ (!empty( Auth::guard('admin')->user()->image)) ? url('upload/admin_images/'.Auth::guard('admin')->user()->image):url('upload/no_image.jpg') }}" alt="user-img" class="rounded-circle mCS_img_loaded">
                        </div>
                        <div class="user-info">
                            <h6 class=" mb-0 text-dark">{{ Auth::guard('admin')->user()->name }}</h6>
                            <span class="text-muted app-sidebar__user-name text-sm">{{ Auth::guard('admin')->user()->username }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar-navs">
                <ul class="nav  nav-pills-circle">
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Settings" aria-describedby="tooltip365540">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link text-center m-2">
                            <i class="fe fe-settings"></i>
                        </a>
                    </li>
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Followers">
                        <a href="{{ route('admin.profile.view') }}"  class="nav-link text-center m-2">
                            <i class="fe fe-user"></i>
                        </a>
                    </li>
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Logout">
                        <a class="nav-link text-center m-2" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fe fe-power"></i>
                        </a>

                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
            <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            <ul class="side-menu ">
                <li class="slide">
                    <a class="side-menu__item {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.admin.home') }}"><i class="side-menu__icon fas fa-th-large"></i><span class="side-menu__label">Dashboard</span></a>
                </li>

                {{-- <li class="slide {{ Request::is('admin/abouts*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-box"></i><span class="side-menu__label">About Us</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">About Us</a></li>
                        <li><a class="slide-item{{ Request::is('admin/abouts/create') ? 'active' : '' }}" href="{{ route('admin.about.create')}}">About Us Add</a></li>
                        <li><a class="slide-item{{ Request::is('admin/abouts/index') ? 'active' : '' }}" href="{{ route('admin.about.index')}}">About Us List</a></li>
                    </ul>
                </li> --}}

                <li class="slide {{ Request::is('admin/sections*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">Sections</span>
                        <span class="badge bg-success side-badge">{{ count($sections??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Sections</a></li>
                        <li><a class="slide-item {{ Request::is('admin/sections/create') ? 'active' : '' }}" href="{{ route('admin.section.create')}}">Section Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/sections/index') ? 'active' : '' }}" href="{{ route('admin.section.index')}}">Section List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/orders*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">Order</span>
                        <span class="badge bg-success side-badge">{{ count($orders ?? '0') }}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Order</a></li>
                        <li><a class="slide-item {{ Request::is('admin/order/index') ? 'active' : '' }}" href="{{ route('admin.order.index')}}">Order List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/branch*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">Branch</span>
                        <span class="badge bg-success side-badge">{{ count($branchs ?? '0') }}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Branch</a></li>
                        <li><a class="slide-item {{ Request::is('admin/branch/index') ? 'active' : '' }}" href="{{ route('admin.branch.index')}}">Branch List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/notices*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">Notice</span>
                        <span class="badge bg-success side-badge">0</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Notice</a></li>
                        <li><a class="slide-item {{ Request::is('admin/notice/create') ? 'active' : '' }}" href="{{ route('admin.notice.create')}}">Notice Send Agent</a></li>
                        <li><a class="slide-item {{ Request::is('admin/notice/index') ? 'active' : '' }}" href="{{ route('admin.notice.index')}}">Notice List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/umrahtour*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">Umrah & Tour</span>
                        <span class="badge bg-success side-badge">0</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Umrah & Tour</a></li>
                        <li><a class="slide-item {{ Request::is('admin/umrahtour/index') ? 'active' : '' }}" href="{{ route('admin.umrahtour.index')}}">Umrah & Tour List</a></li>
                        <li><a class="slide-item {{ Request::is('admin/umrahtour/order/list') ? 'active' : '' }}" href="{{ route('admin.umrahtour.order.list')}}">Order List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/supplier*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">Supplier</span>
                        <span class="badge bg-success side-badge">0</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Supplier</a></li>
                        <li><a class="slide-item {{ Request::is('admin/supplier/index') ? 'active' : '' }}" href="{{ route('admin.supplier.index')}}">Supplier List</a></li>
                        <li><a class="slide-item {{ Request::is('admin/supplier/invoice/index') ? 'active' : '' }}" href="{{ route('admin.supplier.invoice.index')}}">Invoice List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/partners*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Online Apply </span>
                        <span class="badge bg-success side-badge">{{ count($ielts??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Apply</a></li>
                        <li><a class="slide-item {{ Request::is('admin/partners/apply/list') ? 'active' : '' }}" href="{{ route('admin.apply.list')}}">Apply  List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/slider*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">Sliders</span>
                        <span class="badge bg-success side-badge">{{ count($sliders??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Slider</a></li>
                        <li><a class="slide-item {{ Request::is('admin/slider/create') ? 'active' : '' }}" href="{{ route('admin.slider.create')}}">Slider Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/slider/index') ? 'active' : '' }}" href="{{ route('admin.slider.index')}}">Slider List</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/about*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">About</span>
                        <span class="badge bg-success side-badge">{{ count($abouts ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">About</a></li>
                        <li><a class="slide-item {{ Request::is('admin/about/index') ? 'active' : '' }}" href="{{ route('admin.about.index')}}">About List</a></li>
                    </ul>
                </li>

                {{-- <li class="slide {{ Request::is('admin/categories*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-box"></i><span class="side-menu__label">Category</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Category</a></li>
                        <li><a class="slide-item {{ Request::is('admin/categories/create') ? 'active' : '' }}" href="{{ route('admin.category.create')}}">Category Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/categories/index') ? 'active' : '' }}" href="{{ route('admin.category.index')}}">Category List</a></li>
                    </ul>
                </li> --}}

                <li class="slide {{ Request::is('admin/pages*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Pages</span>
                        <span class="badge bg-success side-badge">{{ count($pages??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Pages</a></li>
                        <li><a class="slide-item {{ Request::is('admin/pages/create') ? 'active' : '' }}" href="{{ route('admin.page.create')}}">Pages Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/pages/index') ? 'active' : '' }}" href="{{ route('admin.page.index')}}">Pages List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/menus*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-vote-yea"></i>
                        <span class="side-menu__label">Menu Builder</span>
                        <span class="badge bg-success side-badge">{{ count($menuitems ??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                        </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Menu Builder</a></li>
                        <li><a class="slide-item {{ Request::is('admin/menuBuilder') ? 'active' : '' }}" href="{{ route('admin.menuBuilder')}}">Mange Menu Builder</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/study-abroad*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-user-graduate"></i>
                        <span class="side-menu__label">Study Abroad</span>
                        <span class="badge bg-success side-badge">{{ count($studys ??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Study Abroad</a></li>
                        <li><a class="slide-item {{ Request::is('admin/study-abroad/create') ? 'active' : '' }}" href="{{ route('admin.study.create')}}">Study Abroad Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/study-abroad/index') ? 'active' : '' }}" href="{{ route('admin.study.index') }}">Study Abroad List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/education*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-user-graduate"></i>
                        <span class="side-menu__label">Education</span>
                        <span class="badge bg-success side-badge">{{ count($educations ??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Education</a></li>
                        <li><a class="slide-item {{ Request::is('admin/education/create') ? 'active' : '' }}" href="{{ route('admin.education.create')}}">Education Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/education/index') ? 'active' : '' }}" href="{{ route('admin.education.index')}}">Education List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/visa*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-file-invoice-dollar"></i>
                        <span class="side-menu__label">Visa</span>
                        <span class="badge bg-success side-badge">{{ count($visas ??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Visa
                        </a></li>
                        <li><a class="slide-item {{ Request::is('admin/visa/create') ? 'active' : '' }}" href="{{ route('admin.visa.create')}}">Visa Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/visa/index') ? 'active' : '' }}" href="{{ route('admin.visa.index')}}">Visa List</a></li>
                        <li><a class="slide-item {{ Request::is('admin/visa/request/list') ? 'active' : '' }}" href="{{ route('admin.visa.request.list')}}">Request Visa List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/withdraw*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-shopping-bag"></i>
                        <span class="side-menu__label">Withdraw</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li>
                            <a class="slide-item {{ Request::is('admin/withdraw/index') ? 'active' : '' }}" href="{{ route('admin.withdraw.index') }}">Withdraw  List</a>
                        </li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/teams*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-users"></i>
                        <span class="side-menu__label">Teams</span>
                        <span class="badge bg-success side-badge">{{ count($teams ??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Teams</a></li>
                        <li><a class="slide-item {{ Request::is('admin/teams/create') ? 'active' : '' }}" href="{{ route('admin.team.create') }}">Team Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/teams/list') ? 'active' : '' }}" href="{{ route('admin.team.index') }}">Team List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/testimonials*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-users"></i>
                        <span class="side-menu__label">Testimonials</span>
                        <span class="badge bg-success side-badge">{{ count($testimonials ??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Testimonials</a></li>
                        <li><a class="slide-item {{ Request::is('admin/testimonials/create') ? 'active' : '' }}" href="{{ route('admin.testimonial.create') }}">Testimonials Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/testimonials/index') ? 'active' : '' }}" href="{{ route('admin.testimonial.index') }}">Testimonials List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/agents*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-users"></i>
                        <span class="side-menu__label">Agents</span>
                        <span class="badge bg-success side-badge">{{ count($agents ??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Agents</a></li>
                        <li><a class="slide-item {{ Request::is('admin/agent/index') ? 'active' : '' }}" href="{{ route('admin.agent.index') }}">Agent List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/partners*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cogs"></i>
                        <span class="side-menu__label">Partners</span>
                        <span class="badge bg-success side-badge">{{ count($partners_logo ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Partners</a></li>
                        <li><a class="slide-item {{ Request::is('admin/partner/create') ? 'active' : '' }}" href="{{ route('admin.partner.create') }}">Partner Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/partner/store') ? 'active' : '' }}" href="{{ route('admin.partner.index') }}">Partner List</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/result*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-user-graduate"></i>
                        <span class="side-menu__label">Results</span>
                        <span class="badge bg-success side-badge">{{ count($results ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Results</a></li>
                        <li><a class="slide-item {{ Request::is('admin/result/create') ? 'active' : '' }}" href="{{ route('admin.result.create') }}">Result Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/result/store') ? 'active' : '' }}" href="{{ route('admin.result.index') }}">Result List</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/counters*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Counters</span>
                        <span class="badge bg-success side-badge">{{ count($counters ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Counters</a></li>
                        <li><a class="slide-item {{ Request::is('admin/counter/create') ? 'active' : '' }}" href="{{ route('admin.counter.create') }}">Counter Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/counter/store') ? 'active' : '' }}" href="{{ route('admin.counter.index') }}">Counter List</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/service*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa fa-ambulance"></i>
                        <span class="side-menu__label">Services</span>
                        <span class="badge bg-success side-badge">{{ count($services ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Services</a></li>
                        <li><a class="slide-item {{ Request::is('admin/service/create') ? 'active' : '' }}" href="{{ route('admin.service.create') }}">Service Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/service/store') ? 'active' : '' }}" href="{{ route('admin.service.index') }}">Service List</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/products*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-shopping-bag"></i>
                        <span class="side-menu__label">Products</span>
                        <span class="badge bg-success side-badge">{{ count($products ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Products</a></li>
                        <li><a class="slide-item {{ Request::is('admin/product/create') ? 'active' : '' }}" href="{{ route('admin.product.create') }}">Product Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/product/store') ? 'active' : '' }}" href="{{ route('admin.product.index') }}">Product List</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/pricing*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-globe"></i>
                        <span class="side-menu__label">Pricing</span>
                        <span class="badge bg-success side-badge">{{ count($pricing ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Pricing</a></li>
                        <li><a class="slide-item {{ Request::is('admin/pricing/create') ? 'active' : '' }}" href="{{ route('admin.pricing.create') }}">Pricing Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/pricing/store') ? 'active' : '' }}" href="{{ route('admin.pricing.index') }}">Pricing List</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/varsity*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-user-graduate"></i>
                        <span class="side-menu__label">Varsity</span>
                        <span class="badge bg-success side-badge">{{ count($varrsitys ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Varsity</a></li>
                        <li><a class="slide-item {{ Request::is('admin/varsity/create') ? 'active' : '' }}" href="{{ route('admin.varsity.create') }}">Varsity Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/varsity/index') ? 'active' : '' }}" href="{{ route('admin.varsity.index') }}">Varsity List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/video*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-video"></i>
                        <span class="side-menu__label">Videos</span>
                        <span class="badge bg-success side-badge">{{ count($videos ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Video</a></li>
                        <li><a class="slide-item {{ Request::is('admin/video/create') ? 'active' : '' }}" href="{{ route('admin.video.create') }}">Video Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/video/index') ? 'active' : '' }}" href="{{ route('admin.video.index') }}">Video List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/passport*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-passport"></i>
                        <span class="side-menu__label">Passport</span>
                        <span class="badge bg-success side-badge">{{ count($passports ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Passport</a></li>
                        <li><a class="slide-item {{ Request::is('admin/passport/create') ? 'active' : '' }}" href="{{ route('admin.passport.create') }}">Passport Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/passport/index') ? 'active' : '' }}" href="{{ route('admin.passport.index') }}">Passport List</a></li>
                        <li><a class="slide-item {{ Request::is('admin/passport/success') ? 'active' : '' }}" href="{{ route('admin.passport.success') }}">Success List</a></li>
                        <li><a class="slide-item {{ Request::is('admin/passport/received') ? 'active' : '' }}" href="{{ route('admin.passport.received') }}">Received List</a></li>
                        <li><a class="slide-item {{ Request::is('admin/passport/back') ? 'active' : '' }}" href="{{ route('admin.passport.back') }}">Back List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/voucher*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Voucher</span>
                        <span class="badge bg-success side-badge">{{ count($vouchers ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Voucher</a></li>
                        <li><a class="slide-item {{ Request::is('admin/voucher/create') ? 'active' : '' }}" href="{{ route('admin.voucher.create') }}">Voucher Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/voucher/index') ? 'active' : '' }}" href="{{ route('admin.voucher.index') }}">Voucher List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/refund*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Refund Money</span>
                        <span class="badge bg-success side-badge">{{ count($refunds ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Refund Money</a></li>
                        <li><a class="slide-item {{ Request::is('admin/refund/create') ? 'active' : '' }}" href="{{ route('admin.refund.create') }}">Refund Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/refund/index') ? 'active' : '' }}" href="{{ route('admin.refund.index') }}">Refund List</a></li>
                    </ul>
                </li>

                


                {{-- <li class="slide {{ Request::is('admin/visa*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cogs"></i>
                        <span class="side-menu__label">Request Visa</span>
                        <span class="badge bg-success side-badge">{{ count($requestvisa ?? '0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Request Visa</a></li>
                        <li><a class="slide-item {{ Request::is('admin/visa/request/list') ? 'active' : '' }}" href="{{ route('admin.visa.request.list') }}">Request Visa List</a></li>
                    </ul>
                </li> --}}

                <li class="slide {{ Request::is('admin/settings*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cog"></i>
                        <span class="side-menu__label">Advance Settings</span>
                        <span class="badge bg-success side-badge">{{ count($settings ??'0')}}</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Advance Settings</a></li>
                        <li><a class="slide-item {{ Request::is('admin/settings/index') ? 'active' : '' }}" href="{{ route('admin.settings.index')}}">Manage Setting</a></li>
                    </ul>
                </li>

            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </aside>
</div>