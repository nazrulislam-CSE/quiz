
@php
    $footer_pages = App\Models\Menuitem::with(['subMenus.childMenus'])->whereNull('parent_id')->whereHas('get_menu', function($query){ $query->where('location','footer1')->where('sourch','page');})->orderby('position', 'asc')->get();
@endphp
<footer class="position-relative pt-0">
    <div class="bg-primary py-1-9 mb-6 mb-xxl-10">
        <div class="container">
            <div class="row mt-n1-9 align-items-center">
                <div class="col-md-6 col-lg-4 mt-1-9 wow fadeIn" data-wow-delay="200ms">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('frontend/img/icons/07.png')}}" alt="...">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-0 text-white">Contact Us</p>
                            <h3 class="mb-0 h5 text-white">{{ get_setting('phone')->value ?? 'null'}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mt-1-9 text-start text-lg-center wow fadeIn" data-wow-delay="400ms">
                    <div class="footer-logo">
                        <a href="index.html"><img src="{{ asset(get_setting('site_footer_logo')->value ?? 'frontend/img/logos/footer-light-logo.png') }}" alt="..."></a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mt-1-9 wow fadeIn" data-wow-delay="600ms">
                    <div class="d-flex align-items-center text-lg-end">
                        <div class="flex-grow-1 ms-3 ms-lg-0 me-lg-3 order-2 order-lg-1">
                            <p class="mb-0 text-white">Mail Us</p>
                            <h3 class="mb-0 h5 text-white">{{ get_setting('email')->value ?? 'null'}}</h3>
                        </div>
                        <div class="flex-shrink-0 order-1 order-lg-2">
                            <img src="{{ asset('frontend/img/icons/08.png')}}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-n5 pb-6 pb-xxl-10">
            <div class="col-md-6 col-lg-4 mt-5 wow fadeIn" data-wow-delay="200ms">
                <h3 class="text-white h5 mb-1-9">About Company</h3>
                <h4 class="text-white mb-1-9 fw-light w-75 display-27 lh-base opacity8" style="text-align: justify;">
                    {{ get_setting('about')->value ?? 'null'}}
                </h4>
                <ul class="social-icon-style1">
                    <li>
                        <a target="_blank" href="{{ get_setting('facebook_url')->value ?? 'null' }}"><i class="fab fa-facebook-f"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ get_setting('twitter_url')->value ?? 'null' }}"><i class="fab fa-twitter"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ get_setting('youtube_url')->value ?? 'null' }}"><i class="fab fa-youtube"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ get_setting('linkedin_url')->value ?? 'null' }}"><i class="fab fa-linkedin-in"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ get_setting('whatsapp_url')->value ?? 'null' }}"><i class="fab fa-whatsapp"></i></a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 col-lg-3 mt-5 wow fadeIn" data-wow-delay="400ms">
                <div class="ps-0">
                    {{-- <h3 class="text-white h5 mb-1-9">Contacts</h3> --}}
                    <h4 class="text-white h5 mb-1-9">Head office (Address):</h4>
                    <ul class="footer-link mb-0 list-unstyled">
                        <li class="text-white mb-3">
                            <strong>Adress:</strong> <span class="opacity8">{{ get_setting('business_address')->value ?? 'null'}}</span>
                        </li>
                        <li class="text-white mb-3">
                            <strong>Email:</strong> <span class="opacity8">{{ get_setting('email')->value ?? 'null'}}</span>
                        </li>
                        <li class="text-white">
                            <strong>Phone:</strong> <span class="opacity8">{{ get_setting('phone')->value ?? 'null'}}</span>
                        </li>
                    </ul><hr>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mt-5  wow fadeIn" data-wow-delay="600ms">
                <h4 class="text-white h5 mb-1-9">Corporate office (Address):</h4>
                <ul class="footer-link mb-0 list-unstyled">
                    <li class="text-white mb-3">
                        <strong>Adress:</strong> <span class="opacity8">Malopara,Rajshahi</span>
                    </li>
                    <li class="text-white mb-3">
                        <strong>Email:</strong> <span class="opacity8">{{ get_setting('email')->value ?? 'null'}}</span>
                    </li>
                    <li class="text-white">
                        <strong>Phone:</strong> <span class="opacity8">01316017328</span>
                    </li>
                </ul>
                {{-- <h3 class="text-white h5 mb-1-9">Newsletter</h3>
                <p class="text-white opacity8 mb-3" style="text-align: justify;">Subscribe to our newsletter to receive updates on the latest news!</p>
                <form class="newsletter-form" action="{{ route('subs.store') }}" method="post" enctype="multipart/form-data" >
                    @csrf
                    <div class="quform-elements">
                        <div class="row">
                
                            <!-- Begin Text input element -->
                            <div class="col-md-12">
                                <div class="quform-element mb-0">
                                    <div class="quform-input">
                                        <input class="form-control" id="email" type="email" name="email" placeholder="Subscribe with us" required>
                                    </div>
                                </div>
                            </div>
                            <!-- End Text input element -->
                
                            <!-- Begin Submit button -->
                            <div class="col-md-12">
                                <div class="quform-submit-inner">
                                    <button class="btn btn-white text-white m-0" type="submit"><i class="fas fa-paper-plane"></i></button>
                                </div>
                                <div class="quform-loading-wrap"><span class="quform-loading"></span></div>
                            </div>
                            <!-- End Submit button -->
                        </div>
                
                    </div>
                
                </form> --}}
            </div>
            <div class="col-md-6 col-lg-2 mt-5 wow fadeIn" data-wow-delay="600ms">
                <h3 class="text-white h5 mb-1-9">Populer Pages</h3>
                <ul class="list-unstyled mb-0 text-light">
                    @if(count($footer_pages) == 0)
                        @for($i=1;$i < 5;$i++)
                            <li>
                                <a href="#" class="text-white"><span>Default Page {{ $i }}</span></a>
                            </li>
                        @endfor
                    @endif
                    @foreach($footer_pages->take(5) as $key=> $pages)
                        <li>
                            <a href="{{ route('footer.menu.page',$pages->url) }}" class="text-white"><span> {{ $pages->title ?? 'Null'}}</span></a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="py-4 border-top border-color-light-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 text-center wow fadeIn" data-wow-delay="100ms">
                    <p class="d-inline-block text-white mb-0">{{ get_setting('copy_right')->value ?? 'null'}} Powered by <a href="#!" class="text-primary text-white-hover">Speak Up Bd</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>