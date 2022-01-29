<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View di Modul Pemetaan
 *
 * /donjo-app/views/lokasi/maps.php
 */

?>

<!-- Menampilkan OpenStreetMap dalam Box modal bootstrap (AdminLTE)  -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Lokasi <?= $lokasi['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('plan')?>"> Pengaturan Lokasi</a></li>
			<li class="active">Lokasi <?= $lokasi['nama']?></li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<form id="validasi" action="<?= $form_action ?>" method="POST" class="form-horizontal">
				<div class="box-body">
					<div id="tampil-map">
						<input type="hidden" name="id" id="id"  value="<?= $lokasi['id']?>"/>
					</div>
				</div>
				<div class="box-footer">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="lat">Lat</label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm lat" name="lat" id="lat" value="<?= $lokasi['lat']?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="lng">Lng</label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm lng" name="lng" id="lng" value="<?= $lokasi['lng']?>" />
						</div>
					</div>
					<a href="<?= site_url('plan')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
					<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<button type='submit' class='btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block' id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
					<?php endif; ?>
				</div>
			</form>
		</div>
	</section>
</div>

<script>
	window.onload = function() {
		<?php if (! empty($lokasi['lat']) && ! empty($lokasi['lng'])): ?>
			var posisi = [<?=$lokasi['lat'] . ',' . $lokasi['lng']?>];
			var zoom = 16;
		<?php else: ?>
			var posisi = [<?=$desa['lat'] . ',' . $desa['lng']?>];
			var zoom = <?=$desa['zoom'] ?: 16?>;
		<?php endif; ?>

		//Inisialisasi tampilan peta
		var peta_lokasi = L.map('tampil-map').setView(posisi, zoom);

		//1. Menampilkan overlayLayers Peta Semua Wilayah
		var marker_desa = [];
		var marker_dusun = [];
		var marker_rw = [];
		var marker_rt = [];
		var marker_persil = []

		//WILAYAH DESA
		<?php if (! empty($desa['path'])): ?>
			set_marker_desa(marker_desa, <?=json_encode($desa)?>, "<?=ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']?>", "<?= favico_desa()?>");
		<?php endif; ?>

		//WILAYAH DUSUN
		<?php if (! empty($dusun_gis)): ?>
			set_marker_multi(marker_dusun, '<?=addslashes(json_encode($dusun_gis))?>', '#FFFF00', '<?=ucwords($this->setting->sebutan_dusun)?>', 'dusun');
		<?php endif; ?>

		//WILAYAH RW
		<?php if (! empty($rw_gis)): ?>
			set_marker(marker_rw, '<?=addslashes(json_encode($rw_gis))?>', '#8888dd', 'RW', 'rw');
		<?php endif; ?>

		//WILAYAH RT
		<?php if (! empty($rt_gis)): ?>
			set_marker(marker_rt, '<?=addslashes(json_encode($rt_gis))?>', '#008000', 'RT', 'rt');
		<?php endif; ?>

		//2. Menampilkan overlayLayers Peta Semua Wilayah
		<?php if (! empty($wil_atas['path'])): ?>
			var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, marker_persil,"<?=ucwords($this->setting->sebutan_desa)?>", "<?=ucwords($this->setting->sebutan_dusun)?>");
		<?php else: ?>
			var overlayLayers = {};
		<?php endif; ?>

		//Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_lokasi, '<?=$this->setting->mapbox_key?>');

		//Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS
		L.Control.FileLayerLoad.LABEL = '<img class="icon-map" src="<?= base_url()?>assets/images/folder.svg" alt="file icon"/>';
		showCurrentPoint(posisi, peta_lokasi);

		<?php if ($this->CI->cek_hak_akses('u')): ?>
			//Export/Import Peta dari file GPX
			L.Control.FileLayerLoad.LABEL = '<img class="icon-map" src="<?= base_url()?>assets/images/gpx.png" alt="file icon"/>';
			L.Control.FileLayerLoad.TITLE = 'Impor GPX/KML';
			controlGpxPoint = eximGpxPoint(peta_lokasi);
		<?php endif; ?>

		//Menambahkan zoom scale ke peta
		L.control.scale().addTo(peta_lokasi);

		// Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan
		var layerCustom = tampilkan_layer_area_garis_lokasi_plus(peta_lokasi, '<?= addslashes(json_encode($all_area)) ?>', '<?= addslashes(json_encode($all_garis)) ?>', '<?= addslashes(json_encode($all_lokasi)) ?>', '<?= addslashes(json_encode($all_lokasi_pembangunan)) ?>', '<?= base_url() . LOKASI_SIMBOL_LOKASI ?>', "<?= favico_desa()?>", '<?= base_url() . LOKASI_FOTO_AREA ?>', '<?= base_url() . LOKASI_FOTO_GARIS ?>', '<?= base_url() . LOKASI_FOTO_LOKASI ?>', '<?= base_url() . LOKASI_GALERI ?>', '<?= site_url('pembangunan/')?>');

		L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_lokasi);
		L.control.groupedLayers('', layerCustom, {groupCheckboxes: true, position: 'topleft', collapsed: true}).addTo(peta_lokasi);

	}; //EOF window.onload
</script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
