<?php
/**
 * File ini:
 *
 * View di modul Pemetaan
 *
 * /donjo-app/views/garis/maps.php
 */
?>

<!-- TODO: Pindahkan ke external css -->
<style>
  #map
  {
    width:100%;
    height:65vh
  }
  .icon {
    max-width: 70%;
    max-height: 70%;
    margin: 4px;
  }
  .leaflet-control-layers {
  	display: block;
  	position: relative;
  }
  .leaflet-control-locate a {
  font-size: 2em;
	}
</style>
<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
  <section class="content-header">
		<h1>Peta <?= $garis['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('garis')?>"> Pengaturan garis </a></li>
			<li class="active">Peta <?= $garis['nama']?></li>
		</ol>
	</section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <form action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="box-body">
              <div class="row">
                <div class="col-sm-12">
                  <div id="map">
                    <input type="hidden" id="path" name="path" value="<?= $garis['path']; ?>">
                    <input type="hidden" name="id" id="id"  value="<?= $garis['id']; ?>"/>
                  </div>
                </div>
              </div>
            </div>
            <div class='box-footer'>
              <div class='col-xs-12'>
                <a href="<?= site_url('garis')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                <?php if ($this->CI->cek_hak_akses('u')): ?>
                  <a href="#" data-href="<?= site_url("garis/kosongkan/{$garis['id']}")?>" class="btn btn-social btn-flat bg-maroon btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kosongkan Garis" data-toggle="modal" data-target="#confirm-status" data-body="Apakah yakin akan mengosongkan garis wilayah ini?"><i class="fa fa fa-trash-o"></i>Kosongkan</a>
                <?php endif; ?>
                <a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
                <?php if ($this->CI->cek_hak_akses('u')): ?>
                  <button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right' id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('global/konfirmasi'); ?>
<script>
  var infoWindow;
  window.onload = function()
  {
    <?php if (! empty($desa['lat']) && ! empty($desa['lng'])): ?>
      var posisi = [<?=$desa['lat'] . ',' . $desa['lng']?>];
      var zoom = <?= $desa['zoom'] ?? 18 ?>;
    <?php else: ?>
      var posisi = [-1.0546279422758742,116.71875000000001];
      var zoom = 18;
    <?php endif; ?>

    var jenis = "<?= $garis['jenis_garis']; ?>";
    var tebal = "<?= $garis['tebal']; ?>";
    var warna = "<?= $garis['color']; ?>";

  	//Inisialisasi tampilan peta
    var peta_garis = L.map('map').setView(posisi, zoom);

    //1. Menampilkan overlayLayers Peta Semua Wilayah
    var marker_desa = [];
    var marker_dusun = [];
    var marker_rw = [];
    var marker_rt = [];
    var marker_persil = [];

    //OVERLAY WILAYAH DESA
    <?php if (! empty($desa['path'])): ?>
      set_marker_desa(marker_desa, <?=json_encode($desa)?>, "<?=ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']?>", "<?= favico_desa()?>");
    <?php endif; ?>

    //OVERLAY WILAYAH DUSUN
    <?php if (! empty($dusun_gis)): ?>
      set_marker_multi(marker_dusun, '<?=addslashes(json_encode($dusun_gis))?>', '#FFFF00', '<?=ucwords($this->setting->sebutan_dusun)?>', 'dusun');
    <?php endif; ?>

    //OVERLAY WILAYAH RW
    <?php if (! empty($rw_gis)): ?>
      set_marker(marker_rw, '<?=addslashes(json_encode($rw_gis))?>', '#8888dd', 'RW', 'rw');
    <?php endif; ?>

    //OVERLAY WILAYAH RT
    <?php if (! empty($rt_gis)): ?>
      set_marker(marker_rt, '<?=addslashes(json_encode($rt_gis))?>', '#008000', 'RT', 'rt');
    <?php endif; ?>

    //Menampilkan overlayLayers Peta Semua Wilayah
    <?php if (! empty($wil_atas['path'])): ?>
      var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, marker_persil,"<?=ucwords($this->setting->sebutan_desa)?>", "<?=ucwords($this->setting->sebutan_dusun)?>");
    <?php else: ?>
      var overlayLayers = {};
    <?php endif; ?>

    //Menampilkan BaseLayers Peta
    var baseLayers = getBaseLayers(peta_garis, '<?=$this->setting->mapbox_key?>');

    //Menampilkan Peta wilayah yg sudah ada
    <?php if (! empty($garis['path'])): ?>
      var wilayah = <?=$garis['path']?>;
      showCurrentLine(wilayah, peta_garis, jenis, tebal, warna);
    <?php endif; ?>

    //Menambahkan zoom scale ke peta
    L.control.scale().addTo(peta_garis);

    //Menambahkan toolbar ke peta
    peta_garis.pm.addControls(editToolbarLine());

    //Menambahkan Peta wilayah
    addPetaLine(peta_garis, jenis, tebal, warna);

    <?php if ($this->CI->cek_hak_akses('u')): ?>
      //Export/Import Peta dari file GPX
      eximGpxRegion(peta_garis);

      //Import Peta dari file SHP
      eximShp(peta_garis);
    <?php endif; ?>

    //Geolocation IP Route/GPS
  	geoLocation(peta_garis);

    //Menghapus Peta wilayah
    hapusPeta(peta_garis);

    // Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan
		var layerCustom = tampilkan_layer_area_garis_lokasi_plus(peta_garis, '<?= addslashes(json_encode($all_area)) ?>', '<?= addslashes(json_encode($all_garis)) ?>', '<?= addslashes(json_encode($all_lokasi)) ?>', '<?= addslashes(json_encode($all_lokasi_pembangunan)) ?>', '<?= base_url() . LOKASI_SIMBOL_LOKASI ?>', "<?= favico_desa()?>", '<?= base_url() . LOKASI_FOTO_AREA ?>', '<?= base_url() . LOKASI_FOTO_GARIS ?>', '<?= base_url() . LOKASI_FOTO_LOKASI ?>', '<?= base_url() . LOKASI_GALERI ?>', '<?= site_url('pembangunan/')?>');

    L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_garis);
    L.control.groupedLayers('', layerCustom, {groupCheckboxes: true, position: 'topleft', collapsed: true}).addTo(peta_garis);

  }; //EOF window.onload
</script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
