<div class="panel">
	<div class="panel-body">
		<section class="content">
			<div class='box box-default'>
				<div class='box-header with-border'>
					<h4 class='box-title'>Tambah -
						<small>Data Inventaris Tanah Desa</small>
					</h4>
					<hr>
				</div>
				<div class='box-body'>
					<div class="form">
						<form class="form-horizontal" id="form_tanah" name="form_tanah" method="post" action="">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="nama_barang">Nama Barang</label>
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
									<label class="col-sm-2 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah</label>
									<div class="col-sm-4">
										<div class="input-group">
											<input type="number" class="form-control" id="luas_tanah" name="luas_tanah" type="text" required/>
											<span class="input-group-addon" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="tahun">Tahun Pengadaan </label>
									<div class="col-sm-9">
										<select name="tahun" id="tahun" class="form-control">
											<?php for ($i=date("Y"); $i>=1980; $i--): ?>
												<option value="<?= $i ?>"><?= $i ?></option>
											<?php endfor; ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="alamat">Letak / Alamat </label>
									<div class="col-sm-9">
										<textarea class="form-control" name="alamat" id="alamat" required></textarea>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="hak_tanah">Hak Tanah </label>
									<div class="col-sm-4">
										<select name="hak_tanah" id="hak_tanah" class="form-control">
											<option value="Hak Pakai">Hak Pakai</option>
											<option value="Hak Pengelolaan">Hak Pengelolaan</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="tanggal_sertifikat">Tanggal Sertifikat</label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="tanggal_sertifikat" id="tanggal_sertifikat" type="date" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="nomor_sertifikat">Nomor Sertifikat </label>
									<div class="col-sm-9">
										<input maxlength="50" class="form-control" name="nomor_sertifikat" id="nomor_sertifikat" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="penggunaan">Penggunaan </label>
									<div class="col-sm-9">
										<select name="penggunaan" id="penggunaan" class="form-control" required>
											<option value="">-- Pilih Kegunaan Lahan --</option>
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
									<label class="col-sm-2 control-label required" style="text-align:left;" for="asal_usul">Asal Usul </label>
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
										<textarea rows="5" class="form-control" name="keterangan" id="keterangan"></textarea>
									</div>
								</div>
							</div>
							<div class="pull-right" >
								<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Simpan</button>
								<a href="<?= site_url() ?>inventaris_tanah" class="btn btn-default save"
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
	$("#form_tanah").validate(
	{
		submitHandler: function(form)
		{
		  var formInput = new FormData($(form));
		  formInput.append('nama_barang', $('#nama_barang').val());
		  formInput.append('kode_barang', $('#kode_barang').val());
		  formInput.append('register', $('#nomor_register').val());
		  formInput.append('luas', $('#luas_tanah').val());
		  formInput.append('tahun_pengadaan', $('#tahun').val());
		  formInput.append('letak', $('#alamat').val());
		  formInput.append('hak', $('#hak_tanah').val());
		  formInput.append('no_sertifikat', $('#nomor_sertifikat').val());
		  formInput.append('tanggal_sertifikat', $('#tanggal_sertifikat').val());
		  formInput.append('penggunaan', $('#penggunaan').val());
		  formInput.append('asal', $('#asal_usul').val());
		  formInput.append('harga', $('#harga').val());
		  formInput.append('keterangan', $('#keterangan').val());

		  $.ajax(
		  {
			  url: '<?= site_url("api_inventaris_tanah/add"); ?>',
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
						window.location.href = '<?= site_url("inventaris_tanah"); ?>';
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