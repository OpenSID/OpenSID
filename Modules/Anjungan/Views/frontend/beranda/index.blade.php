<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Layanan Mandiri {{ ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? '')) . get_dynamic_title_page_from_path() }}</title>

    <link rel="shortcut icon" href="{{ favico_desa() }}" />

    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    <link href="{{ module_asset('anjungan', 'css/custom/style.css') }}" rel="stylesheet">
    <link href="{{ module_asset('anjungan', 'css/custom/screen.css') }}" rel="stylesheet">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap3-wysihtml5.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/select2.min.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
    <!-- Bootstrap Date time Picker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datetimepicker.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. -->
    <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}">

    @if (cek_koneksi_internet())
        <!-- Form Wizard - smartWizard -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard_all.min.css">
    @endif

    @if ($controller == 'lapak')
        <!-- Map -->
        <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
        <link rel="stylesheet" href="{{ asset('css/mapbox-gl.css') }}">
        <link rel="stylesheet" href="{{ asset('css/peta.css') }}">
    @endif

    <!-- Style Mandiri Modification CSS -->
    {{-- tampilan beranda anjungan baru jadi tidak sesuai jika pakai ini --}}
    {{--
    <link rel="stylesheet" href="{{ asset('css/mandiri-style.css') }}"> --}}

    <!-- Jquery Confirm -->
    <link rel="stylesheet" href="{{ asset('front/css/jquery-confirm.min.css') }}">
    <!-- Jquery UI -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/jquery-ui.min.css') }}">
    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <!-- Diperlukan untuk global automatic base_url oleh external js file -->
    <script type="text/javascript">
        const BASE_URL = "{{ base_url() }}";
        const SITE_URL = "{{ site_url() }}";
    </script>
    @if ($cek_anjungan)
        <!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
        <link rel="stylesheet" href="{{ asset('css/keyboard.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/mandiri-keyboard.css') }}">
    @endif

    @include('admin.layouts.components.token')

    <style>
        .profil-area {
            padding: 11px;
            height: 100%;
        }

        .box-body table {
            font-size: medium;
        }

        .box {
            margin-bottom: 0px;
        }

        #wrapper-mandiri .tdk-permohonan,
        .breadcrumb.admin,
        .box-header.admin,
        .tdk-periksa,
        .jar_form {
            display: none;
        }

        .box.box-solid {
            height: 100%;
        }

        .content-header {
            padding-top: 0;
        }

        .side-menu-wrapper {
            height: 62%;
            display: flex;
            align-content: space-around;
            justify-content: center;
            align-items: center;
        }
    </style>

    @stack('css')
</head>

<body>

    <div class="full-container" id="element">

        <!-- Mulai Latar -->
        <div class="bg-image">
            <img src="{{ asset('buku_tamu/images/background.jpg') }}">
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
                    </div>
                </div>
            </div>
        </div>
        <!-- Batas Header -->
        <div class="relhid margin-master">
            <div class="grider mainmargin">

                <!-- Mulai Kolom Kiri -->
                <div class="area-title">
                    <div class="profil-area">
                        <div class="box box-solid">
                            <div class="box-body box-line text-center" style="margin-bottom: 10px;">
                                <img class="img-circle my-2" src="{{ AmbilFoto(auth_mandiri()->foto, '', auth_mandiri()->id_sex) }}" alt="Foto" width="50%" style="margin-top: 10px;">
                            </div>
                            <div class="box-body" style="height: 218px;">
                                <table class="table">
                                    <tr>
                                        <td width="80px;">Nama</td>
                                        <td>:</td>
                                        <td>{{ auth_mandiri()->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>No. KK</td>
                                        <td>:</td>
                                        <td>{{ auth_mandiri()->keluarga->no_kk }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIK</td>
                                        <td>:</td>
                                        <td>{{ auth_mandiri()->nik }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td>{{ auth_mandiri()->alamat_wilayah }}</td>
                                    </tr>
                                </table>
                                @if (!$beranda)
                                    <div class="side-menu-wrapper">
                                        <div class="form-group text-center">
                                            <a href="{{ route('anjungan.beranda.index') }}" class="btn bg-aqua btn-social">
                                                <i class="fa fa-arrow-circle-left"></i>Kembali
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Batas Kolom Kiri -->

                @yield('content')

            </div>
        </div>
    </div>

</body>

<script src="{{ asset('bootstrap/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/jquery.ui.autocomplete.scroll.min.js') }}"></script>

<script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
<script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('bootstrap/js/select2.full.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/dataTables.rowsgroup.min.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- bootstrap Date time picker -->
<script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/id.js') }}"></script>
<!-- bootstrap Date picker -->
<script src="{{ asset('bootstrap/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap-datepicker.id.min.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('bootstrap/js/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<script src="{{ asset('front/js/jquery.overlay.min.js') }}"></script>
<script src="{{ asset('front/js/jquery-confirm.min.js') }}"></script>

<!-- Sweetalert JS -->
<script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>

<!-- Validasi js -->
@include('admin.layouts.components.validasi_form')
<!-- Numeral js -->
<script src="{{ asset('js/numeral.min.js') }}"></script>
<!-- Khusus modul layanan mandiri -->
<script src="{{ asset('front/js/mandiri.js') }}"></script>

@if ($cek_anjungan)
    <!-- keyboard widget script -->
    <script src="{{ asset('js/jquery.keyboard.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.keyboard.extension-all.min.js') }}"></script>
    <script src="{{ asset('front/js/mandiri-keyboard.js') }}"></script>
@endif
<script type="text/javascript">
    $(window).on('load', function() {
        $('#notif').modal('show');
    });

    $('document').ready(function() {

        window.setTimeout(function() {
            $("#notifikasi").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 1000);

        setTimeout(function() {
            refresh_badge($("#b_pesan"), "{{ site_url('notif_web/inbox') }}");
            refresh_badge($("#b_surat"), "{{ site_url('notif_web/surat_perlu_perhatian') }}");
        }, 500);

        $.extend($.fn.dataTable.defaults, {
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"]
            ],
            pageLength: 10,
            language: {
                url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}",
            }
        });
    });
</script>

@if (cek_koneksi_internet())
    <!-- Form Wizard - jquery.smartWizard -->
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js"></script>
@endif

@stack('scripts')

</html>
