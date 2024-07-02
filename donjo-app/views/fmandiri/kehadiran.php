<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Kehadiran
 *
 * donjo-app/views/fmandiri/kehadiran.php
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
	<div class="box-header with-border bg-red">
		<h4 class="box-title">KEHADIRAN PERANGKAT <?= strtoupper($this->setting->sebutan_desa) ?> </h4>
	</div>
	<div class="box-body box-line">
		<h4><?= tgl_indo(date('Y-m-d')) ?></h4>
	</div>
	<div class="box-body box-line">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-data" id="tabeldata">
				<thead>
					<tr class="judul">
						<th>No</th>
						<th>Nama</th>
						<th>Jabatan</th>
						<th>Status Kehadiran</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($perangkat as $item): ?>
						<tr>
							<td class="padat"></td>
							<td><?= $item->pamong_nama ?></td>
							<td><?= $item->jabatan->nama; ?></td>
							<td class="padat"><?= empty($item->status_kehadiran) ? '-' : ucfirst($item->status_kehadiran); ?></td>
							<td class="padat">
								<?php if ($item->status_kehadiran == 'hadir' && setting('tampilkan_kehadiran') == '1'): ?>
									<?php if ($item->id_penduduk == $this->session->is_login->id_pend && date('Y-m-d', strtotime($item->waktu)) === date('Y-m-d')): ?>
										<a class="btn btn-primary btn-sm btn-proses btn-social"><i class="fa fa-exclamation"></i> Telah dilaporkan</a>
									<?php else: ?>
										<a href="#" data-href="<?= site_url("layanan-mandiri/kehadiran/lapor/{$item->pamong_id}"); ?>" class="btn btn-primary btn-sm btn-social" title="Laporkan perangkat desa" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-exclamation"></i> Laporkan</a>
									<?php endif ?>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php if (setting('tampilkan_kehadiran') == '1') : ?>
<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
			</div>
			<div class='modal-body btn-info'>
				Apakah Anda yakin ingin melaporkan perangkat ini?
			</div>
			<div class='modal-footer'>
				<button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
					<a class='btn-ok'>
						<button type="button" class="btn btn-social btn-danger btn-sm" id="ok-delete"><i class='fa fa-exclamation'></i> Laporkan</button>
					</a>
			</div>
		</div>
	</div>
</div>
<?php endif ?>
<script>
	$(document).ready(function() {
		var kehadiran = '<?= setting('tampilkan_kehadiran') ?>';
		var tabelData = $('#tabeldata').DataTable({
			'rowsGroup': [1],
			'processing': false,
			'pageLength': 10,
			'lengthMenu': [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, "Semua"]
			],
			'columnDefs': [
				{
					'searchable': false,
					'targets': [0, 4]
				},
				{
					'orderable': false,
					'targets': [0, 4]
				}
			],
			'language': {
				'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
			},
		});

		tabelData.on( 'order.dt search.dt', function () {
			tabelData.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i + 1;
			});
		}).draw();

		if (kehadiran == 0) {
			tabelData.column(3).visible(false);
			tabelData.column(4).visible(false);
		}
	});
</script>