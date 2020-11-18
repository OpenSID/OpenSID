    <select class="form-control required input-sm syarat required" name="syarat[<?= $syarat_id ?>]" onchange="cek_perhatian($(this));">
      <option value=""> -- Pilih dokumen yang melengkapi syarat -- </option>
      <?php foreach ($dokumen AS $key => $data): ?>
      	<?php if ($data['id_syarat'] == $syarat_id): ?>
	        <option value="<?= $data['id']?>" <?php selected($data['id'], $syarat_permohonan[$syarat_id]) ?>><?= $data['nama']?></option>
	      <?php endif; ?>
      <?php endforeach;?>
      <?php if ($cek_anjungan): ?>
        <option value="-1" <?php selected('-1', '$syarat_permohonan[$syarat_id]') ?>>Bawa bukti fisik ke Kantor Desa</option>
			<?php endif; ?>
    </select>
    <i class="fa fa-exclamation-triangle text-red perhatian" style="display: none; padding-left: 10px; font-weight: bold;">&nbsp;Perhatian!</i>
