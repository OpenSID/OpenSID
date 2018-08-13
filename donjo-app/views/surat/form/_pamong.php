<div class="form-group">
	<label class="col-sm-3 control-label">Staf Pemerintah <?= ucwords($this->setting->sebutan_desa)?></label>
	<div class="col-sm-6 col-lg-4">
		<select class="form-control required input-sm" id="pamong" name="pamong" onchange="var nip=$(this).find(':selected').data('nip'); $('#pamong_nip').val(nip);$(this).closest('.box-body').find('select[name=jabatan]').val($(this).find(':selected').data('jabatan')) ">
			<option selected="selected">-- Pilih Staf Pemerintah <?= ucwords($this->setting->sebutan_desa)?>--</option>
			<?php foreach ($pamong AS $data): ?>
				<?php $tmp_nip = trim($data['pamong_nip'],'-'); ?>
				<option value="<?= $data['pamong_nama']?>" data-jabatan="<?= trim($data['jabatan']) ?>" <?php if ($data['pamong_ttd']==1): $pamong_nip =	$data['pamong_nip']; ?>selected <?php endif; ?> data-nip="<?= $data['pamong_nip']?>">
					<?= $data['pamong_nama']?> (<?= $data['jabatan']?>) <?php if (!empty($tmp_nip)): ?>NIP: <?= $data['pamong_nip'];?><?php endif; ?>
				</option>
			<?php endforeach; ?>
		</select>
		<input name="pamong_nip" id="pamong_nip" type="hidden" value="<?= $pamong_nip; ?>"/>
	</div>
</div>
<div class="form-group">
	<label for="jabatan"  class="col-sm-3 control-label">Menjabat Sebagai</label>
	<div class="col-sm-6 col-lg-4">
		<select class="form-control input-sm required" id="jabatan" name="jabatan">
			<option selected="selected">-- Pilih Jabatan--</option>
			<?php foreach ($pamong AS $data): ?>
				<option <?php if ($data['pamong_ttd']==1): ?>selected<?php endif; ?>><?= $data['jabatan']?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>