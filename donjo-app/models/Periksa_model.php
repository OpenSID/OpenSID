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

use App\Enums\StatusDasarEnum;
use App\Models\Keluarga;
use App\Models\KlasifikasiSurat;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Periksa_model extends MY_Model
{
    public $periksa = [];

    public function __construct()
    {
        parent::__construct();
        $this->periksa['migrasi_utk_diulang'] = $this->deteksi_masalah();
    }

    public function getSetting($key)
    {
        return SettingAplikasi::where('key', $key)->pluck('value')->first();
    }

    private function deteksi_masalah()
    {
        $db_error_code    = $this->session->db_error['code'];
        $db_error_message = $this->session->db_error['message'];
        $current_version  = $this->getSetting('current_version');
        $calon            = $current_version;

        // Deteksi jabatan kades atau sekdes tidak ada
        if (! empty($jabatan = $this->deteksi_jabatan())) {
            $this->periksa['masalah'][]    = 'data_jabatan_tidak_ada';
            $this->periksa['data_jabatan'] = $jabatan;
        }

        // Autoincrement hilang, mungkin karena proses backup/restore yang tidak sempurna
        // Untuk masalah yg tidak melalui exception, letakkan sesuai urut migrasi
        if ($db_error_code == 1364) {
            $pos = strpos($db_error_message, "Field 'id' doesn't have a default value");
            if ($pos !== false) {
                $this->periksa['masalah'][] = 'autoincrement';
            }
        }

        // Error collation table
        $collation_table = $this->deteksi_collation_table_tidak_sesuai();
        $error_msg       = strpos($this->session->message_query, 'Illegal mix of collations');
        if (! empty($collation_table) || $error_msg) {
            $this->periksa['masalah'][]       = 'collation';
            $this->periksa['collation_table'] = $collation_table;
        }

        // Error penduduk tanpa ada keluarga di tweb_keluarga
        $penduduk_tanpa_keluarga = $this->deteksi_penduduk_tanpa_keluarga();

        if (! $penduduk_tanpa_keluarga->isEmpty()) {
            $this->periksa['masalah'][]               = 'penduduk_tanpa_keluarga';
            $this->periksa['penduduk_tanpa_keluarga'] = $penduduk_tanpa_keluarga->toArray();
        }

        $log_penduduk_tidak_sinkron = $this->deteksi_log_penduduk_tidak_sinkron();
        if (! $log_penduduk_tidak_sinkron->isEmpty()) {
            $this->periksa['masalah'][]                  = 'log_penduduk_tidak_sinkron';
            $this->periksa['log_penduduk_tidak_sinkron'] = $log_penduduk_tidak_sinkron->toArray();
        }

        $log_penduduk_null = $this->deteksi_log_penduduk_null();
        if (! $log_penduduk_null->isEmpty()) {
            $this->periksa['masalah'][]         = 'log_penduduk_null';
            $this->periksa['log_penduduk_null'] = $log_penduduk_null->toArray();
        }

        $log_keluarga_bermasalah = $this->deteksi_log_keluarga_bermasalah();
        if (! $log_keluarga_bermasalah->isEmpty()) {
            $this->periksa['masalah'][]               = 'log_keluarga_bermasalah';
            $this->periksa['log_keluarga_bermasalah'] = $log_keluarga_bermasalah->toArray();
        }

        $log_keluarga_ganda = $this->deteksi_log_keluarga_ganda();
        if (! $log_keluarga_ganda->isEmpty()) {
            $this->periksa['masalah'][]          = 'log_keluarga_ganda';
            $this->periksa['log_keluarga_ganda'] = $log_keluarga_ganda->toArray();
        }

        $klasifikasi_surat_ganda = $this->deteksi_klasifikasi_surat_ganda();
        if (! $klasifikasi_surat_ganda->isEmpty()) {
            $this->periksa['masalah'][]               = 'klasifikasi_surat_ganda';
            $this->periksa['klasifikasi_surat_ganda'] = $klasifikasi_surat_ganda->toArray();
        }

        return $calon;
    }

    private function deteksi_collation_table_tidak_sesuai()
    {
        return $this->db
            ->query("SELECT TABLE_NAME, TABLE_COLLATION FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$this->db->database}' AND TABLE_COLLATION != '{$this->db->dbcollat}'")
            ->result_array();
    }

    private function deteksi_jabatan()
    {
        $jabatan = [];

        $user = auth()->id ?? User::first()->id;

        // Cek jabatan kades
        if (! kades()) {
            $jabatan[] = [
                'config_id'  => identitas('id'),
                'nama'       => 'Kepala ' . ucwords($this->getSetting('sebutan_desa')),
                'jenis'      => RefJabatan::KADES,
                'created_by' => $user,
                'updated_by' => $user,
            ];
        }

        // Cek jabatan sekdes
        if (! sekdes()) {
            $jabatan[] = [
                'config_id'  => identitas('id'),
                'nama'       => 'Sekretaris',
                'jenis'      => RefJabatan::SEKDES,
                'created_by' => $user,
                'updated_by' => $user,
            ];
        }

        return $jabatan;
    }

    public function deteksi_penduduk_tanpa_keluarga()
    {
        $config_id = identitas('id');

        return Penduduk::select('id', 'nama', 'nik', 'id_cluster', 'id_kk', 'alamat_sekarang', 'created_at')
            ->kepalaKeluarga()
            ->whereNotNull('id_kk')
            ->wheredoesntHave('keluarga', static fn ($q) => $q->where('config_id', $config_id))
            ->get();
    }

    // status dasar penduduk seharusnya mengikuti status terakhir dari log_penduduk
    public function deteksi_log_penduduk_tidak_sinkron()
    {
        $config_id = identitas('id');

        $sqlRaw                = "( SELECT MAX(id) max_id, id_pend FROM log_penduduk where config_id = {$config_id} GROUP BY  id_pend)";
        $statusDasarBukanHidup = Penduduk::select('tweb_penduduk.id', 'nama', 'nik', 'status_dasar', 'alamat_sekarang', 'kode_peristiwa', 'tweb_penduduk.created_at')
            ->where('status_dasar', '=', StatusDasarEnum::HIDUP)
            ->join(DB::raw("({$sqlRaw}) as log"), 'log.id_pend', '=', 'tweb_penduduk.id')
            ->join('log_penduduk', static function ($q) use ($config_id): void {
                $q->on('log_penduduk.id', '=', 'log.max_id')
                    ->where('log_penduduk.config_id', $config_id)
                    ->whereIn('kode_peristiwa', [LogPenduduk::MATI, LogPenduduk::PINDAH_KELUAR, LogPenduduk::HILANG, LogPenduduk::TIDAK_TETAP_PERGI]);
            });

        return Penduduk::select('tweb_penduduk.id', 'nama', 'nik', 'status_dasar', 'alamat_sekarang', 'kode_peristiwa', 'tweb_penduduk.created_at')
            ->where('status_dasar', '!=', StatusDasarEnum::HIDUP)
            ->join(DB::raw("({$sqlRaw}) as log"), 'log.id_pend', '=', 'tweb_penduduk.id')
            ->join('log_penduduk', static function ($q) use ($config_id): void {
                $q->on('log_penduduk.id', '=', 'log.max_id')
                    ->where('log_penduduk.config_id', $config_id)
                    ->whereNotIn('kode_peristiwa', [LogPenduduk::MATI, LogPenduduk::PINDAH_KELUAR, LogPenduduk::HILANG, LogPenduduk::TIDAK_TETAP_PERGI]);
            })->union(
                $statusDasarBukanHidup
            )
            ->get();
    }

    public function deteksi_log_penduduk_null()
    {
        identitas('id');

        return LogPenduduk::select('log_penduduk.id', 'nama', 'nik', 'kode_peristiwa', 'log_penduduk.created_at')
            ->whereNull('kode_peristiwa')
            ->join('tweb_penduduk', 'tweb_penduduk.id', '=', 'log_penduduk.id_pend')
            ->get();
    }

    public function deteksi_log_keluarga_bermasalah()
    {
        return Keluarga::whereDoesntHave('LogKeluarga')->get();
    }

    public function deteksi_log_keluarga_ganda()
    {
        $config_id = identitas('id');

        return Keluarga::whereIn('id', static fn ($query) => $query->from('log_keluarga')->where(['config_id' => $config_id])->select(['id_kk'])->groupBy(['id_kk', 'tgl_peristiwa'])->having(DB::raw('count(tgl_peristiwa)'), '>', 1))->get();
    }

    private function deteksi_klasifikasi_surat_ganda()
    {
        $config_id = identitas('id');

        return KlasifikasiSurat::where(['config_id' => $config_id])->whereIn('kode', static fn ($q) => $q->from('klasifikasi_surat')->select(['kode'])->where(['config_id' => $config_id])->groupBy('kode')->having(DB::raw('count(kode)'), '>', 1))->orderBy('kode')->get();
    }

    public function perbaiki(): void
    {
        // TODO: login
        $this->session->user_id = $this->session->user_id ?: 1;

        // Perbaiki masalah data yg terdeteksi untuk error yg dilaporkan
        log_message('error', '========= Perbaiki masalah data =========');

        foreach ($this->periksa['masalah'] as $masalah_ini) {
            $this->selesaikan_masalah($masalah_ini);
        }
        $this->session->db_error = null;

        $this->db
            ->where('versi_database', VERSI_DATABASE)
            ->delete('migrasi');
        $this->db
            ->set('value', $this->periksa['migrasi_utk_diulang'])
            ->where('key', 'current_version')
            ->update('setting_aplikasi');
        cache()->forget('setting_aplikasi');
        $this->load->model('database_model');
        $this->database_model->migrasi_db_cri();
    }

    public function perbaiki_sebagian($masalah_ini): void
    {
        // TODO: login
        $this->session->user_id = $this->session->user_id ?: 1;

        $this->selesaikan_masalah($masalah_ini);

        $this->session->db_error = null;
    }

    private function perbaiki_autoincrement()
    {
        $hasil = true;

        // Tabel yang tidak memerlukan Auto_Increment
        $exclude_table = [
            'analisis_respon',
            'analisis_respon_hasil',
            'password_resets',
            'sentitems', // Belum tau bentuk datanya bagamana
            'sys_traffic',
            'tweb_penduduk_mandiri',
            'tweb_penduduk_map', // id pada tabel tweb_penduduk_map == penduduk.id (buka id untuk AI)
        ];

        // Auto_Increment hanya diterapkan pada kolom berikut
        $only_pk = [
            'id',
            'id_kontak',
            'id_aset',
        ];

        // Daftar tabel yang tidak memiliki Auto_Increment
        $tables = $this->db->query("SELECT `TABLE_NAME` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$this->db->database}' AND AUTO_INCREMENT IS NULL");

        foreach ($tables->result() as $tbl) {
            $name = $tbl->TABLE_NAME;
            if (! in_array($name, $exclude_table) && in_array($key = $this->db->list_fields($name)[0], $only_pk)) {
                $fields = [
                    $key => [
                        'type'           => 'INT',
                        'constraint'     => 11,
                        'auto_increment' => true,
                    ],
                ];

                $this->db->simple_query('SET FOREIGN_KEY_CHECKS=0');
                if ($hasil = $hasil && $this->dbforge->modify_column($name, $fields)) {
                    log_message('error', "Auto_Increment pada tabel {$name} dengan kolom {$key} telah ditambahkan.");
                }
                $this->db->simple_query('SET FOREIGN_KEY_CHECKS=1');
            }
        }

        return $hasil;
    }

    private function perbaiki_collation_table()
    {
        $hasil  = true;
        $tables = $this->periksa['collation_table'];

        if ($tables) {
            foreach ($tables as $tbl) {
                if ($this->db->table_exists($tbl['TABLE_NAME'])) {
                    $hasil = $hasil && $this->db->query("ALTER TABLE {$tbl['TABLE_NAME']} CONVERT TO CHARACTER SET utf8 COLLATE {$this->db->dbcollat}");

                    log_message('error', 'Tabel ' . $tbl['TABLE_NAME'] . ' collation diubah dari ' . $tbl['TABLE_COLLATION'] . " menjadi {$this->db->dbcollat}.");
                }
            }
        }

        return $hasil;
    }

    private function perbaiki_jabatan()
    {
        if ($jabatan = $this->periksa['data_jabatan']) {
            RefJabatan::insert($jabatan);
        }

        return true;
    }

    private function perbaiki_penduduk_tanpa_keluarga(): void
    {
        $config_id     = identitas('id');
        $kode_desa     = identitas('kode_desa');
        $data_penduduk = Penduduk::select('id', 'id_cluster', 'id_kk', 'alamat_sekarang', 'created_at')
            ->kepalaKeluarga()
            ->whereNotNull('id_kk')
            ->wheredoesntHave('keluarga', static fn ($q) => $q->where('config_id', $config_id))
            ->get();
        // nomer urut kk sementara
        $digit = Keluarga::nomerKKSementara();

        $id_sementara = [];

        foreach ($data_penduduk as $value) {
            if (isset($id_sementara[$value->id_kk])) {
                continue;
            }
            $nokk_sementara = '0' . $kode_desa . sprintf('%05d', (int) $digit + 1);
            $hasil          = Keluarga::create([
                'id'         => $value->id_kk,
                'config_id'  => $config_id,
                'no_kk'      => $nokk_sementara,
                'nik_kepala' => $value->id,
                'tgl_daftar' => $value->created_at,
                'id_cluster' => $value->id_cluster,
                'alamat'     => $value->alamat_sekarang,
                'updated_at' => $value->created_at,
                'updated_by' => 1,
            ]);

            $digit++;
            $id_sementara[$value->id_kk] = 1;
            if ($hasil) {
                log_message('notice', 'Berhasil. Penduduk ' . $value->id . ' sudah terdaftar di keluarga');
            } else {
                log_message('error', 'Gagal. Penduduk ' . $value->id . ' belum terdaftar di keluarga');
            }
        }
    }

    private function perbaiki_log_penduduk_null(): void
    {
        LogPenduduk::whereIn('id', array_column($this->periksa['log_penduduk_null'], 'id'))->update(['kode_peristiwa' => LogPenduduk::BARU_PINDAH_MASUK]);
    }

    private function perbaiki_log_keluarga_bermasalah(): void
    {
        $configId = identitas('id');
        $userId   = auth()->id;
        $sql      = "insert into log_keluarga (config_id, id_kk, id_peristiwa, tgl_peristiwa, updated_by)
                select {$configId} as config_id, id as id_kk, 1 as id_peristiwa, tgl_daftar as tgl_peristiwa, {$userId} as updated_by
                from tweb_keluarga  where id not in ( select id_kk from log_keluarga where id_peristiwa = 1 ) ";
        DB::statement($sql);
    }

    private function selesaikan_masalah($masalah_ini): void
    {
        switch ($masalah_ini) {
            case 'autoincrement':
                $this->perbaiki_autoincrement();
                break;

            case 'collation':
                $this->perbaiki_collation_table();
                break;

            case 'data_jabatan_tidak_ada':
                $this->perbaiki_jabatan();
                break;

            case 'penduduk_tanpa_keluarga':
                $this->perbaiki_penduduk_tanpa_keluarga();
                break;

            case 'log_penduduk_null':
                $this->perbaiki_log_penduduk_null();
                break;

            case 'log_keluarga_bermasalah':
                $this->perbaiki_log_keluarga_bermasalah();
                break;

            default:
                break;
        }
    }
}
