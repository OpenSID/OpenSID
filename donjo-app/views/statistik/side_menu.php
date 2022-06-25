<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk menu pilihan Statistik Kependudukan
 *
 * donjo-app/views/statistik/side_menu.php
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

<div id="penduduk" class="box box-info <?= ($kategori == 'penduduk') ?: 'collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Penduduk</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= ($kategori == 'penduduk') ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php foreach ($stat_penduduk as $id => $nama): ?>
				<li <?= jecho((string) $id, $lap, 'class="active"'); ?>><?= anchor("statistik/clear/{$id}", $nama); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div id="keluarga" class="box box-info <?= ($kategori == 'keluarga') ?: 'collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Keluarga</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= ($kategori == 'keluarga') ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php foreach ($stat_keluarga as $id => $nama): ?>
				<li <?= jecho($id, $lap, 'class="active"'); ?>><?= anchor("statistik/clear/{$id}", $nama); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div id="rtm" class="box box-info <?= ($kategori == 'rtm') ?: 'collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik RTM</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= ($kategori == 'rtm') ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php foreach ($stat_rtm as $id => $nama): ?>
				<li <?= jecho($id, $lap, 'class="active"'); ?>><?= anchor("statistik/clear/{$id}", $nama); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div id="bantuan" class="box box-info <?= ($kategori == 'bantuan') ?: 'collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Program Bantuan</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= ($kategori == 'bantuan') ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php foreach ($stat_kategori_bantuan as $id => $nama): ?>
				<li <?= jecho($id, $lap, 'class="active"'); ?>><?= anchor("statistik/clear/{$id}", $nama); ?></li>
			<?php endforeach; ?>
			<?php foreach ($stat_bantuan as $bantuan): ?>
				<li <?= jecho($bantuan['lap'], $lap, 'class="active"'); ?>><?= anchor("statistik/clear/{$bantuan['lap']}", "{$bantuan['nama']} ({$bantuan['lap']})"); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
