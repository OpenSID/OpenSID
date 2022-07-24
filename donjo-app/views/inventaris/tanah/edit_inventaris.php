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
		<h1>Ubah Data Inventaris Tanah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_tanah"><i class="fa fa-dashboard"></i>Daftar Inventaris Tanah</a></li>
			<li class="active">Ubah Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= site_url("api_inventaris_tanah/update/{$main->id}"); ?>">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('inventaris/menu_kiri.php') ?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url() ?>inventaris_tanah" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Tanah</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nama_barang">Nama Barang</label>
										<div class="col-sm-8">
											<input type="hidden" id="id" name="id" value="<?= $main->id; ?>">
											<input type="hidden" name="nama_barang_save" id="nama_barang_save" value="<?= $main->nama_barang; ?>">
											<input type="hidden" name="kode_desa" id="kode_desa" value="<?= kode_wilayah($get_kode['kode_desa']) ?>">
											<select class="form-control input-sm select2" id="nama_barang" name="nama_barang" style="width:100%;" onchange="formAction('main')">
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
											<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control input-sm required" name="kode_barang" id="kode_barang" type="text" placeholder="Kode Barang" />
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
										<label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input value="<?= $main->luas; ?>" class="form-control input-sm number required" id="luas" name="luas" type="text" placeholder="Luas Tanah" />
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tahun Pengadaan </label>
										<div class="col-sm-4">
											<select name="tahun_pengadaan" id="tahun_pengadaan" class="form-control input-sm required" placeholder="Tahun Pengadaan">
												<option value="<?= $main->tahun_pengadaan; ?>"><?= $main->tahun_pengadaan; ?></option>
												<?php for ($i = date('Y'); $i >= 1900; $i--) : ?>
													<option value="<?= $i ?>"><?= $i ?></option>
												<?php endfor; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="alamat">Letak / Alamat </label>
										<div class="col-sm-8">
											<textarea class="form-control input-sm required" name="letak" id="letak" placeholder="Letak / Alamat"><?= $main->letak; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="hak_tanah">Hak Tanah </label>
										<div class="col-sm-4">
											<select name="hak" id="hak" class="form-control input-sm required" placeholder="Hak Tanah">
												<option value="<?= $main->hak; ?>"><?= $main->hak; ?></option>
												<option value="Hak Pakai">Hak Pakai</option>
												<option value="Hak Pengelolaan">Hak Pengelolaan</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="hak_tanah">Penggunaan Barang </label>
										<div class="col-sm-4">
											<select name="penggunaan_barang" id="penggunaan_barang" class="form-control input-sm required" placeholder="Hak Tanah" required>
												<?php
							                    $value = '';
							if (substr($main->kode_barang, 14, 2) == 01) {
							    $value = 'Pemerintah Desa';
							} elseif (substr($main->kode_barang, 14, 2) == 02) {
							    $value = 'Badan Permusyawaratan Daerah';
							} elseif (substr($main->kode_barang, 14, 2) == 03) {
							    $value = 'PKK';
							} elseif (substr($main->kode_barang, 14, 2) == 04) {
							    $value = 'LKMD';
							} elseif (substr($main->kode_barang, 14, 2) == 05) {
							    $value = 'Karang Taruna';
							} elseif (substr($main->kode_barang, 14, 2) == 07) {
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
										<label class="col-sm-3 control-label" style="text-align:left;" for="tanggal_sertifikat">Tanggal Sertifikat</label>
										<div class="col-sm-4">
											<input maxlength="50" value="<?= $main->tanggal_sertifikat; ?>" class="form-control input-sm required" name="tanggal_sertifikat" id="tanggal_sertifikat" type="date" placeholder="Tanggal Sertifikat" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_sertifikat">Nomor Sertifikat </label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->no_sertifikat; ?>" class="form-control input-sm required" name="no_sertifikat" id="no_sertifikat" type="text" placeholder="Nomor Sertifikat" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="penggunaan">Penggunaan </label>
										<div class="col-sm-4">
											<select name="penggunaan" id="penggunaan" class="form-control input-sm required" placeholder="Penggunaan">
												<option value="<?= $main->penggunaan; ?>"><?= $main->penggunaan; ?></option>
												<option value="Industri">Industri</option>
												<option value="Jalan">Jalan</option>
												<option value="Komersial">Komersial</option>
												<option value="Permukiman">Permukiman</option>
												<option value="Tanah Publik">Tanah Publik</option>
												<option value="Tanah Kosong">Tanah Kosong</option>
												<option value="Perkebunan">Perkebunan</option>
												<option value="Pertanian">Pertanian</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="asal_usul">Asal Usul </label>
										<div class="col-sm-4">
											<select name="asal" id="asal" class="form-control input-sm required" placeholder="Asal Usul">
												<option value="<?= $main->asal; ?>"><?= $main->asal; ?></option>
												<option value="Bantuan Kabupaten">Bantuan Kabupaten</option>
												<option value="Bantuan Pemerintah">Bantuan Pemerintah</option>
												<option value="Bantuan Provinsi">Bantuan Provinsi</option>
												<option value="Pembelian Sendiri">Pembelian Sendiri</option>
												<option value="Sumbangan">Sumbangan</option>
												<option value="Hak Adat">Hak Adat</option>
												<option value="Hibah">Hibah</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="harga">Harga</label>
										<div class="col-sm-4">
											<div class="input-group">
												<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">Rp</span>
												<input value="<?= $main->harga; ?>" class="form-control input-sm number required" id="harga" name="harga" type="text" placeholder="Harga" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
										<div class="col-sm-8">
											<textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan" placeholder="Keterangan"><?= $main->keterangan; ?></textarea>
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