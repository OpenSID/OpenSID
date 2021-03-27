<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View modul Layanan Mandiri > Surat
 *
 * donjo-app/views/layanan_mandiri/surat.php
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

<div class="box box-solid">
	<div class="box-header with-border bg-green">
		<h4 class="box-title">Surat</h4>
	</div>
	<div class="box-body box-line">
		<a href="<?= site_url("layanan-mandiri/surat/buat"); ?>" class="btn btn-social btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-pencil-square-o"></i>Buat Surat</a>
		<a href="<?= site_url("layanan-mandiri/arsip-surat"); ?>" class="btn btn-social btn-primary visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-file-zip-o"></i>Arsip Surat</a>
		<a href="<?= site_url("layanan-mandiri/permohonan-surat"); ?>" class="btn btn-social bg-purple visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-file-word-o"></i>Permohonan Surat</a>
	</div>
	<div class="box-body box-line">
		<h4><b>DAFTAR <?= strtoupper($judul); ?></b></h4>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<?php if($kat == 1): ?>
				<table class="table table-bordered table-hover table-data datatable-polos">
					<thead>
						<tr>
							<th>No</th>
							<th>Aksi</th>
							<th>Jenis Surat</th>
							<th>Tanggal Kirim</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($main):
							foreach ($main as $key => $data): ?>
								<tr class="<?= jecho($data['status_id'], 0, 'bg-orange'); ?>">
									<td class="padat"><?= ($key + 1); ?></td>
									<td class="aksi">
										<?php if ($data['status_id'] == 0): ?>
											<a href="<?= site_url("layanan-mandiri/surat/buat/$data[id]"); ?>" class="btn btn-social bg-navy btn-sm" title="Lengkapi Surat" style="width: 170px"><i class="fa fa-info-circle"></i>Lengkapi Surat</a>
										<?php elseif ($data['status_id'] == 1): ?>
											<a class="btn btn-social btn-info btn-sm btn-proses" title="Surat <?= $data['status']; ?>" style="width: 170px"><i class="fa fa-spinner"></i><?= $data['status']; ?></a>
										<?php elseif ($data['status_id'] == 2): ?>
											<a class="btn btn-social bg-purple btn-sm btn-proses" title="Surat <?= $data['status']; ?>" style="width: 170px"><i class="fa fa-edit"></i><?= $data['status']; ?></a>
										<?php elseif ($data['status_id'] == 3): ?>
											<a class="btn btn-social bg-orange btn-sm btn-proses" title="Surat <?= $data['status']; ?>" style="width: 170px"><i class="fa fa-thumbs-o-up"></i><?= $data['status']; ?></a>
										<?php elseif ($data['status_id'] == 4): ?>
											<a class="btn btn-social btn-success btn-sm btn-proses" title="Surat <?= $data['status']; ?>" style="width: 170px"><i class="fa fa-check"></i><?= $data['status']; ?></a>
										<?php else: ?>
											<a class="btn btn-social btn-danger btn-sm btn-proses" title="Surat <?= $data['status']; ?>" style="width: 170px"><i class="fa fa-times"></i><?= $data['status']; ?></a>
										<?php endif; ?>
										<?php if (in_array($data['status_id'], ['0', '1'])): ?>
											<a href="<?= site_url("layanan_mandiri/surat/proses/$data[id]"); ?>" title="Batalkan Surat" class="btn bg-maroon btn-sm"><i class="fa fa-times"></i></a>
										<?php endif; ?>
									</td>
									<td><?=$data['jenis_surat']; ?></td>
									<td class="padat"><?= tgl_indo2($data['created_at']); ?></td>
								</tr>
							<?php endforeach;
						else: ?>
							<tr>
								<td class="text-center" colspan="6">Data tidak tersedia</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			<?php else: ?>
				<table class="table table-bordered table-hover table-data datatable-polos">
					<thead>
						<tr>
							<th>No</th>
							<th>Nomor Surat</th>
							<th>Jenis Surat</th>
							<th>Nama Staf</th>
							<th>Tanggal</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($main):
							foreach ($main as $key => $data): ?>
								<tr>
									<td class="padat"><?= ($key + 1); ?></td>
									<td><?= $data['no_surat']; ?></td>
									<td class="padat"><?= $data['format']; ?></td>
									<td><?= $data['pamong']; ?></td>
									<td class="padat"><?= tgl_indo2($data['tanggal']); ?></td>
								</tr>
							<?php endforeach;
						else: ?>
							<tr>
								<td class="text-center" colspan="5">Data tidak tersedia</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>
