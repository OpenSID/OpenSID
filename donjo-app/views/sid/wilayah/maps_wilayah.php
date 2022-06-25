<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View pemetaan wilayah di Modul Identitas Desa dan Wilayah Administratif OpenSID
 *
 * /donjo-app/views/sid/wilayah/maps_wilayah.php
 *
 */

?>

<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Peta Wilayah <?= $nama_wilayah ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<?php foreach ($breadcrumb as $tautan): ?>
				<li><a href="<?= $tautan['link'] ?>"> <?= $tautan['judul'] ?></a></li>
			<?php endforeach; ?>
			<li class="active">Peta Wilayah <?= $wilayah ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<form id="validasi" action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="box-body">
					<div id="tampil-map">
						<input type="hidden" id="path" name="path" value="<?= $wil_ini['path']?>">
						<input type="hidden" name="id" id="id"  value="<?= $wil_ini['id']?>"/>
						<input type="hidden" name="zoom" id="zoom"  value="<?= $wil_ini['zoom']?>"/>
						<?php include 'donjo-app/views/gis/cetak_peta.php'; ?>
					</div>
				</div>
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					<div class="box-footer">
						<a href="<?= $tautan['link'] ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
							<a href="#" data-href="<?= "{$tautan['link']}/kosongkan/{$wil_ini['id']}"; ?>" class="btn btn-social btn-flat bg-maroon btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kosongkan Wilayah" data-toggle="modal" data-target="#confirm-status" data-body="Apakah yakin akan mengosongkan peta wilayah ini?"><i class="fa fa fa-trash-o"></i>Kosongkan</a>
						<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
						<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
						<label class="control-label col-sm-1">Warna</label>
						<div class="col-sm-2">
							<div class="input-group my-colorpicker2">
								<input type="text" id="warna" name="warna" class="form-control input-sm warna required" placeholder="#FFFFFF" value="<?= $wil_ini['warna']?>">
								<div class="input-group-addon input-sm">
									<i></i>
								</div>
							</div>
						</div>
							<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
					</div>
				<?php endif; ?>
			</form>
		</div>
	</section>
</div>
<?php $this->load->view('global/konfirmasi'); ?>
<script>
	/**
	 * TODO :
	 * Ikuti aturan penulisan untuk js (https://github.com/OpenSID/OpenSID/wiki/Aturan-Penulisan-Script).
	 * Gunakan $this->header['desa'] yg sudah di set global untuk value $wil_atas, jgn lakukan pemanggilan berulang kali ditiap modul.
	 */

	window.onload = function() {
		<?php if (! empty($wil_ini['lat']) && ! empty($wil_ini['lng'])): ?>
			var posisi = [<?=$wil_ini['lat'] . ', ' . $wil_ini['lng']?>];
			var zoom = <?=$wil_ini['zoom']?>;
		<?php elseif (! empty($wil_atas['lat']) && ! empty($wil_atas['lng'])): ?>
			// Jika posisi saat ini belum ada, maka posisi peta akan menampilkan peta desa
			var posisi = [<?=$wil_atas['lat'] . ', ' . $wil_atas['lng']?>];
			var zoom = <?=$wil_atas['zoom']?>;
		<?php else: ?>
			// Kondisi ini hanya untuk lokasi/wilayah desa yg belum ada
			var posisi = [-1.0546279422758742, 116.71875000000001];
			var zoom = 10;
		<?php endif; ?>

		// Inisialisasi tampilan peta
		var peta_wilayah = L.map('tampil-map').setView(posisi, zoom);

		// 1. Menampilkan overlayLayers Peta Semua Wilayah
		var marker_desa = [];
		var marker_dusun = [];
		var marker_rw = [];
		var marker_rt = [];

		// OVERLAY WILAYAH DESA
		<?php if (! empty($desa['path'])): ?>
			set_marker_desa(marker_desa, <?=json_encode($desa)?>, "<?=ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']?>", "<?= favico_desa()?>");
		<?php endif; ?>

		// OVERLAY WILAYAH DUSUN
		<?php if (! empty($dusun_gis)): ?>
			set_marker_multi(marker_dusun, '<?=addslashes(json_encode($dusun_gis))?>', '<?=ucwords($this->setting->sebutan_dusun)?>', 'dusun', "<?= favico_desa()?>");
		<?php endif; ?>

		// OVERLAY WILAYAH RW
		<?php if (! empty($rw_gis)): ?>
			set_marker(marker_rw, '<?=addslashes(json_encode($rw_gis))?>', 'RW', 'rw', "<?= favico_desa()?>");
		<?php endif; ?>

		// OVERLAY WILAYAH RT
		<?php if (! empty($rt_gis)): ?>
			set_marker(marker_rt, '<?=addslashes(json_encode($rt_gis))?>', 'RT', 'rt', "<?= favico_desa()?>");
		<?php endif; ?>

		// 2. Menampilkan overlayLayers Peta Semua Wilayah
		<?php if (! empty($wil_atas['path'])): ?>
	    var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt,"<?=ucwords($this->setting->sebutan_desa)?>", "<?=ucwords($this->setting->sebutan_dusun)?>");
		<?php else: ?>
			var overlayLayers = {};
		<?php endif; ?>

		// Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_wilayah, '<?=$this->setting->mapbox_key?>');

		// Menampilkan Peta wilayah yg sudah ada
		<?php if (! empty($wil_ini['path'])): ?>
			var wilayah = <?=$wil_ini['path']?>;
			var warna = '<?=$wil_ini['warna']?>';
			<?php if (isset($poly) && $poly == 'multi'): ?>
				// MultiPolygon
				showCurrentMultiPolygon(wilayah, peta_wilayah, warna);
			<?php else: ?>
				// Polygon
				showCurrentPolygon(wilayah, peta_wilayah, warna);
			<?php endif ?>

		<?php endif; ?>

		// Menambahkan zoom scale ke peta
		L.control.scale().addTo(peta_wilayah);

		// Menambahkan toolbar ke peta
		peta_wilayah.pm.addControls(editToolbarPoly());

		<?php if (isset($poly) && $poly == 'multi'): ?>
			// Menambahkan Peta wilayah
			addPetaMultipoly(peta_wilayah);
			var multi = true;
		<?php else: ?>
			// Menambahkan peta poly
			addPetaPoly(peta_wilayah);
			var multi = false;
		<?php endif ?>

		// Update value zoom ketika ganti zoom
		updateZoom(peta_wilayah);

		<?php if ($this->CI->cek_hak_akses('u')): ?>
			// Export/Import Peta dari file GPX
			eximGpxRegion(peta_wilayah, multi);

			// Import Peta dari file SHP
			eximShp(peta_wilayah, multi);
		<?php endif; ?>

		// Geolocation IP Route/GPS
		geoLocation(peta_wilayah);

		// Menghapus Peta wilayah
		hapuslayer(peta_wilayah);

		// Mencetak peta ke PNG
		cetakPeta(peta_wilayah);

		// Menambahkan Legenda Ke Peta
		var legenda_desa = L.control({position: 'bottomright'});
		var legenda_dusun = L.control({position: 'bottomright'});
		var legenda_rw = L.control({position: 'bottomright'});
		var legenda_rt = L.control({position: 'bottomright'});

		peta_wilayah.on('overlayadd', function (eventLayer) {
		  if (eventLayer.name === 'Peta Wilayah Desa') {
		    setlegendPetaDesa(legenda_desa, peta_wilayah, <?=json_encode($desa)?>, '<?=ucwords($this->setting->sebutan_desa)?>', '<?=$desa['nama_desa']?>');
		  }

		  if (eventLayer.name === 'Peta Wilayah Dusun') {
		    setlegendPeta(legenda_dusun, peta_wilayah, '<?=addslashes(json_encode($dusun_gis))?>', '<?=ucwords($this->setting->sebutan_dusun)?>', 'dusun', '', '');
		  }

		  if (eventLayer.name === 'Peta Wilayah RW') {
		    setlegendPeta(legenda_rw, peta_wilayah, '<?=addslashes(json_encode($rw_gis))?>', 'RW', 'rw', '<?=ucwords($this->setting->sebutan_dusun)?>');
		  }

		  if (eventLayer.name === 'Peta Wilayah RT') {
		    setlegendPeta(legenda_rt, peta_wilayah, '<?=addslashes(json_encode($rt_gis))?>', 'RT', 'rt', 'RW');
		  }
		});

		peta_wilayah.on('overlayremove', function (eventLayer) {
		  if (eventLayer.name === 'Peta Wilayah Desa') {
		    peta_wilayah.removeControl(legenda_desa);
		  }

		  if (eventLayer.name === 'Peta Wilayah Dusun') {
		    peta_wilayah.removeControl(legenda_dusun);
		  }

		  if (eventLayer.name === 'Peta Wilayah RW') {
		    peta_wilayah.removeControl(legenda_rw);
		  }

		  if (eventLayer.name === 'Peta Wilayah RT') {
		    peta_wilayah.removeControl(legenda_rt);
		  }
		});

		// Menampilkan baseLayers dan overlayLayers
		L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_wilayah);

	}; // EOF window.onload
</script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
