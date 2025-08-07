<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<div class="sticky">
    <aside class="app-sidebar sidebar-scroll">
        <div class="main-sidebar-header active">
            <a class="desktop-logo logo-light active" href="{{ route('user.home') }}">
                <h3 class="text-uppercase font-weight-bolder text-light text-center">Quiz </h3>
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="main-sidebar-loggedin">
                <div class="app-sidebar__user">
                    <div class="dropdown user-pro-body text-center">
                        <div class="user-pic">
                            <img src="{{ (!empty( Auth::user()->image)) ? url('upload/user_images/'.Auth::user()->image):url('upload/no_image.jpg') }}" alt="user-img" class="rounded-circle mCS_img_loaded">
                        </div>
                        <div class="user-info">
                            <h6 class="mb-0 text-dark font-weight-bolder">{{ ucfirst(Auth::user()->name) }}</h6>
                            <span class="text-light app-sidebar__user-name text-sm">{{ ucfirst(Auth::user()->username) }}</span>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="sidebar-navs">
                <ul class="nav  nav-pills-circle">
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Settings" aria-describedby="tooltip365540">
                        <a href="{{ route('user.password.change') }}" class="nav-link text-center m-2">
                            <i class="fe fe-settings"></i>
                        </a>
                    </li>
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Followers">
                        <a href="{{ route('user.profile.view') }}"  class="nav-link text-center m-2">
                            <i class="fe fe-user"></i>
                        </a>
                    </li>
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Logout">
                        <a class="nav-link text-center m-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fe fe-power"></i>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
                    <a class="side-menu__item {{ Request::is('user') ? 'active' : '' }}" href="{{ route('user.home') }}"><i class="side-menu__icon fas fa-th-large"></i><span class="side-menu__label">Dashboard</span></a>
                </li>
                @if(Auth::check() && Auth::user()->status == 1)
                    <li class="slide {{ Request::is('user/notices*') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="side-menu__icon fas fa-building"></i>
                            <span class="side-menu__label">Notices</span>
                            <span class="badge bg-success side-badge"></span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu">
                            <li class="side-menu__label1"><a href="javascript:void(0);">Notices</a></li>
                            <li><a class="slide-item {{ Request::is('user/notice/index') ? 'active' : '' }}" href="{{ route('user.notice.index') }}">Notice List</a></li>
                        </ul>
                    </li>
                @else
                    <p class="text-danger font-weight-bolder p-3">You are not authorized to access this content.please contact super admin.</p>
                @endif
            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </aside>
</div>
