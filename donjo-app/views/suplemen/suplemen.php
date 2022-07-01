<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View daftar data suplemen untuk modul Suplemen
 *
 * donjo-app/views/suplemen/suplemen.php,
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
		<h1>Data Suplemen <?= ($set_sasaran == 0) ? '' : "Sasaran {$sasaran[$set_sasaran]}"; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Suplemen</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="box box-info">
				<div class="box-header with-border">
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<a href="<?=site_url("{$this->controller}/form"); ?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Suplemen Baru"><i class="fa fa-plus"></i> Tambah Suplemen Baru</a>
					<?php endif; ?>
					<a href="<?=site_url("{$this->controller}/panduan"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Program Bantuan Baru"><i class="fa fa-question-circle"></i> Panduan</a>
					<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bersihkan"><i class="fa fa-refresh"></i>Bersihkan</a>
				</div>
				<div class="box-body">
					<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<form id="mainform" name="mainform" method="post">
							<select class="form-control input-sm" name="sasaran" onchange="formAction('mainform', '<?= site_url('suplemen'); ?>')">
								<option value="">Pilih Sasaran</option>
								<?php foreach ($list_sasaran as $key => $value): ?>
									<?php if (in_array($key, ['1', '2'])) : ?>
										<option value="<?= $key; ?>" <?= selected($set_sasaran, $key); ?>><?= $value?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</form>
						<div class="table-responsive">
							<table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th>No</th>
										<th>Aksi</th>
										<th>Nama Data</th>
										<th>Jumlah Terdata</th>
										<th>Sasaran</th>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php if ($suplemen): ?>
										<?php	foreach ($suplemen as $key => $item):	?>
											<tr id="row-data">
												<td class="padat"><?= ($paging->offset + $key + 1); ?></td>
												<td class="aksi">
													<a href="<?= site_url("suplemen/clear/{$item['id']}"); ?>" class="btn bg-purple btn-flat btn-sm" title="Rincian Data"><i class="fa fa-list-ol"></i></a>
													<?php if ($this->CI->cek_hak_akses('u')): ?>
														<a href="<?= site_url('suplemen/impor/'); ?>" class="btn bg-navy btn-flat btn-sm btn-import" title="Impor Data" data-target="#impor" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false"><i class="fa fa-upload"></i></a>
														<a href="<?= site_url("suplemen/form/{$item['id']}"); ?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data"><i class='fa fa-edit'></i></a>
													<?php endif; ?>
													<?php if ($this->CI->cek_hak_akses('h')): ?>
														<a
															<?php if ($item['jml'] <= 0): ?>
																href="#" data-href="<?= site_url("suplemen/hapus/{$item['id']}")?>" data-toggle="modal" data-target="#confirm-delete"
															<?php endif; ?>
															class="btn bg-maroon btn-flat btn-sm" title="Hapus" <?= jecho($item['jml'] > 0, true, 'disabled'); ?>><i class="fa fa-trash-o"></i>
														</a>
													<?php endif; ?>
												</td>
												<td class="hidden data-id"><?= $item[id] ?></td>
												<td width="20%"><a href="<?= site_url("suplemen/rincian/{$item['id']}"); ?>"><?= $item['nama'] ?></a></td>
												<td class="padat"><?= $item['jml']?></td>
												<td class="nostretch"><?= $sasaran[$item['sasaran']]?></td>
												<td><?= $item['keterangan']?></td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td class="text-center" colspan="6">Data Tidak Tersedia</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
						<?php $this->load->view('global/paging'); ?>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<?php $this->load->view('suplemen/impor'); ?>
<script type="text/javascript">
	$(".btn-import").click(function() {
		$("#id_suplemen").val($(this).closest('#row-data').find(".data-id").html());
	});
</script>