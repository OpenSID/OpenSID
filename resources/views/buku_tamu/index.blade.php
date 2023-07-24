<!DOCTYPE html>
<html lang="en">

<head>
    <title>Buku Tamu</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('buku_tamu/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('buku_tamu/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    @stack('css')
</head>

<body class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <div class="toolbar py-5 py-lg-5" id="kt_toolbar">
                    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
                        <div class="page-title d-flex flex-column me-3">
                            <div class="d-flex flex-wrap flex-sm-nowrap">
                                <div class="me-7 mb-4">
                                    <img src="{{ gambar_desa($desa->logo) }}" alt="{{ $desa->nama_desa }}"
                                        style="max-height: 100px">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                        <div class="d-flex flex-column">
                                            <h1 class="d-flex align-items-center text-white mb-2">
                                                {{ strtoupper('Pemerintah ' . setting('sebutan_desa') . ' ' . $desa->nama_desa) }}
                                            </h1>
                                            <h3 class="d-flex align-items-center text-white mb-2">
                                                {{ strtoupper(setting('sebutan_kecamatan') . ' ' . $desa->nama_kecamatan) }}
                                            </h3>
                                            <h3 class="d-flex align-items-center text-white mb-2">
                                                {{ strtoupper(setting('sebutan_kabupaten') . ' ' . $desa->nama_kabupaten) }}
                                            </h3>
                                            <h3 class="d-flex align-items-center text-white mb-2">
                                                {{ strtoupper('Provinsi ' . $desa->nama_propinsi) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center py-3 py-md-1">
                            <a href="{{ base_url('layanan-mandiri') }}"
                                class="btn btn-bg-white btn-active-color-primary">Layanan Mandiri</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column-fluid align-items-start container-xxl">
                    <div class="content flex-row-fluid">
                        <div class="row gy-5 g-xl-5">
                            <div class="col-xl-4 mb-xl-10">
                                <div class="card bg-primary h-md-100" data-theme="light">
                                    <div class="card-body d-flex flex-column pt-13 pb-14">
                                        <div class="m-0">
                                            <h3 class="fw-semibold text-white text-center lh-lg mb-5">Selamat Datang di
                                                Layanan</h3>
                                            <h1 class="fw-semibold text-white text-center lh-lg mb-9">
                                                <span class="fw-bolder">Buku Tamu</span>
                                            </h1>
                                            <div class="flex-grow-1 bgi-no-repeat bgi-size-contain bgi-position-x-center card-rounded-bottom h-200px mh-200px my-5 mb-lg-12"
                                                style="background-image:url('{{ asset('buku_tamu/ebook.png') }}')">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <a href="{{ site_url('buku-tamu') }}"
                                                class="btn btn-sm bg-white btn-color-gray-800 me-5">Registrasi Tamu</a>
                                            <a href="{{ site_url('buku-tamu/kepuasan') }}"
                                                class="btn btn-sm bg-white btn-color-gray-800">Indeks Kepuasan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @yield('content')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('buku_tamu/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    @if (!setting('inspect_element'))
        <script src="{{ asset('js/disabled.min.js') }}"></script>
    @endif
    <script>
        var success = `{!! session('success') !!}`;
        var error = `{!! session('error') !!}`;

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
    @stack('scripts')
</body>
<style type="text/css">
    .form-group.has-error label {
      color: #dd4b39;
      font-weight: 700;
    }
    .error {
        color: #dd4b39;
        font-weight: 700;
    }
</style>
</html>
