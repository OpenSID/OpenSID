<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_layanan extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->tambah_modul_layanan($hasil);
        $hasil = $hasil && $this->pengaturan_aplikasi_untuk_layanan($hasil);
        $hasil = $hasil && $this->ubah_pengaturan_aplikasi_layanan($hasil);

        return $hasil && $this->tambah_modul_pendaftaran_kerjasama($hasil);
    }

    // Dari migrasi premium 2011
    protected function tambah_modul_layanan($hasil)
    {
        // Tambah menu layanan pelanggan
        $hasil = $hasil && $this->tambah_modul([
            'id'         => '313',
            'modul'      => 'Layanan Pelanggan',
            'url'        => 'pelanggan',
            'aktif'      => '1',
            'ikon'       => 'fa-credit-card',
            'urut'       => '5',
            'level'      => '0',
            'parent'     => '200',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-credit-card',
        ]);

        // Pengaturan API Key
        return $hasil && $this->tambah_setting([
            'key'        => 'api_key_opensid',
            'value'      => '',
            'keterangan' => 'Opensid API Key untuk Pelanggan OpenDesa',
            'kategori'   => '',
        ]);
    }

    // Dari migrasi premium 2107
    protected function pengaturan_aplikasi_untuk_layanan($hasil)
    {
        // Url production layanan opendesa
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'layanan_opendesa_server',
            'value'      => 'https://layanan.opendesa.id',
            'keterangan' => 'Alamat Server Layanan OpenDESA',
            'kategori'   => 'sistem',
        ]);

        // Url development layanan opendesa
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'layanan_opendesa_dev_server',
            'value'      => '',
            'keterangan' => 'Alamat Server Dev Layanan OpenDESA',
            'kategori'   => 'sistem',
        ]);

        // Token pelanggan layanan opendesa
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'layanan_opendesa_token',
            'value'      => '',
            'jenis'      => 'textarea',
            'keterangan' => 'Token pelanggan Layanan OpenDESA',
            'kategori'   => 'sistem',
        ]);

        // Hapus API Key Pelanggan
        $hasil = $hasil && $this->db->where('key', 'api_key_opensid')->delete('setting_aplikasi');

        // Ubah kategori layanan_opendesa_server, layanan_opendesa_dev_server, layanan_opendesa_token jadi pelanggan
        return $hasil && $this->db
            ->where_in('key', ['layanan_opendesa_server', 'layanan_opendesa_dev_server', 'layanan_opendesa_token'])
            ->update('setting_aplikasi', ['kategori' => 'pelanggan']);
    }

    // Dari migrasi premium 2108
    protected function ubah_pengaturan_aplikasi_layanan($hasil)
    {
        // Hapus key layanan_opendesa_server, layanan_opendesa_dev_server dan dev_tracker
        return $hasil && $this->db
            ->where_in('key', ['layanan_opendesa_server', 'layanan_opendesa_dev_server', 'dev_tracker'])
            ->delete('setting_aplikasi');
    }

    // Dari migrasi premium 2111
    protected function tambah_modul_pendaftaran_kerjasama($hasil)
    {
        return $hasil && $this->tambah_modul([
            'id'         => 331,
            'modul'      => 'Pendaftaran Kerjasama',
            'url'        => 'pendaftaran_kerjasama',
            'aktif'      => 1,
            'ikon'       => 'fa-list',
            'urut'       => 6,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-list',
            'parent'     => 200,
        ]);
    }
}
