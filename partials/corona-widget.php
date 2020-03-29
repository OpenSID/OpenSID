<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
 $data_positif = json_decode(file_get_contents('https://api.kawalcorona.com/positif'), true);
 $data_sembuh = json_decode(file_get_contents('https://api.kawalcorona.com/sembuh'), true);
 $data_meninggal = json_decode(file_get_contents('https://api.kawalcorona.com/meninggal'), true);
 $data = json_decode(file_get_contents('https://api.kawalcorona.com/indonesia'), true);
 
 $name = $data[0]['name'];
 $positif = str_replace(",","",$data[0]['positif']);
 $sembuh = str_replace(",","",$data[0]['sembuh']);
 $meninggal = str_replace(",","",$data[0]['meninggal']);
 $perawatan = $positif-($sembuh+$meninggal);
?>
<div class="archive_style_1" style="font-family: Oswald">
    <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">COVID-19 Global</span></h2>
    <div class="row">
    <div style="margin-top:10px;">
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
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">COVID-19 di <?= $name; ?></span></h2>
    <div class="row">
    <div style="margin-top:10px; margin-bottom:10px;">
            <div class="col-lg-3 col-md-3 col-sm-3">
				<div class="panel panel-danger">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Konfirmasi</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($positif); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
            <div class="col-lg-3 col-md-3 col-sm-3">
				<div class="panel panel-warning">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Dalam Perawatan</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($perawatan); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
            <div class="col-lg-3 col-md-3 col-sm-3">
				<div class="panel panel-info">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Sembuh (<?= number_format($sembuh/$positif*100,2); ?>%)</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($sembuh); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
            <div class="col-lg-3 col-md-3 col-sm-3">
				<div class="panel panel-success">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Meninggal (<?= number_format($meninggal/$positif*100,2); ?>%)</h4></div>
					<div style="height: 40px;padding:1px" class="panel-body text-center">
						<h4><?= number_format($meninggal); ?> <small>Orang</small></h4>
					</div>
				</div>
			</div>
            <div class="col-md-12">
                    <div class="progress-group">
                        <a href="https://kawalcorona.com/" rel="noopener noreferrer" target="_blank">
							<button type="button" class="btn btn-info btn-block">Sumber : kawalcorona.com</button>
						</a>
                    </div>
            </div>
    </div>
    </div>
</div>
