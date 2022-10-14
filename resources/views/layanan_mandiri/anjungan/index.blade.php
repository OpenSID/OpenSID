<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Anjungan Desa Mandiri</title>
    {{-- Font --}}
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/ionicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/anjungan.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="{{ asset('css/swiper.min.css') }}" />
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">
    @stack('css')
</head>
<body>
    <header>
        <div class="flex center">
            <img src="{{ gambar_desa($desa['logo']) }}" class="logo" alt="Logo">
            <div class="box-anjungan">
                <h4 class="tulisan-anjungan">Anjungan Desa Mandiri</h4>
                <p class="tulisan-desa">{{ ucwords($setting->sebutan_desa . ' ' . $desa['nama_desa'] . ' Kec. ' . $desa['nama_kecamatan'] . ' Kab. ' . $desa['nama_kabupaten']) }}</p>
            </div>
        </div>
        <div class="flex center">
            <a href="{{ site_url('/') }}" title="Home"><img src="{{ asset('images/anjungan/home.svg') }}" class="menu-header" alt="Icon Menu"></a>
            <a data-value="{{ site_url('kehadiran') }}" class="popup" title="Kehadiran Perangkat Desa"><img src="{{ asset('images/anjungan/kehadiran.svg') }}" class="menu-header" alt="Icon Menu"></a>
            <a href="#" title="Buku Tamu"><img src="{{ asset('images/anjungan/tamu.svg') }}" class="menu-header" alt="Icon Menu"></a>
            <a href="#" title="Daftar Perangkat Desa"><img src="{{ asset('images/anjungan/perangkat.svg') }}" class="menu-header" alt="Icon Menu"></a>
            <a href="#" title="Mode Gelap"><img src="{{ asset('images/anjungan/mode.svg') }}" class="menu-header" alt="Icon Menu"></a>
        </div>
    </header>
    <section class="flex center content">
        <iframe class="background" width="100%" height="100%" src="https://www.youtube.com/embed/PuxiuH-YUF4?autoplay=1&loop=1&rel=0&showinfo=0&color=white&iv_load_policy=3" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <div class="berita">
            <h4 class="tulisan-berita">Berita Desa</h4>
            <div class="menu-berita">
                <ul role="tablist" class="nav nav-tabs custom-tabs">
                    <li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home" href="#terkini">Terbaru</a></li>
                    <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#populer">Populer</a></li>
                </ul>
                <div class="tab-content">
                    <div id="terkini" class="tab-pane fade in active" role="tabpanel">
                        @foreach ($arsip_terkini as $arsip)
                            <div class="box-berita">
                                <a data-value="{{ site_url('artikel/'.buat_slug($arsip)) }}" class="popup">
                                    @if (is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar']))
                                        <img width="25%" style="float:left; margin:0 8px 4px 0;" class="img-berita" src="{{ base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip[gambar]) }}"/>
                                    @else
                                        <img width="25%" style="float:left; margin:0 8px 4px 0;" class="img-berita" src="{{ base_url('assets/images/404-image-not-found.jpg') }}"/>
                                    @endif
                                    <div class="content-berita">
                                        <h4 class="judul-berita">{{ \Illuminate\Support\Str::limit($arsip->judul, $limit = 25, $end = '...') }}</h4>
                                        <p class="keterangan-berita">{{ tgl_indo($arsip['tgl_upload']) }} | {{ hit($arsip['hit']) }} Dilihat</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div id="populer" class="tab-pane fade in" role="tabpanel">
                        @foreach ($arsip_populer as $arsip)
                            <div class="box-berita">
                                <a data-value="{{ site_url('artikel/'.buat_slug($arsip)) }}" class="popup">
                                    @if (is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar']))
                                        <img width="25%" style="float:left; margin:0 8px 4px 0;" class="img-berita" src="{{ base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip[gambar]) }}"/>
                                    @else
                                        <img width="25%" style="float:left; margin:0 8px 4px 0;" class="img-berita" src="{{ base_url('assets/images/404-image-not-found.jpg') }}"/>
                                    @endif
                                    <div class="content-berita">
                                        <h4 class="judul-berita">{{ \Illuminate\Support\Str::limit($arsip->judul, $limit = 20, $end = '...') }}</h4>
                                        <p class="keterangan-berita">{{ tgl_indo($arsip['tgl_upload']) }} | {{ hit($arsip['hit']) }} Dilihat</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="box-menu-statis">
        <!-- Swiper -->
        @if ($slides > 0)
            <div class="swiper">
                <div class="swiper-wrapper">
                    @foreach ($menu as $item)
                        <div class="swiper-slide">
                            <a data-value="{{ $item->link }}" class="popup">
                                <img src="{{ icon_menu_anjungan($item->icon) }}" class="menu-statis" alt="Icon Menu">
                                <p class="keterangan-menu-statis">{{ $item->nama }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @endif
    </section>
    <footer>
        <div class="waktu">
            {{ $tanggal }} | <span id="jam"></span>
        </div>
        <div id="scroll-container">
            <div id="scroll-text">
                {{ $teks_berjalan }}
            <div>
        </div>
    </footer>
    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Swiper JS -->
    <script src="{{ asset('js/swiper.min.js') }}"></script>
    <!-- moment js -->
    <script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
    <!-- bootstrap Date time picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/id.js') }}"></script>
</body>
</html>

<script>
    $.extend($.fn.datetimepicker.defaults, {
        timeZone: `<?= date_default_timezone_get() ?>`
    });

    moment.tz.setDefault(`<?= date_default_timezone_get() ?>`);
    
    $(function () {

    // Refrensi https://www.w3schools.com/js/tryit.asp?filename=tryjs_timing_clock
    function startTime() {
        const today = moment();
        let h = today.hours();
        let m = today.minutes();
        let s = today.seconds();
        m = checkTime(m);
        s = checkTime(s);
        $('#jam').html(h + ":" + m + ":" + s);
        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }
    startTime();
    });

    $('.popup').on('click', function (e) {
        window.open($(this).data("value"), "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=400,width=600,height=600");
    });

	$(document).ready(function () {
        var swiper = new Swiper(".swiper", {
            slidesPerView: '{{ $slides }}',
            spaceBetween: 30,
            slidesPerGroup: 1,
            loop: true,
            loopFillGroupWithBlank: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    })
</script>
