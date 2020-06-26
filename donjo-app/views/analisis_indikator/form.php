<script>
	$(function()
	{
		if ($('input[name=id_tipe]:unchecked').next('label').text()=='Pilihan (Tunggal)'):
			$('div.delik').hide();
		endif;
		if ($('input[name=id_tipe]:checked').next('label').text()=='Pilihan (Tunggal)'):
			$('div.delik').show();
		endif;
		$('input[name=id_tipe]').click(function()
		{
			if ($(this).next('label').text()=='Pilihan (Tunggal)'):
				$('div.delik').show();
			else:
				$('div.delik').hide();
			endif;
		});
	});
</script>
<script>
	function reset_form()
	{
		<?php if ($analisis_indikator['is_publik'] =='1' OR $analisis_indikator['is_publik'] == NULL): ?>
			$("#ss1").addClass('active');
			$("#ss2").removeClass("active");
		<?php endif ?>
		<?php if ($analisis_indikator['is_publik'] =='2'): ?>
			$("#ss2").addClass('active');
			$("#ss1").removeClass("active");
		<?php endif ?>

		<?php if ($analisis_indikator['id_tipe'] =='1' OR $analisis_indikator['id_tipe'] == NULL): ?>
			$("#sx4").removeClass("active");
			$("#sx3").addClass('active');
			$("#sx2").removeClass("active");
			$("#sx1").removeClass("active");
		<?php endif ?>
		<?php if ($analisis_indikator['id_tipe'] =='2'): ?>
			$("#sx4").removeClass("active");
			$("#sx2").addClass('active');
			$("#sx3").removeClass("active");
			$("#sx1").removeClass("active");
		<?php endif ?>
		<?php if ($analisis_indikator['id_tipe'] =='3'): ?>
			$("#sx4").removeClass("active");
			$("#sx1").addClass('active');
			$("#sx3").removeClass("active");
			$("#sx2").removeClass("active");
		<?php endif ?>
		<?php if ($analisis_indikator['id_tipe'] =='4'): ?>
			$("#sx1").removeClass("active");
			$("#sx4").addClass('active');
			$("#sx3").removeClass("active");
			$("#sx2").removeClass("active");
		<?php endif ?>
	};
</script>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Indikator Analisis [ <?= $analisis_master['nama']?> ]</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('analisis_master')?>"> Master Analisis</a></li>
			<li><a href="<?= site_url()?>analisis_indikator">Indikator Analisis</a></li>
			<li class="active">Pengaturan Indikator Analisis</li>
		</ol>
	</section>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left',$data);?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url()?>analisis_indikator" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Indikator Analisis</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="box-body">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-sm-3 control-label" for="id_tipe">Tipe Pertanyaan</label>
												<div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
													<label id="sx3" <?php if ($analisis_master['jenis']==1): ?>disabled="disabled"<?php endif; ?> class="btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label <?php if ($analisis_indikator['id_tipe'] =='1' OR $analisis_indikator['id_tipe'] == NULL): ?>active<?php endif ?>">
														<input id="group3" type="radio" name="id_tipe" class="form-check-input" type="radio" value="1" <?php if ($analisis_indikator['id_tipe'] =='1' OR $analisis_indikator['id_tipe'] == NULL): ?>checked <?php endif ?> autocomplete="off">Pilihan (Multiple Choice)
													</label>
													<label id="sx2" <?php if ($analisis_master['jenis']==1): ?>disabled="disabled"<?php endif; ?> class="btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label <?php if ($analisis_indikator['id_tipe'] =='2'): ?>active<?php endif ?>">
														<input id="group2" type="radio" name="id_tipe" class="form-check-input" type="radio" value="2" <?php if ($analisis_indikator['id_tipe'] =='2'): ?>checked <?php endif ?> autocomplete="off">Pilihan (Checkboxes)
													</label>
													<label id="sx1" <?php if ($analisis_master['jenis']==1): ?>disabled="disabled"<?php endif; ?> class="btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label <?php if ($analisis_indikator['id_tipe'] =='3'): ?>active<?php endif ?>">
														<input id="group1" type="radio" name="id_tipe" class="form-check-input" type="radio" value="3" <?php if ($analisis_indikator['id_tipe'] =='3'): ?>checked <?php endif ?> autocomplete="off">Isian Jumlah (Kuantitatif)
													</label>
													<label id="sx4" <?php if ($analisis_master['jenis']==1): ?>disabled="disabled"<?php endif; ?> class="btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label <?php if ($analisis_indikator['id_tipe'] =='4'): ?>active<?php endif ?>">
														<input id="group4" type="radio" name="id_tipe" class="form-check-input" type="radio" value="4" <?php if ($analisis_indikator['id_tipe'] =='4'): ?>checked <?php endif ?> autocomplete="off">Isian Teks (Kualitatif)
													</label>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-sm-3 control-label" for="nomor">Kode Pertanyaan</label>
												<div class="col-sm-2">
													<input id="nomor" class="form-control input-sm bilangan" type="text" placeholder="Kode Pertanyaan" name="nomor" value="<?= $analisis_indikator['nomor']?>" <?php if ($analisis_master['jenis']==1): ?> readonly="readonly" <?php endif; ?>>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group" id="delik">
												<label class="col-sm-3 control-label" for="pertanyaan">Pertanyaan</label>
												<div class="col-sm-8">
													<textarea  id="pertanyaan" class="form-control input-sm required" placeholder="Pertanyaan" name="pertanyaan" <?php if ($analisis_master['jenis']==1): ?> readonly="readonly" <?php endif; ?>><?= $analisis_indikator['pertanyaan']?></textarea>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-sm-3 control-label" for="id_tipe">Kategori Indikator</label>
												<div class="col-sm-5">
													<select class="form-control select2 required"  id="id_kategori" name="id_kategori" <?php if ($analisis_master['jenis']==1): ?>disabled="disabled"<?php endif; ?>>
														<option value="" selected="selected">-- Kategori Indikator--</option>
														<?php foreach ($list_kategori AS $data): ?>
															<option value="<?= $data['id']?>"  <?php if ($analisis_indikator['id_kategori'] == $data['id']): ?>selected <?php endif; ?>><?= $data['kategori']?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-12 hide">
											<div class="form-group" id="delik">
												<label class="col-sm-3 control-label" for="bobot">Bobot</label>
												<div class="col-sm-2">
													<input  id="bobot" class="form-control input-sm" type="text" placeholder="Bobot Pertanyaan" name="bobot" value="<?php if ($analisis_indikator['bobot']==""): ?>1<?php else: ?><?=$analisis_indikator['bobot'];?><?php endif; ?>">
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group" id="delik">
												<label class="col-sm-3 control-label" for="act_analisis">Publikasi Indikator</label>
												<div class="btn-group col-sm-7" data-toggle="buttons">
													<label id="ss1" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($analisis_indikator['is_publik'] =='1'): ?>active<?php endif ?>">
														<input id="g1" type="radio" name="is_publik" class="form-check-input" type="radio" value="1" <?php if ($analisis_indikator['is_publik']=='1'): ?>checked <?php endif ?> autocomplete="off"> Ya
													</label>
													<label id="ss2" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($analisis_indikator['is_publik'] == '0' OR $analisis_indikator['is_publik'] ==NULL): ?>active<?php endif ?>">
														<input id="g2" type="radio" name="is_publik" class="form-check-input" type="radio" value="0" <?php if ($analisis_indikator['is_publik'] == '0' OR $analisis_indikator['is_publik'] ==NULL): ?>checked<?php endif ?> autocomplete="off"> Tidak
													</label>
												</div>
												<label class="col-sm-3 control-label"></label>
												<div class="col-sm-7"><p class="help-block small">*) Tampilkan data indikator di halaman depan website desa (Menu Data Desa -> Data Analisis).</p></div>
											</div>
										</div>
									</div>
								</div>
								<div class="box-footer">
									<div class="col-xs-12">
										<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
										<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

