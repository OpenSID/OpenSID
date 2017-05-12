<!--
*
* Kalau ada file form surat di folder desa (lihat LOKASI_SURAT_FORM_DESA), pakai file itu.
* Kalau tidak ada, pakai file form surat yang disediakan di release SID di donjo-app/views/surat
*
 -->
<?php $nama_surat = $url; ?>
<?php if(is_file(LOKASI_SURAT_FORM_DESA . $nama_surat . ".php")): ?>
  <?php include(LOKASI_SURAT_FORM_DESA . $nama_surat . ".php"); ?>
<?php else: ?>
  <?php include("donjo-app/views/surat/form/$nama_surat.php"); ?>
<?php endif; ?>