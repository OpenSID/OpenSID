<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View daftar terdata untuk modul suplemen
 *
 * donjo-app/views/suplemen/suplemen_anggota.php,
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
		<h1>Daftar Terdata Suplemen</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url($this->controller); ?>"> Data Suplemen</a></li>
			<li class="active">Daftar Terdata Suplemen</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<p class="hidden" id="data-id"><?= $suplemen['id']; ?></p>
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					<a href="<?= site_url("{$this->controller}/form_terdata/{$suplemen['id']}"); ?>" title="Tambah Data Warga" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Warga Terdata</a>
				<?php endif; ?>
				<?php if ($this->CI->cek_hak_akses('h')): ?>
					<a href="#confirm-delete" title="Hapus Data Terpilih" onclick="deleteAllBox('mainform', '<?= site_url("{$this->controller}/hapus_terdata_all/{$suplemen['id']}"); ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
				<?php endif; ?>
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					<a href="<?= site_url("{$this->controller}/impor"); ?>" class="btn btn-social btn-flat bg-navy btn-sm btn-import visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#impor" data-title="Impor Data Suplemen <?= $sasaran[$suplemen['sasaran']]; ?> "><i class="fa fa-upload "></i> Impor Data</a>
				<?php endif; ?>
				<a href="<?= site_url("{$this->controller}/ekspor/{$suplemen['id']}"); ?>" class="btn btn-social btn-flat bg-teal btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-file-excel-o "></i> Ekspor Data</a>
				<a href="<?= site_url("{$this->controller}/dialog_daftar/{$suplemen['id']}/cetak"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data Suplemen <?= $sasaran[$suplemen['sasaran']]; ?> "><i class="fa fa-print "></i> Cetak</a>
				<a href="<?= site_url("{$this->controller}/dialog_daftar/{$suplemen['id']}/unduh"); ?>" class="btn btn-social btn-flat bg-orange btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data Suplemen <?= $sasaran[$suplemen['sasaran']]; ?> "><i class="fa fa-download "></i> Unduh</a>
				<a href="<?= site_url("{$this->controller}/clear/1"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bersihkan"><i class="fa fa-refresh"></i>Bersihkan</a>
				<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data Suplemen">
					<i class="fa fa-arrow-circle-left "></i>Kembali ke Data Suplemen
				</a>
			</div>
			<?php $this->load->view('suplemen/rincian'); ?>
			<div class="box-body">
				<h5><b>Daftar Terdata</b></h5>
				<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<form id="mainform" name="mainform" method="post">
						<div class="row">
							<div class="col-sm-9">
								<select class="form-control input-sm" name="sex" onchange="formAction('mainform', '<?= site_url('suplemen/filter/sex'); ?>')">
									<option value="">Pilih Jenis Kelamin</option>
									<?php foreach ($list_jenis_kelamin as $data): ?>
										<option value="<?= $data['id']; ?>" <?= selected($sex, $data['id']); ?>><?= set_ucwords($data['nama']); ?></option>
									<?php endforeach; ?>
								</select>
								<?php $this->load->view('global/filter_wilayah', ['form' => 'mainform']); ?>
							</div>
							<div class="col-sm-3">
								<div class="input-group input-group-sm pull-right">
									<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" value="<?= $cari ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("{$this->controller}/filter/cari"); ?>');$('#'+'mainform').submit();}">
									<div class="input-group-btn">
										<button type="submit" class="btn btn-default" value="<?= $cari ?>" onclick="$('#'+'mainform').attr('action', '<?=site_url("{$this->controller}/filter/cari"); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
										<th><?= $judul['judul_terdata_info']; ?></th>
										<th><?= $judul['judul_terdata_plus']; ?> </th>
										<th><?= $judul['judul_terdata_nama']; ?></th>
										<th>Tempat Lahir</th>
										<th>Tanggal Lahir</th>
										<th>Jenis-kelamin</th>
										<th>Alamat</th>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php if ($terdata): ?>
										<?php foreach ($terdata as $key => $item): ?>
											<tr>
												<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $item['id']; ?>" /></td>
												<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
												<td class="aksi">
													<?php if ($this->CI->cek_hak_akses('u')): ?>
														<a href="<?= site_url("{$this->controller}/edit_terdata_form/{$item['id']}"); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Terdata" title="Ubah Terdata" class="btn btn-warning btn-flat btn-sm"><i class="fa fa-edit"></i></a>
													<?php endif; ?>
													<?php if ($this->CI->cek_hak_akses('h')): ?>
														<a href="#" data-href="<?= site_url("{$this->controller}/hapus_terdata/{$suplemen['id']}/{$item['id']}"); ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													<?php endif; ?>
												</td>
												<td nowrap><?= $item['terdata_info']; ?></td>
												<td nowrap><a href="<?= site_url("{$this->controller}/terdata/{$suplemen['sasaran']}/{$item['id_terdata']}"); ?>" title="Daftar suplemen untuk terdata"><?= $item['terdata_plus']; ?></a></td>
												<td nowrap><a href="<?= site_url("{$this->controller}/data_terdata/{$item['id']}"); ?>" title="Data terdata"><?= $item['terdata_nama']; ?></a></td>
												<td><?= $item['tempat_lahir']; ?></td>
												<td nowrap><?= $item['tanggal_lahir']; ?></td>
												<td nowrap><?= $item['sex']; ?></td>
												<td nowrap><?= $item['info']; ?></td>
												<td width="25%"><?= $item['keterangan']; ?></td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td class="text-center" colspan="11">Data Tidak Tersedia</td>
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
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<?php $this->load->view('suplemen/impor'); ?>
<script type="text/javascript">
	$(document).ready(function() {

		var keyword = <?= $keyword ?> ;
		$( "#cari" ).autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});

		$(".btn-import").click(function() {
			$("#id_suplemen").val($("#data-id").html());
		});
	});
</script>