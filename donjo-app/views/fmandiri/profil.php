<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Profil
 *
 * donjo-app/views/fmandiri/profil.php
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

<div class="box box-solid">
	<div class="box-header with-border bg-blue">
		<h4 class="box-title">Profil</h4>
	</div>
	<div class="box-body box-line">
		<h4><b>BIODATA PENDUDUK</b></h4>
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-data">
				<tbody>
					<tr>
						<th colspan="3" class="judul">Data Dasar</th>
					</tr>
					<tr>
						<td width="30%">NIK</td>
						<td class="padat">:</td>
						<td><?= $penduduk['nik']; ?></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['nama']); ?></td>
					</tr>
					<tr>
						<td>Status Kepemilikan KTP</td>
						<td>:</td>
						<td>
							<table class="table table-bordered table-hover table-data">
								<tbody>
									<tr class="judul">
										<th>Wajib KTP</th>
										<th>KTP-EL</th>
										<th>Status Rekam</th>
										<th>Tag ID Card</th>
									</tr>
									<tr>
										<td><?= strtoupper($penduduk['wajib_ktp']); ?></td>
										<td><?= strtoupper($penduduk['ktp_el']); ?></td>
										<td><?= strtoupper($penduduk['status_rekam']); ?></td>
										<td><?= $penduduk['tag_id_card']; ?></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td>Nomor Kartu Keluarga</td>
						<td>:</td>
						<td>
							<?= $penduduk['no_kk']; ?>
							<?php if ($penduduk['status_dasar_id'] != '1' && $penduduk['no_kk'] != $penduduk['log_no_kk']) : ?>
								(waktu peristiwa {<?= $penduduk['status_dasar']; ?>}: {<?= $penduduk['log_no_kk']; ?>})
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td>Nomor KK Sebelumnya</td>
						<td>:</td>
						<td><?= $penduduk['no_kk_sebelumnya']; ?></td>
					</tr>
					<tr>
						<td>Hubungan Dalam Keluarga</td>
						<td>:</td>
						<td><?= $penduduk['hubungan']; ?></td>
					</tr>
					<tr>
						<td>Jenis Kelamin</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['sex']); ?></td>
					</tr>
					<tr>
						<td>Agama</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['agama']); ?></td>
					</tr>
					<tr>
						<td>Status Penduduk</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['status']); ?></td>
					</tr>
					<tr>
						<th colspan="3" class="judul">Data Kelahiran</th>
					</tr>
					<tr>
						<td>Akta Kelahiran</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['akta_lahir']); ?></td>
					</tr>
					<tr>
						<td>Tempat / Tanggal Lahir</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['tempatlahir']); ?> / <?= strtoupper($penduduk['tanggallahir']); ?></td>
					</tr>
					<tr>
						<td>Tempat Dilahirkan</td>
						<td>:</td>
						<td><?= $penduduk['tempat_dilahirkan_nama']; ?></td>
					</tr>
					<tr>
						<td>Jenis Kelahiran</td>
						<td>:</td>
						<td><?= $penduduk['jenis_kelahiran_nama']; ?></td>
					</tr>
					<tr>
						<td>Kelahiran Anak Ke</td>
						<td>:</td>
						<td><?= $penduduk['kelahiran_anak_ke']; ?></td>
					</tr>
					<tr>
						<td>Penolong Kelahiran</td>
						<td>:</td>
						<td><?= $penduduk['penolong_kelahiran_nama']; ?></td>
					</tr>
					<tr>
						<td>Berat Lahir</td>
						<td>:</td>
						<td><?= $penduduk['berat_lahir']; ?> Gram</td>
					</tr>
					<tr>
						<td>Panjang Lahir</td>
						<td>:</td>
						<td><?= $penduduk['panjang_lahir']; ?> cm</td>
					</tr>
					<tr>
						<th colspan="3" class="judul">Data Pendidikan dan Pekerjaan</th>
					</tr>
					<tr>
						<td>Pendidikan dalam KK</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['pendidikan_kk']); ?></td>
					</tr>
					<tr>
						<td>Pendidikan sedang ditempuh</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['pendidikan_sedang']); ?></td>
					</tr>
					<tr>
						<td>Pekerjaan</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['pekerjaan']); ?></td>
					</tr>
					<tr>
						<th colspan="3" class="judul">Data Kewarganegaraan</th>
					</tr>
					<tr>
						<td>Warga Negara</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['warganegara']); ?></td>
					</tr>
					<tr>
						<td>Nomor Paspor</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['dokumen_pasport']); ?></td>
					</tr>
					<tr>
						<td>Tanggal Berakhir Paspor</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['tanggal_akhir_paspor']); ?></td>
					</tr>
					<tr>
						<td>Nomor KITAS/KITAP</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['dokumen_kitas']); ?></td>
					</tr>
					<tr>
						<th colspan="3" class="judul">Data Orang Tua</th>
					</tr>
					<tr>
						<td>NIK Ayah</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['ayah_nik']); ?></td>
					</tr>
					<tr>
						<td>Nama Ayah</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['nama_ayah']); ?></td>
					</tr>
					<tr>
						<td>NIK Ibu</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['ibu_nik']); ?></td>
					</tr>
					<tr>
						<td>Nama Ibu</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['nama_ibu']); ?></td>
					</tr>
					<tr>
						<th colspan="3" class="judul">Data Alamat</th>
					</tr>
					<tr>
						<td>Nomor Telepon</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['telepon']); ?></td>
					</tr>
					<tr>
						<td>Alamat Email</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['email']); ?></td>
					</tr>
					<tr>
						<td>Telegram</td>
						<td>:</td>
						<td><?= $penduduk['telegram'] ?></td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['alamat']); ?></td>
					</tr>
					<tr>
						<td>Dusun</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['dusun']); ?></td>
					</tr>
					<tr>
						<td>RT/ RW</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['rt']); ?> / <?= $penduduk['rw']; ?></td>
					</tr>
					<tr>
						<td>Alamat Sebelumnya</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['alamat_sebelumnya']); ?></td>
					</tr>
					<tr>
						<th colspan="3" class="judul">Data Perkawinan</th>
					</tr>
					<tr>
						<td>Status Kawin</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['kawin']); ?></td>
					</tr>
					<?php if ($penduduk['status_kawin'] != 1) : ?>
						<tr>
							<td>Akta perkawinan</td>
							<td>:</td>
							<td><?= strtoupper($penduduk['akta_perkawinan']); ?></td>
						</tr>
						<tr>
							<td>Tanggal perkawinan</td>
							<td>:</td>
							<td><?= strtoupper($penduduk['tanggalperkawinan']); ?></td>
						</tr>
					<?php endif ?>
					<?php if ($penduduk['status_kawin'] != 1 && $penduduk['status_kawin'] != 2) : ?>
						<tr>
							<td>Akta perceraian</td>
							<td>:</td>
							<td><?= strtoupper($penduduk['akta_perceraian']); ?></td>
						</tr>
						<tr>
							<td>Tanggal perceraian</td>
							<td>:</td>
							<td><?= strtoupper($penduduk['tanggalperceraian']); ?></td>
						</tr>
					<?php endif ?>
					<tr>
						<th colspan="3" class="judul">Data Kesehatan</th>
					</tr>
					<tr>
						<td>Golongan Darah</td>
						<td>:</td>
						<td><?= $penduduk['golongan_darah']; ?></td>
					</tr>
					<tr>
						<td>Cacat</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['cacat']); ?></td>
					</tr>
					<tr>
						<td>Sakit Menahun</td>
						<td>:</td>
						<td><?= strtoupper($penduduk['sakit_menahun']); ?></td>
					</tr>
					<?php if ($penduduk['status_kawin'] == 2) : ?>
						<tr>
							<td>Akseptor KB</td>
							<td>:</td>
							<td><?= strtoupper($penduduk['cara_kb']); ?></td>
						</tr>
					<?php endif ?>
					<?php if ($penduduk['id_sex'] == 2) : ?>
						<tr>
							<td>Status Kehamilan</td>
							<td>:</td>
							<td><?= $penduduk['hamil'] == '1' ? 'HAMIL' : 'TIDAK HAMIL'; ?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td>Nama Asuransi</td>
						<td>:</td>
						<td><?= $penduduk['asuransi']; ?></td>
					</tr>
					<?php if (! empty($penduduk['id_asuransi']) && $penduduk['id_asuransi'] != '1') : ?>
						<tr>
							<td><?= ($penduduk['id_asuransi'] == '99') ? 'Nama/nomor Asuransi' : 'No Asuransi' ?></td>
							<td>:</td>
							<td><?= strtoupper($penduduk['no_asuransi']); ?></td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="box-body box-line">
		<h4><b>KEANGGOTAAN KELOMPOK</b></h4>
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-data datatable-polos">
				<thead>
					<tr>
						<th width="padat">No</th>
						<th width="80%">Nama Kelompok</th>
						<th width="20%">Kategori Kelompok</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($kelompok) :
					    foreach ($kelompok as $key => $data) : ?>
							<tr>
								<td><?= ($key + 1); ?></td>
								<td><?= $data['nama']; ?></td>
								<td><?= $data['kategori']; ?></td>
							</tr>
						<?php endforeach;
					else : ?>
						<tr>
							<td class="text-center" colspan="3">Data tidak tersedia</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
