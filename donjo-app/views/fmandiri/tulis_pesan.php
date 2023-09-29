<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Pesan > Tulis Pesan
 *
 * donjo-app/views/fmandiri/tulis_pesan.php
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

<div class="box box-solid">
	<div class="box-header with-border bg-yellow">
		<h4 class="box-title">Pesan</h4>
	</div>
	<div class="box-body box-line">
		<div class="form-group">
			<a href="<?= site_url("layanan-mandiri/{$tujuan}"); ?>" class="btn bg-aqua btn-social"><i class="fa fa-arrow-circle-left "></i>Kembali ke <?= ucwords(spaceunpenetration($tujuan)); ?></a>
		</div>
	</div>
	<div class="box-body box-line">
		<h4><b><?= ($kat == 2) ? 'BALAS' : 'TULIS'; ?> PESAN</b></h4>
	</div>
	<div class="box-body">

        <!-- Notifikasi -->
            <?php if (($notif = session('notif')) && ($data = session('notif')['data'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $notif['pesan']; ?>
                </div>
            <?php endif; ?>
		<form id="validasi" action="<?= site_url('layanan-mandiri/pesan/kirim'); ?>" method="post">
			<div class="form-group">
				<label for="subjek">Subjek</label>
				<input type="text" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvtext'); ?>" name="subjek" placeholder="Subjek" value="<?= $subjek ?? $data['subjek']; ?>" <?= jecho($kat, 2, 'readonly'); ?>>
			</div>
			<div class="form-group">
				<label for="pesan">Isi Pesan</label>
				<textarea class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvtext'); ?>" name="pesan" placeholder="Isi Pesan" ><?= $data['pesan'] ?? '' ?></textarea>
			</div>

			<div class="form-group">
				<button type="submit" class="btn bg-green btn-social"><i class="fa fa-send-o"></i>Kirim Pesan</button>
			</div>
		</form>
	</div>
</div>
