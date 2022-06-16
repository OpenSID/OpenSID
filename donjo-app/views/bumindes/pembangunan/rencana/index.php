<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Pembangunan Desa > Buku Rencana Pembangunan
 *
 * donjo-app/views/bumindes/pembangunan/rencana_kerja/index.php,
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

<form id="mainformexcel" name="mainformexcel"method="post" class="form-horizontal">
	<div class="box box-info">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-sm-2">
					<select class="form-control input-sm select2" id="tahun" name="tahun">
						<option selected value="semua">Semua Tahun</option>
						<?php foreach ($list_tahun as $list) : ?>
							<option value="<?= $list->tahun_anggaran ?>"><?= $list->tahun_anggaran ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-sm-10">
					<a href="<?= site_url($this->controller . '/dialog/cetak'); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Rencana Kerja Pembangunan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Rencana Kerja Pembangunan"><i class="fa fa-print "></i> Cetak</a>
					<a href="<?= site_url($this->controller . '/dialog/unduh'); ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Rencana Kerja Pembangunan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Rencana Kerja Pembangunan"><i class="fa fa-download"></i> Unduh</a>
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table id="tabel-pembangunan" class="table table-bordered dataTable table-striped table-hover tabel-daftar">
					<thead class="bg-gray disabled color-palette">
						<tr>
							<th rowspan="2">NOMOR URUT</th>
							<th rowspan="2">NAMA PROYEK / KEGIATAN</th>
							<th rowspan="2">LOKASI</th>
							<th colspan="4">SUMBER DANA</th>
							<th rowspan="2">JUMLAH</th>
							<th rowspan="2">PELAKSANA</th>
							<th rowspan="2">MANFAAT</th>
							<th rowspan="2">KET</th>
						</tr>
						<tr>
							<th>PEMERINTAH</th>
							<th>PROVINSI</th>
							<th>KAB/KOTA</th>
							<th>SWADAYA</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</form>
<?php $this->load->view('global/confirm_delete'); ?>
<script>
	$(document).ready(function() {
		let tabelPembangunan = $('#tabel-pembangunan').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [
				[1, 'desc'],
			],
			'columnDefs': [
				{ 'orderable': false, 'targets': [0] },
				{ 'className' : 'padat', 'targets': [0, 3, 4, 5, 6, 7] },
			],
			'ajax': {
				'url': SITE_URL + 'bumindes_rencana_pembangunan',
				'method': 'POST',
				'data': function(d) {
					d.tahun = $('#tahun').val();
				}
			},
			'columns': [
				{
					'data': null,
				},
				{
					'data': 'judul'
				},
				{
					'data': 'alamat'
				},
				{
					'data': 'sumber_biaya_pemerintah',
					'render': $.fn.dataTable.render.number( '.', '.', 0, 'Rp ' )
				},
				{
					'data': 'sumber_biaya_provinsi',
					'render': $.fn.dataTable.render.number( '.', '.', 0, 'Rp ' )
				},
				{
					'data': 'sumber_biaya_kab_kota',
					'render': $.fn.dataTable.render.number( '.', '.', 0, 'Rp ' )
				},
				{
					'data': 'sumber_biaya_swadaya',
					'render': $.fn.dataTable.render.number( '.', '.', 0, 'Rp ' )
				},
				{
					'data': 'sumber_biaya_jumlah',
					'render': $.fn.dataTable.render.number( '.', '.', 0, 'Rp ' )
				},
				{
					'data': 'pelaksana_kegiatan'
				},
				{
					'data': 'manfaat'
				},
				{
					'data': 'keterangan'
				},
			],
			'language': {
				'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
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
