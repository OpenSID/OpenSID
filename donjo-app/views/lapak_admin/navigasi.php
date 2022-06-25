<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View navigasi untuk Modul Lapak Admin
 *
 *
 * donjo-app/views/lapak_admin/navigasi.php
 *
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

<div class="row">
	<a href="<?= site_url('lapak_admin/produk'); ?>">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-aqua">
				<span class="info-box-icon"><i class="fa fa-cubes fa-nav"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">PRODUK</span>
					<span class="info-box-number"><?= $jml_produk['aktif']; ?></span>

					<div class="progress">
						<div class="progress-bar" style="width: <?= ($jml_produk['aktif'] / $jml_produk['total']) * 100; ?>%"></div>
					</div>
					<span class="progress-description">Total : <b><?= $jml_produk['total']; ?></b></span>
				</div>
			</div>
		</div>
	</a>
	<a href="<?= site_url('lapak_admin/pelapak'); ?>">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-green">
				<span class="info-box-icon"><i class="fa fa-users fa-nav"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">PELAPAK</span>
					<span class="info-box-number"><?= $jml_pelapak['aktif']; ?></span>

					<div class="progress">
						<div class="progress-bar" style="width: <?= ($jml_pelapak['aktif'] / $jml_pelapak['total']) * 100; ?>%"></div>
					</div>
					<span class="progress-description">Total : <b><?= $jml_pelapak['total']; ?></b></span>
				</div>
			</div>
		</div>
	</a>
	<a href="<?= site_url('lapak_admin/kategori'); ?>">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-yellow">
				<span class="info-box-icon"><i class="fa fa-tags fa-nav"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">KATEGORI</span>
					<span class="info-box-number"><?= $jml_kategori['aktif']; ?></span>

					<div class="progress">
						<div class="progress-bar" style="width: <?= ($jml_kategori['aktif'] / $jml_kategori['total']) * 100; ?>%"></div>
					</div>
					<span class="progress-description">Total : <b><?= $jml_kategori['total']; ?></b></span>
				</div>
			</div>
		</div>
	</a>
	<a href="<?= site_url('lapak_admin/pengaturan'); ?>" title="Pengaturan Modul" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pengaturan Modul" data-backdrop="false" data-keyboard="false">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box bg-red">
				<span class="info-box-icon"><i class="fa fa-gear fa-nav"></i></span>
				<div class="info-box-content">
					<span class="info-box-number" style="font-size: 14px; padding-top: 30px; padding-bottom: 30px;">PENGATURAN</span>
				</div>
			</div>
		</div>
	</a>
</div>