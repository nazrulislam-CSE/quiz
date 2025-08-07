<!DOCTYPE html>
<html lang="en">

<head>

    <!-- metas -->
    <meta charset="utf-8">
    <meta name="author" content="Website Design Templates">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords" content="Speak Up Bd - Largest Online Speak Up Bd in Bangladesh">
    <meta name="description" content="Speak Up Bd - Largest Online Speak Up Bd in Bangladesh">

    <!-- title  -->
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? '' }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('frontend/img/logos/favicon.png')}}">
    <link rel="apple-touch-icon" href="{{ asset('frontend/img/logos/apple-touch-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('frontend/img/logos/apple-touch-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('frontend/img/logos/apple-touch-icon-114x114.png')}}">

    <!-- plugins -->
    <link rel="stylesheet" href="{{ asset('frontend/css/plugins.css')}}">

    <!-- search css -->
    <link rel="stylesheet" href="{{ asset('frontend/search/search.css')}}">

    <!-- quform css -->
    <link rel="stylesheet" href="{{ asset('frontend/quform/css/base.css')}}">

    <!-- theme core css -->
    <link href="{{ asset('frontend/css/styles.css')}}" rel="stylesheet">

</head>

<body>

    <!-- PAGE LOADING
    ================================================== -->
    <div id="preloader"></div>

    <!-- MAIN WRAPPER
    ================================================== -->
    <div class="main-wrapper">

        <!-- HEADER
        ================================================== -->
        @include('layouts.frontend.navbar')

        <!-- MAIN CONTENT
        ================================================== -->
        @yield('content')

        <!-- FOOTER
        ================================================== -->
        @include('layouts.frontend.footer')

    </div>

    <!-- BUY TEMPLATE
    ================================================== -->
    {{-- <div class="buy-theme alt-font d-none d-lg-inline-block"><a href="https://themeforest.net/item/solutic-it-solutions-and-services-html-template/39778713" target="_blank"><i class="fas fa-cart-plus"></i><span>Buy Template</span></a></div>

    <div class="all-demo alt-font d-none d-lg-inline-block"><a href="https://themeforest.net/user/websitedesigntemplates" target="_blank"><i class="far fa-envelope"></i><span>Quick Question?</span></a></div> --}}

    <!-- SCROLL TO TOP
    ================================================== -->
    <a href="#!" class="scroll-to-top"><i class="fas fa-angle-up" aria-hidden="true"></i></a>

    <!-- all js include start -->

    <!-- jQuery -->
    <script src="{{ asset('frontend/js/jquery.min.js')}}"></script>

    <!-- popper js -->
    <script src="{{ asset('frontend/js/popper.min.js')}}"></script>

    <!-- bootstrap -->
    <script src="{{ asset('frontend/js/bootstrap.min.js')}}"></script>

    <!-- jquery -->
    <script src="{{ asset('frontend/js/core.min.js')}}"></script>

    <!-- Search -->
    <script src="{{ asset('frontend/search/search.js')}}"></script>

    <!-- custom scripts -->
    <script src="{{ asset('frontend/js/main.js')}}"></script>

    <!-- form plugins js -->
    <script src="{{ asset('frontend/quform/js/plugins.js')}}"></script>

    <!-- form scripts js -->
    <script src="{{ asset('frontend/quform/js/scripts.js')}}"></script>

    <!-- all js include end -->

    @stack('frontend-js')

</body>

</html>