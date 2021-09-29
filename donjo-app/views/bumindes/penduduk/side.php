<div class="box box-widget">
  <div class="box-footer no-padding">
    <ul class="nav nav-stacked">
      <li <?= jecho($selected_nav, 'induk', 'class="active"'); ?>><a href="<?= site_url('bumindes_penduduk_induk/clear') ?>">Buku Induk Penduduk</a></li>
      <li <?= jecho($selected_nav, 'mutasi', 'class="active"'); ?>><a href="<?= site_url('bumindes_penduduk_mutasi/clear') ?>">Buku Mutasi Penduduk Desa</a></li>
      <li <?= jecho($selected_nav, 'rekapitulasi', 'class="active"'); ?>><a href="<?= site_url('bumindes_penduduk_rekapitulasi/clear') ?>">Buku Rekapitulasi Jumlah Penduduk</a></li>
      <li <?= jecho($selected_nav, 'sementara', 'class="active"'); ?>><a href="<?= site_url('bumindes_penduduk_sementara/clear') ?>">Buku Penduduk Sementara</a></li>
      <li <?= jecho($selected_nav, 'ktpkk', 'class="active"'); ?>"><a href="<?= site_url('bumindes_penduduk_ktpkk/clear') ?>">Buku KTP dan KK</a></li>
    </ul>
  </div>
</div>

<div class="box box-widget">
  <div class="box-footer no-padding">
    <ul class="nav nav-stacked">
      <li <?= jecho($selected_nav, 'sinkronasi', 'class="active"'); ?>><a href="<?= site_url('laporan_penduduk') ?>">Sinkronasi Laporan Penduduk</a></li>
    </ul>
  </div>
</div>