<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Area</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('area')?>"><i class="fa fa-dashboard"></i> Daftar Area</a></li>
			<li class="active">Pengaturan Area</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
	<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('plan/nav.php')?>
				</div>
				<div class="col-md-9">
					<div class="card card-outline card-info">
            <div class="card-header with-border">
							<a href="<?= site_url("area")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Area
            	</a>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label class="control-label col-sm-3">Nama Area / Properti</label>
								<div class="col-sm-7">
									<input name="nama" class="form-control form-control-sm nomor_sk required" maxlength="100" type="text" value="<?=$area['nama']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Kategori</label>
								<div class="col-sm-7">
									<select class="form-control form-control-sm" id="ref_polygon" name="ref_polygon" style="width:100%;">
									<option value="">Kategori</option>
									<?php foreach ($list_polygon AS $data): ?>
										<option <?php if ($area['ref_polygon']==$data['id']): ?>selected<?php endif ?> value="<?= $data['id']?>"><?= $data['nama']?></option>
									<?php endforeach;?>
									</select>
								</div>
							</div>
							<?php if ($area["foto"]): ?>
								<div class="form-group">
									<label class="control-label col-sm-3"></label>
									<div class="col-sm-7">
									  <img class="attachment-img img-fluid rounded-circle" src="<?= base_url().LOKASI_FOTO_AREA?>kecil_<?= $area['foto']?>" alt="Foto">
									</div>
								</div>
							<?php endif; ?>
							<div class="form-group">
								<label class="control-label col-sm-3">Ganti Foto</label>
								<div class="col-sm-7">
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path">
										<input id="file" type="file" class="hidden" name="foto">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
									<p class="help-block small text-red">Kosongkan jika tidak ingin mengubah foto.</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Keterangan</label>
								<div class="col-sm-7">
									<textarea id="desk" name="desk" class="form-control form-control-sm" style="height: 200px;"><?= $area['desk']?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-3 col-lg-3 control-label" for="status">Status</label>
								<div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
									<label id="sx3" class="btn btn-info btn-flat btn-xs col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($area['enabled'] =='1' OR $area['enabled'] == NULL): ?>active<?php endif ?>">
										<input id="sx1" type="radio" name="enabled" class="form-check-input" type="radio" value="1" <?php if ($area['enabled'] =='1' OR $area['enabled'] == NULL): ?>checked <?php endif ?> autocomplete="off"> Aktif
									</label>
									<label id="sx4" class="btn btn-info btn-flat btn-xs col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($area['enabled'] == '2' ): ?>active<?php endif ?>">
										<input id="sx2" type="radio" name="enabled" class="form-check-input" type="radio" value="2" <?php if ($area['enabled'] == '2' ): ?>checked<?php endif ?> autocomplete="off"> Tidak Aktif
									</label>
								</div>
							</div>
						</div>
						<div class='card-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-flat btn-danger btn-xs' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-flat btn-info btn-xs pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	function reset_form()
	{
		<?php if ($area['enabled'] =='1' OR $area['enabled'] == NULL): ?>
			$("#sx3").addClass('active');
			$("#sx4").removeClass("active");
		<?php endif ?>
		<?php if ($area['enabled'] =='2'): ?>
			$("#sx4").addClass('active');
			$("#sx3").removeClass("active");
		<?php endif ?>
	};
</script>
