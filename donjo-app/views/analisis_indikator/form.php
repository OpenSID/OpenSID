<style>
	.disabled {
		pointer-events:none;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Indikator Analisis [ <?= $analisis_master['nama']; ?> ]</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?=site_url('analisis_master/clear')?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_indikator')?>">Indikator Analisis</a></li>
			<li class="active">Pengaturan Indikator Analisis</li>
		</ol>
	</section>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left', $data); ?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('analisis_indikator') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Indikator Analisis</a>
						</div>
						<div class="box-body">
							<div class="row">
								<?php $disabled = ($analisis_master['jenis'] == 1 || $analisis_indikator['referensi'] || $ubah == false) ? 'disabled' : '' ?>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="referensi">Hubungkan Dengan Data <?= $analisis_master['subjek_nama']; ?></label>
										<div class="col-sm-5">
											<select class="form-control select2" id="referensi" name="referensi" <?= $disabled ?>>
												<option value="" selected data-tipe="">Jangan hubungkan</option>
												<?php foreach ($data_tabel as $referensi => $data): ?>
													<option value="<?= $referensi; ?>" <?= selected($analisis_indikator['referensi'], $referensi) ?> data-tipe="<?= $data['tipe'] ?? 4; ?>"><?= $analisis_master['subjek_nama'] . ' : ' . $data['judul']; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="id_tipe">Tipe Pertanyaan</label>
										<div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
											<label id="sx3" <?= $disabled ?> class="<?= $disabled ?> tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label <?= jecho($analisis_indikator['id_tipe'] == '1' || $analisis_indikator['id_tipe'] == null, true, 'active') ?>">
												<input id="group3" type="radio" name="id_tipe" class="form-check-input" type="radio" value="1" onclick="<?= $analisis_indikator['id_tipe']; ?>" autocomplete="off">Pilihan (Tunggal)
											</label>
											<label id="sx2" <?= $disabled ?> class="<?= $disabled ?> tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label <?= jecho($analisis_indikator['id_tipe'], '2', 'active') ?>">
												<input id="group2" type="radio" name="id_tipe" class="form-check-input" type="radio" value="2" onclick="<?= $analisis_indikator['id_tipe']; ?>" autocomplete="off">Pilihan (Ganda)
											</label>
											<label id="sx1" <?= $disabled ?> class="<?= $disabled ?> tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label <?= jecho($analisis_indikator['id_tipe'], '3', 'active') ?>">
												<input id="group1" type="radio" name="id_tipe" class="form-check-input" type="radio" value="3" onclick="<?= $analisis_indikator['id_tipe']; ?>" autocomplete="off">Isian Jumlah (Kuantitatif)
											</label>
											<label id="sx4" <?= $disabled ?> class="<?= $disabled ?> tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label <?= jecho($analisis_indikator['id_tipe'], '4', 'active') ?>">
												<input id="group4" type="radio" name="id_tipe" class="form-check-input" type="radio" value="4" onclick="<?= $analisis_indikator['id_tipe']; ?>" autocomplete="off">Isian Teks (Kualitatif)
											</label>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="nomor">Kode Pertanyaan</label>
										<div class="col-sm-2">
											<input id="nomor" class="form-control input-sm nomor_sk required" type="text" maxlength="10" placeholder="Kode Pertanyaan" name="nomor" value="<?= $analisis_indikator['nomor']?>" <?= jecho($analisis_master['jenis'], 1, 'readonly="readonly"') ?>>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="pertanyaan">Pertanyaan</label>
										<div class="col-sm-8">
											<textarea id="pertanyaan" class="form-control input-sm required" placeholder="Pertanyaan" name="pertanyaan" rows="5" <?= jecho($analisis_master['jenis'], 1, 'readonly="readonly"') ?>><?= $analisis_indikator['pertanyaan']?></textarea>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="id_tipe">Kategori Indikator</label>
										<div class="col-sm-5">
											<select class="form-control select2 required" id="id_kategori" name="id_kategori">
												<option value="" selected="selected">-- Kategori Indikator --</option>
												<?php foreach ($list_kategori as $data): ?>
													<option value="<?= $data['id']?>" <?= selected($analisis_indikator['id_kategori'], $data['id']) ?>><?= $data['kategori']?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-12 delik">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="bobot">Bobot</label>
										<div class="col-sm-2">
											<input id="bobot" class="form-control input-sm number required" type="number" placeholder="Bobot Pertanyaan" min="0" max="100" name="bobot" value="<?= $analisis_indikator['bobot'] ?? 1; ?>">
										</div>
									</div>
								</div>
								<div class="col-sm-12 delik">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="act_analisis">Aksi Analisis</label>
										<div class="btn-group col-sm-7" data-toggle="buttons">
											<label id="aksi1" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?= jecho($analisis_indikator['act_analisis'], '1', 'active') ?>">
												<input id="aksi1" type="radio" name="act_analisis" class="form-check-input" type="radio" value="1" <?= jecho($analisis_indikator['act_analisis'], '1', 'checked') ?> autocomplete="off"> Ya
											</label>
											<label id="aksi2" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?= jecho($analisis_indikator['act_analisis'] == '2' || $analisis_indikator['act_analisis'] == null, true, 'active') ?>">
												<input id="aksi2" type="radio" name="act_analisis" class="form-check-input" type="radio" value="2" <?= jecho($analisis_indikator['act_analisis'] == '2' || $analisis_indikator['act_analisis'] == null, true, 'checked') ?> autocomplete="off"> Tidak
											</label>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="act_analisis">Publikasi Indikator</label>
										<div class="btn-group col-sm-7" data-toggle="buttons">
											<label id="ss1" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?= jecho($analisis_indikator['is_publik'], '1', 'active') ?>">
												<input type="radio" name="is_publik" class="form-check-input" type="radio" value="1" <?= jecho($analisis_indikator['is_publik'], '1', 'checked') ?> autocomplete="off"> Ya
											</label>
											<label id="ss2" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?= jecho($analisis_indikator['is_publik'] == '0' || $analisis_indikator['is_publik'] == null, true, 'active') ?>">
												<input type="radio" name="is_publik" class="form-check-input" type="radio" value="0" <?= jecho($analisis_indikator['is_publik'] == '0' || $analisis_indikator['is_publik'] == null, true, 'checked') ?> autocomplete="off"> Tidak
											</label>
										</div>
										<label class="col-sm-3 control-label"></label>
										<div class="col-sm-7"><p class="help-block small"><code>*) Tampilkan data indikator di halaman depan website desa melalui menu statis/menu atas</code></p></div>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
							<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	function tampil_delik(tipe) {
		(tipe == '1' || tipe == null) ? $('.delik').show() : $('.delik').hide();
	}

	$(function() {
		checked("<?= $analisis_indikator['id_tipe']; ?>");
		tampil_delik($('input[name=id_tipe]:checked').val());
		$('input[name="id_tipe"]').change(function() {
			tampil_delik($(this).val());
		});

		$("#referensi").change(function () {

			let tipe = $("#referensi option:selected").data('tipe');

			if (tipe) {
				$(".tipe").addClass('disabled');
			} else {
				$(".tipe").removeClass('disabled');
			}
			checked(tipe);
		});
	});

	function reset_form() {
		<?php if ($analisis_indikator['is_publik'] == '1'): ?>
			$("#ss2").removeClass("active");
			$("#ss1").addClass('active');
		<?php else: ?>
			$("#ss2").addClass('active');
			$("#ss1").removeClass("active");
		<?php endif ?>

		<?php if ($analisis_indikator['act_analisis'] == '1'): ?>
			$("#aksi2").removeClass("active");
			$("#aksi1").addClass('active');
		<?php else: ?>
			$("#aksi2").addClass('active');
			$("#aksi1").removeClass("active");
		<?php endif ?>
		checked("<?= $analisis_indikator['id_tipe']; ?>");
	};

	function checked(id_tipe) {
		$(".tipe").removeClass("active");
		$("input[name=id_tipe").prop( "checked", false );
		if (id_tipe == 2) {
			$("#sx2").addClass('active');
			$("#group2").prop( "checked", true );
		} else if (id_tipe == 3) {
			$("#sx1").addClass('active');
			$("#group1").prop( "checked", true );
		} else if (id_tipe == 4) {
			$("#sx4").addClass('active');
			$("#group4").prop( "checked", true );
		} else {
			$("#sx3").addClass('active');
			$("#group3").prop( "checked", true );
		}

		tampil_delik($('input[name=id_tipe]:checked').val());
	}
</script>


