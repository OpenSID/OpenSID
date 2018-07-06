<div class="panel">
	<div class="panel-body">
		<section class="content">
			<div class='box box-default'>
				<div class='box-header with-border'>
					<h4 class='box-title'>Tambah -
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
										<input maxlength="50" class="form-control" name="nama_barang" id="nama_barang" type="text" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="kode_barang" id="kode_barang" type="text" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="nomor_register" id="nomor_register" type="text" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kondisi">Kondisi Bangunan</label>
									<div class="col-sm-4">
										<select name="kondisi" id="kondisi" class="form-control" required>
											<option value="">-- Pilih Kondisi --</option>
											<option value="Baik">Baik</option>
											<option value="Rusak Ringan">Rusak Ringan</option>
											<option value="Rusak Sedang">Rusak Sedang</option>
											<option value="Rusak Berat">Rusak Berat</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kontruksi">Kontruksi</label>
									<div class="col-sm-9">
										<textarea class="form-control" name="kontruksi" id="kontruksi" required></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="panjang">Panjang</label>
									<div class="col-sm-6">
										<div class="input-group">
											<input type="number" class="form-control" id="panjang" name="panjang" type="number"/>
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">Km</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="lebar">Lebar</label>
									<div class="col-sm-6">
									<div class="input-group">
										<input type="number" class="form-control" id="lebar" name="lebar" type="number"/>
										<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">M</span>
									</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="luas">Luas</label>
									<div class="col-sm-6">
										<div class="input-group">
											<input type="number" class="form-control" id="luas" name="luas" type="number"/>
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="alamat">Letak / Lokasi </label>
									<div class="col-sm-9">
										<textarea class="form-control" name="alamat" id="alamat" required></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="no_bangunan">Nomor Kepemilikan</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="no_bangunan" id="no_bangunan" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="tanggal_bangunan">Tanggal Dokumen Kepemilikan</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="tanggal_bangunan" id="tanggal_bangunan" type="date"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="status_tanah">Status Tanah</label>
									<div class="col-sm-9">
										<select name="status_tanah" id="status_tanah" class="form-control">
											<option value="">-- Pilih Status Tanah --</option>
											<option value="Tanah milik Pemda">Tanah milik Pemda</option>
											<option value="Tanah Negara">Tanah Negara (Tanah yang dikuasai langsung oleh Negara)</option>
											<option value="Tanah Hak Ulayat">Tanah Hak Ulayat (Tanah masyarakat Hukum Adat)</option>
											<option value="Tanah Hak">Tanah Hak (Tanah kepunyaan perorangan atau Badan Hukum)</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="kode_tanah">Nomor Kode Tanah</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="kode_tanah" id="kode_tanah" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="asal_usul">Asal Usul </label>
									<div class="col-sm-9">
										<select name="asal_usul" id="asal_usul" class="form-control" required>
											<option value="">-- Pilih Asal Usul Lahan --</option>
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
											<input type="number" class="form-control" id="harga" name="harga" type="text" required/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
									<div class="col-sm-9">
										<textarea rows="5" class="form-control" name="keterangan" id="keterangan" required></textarea>
									</div>
								</div>
							</div>
							<div class="pull-right" >
								<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Simpan</button>
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
	$("#form_jalan").validate({
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