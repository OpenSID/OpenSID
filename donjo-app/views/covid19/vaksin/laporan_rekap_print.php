<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * File ini:
 *
 * View untuk modul Siaga Covid-19 > Vaksin
 *
 * donjo-app/views/covid19/vaksin/laporan_rekap_print.php
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


<div class="text-center">
	<h3>JUMLAH PENDUDUK, SASARAN DAN CAPAIAN VAKSIN <?= strtoupper($this->setting->sebutan_desa . ' ' . $config['nama_desa']) ?></h3>
	<h4>
		<?php if (count($umur_sasaran) == 1 && $umur_sasaran[0] != 0): ?>
			Rentang umur <?= $umur_sasaran[0] ?> tahun s/d seterusnya
		<?php elseif (count($umur_sasaran) == 2): ?>
			Rentang umur <?= $umur_sasaran[0] ?> tahun s/d <?= $umur_sasaran[1] ?> tahun
		<?php endif ?>
	</h4>
	<h4></h4>
</div>
<br>
<table class="list border thick">
	<thead style="background-color:#f9f9f9;">
		<tr>
			<th rowspan="3">No</th>
			<th rowspan="3">Dusun</th>
			<th rowspan="3">Jumlah Penduduk</th>
			<th rowspan="3">Jumlah Sasaran</th>
			<th rowspan="1" colspan="5">Sasaran Vaksin</th>
			<th rowspan="1" colspan="4">Presentase</th>
		</tr>
		<tr>
			<th colspan="4">Sudah</th>
			<th colspan="1" rowspan="2">Belum</th>
			<th colspan="1" rowspan="2">% V1</th>
			<th colspan="1" rowspan="2">% V2</th>
			<th colspan="1" rowspan="2">% V3</th>
			<th colspan="1" rowspan="2">% Belum Vaksin</th>
		</tr>
		<tr>
			<th>v1</th>
			<th>v2</th>
			<th>v3</th>
			<th>Jumlah</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($main['detail'] as $key => $data) : ?>
				<?php
                    $sasaran_vaksin = $sasaran['detail'][$key];
		    $i++;
		    ?>
				<tr>
					<td class="padat"><?= $i ?></td>
					<td><?= $key ?></td>
					<td class="padat"><?= $data['vaksin_1'] + $data['belum'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_1'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_2'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_3'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['vaksin_1'] ?></td>
					<td class="padat"><?= $sasaran_vaksin['belum'] ?></td>
					<td class="padat"><?= persen($sasaran_vaksin['vaksin_1'] / ($sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']), '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_vaksin['vaksin_2'] / ($sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']), '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_vaksin['vaksin_3'] / ($sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']), '', 2); ?></td>
					<td class="padat"><?= persen($sasaran_vaksin['belum'] / ($sasaran_vaksin['vaksin_1'] + $sasaran_vaksin['belum']), '', 2); ?></td>
				</tr>
			<?php endforeach ?>
			<tr class="text-bold" >
				<td colspan="2">JUMLAH</td>
				<td class="padat"><?= $main['total_v1'] + $main['total_belum'] ?></td>
				<td class="padat"><?= $sasaran['total_v1'] + $sasaran['total_belum'] ?></td>
				<td class="padat"><?= $sasaran['total_v1'] ?></td>
				<td class="padat"><?= $sasaran['total_v2'] ?></td>
				<td class="padat"><?= $sasaran['total_v3'] ?></td>
				<td class="padat"><?= $sasaran['total_v1'] ?></td>
				<td class="padat"><?= $sasaran['total_belum'] ?></td>
				<td class="padat"><?= persen($sasaran['total_v1'] / ($sasaran['total_v1'] + $sasaran['total_belum']), '', 2); ?></td>
				<td class="padat"><?= persen($sasaran['total_v2'] / ($sasaran['total_v1'] + $sasaran['total_belum']), '', 2); ?></td>
				<td class="padat"><?= persen($sasaran['total_v3'] / ($sasaran['total_v1'] + $sasaran['total_belum']), '', 2); ?></td>
				<td class="padat"><?= persen($sasaran['total_belum'] / ($sasaran['total_v1'] + $sasaran['total_belum']), '', 2); ?></td>
			</tr>
	</tbody>
</table>