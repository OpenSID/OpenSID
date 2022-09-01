<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Lokasi Kantor <?= $nama_wilayah ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<?php foreach ($breadcrumb as $tautan): ?>
        <li><a href="<?= $tautan['link'] ?>"> <?= $tautan['judul'] ?></a></li>
      <?php endforeach; ?>
			<li class="active">Lokasi Kantor <?= $wilayah ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<form id="validasi" action="<?= $form_action?>" method="POST" class="form-horizontal">
				<div class="box-body">
					<div id="tampil-map">
						<input type="hidden" name="zoom" id="zoom"  value="<?= $wil_ini['zoom']?>"/>
						<input type="hidden" name="map_tipe" id="map_tipe"  value="<?= $wil_ini['map_tipe']?>"/>
						<input type="hidden" name="id" id="id"  value="<?= $wil_ini['id']?>"/>
						<?php include 'donjo-app/views/gis/cetak_peta.php'; ?>
					</div>
				</div>
				<div class="box-footer">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="lat">Latitude</label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm lat" name="lat" id="lat" value="<?= $wil_ini['lat'] ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="lat">Longitude</label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm lng" name="lng" id="lng" value="<?= $wil_ini['lng'] ?>"/>
						</div>
					</div>
					<a href="<?= $tautan['link'] ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
					<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right' id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
					<?php endif; ?>
				</div>
			</form>
		</div>
	</section>
</div>
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
			// TODO : gunakan $this->header['desa'] yg sudah di set global, jgn lakukan pemanggilan berulang kali tiap modul
			// Jika posisi saat ini belum ada, maka posisi peta akan menampilkan peta desa
			var posisi = [<?=$wil_atas['lat'] . ', ' . $wil_atas['lng']?>];
			var zoom = <?=$wil_atas['zoom']?>;
		<?php else: ?>
			// Kondisi ini hanya untuk lokasi/wilayah desa yg belum ada
			var posisi = [-1.0546279422758742, 116.71875000000001];
			var zoom = 10;
		<?php endif; ?>

		// Inisialisasi tampilan peta
		var peta_kantor = L.map('tampil-map').setView(posisi, zoom);

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
		    var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "<?=ucwords($this->setting->sebutan_desa)?>", "<?=ucwords($this->setting->sebutan_dusun)?>");
		<?php else: ?>
		    var overlayLayers = {};
		<?php endif; ?>

		// Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_kantor, '<?=$this->setting->mapbox_key?>');

		// Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS
		showCurrentPoint(posisi, peta_kantor);

		<?php if ($this->CI->cek_hak_akses('u')): ?>
			//Export/Import Peta dari file GPX
			eximGpxPoint(peta_kantor);
		<?php endif; ?>

		// Menambahkan zoom scale ke peta
		L.control.scale().addTo(peta_kantor);

		// Mencetak peta ke PNG
		cetakPeta(peta_kantor);

		// Menambahkan Legenda Ke Peta
		var legenda_desa = L.control({position: 'bottomright'});
		var legenda_dusun = L.control({position: 'bottomright'});
		var legenda_rw = L.control({position: 'bottomright'});
		var legenda_rt = L.control({position: 'bottomright'});

		peta_kantor.on('overlayadd', function (eventLayer) {
			if (eventLayer.name === 'Peta Wilayah Desa') {
				setlegendPetaDesa(legenda_desa, peta_kantor, <?=json_encode($desa)?>, '<?=ucwords($this->setting->sebutan_desa)?>', '<?=$desa['nama_desa']?>');
			}

			if (eventLayer.name === 'Peta Wilayah Dusun') {
				setlegendPeta(legenda_dusun, peta_kantor, '<?=addslashes(json_encode($dusun_gis))?>', '<?=ucwords($this->setting->sebutan_dusun)?>', 'dusun', '', '');
			}

			if (eventLayer.name === 'Peta Wilayah RW') {
				setlegendPeta(legenda_rw, peta_kantor, '<?=addslashes(json_encode($rw_gis))?>', 'RW', 'rw', '<?=ucwords($this->setting->sebutan_dusun)?>');
			}

			if (eventLayer.name === 'Peta Wilayah RT') {
				setlegendPeta(legenda_rt, peta_kantor, '<?=addslashes(json_encode($rt_gis))?>', 'RT', 'rt', 'RW');
			}
		});

		peta_kantor.on('overlayremove', function (eventLayer) {
			if (eventLayer.name === 'Peta Wilayah Desa') {
				peta_kantor.removeControl(legenda_desa);
			}

			if (eventLayer.name === 'Peta Wilayah Dusun') {
				peta_kantor.removeControl(legenda_dusun);
			}

			if (eventLayer.name === 'Peta Wilayah RW') {
				peta_kantor.removeControl(legenda_rw);
			}

			if (eventLayer.name === 'Peta Wilayah RT') {
				peta_kantor.removeControl(legenda_rt);
			}
		});

		L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_kantor);

	}; // EOF window.onload
</script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
