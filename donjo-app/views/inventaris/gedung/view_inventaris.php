<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Data Inventaris Gedung Dan Bangunan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_peralatan"><i class="fa fa-dashboard"></i>Daftar Inventaris Gedung Dan Bangunan</a></li>
			<li class="active">Rincian Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_gedung" method="post" action="<?= $form_action?>">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('inventaris/menu_kiri.php')?>
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
											<input maxlength="50" value="<?= $main->nama_barang; ?>" class="form-control input-sm" name="nama_barang" id="nama_barang" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control input-sm" name="kode_barang" id="kode_barang" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->register; ?>" class="form-control input-sm" name="nomor_register" id="nomor_register" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kondisi">Kondisi Bangunan</label>
										<div class="col-sm-4">
											<select name="kondisi" id="kondisi" class="form-control input-sm" disabled>
												<option value="<?= $main->kondisi_bangunan; ?>"><?= $main->kondisi_bangunan; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tingkat">Bangunan Bertingkat</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input value="<?= $main->kontruksi_bertingkat; ?>" class="form-control number input-sm" id="tingkat" name="tingkat" type="text" disabled/>
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Lantai</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kontruksi">Konstruksi Beton</label>
										<div class="col-sm-4">
											<select name="kontruksi" id="kontruksi" class="form-control input-sm"disabled>
												<option value="<?= $main->kontruksi_beton; ?>"><?= $main->kontruksi_beton; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="luas_bangunan">Luas Bangunan</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input value="<?= $main->luas_bangunan; ?>" class="form-control number input-sm" id="luas_bangunan" name="luas_bangunan" type="text" disabled/>
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="alamat">Letak / Lokasi </label>
										<div class="col-sm-8">
											<textarea class="form-control input-sm" name="alamat" id="alamat" disabled><?= $main->letak; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="no_bangunan">Nomor Bangunan</label>
										<div class="col-sm-8">
											<input maxlength="50" class="form-control input-sm" name="no_bangunan" id="no_bangunan" type="text" disabled value="<?= (! empty($main->no_dokument) ? $main->no_dokument : '-'); ?>"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tanggal_bangunan">Tanggal Dokumen Bangunan</label>
										<div class="col-sm-4">
											<input maxlength="50" class="form-control input-sm" name="tanggal_bangunan" id="tanggal_bangunan" type="text" disabled value="<?= date('d M Y', strtotime($main->tanggal_dokument)); ?>"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="status_tanah">Status Tanah</label>
										<div class="col-sm-8">
											<select name="status_tanah" id="status_tanah" class="form-control input-sm" disabled>
												<option value="<?= $main->status_tanah; ?>"><?= $main->status_tanah; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah </label>
										<div class="col-sm-4">
											<div class="input-group">
												<input class="form-control input-sm" id="luas_tanah" name="luas_tanah" type="text" value="<?= $main->luas; ?>" disabled/>
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kode_tanah">Nomor Kode Tanah</label>
										<div class="col-sm-8">
											<input maxlength="50" class="form-control input-sm" name="kode_tanah" id="kode_tanah" type="text" value="<?= (! empty($main->kode_tanah) ? $main->kode_tanah : '-'); ?>" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="asal_usul">Asal Usul </label>
										<div class="col-sm-8">
											<select name="asal_usul" id="asal_usul" class="form-control input-sm" disabled>
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
												<input type="text" class="form-control input-sm" id="harga" name="harga" type="text" value="<?= number_format($main->harga, 0, '.', '.'); ?>" disabled/>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
										<div class="col-sm-8">
											<textarea rows="5" class="form-control input-sm" name="keterangan" id="keterangan" disabled><?= $main->keterangan; ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

