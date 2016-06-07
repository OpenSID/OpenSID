<!--
*
* Kalau ada file print surat di folder desa (lihat LOKASI_SURAT_PRINT_DESA), pakai file itu.
* Kalau tidak ada, pakai file print surat yang disediakan di release SID di donjo-app/views/surat
*
 -->
<?php $nama_surat = 'print_' . $url; ?>
<?php if(is_file(LOKASI_SURAT_PRINT_DESA . $nama_surat . ".php")): ?>
  <?php include(LOKASI_SURAT_PRINT_DESA . $nama_surat . ".php"); ?>
<?php else: ?>
  <?php include("donjo-app/views/surat/print/$nama_surat.php"); ?>
<?php endif; ?>