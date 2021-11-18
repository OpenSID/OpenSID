<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa > Administrasi Pembangunan > Buku Tanah Desa
 *
 * donjo-app/views/bumindes/pembangunan/tanah_di_desa/form_tanah_di_desa.php,
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

<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= $form_action ?>">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
					<a href="<?= site_url() ?>bumindes_tanah_desa"
						class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
							class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Buku Tanah di Desa</a>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" id="id" name="id" value="<?= $main->id; ?>">
							<input type="hidden" id="id_penduduk" name="id_penduduk" value="<?= $main->id_penduduk; ?>">
							<div class="form-group" id='pilihan_pemilik'>
								<label for="jenis_pemilik" class="col-sm-3 control-label">Jenis Pemilik</label>
								<div class="btn-group col-sm-8 kiri" data-toggle="buttons">
									<label class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label">
										<input type="radio" name="jenis_pemilik" class="form-check-input" value="1"
											autocomplete="off"
											<?php selected((empty($main) || $main->jenis_pemilik == 1), true, true)?>
											onchange="pilih_pemilik(this.value);">Warga Desa
									</label>
									<label class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label">
										<input type="radio" name="jenis_pemilik" class="form-check-input" value="2"
											autocomplete="off"
											<?php selected(($main->jenis_pemilik == 2), true, true)?>
											onchange="pilih_pemilik(this.value);">Warga Luar Desa
									</label>
								</div>
							</div>
							<div class="form-group" id="pilihan_penduduk">
								<label class="col-sm-3 control-label">Cari Penduduk</label>
								<div class="col-sm-8">
									<select class="form-control input-sm select2" style="width: 100%;" id="penduduk"
										name="penduduk">
										<option value="">-- Silakan Masukan Nama / NIK --</option>
										<?php foreach ($penduduk as $item): ?>
										<option value="<?= $item['id']?>" <?php selected($item['id'], $item['nama']) ?>><?= $item['nama'] . ' [ NIK ' . $item['nik'] . ' ]'?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="form-group" id="nama_penduduk">
								<label class="col-sm-3 control-label" style="text-align:left;" for="pemilik_asal">Nama
									Perorangan / Badan Hukum</label>
								<div class="col-sm-8">
									<input class="form-control input-sm nama required" type="text" placeholder="Pemilik" value="<?= $main->nama_pemilik_asal ?: $main->nama ?>" name="pemilik_asal" id="pemilik_asal" />
								</div>
							</div>
							<div class="form-group" id="nik_penduduk">
								<label class="col-sm-3 control-label" style="text-align:left;" for="nik">NIK Penduduk</label>
								<div class="col-sm-8">
									<input class="form-control input-sm nik required" type="text" placeholder="NIK Penduduk" value="<?= $main->nik_penduduk ?: $main->nik ?>" name="nik" id="nik" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas
									Tanah Total</label>
								<div class="col-sm-4">
									<div class="input-group">
										<input type="number" min="0" class="form-control input-sm number disabled required" value="<?= $main->luas ?: 0 ?>" id="luas" name="luas" />
										<span class="input-group-addon input-sm "
											id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Status Hak Tanah :</strong></label>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group">
									<label class="control-label" style="text-align:right;" for="hak_milik">Sertifikat</label>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="hak_milik">Hak Milik</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hak_milik ?: 0 ?>" id="hak_milik" name="hak_milik" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="hak_guna_bangunan">Hak Guna Bangunan</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hak_guna_bangunan ?: 0 ?>" id="hak_guna_bangunan" name="hak_guna_bangunan" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="hak_pakai">Hak Pakai</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hak_pakai ?: 0 ?>" id="hak_pakai" name="hak_pakai" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="hak_guna_usaha">Hak Guna Usaha</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hak_guna_usaha ?: 0 ?>" id="hak_guna_usaha" name="hak_guna_usaha" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="hak_pengelolaan">Hak Pengelolaan</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hak_pengelolaan ?: 0 ?>" id="hak_pengelolaan" name="hak_pengelolaan" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group">
									<label class="control-label" style="text-align:right;" for="hak_milik">Belum Sertifikat</label>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tanah_negara">Hak Milik Adat</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hak_milik_adat ?: 0 ?>" id="hak_milik_adat" name="hak_milik_adat" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tanah_negara">Tanah Negara</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->tanah_negara ?: 0 ?>" id="tanah_negara" name="tanah_negara" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-4'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="hak_verponding">Hak Verponding Indonesia (Milik Pribumi)</label>
									<div class="col-sm-9">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hak_verponding ?: 0 ?>" id="hak_verponding" name="hak_verponding" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Penggunaan Tanah :</strong></label>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group">
									<label class="control-label" style="text-align:right;" for="hak_milik">Non Pertanian</label>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="perumahan">Perumahan</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->perumahan ?: 0 ?>" id="perumahan" name="perumahan" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="perdagangan_jasa">Perdagangan dan Jasa</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->perdagangan_jasa ?: 0 ?>" id="perdagangan_jasa" name="perdagangan_jasa" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="perkantoran">Perkantoran</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->perkantoran ?: 0 ?>" id="perkantoran" name="perkantoran" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="industri">Industri</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->industri ?: 0 ?>" id="industri" name="industri" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="fasilitas_umum">Fasilitas Umum</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->fasilitas_umum ?: 0 ?>" id="fasilitas_umum" name="fasilitas_umum" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group">
									<label class="control-label" style="text-align:right;" for="hak_milik">Pertanian</label>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="sawah">Sawah</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->sawah ?: 0 ?>" id="sawah" name="sawah" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tegalan">Tegalan</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->tegalan ?: 0 ?>" id="tegalan" name="tegalan" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="perkebunan">Perkebunan</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->perkebunan ?: 0 ?>" id="perkebunan" name="perkebunan" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="peternakan_perikanan">Perternakan / Perikanan</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->peternakan_perikanan ?: 0 ?>" id="peternakan_perikanan" name="peternakan_perikanan" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="hutan_belukar">Hutan Belukar</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hutan_belukar ?: 0 ?>" id="hutan_belukar" name="hutan_belukar" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="hutan_lebat_lindung">Hutan Lebat / Lindung</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->hutan_lebat_lindung ?: 0 ?>" id="hutan_lebat_lindung" name="hutan_lebat_lindung" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tanah_kosong">Tanah Kosong</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->tanah_kosong ?: 0 ?>" id="tanah_kosong" name="tanah_kosong" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="lain_lain">Lain - lain</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input type="text" min="0" class="form-control input-sm number required" value="<?= $main->lain ?: 0 ?>" id="lain_lain" name="lain_lain" />
											<span class="input-group-addon input-sm "
												id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Catatan :</strong></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;"
									for="mutasi">Mutasi</label>
								<div class="col-sm-8">
									<textarea rows="5" class="form-control input-sm nomor_sk" name="mutasi" id="mutasi"
										placeholder="Mutasi"><?= $main->mutasi; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;"
									for="keterangan">Keterangan</label>
								<div class="col-sm-8">
									<textarea rows="5" class="form-control input-sm nomor_sk" name="keterangan"
										id="keterangan" placeholder="Keterangan"><?= $main->keterangan; ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="form_footer" class="box-footer">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i
								class="fa fa-times"></i> Batal</button>
						<button type="button" onclick="submit_form()" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i
								class="fa fa-check"></i> Simpan</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$(document).ready(function ()
	{
		var view = <?=$view_mark?>;
		if (1 == view)
		{
			$("#penduduk").attr("disabled", true);
			$("#nik").attr("disabled", true);
			$("#pemilik_asal").attr("disabled", true);
			$("#luas").attr("disabled", true);
			$('#hak_milik').attr("disabled", true);
			$('#hak_guna_bangunan').attr("disabled", true);
			$('#hak_pakai').attr("disabled", true);
			$('#hak_guna_usaha').attr("disabled", true);
			$('#hak_pengelolaan').attr("disabled", true);
			$('#hak_milik_adat').attr("disabled", true);
			$('#hak_verponding').attr("disabled", true);
			$('#tanah_negara').attr("disabled", true);
			$('#perumahan').attr("disabled", true);
			$('#perdagangan_jasa').attr("disabled", true);
			$('#perkantoran').attr("disabled", true);
			$('#industri').attr("disabled", true);
			$('#fasilitas_umum').attr("disabled", true);
			$('#sawah').attr("disabled", true);
			$('#tegalan').attr("disabled", true);
			$('#perkebunan').attr("disabled", true);
			$('#peternakan_perikanan').attr("disabled", true);
			$('#hutan_belukar').attr("disabled", true);
			$('#hutan_lebat_lindung').attr("disabled", true);
			$('#tanah_kosong').attr("disabled", true);
			$("#hak_tanah").attr("disabled", true);
			$("#penggunaan_tanah").attr("disabled", true);
			$("#lain_lain").attr("disabled", true);
			$("#mutasi").attr("disabled", true);
			$("#keterangan").attr("disabled", true);
			$('#nik_penduduk').hide();
			$('#pilihan_penduduk').hide();
			$('#pilihan_pemilik').hide();
			$('#form_footer').hide();
		}
		else
		{
			pilih_pemilik((<?=$main->jenis_pemilik ?: 1?> ))
		}
	});

	function pilih_pemilik(pilih)
	{
		$('#jenis_pemilik').val(pilih);
		if (pilih == 1)
		{
			$('#penduduk').val($('#id_penduduk').val());
			$('#penduduk').addClass('required');
			$('#pemilik_asal').val('');
			$('#nik').val('');
			$('#pemilik_asal').removeClass('required');
			$('#nik').removeClass('required');
			$('#nik').removeClass('nik');
			$('#nama_penduduk').hide();
			$('#nik_penduduk').hide();
			$('#pilihan_penduduk').show();
		}
		else
		{
			$('#penduduk').val('');
			$('#pemilik_asal').addClass('required');
			$('#nik').addClass('required');
			$('#nik').addClass('nik');
			$('#penduduk').removeClass('required');
			$('#nama_penduduk').show();
			$('#nik_penduduk').show();
			$('#pilihan_penduduk').hide();
		}
	}

	function luas_status_hak_tanah()
	{
		var res = 0;
		res = parseFloat($('#hak_milik').val())
			+parseFloat($('#hak_guna_bangunan').val())
			+parseFloat($('#hak_pakai').val())
			+parseFloat($('#hak_guna_usaha').val())
			+parseFloat($('#hak_pengelolaan').val())
			+parseFloat($('#hak_milik_adat').val())
			+parseFloat($('#hak_verponding').val())
			+parseFloat($('#tanah_negara').val());
		$('#luas').val(res);
		return res;
	}

	function luas_penggunaan_tanah()
	{
		var res = 0;
		res = parseFloat($('#perumahan').val())
			+parseFloat($('#perdagangan_jasa').val())
			+parseFloat($('#perkantoran').val())
			+parseFloat($('#industri').val())
			+parseFloat($('#fasilitas_umum').val())
			+parseFloat($('#sawah').val())
			+parseFloat($('#tegalan').val())
			+parseFloat($('#perkebunan').val())
			+parseFloat($('#peternakan_perikanan').val())
			+parseFloat($('#hutan_belukar').val())
			+parseFloat($('#hutan_lebat_lindung').val())
			+parseFloat($('#tanah_kosong').val())
			+parseFloat($('#lain_lain').val());
		return res;
	}

	function submit_form()
	{
		var luas_status_hak = luas_status_hak_tanah();
		var luas_penggunaan = luas_penggunaan_tanah();
		if (luas_status_hak == luas_penggunaan) {
			$("#validasi").submit();
		}
		else {
			notify = 'error';
			notify_msg = 'Luas Status Hak Tanah = ' + luas_status_hak + ' dan Luas Penggunanan Tanah = ' + luas_penggunaan + ' tidak sesuai';
			notification(notify, notify_msg);
		}
	}
</script>