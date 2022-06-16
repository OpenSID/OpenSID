
	<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Komentar</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Komentar</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?=site_url('komentar')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Komentar
            	</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label col-sm-2" for="owner">Pengirim</label>
								<div class="col-sm-9">
									<input name="owner" class="form-control input-sm required" type="text" maxlength="50" value="<?= $komentar['owner']?>"></input>
								</div>
							</div>
              <div class="form-group">
								<label class="control-label col-sm-2" for="no_hp">No. HP</label>
								<div class="col-sm-9">
									<input name="no_hp" class="form-control input-sm required bilangan" type="text" value="<?= $komentar['no_hp']?>"></input>
								</div>
							</div>
              <div class="form-group">
								<label class="control-label col-sm-2" for="email">Email</label>
								<div class="col-sm-9">
									<input name="email" class="form-control input-sm email" type="text" value="<?= $komentar['email']?>"></input>
								</div>
							</div>
              <div class="form-group">
								<label class="col-sm-2 control-label" for="komentar">Komentar</label>
								<div class="col-sm-9">
									<textarea id="komentar" name="komentar" class="form-control input-sm required" placeholder="Isi Komentar" style="height: 200px;"><?= $komentar['komentar']?></textarea>
								</div>
							</div>
              <div class="form-group">
								<label class="col-xs-12 col-sm-2 col-lg-2 control-label" for="status">Status</label>
								<div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
									<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($komentar['status'] == '1' || $komentar['status'] == null): ?>active<?php endif ?>">
										<input id="sx1" type="radio" name="status" class="form-check-input" type="radio" value="1" <?php if ($komentar['status'] == '1' || $komentar['status'] == null): ?>checked <?php endif ?> autocomplete="off"> Aktif
									</label>
									<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($komentar['status'] == '2'): ?>active<?php endif ?>">
										<input id="sx2" type="radio" name="status" class="form-check-input" type="radio" value="2" <?php if ($komentar['status'] == '2'): ?>checked<?php endif ?> autocomplete="off"> Tidak Aktif
									</label>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='button' class='btn btn-social btn-flat btn-danger btn-sm' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Batal</button>
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
		<?php if ($komentar['status'] == '1' || $komentar['status'] == null): ?>
			$("#sx3").addClass('active');
			$("#sx4").removeClass("active");
		<?php endif ?>
		<?php if ($komentar['status'] == '2'): ?>
			$("#sx4").addClass('active');
			$("#sx3").removeClass("active");
		<?php endif ?>
	};
</script>