<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Format Surat Desa</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('surat_master'); ?>"> Format Surat Desa</a></li>
			<li class="active">Pengaturan Format Surat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url("surat_master"); ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
					<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Format Surat
				</a>
			</div>
			<form id="validasi" action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="kode_surat">Kode/Klasifikasi Surat</label>
						<div class="col-sm-7">
							<select class="form-control input-sm select2-tags required" id="kode_surat" name="kode_surat">
								<?php if ( ! empty($surat_master['kode_surat'])): ?>
									<option value="<?= $surat_master['kode_surat']; ?>"><?= $surat_master['kode_surat']; ?></option>
								<?php else: ?>
									<option value="">-- Pilih Kode/Klasifikasi Surat --</option>
								<?php endif; ?>
								<?php foreach ($klasifikasi as $item): ?>
									<option value="<?= $item['kode']; ?>" <?= selected($item['kode'], $surat_master["kode_surat"]); ?>><?= $item['kode'] . ' - ' . $item['nama']; ?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama Layanan</label>
						<div class="col-sm-7">
							<div class="input-group">
								<span class="input-group-addon input-sm">Surat</span>
								<input type="text" class="form-control input-sm required" id="nama" name="nama" placeholder="Nama Layanan" value="<?= $surat_master['nama']?>"/>
							</div>
						</div>
					</div>
					<?php if (strpos($form_action, 'insert') !== FALSE): ?>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="nama">Pemohon Surat</label>
							<div class="col-sm-3">
								<select class="form-control input-sm" id="pemohon_surat" name="pemohon_surat">
									<option value="warga" selected>Warga</option>
									<option value="non_warga">Bukan Warga</option>
								</select>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="nama">Masa Berlaku Default</label>
						<div class="col-sm-3">
							<div class="row">
								<div class="col-sm-3">
									<input type="number" class="form-control input-sm" id="masa_berlaku" name="masa_berlaku" onchange="masaBerlaku()" value="<?= $surat_master['masa_berlaku'] ? $surat_master['masa_berlaku'] : 1; ?>">
								</div>
								<div class="col-sm-6">
									<select class="form-control input-sm" id="satuan_masa_berlaku" name="satuan_masa_berlaku">
										<?php foreach ($list_ref_masa as $kode_masa => $judul_masa): ?>
											<option value="<?= $kode_masa; ?>" <?= selected($surat_master['satuan_masa_berlaku'], $kode_masa); ?>><?= $judul_masa; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<label class="text-muted text-red">Minimal 1 dan maksimal 31</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="mandiri">Sediakan di Layanan Mandiri</label>
						<div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
							<label id="m1" class="tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label <?= jecho($surat_master['mandiri'], 1, 'active'); ?>">
								<input id="g1" type="radio" name="mandiri" class="form-check-input" type="radio" value="1" <?= jecho($surat_master['mandiri'], 1, 'checked'); ?> autocomplete="off">Ya
							</label>
							<label id="m2" class="tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label <?= jecho($surat_master['mandiri'] != 1, TRUE, 'active'); ?>">
								<input id="g2" type="radio" name="mandiri" class="form-check-input" type="radio" value="0" <?= jecho($surat_master['mandiri'] != 1, TRUE, 'checked'); ?> autocomplete="off">Tidak
							</label>
						</div>
					</div>
					<div class="form-group" id="syarat" <?= jecho($surat_master['mandiri'] != 1, TRUE, 'style="display:none;"'); ?>>
						<label class="col-sm-3 control-label" for="mandiri">Syarat Surat</label>
						<div class="col-sm-7">
							<div class="table-responsive">
								<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
									<thead class="bg-gray disabled color-palette">
										<tr>
											<th><input type="checkbox" id="checkall"/></th>
											<th>No</th>
											<th>Nama Dokumen</th>
										</tr>
									</thead>
									<tbody>
										<?php if ($list_ref_syarat): ?>
											<?php foreach ($list_ref_syarat as $key => $ref_syarat): ?>
												<tr>
													<td class="padat"><input type="checkbox" name="syarat[]" value="<?=$ref_syarat['ref_syarat_id']; ?>" <?php in_array($ref_syarat['ref_syarat_id'], array_column($syarat_surat, 'ref_syarat_id')) and print('checked'); ?>/></td>
													<td class="padat"><?= ($key + 1); ?></td>
													<td><?= $ref_syarat['ref_syarat_nama']; ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td class="text-center" colspan="3">Data Tidak Tersedia</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
				</div>
			</form>
		</div>
	</section>
</div>
<script>
	$('document').ready(function() {
		syarat($('input[name=mandiri]:checked').val());
		$('input[name="mandiri"]').change(function() {
			syarat($(this).val());
		});
	});

	function syarat(tipe) {
		(tipe == '1' || tipe == null) ? $('#syarat').show() : $('#syarat').hide();
	}

	function reset_form() {
		$(".tipe").removeClass("active");
		$("input[name=mandiri").prop("checked", false);
		<?php if ($surat_master['mandiri'] == '1'): ?>
			$("#m1").addClass('active');
			$("#g1").prop("checked", true);
		<?php endif; ?>
		<?php if ($surat_master['mandiri'] != '1'): ?>
			$("#m2").addClass('active');
			$("#g2").prop("checked", true);
		<?php endif; ?>
		syarat($('input[name=mandiri]:checked').val());
	};

	function masaBerlaku() {
		var masa_berlaku = document.getElementById("masa_berlaku").value;
		if (masa_berlaku < 1) {
			document.getElementById("masa_berlaku").value = 1;
		} else if (masa_berlaku > 31) {
			document.getElementById("masa_berlaku").value = 31;
		}
	}
</script>
