<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View daftar Buku Kader Pemberdayaan Masyarakat di modul Buku Administrasi Desa
 *
 * donjo-app/views/bumindes/pembangunan/table.php
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

<div class="box box-info">
	<div class="box-header with-border">
		<a href="<?= site_url($this->controller . '/form'); ?>" title="Tambah Kader Pembangunan" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Data</a>
		<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url($this->controller . '/hapus_semua')?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
		<a href="<?= site_url($this->controller . '/dialog/cetak'); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Kegiatan Pembangunan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Kader Pemberdayaan Masyarakat"><i class="fa fa-print "></i> Cetak</a>
		<a href="<?= site_url($this->controller . '/dialog/unduh'); ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Rencana Kerja Pembangunan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Kader Pemberdayaan Masyarakat"><i class="fa fa-download"></i> Unduh</a>
	</div>

	<div class="box-body">
			<form id="mainform" name="mainform" method="post">
				<div class="table-responsive">
					<table id="tabel-data" class="table table-bordered dataTable table-striped table-hover tabel-daftar">
						<thead class="bg-gray color-palette">
							<tr>
								<th><input type="checkbox" id="checkall"/></th>
								<th>No</th>
								<th>Aksi</th>
								<th>Nama</th>
								<th>Umur</th>
								<th>Jenis Kelamin</th>
								<th>Pendidikan/Kursus</th>
								<th>Bidang</th>
								<th>Alamat</th>
								<th>Keterangan</th>
							</tr>
						</thead>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<script>
	$(document).ready(function() {
		let tabelData = $('#tabel-data').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [
				[3, 'desc'],
			],
			'columnDefs': [
				{ 'orderable': false, 'targets': [0, 1, 2] },
				{ 'className' : 'padat', 'targets': [0, 1, 4, 5] },
				{ 'className' : 'aksi', 'targets': [2] },
			],
			'ajax': {
				'url': SITE_URL + 'bumindes_kader',
				'method': 'POST',
			},
			'columns': [
				{
					'data': function(data) {
						return `
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<td class="padat"><input type="checkbox" name="id_cb[]" value="${data.id}"/></td>
							<?php endif; ?>
							`
					}
				},
				{ 'data': null },
				{
					'data': function(data) {
						return `
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?= site_url("{$this->controller}/form/"); ?>${data.id}" title="Ubah Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#" data-href="<?= site_url($this->controller . '/hapus/'); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
							<?php endif; ?>
							`
					}
				},
				{ 'data': 'nama' },
				{ 'data': 'umur' },
				{ 'data': 'jk' },
				{
					'data': function(data) {
						return (data.pendidikan + '<br/>' + data.kursus);
					}
				},
				{ 'data': 'bidang' },
				{ 'data': 'alamat' },
				{ 'data': 'keterangan' },
			],
			'language': {
				'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
			}
		});

		tabelData.on('draw.dt', function() {
			let PageInfo = $('#tabel-data').DataTable().page.info();
			tabelData.column(1, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});
	});
</script>
