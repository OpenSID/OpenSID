<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Data Asset Lainnya</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('inventaris_asset') ?>"><i class="fa fa-dashboard"></i>Daftar Inventaris Asset Lainnya</a></li>
			<li class="active">Rincian Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= $form_action?>">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('inventaris/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
						<a href="<?= site_url('inventaris_asset') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Asset Lainnya</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
								<div class="form-group">
									<label class="col-sm-3 control-label " style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
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
									<label class="col-sm-3 control-label" style="text-align:left;" for="jenis_asset">Jenis Asset</label>
									<div class="col-sm-4">
										<select name="jenis_asset" id="jenis_asset" class="form-control input-sm" disabled>
											<option value="<?= $main->jenis; ?>"><?= $main->jenis; ?></option>
										</select>
									</div>
								</div>
								<div class="form-group judul">
									<label class="col-sm-3 control-label " style="text-align:left;" for="judul">Judul dan Pencipta Buku</label>
									<div class="col-sm-8">
										<input class="form-control input-sm" value="<?= $main->judul_buku; ?>" id="judul" name="judul" type="text" disabled/>
									</div>
								</div>
								<div class="form-group spesifikasi">
									<label class="col-sm-3 control-label" style="text-align:left;" for="spesifikasi">Spesifikasi Buku</label>
									<div class="col-sm-8">
										<input class="form-control input-sm" value="<?= $main->spesifikasi_buku; ?>" id="spesifikasi" name="spesifikasi" type="text" disabled/>
									</div>
								</div>
								<div class="form-group asal_kesenian">
									<label class="col-sm-3 control-label " style="text-align:left;" for="asal_kesenian">Asal Daerah Kesenian</label>
									<div class="col-sm-8">
										<input class="form-control input-sm" value="<?= $main->asal_daerah; ?>" id="asal_kesenian" name="asal_kesenian" type="text" disabled/>
									</div>
								</div>
								<div class="form-group pencipta_kesenian">
									<label class="col-sm-3 control-label" style="text-align:left;" for="pencipta_kesenian">Pencipta Kesenian </label>
									<div class="col-sm-8">
										<input class="form-control input-sm" value="<?= $main->pencipta; ?>" id="pencipta_kesenian" name="pencipta_kesenian" type="text" disabled/>
									</div>
								</div>
								<div class="form-group bahan_kesenian">
									<label class="col-sm-3 control-label " style="text-align:left;" for="bahan_kesenian">Bahan Kesenian</label>
									<div class="col-sm-8">
										<input class="form-control input-sm" value="<?= $main->bahan; ?>" id="bahan_kesenian" name="bahan_kesenian" type="text" disabled/>
									</div>
								</div>
								<div class="form-group jenis_hewan">
									<label class="col-sm-3 control-label " style="text-align:left;" for="jenis_hewan">Jenis Hewan Ternak</label>
									<div class="col-sm-8">
										<input class="form-control input-sm" value="<?= $main->jenis_hewan; ?>" id="jenis_hewan" name="jenis_hewan" type="text" disabled/>
									</div>
								</div>
								<div class="form-group ukuran_hewan">
									<label class="col-sm-3 control-label " style="text-align:left;" for="ukuran_hewan">Ukuran Hewan Ternak</label>
									<div class="col-sm-4">
										<div class="input-group">
											<input class="form-control input-sm number " value="<?= $main->ukuran_hewan; ?>" id="ukuran_hewan" name="ukuran_hewan" type="text" disabled/>
											<span class="input-group-addon input-sm" id="ukuran_hewan-addon">Kg</span>
										</div>
									</div>
								</div>
								<div class="form-group jenis_tumbuhan">
									<label class="col-sm-3 control-label " style="text-align:left;" for="jenis_tumbuhan">Jenis Tumbuhan</label>
									<div class="col-sm-8">
										<input class="form-control input-sm" value="<?= $main->jenis_tumbuhan; ?>" id="jenis_tumbuhan" name="jenis_tumbuhan" type="text" disabled/>
									</div>
								</div>
								<div class="form-group ukuran_tumbuhan">
									<label class="col-sm-3 control-label " style="text-align:left;" for="ukuran_tumbuhan">Ukuran Tumbuhan</label>
									<div class="col-sm-4">
										<div class="input-group">
											<input class="form-control input-smn number" value="<?= $main->ukuran_tumbuhan; ?>" id="ukuran_tumbuhan" name="ukuran_tumbuhan" type="text" disabled/>
											<span class="input-group-addon input-sm" id="ukuran_tumbuhan">M</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label " style="text-align:left;" for="jumlah">Jumlah</label>
									<div class="col-sm-4">
										<input class="form-control number input-sm" value="<?= $main->jumlah; ?>" id="jumlah" name="jumlah" type="text" disabled />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tahun Pembelian </label>
									<div class="col-sm-4">
										<select name="tahun_pengadaan" id="tahun_pengadaan" class="form-control input-sm" disabled >
											<option value="<?= $main->tahun_pengadaan; ?>"><?= $main->tahun_pengadaan; ?></option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label " style="text-align:left;" for="asal_usul">Asal Usul </label>
									<div class="col-sm-4">
										<select name="asal_usul" id="asal_usul" class="form-control input-sm" disabled>
											<option value="<?= $main->asal; ?>"><?= $main->asal; ?></option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" style="text-align:left;" for="harga">Harga</label>
									<div class="col-sm-4">
										<div class="input-group">
											<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Rp</span>
											<input type="text"  value="<?= number_format($main->harga, 0, '.', '.'); ?>" class="form-control input-sm number" id="harga" name="harga" type="text" disabled/>
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

