<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View maps untuk Modul Lapak Admin > Kategori
 *
 *
 * donjo-app/views/lapak_admin/pelapak/maps.php
 *
 */

/*
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
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<!-- Menampilkan OpenStreetMap dalam Box modal bootstrap (AdminLTE)  -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Lokasi Pelapak <?= $pelapak->pelapak; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('produdk/pelapak')?>"> Pelapak</a></li>
			<li class="active">Lokasi Pelapak <?= $pelapak->pelapak; ?></a></li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<form id="validasi" action="<?= $form_action; ?>" method="POST" class="form-horizontal">
				<div class="box-body">
					<div id="tampil-map"></div>
				</div>
				<div class='box-footer'>
					<input type="hidden" name="zoom" id="zoom" value="<?= $lokasi['zoom']; ?>"/>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="lat">Latitude</label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm lat" name="lat" id="lat" value="<?= $lokasi['lat']; ?>"/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="lng">Longitude</label>
						<div class="col-sm-9">
							<input type="text" class="form-control input-sm lng" name="lng" id="lng" value="<?= $lokasi['lng']; ?>"/>
						</div>
					</div>

					<a href="<?= site_url('lapak_admin/pelapak'); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
					<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
					<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" id="resetme"><i class="fa fa-times"></i> Reset</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class='fa fa-check'></i> Simpan</button>
				</div>
			</form>
		</div>
	</section>
</div>
<script>
	window.onload = function() {
		var posisi = [<?= $lokasi['lat'] . ',' . $lokasi['lng']; ?>];
		var zoom = <?= $lokasi['zoom']; ?>;

		//Inisialisasi tampilan peta
		var peta_lapak = L.map('tampil-map').setView(posisi, zoom);

		//1. Menampilkan overlayLayers Peta Semua Wilayah
		var marker_desa = [];
		var marker_dusun = [];
		var marker_rw = [];
		var marker_rt = [];

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
			var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "<?=ucwords($this->setting->sebutan_desa)?>", "<?= ucwords($this->setting->sebutan_dusun); ?>");
		<?php else: ?>
			var overlayLayers = {};
		<?php endif; ?>

		//Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_lapak, '<?= $this->setting->mapbox_key; ?>');

		showCurrentPoint(posisi, peta_lapak);

		<?php if ($this->CI->cek_hak_akses('u')): ?>
			//Export/Import Peta dari file GPX
			L.Control.FileLayerLoad.LABEL = '<img class="icon-map" src="<?= base_url()?>assets/images/gpx.png" alt="file icon"/>';
			L.Control.FileLayerLoad.TITLE = 'Impor GPX/KML';
			controlGpxPoint = eximGpxPoint(peta_lapak);
		<?php endif; ?>

		//Menambahkan zoom scale ke peta
		L.control.scale().addTo(peta_lapak);
		L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_lapak);
	}; //EOF window.onload
</script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
