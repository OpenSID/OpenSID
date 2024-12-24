<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if ($jam_kerja) : ?>
  <div class="box box-primary box-solid">
    <div class="box-header">
      <h3 class="box-title">
        <i class="fa fa-clock-o mr-1"></i> <?= $judul_widget ?>
      </h3>
      <div class="content">
        <table style="width: 100%;" cellpadding="0" cellspacing="0">
          <thead>
            <tr>
              <th>Hari</th>
              <th>Mulai</th>
              <th>Selesai</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($jam_kerja as $value) : ?>
            <tr>
              <td><?= $value->nama_hari ?></td>
              <?php if ($value->status) : ?>
                <td><?= $value->jam_masuk ?></td>
                <td><?= $value->jam_keluar ?></td>
              <?php else : ?>
                <td colspan="2"><span class="label label-danger"> Libur </span></td>
              <?php endif ?>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif ?> 