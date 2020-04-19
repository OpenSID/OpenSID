<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Data Inventaris Kontruksi</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_peralatan"><i class="fa fa-dashboard"></i>Daftar Inventaris Kontruksi</a></li>
			<li class="active">Rincian Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_kontruksi" method="post" action="<?= $form_action?>">
			<div class="row">
				<div class="col-md-3 ">
					<?php $this->load->view('inventaris/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
						<a href="<?= site_url() ?>inventaris_kontruksi" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Kontruksi</a>
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
										<label class="col-sm-3  control-label" style="text-align:left;" for="fisik_bangunan">Fisik Bangunan</label>
										<div class="col-sm-4">
											<select name="fisik_bangunan" id="fisik_bangunan" class="form-control input-sm" disabled>
												<option value="<?= $main->kondisi_bangunan; ?>"><?= $main->kondisi_bangunan; ?></option>
												<option value="Darurat">Darurat</option>
												<option value="Permanen">Permanen</option>
												<option value="Semi Permanen">Semi Permanen</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="tingkat">Bangunan Bertingkat</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input type="text" value="<?= ($main->kontruksi_bertingkat != 0 ? $main->kontruksi_bertingkat : '-' ); ?>" class="form-control input-sm" id="tingkat" name="tingkat" disabled />
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Lantai</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="bahan">Kontruksi Beton</label>
										<div class="col-sm-4">
											<select name="bahan" id="bahan" class="form-control input-sm" disabled>
												<?php if ($main->kontruksi_beton == 0): ?>
													<option value='0'>Tidak</option>
													<option value='1'>Ya</option>
												<?php else: ?>
													<option value='1'>Ya</option>
													<option value='0'>Tidak</option>
												<?php endif; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="luas_bangunan">Luas</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input type="text" value="<?= ($main->luas_bangunan != 0 ? $main->luas_bangunan : '-' ); ?>" class="form-control input-sm" id="luas_bangunan" name="luas_bangunan" disabled/>
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="alamat">Letak / Lokasi </label>
										<div class="col-sm-8">
											<textarea class="form-control input-sm" name="alamat" id="alamat" disabled><?= $main->letak; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="no_bangunan">Nomor Bangunan</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->no_dokument; ?>"  class="form-control input-sm" name="no_bangunan" id="no_bangunan" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="tanggal_bangunan">Tanggal Dokumen Bangunan</label>
										<div class="col-sm-4">
											<input maxlength="50" value="<?= $main->tanggal_dokument; ?>" class="form-control input-sm" name="tanggal_bangunan" id="tanggal_bangunan" type="date" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="tanggal_mulai">Tanggal Mulai </label>
										<div class="col-sm-4">
											<input class="form-control input-sm" value="<?= $main->tanggal; ?>" id="tanggal_mulai" name="tanggal_mulai" type="date" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="status_tanah">Status Tanah</label>
										<div class="col-sm-4">
											<select name="status_tanah" id="status_tanah" class="form-control input-sm" disabled>
												<option value="<?= (!empty($main->status_tanah)? $main->status_tanah : '-' ); ?>"><?= (!empty($main->status_tanah)? $main->status_tanah : '-' ); ?></option>
												<option value="Tanah milik Pemda">Tanah milik Pemda</option>
												<option value="Tanah Negara">Tanah Negara (Tanah yang dikuasai langsung oleh Negara)</option>
												<option value="Tanah Hak Ulayat">Tanah Hak Ulayat (Tanah masyarakat Hukum Adat)</option>
												<option value="Tanah Hak">Tanah Hak (Tanah kepunyaan perorangan atau Badan Hukum)</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="kode_tanah">Nomor Kode Tanah</label>
										<div class="col-sm-8">
											<input maxlength="50"  value="<?= (!empty($main->kode_tanah)? $main->kode_tanah : '-' ); ?>" class="form-control input-sm" name="kode_tanah" id="kode_tanah" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="asal_usul" disabled>Asal Usul </label>
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
										<label class="col-sm-3  control-label" style="text-align:left;" for="harga">Harga</label>
										<div class="col-sm-4">
											<div class="input-group">
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Rp</span>
												<input type="number" value="<?= $main->harga; ?>" class="form-control input-sm" id="harga" name="harga" type="text" disabled/>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3  control-label" style="text-align:left;" for="keterangan">Keterangan</label>
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

