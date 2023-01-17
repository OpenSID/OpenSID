<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        {{ $setting->admin_title . ' ' . ucwords($setting->sebutan_desa . ' ' . ($desa['nama_desa'] ?? '')) . get_dynamic_title_page_from_path() }}
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{{ base_url('rss.xml') }}" />
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/ionicons.min.css') }}" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/select2.min.css') }}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}" />
    <!-- AdminLTE Skins. -->
    <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}" />
    <!-- Modifikasi -->
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}" />
    @stack('css')
</head>

<body id="sidebar_collapse" class="{{ $setting->warna_tema_admin }} fixed sidebar-mini">
    <div class="wrapper">

        @include('admin.layouts.partials.header')

        @include('admin.layouts.partials.sidebar')

        <div class="content-wrapper">
            <section class="content-header">
                @yield('title')

                @include('admin.layouts.components.breadcrumb')
            </section>

            <section id="maincontent" class="content">
                @yield('content')
            </section>
        </div>

        @include('admin.layouts.components.pengaturan')

        @if ($notif['pengumuman'])
            @include('admin.layouts.components.pengumuman', $notif['pengumuman'])
        @endif

        @include('admin.profil.pengaturan_pengguna')

        @include('admin.layouts.partials.footer')

        @include('admin.layouts.partials.control_sidebar')

    </div>
    <script type="text/javascript">
        var SITE_URL = "{{ site_url() }}";
    </script>
    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    @if (config_item('csrf_protection'))
        <!-- CSRF Token -->
        <script type="text/javascript">
            var csrfParam = "{{ $token }}";
            var getCsrfToken = () => document.cookie.match(new RegExp(csrfParam + '=(\\w+)'))[1];
        </script>
        <script src="{{ asset('js/anti-csrf.js') }}"></script>
    @endif

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('bootstrap/js/select2.full.min.js') }}"></script>
    <!-- Slimscroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- jquery validasi -->
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <!-- jquery validasi -->
    <!-- Modifikasi -->
    <script src="{{ asset('js/admin.js') }}"></script>
    @if (config_item('demo_mode'))
        <!-- Website Demo -->
        <script src="{{ asset('js/demo.js') }}"></script>
    @endif
    @stack('scripts')
</body>

</html>
