<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    @include('theme::commons.meta')
    <!-- </head> -->
</head>

<body onLoad="renderDate()">
    <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
    <div class="container" style="background-color: #f6f6f6;">
        <header id="header">
            @include('theme::partials.header')
        </header>
        <div id="navarea">
            @include('theme::partials.menu_head')
        </div>
        <div>
            @yield('layout')
        </div>
    </div>
    <footer id="footer">
        @include('theme::partials.footer_top')
        @include('theme::partials.footer_bottom')
    </footer>
    @include('theme::commons.meta_footer')
    <script type="text/javascript">
        function formatRupiah(angka, prefix = 'Rp ') {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '') + ',00';
        }
    </script>
    @stack('scripts')
</body>

</html>
