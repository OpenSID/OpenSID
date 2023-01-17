<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="opensidInventaris" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="opensidInventaris">Kode Yang Terdaftar</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<ul class="list-group">
							<?php
                            foreach ($kd_reg as $reg) {
                                if (strlen($reg->register) == 21) {
                                    echo '<li class="list-group-item" data-position-id="123">
												<div class="companyPosItem">
													<span class="companyPosLabel">' . substr($reg->register, -6) . '</span>
												</div>
											</li>';
                                }
                            }
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Ubah Data Inventaris Gedung Dan Bangunan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_gedung"><i class="fa fa-dashboard"></i>Daftar Inventaris Gedung Dan Bangunan</a></li>
			<li class="active">Ubah Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_gedung" method="post" action="<?= site_url("api_inventaris_gedung/update/{$main->id}"); ?>">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('inventaris/menu_kiri.php') ?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url() ?>inventaris_gedung" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Gedung Dan Bangunan</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->id; ?>" class="form-control input-sm required" name="id" id="id" type="hidden" />
											<input type="hidden" name="nama_barang_save" id="nama_barang_save" value="<?= $main->nama_barang; ?>">
											<input type="hidden" name="kode_desa" id="kode_desa" value="<?= kode_wilayah($get_kode['kode_desa']) ?>">
											<!-- <input maxlength="50" value="<?= $main->nama_barang; ?>" class="form-control input-sm required" name="nama_barang" id="nama_barang" type="text" /> -->
											<select class="form-control input-sm select2" id="nama_barang" name="nama_barang" onchange="formAction('main')">
												<option value="<?= $main->nama_barang; ?>" disabled><?= $main->nama_barang; ?></option>
												<?php foreach ($aset as $data) : ?>
													<option value="<?= $data['nama'] . '_' . $data['golongan'] . '.' . $data['bidang'] . '.' . $data['kelompok'] . '.' . $data['sub_kelompok'] . '.' . $data['sub_sub_kelompok'] . '.' . $hasil ?>">Kode Reg : <?= $data['golongan'] . '.' . $data['bidang'] . '.' . $data['kelompok'] . '.' . $data['sub_kelompok'] . '.' . $data['sub_sub_kelompok'] . ' - ' . $data['nama'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control input-sm required" name="kode_barang" id="kode_barang" type="text" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
										<div class="col-sm-5">
											<input maxlength="50" value="<?= $main->register; ?>" class="form-control input-sm required" name="register" id="register" type="text" placeholder="Nomor Register" />
										</div>
										<div class="col-sm-3">
											<a style="cursor: pointer;" id="view_modal" name="view_modal">Lihat Kode yang terdaftar</a>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kondisi">Kondisi Bangunan</label>
										<div class="col-sm-4">
											<select name="kondisi" id="kondisi" class="form-control input-sm required">
												<option value="<?= $main->kondisi_bangunan; ?>"><?= $main->kondisi_bangunan; ?></option>
												<option value="Baik">Baik</option>
												<option value="Rusak Ringan">Rusak Ringan</option>
												<option value="Rusak Sedang">Rusak Sedang</option>
												<option value="Rusak Berat">Rusak Berat</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tingkat">Bangunan Bertingkat</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input type="number" value="<?= $main->kontruksi_bertingkat; ?>" class="form-control input-sm required" id="tingkat" name="tingkat" type="number" />
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Lantai</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kontruksi">Konstruksi Beton</label>
										<div class="col-sm-4">
											<select name="kontruksi" id="kontruksi" class="form-control input-sm required">
												<option value="0" <?= ($main->kontruksi_beton == 0 ? 'selected' : ''); ?>>Tidak</option>
												<option value="1" <?= ($main->kontruksi_beton == 1 ? 'selected' : ''); ?>>Ya</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="luas_bangunan">Luas Bangunan</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input value="<?= $main->luas_bangunan; ?>" class="form-control input-sm number required" id="luas_bangunan" name="luas_bangunan" type="text" />
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="alamat">Letak / Lokasi </label>
										<div class="col-sm-8">
											<textarea class="form-control input-sm required" name="alamat" id="alamat"><?= $main->letak; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tahun_pengadaan">Tahun Pembelian</label>
										<div class="col-sm-4">
											<select name="tahun_pengadaan" id="tahun_pengadaan" class="form-control input-sm required">
												<option value="<?= date('Y', strtotime($main->tanggal_dokument)); ?>"><?= date('Y', strtotime($main->tanggal_dokument)); ?></option>
												<?php for ($i = date('Y'); $i >= 1900; $i--) : ?>
													<option value="<?= $i ?>"><?= $i ?></option>
												<?php endfor; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="no_bangunan">Nomor Bangunan</label>
										<div class="col-sm-8">
											<input maxlength="50" class="form-control input-sm required" name="no_bangunan" id="no_bangunan" type="text" value="<?= (! empty($main->no_dokument) ? $main->no_dokument : '-'); ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tanggal_bangunan">Tanggal Dokumen Bangunan</label>
										<div class="col-sm-4">
											<input maxlength="50" class="form-control input-sm required" name="tanggal_bangunan" id="tanggal_bangunan" type="date" value="<?= $main->tanggal_dokument; ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="status_gedung">Status Tanah</label>
										<div class="col-sm-4">
											<select name="status_tanah" id="status_tanah" class="form-control input-sm required">
												<option value="<?= $main->status_tanah; ?>"><?= $main->status_tanah; ?></option>
												<option value="Tanah milik Pemda">Tanah milik Pemda</option>
												<option value="Tanah Negara">Tanah Negara (Tanah yang dikuasai langsung oleh Negara)</option>
												<option value="Tanah Hak Ulayat">Tanah Hak Ulayat (Tanah masyarakat Hukum Adat)</option>
												<option value="Tanah Hak">Tanah Hak (Tanah kepunyaan perorangan atau Badan Hukum)</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah </label>
										<div class="col-sm-4">
											<div class="input-group">
												<input class="form-control input-sm number required" id="luas_tanah" name="luas_tanah" type="text" value="<?= $main->luas; ?>" />
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kode_tanah">Nomor Kode Tanah</label>
										<div class="col-sm-8">
											<input maxlength="50" class="form-control input-sm required" name="kode_tanah" id="kode_tanah" type="text" value="<?= (! empty($main->kode_tanah) ? $main->kode_tanah : '-'); ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="hak_tanah">Penggunaan Barang </label>
										<div class="col-sm-4">
											<select name="penggunaan_barang" id="penggunaan_barang" class="form-control input-sm required" placeholder="Hak Tanah" required>
												<?php
							                    $value = '';
							if (substr($main->kode_barang, -7, 2) == 01) {
							    $value = 'Pemerintah Desa';
							} elseif (substr($main->kode_barang, -7, 2) == 02) {
							    $value = 'Badan Permusyawaratan Daerah';
							} elseif (substr($main->kode_barang, -7, 2) == 03) {
							    $value = 'PKK';
							} elseif (substr($main->kode_barang, -7, 2) == 04) {
							    $value = 'LKMD';
							} elseif (substr($main->kode_barang, -7, 2) == 05) {
							    $value = 'Karang Taruna';
							} elseif (substr($main->kode_barang, -7, 2) == 07) {
							    $value = 'RW';
							}
							?>
												<option value="<?= substr($main->kode_barang, 14, 2); ?>"><?= $value; ?></option>
												<option value="01">Pemerintah Desa</option>
												<option value="02">Badan Permusyawaratan Daerah</option>
												<option value="03">PKK</option>
												<option value="04">LKMD</option>
												<option value="05">Karang Taruna</option>
												<option value="06">RW</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="asal">Asal Usul </label>
										<div class="col-sm-4">
											<select name="asal" id="asal" class="form-control input-sm required">
												<option value="<?= $main->asal; ?>"><?= $main->asal; ?></option>
												<option value="Bantuan Kabupaten">Bantuan Kabupaten</option>
												<option value="Bantuan Pemerintah">Bantuan Pemerintah</option>
												<option value="Bantuan Provinsi">Bantuan Provinsi</option>
												<option value="Pembelian Sendiri">Pembelian Sendiri</option>
												<option value="Sumbangan">Sumbangan</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="harga">Harga</label>
										<div class="col-sm-4">
											<div class="input-group">
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Rp</span>
												<input onkeyup="price()" class="form-control input-sm number required" id="harga" name="harga" value="<?= $main->harga; ?>" />
											</div>
										</div>
										<div class="col-sm-4">
											<div class="input-group">
												<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" disabled />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
										<div class="col-sm-8">
											<textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan"><?= $main->keterangan; ?></textarea>
										</div>
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
					</div>
				</div>
			</div>
		</form>
	</section>
</div>


<script>
	$(document).ready(function() {
		$('#kode_barang').val($('#kode_desa').val() + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());

		$("#tahun_pengadaan").change(function() {
			$('#kode_barang').val($('#kode_desa').val() + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
		});

		$("#penggunaan_barang").change(function() {
			$('#kode_barang').val($('#kode_desa').val() + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
		});
		$('#output').val(numeral($('#harga').val()).format('Rp0,0'));

		$("#nama_barang").change(function() {
			if ($('#register').val().length != 21) {
				$('#register').val($('#nama_barang').val().split('_').pop());
				$('#nama_barang_save').val($('#nama_barang').val().slice(0, -16));
			} else {
				$('#register').val($('#nama_barang').val().split('_').pop() + $('#register').val().slice(-6));
				$('#nama_barang_save').val($('#nama_barang').val().slice(0, -16));
			}
		});
	});

	function price() {
		$('#output').val(numeral($('#harga').val()).format('Rp0,0'));
	}

	$(function() {
		$('.select2').select2();
	})

	$("#view_modal").click(function(event) {
		$('#modal').modal("show");
	});
</script>