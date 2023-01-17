<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk global paging
 *
 * donjo-app/views/global/paging.php,
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

<div class="row paging">
	<div class="col-sm-3 dataTables_length">
		<form class="form-horizontal" id="paging" action="<?= ($func == 'index') ? site_url("{$this->controller}") : site_url("{$this->controller}/{$func}"); ?>" method="POST">
			<label>
				Tampilkan
				<select class="form-control input-sm" name="per_page" onchange="$('#paging').submit()">
					<?php foreach ($set_page as $set): ?>
						<option value="<?= $set; ?>" <?= selected($per_page = $per_page ?: $paging->per_page, $set); ?>><?= $set; ?></option>
					<?php endforeach; ?>
				</select>
				Dari <strong><?= $paging->num_rows; ?></strong> Total Data
			</label>
		</form>
	</div>
	<div class="col-sm-9 dataTables_paginate">
		<ul class="pagination">
			<?php if ($paging->start_link): ?>
				<li <?= jecho($paging->page, 1, "class='disabled'"); ?>><a href="<?= site_url("{$this->controller}/{$func}/1/{$o}");
			    jecho($paging->page . '!', 1, '#'); ?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
			<?php endif; ?>
			<?php if ($paging->prev): ?>
				<li><a href="<?= site_url("{$this->controller}/{$func}/{$paging->prev}/{$o}"); ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
			<?php endif; ?>
			<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
				<li <?= jecho($paging->page, $i, "class='active'"); ?>><a href="<?= ($i == 1) ? site_url("{$this->controller}/{$func}") : site_url("{$this->controller}/{$func}/{$i}/{$o}"); ?>"><?= $i; ?></a></li>
			<?php endfor; ?>
			<?php if ($paging->next): ?>
				<li><a href="<?= site_url("{$this->controller}/{$func}/{$paging->next}/{$o}"); ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
			<?php endif; ?>
			<?php if ($paging->end): ?>
				<li <?= jecho($paging->page . '!', $paging->end, "class='disabled'"); ?>><a href="<?=site_url("{$this->controller}/{$func}/{$paging->end}/{$o}");
			    jecho($paging->page, $paging->end_link, '#'); ?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
			<?php endif; ?>
		</ul>
	</div>
</div>
