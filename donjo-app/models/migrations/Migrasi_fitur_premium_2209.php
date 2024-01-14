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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Libraries\TinyMCE;
use App\Models\FormatSurat;
use App\Models\KB;
use App\Models\Pamong;
use App\Models\RefJabatan;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2209 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2208');
        $hasil = $hasil && $this->migrasi_2022080271($hasil);
        $hasil = $hasil && $this->migrasi_2022080272($hasil);
        $hasil = $hasil && $this->migrasi_2022070551($hasil);
        $hasil = $hasil && $this->migrasi_2022080471($hasil);
        $hasil = $hasil && $this->migrasi_2022080571($hasil);
        $hasil = $hasil && $this->migrasi_2022080451($hasil);
        $hasil = $hasil && $this->migrasi_2022080971($hasil);
        $hasil = $hasil && $this->migrasi_2022081171($hasil);
        $hasil = $hasil && $this->migrasi_2022081271($hasil);
        $hasil = $hasil && $this->migrasi_2022081571($hasil);
        $hasil = $hasil && $this->migrasi_2022081951($hasil);
        $hasil = $hasil && $this->migrasi_2022082071($hasil);
        $hasil = $hasil && $this->migrasi_2022082171($hasil);
        $hasil = $hasil && $this->migrasi_2022082271($hasil);
        $hasil = $hasil && $this->migrasi_2022082371($hasil);
        $hasil = $hasil && $this->migrasi_2022082571($hasil);
        $hasil = $hasil && $this->migrasi_2022083071($hasil);
        $hasil = $hasil && $this->migrasi_2022083171($hasil);

        return $hasil && $this->migrasi_2022090171($hasil);
    }

    protected function migrasi_2022080271($hasil)
    {
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'jenis_peta',
            'value'      => '5',
            'keterangan' => 'Jenis peta yang digunakan',
            'jenis'      => 'option-kode',
        ]);

        if ($this->db->table_exists('setting_aplikasi_options')) {
            $id_setting = $this->db->get_where('setting_aplikasi', ['key' => 'jenis_peta'])->row()->id;

            if ($id_setting) {
                $this->db->where('id_setting', $id_setting)->delete('setting_aplikasi_options');

                $hasil = $hasil && $this->db->insert_batch(
                    'setting_aplikasi_options',
                    [
                        ['id_setting' => $id_setting, 'kode' => '1', 'value' => 'OpenStreetMap'],
                        ['id_setting' => $id_setting, 'kode' => '2', 'value' => 'OpenStreetMap H.O.T'],
                        ['id_setting' => $id_setting, 'kode' => '3', 'value' => 'Mapbox Streets'],
                        ['id_setting' => $id_setting, 'kode' => '4', 'value' => 'Mapbox Satellite'],
                        ['id_setting' => $id_setting, 'kode' => '5', 'value' => 'Mapbox Satellite-Street'],
                    ]
                );
            }
        }

        return $hasil;
    }

    protected function migrasi_2022080272($hasil)
    {
        if (! $this->db->field_exists('notif_telegram', 'user')) {
            $fields = [
                'notif_telegram' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                    'after'      => 'nama',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        if (! $this->db->field_exists('id_telegram', 'user')) {
            $fields = [
                'id_telegram' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'null'       => false,
                    'default'    => 0,
                    'after'      => 'nama',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022070551($hasil)
    {
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

    protected function migrasi_2022080471($hasil)
    {
        // Update kolom pamong_ttd jika null menjadi 0
        Pamong::whereNull('pamong_ttd')->update(['pamong_ttd' => 0]);

        if (! $this->db->table_exists('ref_jabatan')) {
            // Tambah tabel ref_jabatan
            $ref_jabatan = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'tupoksi' => [
                    'type'    => 'LONGTEXT',
                    'null'    => true,
                    'default' => null,
                ],
                'jenis' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                ],
            ];
            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($ref_jabatan)
                ->create_table('ref_jabatan', true);

            if (! $this->db->field_exists('jabatan_id', 'tweb_desa_pamong')) {
                // Tambah field jabatan_id
                $tweb_desa_pamong['jabatan_id'] = [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ];
                $hasil = $hasil && $this->dbforge->add_column('tweb_desa_pamong', $tweb_desa_pamong);
            }

            $jabatan = DB::table('tweb_desa_pamong')
                ->select(['pamong_id', 'jabatan', 'pamong_ttd', 'pamong_ub'])
                ->where('pamong_ttd', 0)
                ->Where('pamong_ub', 0)
                ->get();

            RefJabatan::insert([
                [
                    'id'    => 1,
                    'nama'  => 'Kepala Desa',
                    'jenis' => 1,
                ],
                [
                    'id'    => 2,
                    'nama'  => 'Sekretaris Desa',
                    'jenis' => 1,
                ],
            ]);

            if ($jabatan) {
                $simpan = collect($jabatan)->unique('jabatan')->map(static function ($item, $key) {
                    $id = $key + 3;
                    Pamong::where('jabatan', $item->jabatan)->update(['jabatan_id' => $id]);

                    return [
                        'id'    => $id,
                        'nama'  => $item->jabatan,
                        'jenis' => 0,
                    ];
                })
                    ->values()
                    ->toArray();

                if ($simpan) {
                    RefJabatan::insert($simpan);
                }
            }

            $hasil = $hasil && $this->timestamps('ref_jabatan', true);

            // Hapus field pamong_id
            if ($this->db->field_exists('pamong_id', 'config')) {
                $hasil = $hasil && $this->dbforge->drop_column('config', 'pamong_id');
            }

            // Hapus field nip_kepala_desa
            if ($this->db->field_exists('nip_kepala_desa', 'config')) {
                $hasil = $hasil && $this->dbforge->drop_column('config', 'nip_kepala_desa');
            }

            $hasil = $hasil && $this->timestamps('config', true);

            // Hapus field jabatan
            if ($this->db->field_exists('jabatan', 'tweb_desa_pamong')) {
                $hasil = $hasil && $this->dbforge->drop_column('tweb_desa_pamong', 'jabatan');
            }

            $hasil = $hasil && $this->tentukan_kades_sekdes($hasil);
        }

        return $hasil;
    }

    protected function tentukan_kades_sekdes($hasil)
    {
        // Jalankan hanya jika terdeksi cara lama (kades = a.n)
        if (Pamong::where('pamong_ttd', 1)->exists()) {
            // Sesuaikan Penanda tangan kepala desa
            $hasil = $hasil && Pamong::where('pamong_ttd', 1)->update(['jabatan_id' => 1, 'pamong_ttd' => 0, 'pamong_ub' => 0]);
        }

        // Jalankan hanya jika terdeksi cara lama (sekdes = u.b)
        if (Pamong::where('pamong_ub', 1)->exists()) {
            // Sesuaikan Penanda tangan sekdes (a.n)
            $hasil = $hasil && Pamong::where('pamong_ub', 1)->update(['jabatan_id' => 2, 'pamong_ttd' => 1, 'pamong_ub' => 0]);
        }

        // Bagian ini di lewati, default tidak ada terpilih
        // Untuk penanda tangan u.b perlu disesuaikan ulang agar menyesuaikan

        return $hasil;
    }

    protected function migrasi_2022080571($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(32, ['urut' => 4]);

        return $hasil && $this->ubah_modul(98, ['url' => 'permohonan_surat_admin', 'urut' => 3, 'parent' => 4]);
    }

    protected function migrasi_2022080451($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'notifikasi_koneksi',
            'value'      => 1,
            'keterangan' => 'Ingatkan jika aplikasi tidak terhubung dengan internet.',
            'jenis'      => 'boolean',
        ]);
    }

    protected function migrasi_2022080971($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'tampil_luas_peta',
            'value'      => 0,
            'keterangan' => 'Tampilkan Luas Wilayah Pada Peta',
            'jenis'      => 'boolean',
        ]);
    }

    protected function migrasi_2022081171($hasil)
    {
        return $hasil && KB::updateOrCreate([
            'id' => 100,
        ], [
            'id'   => 100,
            'nama' => 'Tidak Menggunakan',
            'sex'  => 3,
        ]);
    }

    protected function migrasi_2022081571($hasil)
    {
        if (! $this->db->table_exists('log_tolak')) {
            $log_tolak = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'id_surat' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                ],
                'keterangan' => [
                    'type' => 'LONGTEXT',
                    'null' => false,
                ],
            ];
            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($log_tolak)
                ->create_table('log_tolak', true);

            $hasil = $hasil && $this->timestamps('log_tolak', true);
        }

        if (! $this->db->field_exists('alasan', 'permohonan_surat')) {
            $fields = [
                'alasan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('permohonan_surat', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022081271($hasil)
    {
        if (! $this->db->field_exists('berat_badan', 'bulanan_anak')) {
            $fields = [
                'berat_badan' => [
                    'type'  => 'FLOAT',
                    'null'  => true,
                    'after' => 'pengukuran_berat_badan',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('bulanan_anak', $fields);
        }

        if (! $this->db->field_exists('tinggi_badan', 'bulanan_anak')) {
            $fields = [
                'tinggi_badan' => [
                    'type'       => 'INT',
                    'constraint' => 4,
                    'null'       => true,
                    'after'      => 'pengukuran_tinggi_badan',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('bulanan_anak', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022081951($hasil)
    {
        // Semua penduduk yang memiliki foto
        $daftar_penduduk = DB::table('tweb_penduduk')->where('foto', '!=', '')->get(['id', 'nik', 'foto']);

        if ($daftar_penduduk) {
            foreach ($daftar_penduduk as $data) {
                // Ganti nama file jika nama file sama dengan nik penduduk
                if (preg_match("/{$data->nik}/i", $data->foto) && (file_exists(FCPATH . LOKASI_USER_PICT . $data->foto) || file_exists(FCPATH . LOKASI_USER_PICT . 'kecil_' . $data->foto))) {
                    $nama_baru = time() . '-' . $data->id . '-' . mt_rand(10000, 999999) . get_extension($data->foto);

                    if (DB::table('tweb_penduduk')->where('id', $data->id)->update(['foto' => $nama_baru])) {
                        rename(FCPATH . LOKASI_USER_PICT . $data->foto, FCPATH . LOKASI_USER_PICT . $nama_baru);
                        rename(FCPATH . LOKASI_USER_PICT . 'kecil_' . $data->foto, FCPATH . LOKASI_USER_PICT . 'kecil_' . $nama_baru);
                    }
                }
            }
        }

        // Semua aparatur penduduk luar desa
        $daftar_pamong = DB::table('tweb_desa_pamong')->where('foto', '!=', '')->get(['pamong_id', 'pamong_nik', 'foto']);

        if ($daftar_pamong) {
            foreach ($daftar_pamong as $data) {
                // Ganti nama file jika nama file sama dengan nik penduduk
                if (null === $data->id_pend && preg_match("/{$data->pamong_nik}/i", $data->foto) && (file_exists(FCPATH . LOKASI_USER_PICT . $data->foto) || file_exists(FCPATH . LOKASI_USER_PICT . 'kecil_' . $data->foto))) {
                    $nama_baru = 'pamong_' . time() . '-' . $data->pamong_id . '-' . mt_rand(10000, 999999) . get_extension($data->foto);

                    if (DB::table('tweb_desa_pamong')->where('pamong_id', $data->pamong_id)->update(['foto' => $nama_baru])) {
                        rename(FCPATH . LOKASI_USER_PICT . $data->foto, FCPATH . LOKASI_USER_PICT . $nama_baru);
                        rename(FCPATH . LOKASI_USER_PICT . 'kecil_' . $data->foto, FCPATH . LOKASI_USER_PICT . 'kecil_' . $nama_baru);
                    }
                }
            }
        }

        return $hasil;
    }

    protected function migrasi_2022082071($hasil)
    {
        if ($this->db->field_exists('kk_sex', 'log_keluarga')) {
            $hasil && $this->dbforge->drop_column('log_keluarga', 'kk_sex');
        }

        return $hasil;
    }

    protected function migrasi_2022082171($hasil)
    {
        if (! $this->db->field_exists('pid_process', 'log_backup')) {
            $fields = [
                'pid_process' => [
                    'type'       => 'int',
                    'constraint' => 11,
                    'null'       => false,
                    'after'      => 'status',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('log_backup', $fields);
        }

        return $hasil;
    }

    public function migrasi_2022082271($hasil)
    {
        $hasil = $hasil && $this->tambah_setting([
            'key'        => 'footer_surat_tte',
            'value'      => TinyMCE::FOOTER_TTE,
            'keterangan' => 'Footer Surat TTE',
            'kategori'   => 'format_surat',
        ]);

        if (! $this->db->table_exists('log_tte')) {
            $fields = [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                    'unsigned'       => true,
                ],
                'message' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
                'jenis_error' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
            ];

            $hasil = $hasil && $this->dbforge
                ->add_key('id', true)
                ->add_field($fields)
                ->create_table('log_tte', true);

            $hasil = $hasil && $this->timestamps('log_tte', true);
        }

        // Pengaturan TTE Bsre
        $hasil && $this->tambah_setting([
            'key'        => 'tte',
            'value'      => '0',
            'keterangan' => 'TTE - Aktifkan Modul TTE',
            'kategori'   => 'tte',
            'jenis'      => 'boolean',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'tte_api',
            'value'      => '',
            'keterangan' => 'TTE - URL API TTE',
            'kategori'   => 'tte',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'tte_username',
            'value'      => '',
            'keterangan' => 'TTE - Username untuk TTE',
            'kategori'   => 'tte',
        ]);

        $hasil && $this->tambah_setting([
            'key'        => 'tte_password',
            'value'      => '',
            'keterangan' => 'TTE - Password untuk TTE',
            'kategori'   => 'tte',
        ]);

        return $hasil;
    }

    protected function migrasi_2022082371($hasil)
    {
        if (! $this->db->field_exists('form_isian', 'tweb_surat_format')) {
            $fields['form_isian'] = [
                'type'  => 'LONGTEXT',
                'null'  => true,
                'after' => 'template_desa',
            ];

            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', $fields);

            // Sesuaikan data awal surat tinymce
            FormatSurat::jenis(FormatSurat::TINYMCE)->update(['form_isian' => '{"individu":{"sex":"","status_dasar":""}}']);
        }

        return $hasil;
    }

    public function migrasi_2022082571($hasil)
    {
        if (! $this->db->field_exists('header', 'tweb_surat_format')) {
            $fields = [
                'header' => [
                    'type'       => 'int',
                    'constraint' => 1,
                    'null'       => false,
                    'after'      => 'margin',
                    'default'    => 1,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', $fields);
        }

        if (! $this->db->field_exists('footer', 'tweb_surat_format')) {
            $fields = [
                'footer' => [
                    'type'       => 'int',
                    'constraint' => 1,
                    'null'       => false,
                    'after'      => 'margin',
                    'default'    => 1,
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022083071($hasil)
    {
        if (! $this->db->field_exists('telegram_verified_at', 'user')) {
            $fields = [
                'telegram_verified_at' => [
                    'type'  => 'datetime',
                    'null'  => true,
                    'after' => 'id_telegram',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        if (! $this->db->field_exists('token', 'user')) {
            $fields = [
                'token' => [
                    'type'       => 'varchar',
                    'null'       => true,
                    'constraint' => 100,
                    'after'      => 'id_telegram',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        if (! $this->db->field_exists('token_exp', 'user')) {
            $fields = [
                'token_exp' => [
                    'type'  => 'datetime',
                    'null'  => true,
                    'after' => 'token',
                ],
            ];
            $hasil = $hasil && $this->dbforge->add_column('user', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022083171($hasil)
    {
        // tambahkan digit id telegram
        if ($this->db->field_exists('id_telegram', 'user')) {
            $fields = [
                'id_telegram' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
            ];

            $hasil = $hasil && $this->dbforge->modify_column('user', $fields);
        }

        return $hasil;
    }

    protected function migrasi_2022090171($hasil)
    {
        return $hasil && $this->tambah_setting([
            'key'        => 'kode_desa_bps',
            'value'      => null,
            'keterangan' => 'Kode Desa BPS (Dapat di cek di <a href="https://sig.bps.go.id/bridging-kode" target="_blank">https://sig.bps.go.id/bridging-kode</a>)',
            'kategori'   => 'status sdgs',
        ]);
    }
}
