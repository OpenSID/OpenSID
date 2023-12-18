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
    <!-- Sweetalert CSS-->
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    <!-- Modifikasi -->
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}" />
    <!-- Loading Lazy -->
    <link rel="stylesheet" href="<?= asset('js/progressive-image/progressive-image.css') ?>">
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

                @include('admin.layouts.partials.info')

                @yield('content')

            </section>
        </div>

        @include('admin.pengaturan.pengaturan_modal')

        @if ($notif['pengumuman'])
            @include('admin.layouts.components.pengumuman', $notif['pengumuman'])
        @endif

        @include('admin.layouts.partials.footer')

        @include('admin.layouts.partials.control_sidebar')

        <!-- Untuk menampilkan modal bootstrap umum -->
        <div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"></h4>
                    </div>
                    <div class="fetched-data"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var SITE_URL = "{{ site_url() }}";
        var BASE_URL = "{{ base_url() }}";
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
    <!-- Sweetalert JS -->
    <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- jquery validasi -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <!-- Loading Lazy -->
    <script src="<?= asset('js/progressive-image/progressive-image.js') ?>"></script>
    <!-- Modifikasi -->
    @if (config_item('demo_mode'))
        <!-- Website Demo -->
        <script src="{{ asset('js/demo.js') }}"></script>
    @endif
    @if (!setting('inspect_element'))
        <script src="{{ asset('js/disabled.min.js') }}"></script>
    @endif
    @stack('scripts')
    <script>
        $(document).ready(function() {
            $('ul.sidebar-menu').on('expanded.tree', function(e) {
                e.stopImmediatePropagation();
                setTimeout(scrollTampil($('li.treeview.menu-open')[0]), 500);
            });

            function scrollTampil(elem) {
                elem.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    </script>

    @if (isset($perbaharui_langganan) && !config_item('demo_mode'))
        <!-- cek status langganan -->
        <script type="text/javascript">
            var controller = '{{ $controller }}';
            $.ajax({
                    url: `<?= config_item('server_layanan') ?>/api/v1/pelanggan/pemesanan`,
                    headers: {
                        "Authorization": `Bearer {{ $setting->layanan_opendesa_token }}`,
                        "X-Requested-With": `XMLHttpRequest`,
                    },
                    type: 'Post',
                })
                .done(function(response) {
                    let data = {
                        body: response
                    }
                    $.ajax({
                        url: `${SITE_URL}pelanggan/pemesanan`,
                        type: 'Post',
                        dataType: 'json',
                        data: data,
                    }).done(function() {
                        if (controller == 'pelanggan') {
                            location.reload();
                        }
                    });
                })
        </script>
    @endif
</body>

</html>
