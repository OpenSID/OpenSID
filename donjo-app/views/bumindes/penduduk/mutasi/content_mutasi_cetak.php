<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa > Buku Mutasi Penduduk Desa
 *
 * donjo-app/views/bumindes/penduduk/mutasi/content_mutasi_cetak.php,
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

<table>
	<tbody>
		<tr>
			<td>
				<?php if ($aksi != 'unduh'): ?>
					<img class="logo" src="<?= gambar_desa($config['logo']); ?>" alt="logo-desa">
				<?php endif; ?>
				<h1 class="judul">
					PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten'] . ' <br>' . $this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan'] . ' <br>' . $this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?>
				</h1>
			</td>
		</tr>
		<tr>
			<td><hr class="garis"></td>
		</tr>
		<tr>
			<td class="text-center">
				<h4>B.2 BUKU MUTASI PENDUDUK DESA</h4>
			</td>
		</tr>
		<tr>
			<td class="text-center">
				<h4>BUKU MUTASI PENDUDUK DESA BULAN <?= strtoupper(getBulan($bulan)) ?> TAHUN <?= $tahun ?></h4>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th rowspan="2">NOMOR URUT</th>
							<th rowspan="2">NAMA LENGKAP / PANGGILAN</th>
							<th colspan="2">TEMPAT & TANGGAL LAHIR</th>
							<th rowspan="2">JENIS KELAMIN</th>
							<th rowspan="2">KEWARGANEGARAAN</th>
							<th colspan="2">PENAMBAHAN</th>
							<th colspan="4">PENGURANGAN</th>
							<th rowspan="2">KET</th>
						</tr>
						<tr class="border thick">
							<th>TEMPAT LAHIR</th>
							<th>TANGGAL</th>
							<th>DATANG DARI</th>
							<th>TANGGAL</th>
							<th>PINDAH KE</th>
							<th>TANGGAL</th>
							<th>MENINGGAL</th>
							<th>TANGGAL</th>
						</tr>
						<tr class="border thick">
							<th>1</th>
							<th>2</th>
							<th>3</th>
							<th>4</th>
							<th>5</th>
							<th>6</th>
							<th>7</th>
							<th>8</th>
							<th>9</th>
							<th>10</th>
							<th>11</th>
							<th>12</th>
							<th>13</th>
						</tr>
					</thead>
					<tbody>
					<?php if ($main): ?>
						<?php foreach ($main as $key => $data): ?>
							<tr>
								<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
								<td><?= strtoupper($data['nama'])?></td>
								<td><?= $data['tempatlahir']?></td>
								<td><?= tgl_indo_out($data['tanggallahir'])?></td>
								<td><?= strtoupper($data['sex']) ?></td>
								<td><?= $data['warganegara']?></td>
								<td><?= $data['kode_peristiwa'] == 5 ? strtoupper($data['alamat_sebelumnya']) : '-'; ?></td>
								<td><?= $data['kode_peristiwa'] == 5 ? tgl_indo_out($data['created_at']) : '-'; ?></td>
								<td><?= strtoupper($data['kode_peristiwa'] == 3 ? $data['alamat_tujuan'] : '-'); ?></td>
								<td><?= $data['kode_peristiwa'] == 3 ? tgl_indo_out($data['tgl_peristiwa']) : '-'; ?></td>
								<td><?= strtoupper($data['kode_peristiwa'] == 2 ? $data['meninggal_di'] : '-'); ?></td>
								<td><?= $data['kode_peristiwa'] == 2 ? tgl_indo_out($data['tgl_peristiwa']) : '-'; ?></td>
								<td><?= $data['catatan'] ? strtoupper($data['catatan']) : '-'; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>