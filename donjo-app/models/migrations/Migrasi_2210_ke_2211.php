<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2210_ke_2211 extends MY_model
{
    const SATU_DATA_MODUL_ID = 337;
    const DTKS_MODUL_ID = 338;

    protected $id_acuan = [];

    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->tambahSubMenuDTKSK($hasil);
       
        return $hasil;
    }

    protected function tambahSubMenuDTKSK($hasil)
    {
        $hasil = $hasil && $this->tambah_modul([
            'id'         => self::SATU_DATA_MODUL_ID,
            'modul'      => 'Satu Data',
            'url'        => '',
            'aktif'      => '1',
            'ikon'       => 'fa-info',
            'urut'       => '180',
            'level'      => '1',
            'parent'     => '0',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-info',
        ]);
        $hasil = $hasil && $this->tambah_modul([
            'id'         => self::DTKS_MODUL_ID,
            'modul'      => 'DTKS',
            'url'        => 'dtks/clear',
            'aktif'      => '1',
            'ikon'       => 'fa-info',
            'urut'       => '1',
            'level'      => '2',
            'parent'     => self::SATU_DATA_MODUL_ID,
            'hidden'     => '0',
            'ikon_kecil' => 'fa-info',
        ]);
        
        return $hasil;
    }

}