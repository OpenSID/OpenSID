<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Data Pembangunan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('pembangunan') ?>"><i class="fa fa-dashboard"></i>Daftar Pembangunan</a></li>
			<li class="active">Pengaturan Data Pembangunan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('pembangunan') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pembangunan</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="sumber_dana">Sumber Dana</label>
										<div class="col-sm-7">
											<select class="form-control input-sm select2" id="sumber_dana" name="sumber_dana" style="width:100%;">
												<?php foreach ($sumber_dana as $value) : ?>
													<option <?= $value === $main->sumber_dana ? 'selected' : '' ?> value="<?= $value ?>"><?= $value ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;">Nama Kegiatan</label>
										<div class="col-sm-7">
											<input maxlength="50" class="form-control input-sm required" name="judul" id="judul" value="<?= $main->judul ?>" type="text" placeholder="Nama Kegiatan Pembangunan" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;">Volume</label>
										<div class="col-sm-7">
											<input maxlength="50" class="form-control input-sm required" name="volume" id="volume" value="<?= $main->volume ?>" type="text" placeholder="Volume Pembangunan" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="tahun_anggaran">Tahun Anggaran</label>
										<div class="col-sm-7">
											<select class="form-control input-sm select2" id="tahun_anggaran" name="tahun_anggaran" style="width:100%;">
												<?php for ($i = date('Y'); $i >= 1999; $i--) : ?>
													<option value="<?= $i ?>"><?= $i ?></option>
												<?php endfor; ?>
											</select>
											<script>
												$('#tahun_anggaran').val("<?= $main->tahun_anggaran?>");
											</script>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;">Anggaran</label>
										<div class="col-sm-7">
											<input class="form-control input-sm required" name="anggaran" id="anggaran" value="<?= $main->anggaran ?>" type="text" placeholder="Anggaran" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;">Pelaksana Kegiatan</label>
										<div class="col-sm-7">
											<input maxlength="50" class="form-control input-sm required" name="pelaksana_kegiatan" id="pelaksana_kegiatan" value="<?= $main->pelaksana_kegiatan ?>" type="text" placeholder="Pelaksana Kegiatan Pembangunan" />
										</div>
									</div>
									<div class="form-group">
										<label for="jenis_lokasi" class="col-sm-3 control-label">Lokasi Pembangunan</label>
										<div class="btn-group col-sm-8 kiri" data-toggle="buttons">
											<label class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label <?= $main->lokasi ? NULL : 'active' ?>">
												<input type="radio" name="jenis_lokasi" class="form-check-input" value="1" autocomplete="off" onchange="pilih_lokasi(this.value);"> Pilih Lokasi
											</label>
											<label class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label <?= $main->lokasi ? 'active' : NULL ?>">
												<input type="radio" name="jenis_lokasi" class="form-check-input" value="2" autocomplete="off" onchange="pilih_lokasi(this.value);"> Tulis Manual
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"></label>
										<div id="pilih">
											<div class="col-sm-7">
												<select class="form-control input-sm select2 required" id="id_lokasi" name="id_lokasi" style="width:100%">
													<option value=''>-- Pilih Lokasi Pembangunan --</option>
													<?php foreach ($list_lokasi as $key => $item) : ?>
														<option value="<?= $item["id"] ?>" <?php selected($item["id"], $main->id_lokasi) ?>><?= strtoupper($item["dusun"]) . " - RW " . $item["rw"] . " / RT " . $item["rt"] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div id="manual">
											<div class="col-sm-7">
												<textarea id="lokasi" class="form-control input-sm required" type="text" placeholder="Lokasi" name="lokasi"><?= $main->lokasi ?></textarea>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
										<div class="col-sm-7">
											<textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan" placeholder="Keterangan"><?= $main->keterangan ?></textarea>
										</div>
									</div>
									<?php if ($main->foto) : ?>
										<div class="form-group">
											<label class="control-label col-sm-4" for="nama"></label>
											<div class="col-sm-6">
												<input type="hidden" name="old_foto" value="<?= $main->foto ?>">
												<img class="attachment-img img-responsive img-circle" src="<?= base_url() . LOKASI_GALERI . $main->foto ?>" alt="Gambar Dokumentasi" width="200" height="200">
											</div>
										</div>
									<?php endif; ?>
									<div class="form-group">
										<label class="control-label col-sm-3" for="upload">Unggah Gambar Utama</label>
										<div class="col-sm-7">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control " id="file_path" name="foto">
												<input id="file" type="file" class="hidden" name="foto">
												<span class="input-group-btn">
													<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
												</span>
											</div>
											<span class="help-block"><code>(Kosongkan jika tidak ingin mengubah gambar)</code></span>
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
	function pilih_lokasi(pilih) {
		if (pilih == 1) {
			$('#lokasi').val(null);
			$('#lokasi').removeClass('required');
			$("#manual").hide();
			$("#pilih").show();
			$('#id_lokasi').addClass('required');
		} else {
			$('#id_lokasi').val(null);
			$('#id_lokasi').trigger('change', true);
			$('#id_lokasi').removeClass('required');
			$("#manual").show();
			$('#lokasi').addClass('required');
			$("#pilih").hide();
		}
	}

	$(document).ready(function() {
		pilih_lokasi(<?= is_null($main->lokasi) ? 1 : 2 ?>);
	});
</script>
