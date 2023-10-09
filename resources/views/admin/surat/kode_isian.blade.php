<?php foreach ($surat['kode_isian'] as $item): ?>
<?php $nama = underscore($item->nama, true, true); ?>
<?php $class = buat_class($item->atribut, '', $item->required) ?>
<div class="form-group">
    <label for="<?= $item->nama ?>" class="col-sm-3 control-label"><?= $item->nama ?></label>
    <?php if ($item->tipe == 'select-manual'): ?>
    <div class="col-sm-4">
        <select name="<?= $nama ?>" <?= $class ?>>
            <option value="">-- <?= $item->deskripsi ?> --</option>
            <?php foreach ($item->pilihan as $key => $pilih): ?>
            <option @selected(set_value($nama) == $pilih) value="<?= $pilih ?>"><?= $pilih ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <?php elseif ($item->tipe == 'select-otomatis'): ?>
    <div class="col-sm-4">
        <select name="<?= $nama ?>" <?= $class ?> placeholder="<?= $item->deskripsi ?>">
            <option value="">-- <?= $item->deskripsi ?> --</option>
            <?php foreach (ref($item->refrensi) as $key => $pilih): ?>
            <option @selected(set_value($nama) == $pilih->nama) value="<?= $pilih->nama ?>"><?= $pilih->nama ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <?php elseif ($item->tipe == 'textarea'): ?>
    <div class="col-sm-8">
        <textarea name="<?= $nama ?>" <?= $class ?> placeholder="<?= $item->deskripsi ?>"><?= set_value($nama) ?></textarea>
    </div>
    <?php elseif ($item->tipe == 'date' || $item->tipe == 'hari' || $item->tipe == 'hari-tanggal'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" <?= buat_class($item->atribut, 'form-control input-sm tgl', $item->required) ?> name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" value="<?= set_value($nama) ?>" />
        </div>
    </div>
    <?php elseif ($item->tipe == 'time'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" <?= buat_class($item->atribut, 'form-control input-sm jam', $item->required) ?> name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" value="<?= set_value($nama) ?>" />
        </div>
    </div>
    <?php elseif ($item->tipe == 'datetime'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" <?= buat_class($item->atribut, 'form-control input-sm tgl_jam', $item->required) ?> name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" value="<?= set_value($nama) ?>" />
        </div>
    </div>
    <?php else: ?>
    <div class="col-sm-8">
        <input type="<?= $item->tipe ?>" <?= $class ?> name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" value="<?= set_value($nama) ?>" />
    </div>
    <?php endif ?>
</div>
<?php endforeach ?>
