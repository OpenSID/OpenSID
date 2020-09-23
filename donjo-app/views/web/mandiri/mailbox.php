<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Layanan Mandiri Web > Mail Box
 *
 * donjo-app/views/web/mandiri/mailbox.php
 *
 */
/*
 *  File ini bagian dari:
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

<section class="content no-padding">
	<div class="row">
		<div class="col-sm-12">
			<div class="box-header">
				<a href="<?= site_url('mailbox_web/form') ?>" class="btn text-white btn-flat btn-social btn-success btn-md inline-block" title="Tulis Pesan"><i class="fa fa-plus"></i> Tulis Pesan</a>
			</div>
			<div class="box-body">
				<ul class="nav nav-tabs">
					<?php foreach($submenu as $id => $nama_menu) : ?>
						<li class="<?php ($_SESSION['mailbox'] == $id) and print('active') ?>">
							<a href="<?= site_url("mandiri_web/mandiri/1/3/$id") ?>"><?= $nama_menu ?></a>
						</li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered dataTable">
						<thead class="bg-gray disabled color-palette">
							<tr>
								<th>No</th>
								<th>Aksi</th>
								<th>Subjek Pesan</th>
								<th>Status Pesan</th>
								<th>Dikirimkan Pada</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($main_list as $data) : ?>
								<tr class="<?php ($data['status']!=1) and print('unread')?>">
									<td><?=$data['no']?></td>
									<td nowrap>
										<a href="<?=site_url("mailbox_web/baca_pesan/{$kat}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm" title="Baca pesan"><i class="fa fa-list">&nbsp;</i></a>
										<?php if($kat != 2) : ?>
											<?php if ($data['status'] == 1): ?>
												<a href="<?=site_url('mailbox_web/pesan_unread/'.$data['id'])?>" class="btn bg-navy btn-flat btn-sm" title="Tandai sebagai belum dibaca"><i class="fa fa-envelope-o"></i></a>
												<?php else : ?>
													<a href="<?=site_url('mailbox_web/pesan_read/'.$data['id'])?>" class="btn bg-navy btn-flat btn-sm" title="Tandai sebagai sudah dibaca"><i class="fa fa-envelope-open-o"></i></a>
											<?php endif; ?>
										<?php endif ?>
									</td>
									<td width="40%"><?=$data['subjek']?></td>
									<td><?=$data['status'] == 1 ? 'Sudah Dibaca' : 'Belum Dibaca' ?></td>
									<td nowrap><?=tgl_indo2($data['tgl_upload'])?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
