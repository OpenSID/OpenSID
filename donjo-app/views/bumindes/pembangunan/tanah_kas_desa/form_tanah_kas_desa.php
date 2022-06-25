<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa > Administrasi Pembangunan > Buku Tanah Kas Desa
 *
 * donjo-app/views/bumindes/pembangunan/tanah_kas_desa/form_tanah_kas_desa.php,
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

<form class="form-horizontal" id="validasi" name="form_tanah_kas" method="post" action="<?= $form_action ?>">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
					<a href="<?= site_url() ?>bumindes_tanah_kas_desa"
						class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
						<i class="fa fa-arrow-circle-left"></i> Kembali Ke Buku Tanah Kas Desa</a>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" id="id" name="id" value="<?= $main->id; ?>">
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="pemilik_asal">Asal Tanah Kas
									Desa</label>
								<div class="col-sm-4">
									<select name="pemilik_asal" id="pemilik_asal" class="form-control input-sm required"
										onchange="pilih_asal_tanah(this.value)">
										<option value>-- Pilih Asal Tanah--</option>
										<?php foreach ($list_asal_tanah as $item): ?>
											<option value="<?= $item['id']?>" <?php selected($item['id'], $main->nama_pemilik_asal) ?>><?= $item['nama']?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Nomor Sertifikat Buku
									Letter C / Persil</label>
								<div class="col-sm-4">
									<input type="text" min="0" class="form-control input-sm number required" id="letter_c_persil"
										name="letter_c_persil" value="<?= $main->letter_c; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Kelas</label>
								<div class="col-sm-4">
									<select name="kelas" id="kelas" class="form-control input-sm required" placeholder="Kelas">
										<option value>-- Pilih Tipe Tanah--</option>
										<?php foreach ($persil as $item): ?>
											<option value="<?= $item['id']?>" <?php selected($item['id'], $main->kelas) ?>><?= $item['kode'] . ' ' . $item['ndesc']?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label required" style="text-align:left;" for="tanggal_sertifikat">Tanggal
									Perolehan</label>
								<div class="col-sm-4">
									<input maxlength="50" class="form-control input-sm required" name="tanggal_perolehan"
										id="tanggal_perolehan" type="date" placeholder="Tanggal Sertifikat"
										value="<?= $main->tanggal_perolehan; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah Total</label>
								<div class="col-sm-4">
									<div class="input-group">
										<input min="0" class="form-control input-sm number required"
											value="<?= $main->luas ?: 0 ?>" id="luas" name="luas" />
										<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Perolehan TKD :</strong></label>
								</div>
							</div>
							<div class='col-sm-12' id="view_label_asal_tanah">
								<p class="text-center">Pilih Asal Tanah</p>
							</div>
							<div class='col-sm-3' id="view_asli_milik_desa">
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="asli_milik_desa">Asli Milik
										Desa</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_perolehan()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->asli_milik_desa ?: 0 ?>" id="asli_milik_desa"
												name="asli_milik_desa" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3' id="view_pemerintah">
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="pemerintah">Bantuan
										Pemerintah</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_perolehan()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->pemerintah ?: 0 ?>" id="pemerintah"
												name="pemerintah" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3' id="view_provinsi">
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="provinsi">Bantuan
										Provinsi</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_perolehan()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->provinsi ?: 0 ?>" id="provinsi" name="provinsi" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3' id="view_kabupaten_kota">
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="kabupaten_kota">Bantuan Kabupatan
										/ Kota</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_perolehan()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->kabupaten_kota ?: 0 ?>" id="kabupaten_kota"
												name="kabupaten_kota" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3' id="view_lain_lain">
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="lain_lain">Lain - lain</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_perolehan()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->lain_lain ?: 0 ?>" id="lain_lain" name="lain_lain" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Jenis TKD :</strong></label>
								</div>
							</div>
							<div class='col-sm-2'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="sawah">Sawah</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_jenis_TKD()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->sawah ?: 0 ?>" id="sawah" name="sawah" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-2'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tegal">Tegal</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_jenis_TKD()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->tegal ?: 0 ?>" id="tegal" name="tegal" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-2'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="kebun">Kebun</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_jenis_TKD()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->kebun ?: 0 ?>" id="kebun" name="kebun" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-2'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tambak_kolam">Tambak /
										Kolam</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_jenis_TKD()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->tambak_kolam ?: 0 ?>" id="tambak_kolam"
												name="tambak_kolam" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-2'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tanah_kering_darat">Tanah Kering
										/ Darat</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_jenis_TKD()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->tanah_kering_darat ?: 0 ?>"
												id="tanah_kering_darat" name="tanah_kering_darat" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Patok Tanda Batas :</strong></label>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="ada_patok">Ada Patok Tanda
										Batas</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_patok()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->ada_patok ?: 0 ?>" id="ada_patok" name="ada_patok" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tidak_ada_patok">Tidak Ada Patok
										Tanda Batas</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_patok()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->tidak_ada_patok ?: 0 ?>" id="tidak_ada_patok"
												name="tidak_ada_patok" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-12'>
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Papan Nama :</strong></label>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="ada_papan_nama">Ada Papan
										Nama</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_papan()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->ada_papan_nama ?: 0 ?>" id="ada_papan_nama"
												name="ada_papan_nama" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3'>
								<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:left;" for="tidak_ada_papan_nama">Tidak Ada
										Papan Nama</label>
									<div class="col-sm-12">
										<div class="input-group">
											<input onchange="dinamic_papan()" type="text" min="0"
												class="form-control input-sm number required"
												value="<?= $main->tidak_ada_papan_nama ?: 0 ?>"
												id="tidak_ada_papan_nama" name="tidak_ada_papan_nama" />
											<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
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
								<label class="col-sm-3 control-label" style="text-align:left;" for="peruntukan">Peruntukan</label>
								<div class="col-sm-4">
									<select name="peruntukan" id="peruntukan" class="form-control input-sm required">
										<option value>-- Peruntukan Tanah--</option>
										<?php foreach ($list_peruntukan as $item): ?>
										<option value="<?= $item['id']?>" <?php selected($item['id'], $main->peruntukan) ?>><?= $item['nama']?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="lokasi">Lokasi</label>
								<div class="col-sm-8">
									<textarea rows="5" class="form-control input-sm nomor_sk" name="lokasi" id="lokasi"
										placeholder="Lokasi"><?= $main->lokasi; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="mutasi">Mutasi</label>
								<div class="col-sm-8">
									<textarea rows="5" class="form-control input-sm nomor_sk" name="mutasi" id="mutasi"
										placeholder="Mutasi"><?= $main->mutasi; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
								<div class="col-sm-8">
									<textarea rows="5" class="form-control input-sm nomor_sk" name="keterangan" id="keterangan"
										placeholder="Keterangan"><?= $main->keterangan; ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="form_footer" class="box-footer">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i>
							Batal</button>
						<button type="button" onclick="submit_form()" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i
								class="fa fa-check"></i> Simpan</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$('document').ready(function ()
	{
		var view = <?= $view_mark ?> ;
		var asal = "<?= $asal_tanah ?>";
		if (1 == view)
		{
			$("#pemilik_asal").attr("disabled", true);
			$("#letter_c_persil").attr("disabled", true);
			$("#kelas").attr("disabled", true);
			$("#tanggal_perolehan").attr("disabled", true);
			$("#luas").attr("disabled", true);
			$("#asli_milik_desa").attr("disabled", true);
			$("#pemerintah").attr("disabled", true);
			$("#provinsi").attr("disabled", true);
			$("#kabupaten_kota").attr("disabled", true);
			$("#lain_lain").attr("disabled", true);
			$("#sawah").attr("disabled", true);
			$("#tegal").attr("disabled", true);
			$("#kebun").attr("disabled", true);
			$("#tambak_kolam").attr("disabled", true);
			$("#tanah_kering_darat").attr("disabled", true);
			$("#ada_patok").attr("disabled", true);
			$("#tidak_ada_patok").attr("disabled", true);
			$("#ada_papan_nama").attr("disabled", true);
			$("#tidak_ada_papan_nama").attr("disabled", true);
			$("#peruntukan").attr("disabled", true);
			$("#lokasi").attr("disabled", true);
			$("#mutasi").attr("disabled", true);
			$("#keterangan").attr("disabled", true);
			$('#form_footer').hide();
			show_hide(asal);
		}
		else if (view == 2)
		{
			show_hide(asal);
		}
		else
		{
			$("#view_asli_milik_desa").hide();
			$("#view_pemerintah").hide();
			$("#view_provinsi").hide();
			$("#view_kabupaten_kota").hide();
			$("#view_lain_lain").hide();
		}
	});

	function show_hide(param)
	{
		if (1 == param)
		{
			$("#view_asli_milik_desa").show();
			$("#view_pemerintah").hide();
			$("#view_provinsi").hide();
			$("#view_kabupaten_kota").hide();
			$("#view_lain_lain").hide();
		}
		else if (2 == param)
		{
			$("#view_asli_milik_desa").hide();
			$("#view_pemerintah").show();
			$("#view_provinsi").show();
			$("#view_kabupaten_kota").show();
			$("#view_lain_lain").hide();
		}
		else
		{
			$("#view_asli_milik_desa").hide();
			$("#view_pemerintah").hide();
			$("#view_provinsi").hide();
			$("#view_kabupaten_kota").hide();
			$("#view_lain_lain").show();
		}
	}

	function dinamic_perolehan()
	{
		var res = 0;
		res = parseFloat($('#asli_milik_desa').val()) +
			parseFloat($('#pemerintah').val()) +
			parseFloat($('#provinsi').val()) +
			parseFloat($('#kabupaten_kota').val()) +
			parseFloat($('#lain_lain').val());
		return res;
	}

	function dinamic_jenis_TKD()
	{
		var res = 0;
		res = parseFloat($('#sawah').val()) +
			parseFloat($('#tegal').val()) +
			parseFloat($('#kebun').val()) +
			parseFloat($('#tambak_kolam').val()) +
			parseFloat($('#tanah_kering_darat').val());
		return res;
	}

	function dinamic_patok()
	{
		var res = 0;
		res = parseFloat($('#ada_patok').val()) +
			parseFloat($('#tidak_ada_patok').val());
		return res;
	}

	function dinamic_papan()
	{
		var res = 0;
		res = parseFloat($('#ada_papan_nama').val()) +
			parseFloat($('#tidak_ada_papan_nama').val())
		return res;
	}

	function reset_hide_section(param)
	{
		$("#luas").val(0);
		var field = param.substring(5, param.length);
		$("#" + field).val(0);
		$("#" + param).hide();
	}

	function reset_show_section(param)
	{
		var field = param.substring(5, param.length);
		$("#" + field).val(0);
		$("#" + param).show();
	}

	function reset_field()
	{
		$('#sawah').val(0)
		$('#tegal').val(0)
		$('#kebun').val(0)
		$('#tambak_kolam').val(0)
		$('#tanah_kering_darat').val(0)
		$('#ada_patok').val(0)
		$('#tidak_ada_patok').val(0)
		$('#ada_papan_nama').val(0)
		$('#tidak_ada_papan_nama').val(0)
	}

	function pilih_asal_tanah(param)
	{
		if (1 == param)
		{
			$("#view_label_asal_tanah").hide();
			var hideView = ["view_pemerintah", "view_provinsi", "view_kabupaten_kota", "view_lain_lain"];
			hideView.forEach(reset_hide_section);
			var showView = ["view_asli_milik_desa"];
			showView.forEach(reset_show_section);
			reset_field();
		}
		else if (2 == param)
		{
			$("#view_label_asal_tanah").hide();
			var hideView = ["view_asli_milik_desa", "view_lain_lain"];
			hideView.forEach(reset_hide_section);
			var showView = ["view_pemerintah", "view_provinsi", "view_kabupaten_kota"];
			showView.forEach(reset_show_section);
			reset_field();
		}
		else if (3 == param)
		{
			$("#view_label_asal_tanah").hide();
			var hideView = ["view_asli_milik_desa", "view_pemerintah", "view_provinsi", "view_kabupaten_kota"];
			hideView.forEach(reset_hide_section);
			var showView = ["view_lain_lain"];
			showView.forEach(reset_show_section);
			reset_field();
		}
		else
		{
			$("#view_label_asal_tanah").show();
			var hideView = ["view_asli_milik_desa", "view_pemerintah", "view_provinsi", "view_kabupaten_kota",
				"view_lain_lain"];
			hideView.forEach(reset_hide_section);
			reset_field();
		}
	}

	function submit_form()
	{
		var luas = $('#luas').val();
		var dinLuas = dinamic_perolehan();
		var dinTKD = dinamic_jenis_TKD();
		var dinPatok = dinamic_patok();
		var dinPapan = dinamic_papan();
		if (luas == dinLuas &&
			luas == dinTKD &&
			luas == dinPatok &&
			luas == dinPapan)
		{
			$("#validasi").submit();
		}
		else
		{
			notify = 'error';
			notify_msg = 'Luas Tanah Tidak Sesuai';
			notification(notify, notify_msg);
		}
	}
</script>