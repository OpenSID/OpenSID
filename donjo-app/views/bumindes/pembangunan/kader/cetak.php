<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Pembangunan Desa > Buku Kader Pemberdayaan Masyarakat
 *
 * donjo-app/views/bumindes/pembangunan/kader/cetak.php,
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
				<h3>BUKU KADER PEMBERDAYAAN MASYARAKAT</h3>
			</td>
		</tr>
		<?php
            $tahun = date('Y', strtotime($tgl_cetak));
if ($tahun):
    ?>
		<tr>
			<td class="text-center">
				<h4>TAHUN <?= $tahun; ?></h4>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No. Urut</th>
							<th>Nama</th>
							<th>Umur</th>
							<th>Jenis Kelamin</th>
							<th>Pendidikan / Kursus</th>
							<th>Bidang</th>
							<th>Alamat</th>
							<th>Keterangan</th>
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
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $key => $item): ?>
							<tr>
							<td align="center"><?= ($key + 1); ?></td>
								<td class="textx"><?= $item->nama; ?></td>
								<td align="center"><?= $item->umur; ?></td>
								<td align="center"><?= $item->jk; ?></td>
								<td><?= str_replace(',', ', ', $item->pendidikan . '<br/>' . preg_replace('/[^a-zA-Z, ]/', '', $item->kursus)); ?></td>
								<td><?= str_replace(',', ', ', preg_replace('/[^a-zA-Z, ]/', '', $item->bidang)); ?></td>
								<td><?= $item->alamat; ?></td>
								<td><?= $item->keterangan; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>