    <select class="form-control required input-sm required" name="syarat[]" >
      <option value=""> -- Pilih dokumen yang melengkapi syarat -- </option>
      <?php foreach ($dokumen AS $key => $data): ?>
        <option value="<?= $data['id']?>" <?php selected($data['id'], $syarat_permohonan[$no_syarat]) ?>><?= $data['nama']?></option>
      <?php endforeach;?>
    </select>
