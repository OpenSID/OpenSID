    <select class="form-control required input-sm required" name="syarat[<?= $syarat_id ?>]" >
      <option value=""> -- Pilih dokumen yang melengkapi syarat -- </option>
      <?php foreach ($dokumen AS $key => $data): ?>
      	<?php if ($data['id_syarat'] == $syarat_id): ?>
	        <option value="<?= $data['id']?>" <?php selected($data['id'], $syarat_permohonan[$syarat_id]) ?>><?= $data['nama']?></option>
	      <?php endif; ?>
      <?php endforeach;?>
    </select>
