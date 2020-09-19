<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
/**
 * File ini:
 *
 * View untuk Tema Hadakewa, Bagian Copywriht
 *
 * copywright.tpl.php
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<?php if (!is_null($transparansi)) $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/apbdesa-tema.php'), $transparansi);?>

<div id="footer-left">&copy; 2016-<?= date("Y");?>
	<a target="_blank" href="https://opendesa.id">OpenDesa</a> <i class="fa fa-circle" style="font-size: smaller;"></i> <a target="_blank" href="https://github.com/OpenSID/OpenSID">OpenSID</a> <?= AmbilVersi()?> <i class="fa fa-circle" style="font-size: smaller;"></i> Tema Hadakewa
	<br>Dikembangkan oleh <a target="_blank" href="https://www.facebook.com/groups/OpenSID/">Komunitas OpenSID</a>
	<br/>Dikelola oleh Pemerintah <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>
	<?php if (file_exists('mitra')): ?>
		<br/>Hosting didukung <a target="_blank" href="https://idcloudhost.com"><img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" height='15px' alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
	<?php endif; ?>
</div>
<div id="footer-right">
	<ul id="global-nav-right" class="top">
		<?php foreach ($sosmed As $data): ?>
			<?php if (!empty($data['link'])): ?>
				<li><a href="<?= $data['link']?>" target="_blank"><span style="color:#fff" ><i class="fa fa-<?= strtolower($data['nama']);?><?= in_array($data['id'], [1,2]) ? '-square' : '';?> fa-2x"></i></span></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
