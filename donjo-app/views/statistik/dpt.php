<?php defined('BASEPATH') || exit('No direct script access allowed');

/*
 *  File ini:
 *
 * View halaman dpt untuk tema clasik
 *
 * donjo-app/views/statistik/dpt.php
 *
 */
/*
 *  File ini bagian dari:
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

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Calon Pemilih Berdasarkan Wilayah (pada tgl pemilihan <?= $tanggal_pemilihan; ?>)</h3>
	</div>
	<div class="box-body">
		<?php if (count($main) > 0): ?>
			<table id="dpt" class="table table-striped">
				<thead>
					<tr>
						<th class="kiri">No</th>
						<th class="kiri">Nama <?= ucwords($this->setting->sebutan_dusun); ?></th>
						<th class="kiri">RW</th>
						<th class="kanan">Jiwa</th>
						<th class="kanan">Lk</th>
						<th class="kanan">Pr</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($main as $data): ?>
						<tr>
							<td><?= $data['no']; ?></td>
							<td><?= strtoupper($data['dusun']); ?></td>
							<td><?= strtoupper($data['rw']); ?></td>
							<td class="angka"><?= $data['jumlah_warga']; ?></td>
							<td class="angka"><?= $data['jumlah_warga_l']; ?></td>
							<td class="angka"><?= $data['jumlah_warga_p']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfooter>
					<tr>
						<th colspan="3" class="angka">TOTAL</th>
						<th class="angka"><?= $total['total_warga']; ?></th>
						<th class="angka"><?= $total['total_warga_l']; ?></th>
						<th class="angka"><?= $total['total_warga_p']; ?></th>
					</tr>
				</tfooter>
			</table>
		<?php else: ?>
			<div class="">Data tidak tersedia</div>
		<?php endif; ?>
	</div>
</div>
