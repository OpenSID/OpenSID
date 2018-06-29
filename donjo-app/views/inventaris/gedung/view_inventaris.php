<div class="panel">
	<div class="panel-body">
		<section class="content">
			<div class='box box-default'>
				<div class='box-header with-border'>
					<h4 class='box-title'>View -
						<small>Data Inventaris Gedung dan Bangunan Desa</small>
					</h4>
					<hr>
				</div>
				<div class='box-body'>
					<div class="form">
						<form class="form-horizontal" id="form_gedung" name="form_gedung" method="post" action="">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->nama_barang; ?>" class="form-control" name="nama_barang" id="nama_barang" type="text" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control" name="kode_barang" id="kode_barang" type="text" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->register; ?>" class="form-control" name="nomor_register" id="nomor_register" type="text" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kondisi">Kondisi Bangunan</label>
									<div class="col-sm-4">
										<select name="kondisi" id="kondisi" class="form-control" disabled>
											<option value="<?= $main->kondisi_bangunan; ?>"><?= $main->kondisi_bangunan; ?></option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="tingkat">Bangunan Bertingkat</label>
									<div class="col-sm-6">
										<div class="input-group">
											<input type="number" value="<?= $main->kontruksi_bertingkat; ?>" class="form-control" id="tingkat" name="tingkat" type="number" disabled/>
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">Lantai</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kontruksi">Kontruksi Beton</label>
									<div class="col-sm-4">
										<select name="kontruksi" id="kontruksi" class="form-control"disabled>
											<option value="<?= $main->kontruksi_beton; ?>"><?= $main->kontruksi_beton; ?></option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="luas_bangunan">Luas Bangunan</label>
									<div class="col-sm-4">
										<div class="input-group">
											<input type="number" value="<?= $main->luas_bangunan; ?>" class="form-control" id="luas_bangunan" name="luas_bangunan" type="number" disabled/>
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="alamat">Letak / Lokasi </label>
									<div class="col-sm-9">
										<textarea class="form-control" name="alamat" id="alamat" disabled><?= $main->letak; ?></textarea>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="no_bangunan">Nomor Bangunan</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="no_bangunan" id="no_bangunan" type="text" disabled value="<?= (!empty($main->no_dokument) ? $main->no_dokument : '-'); ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="tanggal_bangunan">Tanggal Dokumen Bangunan</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="tanggal_bangunan" id="tanggal_bangunan" type="text" disabled value="<?= date('d M Y', strtotime($main->tanggal_dokument)); ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="status_tanah">Status Tanah</label>
									<div class="col-sm-9">
										<select name="status_tanah" id="status_tanah" class="form-control" disabled>
											<option value="<?= $main->status_tanah; ?>"><?= $main->status_tanah; ?></option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah </label>
									<div class="col-sm-4">
										<div class="input-group">
											<input type="number" class="form-control" id="luas_tanah" name="luas_tanah" type="number" value="<?= $main->luas; ?>" disabled/>
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kode_tanah">Nomor Kode Tanah</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="kode_tanah" id="kode_tanah" type="text" value="<?= (!empty($main->kode_tanah) ? $main->kode_tanah : '-'); ?>" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="asal_usul">Asal Usul </label>
									<div class="col-sm-9">
										<select name="asal_usul" id="asal_usul" class="form-control" disabled>
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
									<label class="col-sm-2 control-label" style="text-align:left;" for="harga">Harga</label>
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">Rp</span>
											<input type="text" class="form-control" id="harga" name="harga" type="text" value="<?= number_format($main->harga,0,".","."); ?>" disabled/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
									<div class="col-sm-9">
										<textarea rows="5" class="form-control" name="keterangan" id="keterangan" disabled><?= $main->keterangan; ?></textarea>
									</div>
								</div>
							</div>
							<div class="pull-right" >
								<a href="<?= site_url() ?>inventaris_gedung" class="btn btn-default save"
									   id="btn_batal" name="yt1" type="button"/>Kembali</a>
							</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
</div>

<script>
$(document).ready(function()
{
	$("#form_gedung").validate(
	{
		submitHandler: function(form)
		{
			var formInput = new FormData($(form));
			formInput.append('nama_barang', $('#nama_barang').val());
			formInput.append('kode_barang', $('#kode_barang').val());
			formInput.append('register', $('#nomor_register').val());
			formInput.append('kondisi', $('#kondisi').val());
			formInput.append('tingkat', $('#tingkat').val());
			formInput.append('kontruksi', $('#kontruksi').val());
			formInput.append('luas_bangunan', $('#luas_bangunan').val());
			formInput.append('alamat', $('#alamat').val());
			formInput.append('no_bangunan', $('#no_bangunan').val());
			formInput.append('tanggal_bangunan', $('#tanggal_bangunan').val());
			formInput.append('status_tanah', $('#status_tanah').val());
			formInput.append('luas_tanah', $('#luas_tanah').val());
			formInput.append('kode_tanah', $('#kode_tanah').val());
			formInput.append('asal', $('#asal_usul').val());
			formInput.append('harga', $('#harga').val());
			formInput.append('keterangan', $('#keterangan').val());

			$.ajax(
			{
				url: '<?= site_url("api_inventaris_gedung/add"); ?>',
				method: 'post',
				dataType: 'json',
				data: formInput,
				contentType: false,
				processData: false,
				success: function()
				{
					swal(
					{
						title: 'Sukses!',
						text: 'Berhasil Menyimpan',
						type: 'success'
					});
					setTimeout(function()
					{
						window.location.href = '<?= site_url("inventaris_gedung"); ?>';
					}, 2000)
				},
				error: function(err)
				{
					console.log('error',err);
				},
			});
		}
	});

});

</script>