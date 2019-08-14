<style type="text/css">
	.nowrap { white-space: nowrap; }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Keuangan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Laporan Keuangan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('keuangan/menu_laporan');?>
			</div>
			<div class="col-md-9">
				<?php include("donjo-app/views/keuangan/grafik_r_pd_chart.php"); ?>
			</div>
		</div>
	</section>
</div>

