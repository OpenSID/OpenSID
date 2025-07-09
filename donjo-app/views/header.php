<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?= $this->setting->admin_title . ' ' . ucwords($this->setting->sebutan_desa) . (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : '') . get_dynamic_title_page_from_path() ?>
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="<?= favico_desa() ?>" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url('rss.xml') ?>" />


    <!-- lazy load images -->
    <script src="<?= asset('js/progressive-image/progressive-image.js') ?>"></script>
    <link rel="stylesheet" href="<?= asset('js/progressive-image/progressive-image.css') ?>">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap.min.css') ?>">
    <!-- Jquery UI -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/jquery-ui.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/font-awesome.min.css') ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/ionicons.min.css') ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/dataTables.bootstrap.min.css') ?>">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap3-wysihtml5.min.css') ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/select2.min.css') ?>">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-colorpicker.min.css') ?>">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-datepicker.min.css') ?>">
    <!-- boostrap datetimepicker -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-datetimepicker.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= asset('css/AdminLTE.min.css') ?>">
    <!-- AdminLTE Skins. -->
    <link rel="stylesheet" href="<?= asset('css/skins/_all-skins.min.css') ?>">
    <!-- Style Admin Modification Css -->
    <!-- Token Field -->
    <?php if ($this->controller == 'bumindes_kader') : ?>
        <link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-tokenfield.min.css') ?>">
    <?php endif ?>
    <link rel="stylesheet" href="<?= asset('css/admin-style.css') ?>">
    <!-- OpenStreetMap Css -->
    <link rel="stylesheet" href="<?= asset('css/leaflet.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/leaflet-geoman.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/L.Control.Locate.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/MarkerCluster.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/MarkerCluster.Default.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/leaflet-measure-path.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/mapbox-gl.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/L.Control.Shapefile.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/leaflet.groupedlayercontrol.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/peta.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/toastr.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/leaflet.fullscreen.css') ?>" />

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="<?= asset('js/sweetalert2/sweetalert2.min.css') ?>">

    <style>
        @media (max-width: 576px) {
            .komunikasi-opendk {
                display: none !important;
            }
        }
    </style>

    <!-- Untuk ubahan style desa -->
    <?php if (is_file('desa/css/siteman.css')) : ?>
        <link rel='Stylesheet' href="<?= base_url('desa/css/siteman.css') ?>">
    <?php endif ?>
    <!-- Diperlukan untuk script jquery khusus halaman -->
    <script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>

    <!-- OpenStreetMap Js-->
    <script src="<?= asset('js/leaflet.js') ?>"></script>
    <script src="<?= asset('js/turf.min.js') ?>"></script>
    <script src="<?= asset('js/leaflet-geoman.min.js') ?>"></script>
    <script src="<?= asset('js/leaflet.filelayer.js') ?>"></script>
    <script src="<?= asset('js/togeojson.js') ?>"></script>
    <script src="<?= asset('js/togpx.js') ?>"></script>
    <script src="<?= asset('js/leaflet-providers.js') ?>"></script>
    <script src="<?= asset('js/L.Control.Locate.min.js') ?>"></script>
    <script src="<?= asset('js/leaflet.markercluster.js') ?>"></script>
    <script src="<?= asset('js/peta.js') ?>"></script>
    <script src="<?= asset('js/leaflet-measure-path.js') ?>"></script>
    <script src="<?= asset('js/apbdes_manual.js') ?>"></script>
    <script src="<?= asset('js/mapbox-gl.js') ?>"></script>
    <script src="<?= asset('js/leaflet-mapbox-gl.js') ?>"></script>
    <script src="<?= asset('js/shp.js') ?>"></script>
    <script src="<?= asset('js/leaflet.shpfile.js') ?>"></script>
    <script src="<?= asset('js/leaflet.groupedlayercontrol.min.js') ?>"></script>
    <script src="<?= asset('js/leaflet.browser.print.js') ?>"></script>
    <script src="<?= asset('js/leaflet.browser.print.utils.js') ?>"></script>
    <script src="<?= asset('js/leaflet.browser.print.sizes.js') ?>"></script>
    <script src="<?= asset('js/dom-to-image.min.js') ?>"></script>
    <script src="<?= asset('js/toastr.min.js') ?>"></script>


    <!-- Diperlukan untuk global automatic base_url oleh external js file -->
    <script type="text/javascript">
        var BASE_URL = "<?= base_url() ?>";
        var SITE_URL = "<?= site_url() ?>";
        var MAPBOX_KEY = '<?= setting('mapbox_key') ?>';
        var JENIS_PETA = '<?= setting('jenis_peta') ?>';
        var TAMPIL_LUAS = "<?= setting('tampil_luas_peta') ?>";
    </script>

    <!-- Highcharts JS -->
    <script src="<?= asset('js/highcharts/highcharts.js') ?>"></script>
    <script src="<?= asset('js/highcharts/highcharts-3d.js') ?>"></script>
    <script src="<?= asset('js/highcharts/exporting.js') ?>"></script>
    <script src="<?= asset('js/highcharts/highcharts-more.js') ?>"></script>
    <script src="<?= asset('js/highcharts/sankey.js') ?>"></script>
    <script src="<?= asset('js/highcharts/organization.js') ?>"></script>
    <script src="<?= asset('js/highcharts/accessibility.js') ?>"></script>

</head>

<body id="sidebar_collapse" class="<?= $this->setting->warna_tema_admin ?> sidebar-mini fixed">
    <div class="wrapper">
        <?= view('admin.layouts.partials.header') ?>
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

        <!-- Untuk menampilkan pengaturan -->
        <?php if ($this->kategori_pengaturan && $this->kategori_pengaturan !== 'pelanggan' && can('u', $this->sub_modul_ini ?? $this->modul_ini)) : ?>
            <div class="modal fade" id="pengaturan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel"> Pengaturan <?= ucwords(str_replace('_', ' ', $this->kategori_pengaturan)) ?></h4>
                        </div>
                        <?php $this->load->view('global/modal_setting', ['kategori_pengaturan' => [$this->kategori_pengaturan]]) ?>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php
        if ($notif_pengumuman) :
            $this->load->view('notif/pengumuman', $notif_pengumuman);
        endif
        ?>