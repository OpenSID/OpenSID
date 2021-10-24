<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View Laporan Warga 
 *
 * donjo-app/views/kehadiran/forms/login_3.php
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

$form_action=site_url('layanan-mandiri/kehadiran/lapor');
?><div style='padding:20px'>

	<div class='row'>
		<div class="col-lg-4 col-md-6">Nama:</div> 
		<div class="col-lg-8 col-md-6"><?=@$aparat->pamong_info->nama;?></div>
	</div>
	<div class='row'>
		<div class="col-lg-4 col-md-6">Jabatan:</div> 
		<div class="col-lg-8 col-md-6"><?=@$aparat->pamong_info->jabatan;?></div>
	</div>
<form id="validasi" action="<?= $form_action; ?>" method="post" class="form-login">
	<input type='hidden' name='aparatid' value="<?=@$aparat->pamong_id;?>" />
	<div class="form-group form-login">
		Masukkan Detail Laporan
		<textarea name='lapor_txt'></textarea>
	</div>
	
	<div class="form-group form-login">
		<button type="submit" class="btn btn-block btn-block bg-green"><b>Saya Membuat Laporan</b></button>
	</div>
</form>
</div>