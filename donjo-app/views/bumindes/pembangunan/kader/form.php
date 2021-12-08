<style>
	.ui-autocomplete {
    max-height: 200px !important;
    overflow-y: auto !important;
    overflow-x: hidden;
    border:1px solid #222;
    position:absolute;
  }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Master Kader Pemberdayaan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kelompok')?>"> Daftar Kader Pemberdayaan</a></li>
			<li class="active"><?php $main ? 'Ubah' : 'Tambah'; ?> Kader Pemberdayaan</li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url($this->controller)?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Daftar Kader Pemberdayaan</a>
			</div>
			<form id="validasi" action="<?= $form_action; ?>" method="POST" class="form-horizontal">
				<div class="box-body">
					<div class="form-group" >
						<label class="col-sm-3 control-label" for="penduduk_id">NIK / Nama Kader</label>
						<div class="col-sm-6">
							<select class="form-control select2 required" id="penduduk_id" name="penduduk_id">
								<option value="" selected="selected">-- Silakan Masukkan NIK / Nama Kader  --</option>
								<?php foreach ($daftar_penduduk as $penduduk): ?>
									<option value="<?= $penduduk['id']; ?>" <?= selected($main['penduduk_id'], $penduduk['id']); ?>>NIK : <?= $penduduk['nik'] . ' | Nama : ' . $penduduk['nama']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-sm-3 control-label" for="kursus">Kursus</label>
						<div class="col-sm-6">
							<input type="text" name="kursus" id="kursus" class="form-control ui-autocomplete required" placeholder="Pilih Kursus" value="<?= $main['kursus']; ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-sm-3 control-label" for="bidang">Bidang Keahlian</label>
						<div class="col-sm-6">
							<input type="text" name="bidang" id="bidang" class="form-control ui-autocomplete required" placeholder="Pilih Bidang Keahlian" value="<?= $main['bidang']; ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
						<div class="col-sm-6">
							<textarea name="keterangan" id="keterangan" class="form-control input-sm required" maxlength="100" placeholder="Keterangan" rows="5"><?= $main['keterangan']; ?></textarea>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
				</div>
			</form>
		</div>
	</section>
</div>
<script>
	$(document).ready(function(){

		var url = SITE_URL + '/bumindes_kader/';

		$('#kursus').tokenfield({
			autocomplete: {
				source: function (request, response) {
					jQuery.get(url + 'get_kursus', {
						nama: request.term
					}, function (data) {
						data = $.parseJSON(data);
						response(data);
					});
				},
				delay: 100
			},
			showAutocompleteOnFocus: true
		});

		$('#bidang').tokenfield({
			autocomplete: {
				source: function (request, response) {
					jQuery.get(url + 'get_bidang', {
						nama: request.term
					}, function (data) {
						data = $.parseJSON(data);
						response(data);
					});
				},
				delay: 100
			},
			showAutocompleteOnFocus: true
		});
	});
</script>