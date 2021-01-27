<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk pengaturan submenu statis di modul Admin Web > Menu
 *
 * donjo-app/views/menu/ajax_add_sub_menu_form.php
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

<?php $this->load->view('global/validasi_form'); ?>
<form action="<?=$form_action; ?>" method="post" id="validasi">
	<div class="modal-body">
		<div class="form-group">
			<label class="control-label" for="nama">Nama</label>
			<input name="nama" class="form-control input-sm required nomor_sk" maxlength="50" type="text" value="<?=$submenu['nama']?>"></input>
		</div>
		<?php if ( ! empty($submenu['link'])): ?>
			<div class="form-group">
				<label class="control-label" for="link_sebelumnya">Link Sebelumnya</label>
				<input class="form-control input-sm" type="text" value="<?=$submenu['link']?>" disabled=""></input>
			</div>
		<?php endif; ?>
		<div class="form-group">
			<label class="control-label" for="link">Jenis Link</label>
			<select class="form-control input-sm required" id="link_tipe" name="link_tipe" style="width:100%;" onchange="ganti_jenis_link($(this).val()); event.stopPropagation();">
				<option option value="">-- Pilih Jenis Link --</option>
				<?php foreach ($link_tipe as $id => $nama): ?>
					<option value="<?= $id; ?>" <?= selected($submenu['link_tipe'], $id) ?>><?= $nama?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group" id="jenis_link" style="<?php ( ! $submenu['link_tipe']) and print('display:none;');; ?>">
			<label class="control-label" for="link">Link</label>
			<select id="artikel_statis" class="form-control input-sm jenis_link select2" name="<?= jecho($submenu['link_tipe'], 1, 'link'); ?>" style="<?php ($submenu['link_tipe'] != 1) and print('display:none;'); ?>">
				<option value="">-- Pilih Artikel Statis --</option>
				<?php foreach ($artikel_statis as $data): ?>
					<option value="artikel/<?= $data['id']; ?>" <?= selected($submenu['link'], "artikel/$data[id]"); ?>><?=$data['judul']; ?></option>
				<?php endforeach; ?>
			</select>
			<select id="kategori_artikel" class="form-control input-sm jenis_link" name="<?= jecho($submenu['link_tipe'], 8, 'link'); ?>" style="<?php ($submenu['link_tipe'] != 8) and print('display:none;');; ?>">
				<option value="">-- Pilih Kategori Artikel --</option>
				<?php foreach ($kategori_artikel as $data): ?>
					<option value="kategori/<?= $data['slug']; ?>" <?= selected($submenu['link'], "kategori/$data[slug]"); ?>><?=$data['kategori']; ?></option>
				<?php endforeach; ?>
			</select>
			<select id="statistik_penduduk" class="form-control input-sm jenis_link" name="<?= jecho($submenu['link_tipe'], 2, 'link'); ?>" style="<?php ($submenu['link_tipe'] != 2) and print('display:none;');; ?>">
				<option value="">-- Pilih Statistik Penduduk --</option>
				<?php foreach ($statistik_penduduk as $id => $nama): ?>
					<option value="<?= "statistik/$id"; ?>" <?= selected($submenu['link'], "statistik/$id") ?>><?= $nama?></option>
				<?php endforeach; ?>
			</select>
			<select id="statistik_keluarga" class="form-control jenis_link input-sm" name="<?= jecho($submenu['link_tipe'], 3, 'link'); ?>" style="<?php ($submenu['link_tipe'] != 3) and print('display:none;');; ?>">
				<option value="">-- Pilih Statistik Keluarga --</option>
				<?php foreach ($statistik_keluarga as $id => $nama): ?>
					<option value="<?= "statistik/$id"; ?>" <?= selected($submenu['link'], "statistik/$id") ?>><?= $nama?></option>
				<?php endforeach; ?>
			</select>
			<select id="statistik_program_bantuan" class="form-control input-sm jenis_link" name="<?= jecho($submenu['link_tipe'], 4, 'link')?>" style="<?php ($submenu['link_tipe'] != 4) and print('display:none;'); ?>">
				<option value="">-- Pilih Statistik Program Bantuan --</option>
				<?php foreach ($statistik_kategori_bantuan as $id => $nama): ?>
					<option value="<?= "statistik/$id"; ?>" <?= selected($submenu['link'], "statistik/$id") ?>><?= $nama?></option>
				<?php endforeach; ?>
				<?php foreach ($statistik_program_bantuan as $nama): ?>
					<option value="<?= "statistik/$nama[lap]"; ?>" <?= selected($submenu['link'], "statistik/$nama[lap]"); ?>><?= $nama['nama']; ?></option>
				<?php endforeach; ?>
			</select>
			<select id="statis_lainnya" class="form-control input-sm jenis_link" name="<?= jecho($submenu['link_tipe'], 5, 'link')?>" style="<?php ($submenu['link_tipe'] != 5) and print('display:none;'); ?>">
				<option value="">-- Pilih Halaman Statis Lainnya --</option>
				<?php foreach ($statis_lainnya as $id => $nama): ?>
					<option value="<?= $id?>" <?= selected($submenu['link'], $id) ?>><?= $nama?></option>
				<?php endforeach; ?>
			</select>
			<select id="artikel_keuangan" class="form-control input-sm jenis_link" name="<?= jecho($submenu['link_tipe'], 6, 'link')?>" style="<?php ($submenu['link_tipe'] != 6) and print('display:none;'); ?>">
				<option value="">-- Pilih Artikel Keuangan --</option>
				<?php foreach ($artikel_keuangan as $id => $nama): ?>
					<option value="<?= $id?>" <?= selected($submenu['link'], $id) ?>><?= $nama?></option>
				<?php endforeach; ?>
			</select>
			<select id="kelompok" class="form-control input-sm jenis_link required" name="<?php if ($submenu['link_tipe']==7): ?>link<?php endif; ?>" style="<?php ($submenu['link_tipe'] != 7 ) and print('display:none;') ?>">
					<option value="">Pilih Kelompok</option>
					<?php foreach ($kelompok as $kel): ?>
						<option value="<?= "kelompok/$kel[id]"; ?>" <?= selected($submenu['link'], "kelompok/$kel[id]") ?>><?= $kel['nama'] . ' (' .$kel['master'] . ')'; ?></option>
				<?php endforeach; ?>
			</select>
			<span id="eksternal" class="jenis_link" style="<?php ($submenu['link_tipe'] != 99) and print('display:none;'); ?>">
				<input name="<?= jecho($submenu['link_tipe'], 99, 'link'); ?>" class="form-control input-sm" type="text" value="<?=$submenu['link']?>" ></input>
				<span class="text-sm text-red">(misalnya: https://opendesa.id)</span>
			</span>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>

<script>
	function ganti_jenis_link(jenis) {
		$('#jenis_link').show();
		$('.jenis_link').hide();
		$('.jenis_link').removeAttr( "name" );
		$('.jenis_link').removeClass('required');
		// Select2 membuat span terpisah dan perlu ditangani khusus
		$('span.select2').hide();
		$('#eksternal > input').attr('name', '');

		if (jenis == '1') {
			$('#artikel_statis').show();
			$('#artikel_statis').attr('name', 'link');
			$('#artikel_statis').addClass('required');
			$('#artikel_statis').select2({
				width: '100%',
				dropdownAutoWidth : true
			});
		} else if (jenis == '2') {
			$('#statistik_penduduk').show();
			$('#statistik_penduduk').attr('name', 'link');
			$('#statistik_penduduk').addClass('required');
		} else if (jenis == '3') {
			$('#statistik_keluarga').show();
			$('#statistik_keluarga').attr('name', 'link');
			$('#statistik_keluarga').addClass('required');
		} else if (jenis == '4') {
			$('#statistik_program_bantuan').show();
			$('#statistik_program_bantuan').attr('name', 'link');
			$('#statistik_program_bantuan').addClass('required');
		} else if (jenis == '5') {
			$('#statis_lainnya').show();
			$('#statis_lainnya').attr('name', 'link');
			$('#statis_lainnya').addClass('required');
		} else if (jenis == '6') {
			$('#artikel_keuangan').show();
			$('#artikel_keuangan').attr('name', 'link');
			$('#artikel_keuangan').addClass('required');
		} else if (jenis == '7') {
			$('#kelompok').show();
			$('#kelompok').attr('name', 'link');
			$('#kelompok').addClass('required');
		} else if (jenis == '9') {
			$('#suplemen').show();
			$('#suplemen').attr('name', 'link');
			$('#suplemen').addClass('required');
		} else if (jenis == '8') {
			$('#kategori_artikel').show();
			$('#kategori_artikel').attr('name', 'link');
			$('#kategori_artikel').addClass('required');
		} else if (jenis == '99') {
			$('#eksternal').show();
			$('#eksternal > input').show();
			$('#eksternal > input').attr('name', 'link');
		} else {
			$('#jenis_link').hide();
		}
	}
	$(document).ready(function()
	{
		setTimeout(function()
		{
			$('#link_tipe').change();
		}, 500);
	});
</script>
