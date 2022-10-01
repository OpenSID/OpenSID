<?php

defined('BASEPATH') || exit('No direct script access allowed');

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
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<script>
	$(function() {
		var keyword = <?= $keyword?> ;
		$("#cari").autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<?= $tipe = ucfirst($this->controller); ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan <?= $tipe; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengelolaan <?= $tipe; ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-3">
					<div id="bantuan" class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Kategori <?= $tipe; ?></h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="box-body no-padding">
							<ul class="nav nav-pills nav-stacked">
								<?php foreach ($list_master as $data): ?>
									<li <?= jecho($filter, $data['id'], 'class="active"'); ?>>
										<a href="<?= site_url("{$this->controller}/to_master/{$data['id']}"); ?>"><?= $data['kelompok']; ?></a>
									</li>
								<?php endforeach; ?>
								<li>
									<a class="btn btn-flat bg-purple btn-sm" href="<?= site_url("{$this->controller}_master/clear"); ?>"><i class="fa fa-plus"></i> Kelola Kategori <?= $tipe; ?></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?= site_url("{$this->controller}/form"); ?>" title="Tambah <?= $tipe; ?> Baru" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah <?= $tipe; ?> Baru</a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("{$this->controller}/delete_all"); ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/dialog/cetak"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data <?= $tipe; ?>"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("{$this->controller}/dialog/unduh"); ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data <?= $tipe; ?>"><i class="fa fa-download"></i> Unduh</a>
							<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
						</div>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="row">
										<div class="col-sm-9">
                                            <select class="form-control input-sm" name="status_dasar" onchange="formAction('mainform', '<?=site_url("{$this->controller}/filter/status_dasar"); ?>')">
                                                <option value="">Pilih Status</option>
                                                <option value="1" <?= selected(1, $status_dasar); ?>>Aktif</option>
                                                <option value="2" <?= selected(2, $status_dasar); ?>>Tidak Aktif</option>
                                            </select>
											<select class="form-control input-sm" name="filter" onchange="formAction('mainform', '<?= site_url("{$this->controller}/filter/filter"); ?>')">
												<option value="">Pilih Kategori <?= $tipe; ?></option>
												<?php foreach ($list_master as $data): ?>
													<option value="<?= $data['id']; ?>" <?php selected($filter, $data['id']); ?> ><?= $data['kelompok']; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-sm-3">
											<div class="input-group input-group-sm pull-right">
												<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/filter/cari"); ?>');$('#'+'mainform').submit();}">
												<div class="input-group-btn">
													<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/filter/cari"); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
													<th>Kode <?= $this->controller ?></th>
													<th width="50%"><?= url_order($o, "{$this->controller}/{$func}/{$p}", 1, "Nama {$this->controller}"); ?></th>
													<th><?= url_order($o, "{$this->controller}/{$func}/{$p}", 3, "Ketua {$this->controller}"); ?></th>
													<th><?= url_order($o, "{$this->controller}/{$func}/{$p}", 5, "Kategori {$this->controller}"); ?></th>
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
																<a href="<?= site_url("{$this->controller}/anggota/{$data['id']}"); ?>" class="btn bg-purple btn-flat btn-sm" title="Rincian <?= $this->controller ?>"><i class="fa fa-list-ol"></i></a>
																<?php if ($this->CI->cek_hak_akses('u')): ?>
																	<a href="<?= site_url("{$this->controller}/form/{$p}/{$o}/{$data['id']}"); ?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data <?= $this->controller ?>"><i class='fa fa-edit'></i></a>
																<?php endif; ?>
																<?php if ($this->CI->cek_hak_akses('h')): ?>
																	<a href="#" data-href="<?= site_url("{$this->controller}/delete/{$data['id']}"); ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																<?php endif; ?>
															</td>
															<td nowrap><?= $data['kode']; ?></td>
															<td nowrap><?= $data['nama']; ?></td>
															<td nowrap><?= $data['ketua']; ?></td>
															<td><?= $data['master']; ?></td>
															<td class="padat"><?= $data['jml_anggota']; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php else: ?>
													<tr>
														<td class="text-center" colspan="8">Data Tidak Tersedia</td>
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
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
