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
	<section class="content">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('global/ambil_foto', ['id_sex' => $pend['id_sex'], 'foto' => $pend['foto'], ]); ?>
				</div>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?= site_url("kelompok/anggota/$kelompok"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Anggota Kelompok</a>
						</div>
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
								<?php if (! empty($pend)): ?>
									<input type="hidden" name="jabatan_lama" value="<?= $pend['jabatan']?>">
								<?php endif; ?>
								<label class="col-sm-3 control-label" for="jabatan">Jabatan</label>
								<div class="col-sm-5">
									<select class="form-control input-sm select2-tags required" id="jabatan" name="jabatan">
										<option option value="">-- Silakan Pilih Jabatan --</option>
										<?php foreach ($list_jabatan1 as $key => $value): ?>
											<option value="<?= $key; ?>"  <?= selected($key, $pend['jabatan']); ?> ><?= $value; ?></option>
										<?php endforeach;?>
										<?php foreach ($list_jabatan2 as $value): ?>
											<option value="<?= $value['jabatan']; ?>"  <?= selected($value['jabatan'], $pend['jabatan']); ?> ><?= $value['jabatan']; ?></option>
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
									<textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="5"><?= $pend['keterangan']; ?></textarea>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
							<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

<?php $this->load->view('global/capture'); ?>
