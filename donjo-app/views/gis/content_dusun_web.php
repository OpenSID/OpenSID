<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk Peta Web
 *
 * donjo-app/views/gis/content_dusun_web.php,
 */

/*
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
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<div id="isi_popup_dusun">
	<?php foreach ($dusun_gis as $key_dusun => $dusun): ?>
		<div id="isi_popup_dusun_<?= $key_dusun ?>" style="visibility: hidden;">
			<div id="content">
				<center><h5 id="firstHeading" class="firstHeading"><b>Wilayah <?= set_ucwords($wilayah) . ' ' . set_ucwords($dusun['dusun']); ?></b></h5></center>
				<div id="bodyContent">
					<p><center><a href="#collapseStatGraph" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStatGraph" aria-expanded="false" aria-controls="collapseStatGraph"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Statistik Penduduk&nbsp;&nbsp;</a></center></p>
					<div class="collapse box-body no-padding" id="collapseStatGraph">
						<div class="card card-body">
							<?php foreach ($list_ref as $key => $value): ?>
								<li <?= jecho($lap, $key, 'class="active"'); ?>><a href="<?= site_url("statistik_web/chart_gis_dusun/{$key}/" . underscore($dusun['dusun'])) ?>" data-remote="false" data-toggle="modal" data-target="#modalSedang" data-title="Statistik Penduduk <?= set_ucwords($wilayah) . ' ' . set_ucwords($dusun['dusun']); ?>"><?= $value ?></a></li>
							<?php endforeach; ?>
						</div>
					</div>
					<p><center><a href="<?=site_url("load_aparatur_wilayah/{$dusun['id_kepala']}/1")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-title="Kepala <?= set_ucwords($wilayah) . ' ' . $dusun['dusun']?>" data-remote="false" data-toggle="modal" data-target="#modalKecil"><i class="fa fa-user"></i>&nbsp;Kepala <?= set_ucwords($wilayah)?>&nbsp;</a></center></p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
