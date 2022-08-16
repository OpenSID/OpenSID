<style type="text/css">
	tr.modul {
		background-color: #d9edf7 !important;
	}

	<?php if ($view) : ?>input {
		pointer-events: none;
		background-color: aliceblue !important;
	}

	input[type="checkbox"] {
		opacity: 0.5;
	}

	<?php endif; ?>
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Form Grup Pengguna</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('grup') ?>"> Pengaturan Grup Pengguna</a></li>
			<li class="active">Form Grup Pengguna</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?= site_url() ?>grup" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Pengaturan Grup Pengguna</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="group">Nama Grup</label>
								<div class="col-sm-4">
									<input name="nama" class="form-control input-sm required nama_terbatas" type="text" maxlength="20" placeholder="Nama Grup" value="<?= $grup['nama'] ?>"></input>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="mandiri">Akses Modul</label>
								<div class="col-sm-7">
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
											<thead class="bg-gray color-palette">
												<tr>
													<th><input type="checkbox" id="checkall" /></th>
													<th colspan="2">No</th>
													<th>Nama Modul</th>
													<th>Hak Baca</th>
													<th>Hak Ubah</th>
													<th>Hak Hapus</th>
												</tr>
											</thead>
											<tbody>
												<?php if ($list_akses_modul) : ?>
													<?php foreach ($list_akses_modul as $key => $akses_modul) : ?>
														<tr class="modul">
															<td class="padat"><input id="m<?= $key + 1 ?>" type="checkbox" name="modul[id][]" value="<?= $akses_modul['id']; ?>" <?= jecho($akses_modul['ada_akses'], 1, 'checked'); ?> /></td>
															<td class="padat" colspan="2"><?= ($key + 1); ?></td>
															<td><?= $akses_modul['modul']; ?></td>
															<?php if (count($list_akses_submodul[$akses_modul['id']]) == 0) : ?>
																<td class="padat">
																	<input type="checkbox" name="modul[akses_baca][<?= $akses_modul['id'] ?>]" value="1" <?= jecho($akses_modul['akses_baca'], 1, 'checked'); ?> />
																</td>
																<td class="padat">
																	<input type="checkbox" name="modul[akses_ubah][<?= $akses_modul['id'] ?>]" value="1" <?= jecho($akses_modul['akses_ubah'], 1, 'checked'); ?> />
																</td>
																<td class="padat">
																	<input type="checkbox" name="modul[akses_hapus][<?= $akses_modul['id'] ?>]" value="1" <?= jecho($akses_modul['akses_hapus'], 1, 'checked'); ?> />
																</td>
															<?php else : ?>
																<td colspan="4">&nbsp;</td>
															<?php endif; ?>
														</tr>
														<?php foreach ($list_akses_submodul[$akses_modul['id']] as $subkey => $akses_submodul) : ?>
															<tr>
																<td class="padat">
																	<input id="m<?= ($key + 1) . '.' . ($subkey + 1) ?>" class="m<?= $key + 1 ?>" type="checkbox" name="modul[id][]" value="<?= $akses_submodul['id']; ?>" <?= jecho($akses_submodul['ada_akses'], 1, 'checked'); ?> />
																</td>
																<td></td>
																<td class="padat"><?= ($key + 1) . '.' . ($subkey + 1); ?></td>
																<td><?= $akses_submodul['modul']; ?></td>
																<td class="padat">
																	<input class="m<?= $key + 1 ?>" type="checkbox" name="modul[akses_baca][<?= $akses_submodul['id'] ?>]" value="1" <?= jecho($akses_submodul['akses_baca'], 1, 'checked'); ?> />
																</td>
																<td class="padat">
																	<input class="m<?= $key + 1 ?>" type="checkbox" name="modul[akses_ubah][<?= $akses_submodul['id'] ?>]" value="1" <?= jecho($akses_submodul['akses_ubah'], 1, 'checked'); ?> />
																</td>
																<td class="padat">
																	<input class="m<?= $key + 1 ?>" type="checkbox" name="modul[akses_hapus][<?= $akses_submodul['id'] ?>]" value="1" <?= jecho($akses_submodul['akses_hapus'], 1, 'checked'); ?> />
																</td>
															</tr>
														<?php endforeach; ?>
													<?php endforeach; ?>
												<?php else : ?>
													<tr>
														<td class="padat" colspan="4">Data Tidak Tersedia</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<?php if (! $view) : ?>
									<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
									<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		$("input[name*='modul[id]']").change(function() {
			var val = $(this).val();
			var id = $(this).attr('id');
			$('input[type=checkbox][name*="[' + val + ']"]').prop('checked', $(this).is(':checked'));
			// Ubah suhmodul sesuai modul
			// Cara berikut karena trigger('change') tidak jalan (?)
			// Submodul aktif tergantung modul
			if (this.checked) {
				$("input." + id).removeAttr("disabled");
				$('input[type=checkbox][id^="' + id + '."]').prop('checked', !$(this).is(':checked'));
				$('input[type=checkbox][id^="' + id + '."]').trigger('click');
			} else {
				$('input[type=checkbox][id^="' + id + '."]').prop('checked', !$(this).is(':checked'));
				$('input[type=checkbox][id^="' + id + '."]').trigger('click');
				$("input." + id).attr("disabled", true);
			}
		});

		$("input[name*='akses']").change(function() {
			var name = $(this).attr('name');
			var modul = $(this).parent().parent().find(":checkbox")[0];
			if ($(this).is(':checked')) {
				$(modul).prop('checked', true);
			}
			if (name.indexOf('akses_baca') > 0) {
				var ubah = name.replace("baca", "ubah")
				var hapus = name.replace("baca", "hapus")
				if (!$(this).is(':checked')) {
					// Pastikan akses_ubah dan akses_hapus tidak checked
					$("input[name='" + ubah + "']").prop('checked', false);
					$("input[name='" + hapus + "']").prop('checked', false);
				}
			} else if (name.indexOf('akses_ubah') > 0) {
				var baca = name.replace("ubah", "baca")
				var hapus = name.replace("ubah", "hapus")
				if ($(this).is(':checked')) {
					// Pastikan akses_baca juga checked
					$("input[name='" + baca + "']").prop('checked', true);
				} else {
					// Pastikan akses_hapus tidak checked
					$("input[name='" + hapus + "']").prop('checked', false);
				}
			} else if (name.indexOf('akses_hapus') > 0) {
				var baca = name.replace("hapus", "baca")
				var ubah = name.replace("hapus", "ubah")
				if ($(this).is(':checked')) {
					// Pastikan akses_baca dan akses_ubah juga checked
					$("input[name='" + baca + "']").prop('checked', true);
					$("input[name='" + ubah + "']").prop('checked', true);
				}
			}
		});

		$("input[name*='modul[id]']").each(function(index) {
			var id = $(this).attr('id');
			if (this.checked) {
				$("input." + id).removeAttr("disabled");
				$('input[type=checkbox][id^="' + id + '."]').prop('checked', !$(this).is(':checked'));
			} else {
				$('input[type=checkbox][id^="' + id + '."]').prop('checked', $(this).is(':checked'));
				$("input." + id).attr("disabled", true);
			}
		});

		$("input[name*='akses']").each(function(index) {
			var modul = $(this).parent().parent().find(":checkbox")[0];
			if ($(this).is(':checked')) {
				$(modul).prop('checked', true);
			}
		});
	});
</script>