<?php
/**
 * File ini:
 *
 * Form kelompok di modul Kelompok
 *
 * donjo-app/views/kelompok/form.php
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
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>

<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Master Kelompok
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fas fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url('kelompok')?>"> Daftar Kelompok</a></li>
						<li class="breadcrumb-item active">Master Kelompok</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="card card-outline card-info">
			<div class="card-header with-border">
				<a href="<?= site_url()?>kelompok" class="btn btn-flat btn-info btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Daftar Kelompok</a>
			</div>
			<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="card-body">
					<div class="row mb-2">
						<label  class="col-sm-3 control-label" for="nama">Nama Kelompok</label>
						<div class="col-sm-7">
							<input  id="nama" class="form-control form-control-sm nama_terbatas required" type="text" placeholder="Nama Kelompok" name="nama" value="<?= $kelompok['nama']?>">
						</div>
					</div>
					<div class="row mb-2">
						<label  class="col-sm-3 control-label" for="kode">Kode Kelompok</label>
						<div class="col-sm-7">
							<input  id="kode" class="form-control form-control-sm nomor_sk" type="text" placeholder="Kode Kelompok" name="kode" value="<?= $kelompok['kode']?>">
						</div>
					</div>
					<div class="row mb-2">
						<label  class="col-sm-3 control-label" for="id_master">Kategori Kelompok</label>
						<div class="col-sm-7">
							<select class="form-control form-control-sm select2 required" id="id_master" name="id_master">
								<option value="">-- Silakan Masukkan Kategori Kelompok--</option>
								<?php foreach ($list_master AS $data): ?>
									<option value="<?= $data['id']; ?>" <?= selected($kelompok['id_master'], $data['id']); ?>><?= $data['kelompok']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="row mb-2">
						<label  class="col-sm-3 control-label" for="id_ketua">Ketua Kelompok</label>
						<div class="col-sm-7">
							<select class="form-control form-control-sm select2 required" id="id_ketua" name="id_ketua">
								<option value="">-- Silakan Masukkan NIK / Nama--</option>
								<?php foreach ($list_penduduk as $data): ?>
									<option value="<?= $data['id']; ?>" <?= selected($data['id'], $kelompok['id_ketua']); ?>>NIK :<?= $data['nik'] . " - " . $data['nama'] . " - " . $data['alamat']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="row mb-2">
						<label  class="col-sm-3 control-label" for="keterangan">Deskripsi Kelompok</label>
						<div class="col-sm-7">
							<textarea name="keterangan" class="form-control form-control-sm" placeholder="Deskripsi Kelompok"  rows="3"><?= $kelompok['keterangan']?></textarea>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-flat btn-danger btn-xs"><i class="fa fa-times"></i> Batal</button>
						<button type="submit" class="btn btn-flat btn-info btn-xs pull-right"><i class="fa fa-check"></i> Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
