<link rel="stylesheet" href="<?= asset('css/bagan.css') ?>">

<div class="content-wrapper">
	<section class="content-header">
		<?php $pemerintah = ucwords(setting('sebutan_pemerintah_desa')) ?>
		<h1>Bagan <?= $pemerintah ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('pengurus')?>"><?= $pemerintah ?></a></li>
			<li class="active">Bagan <?= $pemerintah ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-body">
						<div id="container"></div>
						<p class="highcharts-description"></p>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('home/chart_bagan') ?>