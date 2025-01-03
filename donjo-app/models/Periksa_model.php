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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Models\GrupAkses;
use App\Models\Keluarga;
use App\Models\KlasifikasiSurat;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use App\Models\SuplemenTerdata;
use App\Models\User;
use App\Traits\Collation;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Periksa_model extends MY_Model
{
    use Collation;

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
        if (($jabatan = $this->deteksi_jabatan()) !== []) {
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

        $log_penduduk_asing = $this->deteksi_log_penduduk_asing();
        if (! $log_penduduk_asing->isEmpty()) {
            $this->periksa['masalah'][]          = 'log_penduduk_asing';
            $this->periksa['log_penduduk_asing'] = $log_penduduk_asing->toArray();
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

        $kepala_keluarga_ganda = $this->deteksi_kepala_keluarga_ganda();
        if (! $kepala_keluarga_ganda->isEmpty()) {
            $this->periksa['masalah'][]             = 'kepala_keluarga_ganda';
            $this->periksa['kepala_keluarga_ganda'] = $kepala_keluarga_ganda->toArray();
        }
        // satu nik_kepala berada di lebih dari satu keluarga
        $keluarga_kepala_ganda = $this->deteksi_keluarga_kepala_ganda();
        if (! $keluarga_kepala_ganda->isEmpty()) {
            $this->periksa['masalah'][]             = 'keluarga_kepala_ganda';
            $this->periksa['keluarga_kepala_ganda'] = $keluarga_kepala_ganda->toArray();
        }

        // nik_kepala pada tweb_keluarga bukan kk_level = 1
        $nik_kepala_bukan_kepala_keluarga = $this->deteksi_nik_kepala_bukan_kepala_keluarga();
        if (! $nik_kepala_bukan_kepala_keluarga->isEmpty()) {
            $this->periksa['masalah'][]                        = 'nik_kepala_bukan_kepala_keluarga';
            $this->periksa['nik_kepala_bukan_kepala_keluarga'] = $nik_kepala_bukan_kepala_keluarga->toArray();
        }

        // keluarga tanpa nik_kepala
        $keluarga_tanpa_nik_kepala = $this->deteksi_keluarga_tanpa_nik_kepala();
        if (! $keluarga_tanpa_nik_kepala->isEmpty()) {
            $this->periksa['masalah'][]                 = 'keluarga_tanpa_nik_kepala';
            $this->periksa['keluarga_tanpa_nik_kepala'] = $keluarga_tanpa_nik_kepala->toArray();
        }

        $klasifikasi_surat_ganda = $this->deteksi_klasifikasi_surat_ganda();
        if (! $klasifikasi_surat_ganda->isEmpty()) {
            $this->periksa['masalah'][]               = 'klasifikasi_surat_ganda';
            $this->periksa['klasifikasi_surat_ganda'] = $klasifikasi_surat_ganda->toArray();
        }

        $tgllahir_null_kosong = $this->deteksi_tgllahir_null_kosong();
        if (! $tgllahir_null_kosong->isEmpty()) {
            $this->periksa['masalah'][]            = 'tgllahir_null_kosong';
            $this->periksa['tgllahir_null_kosong'] = $tgllahir_null_kosong->toArray();
        }

        $suplemen_terdata_kosong = $this->deteksi_suplemen_terdata_kosong();
        if (! $suplemen_terdata_kosong->isEmpty()) {
            $this->periksa['masalah'][]               = 'suplemen_terdata_kosong';
            $this->periksa['suplemen_terdata_kosong'] = $suplemen_terdata_kosong->groupBy('id_suplemen')->toArray();
        }

        $modul_asing = $this->deteksi_modul_asing_grup_akses();
        if (! $modul_asing->isEmpty()) {
            $this->periksa['masalah'][]   = 'modul_asing';
            $this->periksa['modul_asing'] = $modul_asing->toArray();
        }

        return $calon;
    }

    private function deteksi_collation_table_tidak_sesuai()
    {
        return $this->db
            ->query("SELECT TABLE_NAME, TABLE_COLLATION FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$this->db->database}' AND TABLE_COLLATION != '{$this->db->dbcollat}'")
            ->result_array();
    }

    private function deteksi_jabatan(): array
    {
        $jabatan = [];

        $user = ci_auth()->id ?? User::first()->id;

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

    public function deteksi_log_penduduk_asing()
    {
        identitas('id');

        return LogPenduduk::select('log_penduduk.id', 'nama', 'nik', 'kode_peristiwa', 'log_penduduk.created_at')
            ->whereNotIn('kode_peristiwa', array_keys(LogPenduduk::kodePeristiwa()))
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

    public function deteksi_kepala_keluarga_ganda()
    {
        $config_id = identitas('id');

        $kepalaKeluargaDobel = Penduduk::withOnly([])->select(['id_kk'])->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->groupBy(['id_kk'])->having(DB::raw('count(id_kk)'), '>', 1)->pluck('id_kk')->toArray();

        return Penduduk::withOnly(['keluarga' => static fn ($q) => $q->withOnly([])])
            ->kepalaKeluarga()
            ->whereIn('id_kk', $kepalaKeluargaDobel)
            ->whereNotIn('id', static fn ($q) => $q->from('tweb_keluarga')->select(['nik_kepala'])->where(['config_id' => $config_id])->whereNotNull('nik_kepala'))
            ->orderBy('id_kk')
            ->get();
    }

    private function deteksi_keluarga_kepala_ganda()
    {
        $kepalaKeluargaDobel = Keluarga::groupBy(['nik_kepala'])->having(DB::raw('count(nik_kepala)'), '>', 1)->pluck('nik_kepala')->toArray();

        return Keluarga::whereIn('nik_kepala', $kepalaKeluargaDobel)
            ->with(['kepalaKeluarga'])
            ->orderBy('id')
            ->get();
    }

    private function deteksi_nik_kepala_bukan_kepala_keluarga()
    {
        return Penduduk::withOnly(['keluarga'])->whereIn('id', static fn ($q) => $q->select(['nik_kepala'])->from('tweb_keluarga'))->where('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA)->get();
    }

    private function deteksi_keluarga_tanpa_nik_kepala()
    {
        $configId = identitas('id');

        return Keluarga::selectRaw('tweb_keluarga.*, log_keluarga.id_peristiwa')->logTerakhir($configId, date('Y-m-d'))->with(['wilayah'])->whereNull('nik_kepala')->get();
    }

    private function deteksi_klasifikasi_surat_ganda()
    {
        $config_id = identitas('id');

        return KlasifikasiSurat::where(['config_id' => $config_id])->whereIn('kode', static fn ($q) => $q->from('klasifikasi_surat')->select(['kode'])->where(['config_id' => $config_id])->groupBy('kode')->having(DB::raw('count(kode)'), '>', 1))->orderBy('kode')->get();
    }

    private function deteksi_tgllahir_null_kosong()
    {
        $config_id = identitas('id');

        return Penduduk::where('config_id', $config_id)->where('tanggallahir', '0000-00-00')->orWhereNull('tanggallahir')->get();
    }

    private function deteksi_suplemen_terdata_kosong()
    {
        $suplemenKeluarga = SuplemenTerdata::withOnly(['suplemen'])->sasaranKeluarga()->whereDoesntHave('keluarga');

        return SuplemenTerdata::withOnly(['suplemen'])->sasaranPenduduk()->whereDoesntHave('penduduk')->union($suplemenKeluarga)->get();
    }

    private function deteksi_modul_asing_grup_akses()
    {
        return GrupAkses::with(['grup'])->whereDoesntHave('modul')->get();
    }

    public function perbaiki(): void
    {
        // TODO: login
        $this->session->user_id = $this->session->user_id ?: 1;

        // Perbaiki masalah data yg terdeteksi untuk error yg dilaporkan
        log_message('notice', '========= Perbaiki masalah data =========');

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

        // clear cache
        cache()->flush();
        $this->load->model('database_model');
        $this->database_model->migrasi_db_cri();
    }

    public function perbaiki_sebagian($masalah_ini): void
    {
        // TODO: login
        $this->session->user_id = $this->session->user_id ?: 1;

        $this->selesaikan_masalah($masalah_ini);

        $this->session->db_error = null;
        // clear cache
        cache()->flush();
    }

    private function perbaiki_autoincrement(): bool
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

    private function perbaiki_collation_table(): bool
    {
        $hasil  = true;
        $tables = $this->periksa['collation_table'];

        if ($tables) {
            $this->updateCollation($this->db->database, $this->db->dbcollat);
        }

        return $hasil;
    }

    private function perbaiki_jabatan(): bool
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

    private function perbaiki_log_penduduk_asing(): void
    {
        LogPenduduk::whereIn('id', array_column($this->periksa['log_penduduk_asing'], 'id'))->delete();
    }

    private function perbaiki_log_keluarga_bermasalah(): void
    {
        $configId = identitas('id');
        $userId   = ci_auth()->id;
        $sql      = "
            INSERT INTO log_keluarga (config_id, id_kk, id_peristiwa, tgl_peristiwa, updated_by)
            SELECT
                {$configId} AS config_id,
                id AS id_kk,
                1 AS id_peristiwa,
                tgl_daftar AS tgl_peristiwa,
                {$userId} AS updated_by
            FROM
                tweb_keluarga
            WHERE
                config_id = {$configId} AND
                id NOT IN (
                    SELECT id_kk FROM log_keluarga WHERE config_id = {$configId} AND id_kk IS NOT NULL AND id_peristiwa = 1
                )
        ";

        DB::statement($sql);
        DB::table('log_keluarga')->where('config_id', $configId)->whereNull('id_kk')->delete();
    }

    private function perbaiki_modul_asing_grup_akses()
    {
        GrupAkses::whereDoesntHave('modul')->delete();
    }

    private function perbaiki_keluarga_kepala_ganda(): void
    {
        $keluarga = $this->periksa['keluarga_kepala_ganda'];
        if ($keluarga) {
            foreach ($keluarga as $k) {
                if ($k['id'] != $k['kepala_keluarga']['id_kk']) {
                    Keluarga::where('id', $k['id'])->update(['nik_kepala' => null]);
                }
            }
        }
    }

    private function perbaiki_nik_kepala_bukan_kepala_keluarga(): void
    {
        $penduduk = $this->periksa['nik_kepala_bukan_kepala_keluarga'];
        if ($penduduk) {
            Penduduk::whereIn('id', array_column($penduduk, 'id'))->update(['kk_level' => SHDKEnum::KEPALA_KELUARGA]);
        }
    }

    private function perbaiki_keluarga_tanpa_nik_kepala(): void
    {
        $keluarga = $this->periksa['keluarga_tanpa_nik_kepala'];
        if ($keluarga) {
            Keluarga::whereIn('id', array_column($keluarga, 'id'))->delete();
        }
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

            case 'log_penduduk_asing':
                $this->perbaiki_log_penduduk_asing();
                break;

            case 'log_keluarga_bermasalah':
                $this->perbaiki_log_keluarga_bermasalah();
                break;

            case 'keluarga_kepala_ganda':
                $this->perbaiki_keluarga_kepala_ganda();
                break;

            case 'nik_kepala_bukan_kepala_keluarga':
                $this->perbaiki_nik_kepala_bukan_kepala_keluarga();
                break;

            case 'keluarga_tanpa_nik_kepala':
                $this->perbaiki_keluarga_tanpa_nik_kepala();
                break;

            case 'modul_asing':
                $this->perbaiki_modul_asing_grup_akses();
                break;

            default:
                break;
        }
    }
}
