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
	<h3>JUMLAH PENDUDUK, SASARAN DAN CAPAIAN VAKSIN <?= strtoupper($this->setting->sebutan_desa) ?> (<?= strtoupper($header['nama_desa']) ?>)</h3>
	<h4><?= ($umur_sasaran == 0) ? '' : "rentang umur {$umur_sasaran} tahun s/d seterusnya "; ?></h4>
	<h4></h4>
</div>
<br>
<table class="list border thick">
	<thead style="background-color:#f9f9f9;">
		<tr>
			<th rowspan="3">No</th>
			<th rowspan="3">Desa</th>
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
		<?php
                            $i            = 0;
                            $tot_penduduk = 0;
                            $tot_sasaran  = 0;
                            $tot_vaksin_1 = 0;
                            $tot_vaksin_2 = 0;
                            $tot_vaksin_3 = 0;
                            $tot_belum    = 0;
                    ?>
		<?php foreach ($main as $key => $data) : ?>
			<?php
                                    // TODO : Perhitungan jangan simpan disini
                                    $sasaran_vaksin   = $sasaran[$key];
                                    $sasaran_vaksin_1 = count($sasaran_vaksin['vaksin_1']);
                                    $sasaran_vaksin_2 = count($sasaran_vaksin['vaksin_2']);
                                    $sasaran_vaksin_3 = count($sasaran_vaksin['vaksin_3']);
                                    $sasaran_belum    = count($sasaran_vaksin['belum']);
                                    $sasaran_total    = $sasaran_vaksin_1 + $sasaran_vaksin_2 + $sasaran_vaksin_3 + $sasaran_belum;
                                    $sasaran_sudah    = $sasaran_vaksin_1 + $sasaran_vaksin_2 + $sasaran_vaksin_3;
                                    $tot_pend_dusun   = count($data['vaksin_1']) + count($data['vaksin_2']) + count($data['vaksin_3']) + count($data['belum']);
                                    $tot_penduduk     = $tot_penduduk + $tot_pend_dusun;
                                    $tot_sasaran      = $tot_sasaran + $sasaran_total;
                                    $tot_vaksin_1     = $tot_vaksin_1 + $sasaran_vaksin_1;
                                    $tot_vaksin_2     = $tot_vaksin_2 + $sasaran_vaksin_2;
                                    $tot_vaksin_3     = $tot_vaksin_3 + $sasaran_vaksin_3;
                                    $tot_belum        = $tot_belum + $sasaran_belum;
                                    $i++;
                            ?>
			<tr>
				<td class="padat"><?= $i ?></td>
				<td><?= $key ?></td>
				<td class="padat"><?= count($data['vaksin_1']) + count($data['vaksin_2']) + count($data['vaksin_3']) + count($data['belum']) ?></td>
				<td class="padat"><?= $sasaran_total ?></td>
				<td class="padat"><?= $sasaran_vaksin_1 ?></td>
				<td class="padat"><?= $sasaran_vaksin_2 ?></td>
				<td class="padat"><?= $sasaran_vaksin_3 ?></td>
				<td class="padat"><?= $sasaran_sudah ?></td>
				<td class="padat"><?= $sasaran_belum ?></td>
				<td class="padat"><?= persen($sasaran_vaksin_1 / $sasaran_total, '', 2); ?></td>
				<td class="padat"><?= persen($sasaran_vaksin_2 / $sasaran_total, '', 2); ?></td>
				<td class="padat"><?= persen($sasaran_vaksin_3 / $sasaran_total, '', 2); ?></td>
				<td class="padat"><?= persen($sasaran_belum / $sasaran_total, '', 2); ?></td>
			</tr>
		<?php endforeach ?>
		<tr class="text-bold" >
			<td colspan="2">JUMLAH</td>
			<td class="padat"><?= $tot_penduduk ?></td>
			<td class="padat"><?= $tot_sasaran ?></td>
			<td class="padat"><?= $tot_vaksin_1 ?></td>
			<td class="padat"><?= $tot_vaksin_2 ?></td>
			<td class="padat"><?= $tot_vaksin_3 ?></td>
			<td class="padat"><?= $tot_vaksin_1 + $tot_vaksin_2 + $tot_vaksin_3?></td>
			<td class="padat"><?=  $tot_belum ?></td>
			<td class="padat"><?=  persen($tot_vaksin_1 / $tot_sasaran, '', 2); ?></td>
			<td class="padat"><?=  persen($tot_vaksin_2 / $tot_sasaran, '', 2); ?></td>
			<td class="padat"><?=  persen($tot_vaksin_3 / $tot_sasaran, '', 2); ?></td>
			<td class="padat"><?=  persen($tot_belum / $tot_sasaran, '', 2); ?></td>
		</tr>
	</tbody>
</table>