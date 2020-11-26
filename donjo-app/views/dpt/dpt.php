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
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Data Calon Pemilih
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Data Calon Pemilih</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<div class="container-fluid">
							<div class="row mb-2">
								<div class="col-sm-9">
									<a href="<?= site_url("dpt/ajax_cetak/$o/cetak")?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Cetak Data" target="_blank" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data" ><i class="fa fa-print "></i> Cetak</a>
									<a href="<?= site_url("dpt/ajax_cetak/$o/unduh")?>" class="btn btn-flat bg-navy btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
									<a href="<?= site_url("dpt/ajax_adv_search")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik" class="btn btn-flat btn-primary btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Pencarian Spesifik"><i class='fa fa-search'></i> Pencarian Spesifik</a>
									<a href="<?= site_url("dpt/clear")?>" class="btn btn-flat btn-default btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Bersihkan Pencarian"><i class="fa fa-refresh"></i> Bersihkan</a>
								</div>
								<div class="col-sm-4 col-md-3">
									<form class="form-inline" id="tglform" name="tglform" action="<?= site_url('dpt/index/1/'.$o)?>" method="post">
										<div class="row">
											<div class="input-group">
												<span class="input-group-addon input-sm">Tanggal Pemilihan</span>
												<div class="input-group input-group-sm date">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input class="form-control form-control-sm datepicker pull-right" onchange="$('#tglform').submit()" name="tanggal_pemilihan" type="text" value="<?= $_SESSION['tanggal_pemilihan']?>">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="card-header">
						<h4 class="text-center"><strong>DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN <?= $_SESSION['tanggal_pemilihan']?></strong></h4>
					</div>
					<div class="card-body">
						<div class="dataTables_wrapper dt-bootstrap no-footer">
							<form class="form-inline" id="mainform" name="mainform" action="" method="post">
								<input type="hidden" name="rt" value="">
								<div class="container-fluid">
									<div class="row mb-2">
										<div class="col-sm-9">
											<select class="form-control form-control-sm" name="sex" onchange="formAction('mainform', '<?= site_url('dpt/filter/sex')?>')">
												<option value="">Jenis Kelamin</option>
												<?php foreach ($list_jenis_kelamin AS $data): ?>
													<option value="<?= $data['id']?>" <?php selected($sex, $data['id']); ?>><?= set_ucwords($data['nama'])?></option>
												<?php endforeach; ?>
											</select>
											<select class="form-control form-control-sm " name="dusun" onchange="formAction('mainform','<?= site_url('dpt/dusun')?>')">
												<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
												<?php foreach ($list_dusun AS $data): ?>
													<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= set_ucwords($data['dusun'])?></option>
												<?php endforeach;?>
											</select>
											<?php if ($dusun): ?>
												<select class="form-control form-control-sm" name="rw" onchange="formAction('mainform','<?= site_url('dpt/rw')?>')" >
													<option value="">Pilih RW</option>
													<?php foreach ($list_rw AS $data): ?>
														<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= set_ucwords($data['rw'])?></option>
													<?php endforeach;?>
												</select>
											<?php endif; ?>
											<?php if ($rw): ?>
												<select class="form-control form-control-sm" name="rt" onchange="formAction('mainform','<?= site_url('dpt/rt')?>')">
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
								</div>
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
							</form>
							<?php $this->load->view('global/paging');?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
