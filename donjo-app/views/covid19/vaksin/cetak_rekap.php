
<div class="form-group">
    <label for="penandatangan_pdf">Tanda Tangan</label>
    <select name="sekdes" id="sekdes" class="form-control input-sm select2">
        <?php foreach ($sekdes as $data) : ?>
            <option value="<?= $data['pamong_id'] ?>" data-jabatan="<?= trim($data['pamong_jabatan']) ?>" <?= selected($data['jabatan_id'], $sekdes_id) ?>>
                <?= $data['pamong_nama'] ?> (<?= $data['pamong_jabatan'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="form-group">
    <label class="control-label" style="text-align:left;" for="penandatangan_pdf">Rentang Umur</label>
    <div class="">
        <input name="umur" id="umur" class="form-control input-sm" placeholder="Masukan rentang umur" title="Contoh : 20-30" type="text" autocomplete="off">
    </div>
</div>