<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa >  Administrasi Pembangunan > Buku Tanah Desa
 *
 * donjo-app/views/bumindes/pembangunan/tanah_di_desa/tanah_di_desa_cetak.php
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
				<h4>BUKU TANAH DI DESA</h4>
			</td>
		</tr>
		<tr>
			<td class="text-center">
				<h4>BUKU TANAH DESA BULAN <?= strtoupper(getBulan($bulan)) ?> TAHUN <?= $tahun ?></h4>
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
							<th rowspan="2">NAMA PERORANGAN / BADAN HUKUM</th>
							<th rowspan="2">NOMOR LETTER C</th>
							<th rowspan="2">PERSIL</th>
							<th rowspan="2">NOMOR SERTIFIKAT</th>
							<th rowspan="2">TANGGAL SERTIFIKAT</th>
							<th rowspan="2">LUAS (M<sup>2</sup>)</th>
							<th rowspan="2">STATUS HAK TANAH</th>							
							<th rowspan="2">PENGGUNAAN TANAH</th>
							<th rowspan="2">LAIN - LAIN</th>
							<th rowspan="2">KETERANGAN</th>										 
						</tr>					
						<tr class="border thick">							
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
						</tr>
					</thead>
					<tbody>
						<?php 
							$count = 1;
							foreach ($main as $data): 						
						?>
							<tr>
								<td><?= $count ?></td>
								<td><?= strtoupper($data['nama_pemilik_asal'])?></td>
								<td><?= strtoupper($data['letter_c']) ?></td>
								<td><?= strtoupper($data['persil']) ?></td>
								<td><?= strtoupper($data['nomor_sertif']) ?></td>
								<td><?= tgl_indo_out($data['tanggal_sertif']) ?></td>
								<td><?= strtoupper($data['luas']) ?></td>
								<td><?= strtoupper($data['hak_tanah']) ?></td>
								<td><?= strtoupper($data['penggunaan_tanah']) ?></td>
								<td><?= strtoupper($data['lain']) ?></td>
								<td><?= strtoupper($data['keterangan']) ?></td>								
							</tr>
						<?php
							$count++;
						 	endforeach; 
						?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>