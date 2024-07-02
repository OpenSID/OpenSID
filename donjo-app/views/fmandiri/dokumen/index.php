<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Profil
 *
 * donjo-app/views/fmandiri/profil.php
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
	<div class="box-header with-border bg-blue">
		<h4 class="box-title">DOKUMEN</h4>
	</div>

	<div class="box-body box-line">
		<a href="<?= site_url('layanan-mandiri/dokumen/form'); ?>" class="btn btn-social btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i>Tambah Dokumen</a>
	</div>

	<div class="box-body box-line">
		<?php $this->load->view('fmandiri/notifikasi') ?>
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-data" id="loaddata">
				<thead>
					<tr>
						<th class="padat">No</th>
						<th class="aksi">Aksi</th>
						<th width="20%">Jenis Dokumen</th>
						<th>Nama Dokumen</th>
						<th class="padat">Tanggal Upload</th>
					</tr>
				</thead>
				<tbody>
					<?php
                        if ($dokumen) :
                            foreach ($dokumen as $data) :
                                ?>
						<tr>
							<td class="padat"></td>
							<td class="aksi">
								<a href="<?= site_url("layanan-mandiri/dokumen/form/{$data['id']}"); ?>" title="Ubah" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
								<a href="<?= site_url("layanan-mandiri/dokumen/hapus/{$data['id']}"); ?>" title="Hapus" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
								<a href="<?= site_url("layanan-mandiri/dokumen/unduh/{$data['id']}"); ?>" title="Unduh" class="btn bg-navy btn-sm"><i class="fa fa-download"></i></a>
							</td>
							<td><?= $jenis_syarat_surat[$data['id_syarat']]['ref_syarat_nama']?></td>
							<td><?= $data['nama']; ?></td>
							<td class="padat"><?= tgl_indo2($data['tgl_upload']); ?></td>
						</tr>
					<?php
                            endforeach;
                        else :
                            ?>
						<tr>
							<td class="text-center" colspan="5">Data tidak tersedia</td>
						</tr>
					<?php endif; ?>
			</table>
			</tbody>
			</table>
		</div>
	</div>

	<script>
		$(document).ready(function () {
			var TabelData = $('#loaddata').DataTable({
				'pageLength': 10,
				'responsive': true,
				'columnDefs': [
					{ "searchable": true, "orderable": false, "targets": [ 0, 1 ] }
				],
				order: [[3, 'asc']],
				'language': {
					'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
				}
			});

			TabelData.on( 'draw.dt', function () {
				var PageInfo = TabelData.page.info();
				TabelData.column(0, { page: 'current' }).nodes().each(function (cell, i) {
					cell.innerHTML = i + 1 + PageInfo.start;
				});
			});
		});
	</script>