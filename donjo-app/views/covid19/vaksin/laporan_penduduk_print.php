<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * File ini:
 *
 * View untuk modul Siaga Covid-19 > Vaksin
 *
 * donjo-app/views/covid19/vaksin/laporan_penduduk_print.php
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
 * @copyright	 Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	 Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<div class="text-center">
	<h3> DATA PENDUDUK YANG TELAH MENERIMA VAKSIN COVID-19</h3>
	<h4><?= strtoupper($this->setting->sebutan_desa) ?> <?= strtoupper($config['nama_desa']) ?> <?= strtoupper($this->setting->sebutan_kecamatan) ?> <?= strtoupper($config['nama_kecamatan']) ?></h4>
	<h4><?= strtoupper($this->setting->sebutan_kabupaten) ?> <?= strtoupper($config['nama_kabupaten']) ?></h4>
</div>
<br>
<table id="example" class="list border thick">
	<thead>
		<tr class="text-center">
			<th rowspan="2">No</th>
			<th rowspan="2">No KK</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">Nik</th>
			<th rowspan="2">Alamat <?= ucwords(setting('sebutan_dusun')) ?></th>
			<th rowspan="2">Jenis Kelamin</th>
			<th rowspan="2">Tempat Lahir</th>
			<th rowspan="2">Tanggal Lahir</th>
			<th rowspan="2">Umur</th>
			<th colspan="6">Vaksin</th>
		</tr>
		<tr class="text-center">
			<th>I</th>
			<th>II</th>
			<th>III</th>
			<th>Belum</th>
			<th>Tunda</th>
			<th>Ket</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($main as $key => $data) : ?>
			<tr>
				<td><?= $key + 1 ?></td>
				<td class="textx"><?= $data->no_kk ?></td>
				<td><?= $data->nama ?></td>
				<td class="textx"><?= $data->nik ?></td>
				<td><?= $data->dusun ?></td>
				<td><?= $data->jenis_kelamin ?></td>
				<td><?= $data->tempatlahir ?></td>
				<td class="padat"><?= rev_tgl($data->tanggallahir) ?></td>

				<td><?= $data->umur ?></td>
				<td class="padat">
					<?php if ($data->vaksin_1 == 1 && $data->tunda == 0) : ?>
						V
					<?php endif ?>
				</td>
				<td class="padat">
					<?php if ($data->vaksin_2 == 1 && $data->tunda == 0) : ?>
						V
					<?php endif ?>
				</td>
				<td class="padat">
					<?php if ($data->vaksin_3 == 1 && $data->tunda == 0) : ?>
						V
					<?php endif ?>
				</td>
				<td class="padat">
					<?php if ($data->vaksin_1 == null || ($data->tunda == 0 && $data->vaksin_1 == 0)) : ?>
						V
					<?php endif ?>
				</td>
				<td class="padat">
					<?php if ($data->tunda == 1) : ?>
						V
					<?php endif ?>
				</td>
				<td class="padat">
					<?php if ($data->tunda == 1) : ?>
						<?= $data->keterangan ?>
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>