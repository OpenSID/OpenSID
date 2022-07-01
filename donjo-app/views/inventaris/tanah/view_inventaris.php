<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Inventaris Tanah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_tanah"><i class="fa fa-dashboard"></i>Daftar Inventaris Tanah</a></li>
			<li class="active">Rincian Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= $form_action?>">
			<div class="row">
				<div class="col-md-3">
          <?php	$this->load->view('inventaris/menu_kiri.php')?>
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
										<label class="col-sm-3 control-label required" style="text-align:left;" for="nama_barang">Nama Barang</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->nama_barang; ?>" class="form-control input-sm" name="nama_barang" id="nama_barang" type="text" disabled />
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
										<label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input value="<?= $main->luas; ?>" class="form-control input-sm" id="luas_tanah" name="luas_tanah" type="text" disabled/>
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tahun Pengadaan </label>
										<div class="col-sm-8">
											<select name="tahun" id="tahun" class="form-control input-sm" disabled>
												<option value="<?= $main->tahun_pengadaan; ?>"><?= $main->tahun_pengadaan; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="alamat">Letak / Alamat </label>
										<div class="col-sm-8">
											<textarea class="form-control input-sm" name="alamat" id="alamat" disabled><?= $main->letak; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="hak_tanah">Hak Tanah </label>
										<div class="col-sm-4">
											<select name="hak_tanah" id="hak_tanah" class="form-control input-sm" disabled>
												<option value="<?= $main->hak; ?>"><?= $main->hak; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="tanggal_sertifikat">Tanggal Sertifikat</label>
										<div class="col-sm-4">
											<input maxlength="50" value="<?= date('d M Y', strtotime($main->tanggal_sertifikat)); ?>" class="form-control input-sm" name="tanggal_sertifikat" id="tanggal_sertifikat" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="nomor_sertifikat">Nomor Sertifikat </label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->no_sertifikat; ?>" class="form-control input-sm" name="nomor_sertifikat" id="nomor_sertifikat" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="penggunaan">Penggunaan </label>
										<div class="col-sm-4">
											<select name="penggunaan" id="penggunaan" class="form-control input-sm" disabled>
												<option value="<?= $main->penggunaan; ?>"><?= $main->penggunaan; ?></option>
												<option value="Industri">Industri</option>
												<option value="Jalan">Jalan</option>
												<option value="Komersial">Komersial</option>
												<option value="Permukiman">Permukiman</option>
												<option value="Tanah Publik">Tanah Publik</option>
												<option value="Tanah Kosong">Tanah Kosong</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="asal_usul">Asal Usul </label>
										<div class="col-sm-4">
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
												<input value="<?= $main->harga; ?>" class="form-control input-sm" id="harga" name="harga" type="text" disabled/>
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

