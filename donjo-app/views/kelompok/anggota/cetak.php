<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View cetak/unduh anggota kelompok di modul Kelompok
 *
 * donjo-app/views/kelompok/anggota/cetak.php
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
			<td align="center">
				<?php if ($aksi != 'unduh'): ?>
					<img src="<?= gambar_desa($config['logo']); ?>" alt="" style="width:100px; height:auto">
				<?php endif; ?>
				<h1>PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten']); ?> </h1>
				<h1><?= strtoupper($this->setting->sebutan_kecamatan . '' . $config['nama_kecamatan']); ?> </h1>
				<h1><?= strtoupper($this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?></h1>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 20px;">
				<hr style="border-bottom: 2px solid #000000; height:0px;">
			</td>
		</tr>
		<tr>
			<td align="center" >
				<h4><u>Daftar Anggota <?= ucwords($this->controller . ' ' . $kelompok['nama']); ?></u></h4>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 20px;">
				<strong>Nama <?= ucwords($this->controller); ?> : </strong><?= $kelompok['nama']; ?><br>
				<strong>Ketua <?= ucwords($this->controller); ?> : </strong><?= $kelompok['nama_ketua']; ?><br>
				<strong>Kategori <?= ucwords($this->controller); ?> : </strong><?= $kelompok['kategori']; ?><br>
				<strong>Keterangan : </strong><?= $kelompok['keterangan']; ?>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 20px;">
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>No.</th>
							<th>No. Anggota</th>
							<th>NIK</th>
							<th>Nama Lengkap</th>
							<th>Jenis Kelamin</th>
							<th>Tempat / Tanggal Lahir</th>
							<th>Agama</th>
							<th>Jabatan</th>
							<th>Pendidikan Terakhir</th>
							<?php if ($this->controller == 'lembaga') : ?>
								<th>Nomor Dan Tanggal Keputusan Pengangkatan</th>
								<th>Nomor Dan Tanggal Keputusan Pemberhentian</th>
							<?php endif ?>
							<th>Ket.</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($main as $key => $data): ?>
							<tr>
								<td align="center"><?= ($key + 1)?></td>
								<td class="textx" align="center"><?= $data['no_anggota']?></td>
								<td class="textx"><?= $data['nik']?></td>
								<td><?= $data['nama']?></td>
								<td><?= $data['sex']?></td>
								<td><?= strtoupper($data['tempatlahir'] . ' / ' . tgl_indo($data['tanggallahir']))?></td>
								<td><?= $data['agama'] ?></td>
								<td><?= $data['jabatan'] ?></td>
								<td><?= $data['pendidikan']?></td>
								<?php if ($this->controller == 'lembaga') : ?>
									<td><?= $data['nmr_sk_pengangkatan'] . ' / ' . tgl_indo_out($data['tgl_sk_pengangkatan'])?></td>
									<td><?= $data['nmr_sk_pemberhentian'] . ' / ' . tgl_indo_out($data['tgl_sk_pemberhentian']) ?></td>
								<?php endif ?>
								<td><?= $data['keterangan']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
