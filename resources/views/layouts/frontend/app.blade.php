<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/frontend.css') }}">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
</head>

<body>

  @include('layouts.frontend.navbar')

  @yield('content')

  @include('layouts.frontend.footer')

  <!-- Scroll to Top Button -->
  <button id="scrollTopBtn" class="btn btn-primary" title="Go to top">
      <i class="fas fa-chevron-up"></i>
  </button>


  <!-- jQuery (required for Slick) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scroll Reveal Script -->
  <script>
      document.addEventListener("scroll", function() {
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
          window.scrollTo({
              top: 0,
              behavior: 'smooth'
          });
      });
  </script>

  <!-- Slick Carousel JS -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <script>
      // Initialize Slick Carousel for Teachers
      $(document).ready(function() {
          $('.teacher-slider').slick({
              dots: true,
              infinite: true,
              speed: 300,
              slidesToShow: 4,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 3000,
              arrows: true,
              responsive: [{
                      breakpoint: 1024,
                      settings: {
                          slidesToShow: 3,
                          slidesToScroll: 1,
                          infinite: true,
                          dots: true
                      }
                  },
                  {
                      breakpoint: 768,
                      settings: {
                          slidesToShow: 2,
                          slidesToScroll: 1
                      }
                  },
                  {
                      breakpoint: 576,
                      settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                      }
                  }
              ]
          });

          // Initialize Slick Carousel for Students
          $('.student-slider').slick({
              dots: true,
              infinite: true,
              speed: 300,
              slidesToShow: 4,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 4000,
              arrows: true,
              responsive: [{
                      breakpoint: 1024,
                      settings: {
                          slidesToShow: 2,
                          slidesToScroll: 1,
                          infinite: true,
                          dots: true
                      }
                  },
                  {
                      breakpoint: 768,
                      settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                      }
                  }
              ]
          });

          // Counter animation
          const counters = document.querySelectorAll('.counter');
          const speed = 200; // The lower the slower

          counters.forEach(counter => {
              const target = +counter.getAttribute('data-target');
              const count = +counter.innerText;
              const increment = Math.ceil(target / speed);

              if (count < target) {
                  const updateCount = () => {
                      const currentCount = +counter.innerText;

                      if (currentCount < target) {
                          counter.innerText = Math.min(currentCount + increment, target);
                          setTimeout(updateCount, 1);
                      } else {
                          counter.innerText = target;
                      }
                  };

                  updateCount();
              }
          });
      });
  </script>

  @stack('frontend-js')
</body>

</html>
