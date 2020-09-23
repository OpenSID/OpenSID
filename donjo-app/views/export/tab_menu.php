<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Database
 *
 * donjo-app/views/export/tab_menu.php
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Database</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pengaturan Database</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li <?= jecho($act_tab, 'export/exp', 'class="active"'); ?>><a href="<?= site_url('database')?>">Ekspor Database</a></li>
						<li <?= jecho($act_tab, 'import/imp', 'class="active"'); ?>><a href="<?= site_url('database/import')?>">Impor Database</a></li>
						<li <?= jecho($act_tab, 'import/bip', 'class="active"'); ?>><a href="<?= site_url('database/import_bip')?>">Impor BIP</a></li>
						<li <?= jecho($act_tab, 'database/backup', 'class="active"'); ?>><a href="<?= site_url('database/backup')?>">Backup/Restore</a></li>
						<li <?= jecho($act_tab, 'database/kosongkan', 'class="active"'); ?>><a href="<?= site_url('database/kosongkan')?>">Kosongkan DB</a></li>
						<li <?= jecho($act_tab, 'database/migrasi_cri', 'class="active"'); ?>><a href="<?= site_url('database/migrasi_cri')?>">Migrasi DB</a></li>
					</ul>
					<?php $this->load->view($act_tab, $data); ?>
				</div>
			</div>
		</div>
	</section>
</div>
