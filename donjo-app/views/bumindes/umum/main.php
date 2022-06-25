<style type="text/css">
  .disabled
	{
     pointer-events: none;
     cursor: default;
  }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Buku Administrasi Umum - <?= $subtitle ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active"><?= $subtitle ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div id="umum-sidebar" class="col-sm-3">
				<?php $this->load->view('bumindes/umum/side') ?>
			</div>
			<div id="umum-content" class="col-sm-9">
				<?php $this->load->view($main_content) ?>
			</div>
		</div>
	</section>
</div>
