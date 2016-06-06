<?php

define("VERSION", '0.4.1');
define("LOKASI_LOGO_DESA", 'desa/logo/');

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

?>