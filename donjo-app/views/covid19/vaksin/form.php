
<div class="content-wrapper">
	<section class="content-header">
		<h1>Form Pendataan Vaksin Covid 19</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url($this->controller)?>"> Daftar Penduduk Penerima Vaksin Covid 19</a></li>
			<li class="active">Penambahan Pemudik Covid-19</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<?php if ($this->CI->cek_hak_akses('u')): ?>
				<div class="box-header with-border">
					<a href="<?= site_url($this->controller)?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Pemudik Saat Covid-19"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Penduduk Penerima Vaksin Covid 19</a>
				</div>
			<?php endif; ?>
			<div class="box-header with-border">
				<h3 class="box-title">Tambahkan Warga Penerima Vaksin</h3>
			</div>
			<div class="box-body">

				<form id="main" name="main" method="GET" class="form-horizontal">
					<div class="form-group" >
						<label class="col-sm-3 control-label required"  for="terdata">NIK / Nama</label>
						<div class="col-sm-4">
							<select class="form-control select2 required" id="terdata" name="terdata"  onchange="formAction('main')" style="width: 100%;">
								<option selected>-- Silahkan Masukan NIK/Nama -- </option>
								<?php foreach ($list_penduduk as $item): ?>
									<?php if ($item->id !== ''): ?>
										<option value="<?= $item->id?>" <?php selected($penduduk->id, $item->id); ?> >Nama : <?= $item->nama . ' - ' . $item->nik?></option>
									<?php endif ?>
								<?php endforeach ?>
							</select>
						</div>
					</div>

				</form>
				<div >
					<form id="validasi" action="<?= site_url($this->controller) . '/update' ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<input type="hidden" name="nik" value="<?= $penduduk->nik ?>">
						<input type="hidden" name="id_penduduk" value="<?= $penduduk->id ?>">

						<div class="form-group">
							<label for="tunda" class="col-xs-12 col-sm-3 col-lg-3 control-label"> Tunda Vaksin</label>
							<input type="hidden" name="tunda" value="<?= (int) ($penduduk->tunda) ?>">
							<div class="btn-group col-xs-12 col-sm-4" data-toggle="buttons">
								<label class="btn btn-info btn-flat btn-sm form-check-label col-xs-6 col-sm-6 <?= ($penduduk->tunda == 1) ? 'active' : ''; ?>"  >
									<input id="tunda_v" type="radio" name="tunda_radio" class="form-check-input" value="1" autocomplete="off" <?= ($penduduk->tunda == 1) ? 'selected' : ''; ?> > Ya
								</label>
								<label id="tidak" class="btn btn-info btn-flat btn-sm form-check-label col-xs-6 col-sm-6 <?= ($penduduk->tunda == 0) ? 'active' : ''; ?>" >
									<input type="radio" name="tunda_radio" class="form-check-input" value="0" autocomplete="off" <?= ($penduduk->tunda == '0' || $penduduk->tunda == null) ? 'selected' : ''; ?> > Tidak
								</label>
							</div>
						</div>

							<!-- vaksin dosis 2 -->
						<div class="form-group">
							<label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
							<div class="col-sm-8">
								<textarea id="keterangan" name="keterangan" class="form-control input-sm" placeholder="Keterangan" rows="3" <?= ($penduduk->tunda == '0' || $penduduk->tunda == null) ? 'disabled' : ''; ?>><?= $penduduk->keterangan ?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label for="surat_dokter" class="col-sm-3 control-label"></label>
							<div class="col-sm-8">
								<div class="input-group input-group-sm">
									<input type="text" class="form-control" id="file_path4" placeholder="Upload Dokumen/Surat Dokter" <?= ($penduduk->tunda == '0' || $penduduk->tunda == null) ? 'disabled' : ''; ?> >
									<input id="file4" type="file" class="hidden" name="surat_dokter">
									<input type="hidden" name="surat_dokter" value="<?= $penduduk->surat_dokter?>">
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat" id="file_browser4" <?= ($penduduk->tunda == '0' || $penduduk->tunda == null) ? 'disabled' : ''; ?>><i class="fa fa-search" ></i> Browse</button>
									</span>
								</div>
							</div>
						</div>

						<!-- vaksin dosis 1 -->
						<div class="form-group">
							<label for="centang_vaksin_1" class="col-sm-3 control-label">Vakin Dosis 1</label>
							<div class="col-sm-4">
								<div class="input-group input-group-sm date ">
									<span class="input-group-addon">
										<input type="checkbox" title="Centang jika sudah vaksin dosis 1" id="centang_vaksin_1" data-ke="1" class="centang_vaksin" checked="checked" <?= jecho($penduduk->tunda, 1, 'disabled'); ?> value="1" name="vaksin_1" >
									</span>
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i> Tanggal Vaksin
									</div>
									<input type="text" class="form-control input-sm pull-right tgl-datepicker" id="tanggal_vaksin_1"  name="tgl_vaksin_1" value="<?= rev_tgl($penduduk->tgl_vaksin_1); ?>" <?= ($penduduk->tunda == 1) ? 'disabled' : ''; ?>>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="input-group input-group-sm date ">
									<div class="input-group-addon">Jenis Vaksin</div>
									<select class="form-control input-sm select2-tags" data-url="<?= site_url($this->controller) ?>" data-placeholder="-- Pilih Jenis Vaksin --" id="jenis_vaksin_1" name="jenis_vaksin_1" disabled>
										<option value="">-- Pilih Jenis vaksin -- </option>
										<?php foreach ($list_vaksin as $vaksin): ?>
											<option value="<?= $vaksin ?>" <?= selected($vaksin, $penduduk->jenis_vaksin_1); ?>><?= $vaksin ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-3"></div>
							<div class="col-sm-8">
								<div class="input-group input-group-sm">
									<input type="text" class="form-control" id="file_path1" placeholder="Upload Dokumen/Sertifikat" <?= jecho($penduduk->tunda, 1, 'disabled'); ?>>
									<input id="file1" type="file" class="hidden" name="vaksin_1">
									<input type="hidden" name="dokumen_vaksin_1" value="<?= $penduduk->dokumen_vaksin_1?>">
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat" id="file_browser1" <?= jecho($penduduk->tunda, 1, 'disabled'); ?>><i class="fa fa-search"></i> Browse</button>
									</span>
								</div>
							</div>
						</div>

						<!-- vaksin dosis 2 -->
						<div class="form-group">
							<label for="centang_vaksin_2" class="col-sm-3 control-label">Vakin Dosis 2</label>
							<div class="col-sm-4">
								<div class="input-group input-group-sm date ">
									<span class="input-group-addon">
										<input type="checkbox" title="Centang jika sudah vaksin dosis 2" id="centang_vaksin_2" data-ke="2" class="centang_vaksin" <?= jecho($penduduk->vaksin_2, 1, 'checked="checked"'); ?> value="1" name="vaksin_2">
									</span>
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i> Tanggal Vaksin
									</div>
									<input type="text" class="form-control input-sm pull-right tgl-datepicker" id="tanggal_vaksin_2" name="tgl_vaksin_2" value="<?= rev_tgl($penduduk->tgl_vaksin_2); ?>" disabled>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="input-group input-group-sm date ">
									<div class="input-group-addon">Jenis Vaksin</div>
									<select class="form-control input-sm select2-tags" data-url="<?= site_url($this->controller) ?>" data-placeholder="-- Pilih Jenis Vaksin --" id="jenis_vaksin_2" name="jenis_vaksin_2" disabled>
										<option value="">-- Pilih Jenis vaksin -- </option>
										<?php foreach ($list_vaksin as $vaksin): ?>
											<option value="<?= $vaksin ?>" <?= selected($vaksin, $penduduk->jenis_vaksin_2); ?>><?= $vaksin ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-3"></div>
							<div class="col-sm-8">
								<div class="input-group input-group-sm">
									<input type="text" class="form-control" id="file_path2" placeholder="Upload Dokumen/Sertifikat" <?= jecho($penduduk->tunda, 1, 'disabled'); ?>>
									<input id="file2" type="file" class="hidden" name="vaksin_2">
									<input type="hidden" name="dokumen_vaksin_2" value="<?= $penduduk->dokumen_vaksin_2?>">
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat" id="file_browser2" <?= jecho($penduduk->tunda, 1, 'disabled'); ?>><i class="fa fa-search"></i> Browse</button>
									</span>
								</div>
							</div>
						</div>

						<!-- vaksin dosis 3 -->
						<div class="form-group">
							<label for="centang_vaksin_3" class="col-sm-3 control-label">Vakin Dosis 3</label>
							<div class="col-sm-4">
								<div class="input-group input-group-sm date ">
									<span class="input-group-addon">
										<input type="checkbox" title="Centang jika sudah vaksin dosis 3" id="centang_vaksin_3" data-ke="3" class="centang_vaksin" <?= jecho($penduduk->vaksin_3, 1, 'checked="checked"'); ?> value="1" name="vaksin_3" disabled>
									</span>
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i> Tanggal Vaksin
									</div>
									<input type="text" class="form-control input-sm pull-right tgl-datepicker " id="tanggal_vaksin_3" data-ke="3" name="tgl_vaksin_3" value="<?= rev_tgl($penduduk->tgl_vaksin_3); ?>" disabled>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="input-group input-group-sm date ">
									<div class="input-group-addon">Jenis Vaksin</div>
									<select class="form-control input-sm select2-tags" data-url="<?= site_url($this->controller) ?>" data-placeholder="-- Pilih Jenis Vaksin --" id="jenis_vaksin_3" name="jenis_vaksin_3" disabled>
										<option value="">-- Pilih Jenis vaksin -- </option>
										<?php foreach ($list_vaksin as $vaksin): ?>
											<option value="<?= $vaksin ?>" <?= selected($vaksin, $penduduk->jenis_vaksin_3); ?>><?= $vaksin ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-3"></div>
							<div class="col-sm-8">
								<div class="input-group input-group-sm">
									<input type="text" class="form-control" id="file_path3" placeholder="Upload Dokumen/Sertifikat" disabled>
									<input id="file3" type="file" class="hidden" name="vaksin_3">
									<input type="hidden" name="dokumen_vaksin_3" value="<?= $penduduk->dokumen_vaksin_3?>">
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat" id="file_browser3" disabled><i class="fa fa-search" ></i> Browse</button>
									</span>
								</div>
							</div>
						</div>

					</form>
				</div>
				<div class="box-footer">
					<div class="col-xs-12">
						<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
						<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" onclick="$('#'+'validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		// fungsi cek vaksin
		function cek_vaksin() {
			var tunda = $('input[name="tunda"]').val();
			if (tunda == 1) {
				for (var i = 1; i <= 3; i++) {
					// disable vaksin
					$(`#tanggal_vaksin_${i}`).prop( "disabled", true );
					$(`#file_path${i}`).prop( "disabled", true );
					$(`#file_browser${i}`).prop( "disabled", true );
					$(`#jenis_vaksin_${i}`).prop( "disabled", true );
					$(`#centang_vaksin_${i}`).prop( "disabled", true );
				}
			} else {
				$(`#centang_vaksin_1`).prop( "disabled", false );
				for (var i = 1; i <= 3; i++) {

					if ($(`#centang_vaksin_${i}`).is(':checked')) {
						$(`#tanggal_vaksin_${i}`).prop( "disabled", false );
						$(`#file_path${i}`).prop( "disabled", false );
						$(`#file_browser${i}`).prop( "disabled", false );
						$(`#centang_vaksin_${i+1}`).prop( "disabled", false );
						$(`#jenis_vaksin_${i}`).prop( "disabled", false );
					}
				}
			}
		}
		cek_vaksin(); // load cek

		// fungsi aktifkan vaksin
		function enable_vaksin() {
			$(`#centang_vaksin_1`).prop( "disabled", false );
			$(`#tanggal_vaksin_1`).prop( "disabled", false );
			$(`#file_path1`).prop( "disabled", false );
			$(`#file_browser1`).prop( "disabled", false );
			$(`#jenis_vaksin_1`).prop( "disabled", false );
			for (var i = 2; i <= 3; i++) {
				// enable checkbox
				if ($(`#centang_vaksin_${i-1}`).is(':checked')) {
					$(`#centang_vaksin_${i}`).prop( "disabled", false );
				}

				if ($(`#centang_vaksin_${i}`).is(':checked')) {
					$(`#tanggal_vaksin_${i}`).prop( "disabled", false );
					$(`#file_path${i}`).prop( "disabled", false );
					$(`#file_browser${i}`).prop( "disabled", false );
					$(`#jenis_vaksin_${i}`).prop( "disabled", false );
				}
			}
		}

		$('input[name="tunda_radio"]').change(function(event) {
			$('input[name="tunda"]').val($(this).val());
			if ($(this).val() == 0) {
				enable_vaksin();
				$(`#file_path4`).prop( "disabled", true );
				$(`#file_browser4`).prop( "disabled", true );
				$('#keterangan').prop("disabled", true );
			} else {
				$('.centang_vaksin').prop( "disabled", true );
				$('#keterangan').prop( "disabled", false );
				$(`#file_path4`).prop( "disabled", false );
				$(`#file_browser4`).prop( "disabled", false );
				for (var i = 1; i <= 3; i++) {
					$(`#tanggal_vaksin_${i}`).prop( "disabled", true );
					$(`#file_path${i}`).prop( "disabled", true );
					$(`#file_browser${i}`).prop( "disabled", true );
					$(`#jenis_vaksin_${i}`).prop( "disabled", true );
				}
			}
		});

		$('#validasi').find('.centang_vaksin').change(function(event) {
			var ke =  $(this).data('ke');
			if ($(this).is(':checked')) {
				$(`#tanggal_vaksin_${ke}`).prop( "disabled", false );
				$(`#file_path${ke}`).prop( "disabled", false );
				$(`#file_browser${ke}`).prop( "disabled", false );
				$(`#jenis_vaksin_${ke}`).prop( "disabled", false );
				$(`#centang_vaksin_${ke+1}`).prop( "disabled", false );
			} else {
				$(`#tanggal_vaksin_${ke}`).prop( "disabled", true );
				$(`#file_path${ke}`).prop( "disabled", true );
				$(`#file_browser${ke}`).prop( "disabled", true );
				$(`#jenis_vaksin_${ke}`).prop( "disabled", true );
				$(`#centang_vaksin_${ke+1}`).prop( "disabled", true );
			}
		});

		$('.tgl-datepicker').datetimepicker({
			format: 'DD-MM-YYYY'
		});
	});
</script>