<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * View daftar kelompok di modul Kelompok
 *
 * donjo-app/views/kelompok/table.php
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
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Pengelolaan Kelompok
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Pengelolaan Kelompok</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="row">

			<div class="col-md-3">
				<div class="panel-group card card-outline card-info">
					<div class="card-header">
						<h3 class="card-title">Kategori Kelompok</h3>
						<div class="card-tools">
							<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar"><i class="plus-minus fa fa-minus"></i></button>
						</div>
					</div>
					<div class="card-body collapse show navbar-collapse" id="collapsibleNavbar">
						<ul class="navbar-nav nav-pills nav-stacked sidebar-menu text-sm">
							<?php foreach ($list_master AS $data): ?>
								<li <?= jecho($filter, $data['id'], 'class="nav-item active"'); ?>><a class="nav-link" href="<?= site_url("kelompok/to_master/$data[id]"); ?>"><?= $data['kelompok']; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<a class="btn btn-flat bg-purple btn-sm" href="<?= site_url("kelompok_master/clear"); ?>"><i class="fa fa-plus"></i> Kelola Kategori Kelompok</a>
				</div>
			</div>

			<div class="col-md-9">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<a href="<?= site_url('kelompok/form'); ?>" title="Tambah kelompok Baru" class="btn btn-flat bg-olive btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"><i class="fa fa-plus"></i> Tambah Kelompok Baru</a>
						<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("kelompok/delete_all"); ?>')" class="btn btn-flat btn-danger btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<a href="<?= site_url("kelompok/dialog/cetak"); ?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data Kelompok"><i class="fa fa-print "></i> Cetak</a>
						<a href="<?= site_url("kelompok/dialog/unduh"); ?>" class="btn btn-flat bg-navy btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data Kelompok"><i class="fa fa-download"></i> Unduh</a>
						<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"><i class="fa fa-refresh"></i>Bersihkan</a>
					</div>
					<div class="card-body">
						<div class="dataTables_wrapper dt-bootstrap no-footer">
							<form class="form-inline" id="mainform" name="mainform" action="" method="post">
								<div class="container-fluid">
									<div class="row mb-2">
										<div class="col-sm-6">
											<select class="form-control form-control-sm" name="filter" onchange="formAction('mainform', '<?= site_url('kelompok/filter/filter'); ?>')">
												<option value="">Kategori Kelompok</option>
												<?php foreach ($list_master AS $data): ?>
													<option value="<?= $data['id']; ?>" <?php selected($filter, $data['id']); ?> ><?= $data['kelompok']; ?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-6">
											<div class="input-group input-group-sm pull-right">
												<input name="cari" id="cari" class="form-control form-control-sm" placeholder="Cari..." type="text" value="<?=html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("kelompok/filter/cari"); ?>');$('#'+'mainform').submit();}">
												<div class="input-group-btn">
													<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("kelompok/filter/cari"); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
										<thead class="bg-gray disabled color-palette">
											<tr>
												<th><input type="checkbox" id="checkall"/></th>
												<th>No</th>
												<th>Aksi</th>
												<th width="50%"><?= url_order($o, "{$this->controller}/{$func}/$p", 1, 'Nama Kelompok'); ?></th>
												<th><?= url_order($o, "{$this->controller}/{$func}/$p", 3, 'Ketua Kelompok'); ?></th>
												<th><?= url_order($o, "{$this->controller}/{$func}/$p", 5, 'Kategori Kelompok'); ?></th>
												<th>Jumlah Anggota</th>
											</tr>
										</thead>
										<tbody>
											<?php if ($main): ?>
												<?php foreach ($main as $key => $data): ?>
													<tr>
														<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']; ?>" /></td>
														<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
														<td class="aksi">
															<a href="<?= site_url("kelompok/anggota/$data[id]"); ?>" class="btn bg-purple btn-flat btn-xs" title="Rincian Kelompok"><i class="fa fa-list-ol"></i></a>
															<a href="<?= site_url("kelompok/form/$p/$o/$data[id]"); ?>" class="btn bg-orange btn-flat btn-xs" title="Ubah Data Kelompok"><i class='fa fa-edit'></i></a>
															<a href="#" data-href="<?= site_url("kelompok/delete/$data[id]"); ?>" class="btn bg-maroon btn-flat btn-xs" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
														</td>
														<td nowrap><?= $data['nama']; ?></td>
														<td nowrap><?= $data['ketua']; ?></td>
														<td><?= $data['master']; ?></td>
														<td class="padat"><?= $data['jml_anggota']; ?></td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td class="text-center" colspan="7">Data Tidak Tersedia</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</form>
							<?php $this->load->view('global/paging'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<script>
	$(function() {
		var keyword = <?= $keyword?> ;
		$("#cari").autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});
	});

	$(function() {
	  function toggleIcon(e) {
	      $(e.target)
	          .prev('.card-header')
	          .find(".plus-minus")
	          .toggleClass('fa-plus fa-minus');
	  }
	  $('.panel-group').on('hidden.bs.collapse', toggleIcon);
	  $('.panel-group').on('shown.bs.collapse', toggleIcon);
	});
</script>
