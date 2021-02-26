<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View cetak/unduh anggota kelompok di modul Kelompok
 *
 * donjo-app/views/kelompok/anggota/cetak.php
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
			<td align="center">
				<?php if ($aksi != 'unduh'): ?>
					<img src="<?= gambar_desa($config['logo']);?>" alt="" style="width:100px; height:auto">
				<?php endif; ?>
				<h1>PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?> <?= strtoupper($config['nama_kabupaten'])?> </h1>
				<h1 style="text-transform: uppercase;"></h1>
				<h1><?= strtoupper($this->setting->sebutan_kecamatan)?> <?= strtoupper($config['nama_kecamatan'])?> </h1>
				<h1><?= strtoupper($this->setting->sebutan_desa)." ".strtoupper($config['nama_desa'])?></h1>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 20px;">
				<hr style="border-bottom: 2px solid #000000; height:0px;">
			</td>
		</tr>
		<tr>
			<td align="center" >
				<h4><u>Daftar Anggota Kelopok <?= ucwords($kelompok['nama']); ?></u></h4>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 20px;">
				<strong>Nama Kelompok : </strong><?= $kelompok['nama']; ?><br>
				<strong>Ketua Kelompok : </strong><?= $kelompok['nama_ketua']; ?><br>
				<strong>Kategori Kelompok : </strong><?= $kelompok['kategori']; ?><br>
				<strong>Keterangan : </strong><?= $kelompok['keterangan'];?>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 20px;">
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>NO.</th>
							<th>NO. ANGGOTA</th>
							<th>JABATAN</th>
							<th>SK JABATAN</th>
							<th>NIK</th>
							<th>NAMA</th>
							<th>TEMPAT / TANGGAL LAHIR</th>
							<th>UMUR (TAHUN)</th>
							<th>JENIS KELAMIN</th>
							<th>ALAMAT</th>
							<th>KETERANGAN</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $key => $data): ?>
							<tr>
								<td align="center"><?= ($key + 1)?></td>
								<td class="textx" align="center"><?= $data['no_anggota']?></td>
								<td><?= $this->referensi_model->list_ref(JABATAN_KELOMPOK)[$data['jabatan']]?></td>
								<td><?= $data['no_sk_jabatan']?></td>
								<td class="textx"><?= $data['nik']?></td>
								<td><?= $data['nama']?></td>
								<td><?= strtoupper($data['tempatlahir'] . ' / ' . tgl_indo($data['tanggallahir']))?></td>
								<td class="textx" align="center"><?= $data['umur']?></td>
								<td><?= $data['sex']?></td>
								<td><?= $data['alamat']?></td>
								<td><?= $data['keterangan']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
