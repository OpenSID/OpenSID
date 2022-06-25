<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul suplemen
 *
 * donjo-app/views/suplemen/rincian.php,
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

<div class="box-body">
	<h5><b>Rincian Suplemen</b></h5>
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover tabel-rincian">
			<tbody>
				<tr>
					<td width="20%">Nama Data</td>
					<td width="1%">:</td>
					<td><?= strtoupper($suplemen['nama']); ?></td>
				</tr>
				<tr>
					<td>Sasaran Terdata</td>
					<td>:</td>
					<td><?= $sasaran[$suplemen['sasaran']]; ?></td>
				</tr>
				<tr>
					<td>Keterangan</td>
					<td>:</td>
					<td><?= $suplemen['keterangan']; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<?php if ($this->session->flashdata('notif')): ?>
	<div class='modal fade' id='notif-box' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
						<h4 class='modal-title' id='myModalLabel'> Informasi</h4>
				</div>
				<div class='modal-body'>
					<?php $data = $this->session->flashdata('notif'); ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover tabel-rincian">
							<tbody>
								<tr>
									<td width="30%">Data Peserta Gagal</td>
									<td width="1">:</td>
									<td><?= $data['gagal']; ?></td>
								</tr>
								<tr>
									<td>Data Peserta Sukses</td>
									<td> : </td>
									<td><?= $data['sukses']; ?></td>
								</tr>
								<?php if ($data['pesan']): ?>
									<tr>
										<td>Informasi Tambahan </td>
										<td> : </td>
										<td><?= $data['pesan']; ?></td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(window).on('load', function() {
			$('#notif-box').modal('show');
		});
	</script>
<?php endif; ?>