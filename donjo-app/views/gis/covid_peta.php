<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Pemetaan
 *
 * donjo-app/views/gis/covid_peta.php
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

<link rel="stylesheet" href="<?= base_url()?>assets/css/peta.css">
<script>
	const KODE_PROVINSI = "<?= $this->setting->provinsi_covid ?: 'undefined' ?>";
</script>

	<section id="covid-nasional">
		<p class="font-weight-bold line line-short shimmer" data-name="wilayah"></p>
		<div class="row">
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid positif shimmer">
					<span>Total Positif</span>
					<span data-name="positif"></span>
					<span class="small">orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid sembuh shimmer">
					<span>Total Sembuh</span>
					<span data-name="sembuh"></span>
					<span class="small">orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid meninggal shimmer">
					<span>Total Meninggal</span>
					<span data-name="meninggal"></span>
					<span class="small">orang</span>
				</div>
			</div>
		</div>
	</section>

	<?php if($this->setting->provinsi_covid) : ?>
	<section id="covid-provinsi">
		<p class="font-weight-bold line line-short shimmer" data-name="wilayah"></p>
		<div class="row">
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid positif shimmer">
					<span>Total Positif</span>
					<span data-name="positif"></span>
					<span class="small">orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid sembuh shimmer">
					<span>Total Sembuh</span>
					<span data-name="sembuh"></span>
					<span class="small">orang</span>
				</div>
			</div>
			<div class="col-lg-2 col-12 px-2 py-1">
				<div class="square covid meninggal shimmer">
					<span>Total Meninggal</span>
					<span data-name="meninggal"></span>
					<span class="small">orang</span>
				</div>
			</div>
		</div>
	</section>
	<?php endif ?>
