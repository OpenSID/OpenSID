<!--
*
* Kalau ada file print surat di folder desa/views/surat, pakai file itu.
* Kalau tidak ada, pakai file print surat yang disediakan di release SID di donjo-app/views/surat
*
 -->
<?php $nama_surat = 'print_' . $url; ?>

<?php if(is_file("desa/views/surat/print/$nama_surat.php")): ?>
  <?php include("desa/views/surat/print/$nama_surat.php"); ?>
<?php else: ?>
  <?php include("donjo-app/views/surat/print/$nama_surat.php"); ?>
<?php endif; ?>