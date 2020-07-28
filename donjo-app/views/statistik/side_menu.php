<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk modul Statistik Kependudukan
 *
 * donjo-app/views/statistik/side_menu.php,
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

<input id="kategori" name="kategori" type="hidden" value="<?= $kategori ?>" />
<div id="penduduk" class="box box-info <?= ($kategori == 'penduduk') ?: 'collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Penduduk</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= ($kategori == 'penduduk') ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php foreach($list_statistik_penduduk as $id => $nama): ?>
				<?php if( ! in_array($id, ['statistik/bantuan_penduduk'])): ?>
					<li <?= jecho($id, 'statistik/'.$lap, 'class="active"'); ?>><a href="<?= site_url(str_replace('/', '/clear/', $id))?>"><?= $nama; ?></a></li>
				<?php endif; ?>
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
			<?php foreach($link_statistik_keluarga as $id => $nama): ?>
				<?php if( ! in_array($id, ['statistik/bantuan_keluarga'])): ?>
					<li <?= jecho($id, 'statistik/'.$lap, 'class="active"'); ?>><a href="<?= site_url(str_replace('/', '/clear/', $id))?>"><?= $nama; ?></a></li>
				<?php endif; ?>
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
			<?php foreach ($list_bantuan as $bantuan): ?>
				<li <?= jecho($bantuan['lap'], $lap, 'class="active"'); ?>>
					<a href="<?= site_url("statistik/clear/$bantuan[lap]")?>"><?= $bantuan['nama']." (".$bantuan['lap'].")"?></a>
				</li>
			<?php endforeach; ?>
			<li <?= jecho('bantuan_penduduk', $lap, 'class="active"'); ?>><a href="<?=site_url('statistik/clear/bantuan_penduduk')?>">Penerima Bantuan (Penduduk)</a></li>
			<li <?= jecho('bantuan_keluarga', $lap, 'class="active"'); ?>><a href="<?=site_url('statistik/clear/bantuan_keluarga')?>">Penerima Bantuan (Keluarga)</a></li>
		</ul>
	</div>
</div>
