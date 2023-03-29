<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Admin Pembangunan
 *
 * donjo-app/views/pembangunan/fadmin/index.php,
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
		<h1>Pembangunan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pembangunan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainformexcel" name="mainformexcel"method="post" class="form-horizontal">
			<div class="box box-info">
				<div class="box-header with-border">
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<a href="<?= site_url("{$this->controller}/form")?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data Baru"><i class="fa fa-plus"></i>Tambah Data</a>
					<?php endif; ?>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-sm-2">
						<select class="form-control input-sm select2" id="tahun" name="tahun">
							<option selected value="semua">Semua Tahun</option>
							<?php foreach ($list_tahun as $list) : ?>
								<option value="<?= $list->tahun_anggaran ?>"><?= $list->tahun_anggaran ?></option>
							<?php endforeach; ?>
						</select>
						</div>
					</div>
					<hr>
					<div class="table-responsive">
						<table id="tabel-pembangunan" class="table table-bordered dataTable table-hover">
							<thead class="bg-gray">
								<tr>
									<th class="text-center">No</th>
									<th width="230px" class="text-center">Aksi</th>
									<th class="text-center">Nama Kegiatan</th>
									<th class="text-center">Sumber Dana</th>
									<th class="text-center">Anggaran</th>
									<th class="text-center">Persentase</th>
									<th class="text-center">Volume</th>
									<th class="text-center">Tahun</th>
									<th class="text-center">Pelaksana</th>
									<th class="text-center">Lokasi</th>
									<th class="text-center">Gambar</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<?php $this->load->view('global/sinkronisasi_notif'); ?>
<script>
	$(document).ready(function() {
		let tabelPembangunan = $('#tabel-pembangunan').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [
				[7, 'desc'],
			],
			'columnDefs': [{
				'orderable': false,
				'targets': [0, 1, 10],
			}],
			'ajax': {
				'url': "<?= site_url($this->controller) ?>",
				'method': 'POST',
				'data': function(d) {
					d.tahun = $('#tahun').val();
				}
			},
			'columns': [
				{ 'data': null },
				{
					'data': function(data) {
						let status;
						if (data.status == 1) {
							status = `<a href="<?= site_url($this->controller . '/lock/') ?>${data.id}" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan Pembangunan"><i class="fa fa-unlock"></i></a>`
						} else {
							status = `<a href="<?= site_url($this->controller . '/unlock/') ?>${data.id}" class="btn bg-navy btn-flat btn-sm" title="Aktifkan Pembangunan"><i class="fa fa-lock"></i></a>`
						}

						return `
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?= site_url("{$this->controller}/form/"); ?>${data.id}" title="Ubah Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
							<?php endif; ?>
							<a href="<?= site_url($this->controller . '/lokasi_maps/'); ?>${data.id}" class="btn bg-olive btn-flat btn-sm" title="Lokasi Pembangunan"><i class="fa fa-map"></i></a>
							<a href="<?= site_url($this->controller . '/dokumentasi/'); ?>${data.id}" class="btn bg-purple btn-flat btn-sm" title="Rincian Dokumentasi Kegiatan"><i class="fa fa-list-ol"></i></a>
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								${status}
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#" data-href="<?= site_url($this->controller . '/delete/'); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
							<?php endif; ?>
							<a href="<?= site_url('pembangunan/'); ?>${data.slug}" target="_blank" class="btn bg-blue btn-flat btn-sm" title="Lihat Summary"><i class="fa fa-eye"></i></a>
							`
					}
				},
				{
					'data': 'judul'
				},
				{
					'data': 'sumber_dana'
				},
				{
					'data': 'jml_anggaran',
					'render': $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' )
				},
				{
					'data': 'max_persentase'
				},
				{
					'data': 'volume'
				},
				{
					'data': 'tahun_anggaran'
				},
				{
					'data': 'pelaksana_kegiatan'
				},
				{
					'data': 'alamat'
				},
				{
					'data': function (data) {
						if (data.foto) {
							return `<img src="<?= base_url(LOKASI_GALERI) ?>${data.foto}" class="penduduk_kecil text-center" alt="Gambar Dokumentasi">`
						}

						return null
					}
				},
			],
			'language': {
				'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
			}
		});

		tabelPembangunan.on('draw.dt', function() {
			let PageInfo = $('#tabel-pembangunan').DataTable().page.info();
			tabelPembangunan.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});

		$('#tahun').on('select2:select', function (e) {
			tabelPembangunan.ajax.reload();
		});
	});
</script>
