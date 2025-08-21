<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? '' }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('dashboard/auth/css/frontend.css') }}">
</head>
<body>

<!-- Top Bar -->
<div class="bg-dark text-white py-2">
  <div class="container d-flex justify-content-between align-items-center">
    <small>যেকোনো প্রশ্নের জন্য কল করুন: <span class="text-success fw-bold">01316017328</span></small>
    <div>
      <a href="#" class="text-white mx-2"><i class="fab fa-facebook"></i></a>
      <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
      <a href="#" class="text-white mx-2"><i class="fab fa-youtube"></i></a>
      <a href="#" class="text-white mx-2"><i class="fab fa-linkedin"></i></a>
    </div>
  </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#"><img src="https://mcqadmission.com/admin/uploads/company/1753513214_dark_MCQ-LogoN.png" height="40"> MCQ Admission</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link active" href="#">হোম</a></li>
        <li class="nav-item"><a class="nav-link" href="#">আমাদের সম্পর্কে</a></li>
        <li class="nav-item"><a class="nav-link" href="#">যোগাযোগ</a></li>
        <li class="nav-item"><a class="btn btn-primary mx-2" href="{{  route('login') }}">লগইন</a></li>
        <li class="nav-item"><a class="btn btn-gradient" href="{{  route('register') }}">রেজিস্ট্রেশন</a></li>
      </ul>
    </div>
  </div>
</nav>
<!-- Hero Slider Section -->
<section id="heroSlider" class="carousel slide" data-bs-ride="carousel">
  
  <!-- Indicators / Dots -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <!-- Slides -->
  <div class="carousel-inner">

    <!-- Slide 1 -->
    <div class="carousel-item active">
      <div class="container py-5">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h1>এডমিশন প্রস্তুতির সকল সমাধান<br> এখন তোমার হাতের মুঠোয়</h1>
            <p class="mt-3">স্বপ্ন যদি হয় বিশ্ববিদ্যালয়, মেডিকেল, নার্সিং অথবা ডিপ্লোমা টেক – তাহলে আজই রেজিস্ট্রেশন করে শুরু করো তোমার প্রস্তুতি।</p>
            <a href="#" class="btn btn-primary mt-3">রেজিস্ট্রেশন করুন</a>
          </div>
          <div class="col-md-6 text-center">
            <img src="https://picsum.photos/id/1011/500/400" alt="Student" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-item">
      <div class="container py-5">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h1>হাজারো প্রশ্ন ব্যাংক<br> সঠিক অনুশীলনের সুযোগ</h1>
            <p class="mt-3">রিয়েল মডেল টেস্ট, প্রশ্নব্যাংক এবং অ্যানালাইসিস একসাথে পেতে এখনই জয়েন করুন।</p>
            <a href="#" class="btn btn-success mt-3">শুরু করুন</a>
          </div>
          <div class="col-md-6 text-center">
            <img src="https://picsum.photos/id/1012/500/400" alt="Exam" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-item">
      <div class="container py-5">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h1>অভিজ্ঞ শিক্ষক<br> আধুনিক অনলাইন প্ল্যাটফর্ম</h1>
            <p class="mt-3">সাফল্যের জন্য প্রতিটি ধাপে থাকছে গাইডলাইন ও পারফরম্যান্স রিপোর্ট।</p>
            <a href="#" class="btn btn-warning mt-3">আরও জানুন</a>
          </div>
          <div class="col-md-6 text-center">
            <img src="https://picsum.photos/id/1012/500/400" alt="Teacher" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Controls / Left-Right Arrows -->
  <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</section>



<!-- About Section -->
<section class="py-5 bg-light" id="about">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
      <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" 
     alt="Company Team" class="img-fluid rounded shadow">

      </div>
      <div class="col-md-6">
        <h2>আমাদের সম্পর্কে</h2>
        <p class="mt-3">MCQ Admission হলো একটি স্মার্ট অনলাইন প্ল্যাটফর্ম যেখানে ভর্তি পরীক্ষার জন্য প্রয়োজনীয় সকল উপকরণ একসাথে পাওয়া যায়। এখানে রয়েছে বিশাল প্রশ্ন ব্যাংক, রিয়েল টাইম মডেল টেস্ট, অভিজ্ঞ শিক্ষকের তৈরি কনটেন্ট এবং বিস্তারিত রেজাল্ট অ্যানালাইসিস।</p>
        <ul class="list-unstyled mt-3">
          <li>✔ বিশ্ববিদ্যালয় ভর্তি প্রস্তুতি</li>
          <li>✔ মেডিকেল ও নার্সিং কোচিং</li>
          <li>✔ ডিপ্লোমা ও অন্যান্য টেকনিক্যাল পরীক্ষা</li>
        </ul>
        <a href="#" class="btn btn-gradient mt-3">আরও জানুন</a>
      </div>
    </div>
  </div>
</section>


<!-- Category Section -->
<section class="py-5">
  <div class="container text-center">
    <h4 class="mb-4 reveal">MCQ এডমিশন এক প্লাটফর্মে</h4>
    <div class="row g-4">
      <div class="col-md-4"><div class="p-4 border rounded shadow-sm category-card reveal"><h5>ভার্সিটি এডমিশন</h5></div></div>
      <div class="col-md-4"><div class="p-4 border rounded shadow-sm category-card reveal"><h5>নার্সিং এডমিশন</h5></div></div>
      <div class="col-md-4"><div class="p-4 border rounded shadow-sm category-card reveal"><h5>মেডিকেল এডমিশন</h5></div></div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4 reveal">কেন MCQ Admission বেছে নিবেন?</h2>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="p-4 border rounded shadow-sm h-100 reveal">
          <i class="fa-solid fa-book-open fa-2x text-primary mb-3"></i>
          <h5>অসংখ্য প্রশ্ন ব্যাংক</h5>
          <p>ভার্সিটি, মেডিকেল ও নার্সিং ভর্তি পরীক্ষার বিশাল প্রশ্ন ব্যাংক।</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 border rounded shadow-sm h-100 reveal">
          <i class="fa-solid fa-laptop-code fa-2x text-success mb-3"></i>
          <h5>স্মার্ট অনলাইন পরীক্ষা</h5>
          <p>বাসায় বসেই রিয়েল টাইম মডেল টেস্ট দেওয়ার সুবিধা।</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 border rounded shadow-sm h-100 reveal">
          <i class="fa-solid fa-users fa-2x text-warning mb-3"></i>
          <h5>অভিজ্ঞ শিক্ষক</h5>
          <p>শীর্ষস্থানীয় শিক্ষকদের তৈরি কনটেন্ট ও প্রশ্ন সেট।</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 border rounded shadow-sm h-100 reveal">
          <i class="fa-solid fa-award fa-2x text-danger mb-3"></i>
          <h5>রেজাল্ট অ্যানালাইসিস</h5>
          <p>প্রতিটি পরীক্ষার পর বিস্তারিত পারফরম্যান্স রিপোর্ট।</p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Counter Section -->
<section class="py-5 text-white" style="background:#1d3557;">
  <div class="container text-center">
    <div class="row g-4">
      <div class="col-md-3 reveal">
        <h2 class="counter" data-target="50000">0</h2>
        <p>মোট শিক্ষার্থী</p>
      </div>
      <div class="col-md-3 reveal">
        <h2 class="counter" data-target="2000">0</h2>
        <p>মডেল টেস্ট</p>
      </div>
      <div class="col-md-3 reveal">
        <h2 class="counter" data-target="150">0</h2>
        <p>অভিজ্ঞ শিক্ষক</p>
      </div>
      <div class="col-md-3 reveal">
        <h2 class="counter" data-target="95">0%</h2>
        <p>সাফল্যের হার</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-5 reveal">শিক্ষার্থীদের মতামত</h2>
    <div class="row g-4">
      <div class="col-md-4 reveal">
        <div class="p-4 border rounded shadow-sm h-100">
          <p>"MCQ Admission এর প্রশ্নব্যাংক ব্যবহার করে আমি মেডিকেলে চান্স পেয়েছি। অসাধারণ প্লাটফর্ম।"</p>
          <h6 class="mt-3">- রাহুল ইসলাম, মেডিকেল</h6>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="p-4 border rounded shadow-sm h-100">
          <p>"অনলাইন মডেল টেস্ট ফিচার আমাকে আসল পরীক্ষার মতো অনুশীলন করতে সাহায্য করেছে।"</p>
          <h6 class="mt-3">- নুসরাত জাহান, ভার্সিটি</h6>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="p-4 border rounded shadow-sm h-100">
          <p>"রেজাল্ট অ্যানালাইসিস দেখে আমি আমার দুর্বলতা চিহ্নিত করে উন্নতি করতে পেরেছি।"</p>
          <h6 class="mt-3">- ফারহান হোসেন, নার্সিং</h6>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Call to Action -->
<section class="py-5 text-center text-white" style="background: linear-gradient(135deg, #6f42c1, #007bff);">
  <div class="container reveal">
    <h2>তোমার ভর্তি পরীক্ষার যাত্রা শুরু করো আজই</h2>
    <p class="mb-4">সেরা শিক্ষকদের গাইডলাইন ও স্মার্ট প্র্যাকটিস টুলস একসাথে।</p>
    <a href="#" class="btn btn-gradient btn-lg">এখনই রেজিস্ট্রেশন করুন</a>
  </div>
</section>





<!-- Newsletter -->
<section class="newsletter container text-center reveal">
  <h3>Subscribe Our Newsletter</h3>
  <p>Subscribed to our newsletter to get regular update about our courses.</p>
  <form class="d-flex justify-content-center mt-3">
    <input type="email" class="form-control w-50" placeholder="Your email here...">
    <button type="submit" class="btn btn-gradient">Subscribe</button>
  </form>
</section>

<!-- Footer -->
<footer class="footer reveal">
  <div class="container">
    <div class="row">
      <div class="col-md-4 text-center text-md-start mb-4">
        <img src="https://mcqadmission.com/admin/uploads/company/1753513214_dark_MCQ-LogoN.png" height="50"><br>
        <p class="mt-2">MCQ Admission</p>
        <div>
          <a href="#" class="text-dark mx-2"><i class="fab fa-facebook fa-lg"></i></a>
          <a href="#" class="text-dark mx-2"><i class="fab fa-instagram fa-lg"></i></a>
          <a href="#" class="text-dark mx-2"><i class="fab fa-x-twitter fa-lg"></i></a>
          <a href="#" class="text-dark mx-2"><i class="fab fa-linkedin fa-lg"></i></a>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <h5>সেবা সমূহ</h5>
        <ul>
          <li><a href="#">ভার্সিটি প্রস্তুতি</a></li>
          <li><a href="#">মেডিকেল প্রস্তুতি</a></li>
          <li><a href="#">নার্সিং প্রস্তুতি</a></li>
          <li><a href="#">জাতীয় বিশ্ববিদ্যালয় প্রস্তুতি</a></li>
        </ul>
      </div>
      <div class="col-md-4 mb-4">
        <h5>কোম্পানি</h5>
        <ul>
          <li><a href="#">আমাদের সম্পর্কে</a></li>
          <li><a href="#">শর্তাবলী</a></li>
          <li><a href="#">গোপনীয়তা নীতি</a></li>
          <li><a href="#">ক্যারিয়ার</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>

<!-- Scroll to Top Button -->
<button id="scrollTopBtn" class="btn btn-primary" title="Go to top">
  <i class="fas fa-chevron-up"></i>
</button>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scroll Reveal Script -->
<script>
document.addEventListener("scroll", function () {
  const reveals = document.querySelectorAll(".reveal");
  reveals.forEach(el => {
    const windowHeight = window.innerHeight;
    const revealTop = el.getBoundingClientRect().top;
    if (revealTop < windowHeight - 100) {
      el.classList.add("active");
    }
  });
});
</script>

<script>
// Counter Animation
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
  counter.innerText = '0';
  const updateCounter = () => {
    const target = +counter.getAttribute('data-target');
    const count = +counter.innerText;
    const increment = target / 200;
    if (count < target) {
      counter.innerText = Math.ceil(count + increment);
      setTimeout(updateCounter, 20);
    } else {
      counter.innerText = target;
    }
  };
  updateCounter();
});
</script>

<script>
  // Get the button
  const scrollTopBtn = document.getElementById("scrollTopBtn");

  // Show the button when user scrolls down 100px
  window.onscroll = function() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
      scrollTopBtn.style.display = "block";
    } else {
      scrollTopBtn.style.display = "none";
    }
  };

  // When button is clicked, scroll to top smoothly
  scrollTopBtn.addEventListener("click", function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
</script>


@stack('frontend-js')
</body>
</html>
