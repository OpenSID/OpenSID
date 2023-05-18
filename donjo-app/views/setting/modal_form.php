<?php foreach ($this->list_setting as $pengaturan): ?>
    <?php if ($pengaturan->jenis != 'upload' && in_array($pengaturan->kategori, $kategori)): ?>
        <div class="form-group" id="form_<?= $pengaturan->key ?>">
            <label><?= $judul ?></label>
            <?php if ($pengaturan->jenis == 'option' || $pengaturan->jenis == 'boolean'): ?>
                <select class="form-control input-sm select2 required" id="<?= $pengaturan->key ?>" name="<?= $pengaturan->key ?>" <?= $pengaturan->attribute ?>>
                    <?php foreach ($pengaturan->option as $key => $value): ?>
                        <option value="<?= $key ?>" <?= selected($pengaturan->value, $key) ?>><?= $value ?></option>
                    <?php endforeach ?>
                </select>
            <?php elseif ($pengaturan->jenis == 'datetime'): ?>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control input-sm pull-right tgl_1" id="<?= $pengaturan->key ?>" name="<?= $pengaturan->key ?>" type="text" value="<?= $pengaturan->value ?>" <?= $pengaturan->attribute ?>>
                </div>
            <?php elseif ($pengaturan->jenis == 'textarea'): ?>
                <textarea class="form-control input-sm" name="<?= $pengaturan->key ?>" placeholder="<?= $pengaturan->keterangan ?>" rows="7" <?= $pengaturan->attribute ?>><?= $pengaturan->value ?> </textarea>
            <?php else: ?>
                <input id="<?= $pengaturan->key ?>" name="<?= $pengaturan->key ?>" class="form-control input-sm" type="text" value="<?= $pengaturan->value ?>" <?= $pengaturan->attribute ?> />
            <?php endif ?>
            <label><code><?= $pengaturan->keterangan ?></code></label>
        </div>
    <?php endif ?>
<?php endforeach ?>
