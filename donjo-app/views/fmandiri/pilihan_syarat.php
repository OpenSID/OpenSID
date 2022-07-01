<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Surat
 *
 * donjo-app/views/fmandiri/pilih_syarat.php
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

<div class="form-group form-horizontal">
	<select class="form-control input-sm syarat required" name="syarat[<?= $syarat_id; ?>]" onchange="cek_perhatian($(this));">
		<option value=""> -- Pilih dokumen yang melengkapi syarat -- </option>
		<?php foreach ($dokumen as $key => $data): ?>
			<?php if ($data['id_syarat'] == $syarat_id): ?>
				<option value="<?= $data['id']?>" <?= selected($data['id'], $syarat_permohonan[$syarat_id]); ?>><?= $data['nama']?></option>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if ($cek_anjungan): ?>
			<option value="-1" <?= selected('-1', '$syarat_permohonan[$syarat_id]'); ?>>Bawa bukti fisik ke Kantor Desa</option>
		<?php endif; ?>
	</select>
	<i class="fa fa-exclamation-triangle text-red perhatian" style="display: none; padding-left: 10px; font-weight: bold;">&nbsp;Perhatian!</i>
</div>

