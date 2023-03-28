<?php foreach ($surat['kode_isian'] as $item): ?>
<?php $nama = underscore($item->nama, true, true); ?>
<div class="form-group">
    <label for="<?= $item->nama ?>" class="col-sm-3 control-label"><?= $item->nama ?></label>
    <?php if ($item->tipe == 'select-manual'): ?>
    <div class="col-sm-4">
        <select name="<?= $nama ?>"
            <?= $item->atribut ? str_replace('class="', 'class="form-control input-sm ', $item->atribut) : 'class="form-control input-sm"' ?>
            placeholder="<?= $item->deskripsi ?>">
            <option value="">-- <?= $item->deskripsi ?> --</option>
            <?php foreach ($item->pilihan as $key => $pilih): ?>
            <option value="<?= $pilih ?>"><?= $pilih ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <?php elseif ($item->tipe == 'select-otomatis'): ?>
    <div class="col-sm-4">
        <select name="<?= $nama ?>"
            <?= $item->atribut ? str_replace('class="', 'class="form-control input-sm ', $item->atribut) : 'class="form-control input-sm"' ?>
            placeholder="<?= $item->deskripsi ?>">
            <option value="">-- <?= $item->deskripsi ?> --</option>
            <?php foreach (ref($item->refrensi) as $key => $pilih): ?>
            <option value="<?= $pilih->nama ?>"><?= $pilih->nama ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <?php elseif ($item->tipe == 'textarea'): ?>
    <div class="col-sm-8">
        <textarea name="<?= $nama ?>" <?= $item->atribut ? str_replace('class="', 'class="form-control input-sm ', $item->atribut) : 'class="form-control input-sm"' ?> placeholder="<?= $item->deskripsi ?>"></textarea>
    </div>
    <?php elseif ($item->tipe == 'date'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text"
                <?= $item->atribut ? str_replace('class="', 'class="form-control input-sm tgl ', $item->atribut) : 'class="form-control input-sm tgl"' ?>
                name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
        </div>
    </div>
    <?php elseif ($item->tipe == 'time'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
            </div>
            <input type="text"
                <?= $item->atribut ? str_replace('class="', 'class="form-control input-sm jam ', $item->atribut) : 'class="form-control input-sm jam"' ?>
                name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
        </div>
    </div>
    <?php elseif ($item->tipe == 'datetime'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text"
                <?= $item->atribut ? str_replace('class="', 'class="form-control input-sm tgl_jam ', $item->atribut) : 'class="form-control input-sm tgl_jam"' ?>
                name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
        </div>
    </div>
    <?php else: ?>
    <div class="col-sm-8">
        <input type="<?= $item->tipe ?>"
            <?= $item->atribut ? str_replace('class="', 'class="form-control input-sm ', $item->atribut) : 'class="form-control input-sm"' ?>
            name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
    </div>
    <?php endif ?>
</div>
<?php endforeach ?>
