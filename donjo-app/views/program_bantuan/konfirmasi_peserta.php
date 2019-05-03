
<?php if ($detail["sasaran"] == 1): ?>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Alamat</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['alamat_wilayah'];?>">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Tempat Tanggal Lahir (Umur)</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Pendidikan</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['pendidikan']?>">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Warganegara / Agama</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['warganegara']?> / <?= $individu['agama']?>">
		</div>
	</div>
<?php elseif ($detail["sasaran"] == 2): ?>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Alamat Keluarga</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['alamat_wilayah']; ?>">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Tempat Tanggal Lahir (Umur) KK</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Pendidikan KK</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['pendidikan']?>">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Warganegara / Agama KK</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['warganegara']?> / <?= $individu['agama']?>">
		</div>
	</div>
<?php elseif ($detail["sasaran"] == 4): ?>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Alamat Kepala Rumah Tangga</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['alamat_wilayah']; ?>">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Tempat Tanggal Lahir (Umur) Kepala RTM</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Pendidikan Kepala RTM</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['pendidikan']?>">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Warganegara / Agama Kepala RTM</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['warganegara']?> / <?= $individu['agama']?>">
		</div>
	</div>
<?php elseif ($detail["sasaran"] == 4): ?>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Alamat Ketua Kelompok</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['alamat_wilayah']; ?>">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Tempat Tanggal Lahir (Umur) Ketua Kelompok</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Pendidikan Ketua Kelompok</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['pendidikan']?>">
		</div>
	</div>
  <div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label">Warganegara / Agama Ketua Kelompok</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['warganegara']?> / <?= $individu['agama']?>">
		</div>
	</div>

<?php endif; ?>