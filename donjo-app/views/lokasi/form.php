<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Lokasi</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('plan')?>"><i class="fa fa-dashboard"></i> Daftar Lokasi</a></li>
			<li class="active">Pengaturan Lokasi</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
	<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('plan/nav.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url("plan")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Lokasi
            	</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label col-sm-3">Nama Lokasi / Properti</label>
								<div class="col-sm-7">
									<input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" value="<?=$lokasi['nama']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Kategori</label>
								<div class="col-sm-7">
									<select class="form-control input-sm required" id="ref_point" name="ref_point" style="width:100%;">
									<option value="">Kategori</option>
									<?php foreach ($list_point AS $data): ?>
										<option <?php if ($lokasi['ref_point']==$data['id']): ?>selected<?php endif ?> value="<?= $data['id']?>"><?= $data['nama']?></option>
									<?php endforeach;?>
									</select>
								</div>
							</div>
							<?php if ($lokasi["foto"]): ?>
								<div class="form-group">
									<label class="control-label col-sm-3"></label>
									<div class="col-sm-7">
									  <img class="attachment-img img-responsive img-circle" src="<?= base_url().LOKASI_FOTO_LOKASI?>kecil_<?= $lokasi['foto']?>" alt="Foto">
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
									<textarea id="desk" name="desk" class="form-control input-sm required" style="height: 200px;"><?= $lokasi['desk']?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-3 col-lg-3 control-label" for="status">Status</label>
								<div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
									<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($lokasi['enabled'] =='1' OR $lokasi['enabled'] == NULL): ?>active<?php endif ?>">
										<input id="sx1" type="radio" name="enabled" class="form-check-input" type="radio" value="1" <?php if ($lokasi['enabled'] =='1' OR $lokasi['enabled'] == NULL): ?>checked <?php endif ?> autocomplete="off"> Aktif
									</label>
									<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($lokasi['enabled'] == '2' ): ?>active<?php endif ?>">
										<input id="sx2" type="radio" name="enabled" class="form-check-input" type="radio" value="2" <?php if ($lokasi['enabled'] == '2' ): ?>checked<?php endif ?> autocomplete="off"> Tidak Aktif
									</label>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
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
		<?php if ($lokasi['enabled'] =='1' OR $lokasi['enabled'] == NULL): ?>
			$("#sx3").addClass('active');
			$("#sx4").removeClass("active");
		<?php endif ?>
		<?php if ($lokasi['enabled'] =='2'): ?>
			$("#sx4").addClass('active');
			$("#sx3").removeClass("active");
		<?php endif ?>
	};
</script>
