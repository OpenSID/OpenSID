<?php

define("VERSION", '0.4.1');
define("LOKASI_LOGO_DESA", 'desa/logo/');
define("LOKASI_ARSIP", 'desa/arsip/');
define("LOKASI_CONFIG_DESA", 'desa/config/');
define("LOKASI_SURAT_PRINT_DESA", 'desa/surat/print/');
define("LOKASI_SURAT_EXPORT_DESA", 'desa/surat/export/');

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
?>