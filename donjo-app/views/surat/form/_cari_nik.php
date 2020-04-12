<div class="form-group">
	<label for="nik"  class="col-sm-3 control-label">NIK / Nama <?= $pemohon?></label>
	<div class="col-sm-6 col-lg-4">
  	<select class="form-control required input-sm select2-nik-ajax readonly-permohonan readonly-periksa" id="nik" name="nik" style ="width:100%;" data-filter-sex="<?= $filter_sex ?>" data-url="<?= site_url('surat/list_penduduk_ajax')?>" onchange="formAction('main')">
			<?php if ($individu): ?>
				<option value="<?= $individu['id']?>" selected><?= $individu['nik'].' - '.$individu['nama']?></option>
			<?php endif;?>
		</select>
	</div>
</div>