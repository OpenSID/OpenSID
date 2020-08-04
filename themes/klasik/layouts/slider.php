<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk tampilan slider Website Tema Klasik
 *
 * themes/klasik/layouts/slider.php,
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

<link rel="Stylesheet" href="<?= base_url('assets/front/css/slider.css')?>" />

<script type="text/javascript">
	$(document).ready(function() {
		$('.slider').cycle({
			pauseOnHover: true,
			// Untuk menghilangkan titik-titik di cycle pager
			pagerTemplate: '<span></span>'
		});
	});
</script>

<div class="box">
	<div class="slider">
		<span class="cycle-prev"><img src="<?= base_url('assets/images/back_button.png'); ?>" alt="Back"></span> <!-- Untuk membuat tanda panah di kiri slider -->
		<span class="cycle-next"><img src="<?= base_url('assets/images/next_button.png'); ?>" alt="Next"></span><!-- Untuk membuat tanda panah di kanan slider -->
		<span class="cycle-pager"></span> <!-- Untuk membuat tanda bulat atau link pada slider -->
		<?php foreach ($slider_gambar['gambar'] as $gambar) : ?>
			<?php $file_gambar = $slider_gambar['lokasi'] . 'sedang_' . $gambar['gambar']; ?>
			<?php if(is_file($file_gambar)) : ?>
				<img src="<?= base_url($file_gambar); ?>"
					<?php if ($slider_gambar['sumber'] != 3): ?>
						onclick="location.href='<?='artikel/'.buat_slug($gambar); ?>'"
					<?php endif; ?>
				>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
