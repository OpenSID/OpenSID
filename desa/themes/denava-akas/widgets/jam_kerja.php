<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($jam_kerja): ?>
  <div class="head-widget flexleft bg-grey-dark2">
    <img src="<?= base_url("{$this->theme_folder}/{$this->theme}/assets/images/icon/jam.svg") ?>" alt=""/>
    <?= $judul_widget ?>
  </div>
  <div class="widget-height bg-white border-grey-soft">
    <div class="widgetscroll">
      <div class="p-10">
        <table class="jamkerja" style="width: 100%;border-radius:0 0 5px 5px 0;overflow:hidden;" cellpadding="0" cellspacing="0">
          <thead>
            <tr>
              <th class="border-grey-soft">Hari</th>
              <th class="border-grey-soft">Masuk</th>
              <th class="border-grey-soft">Keluar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($jam_kerja as $value): ?>
              <tr>
                <td class="border-grey-soft"><?= $value->nama_hari ?></td>
                <?php if ($value->status): ?>
                  <td class="border-grey-soft"><?= $value->jam_masuk ?></td>
                  <td class="border-grey-soft"><?= $value->jam_keluar ?></td>
                <?php else: ?>
                  <td class="border-grey-soft" colspan="2"><span class="label label-danger"> Libur </span></td>
                <?php endif ?>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif ?>
