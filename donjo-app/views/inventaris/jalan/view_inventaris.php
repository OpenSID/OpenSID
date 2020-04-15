<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Daftar Inventaris Jalan, Irigasi dan Jaringan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_jalan"><i class="fa fa-dashboard"></i>Daftar Inventaris Jalan, Irigasi dan Jaringan</a></li>
			<li class="active">Rincian</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_jalan" method="post" action="<?= $form_action?>">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('inventaris/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
						<a href="<?= site_url() ?>inventaris_jalan" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Jalan, Irigasi dan Jaringan</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-sm-3 control-label " style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
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
												<option value="<?= $main->kondisi; ?>"> <?= $main->kondisi; ?> </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kontruksi">Kontruksi</label>
										<div class="col-sm-8">
											<textarea class="form-control input-sm" name="kontruksi" id="kontruksi" disabled><?= $main->kontruksi; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label " style="text-align:left;" for="panjang">Panjang</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input value="<?= (!empty($main->panjang) ? $main->panjang : '0'); ?>" class="form-control input-sm" id="panjang" name="panjang" type="number" disabled/>
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="lebar">Lebar</label>
										<div class="col-sm-4">
										<div class="input-group">
											<input type="number" value="<?= (!empty($main->lebar) ? $main->lebar : '0'); ?>"  class="form-control input-sm" id="lebar" name="lebar" type="number" disabled/>
											<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M</span>
										</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label " style="text-align:left;" for="luas">Luas</label>
										<div class="col-sm-4">
											<div class="input-group">
												<input type="number" value="<?= (!empty($main->luas) ? $main->luas : '0'); ?>"  class="form-control input-sm" id="luas" name="luas" type="number" disabled/>
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
										<label class="col-sm-3 control-label " style="text-align:left;" for="no_bangunan">Nomor Kepemilikan</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= (!empty($main->no_dokument) ? $main->no_dokument : '-'); ?>" class="form-control input-sm" name="no_bangunan" id="no_bangunan" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label " style="text-align:left;" for="tanggal_bangunan">Tanggal Dokumen Kepemilikan</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= (strtotime($main->tanggal_dokument != '0000-00-00') ? '-' : date('d M Y', strtotime($main->tanggal_dokument)) ); ?>" class="form-control input-sm" name="tanggal_bangunan" id="tanggal_bangunan" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label " style="text-align:left;" for="status_tanah">Status Tanah</label>
										<div class="col-sm-8">
											<select name="status_tanah" id="status_tanah" class="form-control input-sm" disabled>
												<option value="<?= $main->status_tanah; ?>"> <?= $main->status_tanah; ?> </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label " style="text-align:left;" for="kode_tanah">Nomor Kode Tanah</label>
										<div class="col-sm-8">
											<input maxlength="50"value="<?= (!empty($main->kode_tanah) ? $main->kode_tanah : '-'); ?>"  class="form-control input-sm" name="kode_tanah" id="kode_tanah" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label " style="text-align:left;" for="asal_usul">Asal Usul </label>
										<div class="col-sm-4">
											<select name="asal_usul" id="asal_usul" class="form-control input-sm" disabled>
												<option value="<?= $main->asal; ?>"> <?= $main->asal; ?> </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="harga">Harga</label>
										<div class="col-sm-4">
											<div class="input-group">
												<span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Rp</span>
												<input type="text"  value="<?= number_format($main->harga,0,".","."); ?>" class="form-control number input-sm" id="harga" name="harga" disabled/>
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

