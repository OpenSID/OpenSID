<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View daftar anggota kelompok di modul Kelompok
 *
 * donjo-app/views/kelompok/anggota/table.php,
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Kelompok <?= ucwords($kelompok['nama']); ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kelompok'); ?>"> Daftar Kelompok</a></li>
			<li class="active"><?= ucwords($kelompok['nama']); ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<div class="btn-group btn-group-vertical">
								<a class="btn btn-social btn-flat btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah Anggota Kelompok</a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="<?= site_url("kelompok/aksi/1/".$kelompok['id']); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Tambah Satu Peserta Baru "><i class="fa fa-plus"></i> Tambah Satu Anggota Kelompok</a>
									</li>
									<li>
										<a href="<?= site_url("kelompok/aksi/2/".$kelompok['id']); ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Tambah Beberapa Peserta Baru"><i class="fa fa-plus"></i> Tambah Beberapa Anggota Kelompok</a>
									</li>
								</ul>
							</div>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("kelompok/delete_anggota_all/$kelompok[id]"); ?>')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<a href="<?= site_url("kelompok/dialog_anggota/cetak/$kelompok[id]"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Daftar Anggota Kelompok <?= $kelompok['nama']; ?>"><i class="fa fa-print"></i> Cetak</a>
							<a href="<?= site_url("kelompok/dialog_anggota/unduh/$kelompok[id]"); ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Daftar Anggota Kelompok <?= $kelompok['nama']; ?>"><i class="fa fa-download"></i> Unduh</a>
							<a href="<?= site_url("kelompok"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Daftar Kelompok</a>
						</div>
						<div class="box-body">
							<h5><b>Rincian Kelompok</b></h5>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover tabel-rincian">
									<tbody>
										<tr>
											<td width="20%">Kode Kelompok</td>
											<td width="1">:</td>
											<td><?= strtoupper($kelompok['kode']); ?></td>
										</tr>
										<tr>
											<td>Nama Kelompok</td>
											<td>:</td>
											<td><?= strtoupper($kelompok['nama']); ?></td>
										</tr>
										<tr>
											<td>Ketua Kelompok</td>
											<td>:</td>
											<td><?= strtoupper($kelompok['nama_ketua']); ?></td>
										</tr>
										<tr>
											<td>Kategori Kelompok</td>
											<td>:</td>
											<td><?= strtoupper($kelompok['kategori']); ?></td>
										</tr>
										<tr>
											<td>Keterangan</td>
											<td>:</td>
											<td><?= $kelompok['keterangan']; ?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<h5><b>Anggota Kelompok</b></h5>
							<div class="table-responsive">
								<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
									<thead class="bg-gray disabled color-palette">
										<tr>
											<th><input type="checkbox" id="checkall"/></th>
											<th>No</th>
											<th>Aksi</th>
											<th>Foto</th>
											<th>No. Anggota</th>
											<th width="5%">Jabatan</th>
											<th width="10%">SK Jabatan</th>
											<th width="10%">NIK</th>
											<th width="10%">Nama</th>
											<th width="10%">Tempat / Tanggal Lahir</th>
											<th>Umur (Tahun)</th>
											<th>Jenis Kelamin</th>
											<th width="30%">Alamat</th>
											<th width="20%">Keterangan</th>
										</tr>
									</thead>
									<tbody>
										<?php if ($main): ?>
											<?php foreach ($main as $key => $data): ?>
												<tr>
													<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['id']; ?>" /></td>
													<td class="padat"><?= ($key + 1) ?></td>
													<td class="padat">
														<a href="<?= site_url("kelompok/form_anggota/$kelompok[id]/$data[id_penduduk]")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Anggota" ><i class="fa fa-edit"></i></a>
														<a href="#" data-href="<?= site_url("kelompok/delete_anggota/$kelompok[id]/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</td>
													<td class="text-center">
														<div class="user-panel">
															<div class="image2">
															<img src="<?= $data['foto'] ? AmbilFoto($data['foto']) : base_url() . 'assets/files/user_pict/kuser.png'; ?>" class="img-circle" alt="User Image"/>
															</div>
														</div>
													</td>
													<td class="padat"><?= $data['no_anggota']?></td>
													<td><?= $this->referensi_model->list_ref(JABATAN_KELOMPOK)[$data['jabatan']]?></td>
													<td><?= $data['no_sk_jabatan']?>
													<td><?= $data['nik']; ?></td>
													<td><?= $data['nama']; ?></td>
													<td><?= strtoupper($data['tempatlahir'] . ' / ' . tgl_indo($data['tanggallahir'])); ?></td>
													<td class="padat"><?= $data['umur']; ?></td>
													<td><?= $data['sex']; ?></td>
													<td><?= $data['alamat']; ?></td>
													<td><?= $data['keterangan']; ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td class="text-center" colspan="11">Data Tidak Tersedia</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>

