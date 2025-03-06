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

namespace App\Libraries;

use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Models\GrupAkses;
use App\Models\Keluarga;
use App\Models\KlasifikasiSurat;
use App\Models\LogPenduduk;
use App\Models\Migrasi;
use App\Models\Penduduk;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use App\Models\SuplemenTerdata;
use App\Models\User;
use App\Traits\Collation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Periksa
{
    use Collation;

    private array $databaseOption;
    private $periksa = [];

    public function __construct()
    {
        $this->databaseOption                 = DB::getConnections()['default']->getConfig();
        $this->periksa['migrasi_utk_diulang'] = $this->deteksiMasalah();
    }

    public function getSetting($key)
    {
        return SettingAplikasi::where('key', $key)->value('value');
    }

    private function deteksiMasalah()
    {
        $dbErrorCode    = session('db_error.code');
        $dbErrorMessage = session('db_error.message');
        $currentVersion = $this->getSetting('current_version');
        $calon          = $currentVersion;

        // Deteksi jabatan kades atau sekdes tidak ada
        if (($jabatan = $this->deteksiJabatan()) !== []) {
            $this->periksa['masalah'][]    = 'data_jabatan_tidak_ada';
            $this->periksa['data_jabatan'] = $jabatan;
        }

        // Autoincrement hilang, mungkin karena proses backup/restore yang tidak sempurna
        // Untuk masalah yg tidak melalui exception, letakkan sesuai urut migrasi
        if ($dbErrorCode == 1364) {
            $pos = strpos($dbErrorMessage, "Field 'id' doesn't have a default value");
            if ($pos !== false) {
                $this->periksa['masalah'][] = 'autoincrement';
            }
        }

        // Error collation table
        $collationTable = $this->deteksiCollationTableTidakSesuai();
        if (! empty($collationTable) || strpos(session('message_query'), 'Illegal mix of collations') !== false) {
            $this->periksa['masalah'][]       = 'collation';
            $this->periksa['collation_table'] = $collationTable;
        }

        // Error penduduk tanpa ada keluarga di tweb_keluarga
        $pendudukTanpaKeluarga = $this->deteksiPendudukTanpaKeluarga();

        if (! $pendudukTanpaKeluarga->isEmpty()) {
            $this->periksa['masalah'][]               = 'penduduk_tanpa_keluarga';
            $this->periksa['penduduk_tanpa_keluarga'] = $pendudukTanpaKeluarga->toArray();
        }

        $logPendudukTidakSinkron = $this->deteksiLogPendudukTidakSinkron();
        if (! $logPendudukTidakSinkron->isEmpty()) {
            $this->periksa['masalah'][]                  = 'log_penduduk_tidak_sinkron';
            $this->periksa['log_penduduk_tidak_sinkron'] = $logPendudukTidakSinkron->toArray();
        }

        $logPendudukNull = $this->deteksiLogPendudukNull();
        if (! $logPendudukNull->isEmpty()) {
            $this->periksa['masalah'][]         = 'log_penduduk_null';
            $this->periksa['log_penduduk_null'] = $logPendudukNull->toArray();
        }

        $logPendudukAsing = $this->deteksiLogPendudukAsing();
        if (! $logPendudukAsing->isEmpty()) {
            $this->periksa['masalah'][]          = 'log_penduduk_asing';
            $this->periksa['log_penduduk_asing'] = $logPendudukAsing->toArray();
        }

        $logKeluargaBermasalah = $this->deteksiLogKeluargaBermasalah();
        if (! $logKeluargaBermasalah->isEmpty()) {
            $this->periksa['masalah'][]               = 'log_keluarga_bermasalah';
            $this->periksa['log_keluarga_bermasalah'] = $logKeluargaBermasalah->toArray();
        }

        $logKeluargaGanda = $this->deteksiLogKeluargaGanda();
        if (! $logKeluargaGanda->isEmpty()) {
            $this->periksa['masalah'][]          = 'log_keluarga_ganda';
            $this->periksa['log_keluarga_ganda'] = $logKeluargaGanda->toArray();
        }

        $kepalaKeluargaGanda = $this->deteksiKepalaKeluargaGanda();
        if (! $kepalaKeluargaGanda->isEmpty()) {
            $this->periksa['masalah'][]             = 'kepala_keluarga_ganda';
            $this->periksa['kepala_keluarga_ganda'] = $kepalaKeluargaGanda->toArray();
        }
        // satu nik_kepala berada di lebih dari satu keluarga
        $keluargaKepalaGanda = $this->deteksiKeluargaKepalaGanda();
        if (! $keluargaKepalaGanda->isEmpty()) {
            $this->periksa['masalah'][]             = 'keluarga_kepala_ganda';
            $this->periksa['keluarga_kepala_ganda'] = $keluargaKepalaGanda->toArray();
        }

        // nik_kepala pada tweb_keluarga bukan kk_level = 1
        $nikKepalaBukanKepalaKeluarga = $this->deteksiNikKepalaBukanKepalaKeluarga();
        if (! $nikKepalaBukanKepalaKeluarga->isEmpty()) {
            $this->periksa['masalah'][]                        = 'nik_kepala_bukan_kepala_keluarga';
            $this->periksa['nik_kepala_bukan_kepala_keluarga'] = $nikKepalaBukanKepalaKeluarga->toArray();
        }

        // keluarga tanpa nik_kepala
        $keluargaTanpaNikKepala = $this->deteksiKeluargaTanpaNikKepala();
        if (! $keluargaTanpaNikKepala->isEmpty()) {
            $this->periksa['masalah'][]                 = 'keluarga_tanpa_nik_kepala';
            $this->periksa['keluarga_tanpa_nik_kepala'] = $keluargaTanpaNikKepala->toArray();
        }

        $klasifikasiSuratGanda = $this->deteksiKlasifikasiSuratGanda();
        if (! $klasifikasiSuratGanda->isEmpty()) {
            $this->periksa['masalah'][]               = 'klasifikasi_surat_ganda';
            $this->periksa['klasifikasi_surat_ganda'] = $klasifikasiSuratGanda->toArray();
        }

        $tgllahirNullKosong = $this->deteksiTgllahirNullKosong();
        if (! $tgllahirNullKosong->isEmpty()) {
            $this->periksa['masalah'][]            = 'tgllahir_null_kosong';
            $this->periksa['tgllahir_null_kosong'] = $tgllahirNullKosong->toArray();
        }

        $suplemenTerdataKosong = $this->deteksiSuplemenTerdataKosong();
        if (! $suplemenTerdataKosong->isEmpty()) {
            $this->periksa['masalah'][]               = 'suplemen_terdata_kosong';
            $this->periksa['suplemen_terdata_kosong'] = $suplemenTerdataKosong->groupBy('id_suplemen')->toArray();
        }

        $modulAsing = $this->deteksiModulAsingGrupAkses();
        if (! $modulAsing->isEmpty()) {
            $this->periksa['masalah'][]   = 'modul_asing';
            $this->periksa['modul_asing'] = $modulAsing->toArray();
        }

        return $calon;
    }

    private function deteksiCollationTableTidakSesuai()
    {
        return DB::select("SELECT TABLE_NAME, TABLE_COLLATION FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$this->databaseOption['database']}' AND TABLE_COLLATION != '{$this->databaseOption['collation']}'");
    }

    private function deteksiJabatan(): array
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

    public function deteksiPendudukTanpaKeluarga()
    {
        $configId = identitas('id');

        return Penduduk::select('id', 'nama', 'nik', 'id_cluster', 'id_kk', 'alamat_sekarang', 'created_at')
            ->kepalaKeluarga()
            ->whereNotNull('id_kk')
            ->wheredoesntHave('keluarga', static fn ($q) => $q->where('config_id', $configId))
            ->get();
    }

    // status dasar penduduk seharusnya mengikuti status terakhir dari log_penduduk
    public function deteksiLogPendudukTidakSinkron()
    {
        $configId = identitas('id');

        $sqlRaw                = "( SELECT MAX(id) max_id, id_pend FROM log_penduduk where config_id = {$configId} GROUP BY  id_pend)";
        $statusDasarBukanHidup = Penduduk::select('tweb_penduduk.id', 'nama', 'nik', 'status_dasar', 'alamat_sekarang', 'kode_peristiwa', 'tweb_penduduk.created_at')
            ->where('status_dasar', '=', StatusDasarEnum::HIDUP)
            ->join(DB::raw("({$sqlRaw}) as log"), 'log.id_pend', '=', 'tweb_penduduk.id')
            ->join('log_penduduk', static function ($q) use ($configId): void {
                $q->on('log_penduduk.id', '=', 'log.max_id')
                    ->where('log_penduduk.config_id', $configId)
                    ->whereIn('kode_peristiwa', [LogPenduduk::MATI, LogPenduduk::PINDAH_KELUAR, LogPenduduk::HILANG, LogPenduduk::TIDAK_TETAP_PERGI]);
            });

        return Penduduk::select('tweb_penduduk.id', 'nama', 'nik', 'status_dasar', 'alamat_sekarang', 'kode_peristiwa', 'tweb_penduduk.created_at')
            ->where('status_dasar', '!=', StatusDasarEnum::HIDUP)
            ->join(DB::raw("({$sqlRaw}) as log"), 'log.id_pend', '=', 'tweb_penduduk.id')
            ->join('log_penduduk', static function ($q) use ($configId): void {
                $q->on('log_penduduk.id', '=', 'log.max_id')
                    ->where('log_penduduk.config_id', $configId)
                    ->whereNotIn('kode_peristiwa', [LogPenduduk::MATI, LogPenduduk::PINDAH_KELUAR, LogPenduduk::HILANG, LogPenduduk::TIDAK_TETAP_PERGI]);
            })->union(
                $statusDasarBukanHidup
            )
            ->get();
    }

    public function deteksiLogPendudukNull()
    {
        identitas('id');

        return LogPenduduk::select('log_penduduk.id', 'nama', 'nik', 'kode_peristiwa', 'log_penduduk.created_at')
            ->whereNull('kode_peristiwa')
            ->join('tweb_penduduk', 'tweb_penduduk.id', '=', 'log_penduduk.id_pend')
            ->get();
    }

    public function deteksiLogPendudukAsing()
    {
        identitas('id');

        return LogPenduduk::select('log_penduduk.id', 'nama', 'nik', 'kode_peristiwa', 'log_penduduk.created_at')
            ->whereNotIn('kode_peristiwa', array_keys(LogPenduduk::kodePeristiwa()))
            ->join('tweb_penduduk', 'tweb_penduduk.id', '=', 'log_penduduk.id_pend')
            ->get();
    }

    public function deteksiLogKeluargaBermasalah()
    {
        return Keluarga::whereDoesntHave('LogKeluarga')->get();
    }

    public function deteksiLogKeluargaGanda()
    {
        $configId = identitas('id');

        return Keluarga::whereIn('id', static fn ($query) => $query->from('log_keluarga')->where(['config_id' => $configId])->select(['id_kk'])->groupBy(['id_kk', 'tgl_peristiwa'])->having(DB::raw('count(tgl_peristiwa)'), '>', 1))->get();
    }

    public function deteksiKepalaKeluargaGanda()
    {
        $configId = identitas('id');

        $kepalaKeluargaDobel = Penduduk::withOnly([])->select(['id_kk'])->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->groupBy(['id_kk'])->having(DB::raw('count(id_kk)'), '>', 1)->pluck('id_kk')->toArray();

        return Penduduk::withOnly(['keluarga' => static fn ($q) => $q->withOnly([])])
            ->kepalaKeluarga()
            ->whereIn('id_kk', $kepalaKeluargaDobel)
            ->whereNotIn('id', static fn ($q) => $q->from('tweb_keluarga')->select(['nik_kepala'])->where(['config_id' => $configId])->whereNotNull('nik_kepala'))
            ->orderBy('id_kk')
            ->get();
    }

    private function deteksiKeluargaKepalaGanda()
    {
        $kepalaKeluargaDobel = Keluarga::groupBy(['nik_kepala'])->having(DB::raw('count(nik_kepala)'), '>', 1)->pluck('nik_kepala')->toArray();

        return Keluarga::whereIn('nik_kepala', $kepalaKeluargaDobel)
            ->with(['kepalaKeluarga'])
            ->orderBy('id')
            ->get();
    }

    private function deteksiNikKepalaBukanKepalaKeluarga()
    {
        return Penduduk::withOnly(['keluarga'])->whereIn('id', static fn ($q) => $q->select(['nik_kepala'])->from('tweb_keluarga'))->where('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA)->get();
    }

    private function deteksiKeluargaTanpaNikKepala()
    {
        $configId = identitas('id');

        return Keluarga::selectRaw('tweb_keluarga.*, log_keluarga.id_peristiwa')->logTerakhir($configId, date('Y-m-d'))->with(['wilayah'])->whereNull('nik_kepala')->get();
    }

    private function deteksiKlasifikasiSuratGanda()
    {
        $configId = identitas('id');

        return KlasifikasiSurat::where(['config_id' => $configId])->whereIn('kode', static fn ($q) => $q->from('klasifikasi_surat')->select(['kode'])->where(['config_id' => $configId])->groupBy('kode')->having(DB::raw('count(kode)'), '>', 1))->orderBy('kode')->get();
    }

    private function deteksiTgllahirNullKosong()
    {
        return Penduduk::where(static function ($query) {
                $query->whereRaw("CAST(tanggallahir AS CHAR) = '0000-00-00'")
                    ->orWhereNull('tanggallahir');
            })
            ->get();
    }

    private function deteksiSuplemenTerdataKosong()
    {
        $suplemenKeluarga = SuplemenTerdata::withOnly(['suplemen'])->sasaranKeluarga()->whereDoesntHave('keluarga');

        return SuplemenTerdata::withOnly(['suplemen'])->sasaranPenduduk()->whereDoesntHave('penduduk')->union($suplemenKeluarga)->get();
    }

    private function deteksiModulAsingGrupAkses()
    {
        return GrupAkses::with(['grup'])->whereDoesntHave('modul')->get();
    }

    public function perbaiki(): void
    {
        // TODO: login
        session(['user_id' => session('user_id') ?: 1]);

        // Perbaiki masalah data yang terdeteksi untuk error yang dilaporkan
        Log::notice('========= Perbaiki masalah data =========');

        foreach ($this->periksa['masalah'] as $masalahIni) {
            $this->selesaikanMasalah($masalahIni);
        }
        session(['db_error' => null]);

        Migrasi::where('versi_database', VERSI_DATABASE)->delete();

        // Clear cache
        cache()->flush();
    }

    public function perbaikiSebagian($masalah_ini): void
    {
        // TODO: login
        session(['user_id' => session('user_id') ?: 1]);

        $this->selesaikanMasalah($masalah_ini);

        session(['db_error' => null]);
        // clear cache
        cache()->flush();
    }

    private function perbaikiAutoincrement(): void
    {
        $hasil = true;

        // Tabel yang tidak memerlukan Auto_Increment
        $excludeTable = [
            'analisis_respon',
            'analisis_respon_hasil',
            'password_resets',
            'sentitems', // Belum tau bentuk datanya bagamana
            'sys_traffic',
            'tweb_penduduk_mandiri',
            'tweb_penduduk_map', // id pada tabel tweb_penduduk_map == penduduk.id (buka id untuk AI)
        ];

        // Auto_Increment hanya diterapkan pada kolom berikut
        $onlyPk = [
            'id',
            'id_kontak',
            'id_aset',
        ];

        // Daftar tabel yang tidak memiliki Auto_Increment
        $tables = DB::select("SELECT `TABLE_NAME` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$this->databaseOption['database']}' AND AUTO_INCREMENT IS NULL");
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $tbl) {
            $name = $tbl->TABLE_NAME;
            if (! in_array($name, $excludeTable) && in_array($key = DB::getSchemaBuilder()->getColumnListing($name)[0], $onlyPk)) {

                $this->addAutoIncrement($name, $key);
                Log::error("Auto_Increment pada tabel {$name} dengan kolom {$key} telah ditambahkan.");
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function addAutoIncrement(string $table, string $key)
    {
        // Query to get the table schema
        $stmt   = DB::select("SHOW CREATE TABLE {$table}");
        $result = (array) $stmt[0];

        $hasPrimaryKey = false;
        // Check for primary key and auto increment
        if (preg_match('/PRIMARY KEY \(`(.+?)`\)/', $result['Create Table'], $matches)) {
            $hasPrimaryKey = true;
        }
        if (! $hasPrimaryKey) {
            DB::statement("ALTER TABLE {$table} add primary key({$key})");
        }
        DB::statement("ALTER TABLE {$table} MODIFY {$key} INT NOT NULL AUTO_INCREMENT");
    }

    private function perbaikiCollationTable(): bool
    {
        $hasil  = true;
        $tables = $this->periksa['collation_table'];

        if ($tables) {
            $this->updateCollation($this->databaseOption['database'], $this->databaseOption['collation']);
        }

        return $hasil;
    }

    private function perbaikiJabatan(): bool
    {
        if ($jabatan = $this->periksa['data_jabatan']) {
            RefJabatan::insert($jabatan);
        }

        return true;
    }

    private function perbaikiPendudukTanpaKeluarga(): void
    {
        $configId     = identitas('id');
        $kodeDesa     = identitas('kode_desa');
        $dataPenduduk = Penduduk::select('id', 'id_cluster', 'id_kk', 'alamat_sekarang', 'created_at')
            ->kepalaKeluarga()
            ->whereNotNull('id_kk')
            ->wheredoesntHave('keluarga', static fn ($q) => $q->where('config_id', $configId))
            ->get();
        // nomer urut kk sementara
        $digit = Keluarga::nomerKKSementara();

        $idSementara = [];

        foreach ($dataPenduduk as $value) {
            if (isset($idSementara[$value->id_kk])) {
                continue;
            }
            $nokkSementara = '0' . $kodeDesa . sprintf('%05d', (int) $digit + 1);
            $hasil         = Keluarga::create([
                'id'         => $value->id_kk,
                'config_id'  => $configId,
                'no_kk'      => $nokkSementara,
                'nik_kepala' => $value->id,
                'tgl_daftar' => $value->created_at,
                'id_cluster' => $value->id_cluster,
                'alamat'     => $value->alamat_sekarang,
                'updated_at' => $value->created_at,
                'updated_by' => 1,
            ]);

            $digit++;
            $idSementara[$value->id_kk] = 1;
            if ($hasil) {
                log_message('notice', 'Berhasil. Penduduk ' . $value->id . ' sudah terdaftar di keluarga');
            } else {
                log_message('error', 'Gagal. Penduduk ' . $value->id . ' belum terdaftar di keluarga');
            }
        }
    }

    private function perbaikiLogPendudukNull(): void
    {
        LogPenduduk::whereIn('id', array_column($this->periksa['log_penduduk_null'], 'id'))->update(['kode_peristiwa' => LogPenduduk::BARU_PINDAH_MASUK]);
    }

    private function perbaikiLogPendudukAsing(): void
    {
        LogPenduduk::whereIn('id', array_column($this->periksa['log_penduduk_asing'], 'id'))->delete();
    }

    private function perbaikiLogKeluargaBermasalah(): void
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

    private function perbaikiModulAsingGrupAkses()
    {
        GrupAkses::whereDoesntHave('modul')->delete();
    }

    private function perbaikiKeluargaKepalaGanda(): void
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

    private function perbaikiNikKepalaBukanKepalaKeluarga(): void
    {
        $penduduk = $this->periksa['nik_kepala_bukan_kepala_keluarga'];
        if ($penduduk) {
            Penduduk::whereIn('id', array_column($penduduk, 'id'))->update(['kk_level' => SHDKEnum::KEPALA_KELUARGA]);
        }
    }

    private function perbaikiKeluargaTanpaNikKepala(): void
    {
        $keluarga = $this->periksa['keluarga_tanpa_nik_kepala'];
        if ($keluarga) {
            Keluarga::whereIn('id', array_column($keluarga, 'id'))->delete();
        }
    }

    private function selesaikanMasalah($masalah_ini): void
    {
        switch ($masalah_ini) {
            case 'autoincrement':
                $this->perbaikiAutoincrement();
                break;

            case 'collation':
                $this->perbaikiCollationTable();
                break;

            case 'data_jabatan_tidak_ada':
                $this->perbaikiJabatan();
                break;

            case 'penduduk_tanpa_keluarga':
                $this->perbaikiPendudukTanpaKeluarga();
                break;

            case 'log_penduduk_null':
                $this->perbaikiLogPendudukNull();
                break;

            case 'log_penduduk_asing':
                $this->perbaikiLogPendudukAsing();
                break;

            case 'log_keluarga_bermasalah':
                $this->perbaikiLogKeluargaBermasalah();
                break;

            case 'keluarga_kepala_ganda':
                $this->perbaikiKeluargaKepalaGanda();
                break;

            case 'nik_kepala_bukan_kepala_keluarga':
                $this->perbaikiNikKepalaBukanKepalaKeluarga();
                break;

            case 'keluarga_tanpa_nik_kepala':
                $this->perbaikiKeluargaTanpaNikKepala();
                break;

            case 'modul_asing':
                $this->perbaikiModulAsingGrupAkses();
                break;

            default:
                break;
        }
    }

    /**
     * Get the value of periksa
     */
    public function getPeriksa()
    {
        return $this->periksa;
    }
}
