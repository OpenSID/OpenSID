<link rel="stylesheet" href="<?= asset('bootstrap/css/font-awesome.min.css') ?>">
<link rel="stylesheet" href="<?= asset('css/leaflet-measure-path.css') ?>">
<link rel="stylesheet" href="<?= asset('css/MarkerCluster.css') ?>">
<link rel="stylesheet" href="<?= asset('css/MarkerCluster.Default.css') ?>">
<link rel="stylesheet" href="<?= asset('css/leaflet.groupedlayercontrol.min.css') ?>">
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
        <?php $this->load->view('gis/cetak_peta') ?>
        <div class="leaflet-top leaflet-left">
          <?php $this->load->view('gis/content_desa_web.php', ['desa' => $desa, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_desa . ' ' . $desa['nama_desa'])]) ?>
          <?php $this->load->view('gis/content_dusun_web.php', ['dusun_gis' => $dusun_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
          <?php $this->load->view('gis/content_rw_web.php', ['rw_gis' => $rw_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
          <?php $this->load->view('gis/content_rt_web.php', ['rt_gis' => $rt_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
        </div>
        <div id="desa_online" style="display: none;">
          <div class="leaflet-top leaflet-right">
            <section class="content">
              <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="fa fa-map-marker">
                    <H5 class="info legend">NEGARA</H5>
                  </i></span>
                <div class="info-box-content">
                  <span class="info-box-text nama_negara"></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="info-box-number jml_desa"></span>
                  <span class="progress-description"><i>Desa OpenSID Aktif</i></span>
                </div>
              </div>
              <div class="info-box bg-red">
                <span class="info-box-icon"><i class="fa fa-map-marker">
                    <h5 class="info legend">PROV.</h5>
                  </i></span>
                <div class="info-box-content">
                  <span class="info-box-text nama_prov"></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="info-box-number jml_desa_prov"></span>
                  <span class="progress-description"><i>Desa OpenSID Aktif</i></span>
                </div>
              </div>
              <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-map-marker">
                    <h5 class="info legend">KAB.</h5>
                  </i></span>
                <div class="info-box-content">
                  <span class="info-box-text nama_kab"></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="info-box-number jml_desa_kab"></span>
                  <span class="progress-description"><i>Desa OpenSID Aktif</i></span>
                </div>
              </div>
              <div class="info-box bg-blue">
                <span class="info-box-icon"><i class="fa fa-map-marker">
                    <h5 class="info legend">KEC.</h5>
                  </i></span>
                <div class="info-box-content">
                  <span class="info-box-text nama_kec"></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="info-box-number jml_desa_kec"></span>
                  <span class="progress-description"><i>Desa OpenSID Aktif</i></span>
                </div>
              </div>
            </section>
          </div>
        </div>
        <div class="leaflet-top leaflet-right">
          <div id="covid_status_local" style="display: none;">
            <?php if ($this->setting->covid_desa) {
                $this->load->view('gis/covid_peta_local.php');
            } ?>
          </div>
        </div>
        <div class="leaflet-bottom leaflet-left">
          <div id="qrcode">
            <div class="panel-body-lg">
              <a href="https://github.com/OpenSID/OpenSID">
                <img src="<?= asset('images/opensid.png') ?>" alt="OpenSID">
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

      //Inisialisasi tampilan peta
      var mymap = L.map('map').setView(posisi, zoom);

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

      //OVERLAY WILAYAH DESA
      <?php if (! empty($desa['path'])) : ?>
        set_marker_desa_content(marker_desa, <?= json_encode($desa) ?>, "<?= ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa'] ?>", "<?= favico_desa() ?>", '#isi_popup');
      <?php endif; ?>

      //OVERLAY WILAYAH DUSUN
      <?php if (! empty($dusun_gis)) : ?>
        set_marker_multi_content(marker_dusun, '<?= addslashes(json_encode($dusun_gis)) ?>', '<?= ucwords($this->setting->sebutan_dusun) ?>', 'dusun', '#isi_popup_dusun_', '<?= favico_desa() ?>');
      <?php endif; ?>

      //OVERLAY WILAYAH RW
      <?php if (! empty($rw_gis)) : ?>
        set_marker_content(marker_rw, '<?= addslashes(json_encode($rw_gis)) ?>', 'RW', 'rw', '#isi_popup_rw_', '<?= favico_desa() ?>');
      <?php endif; ?>

      //OVERLAY WILAYAH RT
      <?php if (! empty($rt_gis)) : ?>
        set_marker_content(marker_rt, '<?= addslashes(json_encode($rt_gis)) ?>', 'RT', 'rt', '#isi_popup_rt_', '<?= favico_desa() ?>');
      <?php endif; ?>



      //Menampilkan overlayLayers Peta Semua Wilayah
      var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "<?= ucwords($this->setting->sebutan_desa) ?>", "<?= ucwords($this->setting->sebutan_dusun) ?>", true);

      //Menampilkan BaseLayers Peta
      var baseLayers = getBaseLayers(mymap, '<?= $this->setting->mapbox_key ?>');

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
          setlegendPetaDesa(legenda_desa, mymap, <?= json_encode($desa) ?>, '<?= ucwords($this->setting->sebutan_desa) ?>', '<?= $desa['nama_desa'] ?>');
        }
        if (eventLayer.name === 'Peta Wilayah Dusun') {
          setlegendPeta(legenda_dusun, mymap, '<?= addslashes(json_encode($dusun_gis)) ?>', '<?= ucwords($this->setting->sebutan_dusun) ?>', 'dusun', '', '');
        }
        if (eventLayer.name === 'Peta Wilayah RW') {
          setlegendPeta(legenda_rw, mymap, '<?= addslashes(json_encode($rw_gis)) ?>', 'RW', 'rw', '<?= ucwords($this->setting->sebutan_dusun) ?>');
        }
        if (eventLayer.name === 'Peta Wilayah RT') {
          setlegendPeta(legenda_rt, mymap, '<?= addslashes(json_encode($rt_gis)) ?>', 'RT', 'rt', 'RW');
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
      var layerCustom = tampilkan_layer_area_garis_lokasi_plus(mymap, '<?= addslashes(json_encode($area)) ?>', '<?= addslashes(json_encode($garis)) ?>', '<?= addslashes(json_encode($lokasi)) ?>', '<?= addslashes(json_encode($lokasi_pembangunan)) ?>', '<?= base_url() . LOKASI_SIMBOL_LOKASI ?>', "<?= favico_desa() ?>", '<?= base_url() . LOKASI_FOTO_AREA ?>', '<?= base_url() . LOKASI_FOTO_GARIS ?>', '<?= base_url() . LOKASI_FOTO_LOKASI ?>', '<?= base_url() . LOKASI_GALERI ?>', '<?= site_url('pembangunan/') ?>');

      // Menampilkan OverLayer Covid dan Desa Pengguna OpenSID
      var mylayer = L.featureGroup();
      var layer_desa = L.featureGroup();
      var layer_desa_sid = {
        "Desa Pengguna OpenSID": layer_desa
      }

      mylayer.on('add', function() {
        setTimeout(function() {
          var bounds = new L.LatLngBounds();
          if (mylayer instanceof L.FeatureGroup) {
            bounds.extend(mylayer.getBounds());
            mark_covid = L.marker([<?= $desa['lat'] . ',' . $desa['lng'] ?>]).addTo(mymap)
              .bindTooltip(<?= json_encode(ucwords($this->setting->sebutan_desa . ' ' . $desa['nama_desa'])) ?> + ' berada di lokasi ini', {
                direction: 'top'
              });
          }
          if (bounds.isValid()) {
            mymap.fitBounds(bounds);
          } else {
            <?php if (! empty($desa['path'])) : ?>
              mymap.fitBounds(<?= $desa['path'] ?>);
            <?php endif; ?>
          }
          $('#covid_status').show();
          $('#covid_status_local').show();
        });
      });

      mylayer.on('remove', function() {
        setTimeout(function() {
          mymap.removeLayer(mark_covid);
          $('#covid_status').hide();
          $('#covid_status_local').hide();
          <?php if (! empty($desa['path'])) : ?>
            mymap.fitBounds(<?= $desa['path'] ?>);
          <?php endif; ?>
        });
      });

      //loading Peta Desa Pengguna OpenSID (Data dari API Server)
      pantau_desa(layer_desa, '<?= config_item('server_pantau'); ?>', <?= json_encode($desa['kode_desa']) ?>, "<?= favico_desa() ?>", "<?= config_item('token_pantau'); ?>");

      layer_desa.on('add', function() {
        setTimeout(function() {
          var bounds = new L.LatLngBounds();
          if (layer_desa instanceof L.FeatureGroup) {
            bounds.extend(layer_desa.getBounds());
            mark_desa = L.marker([<?= $desa['lat'] . ',' . $desa['lng'] ?>]).addTo(mymap)
              .bindTooltip(<?= json_encode(ucwords($this->setting->sebutan_desa . ' ' . $desa['nama_desa'])) ?> + ' berada di lokasi ini', {
                direction: 'top'
              });
          }
          if (bounds.isValid()) {
            mymap.fitBounds(bounds);
          } else {
            <?php if (! empty($desa['path'])) : ?>
              mymap.fitBounds(<?= $desa['path'] ?>);
            <?php endif; ?>
          }
          $('#desa_online').show();
        });
      });

      layer_desa.on('remove', function() {
        setTimeout(function() {
          $('#desa_online').hide();
          mymap.removeLayer(mark_desa);
          <?php if (! empty($desa['path'])) : ?>
            mymap.fitBounds(<?= $desa['path'] ?>);
          <?php endif; ?>
        });
      });

      L.control.layers(baseLayers, overlayLayers, {
        position: 'topleft',
        collapsed: true
      }).addTo(mymap);
      L.control.groupedLayers('', layerCustom, {
        groupCheckboxes: true,
        position: 'topleft',
        collapsed: true
      }).addTo(mymap);
      L.control.layers('', layer_desa_sid, {
        position: 'topleft',
        collapsed: true
      }).addTo(mymap);

      $('#isi_popup_dusun').remove();
      $('#isi_popup_rw').remove();
      $('#isi_popup_rt').remove();
      $('#isi_popup').remove();

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