<div class="form-group konfirmasi">
	<label for="keperluan"  class="col-sm-3 control-label">Tempat  / Tanggal Lahir / Umur</label>
	<div class="col-sm-4">
		<input class="form-control form-control-sm" type="text" value="<?= strtoupper($individu['tempatlahir']);?> " disabled="">
	</div>
	<div class="col-sm-2">
  	<input class="form-control form-control-sm" type="text" value="<?= strtoupper(tgl_indo ($individu['tanggallahir']));?> " disabled="">
	</div>
	<div class="col-sm-2">
		<input class="form-control form-control-sm" type="text" value="<?= strtoupper($individu['umur']);?> TAHUN" disabled="">
	</div>
</div>
<div class="form-group konfirmasi">
	<label for="keperluan"  class="col-sm-3 control-label">Alamat</label>
	<div class="col-sm-8">
		<input class="form-control form-control-sm" type="text" value="<?= strtoupper($individu['alamat_wilayah']); ?>" disabled="">
	</div>
</div>
<div class="form-group konfirmasi">
	<label for="keperluan"  class="col-sm-3 control-label">Pendidikan / Warga Negara /Agama</label>
	<div class="col-sm-4">
		<input class="form-control form-control-sm" type="text" value="<?= strtoupper($individu['pendidikan']);?>" disabled="">
	</div>
	<div class="col-sm-2">
		<input class="form-control form-control-sm" type="text" value="<?= strtoupper($individu['warganegara']);?>" disabled="">
	</div>
	<div class="col-sm-2">
		<input class="form-control form-control-sm" type="text" value=" <?= strtoupper($individu['agama']);?>" disabled="">
	</div>
</div>
<div class="form-group konfirmasi tdk-permohonan tdk-periksa">
	<label for="persyaratan"  class="col-sm-3 control-label">Dokumen Kelengkapan / Syarat</label>
	<div class="col-sm-8">
		<a href="<?= site_url("penduduk/dokumen_list/$individu[id]")?>" class="btn btn-flat btn-success btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Daftar Dokumen">
			<i class='fa fa-book'></i> Daftar Dokumen
		</a>
		<a href="<?= site_url("penduduk/dokumen/$individu[id]")?>" class="btn btn-flat btn-primary btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" target="_blank">
			<i class="fa fa-gears"></i> Manajemen Dokumen
		</a>
	</div>
</div>