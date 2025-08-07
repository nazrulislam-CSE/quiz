<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<div class="sticky">
    <aside class="app-sidebar sidebar-scroll">
        <div class="main-sidebar-header active">
            <a class="desktop-logo logo-light active" href="{{ route('user.home') }}"><img src="{{ asset('dashboard/img/brand/logo.png') }}" class="main-logo" alt="logo"></a>
            <a class="desktop-logo logo-dark active" href="{{ route('user.home') }}"><img src="{{ asset('dashboard/img/brand/logo-white.png') }}" class="main-logo" alt="logo"></a>
            <a class="logo-icon mobile-logo icon-light active" href="{{ route('user.home') }}"><img src="{{ asset('dashboard/img/brand/favicon.png') }}" alt="logo"></a>
            <a class="logo-icon mobile-logo icon-dark active" href="{{ route('user.home') }}"><img src="{{ asset('dashboard/img/brand/favicon-white.png') }}" alt="logo"></a>
        </div>
        <div class="main-sidemenu">
            <div class="main-sidebar-loggedin">
                <div class="app-sidebar__user">
                    <div class="dropdown user-pro-body text-center">
                        <div class="user-pic">
                            <img src="{{ asset('dashboard/img/faces/6.jpg') }}" alt="user-img" class="rounded-circle mCS_img_loaded">
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
                        <a class="nav-link text-center m-2">
                            <i class="fe fe-settings"></i>
                        </a>
                    </li>
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Chat" aria-describedby="tooltip143427">
                        <a class="nav-link text-center m-2">
                            <i class="fe fe-mail"></i>
                        </a>
                    </li>
                    <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Followers">
                        <a class="nav-link text-center m-2">
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
                    <a class="side-menu__item {{ Request::is('user') ? 'active' : '' }}" href="{{ route('user.home') }}"><i class="side-menu__icon fe fe-airplay"></i><span class="side-menu__label">Dashboard</span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-box"></i><span class="side-menu__label">Sub Menu</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Menu</a></li>
                        <li><a class="slide-item" href="cards.html">Cards</a></li>
                        <li class="sub-slide">
                            <a class="slide-item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Mail</span><i class="sub-angle fe fe-chevron-down"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-side-menu__item" href="mail.html">Mail</a></li>
                                <li><a class="sub-side-menu__item" href="mail-compose.html">Mail Compose</a></li>
                                <li><a class="sub-side-menu__item" href="mail-read.html">Read-mail</a></li>
                                <li><a class="sub-side-menu__item" href="mail-settings.html">mail-settings</a></li>
                                <li><a class="sub-side-menu__item" href="chat.html">Chat</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-grid"></i><span class="side-menu__label">Submenus</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu__label1"><a href="javascript:void(0);">Submenus</a></li>
                        <li><a class="slide-item" href="javascript:void(0);">Level1</a></li>
                        <li class="sub-slide">
                            <a class="slide-item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Level-2</span><i class="sub-angle fe fe-chevron-down"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-side-menu__item" href="javascript:void(0);">Level-2.1</a></li>
                                <li class="sub-slide2">
                                    <a class="sub-side-menu__item" data-bs-toggle="sub-slide2" href="javascript:void(0);"><span class="sub-side-menu__label">Level-2.2</span><i class="sub-angle2 fe fe-chevron-down"></i></a>
                                    <ul class="sub-slide-menu1">
                                        <li><a class="sub-slide-item2" href="javascript:void(0);">Level-2.2.1</a></li>
                                        <li><a class="sub-slide-item2" href="javascript:void(0);">Level-2.2.2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
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