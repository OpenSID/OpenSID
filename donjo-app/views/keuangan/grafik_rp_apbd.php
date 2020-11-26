<style type="text/css">
	.nowrap { white-space: nowrap; }
</style>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Laporan Keuangan
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fas fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url('keuangan/laporan')?>">Laporan Keuangan</a></li>
						<li class="breadcrumb-item active">Grafik Pelaksanaan Belanja Desa</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="row">
			<?php $this->load->view('keuangan/filter_laporan', array('data' => $tahun_anggaran)); ?>
			<div class="col-md-9">
				<?php include("donjo-app/views/keuangan/grafik_rp_apbd_chart.php"); ?>
			</div>
		</div>
	</section>
</div>
