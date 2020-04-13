<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (config_item('kode_kota')): ?>
<?php
$tgl = date('Y-m-d');
$tgl_besok = mktime(0,0,0,date("n"),date("j")+1,date("Y"));
$besok = date("Y-m-d", $tgl_besok);
$kota = config_item('kode_kota');

$kode = json_decode(file_get_contents('https://api.banghasan.com/sholat/format/json/kota/kode/'.$kota), true);
$nama = $kode['kota'][0]['nama'];

$waktu = json_decode(file_get_contents('https://api.banghasan.com/sholat/format/json/jadwal/kota/'.$kota.'/tanggal/'.$tgl), true);
$hari = $waktu['jadwal']['data']['tanggal'];
$imsak = $waktu['jadwal']['data']['imsak'];
$subuh = $waktu['jadwal']['data']['subuh'];
$terbit = $waktu['jadwal']['data']['terbit'];
$dhuha = $waktu['jadwal']['data']['dhuha'];
$dzuhur = $waktu['jadwal']['data']['dzuhur'];
$ashar = $waktu['jadwal']['data']['ashar'];
$maghrib = $waktu['jadwal']['data']['maghrib'];
$isya = $waktu['jadwal']['data']['isya'];

$waktu2 = json_decode(file_get_contents('https://api.banghasan.com/sholat/format/json/jadwal/kota/'.$kota.'/tanggal/'.$besok), true);
$hari2 = $waktu2['jadwal']['data']['tanggal'];
$imsak2 = $waktu2['jadwal']['data']['imsak'];
$subuh2 = $waktu2['jadwal']['data']['subuh'];
$terbit2 = $waktu2['jadwal']['data']['terbit'];
$dhuha2 = $waktu2['jadwal']['data']['dhuha'];
$dzuhur2 = $waktu2['jadwal']['data']['dzuhur'];
$ashar2 = $waktu2['jadwal']['data']['ashar'];
$maghrib2 = $waktu2['jadwal']['data']['maghrib'];
$isya2 = $waktu2['jadwal']['data']['isya'];
?>
<style>
	.data-case-container {
		background-color: #FFF8DC; width:100%; height: 100%; padding: 0px; margin: 0px;
	}
	.data-case-container li,
	.data-case-container li p,
	.data-case-container li td {
		font-family: "ants-thin", "Century Gothic", Arial; font-size: 12px;
	}
	.data-case-container li.info-date {
		font-family: "ants-strong", "Century Gothic", Arial; font-size: 14px;
		font-weight: bold; padding: 5px; text-align: center;
	}
	.data-case-container li.info-region {
		padding: 4px 5px; background-color: #e64946; color: #fff; text-align: center;
		font-family: "ants-strong", "Century Gothic", Arial; font-size: 10px; font-weight: bold;
	}
		
	.data-case-container table td {
		padding: 3px 5px; border-bottom: 1px solid #e4bd2e;
	}
	.data-case-container table tr:last-child td {
		border-bottom: none;
	}
	.data-case-container table td.dot {
		width: 5px; white-space: nowrap; text-align: center;
	}
	.data-case-container table td.case {
		width: 5px; white-space: nowrap; text-align: right;
	}
</style>
<div class="archive_style_1">
    <div class="single_bottom_rightbar">
        <h2 class="box-title"><i class="fa fa-calendar"></i> JADWAL SHOLAT & IMSAK</h2>
        <div class="data-case-container">
            <ul class="ants-right-headline">
                <li class="info-date"><?= $nama ?></li>
                <li class="info-case">
                    <table style="width: 100%;" cellpadding="0" cellspacing="0">
                        <tr>
                            <td colspan="3" class="description"><li class="info-region"><?= $hari ?></li></td>
                            <td colspan="3" class="description"><li class="info-region"><?= $hari2 ?></li></td>
                        </tr>
                        <tr>
                            <td class="description">Imsak</td><td class="dot">:</td><td class="case"><?= $imsak ?></td>
                            <td class="description">Imsak</td><td class="dot">:</td><td class="case"><?= $imsak2 ?></td>
                        </tr>
                        <tr>
                            <td class="description">Subuh</td><td class="dot">:</td><td class="case"><?= $subuh ?></td>
                            <td class="description">Subuh</td><td class="dot">:</td><td class="case"><?= $subuh2 ?></td>
                        </tr>
                        <tr>
                            <td class="description">Terbit</td><td class="dot">:</td><td class="case"><?= $terbit ?></td>
                            <td class="description">Terbit</td><td class="dot">:</td><td class="case"><?= $terbit2 ?></td>
                        </tr>
                        <tr>
                            <td class="description">Dhuha</td><td class="dot">:</td><td class="case"><?= $dhuha ?></td>
                            <td class="description">Dhuha</td><td class="dot">:</td><td class="case"><?= $dhuha2 ?></td>
                        </tr>
                        <tr>
                            <td class="description">Dzuhur</td><td class="dot">:</td><td class="case"><?= $dzuhur ?></td>
                            <td class="description">Dzuhur</td><td class="dot">:</td><td class="case"><?= $dzuhur2 ?></td>
                        </tr>
                        <tr>
                            <td class="description">Ashar</td><td class="dot">:</td><td class="case"><?= $ashar ?></td>
                            <td class="description">Ashar</td><td class="dot">:</td><td class="case"><?= $ashar2 ?></td>
                        </tr>
                        <tr>
                            <td class="description">Maghrib</td><td class="dot">:</td><td class="case"><?= $maghrib ?></td>
                            <td class="description">Maghrib</td><td class="dot">:</td><td class="case"><?= $maghrib2 ?></td>
                        </tr>
                        <tr>
                            <td class="description">Isya</td><td class="dot">:</td><td class="case"><?= $isya ?></td>
                            <td class="description">Isya</td><td class="dot">:</td><td class="case"><?= $isya2 ?></td>
                        </tr>
            		</table>
            	</li>
        	</ul>
        </div>
    </div>
</div>
<?php endif; ?>
