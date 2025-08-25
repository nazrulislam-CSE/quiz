@php
  $menuitems = App\Models\Menuitem::with(['subMenus.childMenus'])->whereNull('parent_id')->whereHas('get_menu', function($query){ $query->where('location','main_header');})->orderby('position', 'asc')->get();
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
        @foreach($menuitems as $key=> $menuitem)
          <li class="nav-item {{ $loop->first ? 'active show' : '' }}">
            @if($menuitem->url == 'home-page')
                <a class="nav-item text-decoration-none text-secondary font-weight-bold" href="{{ route('frontend.home') }}">{{ $menuitem->title ?? '' }}</a>
            @else
                <a class="nav-item text-decoration-none text-secondary font-weight-bold" href="{{ route('menu.page',$menuitem->url)}}">{{ $menuitem->title ?? '' }}</a>
            @endif
          </li>
        @endforeach
        <!-- Buttons -->
        <li class="nav-item">
          <a class="btn btn-primary" href="{{ route('login') }}">লগইন</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-gradient" href="{{ route('register') }}">রেজিস্ট্রেশন</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
