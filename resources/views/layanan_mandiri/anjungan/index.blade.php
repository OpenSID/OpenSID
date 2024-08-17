<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Anjungan OpenSID">
    <meta name="author" content="OpenDesa">
    <meta name="keywords" content="anjungan, opensid, mandiri, desa">
    <title>Anjungan OpenSID</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ favico_desa() }}">

    <link href="{{ asset('anjungan/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('anjungan/css/default.css') }}" rel="stylesheet">
    <link href="{{ asset('anjungan/css/color/nature.css') }}" rel="stylesheet">
    <link href="{{ asset('anjungan/css/color/nature.css') }}" rel="stylesheet alternate" title="nature" />
    <link href="{{ asset('anjungan/css/color/travel.css') }}" rel="stylesheet alternate" title="travel" />
    <link href="{{ asset('anjungan/css/color/casual.css') }}" rel="stylesheet alternate" title="casual" />
    <link href="{{ asset('anjungan/css/darkmode.css') }}" rel="stylesheet">
    <link href="{{ asset('anjungan/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('anjungan/css/screen.css') }}" rel="stylesheet">
    @stack('css')

    <!-- jQuery 3 -->
    <script src="{{ asset('anjungan/js/jquery.min.js') }}"></script>
    <script src="{{ asset('anjungan/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('anjungan/js/flickity.js') }}"></script>
</head>

<body>
    <div class="full-container" id="element">

        <div class="backg-image"><img src="{{ asset('images/background.jpg') }}"></div>
        <div class="backg-color"></div>

        <!-- Mulai Header -->
        <div class="anjungan-head plr-master difle-l">

            <!-- Mulai Logo -->
            <a href="">
                <div class="anjungan-head-logo difle-l">
                    <img src="{{ gambar_desa($desa['logo']) }}" alt="logo">
                    <div>
                        <h1>Anjungan Desa Mandiri</h1>
                        <p> {{ ucwords($setting->sebutan_desa . ' ' . $desa['nama_desa'] . ' Kec. ' . $desa['nama_kecamatan'] . ' Kab. ' . $desa['nama_kabupaten']) }}
                        </p>
                    </div>
                </div>
            </a>
            <!-- Batas Logo -->

            <!-- Mulai Icon Kanan -->
            <div class="topright difle-l">
                <a class="topright-icon radius-4 popup" title="Kehadiran Perangkat Desa" data-value="./kehadiran"><img src="{{ asset('anjungan/images/icon/absen.png') }}">
                    <p>Absen</p>
                </a>
                <a class="topright-icon radius-4" href="./buku-tamu"><img src="{{ asset('anjungan/images/icon/bukutamu.png') }}">
                    <p>Buku<br />Tamu</p>
                </a>
                <?php $pemerintah = explode(' ', ucwords(setting('sebutan_pemerintah_desa'))); ?>
                <a class="topright-icon radius-4" data-bs-toggle="modal" data-bs-target="#aparatur"><img src="{{ asset('anjungan/images/icon/aparatur.png') }}">
                    <p><?= $pemerintah[0] ?><br /><?= $pemerintah[1] ?></p>
                </a>
                <a class="topright-icon radius-4 popup" data-value="./layanan-mandiri/masuk"><img src="{{ asset('anjungan/images/icon/mandiri.png') }}">
                    <p>Layanan<br />Mandiri</p>
                </a>
                <div style="position:relative;">
                    <div class="topright-icon radius-4" data-bs-toggle="dropdown">
                        <div><img src="{{ asset('anjungan/images/icon/warna.png') }}">
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
                            <div class="darklight-icon radius-4 difle-c"><img src="{{ asset('anjungan/images/icon/dark.png') }}"></div>
                            <p>Gelapkan Layar</p>
                        </div>
                    </div>
                </div>
                <div class="topright-icon iconhid radius-4" id="openfull" onclick="openFullscreen();">
                    <div><img src="{{ asset('anjungan/images/icon/maximize.png') }}">
                        <p>Full<br />Screen</p>
                    </div>
                </div>
                <div class="topright-icon iconhid radius-4" id="exitfull" onclick="closeFullscreen();">
                    <div><img src="{{ asset('anjungan/images/icon/minimize.png') }}">
                        <p>Exit<br />Fullscreen</p>
                    </div>
                </div>
            </div>
            <!-- Batas Icon Kanan -->

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
                                <iframe class="video-view" src="{{ setting('anjungan_youtube') }}?autoplay=1&controls=1&mute=1&loop=1" frameborder="0"></iframe>
                            </div>

                        @endif

                    </div>
                    <!-- Mulai Video/Slider -->

                    <!-- Mulai Artikel -->
                    <div class="article-area">
                        <div class="article-head difle-c">
                            <h1>Berita {{ ucwords($setting->sebutan_desa) }}</h1>
                        </div>
                        <div class="relhid">
                            <div class="tabs">
                                <input type="radio" id="tab1" name="tab-control" checked>
                                <input type="radio" id="tab2" name="tab-control">
                                <ul>
                                    <li>
                                        <label for="tab1" role="button" class="difle-c">
                                            <svg viewBox="0 0 24 24">
                                                <path
                                                    d="M21,16.5C21,16.88 20.79,17.21 20.47,17.38L12.57,21.82C12.41,21.94 12.21,22 12,22C11.79,22 11.59,21.94 11.43,21.82L3.53,17.38C3.21,17.21 3,16.88 3,16.5V7.5C3,7.12 3.21,6.79 3.53,6.62L11.43,2.18C11.59,2.06 11.79,2 12,2C12.21,2 12.41,2.06 12.57,2.18L20.47,6.62C20.79,6.79 21,7.12 21,7.5V16.5M12,4.15L5,8.09V15.91L12,19.85L19,15.91V8.09L12,4.15Z"
                                                />
                                            </svg>
                                            <span>Terbaru</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label for="tab2" role="button" class="difle-c">
                                            <svg viewBox="0 0 24 24">
                                                <path
                                                    d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z"
                                                />
                                            </svg>
                                            <span>Populer</span>
                                        </label>
                                    </li>
                                </ul>
                                <div class="slider">
                                    <div class="indicator"></div>
                                </div>
                                <div class="content">
                                    <section>
                                        <div class="article-box">
                                            <div id="slide-container">
                                                <div id="slides">
                                                    @for ($i = 0; $i < $arsip_terkini->count(); $i += 2)
                                                        <article class="featured-article animated">
                                                            <div class="mlr-10">
                                                                <div class="grider mlr-min5">
                                                                    <div class="col-2">
                                                                        <a data-value="{{ site_url('artikel/' . buat_slug($arsip_terkini[$i]->toArray())) }}" class="popup">
                                                                            <div class="imagecrop-grid">
                                                                                @if (file_exists(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip_terkini[$i]['gambar']))
                                                                                    <img src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip_terkini[$i]['gambar']) }}">
                                                                                @else
                                                                                    <img src="{{ base_url('assets/images/404-image-not-found.jpg') }}">
                                                                                @endif

                                                                                <div class="posting">
                                                                                    {{ tgl_indo($arsip_terkini[$i]['tgl_upload']) }}
                                                                                </div>
                                                                            </div>
                                                                            <h2>{{ \Illuminate\Support\Str::limit($arsip_terkini[$i]->judul, $limit = 75, $end = '...') }}
                                                                            </h2>
                                                                        </a>
                                                                    </div>

                                                                    @if ($arsip_terkini[$i + 1])
                                                                        <div class="col-2">
                                                                            <a data-value="{{ site_url('artikel/' . buat_slug($arsip_terkini[$i + 1]->toArray())) }}" class="popup">
                                                                                <div class="imagecrop-grid">
                                                                                    @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip_terkini[$i + 1]['gambar']))
                                                                                        <img src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip_terkini[$i + 1]['gambar']) }}">
                                                                                    @else
                                                                                        <img src="{{ base_url('assets/images/404-image-not-found.jpg') }}">
                                                                                    @endif

                                                                                    <div class="posting">
                                                                                        {{ tgl_indo($arsip_terkini[$i + 1]['tgl_upload']) }}
                                                                                    </div>
                                                                                </div>
                                                                                <h2>{{ \Illuminate\Support\Str::limit($arsip_terkini[$i + 1]->judul, $limit = 75, $end = '...') }}
                                                                                </h2>
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </article>
                                                        <div class="button-slide difle-c">
                                                            <button class="prev">
                                                                <div class="slide-btn difle-c"><svg viewBox="0 0 24 24">
                                                                        <path d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z" />
                                                                    </svg></div>
                                                            </button>
                                                            <button class="next">
                                                                <div class="slide-btn difle-c"><svg viewBox="0 0 24 24">
                                                                        <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                                                                    </svg></div>
                                                            </button>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <section>
                                        <div class="article-box">
                                            <div class="marquee-top">
                                                <div class="track-top">
                                                    @foreach ($arsip_populer as $arsip)
                                                        <a data-value="{{ site_url('artikel/' . buat_slug($arsip->toArray())) }}" class="popup">
                                                            <div class="article-row">
                                                                <div class="relhid mlr-min5">
                                                                    <div class="article-image">
                                                                        <div class="imagecrop-artikel">
                                                                            <img src="images/artikel/artikel5.jpg">
                                                                            @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']))
                                                                                <img src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']) }}">
                                                                            @else
                                                                                <img src="{{ base_url('assets/images/404-image-not-found.jpg') }}">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="article-title">
                                                                        <p>{{ hit($arsip['hit']) }} dilihat</p>
                                                                        <h2> {{ \Illuminate\Support\Str::limit($arsip->judul, $limit = 65, $end = '...') }}
                                                                        </h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Mulai Artikel -->

                </div>

                <!-- Mulai Icon Link -->
                <div class="anjungan-bottom">
                    <div class="margin-carousel">
                        <div class="carousel js-flickity" data-flickity='{"pageDots": false, "autoPlay": true, "cellAlign": "left", "wrapAround": true }'>
                            @foreach ($menu as $item)
                                <div class="carousel-col">
                                    <a data-value="{{ $item->link }}" class="popup">
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

    </div>

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
                                                        <span class='label label-danger'><?= ucwords($data['status_kehadiran']) ?></span>
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
    var light = '{{ asset('anjungan/images/icon/light.png') }}';
    var dark = '{{ asset('anjungan/images/icon/dark.png') }}';
</script>

<script src="{{ asset('anjungan/js/support.js') }}"></script>

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
