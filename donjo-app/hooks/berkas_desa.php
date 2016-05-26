<?php
  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Berkas desa
| -------------------------------------------------------------------------
| File ini berisi functions untuk menangani file yang telah disesuaikan
| oleh desa.
|
| Function copy_berkas_data menggantikan file dari release asli SID CRI dengan
| file yang telah disesuaikan untuk desa. File aslinya diganti namanya dengan akhiran .orig
| Function ini dipanggil sebagai
| hook (lihat db_donjo/config/hooks.php) pada tahap post_controller.
|
*/

class BerkasDesa {

  function recurse_copy($src,$dst) {
      $dir = opendir($src);
      @mkdir($dst);
      while(false !== ( $file = readdir($dir)) ) {
          if (( $file != '.' ) && ( $file != '..' )) {
              if ( is_dir($src . '/' . $file) ) {
                  recurse_copy($src . '/' . $file,$dst . '/' . $file);
              }
              else {
                  if (!file_exists($dst . '/' . $file . '.orig')) {
                    if (file_exists($dst . '/' . $file)) {
                      rename($dst . '/' . $file,$dst . '/' . $file . '.orig');
                    }
                    copy($src . '/' . $file,$dst . '/' . $file);
                  }
              }
          }
      }
      closedir($dir);
  }

  public function copy_berkas_desa(){
    $this->recurse_copy("{$_SERVER['DOCUMENT_ROOT']}/desa/views/surat", "{$_SERVER['DOCUMENT_ROOT']}/donjo-app/views/surat");
  }

}

?>