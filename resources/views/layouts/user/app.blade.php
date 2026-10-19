<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'User Dashboard' }}</title>

    {{-- ============ Bootstrap 5 CSS ============ --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ============ Font Awesome ============ --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- ============ Dashboard CSS ============ --}}
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/dashboard.css') }}">
    <link rel="stylesheet" href="https://ecoachingdemo.speakupbd.com/dashboard/css/custom-animated.css">

    {{-- ============ Toastr CSS ============ --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- ============ Stack for child view styles (Select2 CSS etc.) ============ --}}
    @stack('styles')
</head>

<body>
    @include('layouts.user.navbar')

    <div class="container" style="padding-bottom:100px;">
        @yield('content')
    </div>

    @include('layouts.user.footer')

    {{-- ============ jQuery (MUST BE FIRST among JS) ============ --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- ============ Toastr JS ============ --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- ============ Bootstrap Bundle (includes Popper) ============ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ============ SweetAlert2 ============ --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ============ Stack for child view scripts (Select2 JS etc.) ============ --}}
    @stack('scripts')

</body>

</html>