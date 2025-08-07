<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />

    <!-- Title -->
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Signin / Signup' }}</title>

    <!--- Favicon --->
    <link rel="icon" href="{{ asset('dashboard/img/brand/favicon.png') }}" type="image/x-icon" />

    <!-- Bootstrap css -->
    <link href="{{ asset('dashboard/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" id="style" />

    <!--- Icons css --->
    <link href="{{ asset('dashboard/css/icons.css') }}" rel="stylesheet">

    <!--- Style css --->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/plugins.css') }}" rel="stylesheet">

    <!--- Animations css --->
    <link href="{{ asset('dashboard/css/animate.css') }}" rel="stylesheet">

</head>

<body class="main-body bg-light  login-img">

    <!-- Loader -->
    @include('layouts.user.preloader')
    <!-- /Loader -->

    <!-- page -->
    <div class="page">
        <div class="my-auto page page-h">
            <div class="main-signin-wrapper">
                <div class="row mx-auto justify-content-center">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <!-- page closed -->
    <!-- /main-signin-wrapper -->

    <!--- JQuery min js --->
    <script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>

    <!--- Bootstrap Bundle js --->
    <script src="{{ asset('dashboard/plugins/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!--- Ionicons js --->
    <script src="{{ asset('dashboard/plugins/ionicons/ionicons.js') }}"></script>

    <!--- Moment js --->
    <script src="{{ asset('dashboard/plugins/moment/moment.js') }}"></script>

    <!--- Eva-icons js --->
    <script src="{{ asset('dashboard/js/eva-icons.min.js') }}"></script>

    <!--themecolor js-->
    <script src="{{ asset('dashboard/js/themecolor.js') }}"></script>

    <!--- Custom js --->
    <script src="{{ asset('dashboard/js/custom.js') }}"></script>

</body>

</html>
