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
	<div class="col-md-3 col-sm-6 col-xs-12" id="allstatus">
		<div class="info-box bg-default">
			<span class="info-box-icon"><i class="fa fa-info fa-nav"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Semua</span>
				<span class="info-box-number"><?= $allstatus ?></span>

				<div class="progress">
					<div class="progress-bar"></div>
				</div>
				<span class="progress-description">Total bulan ini: <b><?= $m_allstatus ?></b></span>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12" id="status1">
		<div class="info-box bg-red">
			<span class="info-box-icon"><i class="fa fa-info fa-nav"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Menunggu Diproses</span>
				<span class="info-box-number"><?= $status1 ?></span>

				<div class="progress">
					<div class="progress-bar" style="width: <?= $status1 / $allstatus * 100; ?>%"></div>
				</div>
				<span class="progress-description">Total bulan ini: <b><?= $m_status1 ?></b></span>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12" id="status2">
		<div class="info-box bg-blue">
			<span class="info-box-icon"><i class="fa fa-info fa-nav"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Sedang Diproses</span>
				<span class="info-box-number"><?= $status2 ?></span>

				<div class="progress">
					<div class="progress-bar" style="width: <?= $status2 / $allstatus * 100; ?>%"></div>
				</div>
				<span class="progress-description">Total bulan ini: <b><?= $m_status2 ?></b></span>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12" id="status3">
		<div class="info-box bg-green">
			<span class="info-box-icon"><i class="fa fa-info fa-nav"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Selesai Diproses</span>
				<span class="info-box-number"><?= $status3 ?></span>

				<div class="progress">
					<div class="progress-bar" style="width: <?= $status3 / $allstatus * 100; ?>%"></div>
				</div>
				<span class="progress-description">Total bulan ini: <b><?= $m_status3 ?></b></span>
			</div>
		</div>
	</div>
</div>