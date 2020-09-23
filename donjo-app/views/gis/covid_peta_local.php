<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Pemetaan
 *
 * donjo-app/views/gis/covid_peta_local.php
 *
 */
/*
 *  File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<link rel="stylesheet" href="<?= base_url()?>assets/css/peta.css">

<?php
//API Local Data COVID19
$odp = $covid[0]; //"Orang Dalam Pemantauan (ODP)" => "ODP",
$pdp = $covid[1]; //"Pasien Dalam Pengawasan (PDP)" => "PDP",
$odr = $covid[2]; //"Orang Dalam Resiko (ODR)" => "ODR"
$otg = $covid[3]; //"Orang Tanpa Gejala (OTG)" => "OTG",
$positif = $covid[4]; //"Positif Covid-19" => "POSITIF",
?>

<div id="covid_local_peta">
	<div class="panel">
		<div class="panel-body sub">
			<div class="row">
				<div class="col-lg-12 col-md-3 col-sm-3">
					<div style="height: 75px;padding:1px" class="panel-body-lg">
						<img src="<?= base_url()?>assets/images/siaga_cvd.png"/></a>
					</div>
				</div>
			</div>
			<div class="box box-primary box-solid">
				<div class="box-header">
					<h3 class="box-title"><?= ucwords($this->setting->sebutan_desa); ?> <?=$desa['nama_desa']; ?></h3>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-3 col-sm-3">
						<div style="height: 50px;padding:1px" class="panel-body text-center">
							<table><tr>
								<th>&nbsp;&nbsp;</th>
								<th>&nbsp;&nbsp;</th>
								<th>&nbsp;&nbsp;</th>
								<th>&nbsp;&nbsp;</th>
								<th style="color:red">Positif&nbsp;&nbsp;</th>
								<th style="color:green">OTG&nbsp;&nbsp;</th>
								<th style="color:blue">ODR&nbsp;&nbsp;</th>
								<th style="color:grey">PDP&nbsp;&nbsp;</th>
								<th style="color:black">ODP&nbsp;&nbsp;</th>
							</tr><tr>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center><b style="color:red"><?= number_format($positif['jumlah']); ?></b></center></td>
								<td><center><b style="color:green"><?= number_format($otg['jumlah']); ?></b></center></td>
								<td><center><b style="color:blue"><?= number_format($odr['jumlah']); ?></b></center></td>
								<td><center><b style="color:grey"><?= number_format($pdp['jumlah']); ?></b></center></td>
								<td><center><b style="color:black"><?= number_format($odp['jumlah']); ?></b></center></td>
							</tr></table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
