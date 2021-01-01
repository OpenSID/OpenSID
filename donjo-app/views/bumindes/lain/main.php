<style type="text/css">
  .disabled
	{
     pointer-events: none;
     cursor: default;
  }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Buku Administrasi Lainnya</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active"><?= $subtitle ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('bumindes/lain/side') ?>
			</div>
			<div class="col-md-9">
				<?php $this->load->view($main_content) ?>
			</div>
		</div>
	</section>
</div>
