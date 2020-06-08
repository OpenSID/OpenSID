<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label"><?=$individu['judul_nik']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['nik'];?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Nama <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['nama'];?>">
	</div>
</div>
<?php if ($detail["sasaran"] == 2): ?>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Nomer KK</label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['no_kk'];?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Nama Kepala Keluarga</label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['nama_kk'];?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Status KK</label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['hubungan'];?>">
	</div>
</div>
<?php endif; ?>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Alamat <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['alamat_wilayah'];?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Tempat Tanggal Lahir <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['tempatlahir']?>, <?= tgl_indo($individu['tanggallahir'])?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Umur <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['umur']?> Tahun">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Pendidikan <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['pendidikan']?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-3 control-label">Warga Negara / Agama <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['warganegara']?> / <?= $individu['agama']?>">
	</div>
</div>
