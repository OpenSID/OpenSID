<!--
*
* Kalau ada file form surat di folder desa, pakai file itu.
* Urutan: (1) LOKASI_SURAT_DESA/<folder_surat_ini>
*         (2) LOKASI_SURAT_FORM_DESA
* Kalau tidak ada, pakai file form surat yang disediakan di release SID
* di donjo-app/surat/<folder_surat_ini>
*
 -->
<?php
	$nama_surat = $url;
  $form_surat = LOKASI_SURAT_DESA . $nama_surat . "/" . $nama_surat . ".php";
  if (is_file($form_surat))
    include($form_surat);
  elseif (is_file(LOKASI_SURAT_FORM_DESA . $nama_surat . ".php"))
	  include(LOKASI_SURAT_FORM_DESA . $nama_surat . ".php");
	else
	  include("surat/$nama_surat/$nama_surat.php");
?>