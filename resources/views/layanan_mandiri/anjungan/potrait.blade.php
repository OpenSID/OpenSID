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
    <link rel="stylesheet" href="{{ asset('css/anjungan-potrait.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="{{ asset('css/swiper.min.css') }}" />
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/screensaver.css') }}">
    {{-- Sweet Alert --}}
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    @stack('css')
</head>

<body>
    <header>
        <div class="flex center">
            <img src="{{ gambar_desa($desa['logo']) }}" class="logo" alt="Logo">
            <div class="box-anjungan">
                <h4 class="tulisan-anjungan">Anjungan Desa Mandiri</h4>
                <p class="tulisan-desa">
                    {{ ucwords($setting->sebutan_desa . ' ' . $desa['nama_desa'] . ' Kec. ' . $desa['nama_kecamatan'] . ' Kab. ' . $desa['nama_kabupaten']) }}
                </p>
            </div>
        </div>
    </header>

    <section class="flex center content">
        @if (setting('anjungan_profil') == 1)
            <div id="swiper-slide" class="swiper">
                <div class="swiper-wrapper">
                    @foreach ($gambar as $item)
                        <div class="swiper-slide">
                            <img src="{{ base_url(LOKASI_GALERI . 'sedang_' . $item->gambar) }}" class="background" alt="Icon Menu">
                        </div>
                    @endforeach
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        @elseif (setting('anjungan_profil') == 2)
            <video class="background" width="100%" height="100%" autoplay loop controls>
                <source src="{{ setting('anjungan_video') }}" type="video/mp4">
            </video>
        @else
            <iframe
                class="background"
                width="100%"
                height="100%"
                src="{{ setting('anjungan_youtube') }}?autoplay=1&loop=1&rel=0&showinfo=0&color=white&iv_load_policy=3"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
            ></iframe>
        @endif
    </section>

    <section class="flex center box-menu-header">
        <a href="{{ site_url('/') }}" title="Home"><img src="{{ asset('images/anjungan/home.svg') }}" class="menu-header" alt="Icon Menu"></a>
        <a data-value="{{ site_url('kehadiran') }}" class="popup" title="Kehadiran Perangkat Desa"><img src="{{ asset('images/anjungan/kehadiran.svg') }}" class="menu-header" alt="Icon Menu"></a>
        <a href="#" title="Buku Tamu"><img src="{{ asset('images/anjungan/tamu.svg') }}" class="menu-header" alt="Icon Menu"></a>
        <a href="#" id="perangkat" title="Daftar Perangkat Desa"><img src="{{ asset('images/anjungan/perangkat.svg') }}" class="menu-header" alt="Icon Menu"></a>
        <a href="#" title="Mode Gelap"><img src="{{ asset('images/anjungan/mode.svg') }}" class="menu-header" alt="Icon Menu"></a>
    </section>

    <section class="flex center content">
        <div class="berita">
            <div class="header-berita">
                <h4 class="tulisan-berita">Berita {{ ucwords($setting->sebutan_desa) }}</h4>
                <div>
                    <ul role="tablist" class="nav nav-tabs custom-tabs">
                        <li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home" href="#terkini">Terbaru</a></li>
                        <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#populer">Populer</a></li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div id="terkini" class="tab-pane fade in active row" role="tabpanel">
                    @foreach ($arsip_terkini as $arsip)
                        <div class="col-md-3 box-berita">
                            <a data-value="{{ site_url('artikel/' . buat_slug($arsip)) }}" class="popup">
                                @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']))
                                    <img width="25%" class="img-berita" src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']) }}" />
                                @else
                                    <img width="25%" class="img-berita" src="{{ base_url('assets/images/404-image-not-found.jpg') }}" />
                                @endif
                                <div class="content-berita">
                                    <h4 class="judul-berita">
                                        {{ \Illuminate\Support\Str::limit($arsip->judul, $limit = 25, $end = '...') }}
                                    </h4>
                                    <p class="keterangan-berita">{{ tgl_indo($arsip['tgl_upload']) }} |
                                        {{ hit($arsip['hit']) }} Dilihat</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div id="populer" class="tab-pane fade in row" role="tabpanel">
                    @foreach ($arsip_populer as $arsip)
                        <div class="col-md-3 box-berita">
                            <a data-value="{{ site_url('artikel/' . buat_slug($arsip)) }}" class="popup">
                                @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']))
                                    <img width="25%" class="img-berita" src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']) }}" />
                                @else
                                    <img width="25%" class="img-berita" src="{{ base_url('assets/images/404-image-not-found.jpg') }}" />
                                @endif
                                <div class="content-berita">
                                    <h4 class="judul-berita">
                                        {{ \Illuminate\Support\Str::limit($arsip->judul, $limit = 20, $end = '...') }}
                                    </h4>
                                    <p class="keterangan-berita">{{ tgl_indo($arsip['tgl_upload']) }} |
                                        {{ hit($arsip['hit']) }} Dilihat</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="box-menu-statis">
        <!-- Swiper -->
        @if ($slides > 0)
            <div id="swiper-menu" class="swiper">
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
            </div>
        </div>
    </footer>

    <div id="daftar-perangkat" style="display: none">
        <div class="row">
            @if ($pamong)
                @foreach ($pamong as $data)
                    <div class="col-xs-3">
                        <div class="card text-center">
                            <img class="foto-perangkat" src="{{ $data['foto'] }}" alt="Foto {{ $data['nama'] }}" />
                            <hr class="line">
                            <b>
                                {{ $data['nama'] }}<br>
                                {{ $data['jabatan'] }}<br>
                                @if (setting('tampilkan_kehadiran') && $data['status_kehadiran'] == 'hadir')
                                    <span class='label label-success'>Hadir</span>
                                @elseif (setting('tampilkan_kehadiran') && $data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir')
                                    <span class='label label-danger'><?= ucwords($data['status_kehadiran']) ?></span>
                                @else
                                    <span class='label label-danger'>Belum Rekam Kehadiran</span>
                                @endif
                        </div>
                    </div>
                @endforeach
            @else
                <h5>Pemerintah {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }} tidak tersedia.</h5>
            @endif
        </div>
    </div>

    @if (setting('tampilan_anjungan') == 1 && !empty(setting('tampilan_anjungan_slider')))
        <div id="sliderv" class="video-internal" style="display: none;">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($gambar as $key => $value)
                        <div class="item {{ jecho($key, 0, 'active') }}">
                            <img src="{{ AmbilGaleri($value->gambar, 'sedang') }}" alt="Los Angeles" style="width:100%;">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (setting('tampilan_anjungan') == 2 && !empty(setting('tampilan_anjungan_video')))
        <div class="video-internal" id="videov" style="display: none;">
            <video loop {{ jecho(setting('tampilan_anjungan_audio'), 0, 'muted') }} class="video-internal-bg" id="videona">
                <source src="{{ setting('tampilan_anjungan_video') }}" type="video/mp4">
            </video>
        </div>
    @endif
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
    {{-- Sweet Alert --}}
    <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @if (!setting('inspect_element'))
        <script src="{{ asset('js/disabled.min.js') }}"></script>
    @endif
</body>

</html>

<script>
    $.extend($.fn.datetimepicker.defaults, {
        timeZone: `{{ date_default_timezone_get() }}`
    });

    moment.tz.setDefault(`{{ date_default_timezone_get() }}`);

    $(function() {

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
            if (i < 10) {
                i = "0" + i
            }; // add zero in front of numbers < 10
            return i;
        }
        startTime();
    });

    $('.popup').on('click', function(e) {
        window.open($(this).data("value"), "_blank",
            "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=400,width=600,height=600");
    });

    $(document).ready(function() {
        $('#perangkat').click(function() {
            Swal.fire({
                html: $('#daftar-perangkat').html(),
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'swal-perangkat',
                }
            })
        });

        var swiper = new Swiper("#swiper-menu", {
            slidesPerView: '{{ $slides }}',
            spaceBetween: 0,
            slidesPerGroup: 1,
            loop: true,
            loopFillGroupWithBlank: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        var swiper1 = new Swiper("#swiper-slide", {
            autoplay: {
                delay: 3000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        var screensaver = "{{ setting('tampilan_anjungan') }}";
        var screensaver_video = "{{ setting('tampilan_anjungan_video') }}";
        var screensaver_slide = "{{ setting('tampilan_anjungan_slider') }}";
        var IDLE_TIMEOUT = "{{ setting('tampilan_anjungan_waktu') }}";

        var videona = document.getElementById("videona");
        var _idleSecondsCounter = 0;
        document.onclick = function() {
            _idleSecondsCounter = 0;
        };
        document.onmousemove = function() {
            _idleSecondsCounter = 0;
        };
        document.onkeypress = function() {
            _idleSecondsCounter = 0;
        };
        window.setInterval(CheckIdleTime, 1000);

        function CheckIdleTime() {
            _idleSecondsCounter++;
            var video = document.getElementById("videov");
            var slider = document.getElementById("sliderv");

            if (_idleSecondsCounter >= IDLE_TIMEOUT) {
                if (screensaver == 2 && screensaver_video) {
                    videona.play();
                    video.style.display = "block";
                } else if (screensaver == 1 && screensaver_slide) {
                    slider.style.display = "block";
                }
            } else {
                if (screensaver == 2 && screensaver_video) {
                    videona.pause();
                    video.style.display = "none";
                } else if (screensaver == 1 && screensaver_slide) {
                    slider.style.display = "none";
                }
            }
        }
    })
</script>
