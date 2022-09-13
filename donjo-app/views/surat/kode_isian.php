<?php foreach ($surat['kode_isian'] as $item): ?>
    <?php $nama = underscore($item->nama, true, true) ?>
    <div class="form-group">
        <label for="<?= $item->nama ?>" class="col-sm-3 control-label"><?= $item->nama ?></label>
        <?php if ($item->tipe == 'textarea'): ?>
            <div class="col-sm-8">
                <textarea name="<?= $nama ?>" class="form-control input-sm" placeholder="<?= $item->deskripsi ?>" <?= $item->atribut ?>></textarea>
            </div>
        <?php elseif ($item->tipe == 'date'): ?>
            <div class="col-sm-3 col-lg-2">
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control input-sm tgl" name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" <?= $item->atribut ?>/>
                </div>
            </div>
        <?php elseif ($item->tipe == 'time'): ?>
            <div class="col-sm-3 col-lg-2">
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control input-sm jam" name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" <?= $item->atribut ?>/>
                </div>
            </div>
        <?php else: ?>
            <div class="col-sm-8">
                <input type="<?= $item->tipe ?>" class="form-control input-sm <?= $class ?>" name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" <?= $item->atribut ?>/>
            </div>
        <?php endif ?>
    </div>
<?php endforeach ?>