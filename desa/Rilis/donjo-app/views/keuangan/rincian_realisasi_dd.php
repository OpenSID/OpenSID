<style type="text/css">
	.nowrap { white-space: nowrap; }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Keuangan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('keuangan/laporan')?>">Laporan Keuangan</a></li>
			<li class="active">Rincian Realisasi</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<?php $this->load->view('keuangan/filter_laporan', ['data' => $tahun_anggaran]); ?>
			<div class="col-md-9">
				<?php include 'donjo-app/views/keuangan/tabel_laporan_rp_apbd_dd.php'; ?>
			</div>
		</div>
	</section>
</div>
