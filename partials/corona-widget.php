<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
 $data_positif = json_decode(file_get_contents('https://api.kawalcorona.com/positif'), true);
 $data_sembuh = json_decode(file_get_contents('https://api.kawalcorona.com/sembuh'), true);
 $data_meninggal = json_decode(file_get_contents('https://api.kawalcorona.com/meninggal'), true);

 $data = json_decode(file_get_contents('https://api.kawalcorona.com/'), true);
 $data = array_column($data, 'attributes');
 $negara = array_search(config_item('negara_covid'), array_column($data, 'OBJECTID'));
 $name = $data[$negara]['Country_Region'];
 $positif = $data[$negara]['Confirmed'];
 $meninggal = $data[$negara]['Deaths'];
 $sembuh = $data[$negara]['Recovered'];
 $perawatan = $data[$negara]['Active'];
 $update = str_replace("000","",$data[$negara]['Last_Update']);

 $data2 = json_decode(file_get_contents('https://api.kawalcorona.com/indonesia/provinsi'), true);
 $data2 = array_column($data2, 'attributes');
 $provinsi = array_search(config_item('provinsi_covid'), array_column($data2, 'Kode_Provi'));
 $prov_name = $data2[$provinsi]['Provinsi'];
 $prov_positif = str_replace(",","",$data2[$provinsi]['Kasus_Posi']);
 $prov_sembuh = str_replace(",","",$data2[$provinsi]['Kasus_Semb']);
 $prov_meninggal = str_replace(",","",$data2[$provinsi]['Kasus_Meni']);
 $prov_perawatan = $prov_positif - ($prov_sembuh + $prov_meninggal);
?>
<!--
Sisipkan script berikut ke dalam file desa/config/config.php
$config['provinsi_covid'] = 62; // kode provinsi. Comment baris ini untuk menampilkan data Kalimantan Tengah.
$config['negara_covid'] = 89; // kode negara. Comment baris ini untuk menampilkan data Indonesia.
-->
<div class="archive_style_1" style="font-family: Oswald">
    <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Statistik COVID-19</span></h2>
    <div class="row">
		<div style="margin-top:10px;">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="panel panel-danger">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Positif</h4></div>
					<div style="height: 100px;padding:1px" class="panel-body text-center">
						<h4><small>Global</small> <?= $data_positif['value']; ?> <small>Jiwa</small></h4>
						<?php if (!empty(config_item('negara_covid'))): ?>
						<h4><small>di <?= $name; ?></small> <?= number_format($positif); ?> <small>Jiwa</small></h4>
						<?php endif; ?>
						<?php if (!empty(config_item('provinsi_covid'))): ?>
						<h4><small>di <?= $prov_name; ?></small> <?= number_format($prov_positif); ?> <small>Jiwa</small></h4>
						<?php endif; ?>
					</div>
				</div>
			</div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="panel panel-info">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Sembuh</h4></div>
					<div style="height: 100px;padding:1px" class="panel-body text-center">
						<h4><small>Global</small> <?= $data_sembuh['value']; ?> <small>Jiwa</small></h4>
						<?php if (!empty(config_item('negara_covid'))): ?>
						<h4><small>di <?= $name; ?></small> <?= number_format($sembuh); ?> <small>Jiwa (<?= number_format($sembuh/$positif*100,2); ?>%)</small></h4>
						<?php endif; ?>
						<?php if (!empty(config_item('provinsi_covid'))): ?>
						<h4><small>di <?= $prov_name; ?></small> <?= number_format($prov_sembuh); ?> <small>Jiwa (<?= number_format($prov_sembuh/$prov_positif*100,2); ?>%)</small></h4>
						<?php endif; ?>
					</div>
				</div>
			</div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="panel panel-success">
					<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4>Meninggal</h4></div>
					<div style="height: 100px;padding:1px" class="panel-body text-center">
						<h4><small>Global</small> <?= $data_meninggal['value']; ?> <small>Jiwa</small></h4>
						<?php if (!empty(config_item('negara_covid'))): ?>
						<h4><small>di <?= $name; ?></small> <?= number_format($meninggal); ?> <small>Jiwa (<?= number_format($meninggal/$positif*100,2); ?>%)</small></h4>
						<?php endif; ?>
						<?php if (!empty(config_item('provinsi_covid'))): ?>
						<h4><small>di <?= $prov_name; ?></small> <?= number_format($prov_meninggal); ?> <small>Jiwa (<?= number_format($prov_meninggal/$prov_positif*100,2); ?>%)</small></h4>
						<?php endif; ?>
					</div>
				</div>
			</div>
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        		<div class="panel panel-warning">
					<div style="padding:3px" class="panel-heading text-center">Update : <?= date('d M Y H:i:s', gmdate($update));?> WIB</div>
				</div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        		<div class="panel panel-success">
					<div style="padding:3px" class="panel-heading text-center">
					<a href="https://kawalcorona.com/" rel="noopener noreferrer" target="_blank">Sumber : kawalcorona.com</a>
					</div>
				</div>
            </div>
		</div>
    </div>
</div>
