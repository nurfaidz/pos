<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mail Cafe System </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ url('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ url('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/font-awesome/css/font-awesome.min.css') }}" />

    <link rel="stylesheet" href="{{ url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ url('assets/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.png') }}" />

    @yield('page_styles')
    @yield('meta_header')
</head>

<body>
    <div class="container-scroller">

        @include('partials.navbar')

        <div class="container-fluid page-body-wrapper">

            @include('partials.sidebar')

            <div class="main-panel">
                @yield('main')
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{ url('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ url('assets/js/validator.min.js') }}"></script>
    <script src="{{ url('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/vendors/progressbar.js/progressbar.min.js') }}"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ url('assets/js/off-canvas.js') }}"></script>
    <script src="{{ url('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ url('assets/js/template.js') }}"></script>
    <script src="{{ url('assets/js/settings.js') }}"></script>
    <script src="{{ url('assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ url('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/dashboard.js') }}"></script>
    <script src="{{ url('assets/js/transaction.js') }}"></script>
    <script src="{{ url('assets/js/Chart.roundedBarCharts.js') }}"></script>
    <script src="{{ url('assets/js/alerts.js') }}"></script>
    <script src="{{ url('assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ url('assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- End custom js for this page-->
    <!-- dataTables -->
    <script src="{{ url('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ url('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ url('assets/js/data-table.js') }}"></script>

    @yield('page_js')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function preview(selector, temporaryFile, width = 200) {
            $(selector).empty();
            $(selector).append(`<img src="${window.URL.createObjectURL(temporaryFile)}" width="${width}">`);
        }
    </script>
    @yield('page_script')
</body>

</html>
