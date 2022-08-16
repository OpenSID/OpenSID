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

class Migrasi_fitur_premium_2009 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->buku_administrasi_desa($hasil);

        return $hasil && $this->tambah_kolom_pemerintahan_desa($hasil);
    }

    private function buku_administrasi_desa($hasil)
    {
        // Menu parent Buku Administrasi Desa
        $menu[0] = [
            'id'         => '301',
            'modul'      => 'Buku Administrasi Desa',
            'url'        => '',
            'aktif'      => '1',
            'ikon'       => 'fa-paste',
            'urut'       => '6',
            'level'      => '2',
            'hidden'     => '0',
            'ikon_kecil' => 'fa fa-paste',
            'parent'     => 0,
        ];
        $menu[1] = [
            'id'         => '302',
            'modul'      => 'Administrasi Umum',
            'url'        => 'bumindes_umum',
            'aktif'      => '1',
            'ikon'       => 'fa-bookmark',
            'urut'       => '1',
            'level'      => '2',
            'hidden'     => '0',
            'ikon_kecil' => 'fa fa-bookmark',
            'parent'     => 301,
        ];
        $menu[2] = [
            'id'         => '303',
            'modul'      => 'Administrasi Penduduk',
            'url'        => 'bumindes_penduduk',
            'aktif'      => '1',
            'ikon'       => 'fa-users',
            'urut'       => '2',
            'level'      => '2',
            'hidden'     => '0',
            'ikon_kecil' => 'fa fa-users',
            'parent'     => 301,
        ];
        $menu[3] = [
            'id'         => '304',
            'modul'      => 'Administrasi Keuangan',
            'url'        => 'bumindes_keuangan',
            'aktif'      => '1',
            'ikon'       => 'fa-money',
            'urut'       => '3',
            'level'      => '2',
            'hidden'     => '0',
            'ikon_kecil' => 'fa fa-money',
            'parent'     => 301,
        ];
        $menu[4] = [
            'id'         => '305',
            'modul'      => 'Administrasi Pembangunan',
            'url'        => 'bumindes_pembangunan',
            'aktif'      => '1',
            'ikon'       => 'fa-university',
            'urut'       => '4',
            'level'      => '2',
            'hidden'     => '0',
            'ikon_kecil' => 'fa fa-university',
            'parent'     => 301,
        ];
        $menu[5] = [
            'id'         => '306',
            'modul'      => 'Administrasi Lainnya',
            'url'        => 'bumindes_lain',
            'aktif'      => '1',
            'ikon'       => 'fa-archive',
            'urut'       => '5',
            'level'      => '2',
            'hidden'     => '0',
            'ikon_kecil' => 'fa fa-archive',
            'parent'     => 301,
        ];

        foreach ($menu as $modul) {
            $hasil = $hasil && $this->tambah_modul($modul);
        }

        // Menu parent Buku Administrasi Desa. END
        // Dokumen tidak harus ada file
        $hasil = $hasil && $this->db->query('ALTER TABLE dokumen MODIFY satuan VARCHAR(200) NULL DEFAULT NULL;');
        // Sembunyikan menu yg sdh masuk buku administrasi umum
        $this->db->like('url', 'surat_keluar')->update('setting_modul', ['hidden' => 2]);
        $this->db->like('url', 'surat_masuk')->update('setting_modul', ['hidden' => 2]);
        $this->db->like('url', 'dokumen_sekretariat')->update('setting_modul', ['hidden' => 2]);
        // Tambah kolom untuk ekspedisi
        if (! $this->db->field_exists('created_at', 'surat_keluar')) {
            $fields              = [];
            $fields['ekspedisi'] = [
                'type'       => 'tinyint',
                'constraint' => 1,
                'default'    => 0,
            ];
            $fields['tanggal_pengiriman'] = [
                'type'    => 'date',
                'null'    => true,
                'default' => null,
            ];
            $fields['tanda_terima'] = [
                'type'       => 'varchar',
                'constraint' => 200,
            ];
            $fields['keterangan'] = [
                'type'       => 'varchar',
                'constraint' => 500,
            ];
            $hasil = $hasil && $this->dbforge->add_column('surat_keluar', $fields);
            $hasil = $hasil && $this->dbforge->add_column('surat_keluar', 'created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            $hasil = $hasil && $this->dbforge->add_column('surat_keluar', 'created_by int(11) NOT NULL');
            $hasil = $hasil && $this->dbforge->add_column('surat_keluar', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            $hasil = $hasil && $this->dbforge->add_column('surat_keluar', 'updated_by int(11) NOT NULL');
        }
        // Menu permohonan surat untuk operator
        return $hasil && $this->tambah_modul([
            'id'         => '310',
            'modul'      => 'Buku Eskpedisi',
            'url'        => 'ekspedisi/clear',
            'aktif'      => '1',
            'ikon'       => 'fa-files-o',
            'urut'       => '0',
            'level'      => '0',
            'parent'     => '302',
            'hidden'     => '0',
            'ikon_kecil' => 'fa-files-o',
        ]);
    }

    private function tambah_kolom_pemerintahan_desa($hasil)
    {
        // Struktur pemerintahan desa
        if (! $this->db->field_exists('atasan', 'tweb_desa_pamong')) {
            $fields['atasan'] = [
                'type'       => 'INT',
                'constraint' => 11,
            ];
            $fields['bagan_tingkat'] = [
                'type'       => 'TINYINT',
                'constraint' => 2,
            ];
            $fields['bagan_offset'] = [
                'type'       => 'INT',
                'constraint' => 3,
            ];
            $fields['bagan_layout'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_desa_pamong', $fields);
        }
        // Struktur pemerintahan desa
        if (! $this->db->field_exists('bagan_warna', 'tweb_desa_pamong')) {
            $fields                = [];
            $fields['bagan_warna'] = [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => null,
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_desa_pamong', $fields);
        }
        // Ukuran Lebar Bagan
        $query = "
            INSERT INTO `setting_aplikasi` (`key`, `value`, `keterangan`, `jenis`, `kategori`) VALUES
            ('ukuran_lebar_bagan', '800', 'Ukuran Lebar Bagan Organisasi (800 / 1200 / 1400)', 'int', 'conf_web')
            ON DUPLICATE KEY UPDATE `key` = VALUES(`key`), keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)";

        return $hasil && $this->db->query($query);
    }
}