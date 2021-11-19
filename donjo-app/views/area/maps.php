<?php
/**
 * File ini:
 *
 * View di modul Pemetaan
 *
 * /donjo-app/views/area/maps.php
 */
?>

<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Peta <?= $area['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('area')?>"> Pengaturan Area </a></li>
			<li class="active">Peta <?= $area['nama']?></li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<form action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="box-body">
					<div id="tampil-map">
						<input type="hidden" id="path" name="path" value="<?= $area['path']?>">
						<input type="hidden" name="id" id="id"  value="<?= $area['id']?>"/>
					</div>
				</div>
				<div class='box-footer'>
					<a href="<?= site_url('area')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<a href="#" data-href="<?= site_url("area/kosongkan/{$area['id']}")?>" class="btn btn-social btn-flat bg-maroon btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kosongkan Wilayah" data-toggle="modal" data-target="#confirm-status" data-body="Apakah yakin akan mengosongkan peta wilayah ini?"><i class="fa fa fa-trash-o"></i>Kosongkan</a>
					<?php endif; ?>
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
<?php $this->load->view('global/konfirmasi'); ?>
<script>
	window.onload = function() {
		<?php if (! empty($desa['lat']) && ! empty($desa['lng'])): ?>
			var posisi = [<?=$desa['lat'] . ', ' . $desa['lng']?>];
			var zoom = <?=$desa['zoom'] ?: 18?>;
		<?php else: ?>
			var posisi = [-1.0546279422758742, 116.71875000000001];
			var zoom = 4;
		<?php endif; ?>

		//Inisialisasi tampilan peta
		var peta_area = L.map('tampil-map').setView(posisi, zoom);

		//1. Menampilkan overlayLayers Peta Semua Wilayah
		var marker_desa = [];
		var marker_dusun = [];
		var marker_rw = [];
		var marker_rt = [];
		var marker_persil = []

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
			var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt,"<?=ucwords($this->setting->sebutan_desa)?>", "<?=ucwords($this->setting->sebutan_dusun)?>");
		<?php else: ?>
			var overlayLayers = {};
		<?php endif; ?>

		//Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_area, '<?=$this->setting->mapbox_key?>');

		//Menampilkan Peta wilayah yg sudah ada
		<?php if (! empty($area['path'])): ?>
			var wilayah = <?=$area['path']?>;
			showCurrentArea(wilayah, peta_area);
		<?php endif; ?>

		//Menambahkan zoom scale ke peta
		L.control.scale().addTo(peta_area);

		//Menambahkan toolbar ke peta
		peta_area.pm.addControls(editToolbarPoly());

		//Menambahkan Peta wilayah
		addPetaPoly(peta_area);

		<?php if ($this->CI->cek_hak_akses('u')): ?>
			//Export/Import Peta dari file GPX
			eximGpxRegion(peta_area);

			//Import Peta dari file SHP
			eximShp(peta_area);
		<?php endif; ?>

		//Geolocation IP Route/GPS
		geoLocation(peta_area);

		//Menghapus Peta wilayah
		hapusPeta(peta_area);

		// deklrasi variabel agar mudah di baca
		var all_area = '<?= addslashes(json_encode($all_area)) ?>';
		var all_garis = '<?= addslashes(json_encode($all_garis)) ?>';
		var all_lokasi = '<?= addslashes(json_encode($all_lokasi)) ?>';
		var all_lokasi_pembangunan = '<?= addslashes(json_encode($all_lokasi_pembangunan)) ?>';
		var all_persil = '<?= addslashes(json_encode($persil))?>';
		var LOKASI_SIMBOL_LOKASI = '<?= base_url() . LOKASI_SIMBOL_LOKASI ?>';
		var favico_desa = '<?= favico_desa() ?>';
		var LOKASI_FOTO_AREA = '<?= base_url() . LOKASI_FOTO_AREA ?>';
		var LOKASI_FOTO_GARIS = '<?= base_url() . LOKASI_FOTO_GARIS ?>';
		var LOKASI_FOTO_LOKASI = '<?= base_url() . LOKASI_FOTO_LOKASI ?>';
		var LOKASI_GALERI = '<?= base_url() . LOKASI_GALERI ?>';
		var info_pembangunan = '<?= site_url('pembangunan/')?>';

		// Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan
		var layerCustom = tampilkan_layer_area_garis_lokasi_plus(peta_area, all_area, all_garis, all_lokasi, all_lokasi_pembangunan, LOKASI_SIMBOL_LOKASI, favico_desa, LOKASI_FOTO_AREA, LOKASI_FOTO_GARIS, LOKASI_FOTO_LOKASI, LOKASI_GALERI, info_pembangunan, all_persil);

		L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_area);
		L.control.groupedLayers('', layerCustom, {groupCheckboxes: true, position: 'topleft', collapsed: true}).addTo(peta_area);

	}; //EOF window.onload
</script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
