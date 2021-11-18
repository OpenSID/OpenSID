<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa > Buku KTP dan KK
 *
 * donjo-app/views/bumindes/penduduk/ktpkk/content_ktpkk_cetak.php,
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
				<h4>B.5 BUKU KARTU TANDA PENDUDUK DAN BUKU KARTU KELUARGA</h4>
			</td>
		</tr>
		<tr>
			<td class="text-center">
				<h4>BUKU KARTU TANDA PENDUDUK DAN BUKU KARTU KELUARGA <?= strtoupper(getBulan($bulan)) ?> TAHUN <?= $tahun ?></h4>
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
							<th rowspan="2">NO. KK</th>
							<th rowspan="2">NAMA LENGKAP</th>
							<th rowspan="2">NIK</th>
							<th rowspan="2">JENIS KELAMIN</th>
							<th rowspan="2">TEMPAT / TANGGAL LAHIR</th>
							<th rowspan="2">GOL. DARAH</th>
							<th rowspan="2">AGAMA</th>
							<th rowspan="2">PENDIDIKAN</th>
							<th rowspan="2">PEKERJAAN</th>
							<th rowspan="2">ALAMAT</th>
							<th rowspan="2">STATUS PERKAWINAN</th>
							<th rowspan="2">TEMPAT DAN TANGGAL DIKELUARKAN</th>
							<th rowspan="2">STATUS HUB. KELUARGA</th>
							<th rowspan="2">KEWARGANEGARAAN</th>
							<th colspan="2">ORANG TUA</th>
							<th rowspan="2">TGL MULAI DI DESA</th>
							<th rowspan="2">KET</th>
						</tr>
						<tr class="border thick">
							<th>AYAH</th>
							<th>IBU</th>
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
							<th>14</th>
							<th>15</th>
							<th>16</th>
							<th>17</th>
							<th>18</th>
							<th>19</th>
						</tr>
					</thead>
					<tbody>
						<!--
							"""
							Menunggu detil informasi data tiap attributnya sudah atau belum,
							jika sudah ada bagaimana proses menuju flow tersebut
							"""
						-->

					<?php if ($main): ?>
						<?php foreach ($main as $data): ?>
						<tr>
							<td><?= $data['no']?></td>
							<td><?= $privasi_nik ? sensor_nik_kk($data['no_kk']) : ($aksi == 'unduh' ? $data['no_kk'] . '&nbsp' : $data['no_kk']) ?></td>
							<td><?= strtoupper($data['nama']) ?></td>
							<td><?= $privasi_nik ? sensor_nik_kk($data['nik']) : ($aksi == 'unduh' ? $data['nik'] . '&nbsp' : $data['nik']) ?></td>
							<td class="padat"><?= (strtoupper($data['sex']) == 'LAKI-LAKI') ? 'L' : 'P' ?></td>
							<td><?= strtoupper($data['tempatlahir']) . ', ' . tgl_indo_out($data['tanggallahir']) ?></td>
							<td class="padat"><?= $data['gol_darah']?></td>
							<td><?= $data['agama']?></td>
							<td><?= $data['pendidikan'] ?></td>
							<td><?= $data['pekerjaan'] ?></td>
							<td><?= strtoupper($data['alamat'] . ' RT ' . $data['rt'] . ' / RW ' . $data['rw'] . ' ' . $this->setting->sebutan_dusun . ' ' . $data['dusun'])?></td>
							<td><?= (strpos($data['kawin'], 'KAWIN') !== false) ? $data['kawin'] : (($data['sex'] == 'LAKI-LAKI') ? 'DUDA' : 'JANDA') ?></td>
							<td><?= empty($data['tempat_cetak_ktp']) ? '-' : strtoupper($data['tempat_cetak_ktp']) . ', ' . tgl_indo_out($data['tanggal_cetak_ktp']) ?></td>
							<td><?= $data['hubungan'] ?></td>
							<td><?= $data['warganegara'] ?></td>
							<td><?= strtoupper($data['nama_ayah']) ?></td>
							<td><?= strtoupper($data['nama_ibu']) ?></td>
							<td><?= tgl_indo_out($data['tanggal_datang']) ?></td>
							<td><?= $data['ket'] ?></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
