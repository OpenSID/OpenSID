<style type="text/css">
  .disabled
	{
     pointer-events: none;
     cursor: default;
  }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Buku Administrasi Pembangunan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<?php if($subtitle[1]===0){?>	
				<li><?=$subtitle[0]?></li>
			<?php } else { ?>			
				<li class="active"> <a href="<?= site_url($subtitle[0]) ?>"><i class="fa fa-dashboard"></i><?=$subtitle[1]?></a>
				<li class="active"></li><?=$subtitle[2]?></li>
			<?php } ?>	
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('bumindes/pembangunan/side') ?>
			</div>
			<div class="col-md-9">
				<?php $this->load->view($main_content) ?>
			</div>
		</div>
	</section>
</div>
