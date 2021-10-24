<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View Login tipe 3
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
?>
<form id="validasi" action="<?= $form_action; ?>" method="post" class="form-login">
	<div class="form-group form-login">
		<input type="text" class="form-control required  " required name="nik" placeholder="Masukkan NIK" id="nik_keyboard"  onclick2='changeDef("nik_keyboard")' />
	</div>
	<div class="form-group form-login">
		<input type="password" class="form-control required<?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" name="pin" required placeholder="Masukkan PIN " id="pin_keyboard" onclick2='changeDef("pin_keyboard")' />
	</div>
	<div class="form-group">
		<center><input type="checkbox" id="checkbox"> Tampilkan PIN</center>
	</div>
	<div class="form-group form-login">
		<button type="submit" class="btn btn-block btn-block bg-green"><b>MASUK</b></button>
	</div>
</form>
<p align="center">Silakan hubungi operator <?= $this->setting->sebutan_desa; ?> untuk mendapatkan kode PIN anda.</p>
<div>  
	<a href='<?=site_url('kehadiran/masuk');?>?form=2' class="btn btn-block btn-block bg-green">
	<b>Pindai Kartu e-KTP</b>
	</a>
<!-- keyboard -->
</div>