<link rel="stylesheet" href="<?= base_url()?>assets/css/bagan.css">

<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Bagan Pemerintahan <?= ucwords($this->setting->sebutan_desa)?>
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url('pengurus')?>">Pemerintahan <?= ucwords($this->setting->sebutan_desa)?></a></li>
						<li class="breadcrumb-item active">Bagan Pemerintahan <?= ucwords($this->setting->sebutan_desa)?></li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-body">
						<figure class="highcharts-figure">
					    <div id="container"></div>
					    <p class="highcharts-description">
					    </p>
						</figure>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php include('donjo-app/views/home/chart_bagan.php'); ?>
