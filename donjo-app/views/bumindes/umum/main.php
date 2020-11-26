<style type="text/css">
  .disabled
	{
     pointer-events: none;
     cursor: default;
  }
</style>
<div class="content-wrapper">
  <div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						<?= $subtitle ?>
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active"><?= $subtitle ?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
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
