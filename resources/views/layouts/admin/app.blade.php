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
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Dashboard' }}</title>

    <!--- Favicon --->
    <link rel="icon" href="{{ asset('dashboard/img/brand/favicon.png') }}" type="image/x-icon" />

    <!-- Bootstrap css -->
    <link id="style" href="{{ asset('dashboard/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" />

    <!--- Style css --->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/plugins.css') }}" rel="stylesheet">

    <!--- Icons css --->
    <link href="{{ asset('dashboard/css/icons.css') }}" rel="stylesheet">

    <!--- Animations css --->
    <link href="{{ asset('dashboard/css/animate.css') }}" rel="stylesheet">
    

    @stack('css')
</head>

<body class="main-body app sidebar-mini ltr">
    @include('sweetalert::alert')
    <!-- Loader -->
    @include('layouts.admin.preloader')
    <!-- /Loader -->

    <!-- page -->
    <div class="page custom-index">

        <!-- main-header -->
        @include('layouts.admin.navbar')
        <!-- /main-header -->

        <!-- main-sidebar -->
        @include('layouts.admin.sidebar-left')
        <!-- main-sidebar -->

        <!-- main-content -->
        <div class="main-content app-content">

            <!-- container -->
            <div class="main-container container-fluid">

                <!-- breadcrumb -->
                {{-- @include('layouts.admin.breadcrumb') --}}
                <!-- /breadcrumb -->

                <!-- main-content-body -->
                @yield('content')
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /main-content -->

        <!--Sidebar-right-->
        @include('layouts.admin.sidebar-right')
        <!--/Sidebar-right-->

        <!-- Footer opened -->
        @include('layouts.admin.footer')
        <!-- Footer closed -->
    </div>
    <!-- page closed -->

    <!--- Back-to-top --->
    <a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>
    <!--- JQuery min js --->
    <script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
    <!--- Datepicker js --->
    <script src="{{ asset('dashboard/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--- Bootstrap Bundle js --->
    <script src="{{ asset('dashboard/plugins/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!--- Ionicons js --->
    <script src="{{ asset('dashboard/plugins/ionicons/ionicons.js') }}"></script>
    <!--- Chart bundle min js --->
    <script src="{{ asset('dashboard/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!--- JQuery sparkline js --->
    <script src="{{ asset('dashboard/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <!--- Internal Sampledata js --->
    <script src="{{ asset('dashboard/js/chart.flot.sampledata.js') }}"></script>
    <!--- Eva-icons js --->
    <script src="{{ asset('dashboard/js/eva-icons.min.js') }}"></script>
    <!--- Moment js --->
    <script src="{{ asset('dashboard/plugins/moment/moment.js') }}"></script>
    <!--- Perfect-scrollbar js --->
    <script src="{{ asset('dashboard/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/perfect-scrollbar/p-scroll.js') }}"></script>
    <!--- Sidebar js --->
    <script src="{{ asset('dashboard/plugins/side-menu/sidemenu.js') }}"></script>
    <!--- sticky js --->
    <script src="{{ asset('dashboard/js/sticky.js') }}"></script>
    <!-- right-sidebar js -->
    <script src="{{ asset('dashboard/plugins/sidebar/sidebar.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/sidebar/sidebar-custom.js') }}"></script>
    <!-- Morris js -->
    <script src="{{ asset('dashboard/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/morris.js/morris.min.js') }}"></script>
    <!--- Scripts js --->
    <script src="{{ asset('dashboard/js/script.js') }}"></script>
    <!--- Index js --->
    <script src="{{ asset('dashboard/js/index.js') }}"></script>
    <!--themecolor js-->
    <script src="{{ asset('dashboard/js/themecolor.js') }}"></script>
    <!--swither-styles js-->
    <script src="{{ asset('dashboard/js/swither-styles.js') }}"></script>
    <!--- Custom js --->
    <script src="{{ asset('dashboard/js/custom.js') }}"></script>
    <!--- Internal Inputtags js --->
	<script src="{{ asset('dashboard/plugins/inputtags/inputtags.js')}}"></script>
    <!-- INTERNAL Summernote Editor js -->
	<script src="{{ asset('dashboard/plugins/summernote-editor/summernote1.js')}}"></script>

    <!--- Select2.min js --->
	<script src="{{ asset('dashboard/plugins/select2/js/select2.min.js')}}"></script>


    <!-- DATA TABLE JS-->
    <script src="{{ asset('dashboard/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/js/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
	<script src="{{ asset('dashboard/js/table-data.js')}}"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function comingSoon() {
            Swal.fire({
                icon: 'warning',
                title: 'Working!',
                text: 'Please be patient.',
            });
        }
    </script>

    <!-- sweetalerat delete data -->
    <script type="text/javascript">
        $(function(){
            $(document).on('click','#delete',function(e){
                e.preventDefault();
                var link = $(this).attr("href");

                Swal.fire({
                title: 'Are you sure?',
                text: "Delete This Data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                  window.location.href = link
                  Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                  )
                }
              })
            });
        });
    </script>

    @stack('admin')
</body>

</html>
