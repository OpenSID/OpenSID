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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class MultiDB extends Admin_Controller
{
    public $aliasController = 'database';

    // Tabel dengan kondisi khusus, memiliki child dan parent
    public $tabelKhusus = [
        'gambar_gallery' => ['id', 'parrent'],
        'line'           => ['id', 'parrent'],
        'menu'           => ['id', 'parrent'],
        'pengaduan'      => ['id', 'id_pengaduan'],
        'point'          => ['id', 'parrent'],
        'polygon'        => ['id', 'parrent'],
        'setting_modul'  => ['id', 'parent'],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    // PROSES BACKUP DATA
    public function backup(): void
    {
        $tables     = DB::select('SHOW TABLES');
        $tableNames = collect($tables)->pluck('Tables_in_' . $this->db->database);
        $tableNames = $tableNames->filter(static function ($tableName): bool {
            $table          = DB::select("SHOW CREATE TABLE {$tableName}");
            $createTableSQL = collect($table)->pluck('Create Table')->first();

            return preg_match_all('/CONSTRAINT/', $createTableSQL) || $tableName == 'config';
        })->sort(static function ($a, $b): int {
            $a = DB::select("SHOW CREATE TABLE {$a}");
            $b = DB::select("SHOW CREATE TABLE {$b}");
            $a = collect($a)->pluck('Create Table')->first();
            $b = collect($b)->pluck('Create Table')->first();
            $a = preg_match_all('/CONSTRAINT/', $a);
            $b = preg_match_all('/CONSTRAINT/', $b);

            return $a <=> $b;
        });

        $kecuali = [
            'analisis_partisipasi', // tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT (dihapus tidak digunakan)
            'analisis_respon', // tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT (dihapus tidak digunakan)
            'analisis_respon_bukti', // tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT (dihapus tidak digunakan)
            'dtks_ref_lampiran', // tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT (dihapus tidak digunakan)
            'log_login', // karena menggunakan uuid
            'pemilihan', // karena menggunakan uuid
            'pesan_mandiri', // karena menggunakan uuid
            'tweb_penduduk_map', // tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT
            'tweb_penduduk_mandiri', // tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT

            'analisis_parameter', // skip dlu, terjadi error
        ];

        $tableNames = $tableNames->filter(static fn($tableName): bool => ! in_array($tableName, $kecuali));

        // $rand       = mt_rand(100000, 999999);
        // ambil dari 6 digit terakhir kode desa
        $kode_desa  = DB::table('config')->where('app_key', get_app_key())->value('kode_desa');
        $rand       = substr($kode_desa, -6);
        $backupData = [
            'info' => [
                'versi'    => VERSION,
                'premimum' => PREMIUM,
                'tanggal'  => date('Y-m-d H:i:s'),
                'random'   => $rand,
            ],
            'tabel' => [],
        ];
        DB::beginTransaction();

        foreach ($tableNames as $tableName) {
            $backupData['tabel'][$tableName] = $this->fetchTableData($tableName, $rand);
        }
        // kita rollback lagi agar idnya kembali seperti semula
        DB::rollBack();
        $backupFile = 'backup_' . date('YmdHis') . $rand . '.sid';
        $this->load->helper('download');
        force_download($backupFile, json_encode($backupData, JSON_PRETTY_PRINT));
    }

    // Fungsi untuk mengambil data dari tabel dengan mempertimbangkan relasi
    private function fetchTableData($tableName, string $rand)
    {
        $config_id   = DB::table('config')->where('app_key', get_app_key())->value('id');
        $primary_key = DB::select("SHOW KEYS FROM {$tableName} WHERE Key_name = 'PRIMARY'")[0]->Column_name;
        if (! empty($primary_key)) {
            if ($tableName == 'config') {
                $primary_key = 'id';
                DB::table($tableName)->where('id', $config_id)->update(['id' => DB::raw("`id` + {$rand}")]);

                // cek ulang, karena id config sudah berubah
                $config_id_new = DB::table('config')->where('app_key', get_app_key())->value('id');
                $tableData     = DB::table($tableName)->where('id', $config_id_new)->get();
            } else {
                DB::table($tableName)->where('config_id', $config_id)->update([$primary_key => DB::raw("`{$primary_key}` + {$rand}")]);
                if (in_array($tableName, array_keys($this->tabelKhusus))) {
                    $child = $this->tabelKhusus[$tableName][1];
                    DB::table($tableName)->where('config_id', $config_id)->where($child, '!=', 0)->update([$child => DB::raw("`{$child}` + {$rand}")]);
                }
                $tableData = DB::table($tableName)->where('config_id', $config_id)->get();
            }
        } else {
            $tableData = DB::table($tableName)->where('config_id', $config_id)->get();
        }

        // $table          = DB::select("SHOW CREATE TABLE {$tableName}");
        // $createTableSQL = collect($table)->pluck('Create Table')->first();
        $tableData = json_decode(json_encode($tableData), true);

        return [
            // 'struktur'    => $createTableSQL,
            'primary_key' => $primary_key,
            'data'        => $tableData,
        ];
    }
    // END PROSES BACKUP DATA

    // PROSES RESTORE DATA
    public function restore()
    {
        $this->load->library('MY_Upload', null, 'upload');
        $uploadConfig = [
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'sid', // File sql terdeteksi sebagai text/plain
            'file_ext'      => 'sid',
            'max_size'      => max_upload() * 1024,
            'ignore_mime'   => true,
            'cek_script'    => false,
        ];
        $this->upload->initialize($uploadConfig);
        // Upload sukses
        if (! $this->upload->do_upload('userfile')) {
            $this->session->success   = -1;
            $this->session->error_msg = $this->upload->display_errors(null, null);

            redirect_with('error', 'Proses upload gagal ' . $this->session->error_msg, ci_route('database'));
        }
        $uploadData = $this->upload->data();
        $backupFile = $uploadConfig['upload_path'] . '/' . $uploadData['file_name'];

        $backupData = file_get_contents($backupFile); // Ambil data dari file backup
        $backupData = json_decode($backupData, true); // Decode data JSON

        DB::beginTransaction();

        try {
            $rand    = $backupData['info']['random'];
            $version = $backupData['info']['versi'];

            if (substr($version, 0, 4) !== substr(VERSION, 0, 4)) {
                redirect_with('error', 'Proses restore dari backup gagal. <br>Versi opensid tidak sama');
            }
            // cek apakah data dari kolom ini sama dengan data yang ada di database
            // jika sama, maka lanjutkan
            $config_backup   = $backupData['tabel']['config']['data'][0];
            $config_database = DB::table('config')->where('app_key', get_app_key())->first();

            if ($config_backup['kode_desa'] != $config_database->kode_desa) {
                redirect_with('error', 'Proses restore dari backup gagal. <br>Data desa tidak sama dengan data yang ada di database.', ci_route('database'));

                return false;
            }

            DB::table('config')->where('app_key', get_app_key())->delete();
            DB::table('config')->insert($backupData['tabel']['config']['data']);
            write_file(DESAPATH . 'app_key', $backupData['tabel']['config']['data'][0]['app_key']);

            foreach ($backupData['tabel'] as $tableName => $tableDetails) {
                $this->restoreTableData($tableName, $tableDetails);
            }

            // susun ulang isi data yang memiliki primary key
            foreach ($backupData['tabel'] as $tableName => $tableDetails) {
                $this->reStrukturTableData($tableName, $tableDetails, $rand);
            }

            DB::commit();

            hapus_cache('_cache_modul');
            redirect_with('success', 'Proses restore dari backup berhasil.', ci_route('database'));
        } catch (\Exception $e) {
            DB::rollback();
            redirect_with('error', 'Proses restore dari backup gagal. <br><br>' . $e->getMessage(), ci_route('database'));
        }
    }

    private function restoreTableData($tableName, $tableDetails): void
    {
        if ($tableName != 'config') {
            foreach ($tableDetails['data'] as $record) {
                DB::table($tableName)->insert($record);
            }
        }
    }

    private function reStrukturTableData($tableName, $tableDetails, $rand): void
    {
        $primary_key = $tableDetails['primary_key'];
        $idIni       = DB::table('config')->where('app_key', get_app_key())->value('id');
        if ($primary_key !== null) {
            if ($tableName == 'config') {
                $id = DB::table($tableName)->where('id', '!=', $idIni)->orderBy('id', 'desc')->first()->id ?? 0;
                DB::table($tableName)->where('id', $idIni)->update(['id' => $id + 1]);
            } else {
                $id = DB::table($tableName)->where('config_id', '!=', $idIni)->orderBy($primary_key, 'desc')->first()->{$primary_key} ?? 0;
                $id -= $rand;

                if (in_array($tableName, array_keys($this->tabelKhusus))) {
                    $child = $this->tabelKhusus[$tableName][1];
                    DB::table($tableName)->where('config_id', $idIni)->where($child, '!=', 0)->update([$child => DB::raw("`{$child}` + {$id}")]);
                }

                DB::table($tableName)->where('config_id', $idIni)->update([$primary_key => DB::raw("`{$primary_key}` + {$id}")]);
            }
        }
    }
    // END PROSES RESTORE DATA
}
