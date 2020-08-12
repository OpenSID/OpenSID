<?php
/**
 * File ini:
 *
 * View di modul Pemetaan
 *
 * /donjo-app/views/plan/nav.php
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

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Pengaturan Peta</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?=jecho($tip, 3, "class='active'")?>><a href="<?=site_url('plan/clear')?>">Lokasi</a></li>
			<li <?=jecho($tip, 0, "class='active'")?>><a href="<?=site_url('point/clear')?>">Tipe Lokasi</a></li>
			<li <?=jecho($tip, 6, "class='active'")?>><a href="<?=site_url('point/clear_simbol')?>">Simbol Lokasi</a></li>
      <li <?=jecho($tip, 1, "class='active'")?>><a href="<?=site_url('garis/clear')?>">Garis</a></li>
      <li <?=jecho($tip, 2, "class='active'")?>><a href="<?=site_url('line/clear')?>">Tipe Garis</a></li>
      <li <?=jecho($tip, 4, "class='active'")?>><a href="<?=site_url('area/clear')?>">Area</a></li>
      <li <?=jecho($tip, 5, "class='active'")?>><a href="<?=site_url('polygon/clear')?>">Tipe Area</a></li>
		</ul>
	</div>
</div>
