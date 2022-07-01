<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View Log Bulanan Detail untuk modul Statistik > Log Bulanan
 *
 * donjo-app/views/laporan/tabel_bulanan_detil.php
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Kependudukan Bulanan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('laporan/clear')?>"> Laporan Kependudukan Bulanan</a></li>
			<li class="active">Rincian Kependudukan Bulanan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form id="mainform" name="mainform" method="post">
						<div class="box-header with-border">
							<div class="row">
								<div class="col-sm-12">
									<a href="<?= site_url('laporan')?>" class="btn btn-social btn-flat bg-maroon btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Laporan Bulanan</a>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<h5 class="text-center"><strong>RINCIAN LAPORAN PERKEMBANGAN <?= $title ?></strong></h5>
									</br>
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th>Nama</th>
																<th>NIK</th>
																<th>Tempat Lahir</th>
																<th>Tanggal Lahir</th>
																<th>Nama Ayah</th>
																<th>Nama Ibu</th>
															</tr>
														</thead>
														<tbody>
														<?php foreach ($main as $key => $data): ?>
															<tr>
																<td><?= ($key + $paging->offset + 1); ?></td>
																<td><?= $data['nama'] ?></td>
																<td><?= $data['nik'] ?></td>
																<td><?= $data['tempatlahir'] ?></td>
																<td><?= $data['tanggallahir'] ?></td>
																<td><?= $data['nama_ayah'] ?></td>
																<td><?= $data['nama_ibu'] ?></td>
															</tr>
														<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
