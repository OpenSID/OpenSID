<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk menu pilihan Statistik Kependudukan
 *
 * donjo-app/views/statistik/side_menu.php
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
<script>
$(function() {
  function toggleIcon(e) {
      $(e.target)
          .prev('.card-header')
          .find(".plus-minus")
          .toggleClass('fa-plus fa-minus');
  }
  $('.panel-group').on('hidden.bs.collapse', toggleIcon);
  $('.panel-group').on('shown.bs.collapse', toggleIcon);
});
</script>

<div id="penduduk" class="panel-group card card-outline card-info">
	<div class="card-header with-border">
		<h3 class="card-title">Statistik Penduduk</h3>
		<div class="card-tools">
			<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar"><i class="plus-minus fa fa-minus"></i></button>
		</div>
	</div>
	<div class="card-body collapse show navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav nav-pills nav-stacked sidebar-menu text-sm">
			<?php foreach ($stat_penduduk as $id => $nama): ?>
				<li <?= jecho((string)$id, $lap, 'class="nav-item active"'); ?>><?= anchor("statistik/clear/$id", $nama, 'class="nav-link"'); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<div id="keluarga" class="panel-group card card-outline card-info">
	<div class="card-header with-border">
		<h3 class="card-title">Statistik Keluarga</h3>
		<div class="card-tools">
			<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar1"><i class="plus-minus fa fa-plus"></i></button>
		</div>
	</div>
	<div class="card-body collapse navbar-collapse" id="collapsibleNavbar1">
		<ul class="navbar-nav nav-pills nav-stacked sidebar-menu text-sm">
			<?php foreach ($stat_keluarga as $id => $nama): ?>
				<li <?= jecho((string)$id, $lap, 'class="nav-item active"'); ?>><?= anchor("statistik/clear/$id", $nama, 'class="nav-link"'); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<div id="bantuan" class="panel-group card card-outline card-info">
	<div class="card-header with-border">
		<h3 class="card-title">Statistik Program Bantuan</h3>
		<div class="card-tools">
			<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#collapsibleNavbar2"><i class="plus-minus fa fa-plus"></i></button>
		</div>
	</div>
	<div class="card-body collapse navbar-collapse" id="collapsibleNavbar2">
		<ul class="navbar-nav nav-pills nav-stacked sidebar-menu text-sm">
			<?php foreach ($stat_kategori_bantuan as $id => $nama): ?>
				<li <?= jecho($id, $lap, 'class="nav-item active"'); ?>><?= anchor("statistik/clear/$id", $nama, 'class="nav-link"'); ?></li>
			<?php endforeach; ?>
			<?php foreach ($stat_bantuan as $bantuan): ?>
				<li <?= jecho($bantuan['lap'], $lap, 'class="nav-item active"'); ?>><?= anchor("statistik/clear/$bantuan[lap]", "$bantuan[nama] ($bantuan[lap])", 'class="nav-link"'); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
