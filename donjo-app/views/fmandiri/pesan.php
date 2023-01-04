<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Pesan > Pesan Masuk / Keluar
 *
 * donjo-app/views/fmandiri/pesan.php
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

<div class="box box-solid">
	<div class="box-header with-border bg-yellow">
		<h4 class="box-title">Pesan</h4>
	</div>
	<div class="box-body box-line">
		<a href="<?= site_url('layanan-mandiri/pesan/tulis'); ?>" class="btn btn-social btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-pencil-square-o"></i>Tulis Pesan</a>
		<a href="<?= site_url('layanan-mandiri/pesan-masuk'); ?>" class="btn btn-social btn-primary visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-inbox"></i>Pesan Masuk</a>
		<a href="<?= site_url('layanan-mandiri/pesan-keluar'); ?>" class="btn btn-social bg-purple visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-envelope-o"></i>Pesan Keluar</a>
	</div>
	<div class="box-body box-line">
		<h4><b>PESAN <?= strtoupper($judul); ?></b></h4>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-data datatable-polos">
				<thead>
					<tr>
						<th>No</th>
						<th>Aksi</th>
						<th width="75%">Subjek Pesan</th>
						<th>Status Pesan</th>
						<th width="20%">Dikirimkan Pada</th>
					</tr>
				</thead>
				<tbody>
				<?php if ($pesan):
				    foreach ($pesan as $key => $data): ?>
							<tr <?= jecho($data['status'], '2', 'class="select_row"'); ?>>
								<td class="padat"><?= ($key + 1); ?></td>
								<td class="padat">
									<a href="<?= site_url("layanan-mandiri/pesan/baca/{$kat}/{$data['id']}"); ?>" class="btn bg-green btn-sm" title="Baca pesan"><i class="fa fa-eye<?= jecho($data['status'], '2', '-slash'); ?>">&nbsp;</i></a>
								</td>
								<td><?= $data['subjek']; ?></td>
								<td class="padat"><?= $data['status'] == 1 ? 'Sudah Dibaca' : 'Belum Dibaca' ?></td>
								<td nowrap><?=tgl_indo2($data['tgl_upload']); ?></td>
							</tr>
						<?php endforeach;
				else: ?>
						<tr>
							<td class="text-center" colspan="5">Data tidak tersedia</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
