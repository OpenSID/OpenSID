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
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2201 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2112');

        $hasil = $hasil && $this->migrasi_2021120271($hasil);
        $hasil = $hasil && $this->migrasi_2021120371($hasil);
        $hasil = $hasil && $this->migrasi_2021120971($hasil);
        $hasil = $hasil && $this->migrasi_2021121371($hasil);
        $hasil = $hasil && $this->migrasi_2021121571($hasil);

        return $hasil && $this->migrasi_2021121651($hasil);
    }

    protected function migrasi_2021120271($hasil)
    {
        if (! $this->db->field_exists('telegram', 'tweb_penduduk')) {
            $fields = [
                'telegram' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'unique'     => true,
                    'after'      => 'email',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021120971($hasil)
    {
        $list_setting = [
            [
                'key'        => 'tampilan_anjungan',
                'value'      => 0,
                'keterangan' => 'Pilih tampilan di anjungan pada saat tidak ada aktifitas pada halaman login.',
                'jenis'      => 'option-kode',
                'kategori'   => 'setting_mandiri',
            ],
            [
                'key'        => 'waktu_tampilan_anjungan',
                'value'      => 30,
                'keterangan' => 'Atur waktu (detik) kapan tampilan di anjungan akan muncul pada saat tidak ada aktifitas di halaman login.',
                'jenis'      => 'int',
                'kategori'   => 'setting_mandiri',
            ],
            [
                'key'        => 'tampilan_anjungan_slider',
                'keterangan' => 'Pilih album yang akan ditampilkan pada anjungan.',
                'jenis'      => 'option',
                'kategori'   => 'setting_mandiri',
            ],
            [
                'key'        => 'tampilan_anjungan_video',
                'keterangan' => 'Masukan link video dengan format <code>.mp4</code> yang akan ditampilkan pada anjungan',
                'kategori'   => 'setting_mandiri',
            ],
        ];

        foreach ($list_setting as $setting) {
            $hasil = $hasil && $this->tambah_setting($setting);
        }

        $id_setting = $this->db->get_where('setting_aplikasi', ['key' => 'tampilan_anjungan'])->row()->id;
        if ($id_setting) {
            $this->db->where('id_setting', $id_setting)->delete('setting_aplikasi_options');

            $hasil = $hasil && $this->db->insert_batch(
                'setting_aplikasi_options',
                [
                    ['id_setting' => $id_setting, 'kode' => '0', 'value' => 'Slider'],
                    ['id_setting' => $id_setting, 'kode' => '1', 'value' => 'Video'],
                ]
            );
        }

        return $hasil;
    }

    protected function migrasi_2021120371($hasil)
    {
        return $hasil && $this->db->where('url_surat', 'surat_ket_pindah_penduduk')->update('tweb_surat_format', ['lampiran' => 'f-1.03.php,f-1.08.php,f-1.25.php,f-1.27.php']);
    }

    protected function migrasi_2021121371($hasil)
    {
        if (! $this->db->field_exists('telegram_token', 'tweb_penduduk')) {
            $fields = [
                'telegram_token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'unique'     => true,
                    'null'       => true,
                    'after'      => 'telegram',
                ],
                'telegram_tgl_kadaluarsa' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'telegram_token',
                ],
                'telegram_tgl_verifikasi' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'telegram_tgl_kadaluarsa',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_penduduk', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021121571($hasil)
    {
        if (! $this->db->field_exists('tebal', 'line')) {
            $fields = [
                'tebal' => [
                    'type'       => 'INT',
                    'constraint' => 2,
                    'null'       => true,
                    'default'    => '3',
                    'after'      => 'tipe',
                ],
                'jenis' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => true,
                    'default'    => 'solid',
                    'after'      => 'tebal',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('line', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2021121651($hasil)
    {
        // Ubah panjang kolom tag_id_card
        $fields = [
            'tag_id_card' => [
                'type'       => 'VARCHAR',
                'constraint' => 17,
                'null'       => true,
                'default'    => null,
            ],
        ];

        return $hasil && $this->dbforge->modify_column('tweb_penduduk', $fields);
    }
}
