<?php
/**
 * File ini:
 *
 * View di modul Pemetaan
 *
 * /donjo-app/views/point/form_simbol.php
 */
?>

<!-- TODO: Pindahkan ke external css -->
<style>
	.bs-glyphicons
	{
		padding-left: 0;
		padding-bottom: 1px;
		margin-bottom: 20px;
		list-style: none;
		overflow: hidden;
	}

	.bs-glyphicons .glyphicon
	{
		margin-top: 5px;
		margin-bottom: 10px;
		font-size: 24px;
	}

	.bs-glyphicons .glyphicon-class
	{
		display: block;
		text-align: center;
		font-size: 10px;
		height: 25px;
		word-wrap: break-word; /* Help out IE10+ with class names */
	}

	.bs-glyphicons li
	{
		float: left;
		width: 25%;
		height: 115px;
		padding: 10px;
		margin: 0 -1px -1px 0;
		font-size: 12px;
		line-height: 1.2;
		text-align: center;
		border: 1px solid #ddd;
	}
	.bs-glyphicons li:hover, .bs-glyphicons li.active
	{
		background-color: #605ca8;
		color:#fff;
	}

	@media (min-width: 768px)
	{
		.bs-glyphicons li
		{
			width: 12.5%;
		}
	}

	.vertical-scrollbar
	{
		overflow-x: hidden;
		overflow-y: auto;
	}

</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Simbol Lokasi</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Simbol Lokasi</li>
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
							<a href="#" id="btn_ikon" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i>Tambah Simbol Lokasi</a>
							<a href="<?= site_url('point/salin_simbol_default')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Salin Simbol Default">
								<i class="fa fa-copy"></i>Salin Simbol Default
            	</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<div class="col-sm-10">
									<div  class="vertical-scrollbar" style="max-height:460px;">
									  <ul id="icons" class="bs-glyphicons">
											<?php foreach ($simbol as $data): ?>
												<li>
													<label>
														<img src="<?= base_url(LOKASI_SIMBOL_LOKASI)?><?= $data['simbol']?>">
														<span class="glyphicon-class"><?= $data['simbol']?></span>
														<a href="#" data-href="<?= site_url("point/delete_simbol/{$data['id']}/{$data['simbol']}")?>" class="btn btn-danger btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
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
<!--MODAL TAMBAH SIMBOL-->
<div class="modal fade" id="ModalSimbol" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
				<h4 class="modal-title" id="myModalLabel">Tambah Simbol Lokasi Baru</h4>
			</div>
			<form id="mainform" name="mainform" action="<?=site_url('point/tambah_simbol')?>" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label col-sm-3">Pilih File Simbol</label>
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path">
										<input id="file" type="file" class="hidden" name="simbol">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal" aria-hidden="true"><i class='fa fa-sign-out'></i> Tutup</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="simpan"><i class='fa fa-check'></i>Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--END MODAL TAMBAH SIMBOL-->
<?php $this->load->view('global/confirm_delete'); ?>

<script type="text/javascript">
$(document).ready(function(){

	$('#btn_ikon').on('click',function(){
		$('#ModalSimbol').modal('show');
	});

});
</script>
