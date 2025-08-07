@php
    $menuitems = App\Models\Menuitem::with(['subMenus.childMenus'])->whereNull('parent_id')->whereHas('get_menu', function($query){ $query->where('location','main_header');})->orderby('position', 'asc')->get();
    $currentUrl = request()->url();
@endphp
<style>
    /* Media query for small devices */
    /* Default style for large devices */
    .small_device_partner {
        display: none !important;
    }

    /* Media query for small devices */
    @media only screen and (max-width: 600px) {
        .small_device_partner {
            display: block !important; /* or display: inline-block; depending on your layout */
            margin-bottom: 10px;
            text-align: center;
        }
    }
    @media only screen and (max-width: 991px) {
        .mobile-responsive-nav .mobile-responsive-menu.mean-container a.meanmenu-reveal span {
            background: #e32845;
            height: 4px;
            margin-top: 7px !important;
            border-radius: 0;
            position: relative;
            top: 8px;
        }
    }
    /* nav ul li a{
        color:white !important;
    } */
</style>
<header class="header-style2">

    <div class="top-bar bg-primary">
        <div class="container-fluid px-lg-1-6 px-xl-2-5 px-xxl-2-9">
            <div class="row">
                <div class="col-md-9 col-xs-12">
                    <div class="top-bar-info">
                        <ul class="ps-0">
                            <li><i class="ti-mobile"></i>{{ get_setting('phone')->value ?? 'null'}}</li>
                            <li class="d-none d-sm-inline-block"><i class="ti-email"></i>{{ get_setting('email')->value ?? 'null'}}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 d-none d-md-block">
                    <ul class="top-social-icon ps-0">
                        <li><a target="_blank" href="{{ get_setting('facebook_url')->value ?? 'null' }}"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a target="_blank" href="{{ get_setting('twitter_url')->value ?? 'null' }}"><i class="fab fa-twitter"></i></a></li>
                        <li><a target="_blank" href="{{ get_setting('instagram_url')->value ?? 'null'}}"><i class="fab fa-instagram"></i></a></li>
                        <li><a target="_blank" href="{{ get_setting('linkedin_url')->value ?? 'null'}}"><i class="fab fa-linkedin-in"></i></a></li>
                        <li><a target="_blank" href="{{ get_setting('whatsapp_url')->value ?? 'null'}}"><i class="fab fa-whatsapp"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="navbar-default border-bottom" style="background: #22C1C3;
background: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(253, 187, 45, 1) 100%);">

        <!-- start top search -->
        <div class="top-search bg-secondary">
            <div class="container-fluid px-lg-1-6 px-xl-2-5 px-xxl-2-9">
                <form class="search-form" action="#" method="GET" accept-charset="utf-8">
                    <div class="input-group">
                        <span class="input-group-addon cursor-pointer">
                            <button class="search-form_submit fas fa-search text-white" type="submit"></button>
                        </span>
                        <input type="text" class="search-form_input form-control" name="s" autocomplete="off" placeholder="Type & hit enter...">
                        <span class="input-group-addon close-search mt-1"><i class="fas fa-times"></i></span>
                    </div>
                </form>
            </div>
        </div>
        <!-- end top search -->

        <div class="container-fluid px-lg-1-6 px-xl-2-5 px-xxl-2-9">
            <div class="row align-items-center">
                <div class="col-12 col-lg-12">
                    <div class="menu_area alt-font">
                        <nav class="navbar navbar-expand-lg navbar-light p-0" style="background: #22C1C3;
background: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(253, 187, 45, 1) 100%);">
                            <div class="navbar-header navbar-header-custom">
                                <!-- start logo -->
                                <a href="{{ route('frontend.home') }}" class="navbar-brand logodefault">
                                    <img id="logo" src="{{ asset(get_setting('site_logo')->value ?? 'frontend/img/logos/logo.png')}}" alt="logo" />
                                </a>
                                <!-- end logo -->
                            </div>

                            <div class="navbar-toggler bg-primary"></div>

                            <!-- start menu area -->
                            <ul class="navbar-nav ms-auto" id="nav" style="display: none;">
                                @if(count($menuitems) == 0)
                                    @for($i=1;$i < 6;$i++)
                                        <li>
                                            <a href="#">Default Menu {{ $i }}</a>
                                        </li>
                                    @endfor
                                @endif
                                @foreach($menuitems as $key=> $menuitem)
                                <li class="{{ $loop->first ? 'active show' : '' }}">
                                    @if($menuitem->url == 'home-page')
                                        <a href="{{ route('frontend.home') }}">{{ $menuitem->title ?? 'Null' }}</a>
                                    @else
                                        <a href="{{ route('menu.page',$menuitem->url)}}">{{ $menuitem->title ?? 'Null' }}</a>
                                    @endif
                                    @if(count($menuitem->subMenus) > 0)
                                    <ul>
                                        @foreach($menuitem->subMenus as $subMenu)
                                        <li>
                                            <a href="{{ route('menu.page',$subMenu->url)}}">{{ $subMenu->title ?? 'Null' }}</a>
                                            @if(count($subMenu->childMenus) > 0)
                                            <ul>
                                                @foreach($subMenu->childMenus as $childMenu)
                                                    <li>
                                                        <a href="#">  {{ $childMenu->title ?? 'Null' }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                                <li><a class="small_device_partner animate-charcter btn-style2 md text-white" href="{{ route('login') }}">Login</a></li>
                                <li><a class="small_device_partner animate-charcter btn-style2 md text-white" href="{{ route('register') }}">Be a Partner</a></li>
                            </ul>
                            <!-- end menu area -->

                            <!-- start attribute navigation -->
                            <div class="attr-nav align-items-xl-center ms-xl-auto main-font">
                                <ul>
                                    {{-- <li class="search"><a href="#"><i class="fas fa-search"></i></a></li> --}}
                                    <li class="d-none d-xl-inline-block"><a href="{{ route('login') }}" class="animate-charcter btn-style2 md text-white">LOGIN</a></li>
                                    <li class="d-none d-xl-inline-block"><a href="{{ route('register') }}" class="animate-charcter btn-style2 md text-white">Be a Partner</a></li>
                                </ul>
                            </div>
                            <!-- end attribute navigation -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>