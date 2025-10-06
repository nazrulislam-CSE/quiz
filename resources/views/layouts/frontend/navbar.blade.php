<style>
  /* Navbar Background & Text Styling */
.navbar {
  background-color: #13557c !important; /* Primary Header Color */
  transition: background-color 0.3s ease;
}

.navbar .navbar-brand img {
  height: 40px;
}

/* Nav items spacing and styling */
.navbar-nav .nav-item {
  margin: 0 8px; /* gap between items */
}

/* Nav links default state */
.navbar-nav .nav-item a {
  color: #ffffff !important;
  font-weight: 600;
  transition: color 0.3s ease, background-color 0.3s ease;
  padding: 8px 14px;
  border-radius: 5px;
}

/* Hover effect on links */
.navbar-nav .nav-item a:hover {
  color: #4a90e2 !important;
  background-color: #ffffff;
  text-decoration: none;
}

/* Special Buttons (already styled, override if needed) */
.navbar-nav .btn {
  padding: 6px 14px;
  border-radius: 6px;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.navbar-nav .btn:hover {
  opacity: 0.9;
}

/* Responsive behavior tweak for mobile view */
.navbar-collapse {
  text-align: center;
}

</style>
@php
  $menuitems = App\Models\Menuitem::with(['subMenus.childMenus'])->whereNull('parent_id')->whereHas('get_menu', function($query){ $query->where('location','main_header');})->orderby('position', 'asc')->where('status',1)->get();
  $currentUrl = request()->url();
@endphp
<!-- Top Bar -->
<div class="bg-dark text-white py-2">
  <div class="container d-flex justify-content-between align-items-center">
    <small>যেকোনো প্রশ্নের জন্য কল করুন: <span class="text-success fw-bold">{{ get_setting('phone')->value ?? ''}}</span></small>
    <div>
      <a target="_blank" href="{{ get_setting('facebook_url')->value ?? '' }}" class="text-white mx-2"><i class="fab fa-facebook"></i></a>
      <a target="_blank" href="{{ get_setting('instagram_url')->value ?? '' }}" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
      <a target="_blank" href="{{ get_setting('twitter_url')->value ?? '' }}" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
      <a target="_blank" href="{{ get_setting('youtube_url')->value ?? '' }}" class="text-white mx-2"><i class="fab fa-youtube"></i></a>
      <a target="_blank" href="{{ get_setting('linkedin_url')->value ?? '' }}" class="text-white mx-2"><i class="fab fa-linkedin"></i></a>
      <a target="_blank" href="{{ get_setting('whatsapp_url')->value ?? '' }}" class="text-white mx-2"><i class="fab fa-whatsapp"></i></a>
    </div>
  </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('frontend.home') }}">
      <img src="{{ asset(get_setting('site_logo')->value ?? 'upload/MCQ Logo.png')}}" height="40">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center d-flex flex-column flex-lg-row gap-2">
        @if(count($menuitems) == 0)
            @for($i=1;$i < 6;$i++)
                <li>
                    <a class="nav-item text-decoration-none text-secondary font-weight-bold" href="#">Default Menu {{ $i }}</a>
                </li>
            @endfor
        @endif
        @foreach($menuitems as $key => $menuitem)
            <li class="nav-item {{ $loop->first ? 'active show' : '' }}">
                @if($menuitem->url == 'home-page')
                    <a class="nav-item text-decoration-none text-secondary font-weight-bold"
                      href="{{ route('frontend.home') }}">
                        {{ $menuitem->title ?? '' }}
                    </a>
                  @elseif($menuitem->url == 'our-branch')
                    <a class="btn bg-success text-white fw-bold"
                      href="{{ route('menu.page', $menuitem->url) }}">
                        {{ $menuitem->title ?? 'Our Branch' }}
                    </a>
                @elseif($menuitem->url == 'admission-info')
                    <a class="btn bg-info text-white fw-bold"
                      href="{{ route('menu.page', $menuitem->url) }}">
                        {{ $menuitem->title ?? 'Admission Info' }}
                    </a>
                @elseif($menuitem->url == 'program-list')
                    <a class="btn bg-info text-white fw-bold"
                      href="{{ route('menu.page', $menuitem->url) }}">
                        {{ $menuitem->title ?? 'Program List' }}
                    </a>
                @elseif($menuitem->url == 'demo-exam')
                    <a class="btn bg-success text-white fw-bold"
                      href="{{ route('menu.page', $menuitem->url) }}">
                        {{ $menuitem->title ?? 'Demo Exam' }}
                    </a>
                @elseif($menuitem->url == 'online-quiz')
                    <a class="btn bg-danger text-white fw-bold"
                      href="{{ route('menu.page', $menuitem->url) }}">
                        {{ $menuitem->title ?? 'Online Quiz' }}
                    </a>
                @else
                    <a class="nav-item text-decoration-none text-secondary font-weight-bold"
                      href="{{ route('menu.page', $menuitem->url) }}">
                        {{ $menuitem->title ?? '' }}
                    </a>
                @endif
            </li>
        @endforeach
        <!-- Navigation Buttons -->
        @guest
            <!-- Guest: Login & Register -->
            <li class="nav-item">
                <a class="btn btn-primary" href="{{ route('login') }}">লগইন</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-gradient" href="{{ route('register') }}">রেজিস্ট্রেশন</a>
            </li>
        @endguest

        @auth
            <!-- Authenticated User: Dashboard -->
            <li class="nav-item">
                <a class="btn btn-success" href="{{ route('user.user.home') }}">Dashboard</a>
            </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
