@php
  $footer_pages = App\Models\Menuitem::with(['subMenus.childMenus'])->whereNull('parent_id')->whereHas('get_menu', function($query){ $query->where('location','footer1')->where('sourch','page');})->orderby('position', 'asc')->get();
@endphp
<!-- Footer -->
<style>
  .footer-area {
    background-color: #111d5e;
    position: relative;
    z-index: 1
}

.footer-area .shape {
    position: absolute;
    top: 50px;
    margin: auto;
    justify-content: center;
    align-items: center;
    display: flex;
    left: 0;
    right: 0;
    z-index: -1
}

.footer-logo-area {
    margin-bottom: 30px
}

.footer-logo-area img {
    margin-bottom: 20px
}

.footer-logo-area p {
    margin-bottom: 25px;
    color: #fff
}

.footer-logo-area .contact-list ul {
    padding-left: 0;
    margin-bottom: 0
}

.footer-logo-area .contact-list ul li {
    list-style-type: none;
    margin-bottom: 10px
}

.footer-logo-area .contact-list ul li:last-child {
    margin-bottom: 0
}

.footer-logo-area .contact-list ul li a {
    color: #fff
}

.footer-widjet {
    margin-bottom: 30px
}

.footer-widjet h3 {
    font-size: 22px;
    color: #fff;
    margin-bottom: 30px
}

.footer-widjet .list ul {
    padding-left: 0;
    margin-bottom: 0
}

.footer-widjet .list ul li {
    list-style-type: none;
    margin-bottom: 10px
}

.footer-widjet .list ul li:last-child {
    margin-bottom: 0
}

.footer-widjet .list ul li a {
    color: #fff;
    transition: all ease .5s
}

.footer-widjet .list ul li a:hover {
    color: #e32845
}
</style>
<footer class="footer footer-area reveal custom-footer" style="background-color:#111d5e;">
  <div class="container">
    <div class="row">
      <div class="col-md-4 text-center text-md-start mb-4">
        <img src="{{ asset(get_setting('site_footer_logo')->value ?? 'upload/MCQ Logo.png') }}" height="50"><br>
        <div class="mt-3">
          <a target="_blank" href="{{ get_setting('facebook_url')->value ?? '' }}" class="mx-2"><i class="fab fa-facebook fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('twitter_url')->value ?? '' }}" class="mx-2"><i class="fab fa-twitter fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('linkedin_url')->value ?? '' }}" class="mx-2"><i class="fab fa-linkedin fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('whatsapp_url')->value ?? '' }}" class="mx-2"><i class="fab fa-whatsapp fa-lg"></i></a>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <h5>Corporate office (Address):</h5>
          <ul class="footer-link mb-0 list-unstyled">
              <li class="mb-3">
                  <strong>Adress:</strong> <span class="opacity8">Malopara,Rajshahi</span>
              </li>
              <li class="mb-3">
                  <strong>Email:</strong> <span class="opacity8">{{ get_setting('email')->value ?? ''}}</span>
              </li>
              <li>
                  <strong>Phone:</strong> <span class="opacity8">01316017328</span>
              </li>
          </ul>
      </div>
      <div class="col-md-4 mb-4">
        <h5>Popular Pages</h5>
        <ul>
          @if(count($footer_pages) == 0)
              @for($i=1;$i < 5;$i++)
                  <li>
                      <a href="#" class=""><span>Default Page {{ $i }}</span></a>
                  </li>
              @endfor
          @endif
          @foreach($footer_pages->take(5) as $key=> $pages)
            <li>
                <a href="{{ route('footer.menu.page',$pages->url) }}" class=""><span> {{ $pages->title ?? ''}}</span></a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
     <div class="shape">
         <img class="img-fluid" src="{{ asset('upload/footer-shape.png') }}" alt="Image">
      </div>
  </div>
</footer>
