<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View modul Layanan Mandiri > Pesan > Buat Pesan
 *
 * donjo-app/views/layanan_mandiri/buat_pesan.php
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

<form id="validasi" action="<?= site_url("layanan-mandiri/surat/form/$permohonan[id]"); ?>" method="POST" enctype="multipart/form-data">
	<div class="box box-solid">
		<div class="box-header with-border bg-green">
			<h4 class="box-title">Surat</h4>
		</div>
		<div class="box-body box-line">
			<h4><b>LAYANAN PERMOHONAN SURAT</b></h4>
			<input type="hidden" id="id_permohonan" name="id_permohonan" value="<?= $permohonan['id']?>"/>
		</div>
		<div class="box-body box-line">
			<?php if ($permohonan): ?>
				<div class="alert alert-warning" role="alert">
					<span style="font-size: larger;">Lengkapi permohonan surat tanggal <?= $permohonan['updated_at']; ?></span>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label for="nama_surat" class="col-sm-3 control-label">Jenis Surat Yang Dimohon</label>
				<div class="col-sm-9">
					<select class="form-control select2 required" name="id_surat" id="id_surat">
						<option value=""> -- Pilih Jenis Surat -- </option>
						<?php foreach ($menu_surat_mandiri AS $data): ?>
							<option value="<?= $data['id']?>" <?= selected($data['id'], $permohonan['id_surat'])?>><?= $data['nama']?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="keterangan_tambahan" class="col-sm-3 control-label">Keterangan Tambahan</label>
				<div class="col-sm-9">
					<textarea class="form-control <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" name="keterangan" id="keterangan" placeholder="Ketik di sini untuk memberikan keterangan tambahan."><?= $permohonan['keterangan']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="no_hp_aktif" class="col-sm-3 control-label">No. HP aktif</label>
				<div class="col-sm-9">
					<input class="form-control bilangan_spasi required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" type="text" name="no_hp_aktif" id="no_hp_aktif" placeholder="Ketik No. HP" maxlength="14" value="<?= $permohonan['no_hp_aktif']; ?>" />
				</div>
			</div>
		</div>
	</div>

	<!-- Kelengkapan Dokumen Yang Dibutuhkan -->
	<div class="box box-default">
		<div class="ada_syarat" style="display: none">
			<div class="box-header with-border">
				<h4><b>DOKUMEN / KELENGKAPAN PENDUDUK YANG DIBUTUHKAN</b></h4>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-data" id="syarat_surat">
						<thead>
							<tr>
								<th class="padat">No</center></th>
								<th>Syarat</th>
								<th class="padat">Dokumen Melengkapi Syarat</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<button type="reset" class="btn btn-social btn-sm btn-danger"><i class="fa fa-times"></i> Batal</button>
			<button type="submit" class="btn btn-social btn-primary btn-sm pull-right" id="isi_form"><i class="fa fa-sign-in"></i>Isi Form</button>
		</div>
	</div>
</form>

<!-- Kelengkapan Dokumen Yang Dimiliki -->
<div class="box box-default ada_syarat" style="display: none">
	<div class="box-header with-border">
		<h4><b>DOKUMEN / KELENGKAPAN PENDUDUK YANG TERSEDIA</b></h4>
	</div>
	<div class="box-body">
		<button type="button" title="Tambah Dokumen" data-remote="false" data-toggle="modal" data-target="#modal" data-title="Tambah Dokumen" class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="tambah_dokumen"><i class='fa fa-plus'></i>Tambah Dokumen</button>
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-data" id="dokumen">
				<thead>
					<tr>
						<th class="padat">No</th>
						<th class="aksi">Aksi</th>
						<th>Judul Dokumen</th>
						<th class="padat">Jenis Dokumen</th>
						<th width="20%" nowrap>Tanggal Upload</th>
					</tr>
				</thead>
				<tbody id="list_dokumen">
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade in" id="dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header btn-danger">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i> &nbsp;Peringatan</h4>
			</div>
			<div class="modal-body">
				<p id="kata_peringatan"></p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Tambah dokumen</h4>
			</div>
			<form id="unggah_dokumen" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<label for="nama_dokumen">Nama Dokumen</label>
						<input id="nama_dokumen" name="nama" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Nama Dokumen"/>
						<input type="text" class="hidden" name="id" id="id_dokumen" value=""/>
					</div>
					<div class="form-group">
						<label for="nama_dokumen">Jenis Dokumen</label>
						<select class="form-control input-sm required" name="id_syarat" id="id_syarat">
							<option value=""> -- Pilih Jenis Dokumen -- </option>
							<?php foreach ($menu_dokumen_mandiri AS $data): ?>
								<option value="<?= $data['ref_syarat_id']?>" ><?= $data['ref_syarat_nama']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="file" >Pilih File:</label>
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" id="file_path" name="satuan">
							<input type="file" class="hidden" id="file" name="satuan">
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-sm" id="file_browser"><i class="fa fa-search"></i> Browse</button>
							</span>
						</div>
						<span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal <strong><?= max_upload() ?> MB</strong>.</code></span>
					</div>
					</hr>
					<?php if ( ! empty($kk)): ?>
						<p><strong>Centang jika dokumen yang diupload berlaku juga untuk anggota keluarga di bawah ini. </strong></p>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-data">
								<thead>
									<tr>
										<th>#</th>
										<th>NIK</th>
										<th>Nama</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($kk as $item): ?>
										<?php if ($item['nik'] != $this->is_login->nik): ?>
											<tr>
												<td class="padat"><input class='anggota_kk' id="anggota_<?=$item['id']?>" type='checkbox' name='anggota_kk[]' value="<?=$item['id']?>"></td>
												<td><?=$item['nik']?></td>
												<td><?=$item['nama']?></td>
											</tr>
										<?php endif; ?>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php endif ?>
				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Tutup</button>
					<button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type='text/javascript'>
	function cek_perhatian(elem) {
		if ($(elem).val() == '-1') {
			$(elem).next('.perhatian').show();
		} else {
			$(elem).next('.perhatian').hide();
		}
	}
</script>
