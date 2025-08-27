@php
  $footer_pages = App\Models\Menuitem::with(['subMenus.childMenus'])->whereNull('parent_id')->whereHas('get_menu', function($query){ $query->where('location','footer1')->where('sourch','page');})->orderby('position', 'asc')->get();
@endphp
<!-- Footer -->
<footer class="footer reveal">
  <div class="container">
    <div class="row">
      <div class="col-md-4 text-center text-md-start mb-4">
        <img src="{{ asset(get_setting('site_footer_logo')->value ?? 'upload/MCQ Logo.png') }}" height="50"><br>
        <div class="mt-3">
          <a target="_blank" href="{{ get_setting('facebook_url')->value ?? '' }}" class="text-dark mx-2"><i class="fab fa-facebook fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('twitter_url')->value ?? '' }}" class="text-dark mx-2"><i class="fab fa-twitter fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('linkedin_url')->value ?? '' }}" class="text-dark mx-2"><i class="fab fa-linkedin fa-lg"></i></a>
          <a target="_blank" href="{{ get_setting('whatsapp_url')->value ?? '' }}" class="text-dark mx-2"><i class="fab fa-whatsapp fa-lg"></i></a>
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
              <li class="">
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
  </div>
</footer>