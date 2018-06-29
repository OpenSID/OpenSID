<div class="panel">
	<div class="panel-body">
		<section class="content">
			<div class='box box-default'>
				<div class='box-header with-border'>
					<h4 class='box-title'>Edit -
						<small>Data Inventaris Peralatan dan Mesin Desa</small>
					</h4>
					<hr>
				</div>
				<div class='box-body'>
					<div class="form">
						<form class="form-horizontal" id="form_update_peralatan" name="form_update_peralatan" method="post" action="">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->id; ?>" class="form-control" name="id" id="id" type="hidden"/>
										<input maxlength="50" value="<?= $main->nama_barang; ?>" class="form-control" name="nama_barang" id="nama_barang" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->kode_barang; ?>" class="form-control" name="kode_barang" id="kode_barang" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= $main->register; ?>" class="form-control" name="nomor_register" id="nomor_register" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="merk">Merk/Type</label>
									<div class="col-sm-9">
										<input type="text" value="<?= $main->merk; ?>" class="form-control" id="merk" name="merk" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="ukuran">Ukuran/CC </label>
									<div class="col-sm-9">
										<textarea class="form-control" name="ukuran" id="ukuran"><?= $main->ukuran; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="bahan">Bahan</label>
									<div class="col-sm-9">
										<textarea class="form-control" name="bahan" id="bahan"><?= $main->bahan; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="tahun">Tahun Pembelian</label>
									<div class="col-sm-9">
										<select name="tahun" id="tahun" class="form-control">
											<option value="<?= $main->tahun_pengadaan; ?>"><?= $main->tahun_pengadaan; ?></option>
											<?php for ($i=date("Y"); $i>=1980; $i--): ?>
												<option value="<?= $i ?>"><?= $i ?></option>
											<?php endfor; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="no_pabrik">Nomor Pabrik</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= (!empty($main->no_pabrik) ? $main->no_pabrik : '-'); ?>" class="form-control" name="no_pabrik" id="no_pabrik" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="no_rangka">Nomor Rangka </label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= (!empty($main->no_rangka) ? $main->no_rangka : '-'); ?>" class="form-control" name="no_rangka" id="no_rangka" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="no_mesin">Nomor Mesin</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= (!empty($main->no_mesin) ? $main->no_mesin : '-'); ?>" class="form-control" name="no_mesin" id="no_mesin" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="no_polisi">Nomor Polisi </label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= (!empty($main->no_polisi) ? $main->no_polisi : '-'); ?>" class="form-control" name="no_polisi" id="no_polisi" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="bpkb">BPKB</label>
									<div class="col-sm-9">
										<input maxlength="50" value="<?= (!empty($main->no_bpkb) ? $main->no_bpkb : '-'); ?>" class="form-control" name="bpkb" id="bpkb" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " style="text-align:left;" for="asal_usul">Asal Usul </label>
									<div class="col-sm-9">
										<select name="asal_usul" id="asal_usul" class="form-control">
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
											<input type="number" value="<?= $main->harga; ?>" class="form-control" id="harga" name="harga" type="text"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
									<div class="col-sm-9">
										<textarea rows="5" class="form-control" name="keterangan" id="keterangan"><?= $main->keterangan; ?></textarea>
									</div>
								</div>
							</div>
							<div class="pull-right" >
								<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Simpan</button>

								<a href="<?= site_url() ?>inventaris_peralatan" class="btn btn-default save"
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
		$("#form_update_peralatan").validate(
		{
			submitHandler: function(form)
			{
				var formInput = new FormData($(form));
				formInput.append('id', $('#id').val());
				formInput.append('nama_barang', $('#nama_barang').val());
				formInput.append('kode_barang', $('#kode_barang').val());
				formInput.append('register', $('#nomor_register').val());
				formInput.append('merk', $('#merk').val());
				formInput.append('ukuran', $('#ukuran').val());
				formInput.append('bahan', $('#bahan').val());
				formInput.append('tahun_pengadaan', $('#tahun').val());
				formInput.append('no_pabrik', $('#no_pabrik').val());
				formInput.append('no_rangka', $('#no_rangka').val());
				formInput.append('no_mesin', $('#no_mesin').val());
				formInput.append('no_polisi', $('#no_polisi').val());
				formInput.append('no_bpkb', $('#bpkb').val());
				formInput.append('asal', $('#asal_usul').val());
				formInput.append('harga', $('#harga').val());
				formInput.append('keterangan', $('#keterangan').val());

				$.ajax(
				{
					url: '<?= site_url("api_inventaris_peralatan/update"); ?>' + '/' + $('#id').val(),
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
							window.location.href = '<?= site_url("inventaris_peralatan"); ?>';
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