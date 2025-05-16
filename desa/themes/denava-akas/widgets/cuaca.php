<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
date_default_timezone_set($desa['timezone']);
$namaProvinsi = preg_replace('/\s+/', '', $desa['nama_propinsi']);
$xmlFile = "https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-".$namaProvinsi.".xml";
$xml = simplexml_load_file($xmlFile);
$tanggal = $xml->forecast->issue->day . "-" . $xml->forecast->issue->month . "-" . $xml->forecast->issue->year;
$jam = $xml->forecast->issue->hour . ":" . $xml->forecast->issue->minute . ":" . $xml->forecast->issue->second;
?>
<div class="head-widget flexleft bg-grey-dark2">
    <img src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/images/icon/location.svg") ?>" alt=""/> Prakiraan Cuaca<br>
</div>
<div class="widget-height bg-white border-grey-soft">
    <div class="widgetscroll">
        <div class="p-10">
			<?php if ($xml === false) : ?>
			Gagal menampilkan data. 
			<?php else : ?>
            <table class="jamkerja" style="width: 100%;border-radius:0 0 5px 5px 0;overflow:hidden;" cellpadding="0" cellspacing="0">
                <tbody>
                    <?php
                    $kodeCuaca = [
                        0 => 'Cerah',
                        1 => 'Cerah Berawan',
                        2 => 'Cerah Berawan',
                        3 => 'Berawan',
                        4 => 'Berawan Tebal',
                        5 => 'Udara Kabur',
                        10 => 'Asap',
                        45 => 'Kabut',
                        60 => 'Hujan Ringan',
                        61 => 'Hujan Sedang',
                        63 => 'Hujan Lebat',
                        80 => 'Hujan Lokal',
                        95 => 'Hujan Petir',
                        97 => 'Hujan Petir'
                    ];
                    ?>
                    <?php
                    $dates = []; // Simpan tanggal yang telah diproses
                    $today = date("d-m-Y"); // Tanggal hari ini
                    $tomorrow = date("d-m-Y", strtotime("+1 day")); // Tanggal esok
                    $dayAfterTomorrow = date("d-m-Y", strtotime("+2 days")); // Tanggal lusa
                    $labelhari = date("l", strtotime($tanggal));

                    $labels = [
                        $today => 'Hari Ini',
                        $tomorrow => 'Besok',
                        $dayAfterTomorrow => 'Lusa'
                    ];

                    $labelshari = [
                        $today => date("l", strtotime($today)),
                        $tomorrow => date("l", strtotime($tomorrow)),
                        $dayAfterTomorrow => date("l", strtotime($dayAfterTomorrow))
                    ];
                    
                    foreach ($xml->forecast->area as $area) {
                        $lokasi = preg_replace('/\s+/', '', $area->name[1]);
                        $latitude = $area['latitude'];
                        $longitude = $area['longitude'];
                        $coordinate = $area['coordinate'];
                        $description = $area['description'];
                        if ($lokasi == preg_replace('/\s+/', '', ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten']))) {
                            ?>
                            <p style="text-align: center;">Lokasi : <?= $area->name[1] ?><br>Koordinat : <?= $coordinate ?><br>Diperbarui <?= $tanggal ?> jam <?= $jam ?></p>
                            <?php
                            foreach ($area->parameter as $parameter) {
                                $id = (string)$parameter['id'];
                                if ($id == "weather") {
                                    foreach ($parameter->timerange as $timerange) {
                                        $waktu = date("d-m-Y H:i:s", strtotime($timerange['datetime']));
                                        list($tanggal, $jam) = explode(' ', $waktu);
                                        $weatherCode = (int)$timerange->value[0];
                                        $cuaca = isset($kodeCuaca[$weatherCode]) ? $kodeCuaca[$weatherCode] : 'Tidak diketahui';
                                        $ampm = date('H:i:s');
                                        $gambarCuaca = '';
                                        if ($weatherCode === 0) {
                                            $gambarCuaca = ($ampm >= '06:00:00' && $ampm < '18:00:00') ? 'cerah-am.png' : 'cerah-pm.png';
                                        } elseif ($weatherCode === 1 || $weatherCode === 2) {
                                            $gambarCuaca = ($ampm >= '06:00:00' && $ampm < '18:00:00') ? 'cerahberawan-am.png' : 'cerahberawan-pm.png';
                                        } elseif ($weatherCode === 3) {
                                            $gambarCuaca = 'berawan.png';
                                        } elseif ($weatherCode === 4) {
                                            $gambarCuaca = 'berawantebal.png';
                                        } elseif ($weatherCode === 5 || $weatherCode === 10) {
                                            $gambarCuaca = 'asap.png';
                                        } elseif ($weatherCode === 45) {
                                            $gambarCuaca = ($ampm >= '06:00:00' && $ampm < '18:00:00') ? 'kabut-am.png' : 'kabut-pm.png';
                                        } elseif ($weatherCode === 60) {
                                            $gambarCuaca = 'hujanringan.png';
                                        } elseif ($weatherCode === 61) {
                                            $gambarCuaca = 'hujansedang.png';
                                        } elseif ($weatherCode === 63) {
                                            $gambarCuaca = 'hujanlebat.png';
                                        } elseif ($weatherCode === 80) {
                                            $gambarCuaca = ($ampm >= '06:00:00' && $ampm < '18:00:00') ? 'hujanlokal-am.png' : 'hujanlokal-pm.png';
                                        } elseif ($weatherCode === 95 || $weatherCode === 97) {
                                            $gambarCuaca = 'hujanpetir.png';
                                        }                                        
                                        if ($gambarCuaca !== '') {
                                            $gambarCuaca = base_url("$this->theme_folder/$this->theme/assets/images/weather/$gambarCuaca");
                                        } else {
                                            // Tambahkan kondisi lain jika diperlukan
                                        }
                                        if ($tanggal == $today || $tanggal == $tomorrow || $tanggal == $dayAfterTomorrow) { ?>
                                            <?php
                                            // Jika tanggal belum diproses, tambahkan ke tabel
                                            if (!in_array($tanggal, $dates)) {
                                                $dates[] = $tanggal;
                                                ?>
                                                <tr>
                                                <td class="border-grey-soft" colspan="4"><i class="fa fa-clock-o" aria-hidden="true"></i> <?= $labels[$tanggal]; ?>, <?= tgl_indo2(date("Y-m-d", strtotime($tanggal))); ?></td>
                                            </tr>
                                            <tr>
                                                <?php
                                            }
                                            ?>
                                            <td class="border-grey-soft" width="25%" valign="top"><?= $jam ?><br><img src="<?= $gambarCuaca ?>" alt="" width="30px"><br><small><?= $cuaca ?></small><br>
                                                <?php
                                                $temperatures = [];
                                                foreach ($area->parameter as $param) {
                                                    $param_id = (string)$param['id'];
                                                    if ($param_id == "t") {
                                                        foreach ($param->timerange as $param_timerange) {
                                                            $param_waktu = date("d-m-Y H:i:s", strtotime($param_timerange['datetime']));
                                                            if ($param_waktu == $waktu) {
                                                                foreach ($param_timerange->value as $param_value) {
                                                                    $unit = (string)$param_value->attributes()->unit;
                                                                    if ($unit === "C") {
                                                                        $temperature = (float)$param_value;
                                                                        $temperatures[] = $temperature . '°C';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                echo implode(', ', $temperatures);
                                                ?>
                                            </td>
                                        <?php
                                        }
                                    } ?>
                                    </tr><?php
                                }
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
          	<?php endif; ?>
      	</div>
      	<p style="text-align: center;">Sumber : BMKG | <a href="https://www.ariandi.net" rel="noopener noreferrer" target="_blank">Tema DeNava</a></p>
        <div class="p-10">
			<?php
			$xmlUrl = "https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml";
			$xmlString = file_get_contents($xmlUrl);
			$xml = simplexml_load_string($xmlString);
			?>
			<?php if ($xml === false) : ?>
			Gagal menampilkan data. 
			<?php else : ?>
            <table class="jamkerja" style="width: 100%;border-radius:0 0 5px 5px 0;overflow:hidden;" cellpadding="0" cellspacing="0">
              <?php foreach ($xml->gempa as $gempa) : ?>
                <thead>
                    <tr>
                        <th class="padat border-grey-soft" colspan="4">INFO GEMPA BUMI TERBARU<br><?= date('d-m-Y \j\a\m H:i:s', strtotime($gempa->DateTime)); ?></th>
                    </tr>
                </thead>
              	<tbody>
                  	<?php
                  	  $coordinates = $gempa->point->coordinates;
                      $koordinat = explode(',', $coordinates);
                      $lintang = $koordinat[0] >= 0 ? $koordinat[0] . ' LU' : abs($koordinat[0]) . ' LS';
                      $bujur = $koordinat[1] >= 0 ? $koordinat[1] . ' BT' : abs($koordinat[1]) . ' BB';
                      $koordinat_formatted = $lintang . ' ; ' . $bujur; ?>
                      <tr>
                        <td class="border-grey-soft" rowspan="4" colspan="2" width="100px">
                          <a data-fancybox="" href="https://static.bmkg.go.id/<?= $gempa->Shakemap; ?>">
                          <img src="https://static.bmkg.go.id/<?= $gempa->Shakemap; ?>" alt="Shakemap" width="100%" oncontextmenu="return false;"></a>
                        </td>
                      </tr>
                      <tr>
                        <td class="border-grey-soft" width="50px"><img src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/images/loc.png") ?>" alt=""/></td>
                        <td class="border-grey-soft"><?= $koordinat_formatted; ?></td>
                      </tr>
                      <tr>
                        <td class="border-grey-soft" width="50px"><img src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/images/mag.png") ?>" alt=""/></td>
                        <td class="border-grey-soft">Magnitude <?= $gempa->Magnitude; ?></td>
                      </tr>
                      <tr>
                        <td class="border-grey-soft" width="50px"><img src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/images/dep.png") ?>" alt=""/></td>
                        <td class="border-grey-soft">Kedalaman <?= $gempa->Kedalaman; ?></td>
                      </tr>
                      <tr>
                        <td class="border-grey-soft" width="50px"><img src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/images/pos.png") ?>" alt=""/></td>
                        <td class="border-grey-soft" colspan="3"><?= $gempa->Wilayah; ?></td>
                      </tr>
                      <tr>
                        <td class="border-grey-soft" width="50px"><img src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/images/tsu.png") ?>" alt=""/></td>
                        <td class="border-grey-soft" colspan="3"><?= $gempa->Potensi; ?></td>
                      </tr>
                      <tr>
                        <td class="border-grey-soft" colspan="4">Dirasakan <?= $gempa->Dirasakan; ?></td>
                      </tr>
                </tbody>
              <?php endforeach; ?>
            </table>
          	<?php endif; ?>
        </div>
    </div>
</div>
