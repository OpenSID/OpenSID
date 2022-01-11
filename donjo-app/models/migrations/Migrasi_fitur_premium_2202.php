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

class Migrasi_fitur_premium_2202 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2201');
        $hasil = $hasil && $this->migrasi_2022010671($hasil);

        return $hasil && $this->migrasi_2022011071($hasil);
    }

    protected function migrasi_2022010671($hasil)
    {
        // Tambah tabel ref_penduduk_kehamilan
        if (! $this->db->table_exists('ref_penduduk_hamil')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_field($fields);
            $hasil = $hasil && $this->dbforge->add_key('id', true);
            $hasil = $hasil && $this->dbforge->create_table('ref_penduduk_hamil', true);

            // tambahkan data awal
            $insert_batch = [
                ['id' => 1, 'nama' => 'Hamil'],
                ['id' => 2, 'nama' => 'Tidak Hamil'],
            ];

            $hasil = $hasil && $this->db->insert_batch('ref_penduduk_hamil', $insert_batch);
        }

        return $hasil;
    }

    protected function migrasi_2022011071($hasil)
    {
        $folder = 'upload/pendaftaran';
        if (! file_exists('/desa/' . $folder)) {
            mkdir('desa/' . $folder, 0755, true);
            xcopy('desa-contoh/' . $folder, 'desa/' . $folder);
        }

        if (! $this->db->field_exists('aktif', 'tweb_penduduk_mandiri')) {
            $fields = [
                'aktif' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'null'       => true,
                    'default'    => 1,
                    'after'      => 'id_pend',
                ],
                'scan_ktp' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'aktif',
                ],
                'scan_kk' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'scan_ktp',
                ],
                'foto_selfie' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'scan_kk',
                ],

            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk_mandiri', $fields);
        }

        return $hasil && $this->tambah_setting([
            'key'        => 'tampilkan_pendaftaran',
            'value'      => 0,
            'keterangan' => 'Aktifkan / Non Aktifkan Pendaftaran Layanan Mandiri',
            'jenis'      => 'boolean',
            'kategori'   => 'setting_mandiri',
        ]);
    }
}
