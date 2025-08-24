<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">
      <img src="{{ asset('upload/MCQ Logo.png') }}" height="40">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center d-flex flex-column flex-lg-row gap-2">
        <li class="nav-item"><a class="nav-link active" href="#">হোম</a></li>
        <li class="nav-item"><a class="nav-link" href="#">আমাদের সম্পর্কে</a></li>
        <li class="nav-item"><a class="nav-link" href="#">যোগাযোগ</a></li>
        
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
