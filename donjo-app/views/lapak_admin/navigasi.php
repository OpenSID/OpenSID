<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * View navigasi untuk Modul Lapak Admin
 *
 *
 * donjo-app/views/lapak_admin/navigasi.php
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

<div class="row">
	<a href="<?= site_url('lapak_admin/produk'); ?>">
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3><?= $jml_produk; ?></h3>
					<p><b>PRODUK</b></p>
				</div>
				<div class="icon">
					<i class="ion ion-ios-cart"></i>
				</div>
			</div>
		</div>
	</a>
	<a href="<?= site_url('lapak_admin/pelapak'); ?>">
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-green">
				<div class="inner">
					<h3><?= $jml_pelapak; ?></sup></h3>
					<p><b>PELAPAK</b></p>
				</div>
				<div class="icon">
					<i class="ion ion-person"></i>
				</div>
			</div>
		</div>
	</a>
	<a href="<?= site_url('lapak_admin/kategori'); ?>">
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3><?= $jml_kategori; ?></h3>
					<p><b>KATEGORI</b></p>
				</div>
				<div class="icon">
					<i class="ion ion-pricetags"></i>
				</div>
			</div>
		</div>
	</a>
	<a href="<?= site_url('lapak_admin/pengaturan'); ?>" title="Pengaturan Modul" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pengaturan Modul" data-backdrop="false" data-keyboard="false">
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-red">
				<div class="inner">
					<h3><?= $jml_pengaturan; ?></h3>
					<p><b>PENGATURAN</b></p>
				</div>
				<div class="icon">
					<i class="ion ion-gear-a"></i>
				</div>
			</div>
		</div>
	</a>
</div>