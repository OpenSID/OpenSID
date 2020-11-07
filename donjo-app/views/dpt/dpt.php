<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * File ini:
 *
 * View untuk modul Kependudukan > Dpt
 *
 * donjo-app/views/dpt/dpt.php
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<script>
	$(document).ready(function() {
		$('#tglform').validate();
	});

	$(function() {
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Calon Pemilih</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Calon Pemilih</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<div class="col-sm-8 col-lg-9">
							<div class="row">
								<a href="<?= site_url("dpt/ajax_cetak/$o/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data" ><i class="fa fa-print "></i> Cetak</a>
								<a href="<?= site_url("dpt/ajax_cetak/$o/unduh")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
								<a href="<?= site_url("dpt/ajax_adv_search")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Pencarian Spesifik"><i class='fa fa-search'></i> Pencarian Spesifik</a>
								<a href="<?= site_url("dpt/clear")?>" class="btn btn-social btn-flat btn-default btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bersihkan Pencarian"><i class="fa fa-refresh"></i>Bersihkan</a>
							</div>
						</div>
						<div class="col-sm-4 col-md-3">
							<form id="tglform" name="tglform" action="<?= site_url('dpt/index/1/'.$o)?>" method="post">
								<div class="row">
									<div class="input-group">
										<span class="input-group-addon input-sm">Tanggal Pemilihan</span>
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input class="form-control input-sm datepicker pull-right" onchange="$('#tglform').submit()" name="tanggal_pemilihan" type="text" value="<?= $_SESSION['tanggal_pemilihan']?>">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="box-header">
						<h4 class="text-center"><strong>DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN <?= $_SESSION['tanggal_pemilihan']?></strong></h4>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<input type="hidden" name="rt" value="">
										<div class="row">
											<div class="col-sm-9">
												<select class="form-control input-sm" name="sex" onchange="formAction('mainform', '<?= site_url('dpt/filter/sex')?>')">
													<option value="">Jenis Kelamin</option>
													<?php foreach ($list_jenis_kelamin AS $data): ?>
														<option value="<?= $data['id']?>" <?php selected($sex, $data['id']); ?>><?= set_ucwords($data['nama'])?></option>
													<?php endforeach; ?>
												</select>
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('dpt/dusun')?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= set_ucwords($data['dusun'])?></option>
													<?php endforeach;?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('dpt/rw')?>')" >
														<option value="">Pilih RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= set_ucwords($data['rw'])?></option>
														<?php endforeach;?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('dpt/rt')?>')">
														<option value="">Pilih RT</option>
														<?php foreach ($list_rt AS $data): ?>
															<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
														<?php endforeach;?>
													</select>
												<?php endif; ?>
											</div>
											<div class="col-sm-3">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("dpt/filter/cari")?>');$('#'+'mainform').submit();}">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("dpt/filter/cari")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-striped table-hover nowrap">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<?php if ($o==2): ?>
																	<th><a href="<?= site_url("dpt/index/$p/1")?>">NIK <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==1): ?>
																	<th><a href="<?= site_url("dpt/index/$p/2")?>">NIK <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th><a href="<?= site_url("dpt/index/$p/1")?>">NIK <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<?php if ($o==4): ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/3")?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==3): ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/4")?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/3")?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<?php if ($o==6): ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/5")?>">No. KK <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==5): ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/6")?>">No. KK <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/5")?>">No. KK <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th>Alamat</th>
																<th><?= ucwords($this->setting->sebutan_dusun); ?></th>
																<th>RW</th>
																<th>RT</th>
																<th nowrap>Pendidikan dalam KK</th>
																<?php if ($o==8): ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/7")?>">Umur Pada <?= $_SESSION['tanggal_pemilihan']?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==7): ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/8")?>">Umur Pada <?= $_SESSION['tanggal_pemilihan']?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("dpt/index/$p/7")?>">Umur Pada <?= $_SESSION['tanggal_pemilihan']?> <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th nowrap>Pekerjaan</th>
																<th nowrap>Kawin</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $data): ?>
																<tr>
																	<td><?= $data['no']?></td>
																	<td>
																		<a href="<?= site_url("penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?= $data['id']?>"><?= $data['nik']?></a>
																	</td>
																	<td><?= strtoupper($data['nama'])?></td>
																	<td><a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?= $data['no_kk']?> </a></td>
																	<td><?= strtoupper($data['alamat'])?></td>
																	<td><?= strtoupper($data['dusun'])?></td>
																	<td><?= $data['rw']?></td>
																	<td><?= $data['rt']?></td>
																	<td><?= $data['pendidikan']?></td>
																	<td><?= $data['umur_pada_pemilihan']?></td>
																	<td><?= $data['pekerjaan']?></td>
																	<td><?= $data['kawin']?></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<?php $this->load->view('global/paging');?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
