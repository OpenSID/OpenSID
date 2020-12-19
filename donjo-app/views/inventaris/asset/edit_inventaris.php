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
								foreach($kd_reg as $reg){
									if(strlen($reg->register) == 21){
										echo '<li class="list-group-item" data-position-id="123">
												<div class="companyPosItem">
													<span class="companyPosLabel">'.substr($reg->register,-6).'</span>
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
		<h1>Ubah Data Asset Lainnya</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_asset"><i class="fa fa-dashboard"></i>Daftar Asset Lainnya</a></li>
			<li class="active">Ubah Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_asset" method="post" action="<?= site_url("api_inventaris_asset/update/$main->id"); ?>">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('inventaris/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
						<a href="<?= site_url() ?>inventaris_asset" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Asset Lainnya</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->id; ?>" class="form-control input-sm required" name="id" id="id" type="hidden"/>
											<input type="hidden" name="nama_barang_save" id="nama_barang_save" value="<?= $main->nama_barang; ?>">
											<input type="hidden" name="kode_desa" id="kode_desa" value="<?=kode_wilayah($get_kode["kode_desa"])?>">
											<select class="form-control input-sm select2" id="nama_barang" name="nama_barang" onchange="formAction('main')">
												<option value="<?= $main->nama_barang; ?>"><?= $main->nama_barang; ?></option>
												<?php foreach ($aset as $data): ?>
													<option value="<?=  $data['nama']."_".$data['golongan'].".".$data['bidang'].".".$data['kelompok'].".".$data['sub_kelompok'].".".$data['sub_sub_kelompok'].".".$hasil?>">Kode Reg : <?= $data['golongan'].".".$data['bidang'].".".$data['kelompok'].".".$data['sub_kelompok'].".".$data['sub_sub_kelompok']." - ".$data['nama']?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control input-sm required" name="kode_barang" id="kode_barang" type="text"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
										<div class="col-sm-5">
											<input maxlength="50" value="<?= $main->register; ?>" class="form-control input-sm required" name="register" id="register" type="text" placeholder="Nomor Register"/>
										</div>
										<div class="col-sm-3">
											<a style="cursor: pointer;" id="view_modal" name="view_modal" >Lihat Kode yang terdaftar</a>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="jenis_asset">Jenis Asset</label>
										<div class="col-sm-4">
											<select name="jenis_asset" id="jenis_asset" class="form-control input-sm required">
												<option value="<?= $main->jenis; ?>"><?= $main->jenis; ?></option>
												<option value="Buku">Buku</option>
												<option value="Barang Kesenian">Barang Kesenian</option>
												<option value="Hewan Ternak">Hewan Ternak</option>
												<option value="Tumbuhan">Tumbuhan</option>
											</select>
										</div>
									</div>
									<div class="form-group judul">
										<label class="col-sm-3 control-label" style="text-align:left;" for="judul">Judul dan Pencipta Buku</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" value="<?= $main->judul_buku; ?>" id="judul" name="judul" type="text"/>
										</div>
									</div>
									<div class="form-group spesifikasi">
										<label class="col-sm-3 control-label" style="text-align:left;" for="spesifikasi">Spesifikasi Buku</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" value="<?= $main->spesifikasi_buku; ?>" id="spesifikasi" name="spesifikasi" type="text"/>
										</div>
									</div>
									<div class="form-group asal_kesenian">
										<label class="col-sm-3 control-label" style="text-align:left;" for="asal_kesenian">Asal Daerah Kesenian</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" value="<?= $main->asal_daerah; ?>" id="asal_kesenian" name="asal_kesenian" type="text" />
										</div>
									</div>
									<div class="form-group pencipta_kesenian">
										<label class="col-sm-3 control-label" style="text-align:left;" for="pencipta_kesenian">Pencipta Kesenian </label>
										<div class="col-sm-8">
											<input class="form-control input-sm" value="<?= $main->pencipta; ?>" id="pencipta_kesenian" name="pencipta_kesenian" type="text"/>
										</div>
									</div>
									<div class="form-group bahan_kesenian">
										<label class="col-sm-3 control-label" style="text-align:left;" for="bahan_kesenian">Bahan Kesenian</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" value="<?= $main->bahan; ?>" id="bahan_kesenian" name="bahan_kesenian" type="text"/>
										</div>
									</div>
									<div class="form-group jenis_hewan">
										<label class="col-sm-3 control-label" style="text-align:left;" for="jenis_hewan">Jenis Hewan Ternak</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" value="<?= $main->jenis_hewan; ?>" id="jenis_hewan" name="jenis_hewan" type="text"/>
										</div>
									</div>
									<div class="form-group ukuran_hewan">
										<label class="col-sm-3 control-label" style="text-align:left;" for="ukuran_hewan">Ukuran Hewan Ternak</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input class="form-control input-sm number" value="<?= $main->ukuran_hewan; ?>" id="ukuran_hewan" name="ukuran_hewan" type="text"/>
												<span class="input-group-addon input-sm" id="ukuran_hewan-addon">Kg</span>
											</div>
										</div>
									</div>
									<div class="form-group jenis_tumbuhan">
										<label class="col-sm-3 control-label" style="text-align:left;" for="jenis_tumbuhan">Jenis Tumbuhan</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" value="<?= $main->jenis_tumbuhan; ?>" id="jenis_tumbuhan" name="jenis_tumbuhan" type="text"/>
										</div>
									</div>
									<div class="form-group ukuran_tumbuhan">
										<label class="col-sm-3 control-label" style="text-align:left;" for="ukuran_tumbuhan">Ukuran Tumbuhan</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input class="form-control input-sm number" value="<?= $main->ukuran_tumbuhan; ?>" id="ukuran_tumbuhan" name="ukuran_tumbuhan" type="text"/>
												<span class="input-group-addon input-sm" id="ukuran_tumbuhan">M</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="jumlah">Jumlah</label>
										<div class="col-sm-8">
											<input class="form-control input-sm number" value="<?= $main->jumlah; ?>" id="jumlah" name="jumlah" type="text"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tahun Pembelian </label>
										<div class="col-sm-4">
											<select name="tahun" id="tahun" class="form-control input-sm">
												<option value="<?= $main->tahun_pengadaan; ?>"><?= $main->tahun_pengadaan; ?></option>
												<?php for ($i=date("Y"); $i>=1980; $i--): ?>
													<option value="<?= $i ?>"><?= $i ?></option>
												<?php endfor; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="hak_tanah">Penggunaan Barang </label>
										<div class="col-sm-4">
											<select name="penggunaan_barang" id="penggunaan_barang" class="form-control input-sm required" placeholder="Hak Tanah" required>
											<?php
												$value = '';
												if(substr($main->kode_barang,-7,2)==01){
													$value = 'Pemerintah Desa';
												}elseif(substr($main->kode_barang,-7,2)==02){
													$value = 'Badan Permusyawaratan Daerah';
												}elseif(substr($main->kode_barang,-7,2)==03){
													$value = 'PKK';
												}elseif(substr($main->kode_barang,-7,2)==04){
													$value = 'LKMD';
												}elseif(substr($main->kode_barang,-7,2)==05){
													$value = 'Karang Taruna';
												}elseif(substr($main->kode_barang,-7,2)==07){
													$value = 'RW';
												}
											?>
												<option value="<?=substr($main->kode_barang,14,2);?>"><?=$value;?></option>
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
										<label class="col-sm-3 control-label" style="text-align:left;" for="asal_usul">Asal Usul </label>
										<div class="col-sm-4">
											<select name="asal_usul" id="asal_usul" class="form-control input-sm required">
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
													<input onkeyup="price()"  class="form-control input-sm number required" id="harga" name="harga" value="<?= $main->harga; ?>" />
												</div>
											</div>
											<div class="col-sm-4">
												<div class="input-group">
													<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" disabled/>
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
		if($("#jenis_asset").val() == "Buku") {
			$(".judul").show();
			$(".spesifikasi").show();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		} else if ($("#jenis_asset").val() == "Barang Kesenian") {
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").show();
			$(".pencipta_kesenian").show();
			$(".bahan_kesenian").show();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		} else if ($("#jenis_asset").val() == "Hewan Ternak") {
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").show();
			$(".ukuran_hewan").show();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		} else if ($("#jenis_asset").val() == "Tumbuhan") {
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").show();
			$(".ukuran_tumbuhan").show();
		}

		$("#jenis_asset").change(function() {
			if ($("#jenis_asset").val() == "Buku") {
				$(".judul").show();
				$(".spesifikasi").show();
				$(".asal_kesenian").hide();
				$(".pencipta_kesenian").hide();
				$(".bahan_kesenian").hide();
				$(".jenis_hewan").hide();
				$(".ukuran_hewan").hide();
				$(".jenis_tumbuhan").hide();
				$(".ukuran_tumbuhan").hide();
			} else if ($("#jenis_asset").val() == "Barang Kesenian") {
				$(".judul").hide();
				$(".spesifikasi").hide();
				$(".asal_kesenian").show();
				$(".pencipta_kesenian").show();
				$(".bahan_kesenian").show();
				$(".jenis_hewan").hide();
				$(".ukuran_hewan").hide();
				$(".jenis_tumbuhan").hide();
				$(".ukuran_tumbuhan").hide();
			} else if ($("#jenis_asset").val() == "Hewan Ternak") {
				$(".judul").hide();
				$(".spesifikasi").hide();
				$(".asal_kesenian").hide();
				$(".pencipta_kesenian").hide();
				$(".bahan_kesenian").hide();
				$(".jenis_hewan").show();
				$(".ukuran_hewan").show();
				$(".jenis_tumbuhan").hide();
				$(".ukuran_tumbuhan").hide();
			} else if ($("#jenis_asset").val() == "Tumbuhan") {
				$(".judul").hide();
				$(".spesifikasi").hide();
				$(".asal_kesenian").hide();
				$(".pencipta_kesenian").hide();
				$(".bahan_kesenian").hide();
				$(".jenis_hewan").hide();
				$(".ukuran_hewan").hide();
				$(".jenis_tumbuhan").show();
				$(".ukuran_tumbuhan").show();
			}
		});
	});

	$( document ).ready(function() {
		$("#tahun").change(function() {
			$('#kode_barang').val($('#kode_desa').val()+"."+$('#penggunaan_barang').val()+"."+$('#tahun').val());
		});

		$("#penggunaan_barang").change(function() {
			$('#kode_barang').val($('#kode_desa').val()+"."+$('#penggunaan_barang').val()+"."+$('#tahun').val());
		});

		$('#output').val(numeral($('#harga').val()).format('Rp0,0'));

		$("#nama_barang").change(function() {
			if($('#register').val().length != 21){
				$('#register').val($('#nama_barang').val().split('_').pop());
				$('#nama_barang_save').val($('#nama_barang').val().slice(0,-16));
			} else {
				$('#register').val($('#nama_barang').val().split('_').pop() + $('#register').val().slice(-6));
				$('#nama_barang_save').val($('#nama_barang').val().slice(0,-16));
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
