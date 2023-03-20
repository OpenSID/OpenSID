<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk modul Pembangunan
 *
 * donjo-app/views/pembangunan/form.php,
 */

/**
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
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<?php $this->load->view('global/validasi_form'); ?>
<form action="<?= $form_action; ?>" method="post" id="validasi" enctype="multipart/form-data">
	<div class="modal-body">
		<div class="form-group">
			<label class="control-label" style="text-align:left;">Nama Kegiatan</label>
			<input maxlength="50" class="form-control input-sm required" name="judul" id="judul" value="<?= $main->judul ?>" type="text" placeholder="Nama Kegiatan Pembangunan" />
		</div>
		<div class="form-group">
			<label class="control-label" style="text-align:left;">Volume</label>
			<input maxlength="50" class="form-control input-sm required" name="volume" id="volume" value="<?= $main->volume ?>" type="text" placeholder="Volume Pembangunan" />
		</div>
		<div class="form-group">
			<label class="control-label" style="text-align:left;">Waktu</label>
			<input maxlength="50" class="form-control number input-sm required" name="waktu" id="waktu" value="<?= $main->waktu ?>" type="text" placeholder="Lamanya pembangunan (bulan)" />
		</div>
		<div class="form-group">
			<label class="control-label" for="sumber_dana">Sumber Dana</label>
			<select class="form-control input-sm select2" id="sumber_dana" name="sumber_dana" style="width:100%;">
				<?php foreach ($sumber_dana as $value) : ?>
					<option <?= $value === $main->sumber_dana ? 'selected' : '' ?> value="<?= $value ?>"><?= $value ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="tahun_anggaran">Tahun Anggaran</label>
					<select class="form-control input-sm select2" id="tahun_anggaran" name="tahun_anggaran" style="width:100%;">
						<?php for ($i = date('Y'); $i >= 1999; $i--) : ?>
							<option value="<?= $i ?>" <?= selected($id, $main->tahun_anggaran) ?>><?= $i ?></option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" style="text-align:left;">Anggaran</label>
					<input class="form-control input-sm required" name="anggaran" id="anggaran" value="<?= $main->anggaran ?>" type="number" placeholder="Anggaran" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="sumber_biaya_pemerintah">Sumber Biaya Pemerintah</label>
					<input id="sumber_biaya_pemerintah" name="sumber_biaya_pemerintah" class="form-control input-sm required" type="number" placeholder="Sumber Biaya Pemerintah" minlength="1" maxlength="100" value="<?= $main->sumber_biaya_pemerintah; ?>" ></input>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="sumber_biaya_provinsi">Sumber Biaya Provinsi</label>
					<input id="sumber_biaya_provinsi" name="sumber_biaya_provinsi" class="form-control input-sm required" type="number" placeholder="Sumber Biaya Provinsi" minlength="1" maxlength="100" value="<?= $main->sumber_biaya_provinsi; ?>" ></input>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="sumber_biaya_kab_kota">Sumber Biaya Kab / Kota</label>
					<input id="sumber_biaya_kab_kota" name="sumber_biaya_kab_kota" class="form-control input-sm required" type="number" placeholder="Sumber Biaya Kab / Kota" minlength="1" maxlength="100" value="<?= $main->sumber_biaya_kab_kota; ?>" ></input>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="sumber_biaya_swadaya">Sumber Biaya Swadaya</label>
					<input id="sumber_biaya_swadaya" name="sumber_biaya_swadaya" class="form-control input-sm required" type="number" placeholder="Sumber Biaya Swadaya" minlength="1" maxlength="100" value="<?= $main->sumber_biaya_swadaya; ?>" ></input>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" style="text-align:left;">Sifat Proyek</label>
			<select class="form-control input-sm select2 required" id="sifat_proyek" name="sifat_proyek">
				<option value="">-- Pilih Sifat Proyek --</option>
				<option value="BARU" <?php selected($main->sifat_proyek, 'BARU') ?>>BARU</option>
				<option value="LANJUTAN" <?php selected($main->sifat_proyek, 'LANJUTAN') ?>>LANJUTAN</option>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label" style="text-align:left;">Pelaksana Kegiatan</label>
			<input maxlength="50" class="form-control input-sm required" name="pelaksana_kegiatan" id="pelaksana_kegiatan" value="<?= $main->pelaksana_kegiatan ?>" type="text" placeholder="Pelaksana Kegiatan Pembangunan" />
		</div>
		<div class="form-group">
			<label for="jenis_lokasi" class="control-label">Lokasi Pembangunan</label>
			<div class="row">
				<div class="btn-group col-sm-12" data-toggle="buttons">
					<label class="btn btn-info btn-flat btn-sm form-check-label col-sm-6 <?= $main->lokasi ? null : 'active' ?>">
						<input type="radio" name="jenis_lokasi" class="form-check-input" value="1" autocomplete="off" onchange="pilih_lokasi(this.value);"> Pilih Lokasi
					</label>
					<label class="btn btn-info btn-flat btn-sm form-check-label col-sm-6 <?= $main->lokasi ? 'active' : null ?>">
						<input type="radio" name="jenis_lokasi" class="form-check-input" value="2" autocomplete="off" onchange="pilih_lokasi(this.value);"> Tulis Manual
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label"></label>
				<div id="pilih">
						<select class="form-control input-sm select2 required" id="id_lokasi" name="id_lokasi">
							<option value="">-- Pilih Lokasi Pembangunan --</option>
							<?php foreach ($list_lokasi as $key => $item) : ?>
								<option value="<?= $item['id'] ?>" <?= selected($item['id'], $main->id_lokasi) ?>><?= strtoupper($item['dusun']) ?> <?= empty($item['rw']) ? '' : " - RW  {$item['rw']}" ?> <?= empty($item['rt']) ? '' : " / RT  {$item['rt']}" ?></option>
							<?php endforeach; ?>
						</select>
				</div>
				<div id="manual">
						<textarea id="lokasi" class="form-control input-sm required" type="text" placeholder="Lokasi" name="lokasi" rows="3"><?= $main->lokasi ?></textarea>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label" for="manfaat">Manfaat</label>
			<textarea id="manfaat" name="manfaat" class="form-control input-sm required" name="manfaat" placeholder="Manfaat" rows="3"><?= $main->manfaat; ?></textarea>
		</div>
		<div class="form-group">
			<label class="control-label" for="keterangan">Keterangan</label>
				<textarea id="keterangan" class="form-control input-sm required" name="keterangan" placeholder="Keterangan" rows="3"><?= $main->keterangan ?></textarea>
		</div>
		<?php if ($main->foto) : ?>
			<div class="form-group">
				<label class="control-label col-sm-4" for="nama"></label>
				<div class="col-sm-7">
					<input type="hidden" name="old_foto" value="<?= $main->foto ?>">
					<img class="attachment-img img-responsive img-circle" src="<?= base_url() . LOKASI_GALERI . $main->foto ?>" alt="Gambar Dokumentasi" width="200" height="200">
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group">
			<label class="control-label" for="upload">Unggah Gambar Utama</label>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control " id="file_path" name="foto">
					<input id="file" type="file" class="hidden" name="foto">
					<span class="input-group-btn">
						<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
					</span>
				</div>
				<span class="help-block"><code>(Kosongkan jika tidak ingin mengubah gambar)</code></span>
			</div>
		</div>
		<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left" id="batal"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm confirm"><i class="fa fa-check"></i> Simpan</button>
	</div>
</form>
<script>
	function pilih_lokasi(pilih) {
		if (pilih == 1) {
			$('#lokasi').val(null);
			$('#lokasi').removeClass('required');
			$("#manual").hide();
			$("#pilih").show();
			$('#id_lokasi').addClass('required');
		} else {
			$('#id_lokasi').val(null);
			$('#id_lokasi').trigger('change', true);
			$('#id_lokasi').removeClass('required');
			$("#manual").show();
			$('#lokasi').addClass('required');
			$("#pilih").hide();
		}
	}

	$(document).ready(function() {
		pilih_lokasi(<?= null === $main->id_lokasi ? 2 : 1 ?>);
	});
</script>
