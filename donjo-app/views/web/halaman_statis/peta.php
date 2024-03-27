<link rel="stylesheet" href="<?= asset('bootstrap/css/font-awesome.min.css') ?>">
<link rel="stylesheet" href="<?= asset('css/leaflet-measure-path.css') ?>">
<link rel="stylesheet" href="<?= asset('css/MarkerCluster.css') ?>">
<link rel="stylesheet" href="<?= asset('css/MarkerCluster.Default.css') ?>">
<link rel="stylesheet" href="<?= asset('css/leaflet.groupedlayercontrol.min.css') ?>">
<link rel="stylesheet" href="<?= asset('css/leaflet.fullscreen.css') ?>" />
<style>
  #map .leaflet-popup-content {
    height: auto;
    overflow-y: auto;
  }

  table {
    table-layout: fixed;
    white-space: normal !important;
  }

  td {
    word-wrap: break-word;
  }

  .persil {
    min-width: 350px;
  }

  .persil td {
    padding-right: 1rem;
  }
</style>

<form id="mainform_map" name="mainform_map" method="post">
  <div class="row">
    <div class="col-md-12">
      <div id="map">
        <?php $this->load->view('gis/cetak_peta', ['wil_atas' => $desa]) ?>
        <div class="leaflet-top leaflet-left">
          <?php $this->load->view('gis/content_desa_web.php', ['desa' => $desa, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_desa . ' ' . $desa['nama_desa'])]) ?>
          <?php $this->load->view('gis/content_dusun_web.php', ['dusun_gis' => $dusun_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
          <?php $this->load->view('gis/content_rw_web.php', ['rw_gis' => $rw_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
          <?php $this->load->view('gis/content_rt_web.php', ['rt_gis' => $rt_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
        </div>
        <div class="leaflet-bottom leaflet-left">
          <div id="qrcode">
            <div class="panel-body-lg">
              <a href="https://github.com/OpenSID/OpenSID">
                <img src="<?= to_base64(GAMBAR_QRCODE) ?>" alt="OpenSID">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="modal fade" id="modalKecil" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog modal-sm">
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h4 class='modal-title' id='myModalLabel'></h4>
      </div>
      <div class="fetched-data"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalSedang" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog">
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h4 class='modal-title' id='myModalLabel'></h4>
      </div>
      <div class="fetched-data"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalBesar" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i></h4>
      </div>
      <div class="fetched-data"></div>
    </div>
  </div>
</div>
<script src="<?= asset('js/Leaflet.fullscreen.min.js') ?>"></script>
<script>
  (function() {
    var infoWindow;
    window.onload = function() {
      <?php if (! empty($desa['lat']) && ! empty($desa['lng'])) : ?>
        var posisi = [<?= $desa['lat'] . ',' . $desa['lng'] ?>];
        var zoom = <?= $desa['zoom'] ?: 10 ?>;
      <?php elseif (! empty($desa['path'])) : ?>
        var wilayah_desa = <?= $desa['path'] ?>;
        var posisi = wilayah_desa[0][0];
        var zoom = <?= $desa['zoom'] ?: 10 ?>;
      <?php else : ?>
        var posisi = [-1.0546279422758742, 116.71875000000001];
        var zoom = 10;
      <?php endif; ?>

      var options = {
        maxZoom: <?= setting('max_zoom_peta') ?>,
        minZoom: <?= setting('min_zoom_peta') ?>,
        fullscreenControl: {
          position: 'topright' // Menentukan posisi tombol fullscreen
        }
      };

      //Inisialisasi tampilan peta
      var mymap = L.map('map', options).setView(posisi, zoom);

      <?php if (! empty($desa['path'])) : ?>
        mymap.fitBounds(<?= $desa['path'] ?>);
      <?php endif; ?>

      //Menampilkan overlayLayers Peta Semua Wilayah
      var marker_desa = [];
      var marker_dusun = [];
      var marker_rw = [];
      var marker_rt = [];
      var marker_area = [];
      var marker_garis = [];
      var marker_lokasi = [];
      var markers = new L.MarkerClusterGroup();
      var markersList = [];
      var marker_legend = [];
      var mark_desa = [];
      var mark_covid = [];

      // deklrasi variabel agar mudah di baca
      var all_area = '<?= addslashes(json_encode($area, JSON_THROW_ON_ERROR)) ?>';
      var all_garis = '<?= addslashes(json_encode($garis, JSON_THROW_ON_ERROR)) ?>';
      var all_lokasi = '<?= addslashes(json_encode($lokasi, JSON_THROW_ON_ERROR)) ?>';
      var all_lokasi_pembangunan = '<?= addslashes(json_encode($lokasi_pembangunan, JSON_THROW_ON_ERROR)) ?>';
      var LOKASI_SIMBOL_LOKASI = '<?= base_url(LOKASI_SIMBOL_LOKASI) ?>';
      var favico_desa = '<?= favico_desa() ?>';
      var LOKASI_FOTO_AREA = '<?= base_url(LOKASI_FOTO_AREA) ?>';
      var LOKASI_FOTO_GARIS = '<?= base_url(LOKASI_FOTO_GARIS) ?>';
      var LOKASI_FOTO_LOKASI = '<?= base_url(LOKASI_FOTO_LOKASI) ?>';
      var LOKASI_GALERI = '<?= base_url(LOKASI_GALERI) ?>';
      var info_pembangunan = '<?= site_url('pembangunan') ?>';
      var all_persil = '<?= addslashes(json_encode($persil, JSON_THROW_ON_ERROR)) ?>';
      var TAMPIL_LUAS = '<?= setting('tampil_luas_peta') ?>';
      var PENGATURAN_WILAYAH = '<?= SebutanDesa(setting('default_tampil_peta_wilayah')) ?: [] ?>';
      var PENGATURAN_INFRASTRUKTUR = '<?= SebutanDesa(setting('default_tampil_peta_infrastruktur')) ?: [] ?>';
      var WILAYAH_INFRASTRUKTUR = PENGATURAN_WILAYAH.concat(PENGATURAN_INFRASTRUKTUR);

      console.log(PENGATURAN_WILAYAH);

      //OVERLAY WILAYAH DESA
      <?php if (! empty($desa['path'])) : ?>
        set_marker_desa_content(marker_desa, <?= json_encode($desa, JSON_THROW_ON_ERROR) ?>, "<?= ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa'] ?>", "<?= favico_desa() ?>", '#isi_popup');
      <?php endif; ?>

      //OVERLAY WILAYAH DUSUN
      <?php if (! empty($dusun_gis)) : ?>
        set_marker_multi_content(marker_dusun, '<?= addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) ?>', '<?= ucwords($this->setting->sebutan_dusun) ?>', 'dusun', '#isi_popup_dusun_', '<?= favico_desa() ?>');
      <?php endif; ?>

      //OVERLAY WILAYAH RW
      <?php if (! empty($rw_gis)) : ?>
        set_marker_content(marker_rw, '<?= addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) ?>', 'RW', 'rw', '#isi_popup_rw_', '<?= favico_desa() ?>');
      <?php endif; ?>

      //OVERLAY WILAYAH RT
      <?php if (! empty($rt_gis)) : ?>
        set_marker_content(marker_rt, '<?= addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) ?>', 'RT', 'rt', '#isi_popup_rt_', '<?= favico_desa() ?>');
      <?php endif; ?>

      //Menampilkan overlayLayers Peta Semua Wilayah
      var overlayLayers = overlayWil(
        marker_desa,
        marker_dusun,
        marker_rw,
        marker_rt,
        "<?= ucwords($this->setting->sebutan_desa) ?>",
        "<?= ucwords($this->setting->sebutan_dusun) ?>",
        true,
        TAMPIL_LUAS.toString()
      );

      //Menampilkan BaseLayers Peta
      var baseLayers = getBaseLayers(mymap, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");

      //Geolocation IP Route/GPS
      geoLocation(mymap);

      //Menambahkan zoom scale ke peta
      L.control.scale().addTo(mymap);

      //Mencetak peta ke PNG
      cetakPeta(mymap);

      //Menambahkan Legenda Ke Peta
      var legenda_desa = L.control({
        position: 'bottomright'
      });
      var legenda_dusun = L.control({
        position: 'bottomright'
      });
      var legenda_rw = L.control({
        position: 'bottomright'
      });
      var legenda_rt = L.control({
        position: 'bottomright'
      });

      mymap.on('overlayadd', function(eventLayer) {
        if (eventLayer.name === 'Peta Wilayah Desa') {
          setlegendPetaDesa(legenda_desa, mymap, <?= json_encode($desa, JSON_THROW_ON_ERROR) ?>, '<?= ucwords($this->setting->sebutan_desa) ?>', '<?= $desa['nama_desa'] ?>');
        }
        if (eventLayer.name === 'Peta Wilayah Dusun') {
          setlegendPeta(legenda_dusun, mymap, '<?= addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) ?>', '<?= ucwords($this->setting->sebutan_dusun) ?>', 'dusun', '', '');
        }
        if (eventLayer.name === 'Peta Wilayah RW') {
          setlegendPeta(legenda_rw, mymap, '<?= addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) ?>', 'RW', 'rw', '<?= ucwords($this->setting->sebutan_dusun) ?>');
        }
        if (eventLayer.name === 'Peta Wilayah RT') {
          setlegendPeta(legenda_rt, mymap, '<?= addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) ?>', 'RT', 'rt', 'RW');
        }
      });

      mymap.on('overlayremove', function(eventLayer) {
        if (eventLayer.name === 'Peta Wilayah Desa') {
          mymap.removeControl(legenda_desa);
        }
        if (eventLayer.name === 'Peta Wilayah Dusun') {
          mymap.removeControl(legenda_dusun);
        }
        if (eventLayer.name === 'Peta Wilayah RW') {
          mymap.removeControl(legenda_rw);
        }
        if (eventLayer.name === 'Peta Wilayah RT') {
          mymap.removeControl(legenda_rt);
        }
      });

      // Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan
      var layerCustom = tampilkan_layer_area_garis_lokasi_plus(
        mymap,
        all_area,
        all_garis,
        all_lokasi,
        all_lokasi_pembangunan,
        LOKASI_SIMBOL_LOKASI,
        favico_desa,
        LOKASI_FOTO_AREA,
        LOKASI_FOTO_GARIS,
        LOKASI_FOTO_LOKASI,
        LOKASI_GALERI,
        info_pembangunan,
        all_persil,
        TAMPIL_LUAS.toString()
      );

      L.control.layers(baseLayers, overlayLayers, {
        position: 'topleft',
        collapsed: true
      }).addTo(mymap);
      L.control.groupedLayers('', layerCustom, {
        groupCheckboxes: true,
        position: 'topleft',
        collapsed: true
      }).addTo(mymap);

      $('#isi_popup_dusun').remove();
      $('#isi_popup_rw').remove();
      $('#isi_popup_rt').remove();
      $('#isi_popup').remove();

      $('input[type=checkbox]').each(function() {
        if (WILAYAH_INFRASTRUKTUR.includes($(this).next().text().trim())) {
          $(this).click();
        }
      });

    }; //EOF window.onload

  })();
</script>

<script src="<?= asset('js/turf.min.js') ?>"></script>
<script src="<?= asset('js/leaflet-providers.js') ?>"></script>
<script src="<?= asset('js/L.Control.Locate.min.js') ?>"></script>
<script src="<?= asset('js/leaflet-measure-path.js') ?>"></script>
<script src="<?= asset('js/leaflet.markercluster.js') ?>"></script>
<script src="<?= asset('js/leaflet.groupedlayercontrol.min.js') ?>"></script>
<script src="<?= asset('js/leaflet.browser.print.js') ?>"></script>
<script src="<?= asset('js/leaflet.browser.print.utils.js') ?>"></script>
<script src="<?= asset('js/leaflet.browser.print.sizes.js') ?>"></script>
<script src="<?= asset('js/dom-to-image.min.js') ?>"></script>
<script src="<?= asset('js/script.js') ?>"></script>