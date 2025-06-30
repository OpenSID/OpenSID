<?php foreach ($this->list_setting as $pengaturan): ?>
    <?php if ($pengaturan->jenis != 'upload' && in_array($pengaturan->kategori, $kategori_pengaturan)): ?>
        <div class="form-group" id="form_<?= $pengaturan->key ?>">
            <label><?= SebutanDesa($pengaturan->judul) ?></label>
            <?php if ($pengaturan->jenis == 'option' || $pengaturan->jenis == 'boolean'): ?>
                <select class="form-control input-sm select2 required" id="<?= $pengaturan->key ?>" name="<?= $pengaturan->key ?>" <?= $pengaturan->attribute ?>>
                    <?php foreach ($pengaturan->option as $key => $value): ?>
                        <option value="<?= $key ?>" <?= selected($pengaturan->value, $key) ?>><?= $value ?></option>
                    <?php endforeach ?>
                </select>
            <?php elseif ($pengaturan->jenis == 'multiple-option'): ?>
                <select class="form-control input-sm select2 required" name="<?= $pengaturan->key?>[]" multiple="multiple">
                    <?php foreach ($pengaturan->option as $val): ?>
                        <option value="<?= $val ?>" <?= selected(in_array($val, $pengaturan->value), true) ?>><?= $val ?></option>
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
            <?php elseif ($pengaturan->jenis == 'referensi'): ?>
                <select class="form-control input-sm select2 required" name="<?= $pengaturan->key?>[]" multiple="multiple">
                    <?php
                        $modelData     = $pengaturan->option;
                        $referensiData = (new $modelData['model']())->select([$modelData['value'], $modelData['label']])->get()->toArray();
                        $selectedValue = json_decode($pengaturan->value, 1);
                    ?>
                    <option value="-" <?= selected(empty($selectedValue), true) ?> >Tanpa Referensi (kosong)</option>
                    <?php foreach ($referensiData as $val): ?>
                        <option value="<?= $val[$modelData['value']] ?>" <?= selected(in_array($val[$modelData['value']], $selectedValue), true) ?> ><?= $val[$modelData['label']] ?></option>
                    <?php endforeach ?>
                </select>
            <?php else: ?>
                <input id="<?= $pengaturan->key ?>" name="<?= $pengaturan->key ?>" class="form-control input-sm" type="text" value="<?= $pengaturan->value ?>" <?= $pengaturan->attribute ?> />
            <?php endif ?>
            <label style="margin-top: 5px;"><code><?= $pengaturan->keterangan ?></code></label>
        </div>
    <?php endif ?>
<?php endforeach ?>
