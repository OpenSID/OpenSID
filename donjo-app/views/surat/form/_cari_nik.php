<div class="form-group cari_nik">
	<label for="nik"  class="col-sm-3 control-label">NIK / Nama <?= $pemohon?></label>
	<div class="col-sm-6 col-lg-4">
  	<select class="form-control required input-sm select2-nik-ajax readonly-permohonan readonly-periksa" id="nik" name="nik" style ="width:100%;" data-filter-sex="<?= $filter_sex ?>" data-url="<?= site_url('surat/list_penduduk_ajax')?>" onchange="formAction('main')">
			<?php if ($individu): ?>
				<option value="<?= $individu['id']?>" selected><?= $individu['nik'] . ' - ' . $individu['tag_id_card'] . ' - ' . $individu['nama']?></option>
			<?php endif; ?>
		</select>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	// Daftar angka di script berikut adalah key number untuk tombol. Karena dropdown ini memakai select2 maka ketika e_KTP discan hasil pencarian akan otomatis dan default memilih record no. 1. Maka proses harus di delay supaya hasil search tampil terlebih dahulu dengan menghilangkan semua karakter di belakang nomor id yg discan.
	$('#nik').on('select2:open', e => {
		$('.select2-search__field').on('keydown.ajaxfix', e => {
			if (![9, 13, 16, 17, 18, 27, 33, 34, 35, 36, 37, 38, 39, 40].includes(e.which)) {
				$('.select2-results__option').removeClass('select2-results__option--highlighted');
			}
		});
	}).on('select2:closing', e => {
		$('.select2-search__field').off('keydown.ajaxfix');
	});
});
</script>
