<div class="panel">
	<div class="panel-body">
		<section class="content">
			<div class='box box-default'>
				<div class='box-header with-border'>
					<h4 class='box-title'>View -
						<small>Data Inventaris Jalan, Irigasi, dan Jaringan Desa</small>
					</h4>
					<hr>
				</div>
				<div class='box-body'>
					<div class="form">
						<form class="form-horizontal" id="form_jalan" name="form_jalan" method="post" action="">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
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
											<option value="<?= $main->kondisi; ?>"> <?= $main->kondisi; ?> </option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kontruksi">Kontruksi</label>
									<div class="col-sm-9">
										<textarea class="form-control" name="kontruksi" id="kontruksi" disabled><?= $main->kontruksi; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="panjang">Panjang</label>
									<div class="col-sm-6">
										<div class="input-group">
											<input value="<?= (!empty($main->panjang) ? $main->panjang : '0'); ?>" class="form-control" id="panjang" name="panjang" type="number" disabled/>
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">Km</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="lebar">Lebar</label>
									<div class="col-sm-6">
									<div class="input-group">
										<input type="number" value="<?= (!empty($main->lebar) ? $main->lebar : '0'); ?>"  class="form-control" id="lebar" name="lebar" type="number" disabled/>
										<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">M</span>
									</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="luas">Luas</label>
									<div class="col-sm-6">
										<div class="input-group">
											<input type="number" value="<?= (!empty($main->luas) ? $main->luas : '0'); ?>"  class="form-control" id="luas" name="luas" type="number" disabled/>
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">

								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="alamat">Letak / Lokasi </label>
									<div class="col-sm-9">
										<textarea class="form-control" name="alamat" id="alamat" disabled><?= $main->letak; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="no_bangunan">Nomor Kepemilikan</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= (!empty($main->no_dokument) ? $main->no_dokument : '-'); ?>" class="form-control" name="no_bangunan" id="no_bangunan" type="text" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="tanggal_bangunan">Tanggal Dokumen Kepemilikan</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= (strtotime($main->tanggal_dokument != '0000-00-00') ? '-' : date('d M Y', strtotime($main->tanggal_dokument)) ); ?>" class="form-control" name="tanggal_bangunan" id="tanggal_bangunan" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="status_tanah">Status Tanah</label>
									<div class="col-sm-9">
										<select name="status_tanah" id="status_tanah" class="form-control" disabled>
											<option value="<?= $main->status_tanah; ?>"> <?= $main->status_tanah; ?> </option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="kode_tanah">Nomor Kode Tanah</label>
									<div class="col-sm-9">
										<input maxlength="50"value="<?= (!empty($main->kode_tanah) ? $main->kode_tanah : '-'); ?>"  class="form-control" name="kode_tanah" id="kode_tanah" type="text" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="asal_usul">Asal Usul </label>
									<div class="col-sm-9">
										<select name="asal_usul" id="asal_usul" class="form-control" disabled>
											<option value="<?= $main->asal; ?>"> <?= $main->asal; ?> </option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="harga">Harga</label>
									<div class="col-sm-6">
										<div class="input-group">
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">Rp</span>
											<input type="text"  value="<?= number_format($main->harga,0,".","."); ?>" class="form-control" id="harga" name="harga" type="text" disabled/>
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
								<a href="<?= site_url() ?>inventaris_jalan" class="btn btn-default save"
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
	$("#form_jalan").validate(
	{
		submitHandler: function(form)
		{
			var formInput = new FormData($(form));
			formInput.append('nama_barang', $('#nama_barang').val());
			formInput.append('kode_barang', $('#kode_barang').val());
			formInput.append('nomor_register', $('#nomor_register').val());
			formInput.append('kondisi', $('#kondisi').val());
			formInput.append('kontruksi', $('#kontruksi').val());
			formInput.append('panjang', $('#panjang').val());
			formInput.append('lebar', $('#lebar').val());
			formInput.append('luas', $('#luas').val());
			formInput.append('alamat', $('#alamat').val());
			formInput.append('no_bangunan', $('#no_bangunan').val());
			formInput.append('tanggal_bangunan', $('#tanggal_bangunan').val());
			formInput.append('status_tanah', $('#status_tanah').val());
			formInput.append('kode_tanah', $('#kode_tanah').val());
			formInput.append('asal', $('#asal_usul').val());
			formInput.append('harga', $('#harga').val());
			formInput.append('keterangan', $('#keterangan').val());

			$.ajax(
			{
				url: '<?= site_url("api_inventaris_jalan/add"); ?>',
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
						window.location.href = '<?= site_url("inventaris_jalan"); ?>';
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