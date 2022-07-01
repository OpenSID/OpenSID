<?php defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk global Combobox Wilayah
 *
 * donjo-app/views/global/combobox_wilayah.php
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<select class="form-control input-sm " name="dusun" onchange="formAction('<?= $form; ?>','<?= site_url("{$this->controller}/filter/dusun"); ?>')">
	<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun); ?></option>
	<?php foreach ($list_dusun as $data): ?>
		<option value="<?= $data['dusun']; ?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun']); ?></option>
	<?php endforeach; ?>
</select>
<?php if ($dusun): ?>
	<select class="form-control input-sm" name="rw" onchange="formAction('<?= $form; ?>','<?= site_url("{$this->controller}/filter/rw"); ?>')" >
		<option value="">Pilih RW</option>
		<?php foreach ($list_rw as $data): ?>
			<option value="<?= $data['rw']; ?>" <?= selected($rw, $data['rw']); ?>><?= set_ucwords($data['rw']); ?></option>
		<?php endforeach; ?>
	</select>
<?php endif; ?>
<?php if ($rw): ?>
	<select class="form-control input-sm" name="rt" onchange="formAction('<?= $form; ?>','<?= site_url("{$this->controller}/filter/rt"); ?>')">
		<option value="">Pilih RT</option>
		<?php foreach ($list_rt as $data): ?>
			<option value="<?= $data['rt']; ?>"<?= selected($rt, $data['rt']); ?>><?= set_ucwords($data['rt']); ?></option>
		<?php endforeach; ?>
	</select>
<?php endif; ?>
