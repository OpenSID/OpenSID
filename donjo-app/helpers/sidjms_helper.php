<?php

define("VERSION", '0.6.1');
define("LOKASI_LOGO_DESA", 'desa/logo/');
define("LOKASI_ARSIP", 'desa/arsip/');
define("LOKASI_CONFIG_DESA", 'desa/config/');
define("LOKASI_SURAT_PRINT_DESA", 'desa/surat/print/');
define("LOKASI_SURAT_EXPORT_DESA", 'desa/surat/export/');
define("LOKASI_USER_PICT", 'desa/upload/user_pict/');
define("LOKASI_GALERI", 'desa/upload/galeri/');
define("LOKASI_FOTO_ARTIKEL", 'desa/upload/artikel/');
define("LOKASI_FOTO_LOKASI", 'desa/upload/gis/lokasi/');
define("LOKASI_FOTO_AREA", 'desa/upload/gis/area/');
define("LOKASI_FOTO_GARIS", 'desa/upload/gis/garis/');
define("LOKASI_DOKUMEN", 'desa/upload/dokumen/');

/**
 * Ambil Versi
 *
 * Mengembalikan nomor versi aplikasi
 *
 * @access  public
 * @return  string
 */
  function AmbilVersi()
  {
    return VERSION;
  }

/**
 * LogoDesa
 *
 * Mengembalikan path lengkap untuk file logo desa
 *
 * @access  public
 * @return  string
 */
  function LogoDesa($nama_logo)
  {
    $logo_desa = base_url() . LOKASI_LOGO_DESA . $nama_logo;
    return $logo_desa;
  }

/**
 * KonfigurasiDatabase
 *
 * Mengembalikan path file konfigurasi database desa
 *
 * @access  public
 * @return  string
 */
  function KonfigurasiDatabase()
  {
    $konfigurasi_database = LOKASI_CONFIG_DESA . 'database.php';
    return $konfigurasi_database;
  }

/**
 * SuratExportDesa
 *
 * Mengembalikan path surat ubahan desa apabila ada
 *
 * @access  public
 * @return  string
 */
  function SuratExportDesa($nama_surat)
  {
    $surat_export_desa = LOKASI_SURAT_EXPORT_DESA . $nama_surat . ".rtf";
    if(is_file($surat_export_desa)){
      return $surat_export_desa;
    } else {
      return "";
    }

  }

/**
 * SuratExport
 *
 * Mengembalikan path surat export apabila ada, dengan prioritas:
 *    1. surat export ubahan desa
 *    2. surat export asli SID
 *
 * @access  public
 * @return  string
 */
    function SuratExport($nama_surat)
  {
    if(SuratExportDesa($nama_surat) != ""){
      return SuratExportDesa($nama_surat);
    } elseif(is_file("surat/$nama_surat/$nama_surat.rtf")) {
      return "surat/$nama_surat/$nama_surat.rtf";
    } else {
      return "";
    }
  }

?>