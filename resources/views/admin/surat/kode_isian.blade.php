<?php foreach ($surat['kode_isian'] as $item): ?>
<?php $nama = underscore($item->nama, true, true); ?>
@php $required = $item->required == 1 ? 'required' : '' @endphp
<div class="form-group">
    <label for="<?= $item->nama ?>" class="col-sm-3 control-label"><?= $item->nama ?></label>
    <?php if ($item->tipe == 'select-manual'): ?>
    <div class="col-sm-4">
        <select name="<?= $nama ?>"
            class="form-control input-sm {{$required}}" {{ $item->atribut }}
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
            class="form-control input-sm {{$required}}" {{ $item->atribut }}
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
        <textarea name="<?= $nama ?>" class="form-control input-sm {{$required}}" {{ $item->atribut }} placeholder="<?= $item->deskripsi ?>"></textarea>
    </div>
    <?php elseif ($item->tipe == 'date'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text"
                class="form-control input-sm tgl {{$required}}" {{ $item->atribut }}
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
                class="form-control input-sm jam {{$required}}" {{ $item->atribut }}
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
                class="form-control input-sm tgl_jam {{$required}}" {{ $item->atribut }}
                name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
        </div>
    </div>
    <?php else: ?>
    <div class="col-sm-8">
        <input type="<?= $item->tipe ?>"
            class="form-control input-sm {{$required}}" {{ $item->atribut }}
            name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
    </div>
    <?php endif ?>
</div>
<?php endforeach ?>
