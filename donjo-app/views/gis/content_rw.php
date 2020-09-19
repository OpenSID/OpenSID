<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk modul Peta
 *
 * donjo-app/views/gis/content_rw.php,
 *
 */

/**
 *
 * File ini bagian dari:
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

<div id="isi_popup_rw">
	<?php foreach ($rw_gis as $key_rw => $rw): ?>
		<div id="isi_popup_rw_<?= $key_rw ?>" style="visibility: hidden;">
			<div id="content">
				<center><h5 id="firstHeading" class="firstHeading"><b>Wilayah RW <?= $rw['rw'] . " " . ucwords($this->setting->sebutan_dusun) . " " . $rw['dusun']; ?></b></h5></center>
				<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-modal" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa fa-bar-chart"></i>Statistik Penduduk</a></center></p>
				<div class="collapse box-body no-padding" id="collapseStatGraph">
					<div id="bodyContent">
						<div class="card card-body">
							<?php foreach ($list_ref as $key => $value): ?>
								<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik/chart_gis_rw/$key/".underscore($rw[dusun]) . "/" . underscore($rw[rw])); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW <?= $rw['rw'] . " " . $wilayah . " " . $rw['dusun']; ?>"><?= $value; ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
