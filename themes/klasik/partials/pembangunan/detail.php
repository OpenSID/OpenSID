<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * File ini:
 *
 * View halaman detail pembangunan pada website
 *
 *
 * donjo-app/views/fweb/pembangunan/detail.php
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
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<!-- <div class="single_category wow fadeInDown">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Detail Pembangunan</span></h2>
</div> -->

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title text-center">Detail Pembangunan</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-success">
					<div class="panel-heading">Data Pembangunan</div>
					<div class="panel-body">
						<?php if (is_file(LOKASI_GALERI . $pembangunan->foto)): ?>
							<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url() . LOKASI_GALERI . $pembangunan->foto ?>" alt="Foto Pembangunan"/>
						<?php else: ?>
							<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="Foto Pembangunan"/>
						<?php endif; ?>
						<table class="table table-bordered">
							<tr>
								<th width="150px">Nama Kegiatan</th>
								<td width="20px">:</td>
								<td><?= $pembangunan->judul ?></td>
							</tr>
							<tr>
								<th>Alamat</th>
								<td width="20px">:</td>
								<td><?= $pembangunan->alamat ?></td>
							</tr>
							<tr>
								<th>Sumber Dana</th>
								<td width="20px">:</td>
								<td><?= $pembangunan->sumber_dana ?></td>
							</tr>
							<tr>
								<th>Anggaran</th>
								<td width="20px">:</td>
								<td>Rp. <?= number_format($pembangunan->anggaran,0) ?></td>
							</tr>
							<tr>
								<th>Volume</th>
								<td width="20px">:</td>
								<td><?= $pembangunan->volume?></td>
							</tr>
							<tr>
								<th>Pelaksana</th>
								<td width="20px">:</td>
								<td><?= $pembangunan->pelaksana_kegiatan ?></td>
							</tr>
							<tr>
								<th>Tahun</th>
								<td width="20px">:</td>
								<td><?= $pembangunan->tahun_anggaran ?></td>
							</tr>
							<tr>
								<th>Keterangan</th>
								<td width="20px">:</td>
								<td><?= $pembangunan->keterangan ?></td>
							</tr>
						</table>
					</div>
				</div>

				<div class="panel panel-success">
					<div class="panel-heading">Lokasi Pembangunan</div>
					<div class="panel-body">
						<div id="map" style="height: 340px;"></div>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="panel panel-success">
					<div class="panel-heading">Progres Pembangunan</div>
					<div class="panel-body">
						<div class="row">
							<?php foreach ($dokumentasi as $value): ?>
								<div class="col-sm-6 text-center">
									<?php if (is_file(LOKASI_GALERI . $value->foto)): ?>
										<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url() . LOKASI_GALERI . $value->foto ?>" alt="Foto Pembangunan <?= $value->persentase; ?>"/>
									<?php else: ?>
										<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="Foto Pembangunan <?= $value->persentase; ?>"/>
									<?php endif; ?>
									<b>Foto Pembangunan <?= $value->persentase; ?></b>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var posisi = ["<?= $pembangunan->lat; ?>", "<?= $pembangunan->lng; ?>"];
		var zoom = "<?= $desa['zoom'] ?? 10 ?>";

		// Inisialisasi tampilan peta
		pembangunan = L.map('map').setView(posisi, zoom);

		// Menampilkan BaseLayers Peta
		getBaseLayers(pembangunan, "<?= $this->setting->mapbox_key; ?>");

		// Tampilkan Posisi pembangunan
		marker = new L.Marker(posisi, {draggable:false});
		pembangunan.addLayer(marker);
	});
</script>