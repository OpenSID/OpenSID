<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk Modul Lapak Admin > Produk
 *
 *
 * donjo-app/views/lapak_admin/produk/index.php
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			LAPORAN APDES
			<small>Daftar Data</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Data</li>
		</ol>
	</section>
	<section class="content">
		<div id="maincontent"></div>
		<div class="box box-info">
			<div class="box-header with-border">
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					<a href="<?= site_url("$this->controller/form/$main->id"); ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Tambah Laporan APBDes"><i class="fa fa-plus"></i> Tambah Data</a>
				<?php endif; ?>
				<?php if ($this->CI->cek_hak_akses('h')): ?>
					<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("$this->controller/delete_all"); ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
				<?php endif; ?>
				<a href="<?= site_url("sinkronisasi_opendk"); ?>" class="btn btn-social btn-flat btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Sinkronisasi OpenDK"><i class="fa fa-random"></i> Sinkronisasi OpenDK</a>
			</div>
			<form id="mainform" name="mainform" method="post">
				<div class="box-header with-border form-inline">
					<div class="row">
						<div class="col-sm-2">
							<select class="form-control input-sm select2" id="filter-tahun" name="filter-tahun">
								<option value="">Semua Tahun</option>
								<?php foreach ($tahun as $thn): ?>
									<option value="<?= $thn->tahun; ?>"><?= $thn->tahun; ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-keuangan">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th><input type="checkbox" id="checkall"/></th>
									<th>No</th>
									<th>Aksi</th>
									<th>Judul</th>
									<th>Tahun</th>
									<th>Semester</th>
									<th>File</th>
									<th>Tanggal Upload</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
<?php $this->load->view('global/konfirmasi'); ?>
<script>
	$(document).ready(function() {
		let tabel_keuangan = $('#tabel-keuangan').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [[4, 'desc']],
			'columnDefs': [
				{ 'orderable': false, 'targets': [0, 1, 2] },
				{ 'className' : 'padat', 'targets': [0, 1, 7] },
				{ 'className' : 'aksi', 'targets': [2] },
			],
			'ajax': {
				'url': "<?= site_url("$this->controller"); ?>",
				'method': 'POST',
				'data': function(d) {
					d.tahun= $('#tahun').val();
				}
			},
			'columns': [
				{
					'data': function(data) {
						return `<input type="checkbox" name="id_cb[]" value="${data.id}"/>`
					}
				},
				{ 'data': null },
				{
					'data': function(data) {
						return `
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url("$this->controller/form/"); ?>${data.id}" title="Edit APBDes" class="btn bg-orange btn-flat btn-sm" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Ubah Laporan APBDes"><i class="fa fa-edit"></i></a>
						<?php endif; ?>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							<a href="#" data-href="<?= site_url("$this->controller/delete/"); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
						<?php endif; ?>
						<a href="<?= site_url("$this->controller/unduh/"); ?>${data.id}" class="btn bg-purple btn-flat btn-sm"  title="Unduh"><i class="fa fa-download"></i></a>
						`
					}
				},
				{ 'data': 'judul' },
				{ 'data': 'tahun' },
				{ 'data': 'semester' },
				{ 'data': 'nama_file' },
                { 'data': 'updated_at' },
			],
			'language': {
				'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
			}
		});

		tabel_keuangan.on('draw.dt', function() {
			let PageInfo = $('#tabel-keuangan').DataTable().page.info();
			tabel_keuangan.column(1, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});

		$('#filter-tahun').on('select2:select', function (e) {
			tabel_keuangan.ajax.reload();
		});
	});
</script>
