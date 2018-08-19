<div class="content-wrapper">
	<section class="content-header">
		<h1>Ubah Data Inventaris Tanah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_tanah"><i class="fa fa-dashboard"></i>Daftar Inventaris Tanah</a></li>
			<li class="active">Ubah Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= site_url("api_inventaris_tanah/update"); ?>">
			<div class="row">
				<div class="col-md-3">
          <?php	$this->load->view('inventaris/tanah/menu_kiri.php')?>
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
											<input maxlength="50" value="<?= $main->nama_barang; ?>" class="form-control input-sm required" name="nama_barang" id="nama_barang" type="text" placeholder="Nama Barang"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control input-sm required" name="kode_barang" id="kode_barang" type="text" placeholder="Kode Barang"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->register; ?>" class="form-control input-sm required" name="register" id="register" type="text" placeholder="Nomor Register"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input value="<?= $main->luas; ?>" class="form-control input-sm number required" id="luas" name="luas" type="text" placeholder="Luas Tanah"/>
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tahun Pengadaan </label>
										<div class="col-sm-4">
											<select name="tahun_pengadaan" id="tahun_pengadaan" class="form-control input-sm required" placeholder="Tahun Pengadaan">
												<option value="<?= $main->tahun_pengadaan; ?>"><?= $main->tahun_pengadaan; ?></option>
												<?php for ($i=date("Y"); $i>=1980; $i--): ?>
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
										<label class="col-sm-3 control-label" style="text-align:left;" for="tanggal_sertifikat">Tanggal Sertifikat</label>
										<div class="col-sm-4">
											<input maxlength="50" value="<?= $main->tanggal_sertifikat; ?>" class="form-control input-sm required" name="tanggal_sertifikat" id="tanggal_sertifikat" type="date" placeholder="Tanggal Sertifikat"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_sertifikat">Nomor Sertifikat </label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->no_sertifikat; ?>" class="form-control input-sm required" name="no_sertifikat" id="no_sertifikat" type="text" placeholder="Nomor Sertifikat"/>
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
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="harga">Harga</label>
										<div class="col-sm-4">
											<div class="input-group">
												<span class="input-group-addon input-sm " id="koefisien_dasar_bangunan-addon">Rp</span>
												<input value="<?= $main->harga; ?>" class="form-control input-sm number required" id="harga" name="harga" type="text" placeholder="Harga"/>
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

