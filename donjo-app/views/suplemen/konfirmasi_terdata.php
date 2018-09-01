<?php if ($suplemen["sasaran"] == 1): ?>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Tempat  Tanggal Lahir / Umur</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['tempatlahir']?>" disabled="">
		</div>
		<div class="col-sm-2">
			<input class="form-control input-sm" type="text" value="<?= tgl_indo($individu['tanggallahir'])?>" disabled="">
		</div>
		<div class="col-sm-2">
			<input class="form-control input-sm" type="text" value="<?= $individu['umur']?> Tahun" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Alamat</label>
		<div class="col-sm-8">
			<input class="form-control input-sm" type="text" value="<?= $individu['alamat_wilayah']; ?>" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Pendidikan</label>
		<div class="col-sm-8">
			<input class="form-control input-sm" type="text" value="<?= $individu['pendidikan']?>" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Warga Negara /Agama</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['warganegara']?>" disabled="">
		</div>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['agama']?>" disabled="">
		</div>
	</div>
<?php elseif ($suplemen["sasaran"] == 2): ?>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Tempat Tanggal Lahir (Umur) KK</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['tempatlahir']?>" disabled="">
		</div>
		<div class="col-sm-2">
			<input class="form-control input-sm" type="text" value=" <?= tgl_indo($individu['tanggallahir'])?>" disabled="">
		</div>
		<div class="col-sm-2">
			<input class="form-control input-sm" type="text" value="<?= $individu['umur']?> Tahun" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Alamat Keluarga</label>
		<div class="col-sm-8">
			<input class="form-control input-sm" type="text" value="<?= $individu['alamat_wilayah']; ?>" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Pendidikan KK</label>
		<div class="col-sm-8">
			<input class="form-control input-sm" type="text" value="<?= $individu['pendidikan']?>" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Warga Negara /Agama KK</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['warganegara']?>" disabled="">
		</div>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['agama']?>" disabled="">
		</div>
	</div>
<?php endif; ?>