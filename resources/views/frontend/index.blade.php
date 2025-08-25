@extends('layouts.frontend.app')
@section('content')
<!-- Hero Image Slider -->
<section id="heroSlider" class="carousel slide" data-bs-ride="carousel">

  <!-- Indicators -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <!-- Slides -->
  <div class="carousel-inner">
    @foreach($sliders as $slider)
    <div class="carousel-item active">
      <img src="{{ (!empty($slider->image)) ? url('upload/slider/'.$slider->image):url('upload/no_image.jpg') }}" class="d-block w-100 img-fluid" alt="Slide 1">
    </div>
    @endforeach
  </div>

  <!-- Controls -->
  <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
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
      <div class="col-md-6 mt-3">
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

<!-- MCQ Admission Section -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h4 class="mb-4 fw-bold reveal">MCQ এডমিশন এক প্লাটফর্মে</h4>
    <div class="row g-4">
      
      <!-- Card Item -->
      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-white bg-primary reveal">
          <h5 class="mb-0">বিসিএস প্রিলি</h5>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-white bg-success reveal">
          <h5 class="mb-0">প্রাইমারি</h5>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-white bg-danger reveal">
          <h5 class="mb-0">শিক্ষক নিবন্ধন</h5>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-dark bg-warning reveal">
          <h5 class="mb-0">ব্যাংক জব</h5>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-dark bg-info reveal">
          <h5 class="mb-0">ভার্সিটি এডমিশন</h5>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-white bg-secondary reveal">
          <h5 class="mb-0">মেডিকেল এডমিশন</h5>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-white bg-dark reveal">
          <h5 class="mb-0">নার্সিং এডমিশন</h5>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-dark bg-light border reveal">
          <h5 class="mb-0">জাতীয় বিশ্ববিদ্যালয়</h5>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="p-4 rounded shadow-sm h-100 text-white bg-primary reveal">
          <h5 class="mb-0">ডিপ্লোমা ইঞ্জিনিয়া</h5>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Teacher Section -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h4 class="mb-4 fw-bold">আমাদের মেন্টর</h4>

    <div id="teacherCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="row g-4">
            <div class="col-md-3 col-sm-6">
              <div class="card shadow-sm border-0 h-100">
                <img src="https://picsum.photos/300/300" class="card-img-top" alt="Teacher">
                <div class="card-body">
                  <h5 class="card-title mb-1">মোঃ রাকিব হাসান</h5>
                  <p class="text-muted mb-1">Assistant Professor</p>
                  <p class="fw-bold text-primary">পদার্থবিজ্ঞান</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="card shadow-sm border-0 h-100">
                <img src="https://picsum.photos/301/300" class="card-img-top" alt="Teacher">
                <div class="card-body">
                  <h5 class="card-title mb-1">সুমাইয়া আক্তার</h5>
                  <p class="text-muted mb-1">Lecturer</p>
                  <p class="fw-bold text-success">রসায়ন</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="card shadow-sm border-0 h-100">
                <img src="https://picsum.photos/302/300" class="card-img-top" alt="Teacher">
                <div class="card-body">
                  <h5 class="card-title mb-1">আব্দুল কাদের</h5>
                  <p class="text-muted mb-1">Senior Lecturer</p>
                  <p class="fw-bold text-danger">জীববিজ্ঞান</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="card shadow-sm border-0 h-100">
                <img src="https://picsum.photos/303/300" class="card-img-top" alt="Teacher">
                <div class="card-body">
                  <h5 class="card-title mb-1">মেহেদী হাসান</h5>
                  <p class="text-muted mb-1">Assistant Teacher</p>
                  <p class="fw-bold text-warning">গণিত</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="row g-4">
            <div class="col-md-3 col-sm-6">
              <div class="card shadow-sm border-0 h-100">
                <img src="https://picsum.photos/304/300" class="card-img-top" alt="Teacher">
                <div class="card-body">
                  <h5 class="card-title mb-1">তানজিলা আক্তার</h5>
                  <p class="text-muted mb-1">Lecturer</p>
                  <p class="fw-bold text-info">ইংরেজি</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="card shadow-sm border-0 h-100">
                <img src="https://picsum.photos/305/300" class="card-img-top" alt="Teacher">
                <div class="card-body">
                  <h5 class="card-title mb-1">আরিফুল ইসলাম</h5>
                  <p class="text-muted mb-1">Professor</p>
                  <p class="fw-bold text-dark">বাংলা</p>
                </div>
              </div>
            </div>
            <!-- প্রয়োজনে আরও teacher add করুন -->
          </div>
        </div>

      </div>

      <!-- Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#teacherCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#teacherCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
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
      @foreach($counters as $counter)
      <div class="col-md-3 reveal">
        <h2 class="counter" data-target="{{ $counter->counter_no ?? '0'}}">{{ $counter->counter_no ?? '0'}}</h2>
        <p>{{ $counter->title ?? ''}}</p>
      </div>
      @endforeach
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
@endsection
