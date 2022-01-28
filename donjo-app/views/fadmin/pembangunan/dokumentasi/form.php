<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Dokumentasi Pembangunan
			<small><?= ($id_pembangunan ? 'Ubah' : 'Tambah') ?> Data</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url($this->controller) ?>"> Pembangunan</a></li>
			<li><a href="<?= site_url("{$this->controller}/dokumentasi/{$id_pembangunan}") ?>"></i>Dokumentasi Pembangunan</a></li>
			<li class="active"><?= ($id_pembangunan ? 'Ubah' : 'Tambah') ?> Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url("{$this->controller}/dokumentasi/{$id_pembangunan}") ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pembangunan</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" name="id_pembangunan" value="<?= $id_pembangunan ?>">
									<div class="form-group">
										<label for="jenis_persentase" class="col-sm-3 control-label">Persentase Pembangunan</label>
										<div class="btn-group col-sm-8 kiri" data-toggle="buttons">
											<label class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label active">
												<input type="radio" name="jenis_persentase" class="form-check-input" value="1" autocomplete="off" onchange="pilih_persentase(this.value);"> Pilih Persentase
											</label>
											<label class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label">
												<input type="radio" name="jenis_persentase" class="form-check-input" value="2" autocomplete="off" onchange="pilih_persentase(this.value);"> Tulis Manual
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"></label>
										<div id="pilih">
											<div class="col-sm-7">
												<select class="form-control input-sm select2 required" id="id_persentase" name="id_persentase" onchange="show_hide_anggaran(this.value)">
													<option value=''>-- Pilih Persentase Pembangunan --</option>
													<?php foreach ($persentase as $value) : ?>
														<option value="<?= $value ?>" <?= selected($main->persentase, $value) ?>><?= $value ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div id="manual">
											<div class="col-sm-7">
												<input maxlength="50" class="form-control input-sm required" name="persentase" id="persentase" type="text" onkeyup="show_hide_anggaran(this.value)" placeholder="Contoh: 50%" value="<?= $main->persentase ?>" />
											</div>
										</div>
									</div>
									<?php if ($main->gambar) : ?>
										<div class="form-group">
											<label class="control-label col-sm-4" for="nama"></label>
											<div class="col-sm-6">
												<input type="hidden" name="old_foto" value="<?= $main->gambar ?>">
												<img class="attachment-img img-responsive img-circle" src="<?= base_url() . LOKASI_GALERI . $main->gambar ?>" alt="Gambar Dokumentasi" width="200" height="200">
											</div>
										</div>
									<?php endif; ?>
									<div class="form-group">
										<label class="control-label col-sm-3" for="upload">Unggah Dokumentasi</label>
										<div class="col-sm-7">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control " id="file_path" name="gambar">
												<input id="file" type="file" class="hidden" name="gambar">
												<span class="input-group-btn">
													<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
												</span>
											</div>
											<span class="help-block"><code>(Kosongkan jika tidak ingin mengubah gambar)</code></span>
										</div>
									</div>
									<div class="form-group" id="anggaran">
										<label class="control-label col-sm-3" for="upload">Perubahan Anggaran</label>
										<div class="col-sm-7">
											<input class="form-control input-sm" name="perubahan_anggaran" id="ubahanggaran" type="number" min="0" value="<?= $perubahan ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
										<div class="col-sm-7">
											<textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan" placeholder="Keterangan"><?= $main->keterangan ?></textarea>
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
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	function pilih_persentase(pilih) {
		if (pilih == 1) {
			$('#persentase').val('');
			$('#persentase').removeClass('required');
			$("#manual").hide();
			$("#pilih").show();
			$('#id_persentase').addClass('required');
		} else {
			$('#id_persentase').val('');
			$('#id_persentase').removeClass('required');
			$("#manual").show();
			$("#pilih").hide();
			$('#persentase').addClass('required');
		}
	}

	function show_hide_anggaran(anggaran) {
		if (anggaran == '100%' || anggaran == '100'){
			$('#anggaran').fadeIn();
			$("#ubahanggaran").attr('required', '');
			$("#ubahanggaran").value('<?= $main->perubahan_anggaran ?? 0; ?>');
		} else {
			$('#anggaran').fadeOut();
			$("#ubahanggaran").removeAttr('required', '');
			$("#ubahanggaran").value(0);
		}
	}

	$(document).ready(function() {
		pilih_persentase(<?= in_array($main->persentase, $persentase) ? 1 : 2 ?>);
	});
</script>