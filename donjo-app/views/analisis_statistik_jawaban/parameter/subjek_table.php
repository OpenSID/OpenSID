<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Analisis
 *
 * donjo-app/views/analisis_statistik_jawaban/parameter/subjek_table.php
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar Responden - <?= $analisis_master['nama']?> </h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('analisis_master')?>"> Master Analisis</a></li>
			<li><a href="<?= site_url()?>analisis_statistik_jawaban/leave"><?= $analisis_master['nama']?></a></li>
			<li><a href="<?= site_url()?>analisis_statistik_jawaban">Laporan Per Indikator</a></li>
			<li class="active">Daftar Responden</li>
		</ol>
	</section>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left', $data);?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url("analisis_statistik_jawaban/cetak2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank">
								<i class="fa fa-print"></i>Cetak
            	</a>
						  <a href="<?= site_url("analisis_statistik_jawaban/excel2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank">
								<i class="fa fa-download"></i>Unduh
            	</a>
							<a href="<?= site_url()?>analisis_statistik_jawaban" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Laporan Per Indikator</a>
						</div>
						<div class="box-header with-border">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" >
									<tr>
										<td width="150">Indikator Pertanyaan</td>
										<td width="1">:</td>
										<td><?= $analisis_statistik_pertanyaan['pertanyaan']?></td>
									</tr>
									<tr>
										<td>Jawaban</td>
										<td>:</td>
										<td><?= $analisis_statistik_jawaban['jawaban']?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="box-header with-border">
							<h5><strong>Daftar Responden</strong></h5>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url("analisis_statistik_jawaban/dusun2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>')">
														<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
														<?php foreach ($list_dusun AS $data): ?>
															<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper($data['dusun'])?></option>
														<?php endforeach;?>
													</select>
													<?php if ($dusun): ?>
														<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url("analisis_statistik_jawaban/rw2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>')" >
															<option value="">RW</option>
															<?php foreach ($list_rw AS $data): ?>
																<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw']?></option>
															<?php endforeach;?>
														</select>
													<?php endif; ?>
													<?php if ($rw): ?>
														<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url("analisis_statistik_jawaban/rt2/$analisis_statistik_pertanyaan[id]/$analisis_statistik_jawaban[id]")?>')">
															<option value="">RT</option>
															<?php foreach ($list_rt AS $data): ?>
																<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
															<?php endforeach;?>
														</select>
													<?php endif; ?>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered dataTable table-hover nowrap">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th>No</th>
																	<th>NIK</th>
																	<th>Nama</th>
																	<th>Dusun</th>
																	<th>RW</th>
																	<th>RT</th>
																	<th>Umur (Tahun)</th>
																	<th nowrap>Jenis Kelamin</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<td align="center" width="2"><?= $data['no']?></td>
																		<td><a href="<?= site_url("penduduk/detail/1/0/$data[id_pend]");?>" target="_blank"><?= $data['nik']?></a></td>
																		<td nowrap width="30%"><a href="<?= site_url("penduduk/detail/1/0/$data[id_pend]");?>" target="_blank"><?= $data['nama']?></a></td>
																		<td><?= strtoupper($data['dusun'])?></td>
																		<td><?= $data['rw']?></td>
																		<td><?= $data['rt']?></td>
																		<td><?= $data['umur']?></td>
																		<td><?= $data['sex']?></td>
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
		</form>
	</section>
</div>

