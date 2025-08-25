@extends('layouts.frontend.app', [$pageTitle => 'Page Title'])
@section('content')
    @if($page->page_slug == 'apply-now')
        
    @elseif($page->page_slug == 'study-abroad')

    @elseif($page->page_slug == 'education')
     
    @elseif($page->page_slug == 'work-permit-visa')
      
    @elseif($page->page_slug == 'tourist-visa')
       
    @elseif($page->page_slug == 'our-services')
       
    @elseif($page->page_slug == 'it-solution')
  
    @elseif($page->page_slug == 'our-software-package')

    @elseif($page->page_slug == 'it-product')
   
    @elseif($page->page_slug == 'about-us')
      
    @elseif($page->page_slug == 'our-video')
  
    @elseif($page->page_slug == 'our-team')
       
    @elseif($page->page_slug == 'contact-us')

    @endif
    @push('frontend-js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script>
        // Initialize Select2 on the <select> element
        $( '#country' ).select2( {
            theme: 'bootstrap-5'
        } );
    </script>
    @endpush
@endsection