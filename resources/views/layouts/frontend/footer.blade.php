<style>
  .custom-footer {
    background-color: #061850;
    background-image: radial-gradient(
      circle at 30% 30%, 
      rgba(255, 255, 255, 0.03) 0%, 
      rgba(255, 255, 255, 0.01) 100%
    ), 
    radial-gradient(
      circle at 70% 60%, 
      rgba(255, 255, 255, 0.03) 0%, 
      rgba(255, 255, 255, 0.01) 100%
    );
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    color: white;
    padding: 60px 0;
    position: relative;
  }

  .footer-link li {
    color: #fff;
    margin-bottom: 10px;
  }

  .footer-link li strong {
    color: #fff;
  }

  .footer a {
    color: #fff;
    text-decoration: none;
  }

  .footer a:hover {
    text-decoration: underline;
  }

  .footer h5 {
    color: white;
    font-weight: bold;
    margin-bottom: 20px;
  }
</style>

<footer class="footer reveal custom-footer">
  <div class="container">
    <div class="row">
      <!-- Left -->
      <div class="col-md-4 text-center text-md-start mb-4">
        <img src="{{ asset(get_setting('site_footer_logo')->value ?? 'upload/MCQ Logo.png') }}" height="50"><br>
        <div class="mt-3">
          <a target="_blank" href="{{ get_setting('facebook_url')->value ?? '' }}" class="text-light mx-2"><i class="fab fa-facebook fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('twitter_url')->value ?? '' }}" class="text-light mx-2"><i class="fab fa-twitter fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('linkedin_url')->value ?? '' }}" class="text-light mx-2"><i class="fab fa-linkedin fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('whatsapp_url')->value ?? '' }}" class="text-light mx-2"><i class="fab fa-whatsapp fa-lg"></i></a>
        </div>
      </div>

      <!-- Middle -->
      <div class="col-md-4 mb-4">
        <h5>Corporate Office</h5>
        <ul class="footer-link list-unstyled">
          <li><strong>Address:</strong> Malopara, Rajshahi</li>
          <li><strong>Email:</strong> {{ get_setting('email')->value ?? ''}}</li>
          <li><strong>Phone:</strong> 01316017328</li>
        </ul>
      </div>

      <!-- Right -->
      <div class="col-md-4 mb-4">
        <h5>Popular Pages</h5>
        <ul class="footer-link list-unstyled">
          @if(count($footer_pages) == 0)
            @for($i=1;$i < 5;$i++)
              <li><a href="#">Default Page {{ $i }}</a></li>
            @endfor
          @endif
          @foreach($footer_pages->take(5) as $pages)
            <li>
              <a href="{{ route('footer.menu.page',$pages->url) }}">
                {{ $pages->title ?? '' }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</footer>
