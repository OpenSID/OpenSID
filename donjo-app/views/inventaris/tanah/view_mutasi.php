<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Mutasi Inventaris Tanah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url() ?>inventaris_tanah/mutasi"><i class="fa fa-dashboard"></i>Daftar Mutasi Inventaris Tanah</a></li>
			<li class="active">Rincian Mutasi</li>
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
						<a href="<?= site_url() ?>inventaris_tanah/mutasi" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Mutasi Inventaris Tanah</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="nama_barang">Nama Barang</label>
										<div class="col-sm-8">
											<input type="hidden" name="id_inventaris" id="id_inventaris" value="<?= $main->id; ?>">
											<input maxlength="50" value="<?= $main->nama_barang; ?>"  class="form-control input-sm" name="nama_barang" id="nama_barang" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->kode_barang; ?>"  class="form-control input-sm" name="kode_barang" id="kode_barang" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
										<div class="col-sm-8">
											<input maxlength="50" value="<?= $main->register; ?>"  class="form-control input-sm" name="kode_barang" id="kode_barang" type="text" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="mutasi" require>Jenis Mutasi </label>
										<div class="col-sm-4">
											<select name="mutasi" id="mutasi" class="form-control input-sm" disabled>
												<option value="<?= $main->jenis_mutasi; ?>">   <?= $main->jenis_mutasi;?></option>
												<option value="Rusak">Status Rusak</option>
												<option value="Diperbaiki">Status Diperbaiki</option>
												<optgroup label="Barang Masih Baik">
													<option value="Masih Baik Disumbangkan">Sumbangakan</option>
													<option value="Masih Baik Dijual">Jual</option>
												</optgroup>
												<optgroup label="Barang Sudah Rusak">
													<option value="Barang Rusak Disumbangkan">Sumbangakan</option>
													<option value="Barang Rusak Dijual">Jual</option>
												</optgroup>
											</select>
										</div>
									</div>
									<div class="form-group disumbangkan">
										<label class="col-sm-3 control-label" style="text-align:left;" for="disumbangkan">Disumbangkan ke-</label>
										<div class="col-sm-8">
											<input maxlength="50"  class="form-control input-sm" name="disumbangkan" id="disumbangkan" type="text" value="<?= $main->sumbangkan; ?>" disabled/>
										</div>
									</div>
									<div class="form-group harga_jual">
										<label class="col-sm-3 control-label " style="text-align:left;" for="harga_jual">Harga Penjualan</label>
										<div class="col-sm-4">
											<input maxlength="50"  class="form-control input-sm" name="harga_jual" id="harga_jual" type="text" value="Rp. <?= number_format( $main->harga_jual,0,".","."); ?>" disabled/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tahun Pengadaan </label>
										<div class="col-sm-4">
											<select name="tahun" id="tahun" class="form-control input-sm" disabled>
												<option value="<?= $main->tahun_pengadaan; ?>"><?= $main->tahun_pengadaan; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label required" style="text-align:left;" for="tahun_mutasi">Tahun Mutasi</label>
										<div class="col-sm-4">
											<input maxlength="50" class="form-control input-sm" name="tahun_mutasi" id="tahun_mutasi" value="<?= date('d M Y',strtotime($main->tahun_mutasi));;?>" disabled/>
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

