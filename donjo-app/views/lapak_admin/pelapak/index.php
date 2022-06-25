<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk Modul Lapak Admin > Pelapak
 *
 *
 * donjo-app/views/lapak_admin/pelapak/index.php
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
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			PELAPAK
			<small>Daftar Data</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Data</li>
		</ol>
	</section>
	<section class="content">
		<?php $this->load->view("{$this->controller}/navigasi", $navigasi); ?>
		<div id="maincontent"></div>
		<div class="box box-info">
			<div class="box-header with-border">
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					<a href="<?= site_url("{$this->controller}/pelapak_form/{$main->id}"); ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Tambah Data"><i class="fa fa fa-plus"></i> Tambah Data</a>
				<?php endif; ?>
				<?php if ($this->CI->cek_hak_akses('h')): ?>
					<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("{$this->controller}/pelapak_delete_all"); ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
				<?php endif; ?>
			</div>
			<form id="mainform" name="mainform" method="post">
				<div class="box-header with-border form-inline">
					<div class="row">
						<div class="col-sm-2">
							<select class="form-control input-sm select2" id="status" name="status">
								<option value="">Semua Status</option>
								<option value="1">Aktif</option>
								<option value="2">Non Aktif</option>
							</select>
						</div>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-pelapak">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th><input type="checkbox" id="checkall"/></th>
									<th>No</th>
									<th>Aksi</th>
									<th>Pelapak</th>
									<th>No. Telelpon</th>
									<th>Jumlah Produk</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<script>
	$(document).ready(function() {
		let tabel_produk = $('#tabel-pelapak').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [[3, 'desc']],
			'columnDefs': [
				{ 'orderable': false, 'targets': [0, 1, 2] },
				{ 'className' : 'padat', 'targets': [0, 1, 4, 5] },
				{ 'className' : 'aksi', 'targets': [2] }
			],
			'ajax': {
				'url': "<?= site_url("{$this->controller}/pelapak"); ?>",
				'method': 'POST',
				'data': function(d) {
					d.status = $('#status').val();
				}
			},
			'columns': [
				{
					'data': function(data) {
						if (data.jumlah == 0) {
							return `<input type="checkbox" name="id_cb[]" value="${data.id}"/>`
						} else return ''
					}
				},
				{ 'data': null },
				{
					'data': function(data) {
						let status;
						if (data.status == 1) {
							status = `<a href="<?= site_url("{$this->controller}/pelapak_status/"); ?>${data.id}/2" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan Pelapak"><i class="fa fa-unlock"></i></a>`
						} else {
							status = `<a href="<?= site_url("{$this->controller}/pelapak_status/"); ?>${data.id}/1" class="btn bg-navy btn-flat btn-sm" title="Aktifkan Pelapak"><i class="fa fa-lock"></i></a>`
						}

						let hapus;
						if (data.jumlah == 0) {
							hapus = `<a href="#" data-href="<?= site_url("{$this->controller}/pelapak_delete/"); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>`
						} else { hapus = ''}

						return `
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url("{$this->controller}/pelapak_form/"); ?>${data.id}" title="Edit Data" class="btn bg-orange btn-flat btn-sm" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Ubah Pelapak"><i class="fa fa-edit"></i></a>
							${status}
						<?php endif; ?>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							${hapus}
						<?php endif; ?>
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url("{$this->controller}/pelapak_maps/"); ?>${data.id}" class="btn bg-green btn-flat btn-sm" title="Lokasi"><i class="fa fa-map"></i></a>
						<?php endif; ?>
						`
					}
				},
				{ 'data': 'pelapak' },
				{ 'data': 'telepon' },
				{ 'data': 'jumlah'}
			],
			'language': {
				'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
			}
		});

		tabel_produk.on('draw.dt', function() {
			let PageInfo = $('#tabel-pelapak').DataTable().page.info();
			tabel_produk.column(1, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});

		$('#status').on('select2:select', function (e) {
			tabel_produk.ajax.reload();
		});
	});
</script>
