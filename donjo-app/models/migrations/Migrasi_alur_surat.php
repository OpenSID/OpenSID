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

class Migrasi_alur_surat extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil && $this->tambah_setting([
            'key'        => 'verifikasi_kades',
            'value'      => '0',
            'keterangan' => 'Verifikasi Surat Oleh Kepala Desa',
            'kategori'   => 'alur_surat',
            'jenis'      => 'boolean',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'verifikasi_sekdes',
            'value'      => '0',
            'keterangan' => 'Verifikasi Surat Oleh Sekretaris daerah',
            'kategori'   => 'alur_surat',
            'jenis'      => 'boolean',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'verifikasi_operator',
            'value'      => '1',
            'keterangan' => 'Verifikasi Surat Oleh Operator (Layanan Mandiri)',
            'kategori'   => 'alur_surat',
            'jenis'      => 'boolean',
        ]);

        if (! $this->db->field_exists('verifikasi_sekdes', 'log_surat')) {
            $fields = [
                'verifikasi_sekdes' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        if (! $this->db->field_exists('verifikasi_kades', 'log_surat')) {
            $fields = [
                'verifikasi_kades' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        if (! $this->db->field_exists('verifikasi_operator', 'log_surat')) {
            $fields = [
                'verifikasi_operator' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        if (! $this->db->field_exists('tte', 'log_surat')) {
            $fields = [
                'tte' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        if (! $this->db->field_exists('log_verifikasi', 'log_surat')) {
            $fields = [
                'log_verifikasi' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_surat', $fields);
        }

        return $hasil && $this->ubah_modul(32, ['url' => 'keluar/clear/masuk']);
    }
}
