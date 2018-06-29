<div class="panel">
	<div class="panel-body">
		<section class="content">
			<div class='box box-default'>
				<div class='box-header with-border'>
					<h4 class='box-title'>Tambah -
						<small>Data Inventaris Asset Tetap Lainnya</small>
					</h4>
					<hr>
				</div>
				<div class='box-body'>
					<div class="form">
						<form class="form-horizontal" id="form_update_asset" name="form_update_asset" method="post" action="">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->id; ?>" class="form-control" name="id" id="id" type="hidden"/>
										<input maxlength="50" value="<?= $main->nama_barang; ?>" class="form-control" name="nama_barang" id="nama_barang" type="text" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control" name="kode_barang" id="kode_barang" type="text" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->register; ?>" class="form-control" name="nomor_register" id="nomor_register" type="text" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="jenis_asset">Jenis Asset</label>
									<div class="col-sm-6">
										<select name="jenis_asset" id="jenis_asset" class="form-control" required>
											<option value="<?= $main->jenis; ?>"><?= $main->jenis; ?></option>
											<option value="Buku">Buku</option>
											<option value="Barang Kesenian">Barang Kesenian</option>
											<option value="Hewan Ternak">Hewan Ternak</option>
											<option value="Tumbuhan">Tumbuhan</option>
										</select>
									</div>
								</div>
								<div class="form-group judul">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="judul">Judul dan Pencipta Buku</label>
									<div class="col-sm-9">
										<input class="form-control" value="<?= $main->judul_buku; ?>" id="judul" name="judul" type="text"/>
									</div>
								</div>
								<div class="form-group spesifikasi">
									<label class="col-sm-2 control-label" style="text-align:left;" for="spesifikasi">Spesifikasi Buku</label>
									<div class="col-sm-9">
										<input class="form-control" value="<?= $main->spesifikasi_buku; ?>" id="spesifikasi" name="spesifikasi" type="text"/>
									</div>
								</div>
								<div class="form-group asal_kesenian">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="asal_kesenian">Asal Daerah Kesenian</label>
									<div class="col-sm-9">
										<input class="form-control" value="<?= $main->asal_daerah; ?>" id="asal_kesenian" name="asal_kesenian" type="text" />
									</div>
								</div>
								<div class="form-group pencipta_kesenian">
									<label class="col-sm-2 control-label" style="text-align:left;" for="pencipta_kesenian">Pencipta Kesenian </label>
									<div class="col-sm-9">
										<input class="form-control" value="<?= $main->pencipta; ?>" id="pencipta_kesenian" name="pencipta_kesenian" type="text"/>
									</div>
								</div>
								<div class="form-group bahan_kesenian">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="bahan_kesenian">Bahan Kesenian</label>
									<div class="col-sm-9">
										<input class="form-control" value="<?= $main->bahan; ?>" id="bahan_kesenian" name="bahan_kesenian" type="text"/>
									</div>
								</div>
								<div class="form-group jenis_hewan">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="jenis_hewan">Jenis Hewan Ternak</label>
									<div class="col-sm-9">
										<input class="form-control" value="<?= $main->jenis_hewan; ?>" id="jenis_hewan" name="jenis_hewan" type="text"/>
									</div>
								</div>
								<div class="form-group ukuran_hewan">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="ukuran_hewan">Ukuran Hewan Ternak</label>
									<div class="col-sm-4">
										<div class="input-group">
											<input class="form-control" value="<?= $main->ukuran_hewan; ?>" id="ukuran_hewan" name="ukuran_hewan" type="number"/>
											<span class="input-group-addon" id="ukuran_hewan-addon">Kg</span>
										</div>
									</div>
								</div>
								<div class="form-group jenis_tumbuhan">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="jenis_tumbuhan">Jenis Tumbuhan</label>
									<div class="col-sm-9">
										<input class="form-control" value="<?= $main->jenis_tumbuhan; ?>" id="jenis_tumbuhan" name="jenis_tumbuhan" type="text"/>
									</div>
								</div>
								<div class="form-group ukuran_tumbuhan">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="ukuran_tumbuhan">Ukuran Tumbuhan</label>
									<div class="col-sm-4">
										<div class="input-group">
											<input class="form-control" value="<?= $main->ukuran_tumbuhan; ?>" id="ukuran_tumbuhan" name="ukuran_tumbuhan" type="number"/>
											<span class="input-group-addon" id="ukuran_tumbuhan">M</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="jumlah">Jumlah</label>
									<div class="col-sm-9">
										<input class="form-control" value="<?= $main->jumlah; ?>" id="jumlah" name="jumlah" type="number" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="tahun">Tahun Pembelian </label>
									<div class="col-sm-9">
										<select name="tahun" id="tahun" class="form-control" required>
											<option value="<?= $main->tahun_pengadaan; ?>"><?= $main->tahun_pengadaan; ?></option>
											<?php for ($i=date("Y"); $i>=1980; $i--): ?>
												<option value="<?= $i ?>"><?= $i ?></option>
											<?php endfor; ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label required" style="text-align:left;" for="asal_usul">Asal Usul </label>
									<div class="col-sm-9">
										<select name="asal_usul" id="asal_usul" class="form-control" required>
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
											<input type="number"  value="<?= $main->harga; ?>" class="form-control" id="harga" name="harga" type="text" required/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
									<div class="col-sm-9">
										<textarea rows="5" class="form-control" name="keterangan" id="keterangan" required><?= $main->keterangan; ?></textarea>
									</div>
								</div>
							</div>
							<div class="pull-right" >
								<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Simpan</button>

								<a href="<?= site_url() ?>inventaris_asset" class="btn btn-default save"
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
		if($("#jenis_asset").val() == "Buku")
		{
			$(".judul").show();
			$(".spesifikasi").show();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		}
		else if ($("#jenis_asset").val() == "Barang Kesenian")
		{
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").show();
			$(".pencipta_kesenian").show();
			$(".bahan_kesenian").show();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		}
		else if ($("#jenis_asset").val() == "Hewan Ternak")
		{
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").show();
			$(".ukuran_hewan").show();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		}
		else if ($("#jenis_asset").val() == "Tumbuhan")
		{
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").show();
			$(".ukuran_tumbuhan").show();
		}

	$("#jenis_asset").change(function()
	{
		if ($("#jenis_asset").val() == "Buku")
		{
			$(".judul").show();
			$(".spesifikasi").show();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		}
		else if ($("#jenis_asset").val() == "Barang Kesenian")
		{
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").show();
			$(".pencipta_kesenian").show();
			$(".bahan_kesenian").show();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		}
		else if ($("#jenis_asset").val() == "Hewan Ternak")
		{
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").show();
			$(".ukuran_hewan").show();
			$(".jenis_tumbuhan").hide();
			$(".ukuran_tumbuhan").hide();
		}
		else if ($("#jenis_asset").val() == "Tumbuhan")
		{
			$(".judul").hide();
			$(".spesifikasi").hide();
			$(".asal_kesenian").hide();
			$(".pencipta_kesenian").hide();
			$(".bahan_kesenian").hide();
			$(".jenis_hewan").hide();
			$(".ukuran_hewan").hide();
			$(".jenis_tumbuhan").show();
			$(".ukuran_tumbuhan").show();
		}
	});
});


$(document).ready(function()
{
	$("#form_update_asset").validate(
	{
		submitHandler: function(form)
		{
			var formInput = new FormData($(form));
			formInput.append('nama_barang', $('#nama_barang').val());
			formInput.append('kode_barang', $('#kode_barang').val());
			formInput.append('nomor_register', $('#nomor_register').val());
			formInput.append('jenis_asset', $('#jenis_asset').val());
			formInput.append('judul', $('#judul').val());
			formInput.append('spesifikasi', $('#spesifikasi').val());
			formInput.append('asal_kesenian', $('#asal_kesenian').val());
			formInput.append('pencipta_kesenian', $('#pencipta_kesenian').val());
			formInput.append('bahan_kesenian', $('#bahan_kesenian').val());
			formInput.append('jenis_hewan', $('#jenis_hewan').val());
			formInput.append('ukuran_hewan', $('#ukuran_hewan').val());
			formInput.append('jenis_tumbuhan', $('#jenis_tumbuhan').val());
			formInput.append('ukuran_tumbuhan', $('#ukuran_tumbuhan').val());
			formInput.append('jumlah', $('#jumlah').val());
			formInput.append('tahun', $('#tahun').val());
			formInput.append('asal_usul', $('#asal_usul').val());
			formInput.append('harga', $('#harga').val());
			formInput.append('keterangan', $('#keterangan').val());
		$.ajax(
		{
			url: '<?= site_url("api_inventaris_asset/update"); ?>' + '/' + $('#id').val(),
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
						text: 'Berhasil Mengubah Data',
						type: 'success'
					});
					setTimeout(function()
					{
						window.location.href = '<?= site_url("inventaris_asset"); ?>';
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