<?php
/*
 * File ini:
 *
 * View pemetaan wilayah di Modul Identitas Desa dan Wilayah Administratif OpenSID
 *
 * /donjo-app/views/sid/wilayah/maps_wilayah.php
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

<style>
	#map
	{
		width:100%;
		height:63vh
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
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div id="map">
										<input type="hidden" id="path" name="path" value="<?= $wil_ini['path']?>">
										<input type="hidden" name="id" id="id"  value="<?= $wil_ini['id']?>"/>
										<input type="hidden" name="zoom" id="zoom"  value="<?= $wil_ini['zoom']?>"/>
										<?php include("donjo-app/views/gis/cetak_peta.php"); ?>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<a href="<?= $tautan['link'] ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
								<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
								<label class="control-label col-sm-1">Warna</label>
								<div class="col-sm-2">
									<div class="input-group my-colorpicker2">
										<input type="text" id="warna" name="warna" class="form-control input-sm required" placeholder="#FFFFFF" value="<?= $wil_ini['warna']?>">
										<div class="input-group-addon input-sm">
											<i></i>
										</div>
									</div>
								</div>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	var infoWindow;
	window.onload = function()
	{
		//Jika posisi kantor dusun belum ada, maka posisi peta akan menampilkan peta desa
		<?php if (!empty($wil_ini['lat']) && !empty($wil_ini['lng'])): ?>
			var posisi = [<?=$wil_ini['lat'].",".$wil_ini['lng']?>];
			var zoom = <?=$wil_ini['zoom']?>;
		<?php elseif (!empty($wil_atas['lat']) && !empty($wil_atas['lng'])): ?>
			var posisi = [<?=$wil_atas['lat'].",".$wil_atas['lng']?>];
			var zoom = <?=$wil_atas['zoom']?>;
		<?php else: ?>
			var posisi = [-1.0546279422758742,116.71875000000001];
			var zoom   = 10;
		<?php endif; ?>

		//Inisialisasi tampilan peta
		var peta_wilayah = L.map('map').setView(posisi, zoom);

		//1. Menampilkan overlayLayers Peta Semua Wilayah
		var marker_desa = [];
		var marker_dusun = [];
		var marker_rw = [];
		var marker_rt = [];

		//OVERLAY WILAYAH DESA
		<?php if (!empty($desa['path'])): ?>
			set_marker_desa(marker_desa, <?=json_encode($desa)?>, "<?=ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa']?>", "<?= favico_desa()?>");
		<?php endif; ?>

		//OVERLAY WILAYAH DUSUN
		<?php if (!empty($dusun_gis)): ?>
			set_marker(marker_dusun, '<?=addslashes(json_encode($dusun_gis))?>', '<?=ucwords($this->setting->sebutan_dusun)?>', 'dusun', "<?= favico_desa()?>");
		<?php endif; ?>

		//OVERLAY WILAYAH RW
		<?php if (!empty($rw_gis)): ?>
			set_marker(marker_rw, '<?=addslashes(json_encode($rw_gis))?>', 'RW', 'rw', "<?= favico_desa()?>");
		<?php endif; ?>

		//OVERLAY WILAYAH RT
		<?php if (!empty($rt_gis)): ?>
			set_marker(marker_rt, '<?=addslashes(json_encode($rt_gis))?>', 'RT', 'rt', "<?= favico_desa()?>");
		<?php endif; ?>

		//Menampilkan overlayLayers Peta Semua Wilayah
		<?php if (!empty($wil_atas['path'])): ?>
	    var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "<?=ucwords($this->setting->sebutan_desa)?>", "<?=ucwords($this->setting->sebutan_dusun)?>");
		<?php else: ?>
			var overlayLayers = {};
		<?php endif; ?>

		//Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_wilayah, '<?=$this->setting->google_key?>');

		//Menampilkan Peta wilayah yg sudah ada
		<?php if (!empty($wil_ini['path'])): ?>
			var wilayah = <?=$wil_ini['path']?>;
			var warna = '<?=$wil_ini['warna']?>';
			showCurrentPolygon(wilayah, peta_wilayah, warna);
		<?php endif; ?>

		//Menambahkan zoom scale ke peta
		L.control.scale().addTo(peta_wilayah);

		//Menambahkan toolbar ke peta
		peta_wilayah.pm.addControls(editToolbarPoly());

		//Menambahkan Peta wilayah
		addPetaPoly(peta_wilayah);

		// update value zoom ketika ganti zoom
		updateZoom(peta_wilayah);

		//Export/Import Peta dari file GPX
		L.Control.FileLayerLoad.LABEL = '<img class="icon" src="<?= base_url()?>assets/images/gpx.png" alt="file icon"/>';
		L.Control.FileLayerLoad.TITLE = 'Impor GPX/KML';
		control = eximGpxPoly(peta_wilayah);

		//Import Peta dari file SHP
		eximShp(peta_wilayah);

		//Geolocation IP Route/GPS
		geoLocation(peta_wilayah);

		//Menghapus Peta wilayah
		hapusPeta(peta_wilayah);

		//Mencetak peta ke PNG
		cetakPeta(peta_wilayah);

		//Menambahkan Legenda Ke Peta
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

		//Menampilkan baseLayers dan overlayLayers
		L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_wilayah);

	}; //EOF window.onload
</script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
