<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa > Buku Rekapitulasi Jumlah Penduduk
 *
 * donjo-app/views/bumindes/penduduk/rekapitulasi/content_rekapitulasi_cetak.php,
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
				<?php if ($aksi == 'cetak'): ?>
					<img class="logo" src="<?= gambar_desa($config['logo']); ?>" alt="logo-desa">
				<?php elseif ($aksi == 'pdf'): ?>
					<div style="text-align: center;">
						<img class="logo" src="<?= gambar_desa($config['logo'], false, $file = true); ?>" alt="logo-desa">
					</div>
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
				<h4>B3. BUKU REKAPITULASI JUMLAH PENDUDUK</h4>
			</td>
		</tr>
		<tr>
			<td class="text-center">
				<h4>BUKU REKAPITULASI JUMLAH PENDUDUK BULAN <?= strtoupper(getBulan($bulan)) ?> TAHUN <?= $tahun ?></h4>
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
							<th rowspan="4">NOMOR URUT</th>
							<th rowspan="4">NAMA DUSUN / LINGKUNGAN</th>
							<th colspan="7">JUMLAH PENDUDUK AWAL BULAN</th>
							<th colspan="8">TAMBAHAN BULAN INI</th>
							<th colspan="8">PENGURANGAN BULAN INI</th>
							<th rowspan="2" colspan="7">JML PENDUDUK AKHIR BULAN</th>
							<th rowspan="4">KET</th>
						</tr>
						<tr class="border thick">
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th rowspan="3">JLH KK</th>
							<th rowspan="3">JML ANGGOTA KELUARGA</th>
							<th rowspan="3">JML JIWA (7+8)</th>
							<th colspan="4">LAHIR</th>
							<th colspan="4">DATANG</th>
							<th colspan="4">MENINGGAL</th>
							<th colspan="4">PINDAH</th>
						</tr>
						<tr class="border thick">
							<th rowspan="2">L</th>
							<th rowspan="2">P</th>
							<th rowspan="2">L</th>
							<th rowspan="2">P</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th colspan="2">WNA</th>
							<th colspan="2">WNI</th>
							<th rowspan="2">JML KK</th>
							<th rowspan="2">JML ANGGOTA KELUARGA</th>
							<th rowspan="2">JML JIWA (30+31)</th>
						</tr>
						<tr class="border thick">
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
							<th>L</th>
							<th>P</th>
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
							<th>20</th>
							<th>21</th>
							<th>22</th>
							<th>23</th>
							<th>24</th>
							<th>25</th>
							<th>26</th>
							<th>27</th>
							<th>28</th>
							<th>29</th>
							<th>30</th>
							<th>31</th>
							<th>32</th>
							<th>33</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($main): ?>
							<?php foreach ($main as $key => $data): ?>
								<tr>
									<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
									<td><?= strtoupper($data['DUSUN'])?></td>
									<td><?= show_zero_as($data['WNA_L_AWAL'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_P_AWAL'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_L_AWAL'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_P_AWAL'], '-') ?></td>
									<td><?= show_zero_as($data['KK_JLH'], '-') ?></td>
									<td><?= show_zero_as($data['KK_ANG_KEL'], '-') ?></td>
									<td><?= show_zero_as($data['KK_JLH'] + $data['KK_ANG_KEL'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_L_TAMBAH_LAHIR'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_P_TAMBAH_LAHIR'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_L_TAMBAH_LAHIR'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_P_TAMBAH_LAHIR'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_L_TAMBAH_MASUK'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_P_TAMBAH_MASUK'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_L_TAMBAH_MASUK'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_P_TAMBAH_MASUK'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_L_KURANG_MATI'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_P_KURANG_MATI'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_L_KURANG_MATI'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_P_KURANG_MATI'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_L_KURANG_KELUAR'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_P_KURANG_KELUAR'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_L_KURANG_KELUAR'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_P_KURANG_KELUAR'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_L_AKHIR'], '-') ?></td>
									<td><?= show_zero_as($data['WNA_P_AKHIR'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_L_AKHIR'], '-') ?></td>
									<td><?= show_zero_as($data['WNI_P_AKHIR'], '-') ?></td>
									<td><?= show_zero_as($data['KK_AKHIR_JML'], '-') ?></td>
									<td><?= show_zero_as($data['KK_AKHIR_ANG_KEL'], '-') ?></td>
									<td><?= show_zero_as($data['KK_AKHIR_JML'] + $data['KK_AKHIR_ANG_KEL'], '-') ?></td>
									<td>-</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
