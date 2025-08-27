@php
 
@endphp
<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<div class="sticky">
    <aside class="app-sidebar sidebar-scroll">
        <div class="main-sidebar-header active">
            <a class="desktop-logo logo-light active" href="{{ route('user.home') }}">
               <h4 class="text-uppercase font-weight-bolder">Quiz Application</h4>
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="main-sidebar-loggedin">
                <div class="app-sidebar__user">
                    <div class="dropdown user-pro-body text-center">
                        <div class="user-pic">
                            <img src="{{ (!empty( Auth::guard('admin')->user()->image)) ? url('upload/admin_images/'.Auth::guard('admin')->user()->image):url('upload/avater.png') }}" alt="user-img" class="rounded-circle mCS_img_loaded">
                        </div>
                        <div class="user-info">
                            <h6 class=" mb-0 text-dark">{{ Auth::guard('admin')->user()->name }}</h6>
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
                <li class="slide {{ Request::is('admin/admission*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cog"></i>
                        <span class="side-menu__label">Admission</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Admission</a></li>
                        <li><a class="slide-item {{ Request::is('admin/admission/index') ? 'active' : '' }}" href="{{ route('admin.admission.index')}}">Manage Admission</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/department*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cog"></i>
                        <span class="side-menu__label">Department</span>
                        <span class="badge bg-success side-badge"></span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Department</a></li>
                        <li><a class="slide-item {{ Request::is('admin/department/index') ? 'active' : '' }}" href="{{ route('admin.department.index')}}">Manage Department</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/subject*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cog"></i>
                        <span class="side-menu__label">Subject</span>
                        <span class="badge bg-success side-badge"></span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Subject</a></li>
                        <li><a class="slide-item {{ Request::is('admin/subject/index') ? 'active' : '' }}" href="{{ route('admin.subject.index')}}">Manage Subject</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/topic*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cog"></i>
                        <span class="side-menu__label">Topics</span>
                        <span class="badge bg-success side-badge"></span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Topics</a></li>
                        <li><a class="slide-item {{ Request::is('admin/topic/index') ? 'active' : '' }}" href="{{ route('admin.topic.index')}}">Manage Topics</a></li>
                    </ul>
                </li>
                  <li class="slide {{ Request::is('admin/mcq*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cog"></i>
                        <span class="side-menu__label">MCQ</span>
                        <span class="badge bg-success side-badge"></span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">MCQ</a></li>
                        <li><a class="slide-item {{ Request::is('admin/mcq/index') ? 'active' : '' }}" href="{{ route('admin.mcq.index')}}">Manage MCQ</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/slider*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-store-alt"></i>
                        <span class="side-menu__label">Sliders</span>
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
                        <span class="badge bg-success side-badge"></span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">About</a></li>
                        <li><a class="slide-item {{ Request::is('admin/about/index') ? 'active' : '' }}" href="{{ route('admin.about.index')}}">About List</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/pages*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Pages</span>
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
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                        </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Menu Builder</a></li>
                        <li><a class="slide-item {{ Request::is('admin/menuBuilder') ? 'active' : '' }}" href="{{ route('admin.menuBuilder')}}">Mange Menu Builder</a></li>
                    </ul>
                </li>
                <li class="slide {{ Request::is('admin/counters*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Counters</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Counters</a></li>
                        <li><a class="slide-item {{ Request::is('admin/counter/create') ? 'active' : '' }}" href="{{ route('admin.counter.create') }}">Counter Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/counter/index') ? 'active' : '' }}" href="{{ route('admin.counter.index') }}">Counter List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/features*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Feature</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Feature</a></li>
                        <li><a class="slide-item {{ Request::is('admin/feature/create') ? 'active' : '' }}" href="{{ route('admin.feature.create') }}">Feature Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/feature/index') ? 'active' : '' }}" href="{{ route('admin.feature.index') }}">Feature List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/teacher*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Teacher</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Teacher</a></li>
                        <li><a class="slide-item {{ Request::is('admin/teacher/create') ? 'active' : '' }}" href="{{ route('admin.teacher.create') }}">Teacher Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/teacher/index') ? 'active' : '' }}" href="{{ route('admin.teacher.index') }}">Teacher List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/student*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-building"></i>
                        <span class="side-menu__label">Student</span>
                        <i class="angle fe fe-chevron-down hor-angle"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Student</a></li>
                        <li><a class="slide-item {{ Request::is('admin/student/create') ? 'active' : '' }}" href="{{ route('admin.student.create') }}">Student Add</a></li>
                        <li><a class="slide-item {{ Request::is('admin/student/index') ? 'active' : '' }}" href="{{ route('admin.student.index') }}">Student List</a></li>
                    </ul>
                </li>

                <li class="slide {{ Request::is('admin/settings*') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="side-menu__icon fas fa-cog"></i>
                        <span class="side-menu__label">Advance Settings</span>
                        <span class="badge bg-success side-badge"></span>
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