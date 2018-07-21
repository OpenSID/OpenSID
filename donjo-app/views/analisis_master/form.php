<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('#subjek_tipe').change(function()
		{
			if ($(this).val()==4):
				$('#idelik').addClass("show").removeClass("hide");
			else:
				$('#idelik').removeClass("show").addClass("hide");
			endif;
		});
	});
</script>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Master Analisis</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa/about')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('analisis_master')?>"> Master Analisis</a></li>
			<li class="active">Pengaturan Master Analisis</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('analisis_master')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Master Analisis</a>
					</div>
					<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="nama">Nama Analisis</label>
										<div class="col-sm-7">
											<input  id="nama" class="form-control input-sm" type="text" placeholder="Nama Analisa" name="nama" value="<?= $analisis_master['nama']?>">
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="nama">Subjek/Unit Analisis</label>
										<div class="col-sm-7 col-lg-4">
											<select class="form-control input-sm" id="subjek_tipe" name="subjek_tipe">
												<option value="1" <?php if ($analisis_master['subjek_tipe'] == '1' OR $analisis_master['subjek_tipe'] == ''):?>selected<?php endif;?>>Penduduk</option>
												<option value="2" <?php if ($analisis_master['subjek_tipe'] == '2'):?>selected<?php endif;?>>Keluarga / KK</option>
												<option value="3" <?php if ($analisis_master['subjek_tipe'] == '3'):?>selected<?php endif;?>>Rumah Tangga</option>
												<option value="4" <?php if ($analisis_master['subjek_tipe'] == '4'):?>selected<?php endif;?>>Kelompok Masyarakat</option>
											</select>
										</div>
									</div>
								</div>
								<div class=" col-sm-12">
									<div class="form-group hide" id="idelik">
										<label class="col-sm-3 control-label" for="nama">Kategori Kelompok</label>
										<div class="col-sm-7 col-lg-4">
											<select class="form-control input-sm" id="id_kelompok" name="id_kelompok" style="width:100%">
												<option value="">--Kategori Kelompok--</option>
												<?php foreach ($list_kelompok AS $data):?>
													 <option value="<?= $data['id']?>" <?php if ($analisis_master['id_kelompok'] == $data['id']):?>selected<?php endif?>><?= $data['kelompok']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="nama">Status Analisis</label>
										<div class="col-sm-7 col-lg-4">
											<select class="form-control input-sm" id="lock" name="lock">
												<option value="1" <?php if ($analisis_master['lock'] == '1' OR $analisis_master['lock'] == ''):?>selected<?php endif;?>>Tidak Terkunci</option>
												<option value="2" <?php if ($analisis_master['lock'] == '2'):?>selected<?php endif;?>> Terkunci</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label">Format Impor Tambahan</label>
										<div class="col-sm-7 col-lg-4">
											<select class="form-control input-sm" id="format_impor" name="format_impor" <?php if ($analisis_master['jenis']==1) echo 'disabled'?>>
												<option value="">--Pilih Format Impor--</option>
												<?php foreach ($list_format_impor AS $key => $nama):?>
													<option value="<?= $key?>" <?php if ($analisis_master['format_impor'] == $key):?>selected<?php endif?>><?= $nama?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="kepala_lama">Rumus Penilaian Analisis</label>
										<div class="col-sm-7">
											<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;margin-bottom: 10px;">
												<code>Sigma (Bobot (indikator) x Nilai (parameter)) / Bilangan Pembagi</code>
											</P>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="pembagi">Bilangan Pembagi</label>
										<div class="col-sm-7">
											<input  id="pembagi" class="form-control input-sm" type="text" placeholder="Bilangan Pembagi" name="pembagi" value="<?= $analisis_master['pembagi']?>">
											<p class="help-block"><code>Untuk tanda koma "," gunakan tanda titik "." sebagai substitusinya</code></p>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="nama">Analisis Terhubung</label>
										<div class="col-sm-7 col-lg-4">
											<select class="form-control input-sm" id="id_child" name="id_child">
												<option value="">-- Silakan Masukan Analisis Terhubung--</option>
												<?php foreach ($list_analisis AS $data):?>
													<option value="<?= $data['id']?>" <?php if ($analisis_master['id_child'] == $data['id']):?>selected<?php endif?>><?= $data['nama']?></option>
											 	<?php endforeach;?>
											</select>
											<p class="help-block"><code>Kosongkan jika tidak ada Analisis yang terhubung</code></p>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="nama">Deskripsi Analisis</label>
										<div class="col-sm-7">
											<textarea id="deskripsi" class="form-control input-sm" placeholder="Deskripsi Analisis" name="deskripsi"><?= $analisis_master['deskripsi']?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-xs-12">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
