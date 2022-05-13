<div class="content-wrapper">
	<section class="content-header">
		<h1>Ubah Data Mutasi Inventaris Jalan, Irigasi dan Jaringan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('inventaris_jalan/mutasi') ?>">Daftar Mutasi Inventaris Jalan, Irigasi dan Jaringan</a></li>
			<li class="active">Ubah Data Mutasi</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form class="form-horizontal" id="validasi" name="form_mutasi_jalan" method="post" action="<?= site_url("api_inventaris_jalan/update_mutasi/{$main->id}"); ?>">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('inventaris/menu_kiri.php') ?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('inventaris_jalan/mutasi') ?>" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Mutasi Inventaris Jalan, Irigasi dan Jaringan</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label required" style="text-align:left;" for="nama_barang">Nama Barang</label>
								<div class="col-sm-8">
									<input type="hidden" name="id" id="id" value="<?= $main->id; ?>">
									<input type="hidden" name="id_asset" id="id_asset" value="<?= $main->id_inventaris_jalan; ?>">
									<input maxlength="50" value="<?= $main->nama_barang; ?>" class="form-control input-sm required" name="nama_barang" id="nama_barang" type="text" disabled />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
								<div class="col-sm-8">
									<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control input-sm required" name="kode_barang" id="kode_barang" type="text" disabled />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
								<div class="col-sm-8">
									<input maxlength="50" value="<?= $main->register; ?>" class="form-control input-sm required" name="kode_barang" id="kode_barang" type="text" disabled />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="mutasi">Status Mutasi</label>
								<div class="col-sm-4">
									<select name="status_mutasi" id="status" class="form-control input-sm required">
										<option value="Baik" <?= selected($main->status_mutasi, 'Baik') ?>>Baik</option>
										<option value="Rusak" <?= selected($main->status_mutasi, 'Rusak') ?>>Rusak</option>
										<option value="Diperbaiki" <?= selected($main->status_mutasi, 'Diperbaiki') ?>>Diperbaiki</option>
										<option value="Hapus" <?= selected($main->status_mutasi, 'Hapus') ?>>Penghapusan</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="mutasi">Jenis Mutasi </label>
								<div class="col-sm-4">
									<select name="mutasi" id="mutasi" class="form-control input-sm">
										<optgroup label="Penghapusan">
											<option value="Rusak" <?= selected($main->jenis_mutasi, 'Rusak') ?>>Status Rusak</option>
										</optgroup>
										<optgroup label="Disumbangkan">
											<option value="Masih Baik Disumbangkan" <?= selected($main->jenis_mutasi, 'Masih Baik Disumbangkan') ?>>Masih Baik</option>
											<option value="Barang Rusak Disumbangkan" <?= selected($main->jenis_mutasi, 'Barang Rusak Disumbangkan') ?>>Rusak</option>
										</optgroup>
										<optgroup label="Jual">
											<option value="Masih Baik Dijual" <?= selected($main->jenis_mutasi, 'Masih Baik Dijual') ?>>Masih Baik</option>
											<option value="Barang Rusak Dijual" <?= selected($main->jenis_mutasi, 'Barang Rusak Dijual') ?>>Rusak</option>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group disumbangkan">
								<label class="col-sm-3 control-label" style="text-align:left;" for="sumbangkan">Disumbangkan ke-</label>
								<div class="col-sm-8">
									<input maxlength="50" class="form-control input-sm" name="sumbangkan" id="sumbangkan" type="text" value="<?= $main->sumbangkan; ?>" />
								</div>
							</div>
							<div class="form-group harga_jual">
								<label class="col-sm-3 control-label " style="text-align:left;" for="harga_jual">Harga Penjualan</label>
								<div class="col-sm-4">
									<input maxlength="50" class="form-control input-sm number" name="harga_jual" id="harga_jual" type="text" value="<?= $main->harga_jual; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tahun Pengadaan </label>
								<div class="col-sm-4">
									<select name="tahun" id="tahun" class="form-control input-sm required" disabled>
										<option value="<?= $main->tahun_pengadaan; ?>"><?= date('d M Y', strtotime($main->tanggal_dokument)); ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label required" style="text-align:left;" for="tahun_mutasi">Tanggal Mutasi</label>
								<div class="col-sm-4">
									<input type="date" maxlength="50" class="form-control input-sm required" name="tahun_mutasi" id="tahun_mutasi" value="<?= $main->tahun_mutasi; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
								<div class="col-sm-8">
									<textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan"><?= $main->keterangan; ?></textarea>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
							<button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

<?php $this->load->view('inventaris/js_mutasi') ?>