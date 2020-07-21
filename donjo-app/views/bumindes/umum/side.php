<div class="box box-widget widget-user-2">
  <div class="box-footer no-padding">
    <ul class="nav nav-stacked">
      <li class="<?php compared_return($selected_nav, "peraturan", "active"); ?>"><a href="<?= site_url('dokumen_sekretariat/peraturan_desa/3') ?>">Buku Peraturan Desa</a></li>
      <li class="<?php compared_return($selected_nav, "keputusan", "active"); ?>"><a href="<?= site_url('dokumen_sekretariat/peraturan_desa/2') ?>">Buku Keputusan Kepala Desa</a></li>
      <li class="<?php compared_return($selected_nav, "aparat", "active"); ?>"><a href="<?= site_url('pengurus') ?>">Buku Aparat Pemerintah Desa</a></li>
      <li class="<?php compared_return($selected_nav, "agenda_keluar", "active"); ?>"><a href="<?= site_url('surat_keluar') ?>">Buku Agenda - Surat Keluar</a></li>
      <li class="<?php compared_return($selected_nav, "agenda_masuk", "active"); ?>"><a href="<?= site_url('surat_masuk') ?>">Buku Agenda - Surat Masuk</a></li>
      <li class="<?php compared_return($selected_nav, "ekspedisi", "active"); ?>"><a href="<?= site_url('bumindes_umum/tables/ekspedisi') ?>">Buku Ekspedisi</a></li>
      <li class="<?php compared_return($selected_nav, "berita", "active"); ?>"><a href="<?= site_url('bumindes_umum/tables/berita') ?>">Buku Lembaran Desa dan Berita Desa</a></li>
    </ul>
  </div>
</div>
