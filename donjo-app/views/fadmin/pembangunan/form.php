<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Pembangunan
 *
 * donjo-app/views/pembangunan/fadmin/form.php,
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Pembangunan
			<small><?= ($main->id ? 'Ubah' : 'Tambah') ?> Data</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('admin_pembangunan') ?>"> Pembangunan</a></li>
			<li class="active"><?= ($main->id ? 'Ubah' : 'Tambah') ?> Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form action="<?= $form_action; ?>" method="post" id="validasi" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('admin_pembangunan') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Pembangunan</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label" style="text-align:left;">Nama Kegiatan</label>
								<input id="judul" name="judul" class="form-control input-sm required" value="<?= $main->judul ?>" type="text" maxlength="50" minlength="5" maxlength="100" placeholder="Nama Kegiatan Pembangunan" />
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
											<?php foreach (tahun(1999) as $value): ?>
												<option value="<?= $value ?>" <?= selected($value, $main->tahun_anggaran) ?> ><?= $value ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label" style="text-align:left;">Anggaran</label>
										<input class="form-control input-sm required bilangan" name="anggaran" id="anggaran" value="<?= $main->anggaran; ?>" type="text" placeholder="Anggaran" readonly/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label" for="sumber_biaya_pemerintah">Sumber Biaya Pemerintah</label>
										<input id="sumber_biaya_pemerintah" name="sumber_biaya_pemerintah" onkeyup="cek()" class="form-control input-sm required bilangan" maxlength="12" type="text" placeholder="Sumber Biaya Pemerintah" value="<?= $main->sumber_biaya_pemerintah; ?>"></input>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label" for="sumber_biaya_provinsi">Sumber Biaya Provinsi</label>
										<input id="sumber_biaya_provinsi" name="sumber_biaya_provinsi" onkeyup="cek()" class="form-control input-sm required bilangan" maxlength="12" type="text" placeholder="Sumber Biaya Provinsi" value="<?= $main->sumber_biaya_provinsi; ?>"></input>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label" for="sumber_biaya_kab_kota">Sumber Biaya Kab / Kota</label>
										<input id="sumber_biaya_kab_kota" name="sumber_biaya_kab_kota" class="form-control input-sm required bilangan" maxlength="12" onkeyup="cek()" type="text" placeholder="Sumber Biaya Kab / Kota" value="<?= $main->sumber_biaya_kab_kota; ?>"></input>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label" for="sumber_biaya_swadaya">Sumber Biaya Swadaya</label>
										<input id="sumber_biaya_swadaya" name="sumber_biaya_swadaya" class="form-control input-sm required bilangan" maxlength="12" type="text" onkeyup="cek()" placeholder="Sumber Biaya Swadaya" value="<?= $main->sumber_biaya_swadaya; ?>"></input>
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
									<div class="btn-group col-sm-6" data-toggle="buttons">
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
						</div>
						<div class="box-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
							<button type="submit" id="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Gambar Utama</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<center>
								<div class="form-group">
									<?php if (is_file(LOKASI_GALERI . $main->foto)) : ?>
										<img class="img-responsive" src="<?= base_url(LOKASI_GALERI . $main->foto); ?>" alt="Gambar Utama Pembangunan">
									<?php else : ?>
										<img class="img-responsive" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="Gambar Utama Pembangunan" />
									<?php endif; ?>
									<div class="input-group input-group-sm">
										<input type="hidden" name="old_foto" value="<?= $main->foto; ?>">
										<input type="text" class="form-control" id="file_path">
										<input type="file" class="hidden" id="file" name="foto">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i></button>
										</span>
										<span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
											<input type="checkbox" title="Centang Untuk Hapus Gambar" name="hapus_foto" value="hapus">
										</span>
									</div>
								</div>
							</center>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	var sb_pem = document.getElementById('sumber_biaya_pemerintah');
	var sb_prov = document.getElementById('sumber_biaya_provinsi');
	var sb_kab = document.getElementById('sumber_biaya_kab_kota');
	var sb_swad = document.getElementById('sumber_biaya_swadaya');
	var aggaran = document.getElementById('anggaran');

	function getSum(total, num) {
		return total + Math.round(num);
	}

	function cek() {
		const numbers = [sb_pem.value, sb_prov.value, sb_kab.value, sb_swad.value];
		var biaya = numbers.reduce(getSum, 0);
		document.getElementById('anggaran').value = biaya;
		var total_anggaran = aggaran.value;
	};

	$(document).ready(function() {
		$("form").submit(function(e) {
			const numbers = [sb_pem.value, sb_prov.value, sb_kab.value, sb_swad.value];
			var biaya = numbers.reduce(getSum, 0);
			var total_anggaran = aggaran.value;
			if (biaya > total_anggaran) {
				alert('Total rincian sumber biaya tidak boleh melebihi anggaran.');
				e.preventDefault(e);
			}
		});
	});

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
		pilih_lokasi(<?= (null === $main->id_lokasi && $main) ? 2 : 1 ?>);
	});
</script>