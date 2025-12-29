<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Fevicon -->
    <link rel="icon" href="{{ asset('assets/admin/images/logo.png') }}">
    <!-- Select2 css -->
    <link href="{{ asset('assets/admin/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Start css -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/flag-icon.min.css') }}" rel="stylesheet">
    @yield('styles')
    <!-- Plugins Custom Css -->
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/admin.css') }}" rel="stylesheet" type="text/css">
    <!-- End css -->
</head>
<body class="vertical-layout">
    <!-- Start Containerbar -->
    <div id="containerbar">
        <!-- Start Leftbar -->
        <div class="leftbar">
            <!-- Start Sidebar -->
            @include('admin.layouts.sidebar')
            <!-- End Sidebar -->
        </div>
        <!-- End Leftbar -->
        <!-- Start Rightbar -->
        <div class="rightbar">
            <!-- Start Topbar Mobile -->
            <div class="topbar-mobile">
                @include('admin.layouts.topbar-mobilebar')
            </div>
            <!-- Start Topbar -->
            @include('admin.layouts.topbar')
            <!-- End Topbar -->
            @include('admin.layouts.alert')
            @yield('content')
            <!-- Start Footerbar -->
            <div class="footerbar">
                <footer class="footer">
                    <p class="mb-0">© {{ date('Y') }} Pyae Baby Store - All Rights Reserved.</p>
                </footer>
            </div>
            <!-- End Footerbar -->
        </div>
        <!-- End Rightbar -->
    </div>
    <!-- End Containerbar -->
    <!-- Start js -->
    <script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vertical-menu.js') }}"></script>
    <!-- Select2 js -->
    <script src="{{ asset('assets/admin/plugins/select2/select2.min.js') }}"></script>
    <!-- Core js -->
    <script src="{{ asset('assets/admin/js/core.js') }}"></script>
    <script src="{{ asset('assets/admin/js/admin.js') }}"></script>
    @yield('js')
    <!-- End js -->
</body>
</html>
