<?php

/**
 * File ini:
 *
 * View di Modul Pemetaan
 *
 * /donjo-app/views/pembangunan/lokasi_maps.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>

<script>
	window.onload = function() {
		<?php if (!empty($data->lat) && !empty($data->lng)) : ?>
			var posisi = [<?= $data->lat . "," . $data->lng ?>];
			var zoom = 16;
		<?php else : ?>
			var posisi = [<?= $desa['lat'] . "," . $desa['lng'] ?>];
			var zoom = <?= $desa['zoom'] ?: 16 ?>;
		<?php endif; ?>

		//Inisialisasi tampilan peta
		var peta_lokasi = L.map('mapx').setView(posisi, zoom);

		//1. Menampilkan overlayLayers Peta Semua Wilayah
		var marker_desa = [];
		var marker_dusun = [];
		var marker_rw = [];
		var marker_rt = [];

		//WILAYAH DESA
		<?php if (!empty($desa['path'])) : ?>
			set_marker_desa(marker_desa, <?= json_encode($desa) ?>, "<?= ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa'] ?>", "<?= favico_desa() ?>");
		<?php endif; ?>

		//WILAYAH DUSUN
		<?php if (!empty($dusun_gis)) : ?>
			set_marker(marker_dusun, '<?= addslashes(json_encode($dusun_gis)) ?>', '#FFFF00', '<?= ucwords($this->setting->sebutan_dusun) ?>', 'dusun');
		<?php endif; ?>

		//WILAYAH RW
		<?php if (!empty($rw_gis)) : ?>
			set_marker(marker_rw, '<?= addslashes(json_encode($rw_gis)) ?>', '#8888dd', 'RW', 'rw');
		<?php endif; ?>

		//WILAYAH RT
		<?php if (!empty($rt_gis)) : ?>
			set_marker(marker_rt, '<?= addslashes(json_encode($rt_gis)) ?>', '#008000', 'RT', 'rt');
		<?php endif; ?>

		//2. Menampilkan overlayLayers Peta Semua Wilayah
		<?php if (!empty($wil_atas['path'])) : ?>
			var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "<?= ucwords($this->setting->sebutan_desa) ?>", "<?= ucwords($this->setting->sebutan_dusun) ?>");
		<?php else : ?>
			var overlayLayers = {};
		<?php endif; ?>

		//Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_lokasi, '<?= $this->setting->google_key ?>');

		//Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS
		L.Control.FileLayerLoad.LABEL = '<img class="icon" src="<?= base_url() ?>assets/images/folder.svg" alt="file icon"/>';
		showCurrentPoint(posisi, peta_lokasi);

		//Export/Import Peta dari file GPX
		L.Control.FileLayerLoad.LABEL = '<img class="icon" src="<?= base_url() ?>assets/images/gpx.png" alt="file icon"/>';
		L.Control.FileLayerLoad.TITLE = 'Impor GPX/KML';
		controlGpxPoint = eximGpxPoint(peta_lokasi);

		//Menambahkan zoom scale ke peta
		L.control.scale().addTo(peta_lokasi);

		// Menampilkan OverLayer Area, Garis, Lokasi dan Lokasi Pembangunan
		layerCustom = tampilkan_layer_area_garis_lokasi_plus(peta_lokasi, '<?= addslashes(json_encode($all_area)) ?>', '<?= addslashes(json_encode($all_garis)) ?>', '<?= addslashes(json_encode($all_lokasi)) ?>', '<?= addslashes(json_encode($all_lokasi_pembangunan)) ?>', '<?= base_url() . LOKASI_SIMBOL_LOKASI ?>', '<?= favico_desa()?>', '<?= base_url() . LOKASI_FOTO_AREA ?>', '<?= base_url() . LOKASI_FOTO_GARIS ?>', '<?= base_url() . LOKASI_FOTO_LOKASI ?>', '<?= base_url() . LOKASI_GALERI ?>', '<?= site_url("pembangunan/info_pembangunan/")?>');

		L.control.layers(baseLayers, overlayLayers, {
			position: 'topleft',
			collapsed: true
		}).addTo(peta_lokasi);
		L.control.groupedLayers('', layerCustom, {
			groupCheckboxes: true,
			position: 'topleft',
			collapsed: true
		}).addTo(peta_lokasi);

	}; //EOF window.onload
</script>
<!-- TODO: Pindahkan ke external css -->
<style>
	#mapx {
		width: 100%;
		height: 50vh
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
	table {
	  table-layout: fixed;
	  white-space: normal!important;
	}
	td {
	  word-wrap: break-word;
	}
</style>
<!-- Menampilkan OpenStreetMap dalam Box modal bootstrap (AdminLTE)  -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Lokasi <?= $data->judul ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('pembangunan') ?>"> Daftar Pembangunan</a></li>
			<li class="active">Lokasi <?= $data->judul ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form id="validasi1" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div id="mapx">
										<input type="hidden" name="id" id="id" value="<?= $data->id ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Lat</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lat" id="lat" value="<?= $data->lat ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Lng</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lng" id="lng" value="<?= $data->lng ?>" />
									</div>
								</div>
								<a href="<?= site_url('pembangunan') ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
								<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right' id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	$(document).ready(function() {
		$('#simpan_kantor').click(function() {

			$("#validasi1").validate({
				errorElement: "label",
				errorClass: "error",
				highlight: function(element) {
					$(element).closest(".form-group").addClass("has-error");
				},
				unhighlight: function(element) {
					$(element).closest(".form-group").removeClass("has-error");
				},
				errorPlacement: function(error, element) {
					if (element.parent('.input-group').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
			});

			if (!$('#validasi1').valid()) return;

			window.location.reload(false);

			var id = $('#id').val();
			var lat = $('#lat').val();
			var lng = $('#lng').val();

			$.ajax({
				type: "POST",
				url:`<?= site_url('pembangunan/lokasi_maps') ?>/${id}`,
				dataType: 'json',
				data: {
					lat: lat,
					lng: lng
				},
			});
		});
	});
</script>

<script src="<?= base_url() ?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url() ?>assets/js/togeojson.js"></script>
