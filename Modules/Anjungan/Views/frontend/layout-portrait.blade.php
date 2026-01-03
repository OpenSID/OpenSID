<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ setting('sebutan_anjungan_mandiri') }}">
    <meta name="author" content="OpenDesa">
    <meta name="keywords" content="anjungan, opensid, mandiri, desa">
    <title>{{ setting('sebutan_anjungan_mandiri') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ favico_desa() }}">

    <link href="{{ module_asset('anjungan', 'css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ module_asset('anjungan', 'css/default.css') }}" rel="stylesheet">
    <link href="{{ module_asset('anjungan', 'css/color/nature.css') }}" rel="stylesheet">
    <link href="{{ module_asset('anjungan', 'css/color/nature.css') }}" rel="stylesheet alternate" title="nature" />
    <link href="{{ module_asset('anjungan', 'css/color/travel.css') }}" rel="stylesheet alternate" title="travel" />
    <link href="{{ module_asset('anjungan', 'css/color/casual.css') }}" rel="stylesheet alternate" title="casual" />
    <link href="{{ module_asset('anjungan', 'css/darkmode.css') }}" rel="stylesheet">
    <link href="{{ module_asset('anjungan', 'css/style.css') }}" rel="stylesheet">
    <link href="{{ module_asset('anjungan', 'css/portrait.css') }}" rel="stylesheet">
    @stack('css')

    <!-- jQuery 3 -->
    <script src="{{ module_asset('anjungan', 'js/jquery.min.js') }}"></script>
    <script src="{{ module_asset('anjungan', 'js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ module_asset('anjungan', 'js/flickity.js') }}"></script>
</head>

<style>
    .full-container {
        position: absolute;
        left: auto;
        top: 0;
        right: auto;
        min-height: 100vh;
        background: #000;
    }
</style>

<body>
    <div class="full-container" id="element" style="background: #000; max-width: fit-content;">

        <div class="backg-image"><img src="{{ asset('images/background.jpg') }}"></div>
        <div class="backg-color"></div>

        <!-- Mulai Header -->
        <div class="anjungan-head plr-master difle-l">

            <!-- Mulai Logo -->
            <a href="">
                <div class="anjungan-head-logo difle-l">
                    <img src="{{ gambar_desa($desa['logo']) }}" alt="logo">
                    <div>
                        <h1>{{ setting('sebutan_anjungan_mandiri') }}</h1>
                        <p> {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa'] . ' Kec. ' . $desa['nama_kecamatan'] . ' Kab. ' . $desa['nama_kabupaten']) }}
                        </p>
                    </div>
                </div>
            </a>
            <!-- Batas Logo -->
        </div>
        <!-- Batas Header -->

        <!-- Mulai Video/Slider, Artikel & Icon Link -->
        <div class="anjungan-middle">
            <div class="anjungan-middle-inner plr-master">
                <div class="grider mainmargin">
                    <!-- Mulai Video/Slider -->
                    <div class="slider-area">
                        @if (setting('anjungan_profil') == 1)
                            <div class="carousel js-flickity" data-flickity='{ "autoPlay": true, "cellAlign": "left", "fade": "true" }'>
                                @foreach ($gambar as $item)
                                    <div class="carousel-col">
                                        <div class="image-slider">
                                            <img src="{{ base_url(LOKASI_GALERI . 'sedang_' . $item->gambar) }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif (setting('anjungan_profil') == 2)
                            <div class="video-container">
                                <div class="video-view">
                                    <source src="{{ setting('anjungan_video') }}" type="video/mp4">
                                </div>
                            </div>
                        @else
                            <div class="video-container">
                                <iframe class="video-view" src="{{ setting('anjungan_youtube') }}?autoplay=1&controls=1&mute=0&loop=1" frameborder="0"></iframe>
                            </div>

                        @endif

                    </div>
                    <!-- Mulai Video/Slider -->

                    <!-- Mulai Icon Kanan -->
                    <div class="topright difle-l" style="justify-content: center !important; margin: 5px 0 15px 0;">
                        @if (isset($cek_anjungan['permohonan_surat_tanpa_akun']) && $cek_anjungan['permohonan_surat_tanpa_akun'] == 1)
                            <a href="{{ request()->is('anjungan-mandiri/penduduk-guest*') ? ci_route('anjungan-mandiri') : ci_route('anjungan-mandiri/penduduk-guest') }}" class="topright-icon radius-4" title="Permohonan Surat Tanpa Akun">
                                <img src="{{ module_asset('anjungan', request()->is('anjungan-mandiri/penduduk-guest*') ? 'images/icon/website.png' : 'images/icon/bukutamu.png') }}">
                                <p>{!! request()->is('anjungan-mandiri/penduduk-guest*') ? 'Anjungan<br>Awal' : 'Anjungan<br>Cepat' !!}</p>
                            </a>
                        @endif
                        <a class="topright-icon radius-4 popup" title="Kehadiran Perangkat Desa" data-value="./kehadiran"><img src="{{ module_asset('anjungan', 'images/icon/absen.png') }}">
                            <p>Absen</p>
                        </a>
                        <a class="topright-icon radius-4" href="./buku-tamu"><img src="{{ module_asset('anjungan', 'images/icon/bukutamu.png') }}">
                            <p>Buku<br />Tamu</p>
                        </a>
                        @php $pemerintah = explode(' ', ucwords(setting('sebutan_pemerintah_desa'))); @endphp
                        <a class="topright-icon radius-4" data-bs-toggle="modal" data-bs-target="#aparatur"><img src="{{ module_asset('anjungan', 'images/icon/aparatur.png') }}">
                            <p>{{ $pemerintah[0] }}<br />{{ $pemerintah[1] }}</p>
                        </a>
                        <a href="{{ ci_route('anjungan-mandiri/beranda') }}" class="topright-icon radius-4"><img src="{{ module_asset('anjungan', 'images/icon/mandiri.png') }}">
                            <p>Layanan<br />Mandiri</p>
                        </a>
                        <div style="position:relative;">
                            <div class="topright-icon radius-4" data-bs-toggle="dropdown">
                                <div><img src="{{ module_asset('anjungan', 'images/icon/warna.png') }}">
                                    <p>Pilih<br />Warna</p>
                                </div>
                            </div>
                            <div class="dropdown-menu colorstyle" role="menu">
                                <p style="text-align:center;margin:0 auto 15px;"><b>Pilihan Warna</b></p>
                                <div class="colors">
                                    <a data-val="nature" href="javascript:void(0);">
                                        <div class="changecolor nature difle-l">
                                            <div class="changecolor-box"></div>
                                            <p>Biru & Hijau</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="colors">
                                    <a data-val="travel" href="javascript:void(0);">
                                        <div class="changecolor travel difle-l">
                                            <div class="changecolor-box"></div>
                                            <p>Ungu & Pink</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="colors">
                                    <a data-val="casual" href="javascript:void(0);">
                                        <div class="changecolor casual difle-l">
                                            <div class="changecolor-box"></div>
                                            <p>Toska & Orange</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="darklight difle-l" onclick="setDarkMode(true)" id="darkBtn">
                                    <div class="darklight-icon radius-4 difle-c"><img src="{{ module_asset('anjungan', 'images/icon/dark.png') }}"></div>
                                    <p>Gelapkan Layar</p>
                                </div>
                            </div>
                        </div>
                        <div class="topright-icon iconhid radius-4" id="openfull" onclick="openFullscreen();">
                            <div><img src="{{ module_asset('anjungan', 'images/icon/maximize.png') }}">
                                <p>Full<br />Screen</p>
                            </div>
                        </div>
                        <div class="topright-icon iconhid radius-4" id="exitfull" onclick="closeFullscreen();">
                            <div><img src="{{ module_asset('anjungan', 'images/icon/minimize.png') }}">
                                <p>Exit<br />Fullscreen</p>
                            </div>
                        </div>
                    </div>
                    <!-- Batas Icon Kanan -->

                    @yield('content')

                </div>

                <!-- Mulai Icon Link -->
                <div class="anjungan-bottom">
                    <div class="margin-carousel">
                        <div class="carousel js-flickity" data-flickity='{"pageDots": false, "autoPlay": true, "cellAlign": "left", "wrapAround": true }'>
                            @foreach ($menu as $item)
                                <div class="carousel-col">
                                    <a data-value="{{ $item->link_url }}" class="popup">
                                        <div class="icon-stat">
                                            @if ($item->icon)
                                                <img src="{{ icon_menu_anjungan($item->icon) }}">
                                            @else
                                                <img src="{{ base_url('assets/images/404-image-not-found.jpg') }}">
                                            @endif
                                            <div class="icon-stat-title difle-c">
                                                <p>{{ $item->nama }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <!-- Batas Icon Link -->

            </div>
        </div>
        <!-- Batas Slider, Artikel & Icon Link -->

        <!-- Mulai Footer -->
        <div class="bottom-page plr-master">
            <div class="bottom-page-inner">
                <div class="datetime difle-l">
                    <div class="datetime-box difle-l">
                        <div id="tanggal"></div>
                        <div id="thistime"></div>
                    </div>
                </div>
                <div class="runtext">
                    <marquee onmouseover="this.stop()" onmouseout="this.start()">{{ $teks_berjalan }}</marquee>
                </div>
            </div>
        </div>
        <!-- Batas Footer -->

        </di>

        <div class="modal-custom">
            <div class="modal fade" id="aparatur" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="headmodal difle-c">
                        <h1>{{ ucwords(setting('sebutan_pemerintah_desa')) }}</h1>
                    </div>
                    <div class="modal-inner">
                        <div class="colscroll">
                            <div class="modal-padding">
                                <div class="modal-padding">
                                    <div class="grider mlr-min15">
                                        @if ($pamong)
                                            @foreach ($pamong as $data)
                                                <div class="aparatur-col">
                                                    <div class="aparatur-container">
                                                        <div class="aparatur-box"><img src="{{ $data['foto'] }}" alt="Foto {{ $data['nama'] }}"></div>
                                                    </div>
                                                    <h2>{{ $data['jabatan'] }}</h2>
                                                    <p>{{ $data['nama'] }}</p>
                                                    <div class="absensi absen difle-c">
                                                        @if (setting('tampilkan_kehadiran') && $data['status_kehadiran'] == 'hadir')
                                                            <span class='label label-success'>Hadir</span>
                                                        @elseif (setting('tampilkan_kehadiran') && $data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir')
                                                            <span class='label label-danger'>{{ ucwords($data['status_kehadiran']) }}</span>
                                                        @else
                                                            <span class='label label-danger'>Belum Rekam Kehadiran</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <h5>{{ ucwords(setting('sebutan_pemerintah_desa')) }} tidak
                                                tersedia.</h5>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footmodal difle-c">
                        <div class="close-modal difle-c" data-bs-dismiss="modal"><svg viewBox="0 0 24 24">
                                <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                            </svg>Tutup</div>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>
<script>
    var light = '{{ module_asset('anjungan', 'images/icon/light.png') }}';
    var dark = '{{ module_asset('anjungan', 'images/icon/dark.png') }}';
</script>

<script src="{{ module_asset('anjungan', 'js/support.js') }}"></script>

<script>
    var count = -1;
    var slides = jQuery.makeArray($('#slides article')),
        totalSlides = slides.length - 1;
    var startPos = {
            "top": '100%',
            "z-index": "0"
        },
        endPos = {
            'top': '0px',
            "z-index": "2"
        },
        prevPos = {
            'top': '-100%',
            "z-index": "0"
        },
        transit = {
            "transition": "top 800ms ease 0s",
            "transition-delay": "0s"
        },
        nonetrans = {
            "transition": "none"
        },
        timer = null

    function advance() {
        if (count == totalSlides) {
            $(slides[count]).animate(startPos, 0).css(transit);
            count = 0;
            $(slides[count]).css(prevPos).css(nonetrans);
            $(slides[count]).animate(endPos, 0).css(transit)
        } else {
            $(slides[count]).animate(startPos, 0).css(transit);
            count++;
            $(slides[count]).css(prevPos).css(nonetrans);
            $(slides[count]).animate(endPos, 0).css(transit)
        }
    }

    function rewind() {
        if (count === 0) {
            $(slides[count]).animate(prevPos, 0).css(transit);
            count = totalSlides;
            $(slides[count]).css(startPos).css(nonetrans);
            $(slides[count]).animate(endPos, 0).css(transit)
        } else {
            $(slides[count]).prev().css(startPos).css(nonetrans);
            $(slides[count]).animate(prevPos, 0).css(transit);
            count = count - 1;
            $(slides[count]).animate(endPos, 0).css(transit)
        }
    }

    function selectDots() {
        n = count + 1;
        $('#dots li:nth-child(' + n + ')').addClass('selected');
        $('#dots li:nth-child(' + n + ')').siblings().removeClass('selected')
    }

    function clickDots() {
        $('#dots li').bind('click', function() {
            var index = $(this).index();
            if (count > index) {
                $(slides[count]).animate(prevPos, 0).css(transit);
                count = index;
                $(slides[count]).css(startPos).css(nonetrans);
                $(slides[count]).animate(endPos, 0).css(transit);
            } else if (count < index) {
                $(slides[count]).animate(startPos, 0).css(transit);
                count = index;
                $(slides[count]).css(prevPos).css(nonetrans);
                $(slides[count]).animate(endPos, 0).css(transit);
            } else {
                return false;
            }
            selectDots();
            clearTimeout(timer);
            timer = setTimeout(playSlides, 7500);
            unbindBtn();
        });
    }

    function upDown() {
        $('.next').bind('click', function() {
            advance();
            selectDots();
            clearTimeout(timer);
            timer = setTimeout(playSlides, 7500);
            unbindBtn();
        });
        $('.prev').bind('click', function() {
            if (count == -1) {
                count = 0
            } else {
                rewind()
            }
            selectDots();
            clearTimeout(timer);
            timer = setTimeout(playSlides, 7500);
            unbindBtn();
        });
    }

    function unbindBtn() {
        $('.next,.prev,#dots li').unbind('click');
        setTimeout(upDown, 800);
        setTimeout(clickDots, 800);
    }

    function playSlides() {
        clickDots();
        upDown();

        function loop() {
            advance();
            selectDots();
            timer = setTimeout(loop, 7000);
            unbindBtn();
        }
        loop();
    }
    $(document).ready(function() {
        playSlides()
    });
</script>
<script>
    var elem = document.documentElement;

    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
        document.getElementById("openfull").style.display = "none";
        document.getElementById("exitfull").style.display = "block";
    }

    function closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
        document.getElementById("openfull").style.display = "block";
        document.getElementById("exitfull").style.display = "none";
    }
</script>

<script type="text/javascript">
    $(function() {
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
    });

    $('.popup').on('click', function(e) {
        window.open($(this).data("value"), "_blank",
            "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=400,width=600,height=600");
    });
</script>

@stack('scripts')
