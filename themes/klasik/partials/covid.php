<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
 // $data_positif = json_decode(file_get_contents('https://api.kawalcorona.com/positif'), true);
 // $data_sembuh = json_decode(file_get_contents('https://api.kawalcorona.com/sembuh'), true);
 // $data_meninggal = json_decode(file_get_contents('https://api.kawalcorona.com/meninggal'), true);

 if (empty(config_item('provinsi_covid')))
 {
	 $data = json_decode(file_get_contents('https://api.kawalcorona.com/indonesia'), true);
 	 $name = $data[0]['name'];
	 $positif = str_replace(",","",$data[0]['positif']);
	 $sembuh = str_replace(",","",$data[0]['sembuh']);
	 $meninggal = str_replace(",","",$data[0]['meninggal']);
 }
 else
 {
	 // Kode provinsi sesuai dengan yg di http://pusatkrisis.kemkes.go.id/daftar-kode-provinsi
	 $data = json_decode(file_get_contents('https://api.kawalcorona.com/indonesia/provinsi'), true);
	 $data = array_column($data, 'attributes');
	 $provinsi = array_search(config_item('provinsi_covid'), array_column($data, 'Kode_Provi'));
 	 $name = $data[$provinsi]['Provinsi'];
	 $positif = str_replace(",","",$data[$provinsi]['Kasus_Posi']);
	 $sembuh = str_replace(",","",$data[$provinsi]['Kasus_Semb']);
	 $meninggal = str_replace(",","",$data[$provinsi]['Kasus_Meni']);
 }
 $perawatan = $positif - ($sembuh + $meninggal);

 /*
 	 Untuk memudahkan testing tampilan, gunakan data berikut dan comment pengambilan data di atas.
 */
 // $data_positif['value'] = '678,720';
 // $data_sembuh['value'] = '145,609';
 // $data_meninggal['value'] = '31,700';

 // $positif = 1285;
 // $sembuh = 1107;
 // $meninggal = 64;
 // $perawatan = 114;

?>

<style type="text/css">
	#covid {
		margin-right: 8px;
		margin-left: 7px;
	}
	#covid .panel {
		background-color: inherit;
		margin-bottom: 0px;
	}
	#covid .panel-body {
		background-color: white;
	}
	#covid .panel-body.sub {
		background-color: inherit;
		padding-top: 10px;
	}
	#covid .row .panel-heading {
		height: 40px;
		padding: 1px;
	}
</style>

<?php if (!empty($positif)): ?>
	<div id="covid">
<!-- 		<div class="panel">
			<div class="box-header with-border">
			  <h3 class="box-title"> 
			  	<span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">COVID-19 Global</span>
			  </h3>
			</div>
		  <div class="panel-body sub">
		    <div class="row">
		      <div class="col-lg-4 col-md-4 col-sm-4">
						<div class="panel panel-danger">
							<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Positif</h4></div>
							<div style="height: 40px;padding:1px" class="panel-body text-center">
								<h4><?= $data_positif['value']; ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
		      <div class="col-lg-4 col-md-4 col-sm-4">
						<div class="panel panel-info">
							<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Sembuh</h4></div>
							<div style="height: 40px;padding:1px" class="panel-body text-center">
								<h4><?= $data_sembuh['value']; ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
		      <div class="col-lg-4 col-md-4 col-sm-4">
						<div class="panel panel-success">
							<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Meninggal</h4></div>
							<div style="height: 40px;padding:1px" class="panel-body text-center">
								<h4><?= $data_meninggal['value']; ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> -->
		<div class="panel">
			<div class="box-header with-border">
			  <h3 class="box-title"> 
			  	<span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">COVID-19 di <?= $name; ?></span>
			  </h3>
			</div>
			<div class="panel-body sub">
			  <div class="row">
		      <div class="col-lg-3 col-md-3 col-sm-3">
						<div class="panel panel-danger">
							<div style="padding:1px" class="panel-heading text-center"><h4>Positif<br>&nbsp;</h4></div>
							<div style="height: 40px;padding:1px" class="panel-body text-center">
								<h4><?= number_format($positif); ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
			    <div class="col-lg-3 col-md-3 col-sm-3">
						<div class="panel panel-warning">
							<div style="padding:1px" class="panel-heading text-center"><h4>Dalam Perawatan</h4></div>
							<div style="height: 40px;padding:1px" class="panel-body text-center">
								<h4><?= number_format($perawatan); ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
			    <div class="col-lg-3 col-md-3 col-sm-3">
						<div class="panel panel-info">
							<div style="padding:1px" class="panel-heading text-center"><h4>Sembuh (<?= number_format($sembuh/$positif*100,2); ?>%)</h4></div>
							<div style="height: 40px; padding:1px" class="panel-body text-center">
								<h4><?= number_format($sembuh); ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
			    <div class="col-lg-3 col-md-3 col-sm-3">
						<div class="panel panel-success">
							<div style="padding:1px" class="panel-heading text-center"><h4>Meninggal (<?= number_format($meninggal/$positif*100,2); ?>%)</h4></div>
							<div style="height: 40px;padding:1px" class="panel-body text-center">
								<h4><?= number_format($meninggal); ?> <small>Orang</small></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
		  <div class="col-md-12">
		    <div class="progress-group">
		      <a href="https://kawalcorona.com/" rel="noopener noreferrer" target="_blank">
						<button type="button" class="btn btn-info btn-block">Sumber : kawalcorona.com</button>
					</a>
		    </div>
		  </div>
		</div>
	</div>
<?php endif; ?>