<?php
/**
 * File ini:
 *
 * Form anggota untuk modul Kelompok
 *
 * donjo-app/views/kelompok/anggota/form.php
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
	<section class="content-header">
		<h1>Data Anggota Kelompok</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kelompok'); ?>"> Daftar Kelompok</a></li>
			<li class="active">Data Anggota Kelompok</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
					<a href="<?= site_url("kelompok/anggota/$kelompok"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Anggota Kelompok</a>
					</div>
					<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
						<div class="col-md-3">
							<div class="box-body box-profile">
								<img class="profile-user-img img-responsive img-circle" src="<?= $pend['foto'] ? AmbilFoto($pend['foto']) : base_url() . 'assets/files/user_pict/kuser.png'; ?>" alt="Foto">
								<br/>
								<p class="text-muted text-center"><code>(Kosongkan jika tidak ingin mengubah foto)</code></p>
								<br/>
								<div class="input-group input-group-sm">
									<input type="text" class="form-control" id="file_path2" name="foto">
									<input type="file" class="hidden" id="file2" name="foto">
									<input type="hidden" name="old_foto" value="<?= $pend['foto']?>">
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-9">
							<div class="box-body">
								<div class="form-group">
									<label class="col-sm-3 control-label"  for="id_penduduk">Nama Anggota</label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2 required" <?= jecho($pend, true, 'disabled')?> id="id_penduduk" name="id_penduduk">
											<option value="">-- Silakan Masukan NIK / Nama --</option>
											<?php foreach ($list_penduduk as $data): ?>
												<option value="<?= $data['id']; ?>" <?= selected($data['id'], $pend['id_penduduk']); ?>>NIK :<?= $data['nik'] . " - " . $data['nama'] . " - " . $data['alamat']; ?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label  class="col-sm-3 control-label" for="no_anggota">Nomor Anggota</label>
									<div class="col-sm-5">
										<input  id="no_anggota" class="form-control input-sm number" type="text" placeholder="Nomor Anggota" name="no_anggota" value="<?=$pend['no_anggota']; ?>">
									</div>
								</div>
								<div class="form-group">
									<?php if (!empty($pend)): ?>
										<input type="hidden" name="jabatan_lama" value="<?= $pend['jabatan']?>">
									<?php endif; ?>
									<label class="col-sm-3 control-label" for="jabatan">Jabatan</label>
									<div class="col-sm-5">
										<select class="form-control input-sm required" id="jabatan" name="jabatan">
											<option option value="">-- Silakan Pilih Jabatan --</option>
											<?php foreach ($list_jabatan as $key => $value): ?>
												<option value="<?= $key?>"  <?= selected($key, $pend['jabatan']); ?> ><?= $value?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label  class="col-sm-3 control-label" for="no_sk_jabatan">SK Jabatan</label>
									<div class="col-sm-5">
										<input  id="no_sk_jabatan" class="form-control input-sm nomor_sk" type="text" placeholder="SK Jabatan" name="no_sk_jabatan" value="<?=$pend['no_sk_jabatan']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
									<div class="col-sm-5">
										<textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3"><?= $pend['keterangan']; ?></textarea>
								 	</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-xs-12">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>


