<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Buku Tamu">
    <meta name="author" content="OpenDesa">
    <meta name="keywords" content="buku tamu, tamu, buku, opensid, desa">
    <title>Buku Tamu</title>

    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link href="{{ module_asset('bukutamu', 'css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    <link href="{{ module_asset('bukutamu', 'css/style.css') }}" rel="stylesheet">
    <link href="{{ module_asset('bukutamu', 'css/screen.css') }}" rel="stylesheet">
    @stack('css')

    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/flickity.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

    @include('admin.layouts.components.token')

</head>

<body>

    <div class="full-container" id="element">

        <!-- Mulai Latar -->
        <div class="bg-image">
            <img src="{{ module_asset('bukutamu', 'images/background.jpg') }}">
            <div class="bgload"></div>
            <div class="bgload bgload2"></div>
            <div class="bgload bgload3"></div>
        </div>
        <!-- Batas Latar -->

        <!-- Mulai Header -->
        <div class="headpage">
            <div class="relhid margin-master difle-l">
                <div class="logo difle-l">
                    <img src="{{ gambar_desa($desa->logo) }}" alt="{{ $desa->nama_desa }}">
                    <div>
                        <h1>{{ strtoupper('Pemerintah ' . setting('sebutan_desa') . ' ' . $desa->nama_desa) }}</h1>
                        <p> {{ strtoupper(setting('sebutan_kecamatan') . ' ' . $desa->nama_kecamatan) }} {{ strtoupper(setting('sebutan_kabupaten') . ' ' . $desa->nama_kabupaten) }} </p>
                    </div>
                </div>
                <div class="headright difle-r">
                    <div>
                        <div class="datetime"><span id="tanggal"></span><span id="thistime"></span></div>
                        <button id="layanan-mandiri" type="button" class="btn right-btn knob bgyellow" style="margin:0 !important;">Anjungan</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Batas Header -->

        <div class="relhid margin-master">
            <div class="grider mainmargin">

                <!-- Mulai Kolom Kiri -->
                <div class="area-title">
                    <div class="relhid area-title-inner">
                        <h2>Selamat Datang Di Layanan</h2>
                        <div class="area-title-center difle-c">
                            <div>
                                <div class="bukutamu-title">
                                    <svg viewBox="0 0 1229.000000 1161.000000">
                                        <g transform="translate(0.000000,1161.000000) scale(0.100000,-0.100000)">
                                            <path
                                                d="M15 11598 c-3 -7 -4 -1266 -3 -2798 l3 -2785 665 -3 c709 -3 931 5 1103 38 530 102 845 439 932 995 21 133 31 684 16 900 -13 201 -32 312 -77 444 -87 261 -226 429 -447 541 -55 28 -105 50 -113 50 -29 0 -12 21 38 46 277 140 430 375 494 759 27 156 27 686 1 853 -46 296 -135 490 -297 652 -187 187 -433 282 -800 310 -206 15 -1509 13 -1515 -2z m1428 -803 c169 -36 257 -132 303 -330 27 -114 28 -650 1 -765 -24 -104 -67 -188 -127 -244 -106 -99 -215 -126 -516 -126 l-204 0 0 740 0 740 238 0 c167 0 257 -5 305 -15z m82 -2294 c228 -74 304 -209 326 -575 15 -233 5 -684 -16 -781 -32 -149 -110 -253 -219 -294 -85 -31 -192 -41 -462 -41 l-254 0 0 861 0 861 278 -4 c254 -4 283 -6 347 -27z M3180 9407 c0 -1875 2 -2221 15 -2328 41 -357 152 -609 354 -809 159 -158 330 -246 583 -302 118 -25 468 -36 604 -19 295 39 528 145 703 321 193 193 308 454 351 800 7 57 10 818 10 2312 l0 2228 -420 0 -420 0 -3 -2242 c-2 -2086 -4 -2247 -20 -2306 -38 -142 -112 -238 -220 -287 -119 -54 -303 -52 -424 4 -105 49 -168 129 -209 267 -18 56 -19 172 -21 2312 l-3 2252 -440 0 -440 0 0 -2203z M6395 11598 c-3 -7 -4 -1266 -3 -2798 l3 -2785 440 0 440 0 5 905 5 905 171 340 c171 338 171 339 182 305 6 -19 77 -255 157 -525 433 -1451 568 -1902 576 -1917 9 -17 42 -18 464 -18 250 0 455 2 455 5 0 3 -167 560 -370 1238 -204 678 -443 1473 -532 1767 l-160 535 515 1010 c284 556 519 1018 523 1028 6 16 -19 17 -430 17 -404 0 -437 -1 -446 -17 -10 -16 -656 -1375 -993 -2085 -61 -131 -114 -238 -117 -238 -3 0 -5 525 -5 1167 l0 1168 -438 3 c-345 2 -439 0 -442 -10z M9640 9400 c0 -1483 3 -2243 11 -2308 25 -230 93 -451 192 -618 64 -109 220 -271 324 -338 212 -136 451 -196 783 -196 432 0 727 106 956 342 169 175 268 376 326 668 l23 115 3 2273 2 2272 -422 -2 -423 -3 -5 -2260 -5 -2260 -21 -62 c-60 -175 -161 -258 -348 -283 -48 -7 -95 -7 -144 0 -180 25 -282 107 -339 275 l-28 80 -3 2258 -2 2257 -440 0 -440 0 0 -2210z M7 5673 c-4 -3 -7 -183 -7 -400 l0 -393 460 0 460 0 0 -2395 0 -2395 440 0 440 0 2 2393 3 2392 458 3 457 2 0 400 0 400 -1353 0 c-745 0 -1357 -3 -1360 -7z M3255 5658 c-3 -13 -79 -480 -170 -1038 -91 -558 -183 -1125 -205 -1260 -22 -135 -55 -339 -74 -455 -19 -115 -129 -792 -245 -1502 -116 -711 -211 -1298 -211 -1303 0 -7 137 -9 407 -8 l407 3 67 458 c37 251 72 496 79 542 l12 85 499 0 c391 0 501 -3 504 -12 4 -12 155 -1056 155 -1071 0 -4 198 -7 440 -7 242 0 440 3 440 8 0 4 -122 754 -271 1667 -302 1852 -283 1735 -483 2965 -80 492 -149 907 -152 923 l-6 27 -594 0 -594 0 -5 -22z m643 -1460 c39 -277 125 -885 192 -1353 66 -467 122 -862 125 -877 l5 -28 -395 0 c-284 0 -395 3 -395 11 0 13 368 2670 376 2717 4 17 9 32 13 32 4 0 40 -226 79 -502z M5580 2885 l0 -2795 385 0 385 0 0 2115 c0 1403 3 2115 10 2115 6 0 10 -5 10 -11 0 -9 143 -959 563 -3734 l73 -480 369 -3 370 -2 143 947 c429 2840 497 3283 504 3283 4 0 8 -952 8 -2115 l0 -2115 415 0 415 0 0 2795 0 2795 -627 -2 -628 -3 -37 -265 c-68 -487 -106 -759 -233 -1665 -69 -495 -143 -1024 -164 -1175 -22 -151 -58 -413 -82 -582 -24 -170 -46 -308 -51 -308 -9 0 -9 0 -73 455 -30 215 -81 584 -114 820 -63 450 -341 2434 -366 2614 -8 58 -15 106 -15 108 0 2 -283 3 -630 3 l-630 0 0 -2795z M9663 3413 l3 -2268 22 -111 c58 -303 165 -520 341 -694 156 -155 334 -248 579 -301 139 -30 472 -38 630 -15 418 62 709 258 880 594 66 130 98 227 140 422 14 67 16 317 19 2358 l3 2282 -420 0 -420 0 -3 -2237 c-3 -2472 2 -2297 -65 -2430 -32 -62 -97 -129 -153 -158 -120 -63 -319 -65 -445 -6 -106 49 -169 129 -210 267 -18 56 -19 172 -21 2312 l-3 2252 -440 0 -440 0 3 -2267z"
                                            />
                                        </g>
                                    </svg>
                                </div>
                                <div class="intro">
                                    <h3>BUKU TAMU</h3>
                                    <img src="{{ module_asset('bukutamu', 'images/intro.png') }}">
                                </div>
                            </div>
                        </div>
                        <div class="area-title-bottom difle-c" style="align-self: end;">
                            <button class="btn knob knob-mono" id="registrasi">Registrasi Tamu</button>
                            <button class="btn knob knob-mono" id="kepuasan">Indeks Kepuasan</button>
                        </div>
                    </div>
                </div>
                <!-- Batas Kolom Kiri -->

                @yield('content')

            </div>
        </div>
    </div>

</body>
<script src="{{ module_asset('bukutamu', 'js/plugins.bundle.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/validasi.js') }}"></script>
@if (!setting('inspect_element'))
    <script src="{{ asset('js/disabled.min.js') }}"></script>
@endif

<script>
    var success = `{!! session('success') !!}`;
    var error = `{!! session('error') !!}`;
    console.log((success))
    if (success) {
        Swal.fire({
            html: '<strong> ' + success + ' </strong>',
            icon: "success",
            timer: 2000,
            buttonsStyling: false,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-primary"
            },
        });
    }

    if (error) {
        Swal.fire({
            html: '<strong> ' + error + ' </strong>',
            icon: "error",
            timer: 2000,
            buttonsStyling: false,
            confirmButtonText: "OK",
            customClass: {
                confirmButton: "btn btn-danger"
            },
        });
    }
</script>

<style type="text/css">
    .form-group .error label {
        color: #dd4b39;
        font-weight: 700;
    }

    .error {
        color: #dd4b39;
        font-weight: 700;
        font-size: 13px !important;
    }
</style>

<script src="{{ asset('buku/js/support.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#layanan-mandiri').click(function(event) {
            window.location.replace('{{ base_url('layanan-mandiri') }}');
        });

        $('#registrasi').click(function(event) {
            window.location.replace('{{ site_url('buku-tamu') }}');
        });

        $('#kepuasan').click(function(event) {
            window.location.replace('{{ base_url('buku-tamu/kepuasan') }}');
        });
    });
</script>
@stack('scripts')

</html>
