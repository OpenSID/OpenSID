<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
//API Local Data COVID19
$odp = $covid[0]; //"Orang Dalam Pemantauan (ODP)" => "ODP",
$pdp = $covid[1]; //"Pasien Dalam Pengawasan (PDP)" => "PDP",
$odr = $covid[2]; //"Orang Dalam Resiko (ODR)" => "ODR"
$otg = $covid[3]; //"Orang Tanpa Gejala (OTG)" => "OTG",
$positif = $covid[4]; //"Positif Covid-19" => "POSITIF",
?>

<div class="archive_style_1" style="font-family: Oswald">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Status COVID-19 di <?= ucwords($this->setting->sebutan_desa); ?></span></h2>
	<div class="row">
		<div style="margin-top:10px;">
			<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
				<div class="panel panel-default">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>ODR</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($odr['jumlah']); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
				<div class="panel panel-info">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>ODP</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($odp['jumlah']); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">
				<div class="panel panel-success">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>PDP</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($pdp['jumlah']); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
				<div class="panel panel-warning">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>OTG</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($otg['jumlah']); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				<div class="panel panel-danger">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Positif</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($positif['jumlah']); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="progress-group">
					<a href="<?= site_url('first/statistik/covid')?>">
						<button type="button" class="btn btn-info btn-block">Lihat info lengkap di Statistik COVID19</button>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
