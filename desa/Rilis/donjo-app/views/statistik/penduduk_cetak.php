<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * Format cetak untuk modul Statistik Kependudukan
 *
 * donjo-app/views/statistik/penduduk_cetak.php,
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
			<td align="center" >
				<?php if ($aksi != 'unduh'): ?>
					<img src="<?= gambar_desa($config['logo']); ?>" alt="" style="width:100px; height:auto">
				<?php endif; ?>
				<h1>PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?> <?= strtoupper($config['nama_kabupaten'])?> </h1>
				<h1 style="text-transform: uppercase;"></h1>
				<h1><?= strtoupper($this->setting->sebutan_kecamatan)?> <?= strtoupper($config['nama_kecamatan'])?> </h1>
				<h1><?= strtoupper($this->setting->sebutan_desa) . ' ' . strtoupper($config['nama_desa'])?></h1>
				<h1>LAPORAN DATA STATISTIK KEPENDUDUKAN MENURUT <?= strtoupper($stat)?></h1>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 20px;">
				<br>
				<table>
					<tbody>
						<tr>
							<td class="top" width="60%">
								<div class="nowrap">
									<label style="width: 150px;">Laporan. No</label>
									<label>:</label>
									<span><?= $laporan_no ?></span>
								</div>
							</td>
						</tr>
						<?php if ($dusun) : ?>
							<tr>
								<td class="top" width="60%">
									<div class="nowrap">
										<label style="width: 150px;"><?= ucwords($this->setting->sebutan_dusun) ?></label>
										<label>:</label>
										<span><?= ucwords($dusun) ?></span>
									</div>
								</td>
							</tr>
						<?php endif ?>
						<?php if ($rw) : ?>
							<tr>
								<td class="top" width="60%">
									<div class="nowrap">
										<label style="width: 150px;">RW</label>
										<label>:</label>
										<span><?= $rw ?></span>
									</div>
								</td>
							</tr>
						<?php endif ?>
						<?php if ($rt) : ?>
							<tr>
								<td class="top" width="60%">
									<div class="nowrap">
										<label style="width: 150px;">RT</label>
										<label>:</label>
										<span><?= $rt ?></span>
									</div>
								</td>
							</tr>
						<?php endif ?>
					</tbody>
				</table>
				<br>
				<table class="border thick data">
					<thead>
						<tr class="thick">
							<th class="thick">No</th>
							<th class="thick" width="50%"><?= $stat?></th>
							<th class="thick" width="16%">Jumlah</th>
							<th class="thick" width="16%">Laki-laki</th>
							<th class="thick" width="16%">Perempuan</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
							<tr>
								<td class="thick" align="center" width="2"><?= $data['no']?></td>
								<td class="thick"><?= strtoupper($data['nama'])?></td>
								<td class="thick"><?= $data['jumlah']?></td>
								<td class="thick"><?= $data['laki']?></td>
								<td class="thick"><?= $data['perempuan']?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<br>
				<table class="noborder">
					<tbody>
						<tr>
							<td class="top" colspan="5" >
								<div class="nowrap">
									<label>Laporan data statistik kependudukan menurut <?= strtolower($stat)?> pada tanggal</label>
									<label>:</label>
									<strong><?= tgl_indo(date('Y m d'))?></strong>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
