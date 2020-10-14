
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Data C-Desa <?=ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('cdesa/clear')?>"> Daftar C-Desa</a></li>
			<li><a href="<?=site_url('cdesa/rincian/'. $cdesa[id])?>"> Rincian C-Desa</a></li>
			<li class="active">Pengelolaan Data C-Desa</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('data_persil/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-body">
						<div class="box-header with-border">
							<a href="<?= site_url('cdesa/rincian/'. $cdesa[id])?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian C-Desa"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian C-Desa</a>
							<?php if ($persil): ?>
								<a href="<?= site_url('cdesa/mutasi/'. $cdesa[id].'/'.$persil['id'])?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian C-Desa"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian Mutasi C-Desa</a>
							<?php endif; ?>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="box-header with-border">
									<h3 class="box-title">Rincian C-Desa</h3>
								</div>
								<div class="box-body">
									<?php if ($pemilik): ?>
										<div class="form-group">
											<label class="col-sm-3 control-label">Nama Pemilik</label>
											<div class="col-sm-8">
												<input  class="form-control input-sm" type="text" placeholder="Nama Pemilik" value="<?= $pemilik["namapemilik"] ?>" disabled >
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">NIK Pemilik</label>
											<div class="col-sm-8">
												<input  class="form-control input-sm" type="text" placeholder="NIK Pemilik" value="<?= $pemilik["nik"] ?>" disabled >
											</div>
										</div>
										<div class="form-group">
											<label for="alamat"  class="col-sm-3 control-label">Alamat Pemilik</label>
											<div class="col-sm-8">
												<textarea  class="form-control input-sm" placeholder="Alamat Pemilik" disabled><?= $pemilik["alamat"] ?></textarea>
											</div>
										</div>
									<?php endif; ?>

									<div class="form-group">
										<label for="c_desa" class="col-sm-3 control-label">Nomor C-DESA</label>
										<div class="col-sm-8">
											<input class="form-control input-sm angka required" type="text" placeholder="Nomor Surat C-DESA" name="c_desa" value="<?= ($cdesa['nomor'])?sprintf("%04s", $cdesa["nomor"]): NULL ?>" disabled >
										</div>
									</div>

									<div class="form-group">
										<label for="nama_kepemilikan" class="col-sm-3 control-label">Nama Pemilik Tertulis di C-Desa</label>
										<div class="col-sm-8">
											<input class="form-control input-sm nama required" type="text" placeholder="Nama pemilik di Surat C-DESA" name="nama_kepemilikan" value="<?= ($cdesa["nama_kepemilikan"])?sprintf("%04s", $cdesa["nama_kepemilikan"]): NULL ?>" disabled >
										</div>
									</div>
								</div>

								<div class="box-header with-border">
									<h3 class="box-title">Tambah Persil</h3>
								</div>
								<div class="panel box box-default">
									<div class="box-header with-border">
										<h4 class="box-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#persil">Persil</a>
										</h4>
									</div>
									<div id="persil" class="panel-collapse">
										<div class="box-body">
											<form action="" id="main" name="main" method="POST" class="form-horizontal">
												<div class="form-group" >
									  			<label class="col-sm-3 control-label" for="id_pend">Nomor Persil</label>
													<div class="col-sm-8">
														<select class="form-control select2 input-sm" name="id_persil"  onchange="formAction('main')" style="width:100%" >
															<option value='' selected="selected">-- Pilih Nomor Persil --</option>
															<?php foreach ($list_persil as $data): ?>
																<option value="<?= $data['id']?>" <?php selected($persil['id'], $data['id']); ?>> <?= $data['nomor']." : ".$data['nomor_urut_bidang']." - ".$data['lokasi']?></option>
															<?php endforeach;?>
														</select>
													</div>
												</div>
												<?php if (empty($persil)): ?>
													<div class="form-group" >
										  			<label class="col-sm-3 control-label">Kalau persil belum ada</label>
														<div class="col-sm-8">
															<a href="<?=site_url("data_persil/form/0/$cdesa[id]")?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Persil">
																<i class="fa fa-plus"></i>Tambah Persil
															</a>
														</div>
													</div>
												<?php else: ?>
													<div class="form-group">
														<label for="no_persil" class="col-sm-3 control-label">Nomor Persil : Nomor Urut Bidang</label>
														<div class="col-sm-8">
															<input name="no_persil" class="form-control input-sm angka required" type="text" disabled value="<?= $persil["nomor"].' : '.$persil['nomor_urut_bidang'] ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tipe" class="col-sm-3 control-label">Tipe Tanah</label>
														<div class="col-sm-8">
															<input name="tipe" class="form-control input-sm angka required" type="text" disabled value="<?= $persil["tipe"] ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="kelas" class="col-sm-3 control-label">Kelas Tanah</label>
														<div class="col-sm-8">
															<input name="kelas" class="form-control input-sm required" type="text" disabled value="<?= $persil["kode"] ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="luas_persil" class="col-sm-3 control-label">Luas Persil Keseluruhan (M2)</label>
														<div class="col-sm-8">
															<input name="luas_persil" class="form-control input-sm angka required" type="text" disabled value="<?= $persil["luas_persil"] ?>">
														</div>
													</div>
													<div class="form-group ">
														<label for="alamat" class="col-sm-3 control-label">Lokasi Tanah</label>
														<div class="col-sm-8">
															<input name="alamat" class="form-control input-sm angka required" type="text" disabled value="<?= $persil["alamat"] ?>">
														</div>
													</div>
												<?php endif; ?>
											</form>
										</div>
									</div>
								</div>

								<?php if ($persil): ?>
									<div class="box-header with-border">
										<h3 class="box-title">Tambah Mutasi</h3>
									</div>

									<?php if (empty($persil['cdesa_awal']) and empty($mutasi)): ?>
										<div id="cdesa_awal">
											<div class="box-body">
												<a href="<?= site_url('cdesa/awal_persil/'. $cdesa[id] .'/' .$persil['id'])?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block col-sm-2" title="Kembali Ke Rincian C-Desa"><i class="fa fa-step-backward"></i>C-Desa Awal</a>
												<span style="padding-left: 10px;">Catat C-Desa ini sebagai pemilik awal keseluruhan persil <?= $persil["nomor"] ?></span>
											</div>
											<div class="box-body">
												<a class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block col-sm-2" title="Kembali Ke Rincian C-Desa" onclick="tambah_mutasi();"><i class="fa fa-plus"></i>Tambah Mutasi</a>
												<span style="padding-left: 10px;">Tambah mutasi C-Desa <?= $cdesa['nomor']?> untuk persil <?= $persil['nomor']?></span>
											</div>
										</div>
									<?php endif; ?>

									<div id="mutasi_persil" <?php empty($persil['cdesa_awal']) and empty($mutasi) and print('style="display: none;"')?>>

										<form name='mainform' action="<?= site_url('cdesa/simpan_mutasi/'.$cdesa['id'].'/'.$mutasi['id'])?>" method="POST"  id="validasi" class="form-horizontal">
											<input name="jenis_pemilik" type="hidden" value="1">
											<input type="hidden" name="nik_lama" value="<?= $pemilik["nik_lama"] ?>"/>
											<input type="hidden" name="nik" value="<?= $pemilik["nik"] ?>"/>
											<input type="hidden" name="id_pend" value="<?= $pemilik["id"] ?>"/>
											<input type="hidden" name="id_persil" value="<?= $persil["id"] ?>"/>

											<?php if ($mutasi['jenis_mutasi'] != 9): ?>
												<div class="box-header with-border">
													<h4 class="box-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#persil">Mutasi - Bidang Tanah</a>
													</h4>
												</div>
												<div id="bidang_persil" class="panel-collapse">
													<div class="box-body">
														<div class="form-group">
															<label for="no_bidang_persil" class="col-sm-3 control-label">Nomor Bidang Mutasi</label>
															<div class="col-sm-4">
																<input name="no_bidang_persil" type="text" class="form-control input-sm digits" placeholder="Nomor urut pecahan bidang persil hasil mutasi" maxlength="2" value="<?= $mutasi["no_bidang_persil"] ?>">
															</div>
														</div>
														<div class="form-group">
															<label for="luas" class="col-sm-3 control-label">Luas Mutasi (M2)</label>
															<div class="col-sm-9">
																<input name="luas" type="text" class="form-control input-sm luas required" placeholder="Luas Mutasi (M2)" value="<?= $mutasi['luas']?>">
															</div>
														</div>
														<div class="form-group">
															<label for="no_objek_pajak" class="col-sm-3 control-label">Nomor Objek Pajak</label>
															<div class="col-sm-8">
																<input class="form-control input-sm angka" type="text" placeholder="Nomor Objek Pajak" name="no_objek_pajak" value="<?= $mutasi["no_objek_pajak"] ?>">
															</div>
														</div>
													</div>
												</div>
												<div class="box-header with-border">
													<h4 class="box-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#mutasi">Mutasi - Sebab Dan Tanggal Perubahan</a>
													</h4>
												</div>
												<div id="mutasi" class="panel-collapse">
													<div class="box-body">
														<div class="form-group">
															<label for="tanggal_mutasi" class="col-sm-3 control-label">Tanggal Perubahan</label>
															<div class="col-sm-4">
																<div class="input-group input-group-sm date">
																	<div class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</div>
																	<input class="form-control input-sm pull-right tgl_indo required" name="tanggal_mutasi" type="text" value="<?= tgl_indo_out($mutasi['tanggal_mutasi'])?>">
																</div>
															</div>
														</div>
														<div class="form-group">
															<label for="jenis_mutasi" class="col-sm-3 control-label required">Sebab Mutasi</label>
															<div class="col-sm-4">
																<select class="form-control input-sm required" name="jenis_mutasi" >
																	<option value>-- Pilih Sebab Mutasi--</option>
																	<?php foreach ($persil_sebab_mutasi as $key => $item): ?>
																		<option value="<?= $item['id'] ?>" <?php selected($key, $mutasi['jenis_mutasi'])?>><?= $item['nama']?></option>
																	<?php endforeach;?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label for="" class="col-sm-3 control-label"></label>
															<div class="col-sm-8">
																<p class="help-block"><code>Gunakan tanda titik (.) untuk bilangan pecahan</code></p>
															</div>
														</div>
														<div class="form-group">
															<label for="cdesa_keluar" class="col-sm-3 control-label">Perolehan Dari</label>
															<div class="col-sm-9">
																<select class="form-control select2 input-sm" id="cdesa_keluar" name="cdesa_keluar">
																	<option value='' selected="selected">-- Pilih C-DESA dari mana bidang persil ini dimutasikan --</option>
																	<?php foreach ($list_cdesa as $data): ?>
																		<option value="<?= $data['id_cdesa']?>" <?php selected($mutasi['cdesa_keluar'], $data['id_cdesa']); ?>> <?= $data['nomor']." - ".$data['namapemilik']?></option>
																	<?php endforeach;?>
																</select>
																<label for="" class="col-sm-3 control-label"></label>
																<div class="form-group">
																<div class="col-sm-9">
																	<span class="help-block"><code>Jika C-Desa tidak ditemukan, bisa dibuat dan ditambahkan belakangan</code></span>
																</div>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
															<div class="col-sm-9">
																<textarea  name="keterangan" class="form-control input-sm" type="text" placeholder="Keterangan" name="ket" ><?= $mutasi['keterangan']?></textarea>
															</div>
														</div>
													</div>
												</div>
											<?php else: ?>
												<div class="box-header with-border">
													<h4 class="box-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#mutasi">Pemilik Awal Persil</a>
													</h4>
												</div>
												<input type="hidden" name="jenis_mutasi" value="<?= $mutasi['jenis_mutasi']?>">
												<div id="pemilik_awal" class="panel-collapse">
													<div class="box-body">
														<div class="form-group">
															<label for="no_objek_pajak" class="col-sm-3 control-label">Nomor Objek Pajak</label>
															<div class="col-sm-8">
																<input class="form-control input-sm angka" type="text" placeholder="Nomor Objek Pajak" name="no_objek_pajak" value="<?= $mutasi["no_objek_pajak"] ?>">
															</div>
														</div>
														<div class="form-group">
															<label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
															<div class="col-sm-9">
																<textarea  name="keterangan" class="form-control input-sm" type="text" placeholder="Keterangan" name="ket" ><?= $mutasi['keterangan']?></textarea>
															</div>
														</div>
												</div>
											<?php endif; ?>

											<div class="box-footer">
												<div class="col-xs-12">
													<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
													<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
												</div>
											</div>

										</form>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	function pilih_lokasi(pilih)
	{
		if (pilih == 1)
		{
			$('#lokasi').val('');
			$('#lokasi').removeClass('required');
			$("#manual").hide();
			$("#pilih").show();
			$('#id_wilayah').addClass('required');
		}
		else
		{
			$('#id_wilayah').val('');
			$('#id_wilayah').trigger('change', true);
			$('#id_wilayah').removeClass('required');
			$("#manual").show();
			$('#lokasi').addClass('required');
			$("#pilih").hide();
		}
	}

	$(document).ready(function(){
		$('#tipe').change(function(){
			var id=$(this).val();
			$.ajax({
				url : "<?=site_url('data_persil/kelasid')?>",
				method : "POST",
				data : {id: id},
				async : true,
				dataType : 'json',
				success: function(data){
					var html = '';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].id+'>'+data[i].kode+' '+data[i].ndesc+'</option>';
					}
					$('#kelas').html(html);
				}
			});
			return false;
		});
		pilih_lokasi(<?= empty($persil['lokasi']) ? 1 : 2?>);
	});

	function tambah_mutasi() {
		$('#cdesa_awal').hide();
		$('#mutasi_persil').show();
		$('#cdesa_keluar').select2(); // Untuk refresh tampilan
	}

</script>

