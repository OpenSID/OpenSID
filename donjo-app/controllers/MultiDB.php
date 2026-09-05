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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\User;
use App\Traits\Upload;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class MultiDB extends Admin_Controller
{
    use Upload;

    public $modul_ini    = 'pengaturan';
    public $sub_modul_in = 'database';

    /**
     * Daftar nama tabel yang hanya disertakan jika ada di database.
     */
    protected array $existenceTableNames = [
        'keuangan_template',
    ];

    /**
     * Property untuk menyimpan data backup sebagai collection.
     */
    private Collection $backupData;

    /**
     * Property untuk menyimpan data restore sebagai collection.
     */
    private Collection $restoreData;

    /**
     * Daftar nama tabel yang digunakan dalam backup database.
     */
    private array $tableNames = [
        'config',
        'tweb_wil_clusterdesa',
        'tweb_penduduk',
        'tweb_keluarga',
        'tweb_rtm',
        'suplemen',
        'suplemen_terdata',
        'kelompok_master',
        'kelompok',
        'kelompok_anggota',
        'widget',
        'kategori',
        'pendapat',
        'pengaduan',
        'pesan',
        'kehadiran_jam_kerja',
        'kehadiran_hari_libur',
        'inventaris_tanah',
        'inventaris_peralatan',
        'inventaris_kontruksi',
        'inventaris_jalan',
        'inventaris_gedung',
        'inventaris_asset',
        'inbox',
        'point',
        'keuangan_template',
        'pemilihan',
        'polygon',
        'alias_kodeisian',
        'klasifikasi_surat',
        'kontak',
        'kontak_grup',
        'laporan_sinkronisasi',
        'line',
        'log_backup',
        'log_restore_desa',
        'outbox',
        'log_sinkronisasi',
        'log_tte',
        'media_sosial',
        'menu',
        'notifikasi',
        'hubung_warga',
        'kehadiran_alasan_keluar',
        'gis_simbol',
        'tanah_kas_desa',
        'sys_traffic',
        'posyandu',
        'teks_berjalan',
        // 'theme', // Tidak perlu, karena bisa lakukan scan ulang masing-masing desa
        'buku_keperluan',
        'surat_masuk',
        'urls',
        'tweb_surat_format',
        'buku_pertanyaan',
        'buku_tamu',
        'surat_keluar',
        'cdesa',
        'anjungan',
        'surat_dinas',
        'statistics',
        'user_grup',
        'gambar_gallery',
        'produk_kategori',
        'program',
        'ref_jabatan',
        'ref_syarat_surat',
        'sentitems',
        'setting_aplikasi',
        'setting_modul',
        'tweb_penduduk_umur',
        'dokumen',
        'tweb_penduduk_map',
        'shortcut',
        'covid19_vaksin',
        'anjungan_menu',
        'keuangan',
        'mutasi_inventaris_asset',
        'mutasi_inventaris_tanah',
        'pesan_mandiri',
        'pesan_detail',
        'pembangunan_ref_dokumentasi',
        'pembangunan',
        'pelapak',
        'mutasi_inventaris_peralatan',
        'log_hapus_penduduk',
        'mutasi_inventaris_jalan',
        'mutasi_inventaris_gedung',
        'tanah_desa',
        'log_notifikasi_admin',
        'tweb_penduduk_mandiri',
        'log_notifikasi_mandiri',
        'kader_pemberdayaan_masyarakat',
        'garis',
        'dtks_ref_lampiran',
        'dtks_pengaturan_program',
        'dtks_lampiran',
        'covid19_pemudik',
        'covid19_pantau',
        'area',
        'analisis_parameter',
        'analisis_master',
        'analisis_klasifikasi',
        'analisis_kategori_indikator',
        'tweb_desa_pamong',
        'user',
        'artikel',
        'komentar',
        'agenda',
        'cdesa_penduduk',
        'bulanan_anak',
        'buku_kepuasan',
        'kehadiran_perangkat_desa',
        'log_perubahan_penduduk',
        'anggota_grup_kontak',
        'analisis_periode',
        'lokasi',
        'log_penduduk',
        'mutasi_cdesa',
        'kia',
        'permohonan_surat',
        'persil',
        'kehadiran_pengaduan',
        'ibu_hamil',
        'disposisi_surat_masuk',
        'grup_akses',
        'log_tolak',
        'produk',
        'sasaran_paud',
        'dtks',
        'program_peserta',
        'analisis_respon_bukti',
        'analisis_respon_hasil',
        'analisis_respon',
        'log_keluarga',
        'analisis_partisipasi',
        'dtks_anggota',
        'analisis_indikator',
        'log_surat',
        'log_surat_dinas',
    ];

    /**
     * Daftar nama tabel yang dikecualikan dalam backup database.
     */
    private array $excludeTableNames = [

    ];

    /**
     * Daftar tabel dan kolom yang memerlukan pembaruan berantai (cascade update).
     */
    private array $cascadeUpdate = [
        'tweb_keluarga' => [
            'column'    => 'id_kk',
            'reference' => 'tweb_penduduk',
        ],
        'tweb_penduduk' => [
            'column'    => 'id_kepala',
            'reference' => 'tweb_wil_clusterdesa',
        ],
    ];

    /**
     * Daftar nama tabel yang digunakan dengan kondisi khusus, memiliki child dan parent.
     *
     * @var array
     */
    private $tabelKhusus = [
        'gambar_gallery' => ['id', 'parrent'],
        'line'           => ['id', 'parrent'],
        'menu'           => ['id', 'parrent'],
        'pengaduan'      => ['id', 'id_pengaduan'],
        'point'          => ['id', 'parrent'],
        'polygon'        => ['id', 'parrent'],
        'setting_modul'  => ['id', 'parent'],
        'dokumen'        => ['id', 'id_parent'],
    ];

    /**
     * Daftar nama tabel yang digunakan untuk mendefinisikan relasi antara tabel-tabel dalam format JSON.
     *
     * @var array
     */
    private $tabelRelasiJson = [
        'tweb_wil_clusterdesa' => [
            'user' => 'akses_wilayah',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        isCan('b', $this->sub_modul_ini);
    }

    public function backup(): void
    {
        // Filter tabel yang boleh di-backup
        $tableNames = collect($this->tableNames)
            ->filter(fn ($tableName): bool => ! in_array($tableName, $this->excludeTableNames))
            ->filter(
                fn ($tableName): bool => ! in_array($tableName, $this->existenceTableNames)
                || Schema::hasTable($tableName)
            );

        // Ambil max ID untuk setiap tabel
        $maxIds = $this->getMaxIdForTables($tableNames->toArray());

        // Buat random ID berdasarkan max ID yang ada
        $randomIds = $tableNames->mapWithKeys(static fn ($tableName) => [$tableName => ($maxIds[$tableName] ?? 0) + 1]);

        // Inisialisasi property backupData sebagai Collection
        $this->backupData = collect([
            'info' => [
                'versi'    => VERSION,
                'premimum' => PREMIUM,
                'tanggal'  => date('Y-m-d H:i:s'),
                'random'   => $randomIds->toArray(),
            ],
            'tabel' => collect(),
        ]);

        DB::beginTransaction();

        try {
            $tableNames->each(function ($tableName) use ($randomIds) {
                $data = $this->fetchTableData($tableName, $randomIds[$tableName] ?? null);

                // Mutasi langsung ke koleksi 'tabel'
                $this->backupData->get('tabel')->put($tableName, $data);
            });

            $backupFile = 'backup_' . date('YmdHis') . '.sid';

            $this->load->helper('download');
            force_download($backupFile, $this->backupData->toJson());

        } catch (Throwable $e) {
            Log::error($e);

            redirect_with('error', 'Proses backup seluruh database SID (.sid) gagal.', ci_route('database'));
        } finally {
            DB::rollBack();
        }
    }

    public function restore()
    {
        isCan('b', $this->sub_modul_ini, true);

        $file = $this->upload('userfile', [
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'sid',
            'file_ext'      => 'sid',
            'max_size'      => max_upload() * 1024,
            'ignore_mime'   => true,
            'cek_script'    => false,
        ], site_url('database'));

        $backupFile = sys_get_temp_dir() . '/' . $file;
        // Ubah ke Collection
        $this->restoreData = collect(json_decode(file_get_contents($backupFile), true));

        $redirctType = 'success';
        $message     = 'Proses restore dari backup berhasil.';

        try {
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $this->validateBackupData($this->restoreData->toArray());
            $this->restoreConfigData($this->restoreData['tabel']['config']['data']);
            $this->deleteExistingData($this->restoreData['tabel']);
            $this->restoreBackupData($this->restoreData['tabel']);
            $this->updateDataJsonTable($this->restoreData['info']['random']);

            DB::afterCommit(static function () {
                // Login ulang karena user sebelumnya sudah dihapus
                $user = User::superAdmin()->first();
                auth('admin')->login($user);

                // Hapus cache setelah transaksi selesai
                hapus_cache('_cache_modul');
                kosongkanFolder(config_item('cache_blade'));
                cache()->flush();
            });

            DB::commit();

            Log::info('Backup restore berhasil.');
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();

            $redirctType = 'error';
            $message     = 'Proses restore dari backup gagal.';
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        redirect_with($redirctType, $message, site_url('database'));
    }

    /**
     * Mengambil data dari tabel dengan mempertimbangkan relasi.
     *
     * @param mixed $tableName
     */
    private function fetchTableData($tableName, int $rand): array
    {
        $config_id   = DB::table('config')->where('app_key', get_app_key())->value('id');
        $primary_key = $this->getPrimaryKey($tableName);

        if ($primary_key) {
            if ($tableName == 'config') {
                DB::table($tableName)->where('id', $config_id)->update(['id' => DB::raw("`id` + {$rand}")]);
                $config_id_new = DB::table('config')->where('app_key', get_app_key())->value('id');
                $tableData     = DB::table($tableName)->where('id', $config_id_new)->get();
            } else {
                $this->updatePrimaryKeyAndRelatedTables($tableName, $config_id, $primary_key, $rand);
                $tableData = DB::table($tableName)->where('config_id', $config_id)->get();
            }
        } else {
            $tableData = DB::table($tableName)->where('config_id', $config_id)->get();
        }

        return [
            'primary_key' => $primary_key,
            'data'        => $tableData,
        ];
    }

    /**
     * Mendapatkan primary key dari tabel yang bertipe INT
     *
     * @param string $tableName
     *
     * @return string|null
     */
    private function getPrimaryKey($tableName)
    {
        $database = DB::getDatabaseName();

        $primaryKeys = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $tableName)
            ->where('CONSTRAINT_NAME', 'PRIMARY')
            ->pluck('COLUMN_NAME');

        if ($primaryKeys->isEmpty()) {
            return null;
        }

        foreach ($primaryKeys as $column) {
            $columnType = DB::table('information_schema.COLUMNS')
                ->where('TABLE_SCHEMA', $database)
                ->where('TABLE_NAME', $tableName)
                ->where('COLUMN_NAME', $column)
                ->value('DATA_TYPE');

            if (in_array($columnType, ['int', 'bigint', 'smallint', 'mediumint', 'tinyint'])) {
                return $column;
            }
        }

        return null;
    }

    /**
     * Mengambil nilai maksimum dari primary key pada setiap tabel.
     */
    private function getMaxIdForTables(array $tableNames): array
    {
        $maxIds           = [];
        $connections      = array_keys(config('database.connections'));
        $secondConnection = count($connections) >= 2 ? end($connections) : null;

        foreach ($tableNames as $tableName) {
            $primaryKey = $this->getPrimaryKey($tableName);

            if ($primaryKey) {
                $maxIdA = DB::table($tableName)->max($primaryKey) ?? 0;
                $maxIdB = $secondConnection ? DB::connection($secondConnection)->table($tableName)->max($primaryKey) ?? 0 : 0;

                $maxIds[$tableName] = max($maxIdA, $maxIdB);
            } else {
                $maxIds[$tableName] = 0;
            }
        }

        return $maxIds;
    }

    private function updatePrimaryKeyAndRelatedTables($tableName, $config_id, $primary_key, $rand)
    {
        $oldRows = DB::table($tableName)
            ->where('config_id', $config_id)
            ->get([$primary_key]);

        DB::table($tableName)
            ->where('config_id', $config_id)
            ->update([
                $primary_key => DB::raw("`{$primary_key}` + {$rand}"),
            ]);

        // Jika tabel ini punya relasi JSON, proses update JSON
        if (array_key_exists($tableName, $this->tabelRelasiJson)) {
            foreach ($this->tabelRelasiJson[$tableName] as $relatedTable => $jsonColumn) {
                foreach ($oldRows as $row) {
                    $oldId = (string) $row->{$primary_key};
                    $newId = (string) ($oldId + $rand);

                    DB::statement("
                        UPDATE {$relatedTable}
                        SET {$jsonColumn} = JSON_REPLACE(
                            {$jsonColumn},
                            JSON_UNQUOTE(JSON_SEARCH({$jsonColumn}, 'one', ?, NULL)),
                            ?
                        )
                        WHERE config_id = ?
                        AND JSON_SEARCH({$jsonColumn}, 'one', ?, NULL) IS NOT NULL
                    ", [$oldId, $newId, $config_id, $oldId]);
                }
            }
        }

        if (array_key_exists($tableName, $this->cascadeUpdate)) {
            $column    = $this->cascadeUpdate[$tableName]['column'];
            $reference = $this->cascadeUpdate[$tableName]['reference'];

            foreach ($oldRows as $row) {
                $oldId = (int) $row->{$primary_key};
                $newId = $oldId + $rand;

                $this->cascadeUpdate($reference, $column, $oldId, $newId);
            }
        }

        if (in_array($tableName, array_keys($this->tabelKhusus))) {
            $child = $this->tabelKhusus[$tableName][1];
            DB::table($tableName)->where('config_id', $config_id)->where($child, '!=', 0)->update([$child => DB::raw("`{$child}` + {$rand}")]);
        }
    }

    /**
     * Melakukan update manual terhadap foreign key pada data collection backupData.
     * Digunakan untuk meniru efek ON UPDATE CASCADE pada struktur backupData yang berupa Collection.
     *
     * Asumsi struktur backupData['tabel'][$tableName] berisi array dengan:
     *  - 'primary_key' => nama primary key tabel,
     *  - 'data' => Collection berisi data tabel,
     *
     * Fungsi ini mencari setiap record di dalam 'data' yang memiliki nilai foreign key
     * sama dengan $oldId, kemudian menggantinya dengan $newId.
     *
     * @param string $tableName       Nama tabel dalam collection 'tabel' di backupData.
     * @param string $foreignKeyField Nama field yang merupakan foreign key, misalnya 'id_kk'.
     * @param int    $oldId           Nilai primary key lama yang akan diganti.
     * @param int    $newId           Nilai primary key baru sebagai pengganti.
     */
    private function cascadeUpdate(string $tableName, string $foreignKeyField, int $oldId, int $newId): void
    {
        $tabel = $this->backupData->get('tabel');

        if (! $tabel->has($tableName)) {
            return;
        }

        $tableEntry = $tabel->get($tableName);

        // Pastikan 'data' adalah Collection
        if (! ($tableEntry['data'] instanceof Collection)) {
            return;
        }

        // Update field foreign key di dalam collection 'data'
        $tableEntry['data']->transform(static function ($item) use ($foreignKeyField, $oldId, $newId) {
            if (data_get($item, $foreignKeyField) == $oldId) {
                data_set($item, $foreignKeyField, $newId);
            }

            return $item;
        });
    }

    private function validateBackupData($backupData)
    {
        $version = $backupData['info']['versi'];

        if (substr((string) $version, 0, 4) !== substr(VERSION, 0, 4)) {
            redirect_with('error', 'Proses restore dari backup gagal. <br>Versi opensid tidak sama', 'database');
        }

        $config_backup   = $backupData['tabel']['config']['data'][0];
        $config_database = DB::table('config')->where('app_key', get_app_key())->first();

        if ($config_backup['kode_desa'] != $config_database->kode_desa) {
            redirect_with('error', 'Proses restore dari backup gagal. <br>Data desa tidak sama dengan data yang ada di database.', ci_route('database'));
        }
    }

    private function restoreConfigData($configData)
    {
        unset($configData['id'], $configData['app_key']);
        (new App\Models\Config())->update($configData);
    }

    private function deleteExistingData($tables)
    {
        foreach (array_keys(array_reverse($tables)) as $tableName) {
            if ($tableName == 'config') {
                continue;
            }
            DB::table($tableName)->where(['config_id' => identitas('id')])->delete();
        }
    }

    private function restoreBackupData($tables)
    {
        foreach ($tables as $tableName => $tableDetails) {
            $this->restoreTableData($tableName, $tableDetails);
        }
    }

    private function restoreTableData(string $tableName, array $tableDetails): void
    {
        if ($tableName === 'config' || empty($tableDetails['data'])) {
            return;
        }

        $configId = identitas('id');

        // Proses data dalam batch kecil untuk mengurangi beban memori
        collect($tableDetails['data'])
            ->chunk(500) // Batch lebih besar untuk mengurangi jumlah query
            ->each(static function ($chunk) use ($tableName, $configId) {
                $chunk = $chunk->map(static function ($record) use ($configId) {
                    if (isset($record['config_id'])) {
                        $record['config_id'] = $configId;
                    }

                    return $record;
                });

                // Gunakan bulk insert untuk mempercepat proses
                DB::table($tableName)->insert($chunk->toArray());
            });

        log_message('notice', "Restore data {$tableName} berhasil, total: " . count($tableDetails['data']));
    }

    private function updateDataJsonTable($rand): void
    {
        $listTables = [
            'permohonan_surat'  => 'perbaikanPermohonanSurat',
            'tweb_surat_format' => 'perbaikanSuratFormat',
        ];

        foreach ($listTables as $tableName => $functionName) {
            $this->{$functionName}($rand);
            log_message('notice', 'perbaikan data json table  ' . $tableName . ' berhasil.');
        }
    }

    private function perbaikanSuratFormat($rand): void
    {
        $suratFormat               = DB::table('tweb_surat_format')->where(['config_id' => identitas('id')])->whereNotNull('syarat_surat')->get();
        $idSyaratSuratAwal         = DB::table('ref_syarat_surat')->where('config_id', identitas('id'))->orderBy('ref_syarat_id', 'asc')->first()->ref_syarat_id ?? 0;
        $idSyaratSuratDesaLainAwal = DB::table('ref_syarat_surat')->where('config_id', '!=', identitas('id'))->orderBy('ref_syarat_id', 'desc')->first()->ref_syarat_id ?? 0;
        $selisihSyarat             = $idSyaratSuratAwal - $idSyaratSuratDesaLainAwal;
        $idSyaratSuratAwal -= ($selisihSyarat);

        foreach ($suratFormat as $data) {
            $syarat = json_decode($data->syarat_surat, true);
            if (! is_array($syarat)) {
                $syarat = [];
            }
            $syarat = empty($syarat) ? null : $this->perbaikanSyaratSurat($syarat, ['idSyaratSuratAwal' => $idSyaratSuratAwal]);

            DB::table('tweb_surat_format')->where('id', $data->id)->update([
                'syarat_surat' => $syarat,
            ]);
        }
    }

    private function perbaikanPermohonanSurat($rand): void
    {
        $permohonanSurat       = DB::table('permohonan_surat')->where(['config_id' => identitas('id')])->get();
        $idDokumenAwal         = DB::table('dokumen')->where('config_id', identitas('id'))->orderBy('id', 'asc')->first()->id ?? 0;
        $idDokumenDesaLainAwal = DB::table('dokumen')->where('config_id', '!=', identitas('id'))->orderBy('id', 'desc')->first()->id ?? 0;
        $selisihIdDokumen      = $idDokumenAwal - $idDokumenDesaLainAwal;
        $idDokumenAwal -= ($selisihIdDokumen);
        $idSyaratSuratAwal         = DB::table('ref_syarat_surat')->where('config_id', identitas('id'))->orderBy('ref_syarat_id', 'asc')->first()->ref_syarat_id ?? 0;
        $idSyaratSuratDesaLainAwal = DB::table('ref_syarat_surat')->where('config_id', '!=', identitas('id'))->orderBy('ref_syarat_id', 'desc')->first()->ref_syarat_id ?? 0;
        $selisihSyarat             = $idSyaratSuratAwal - $idSyaratSuratDesaLainAwal;
        $idSyaratSuratAwal -= ($selisihSyarat);

        $idNikAwal         = DB::table('tweb_penduduk')->where('config_id', identitas('id'))->orderBy('id', 'asc')->first()->id ?? 0;
        $idNikDesaLainAwal = DB::table('tweb_penduduk')->where('config_id', '!=', identitas('id'))->orderBy('id', 'desc')->first()->id ?? 0;
        $selisihNik        = $idNikAwal - $idNikDesaLainAwal;
        $idNikAwal -= ($selisihNik);
        $idPamongAwal         = DB::table('tweb_desa_pamong')->where('config_id', identitas('id'))->orderBy('pamong_id', 'asc')->first()->pamong_id ?? 0;
        $idPamongDesaLainAwal = DB::table('tweb_desa_pamong')->where('config_id', '!=', identitas('id'))->orderBy('pamong_id', 'desc')->first()->pamong_id ?? 0;
        $selisihPamong        = $idPamongAwal - $idPamongDesaLainAwal;
        $idPamongAwal -= ($selisihPamong);

        foreach ($permohonanSurat as $data) {
            $isianForm = json_decode($data->isian_form, true);
            $syarat    = json_decode($data->syarat, true);
            if (! is_array($syarat)) {
                $syarat = [];
            }

            $isianForm = $this->perbaikanIsianForm($isianForm, $data->id_surat, ['idNikAwal' => $idNikAwal, 'idPamongAwal' => $idPamongAwal]);
            $syarat    = empty($syarat) ? '{}' : $this->perbaikanSyarat($syarat, ['idDokumenAwal' => $idDokumenAwal, 'idSyaratSuratAwal' => $idSyaratSuratAwal]);

            DB::table('permohonan_surat')->where('id', $data->id)->update([
                'isian_form' => json_encode($isianForm),
                'syarat'     => $syarat,
            ]);
        }
    }

    /**
     * Perbaikan isian form.
     *
     * @param array $isianForm
     *                         {"nik":"2381","id_surat":"8","pamong_id":"33"} sementara ini yang diketahui untuk disesuaikan
     * @param mixed $rand
     * @param mixed $idSurat
     * @param mixed $dataAwal
     */
    private function perbaikanIsianForm(array $isianForm, $idSurat, $dataAwal): array
    {
        $isianForm['id_surat']  = $idSurat;
        $isianForm['nik']       = ($isianForm['nik']) + $dataAwal['idNikAwal'];
        $isianForm['pamong_id'] = empty($isianForm['pamong_id']) ? '' : (int) $isianForm['pamong_id'] + $dataAwal['idPamongAwal'];

        return $isianForm;
    }

    /**
     * Perbaikan syarat surat.
     * {"1":"48","3":"50","9":"49"},  key berasal dari ref_syarat_surat dan value berasal dari dokumen
     *
     * @param mixed $rand
     * @param mixed $dataAwal
     */
    private function perbaikanSyarat(array $syarat, $dataAwal): string
    {

        $updatedArray = [];

        foreach ($syarat as $key => $value) {
            $newKey   = (int) $key + $dataAwal['idSyaratSuratAwal'];
            $newValue = $value == -1 ? -1 : (int) $value + $dataAwal['idDokumenAwal'];
            // Assign the new key and value to the updated array
            $updatedArray[$newKey] = $newValue;
        }

        return json_encode($updatedArray);
    }

    private function perbaikanSyaratSurat(array $syarat, $dataAwal): string
    {

        $updatedArray = [];

        foreach ($syarat as $key => $value) {
            $newValue = (int) $value + $dataAwal['idSyaratSuratAwal'];
            // Assign the new key and value to the updated array
            $updatedArray[] = $newValue;
        }

        return '["' . implode('","', $updatedArray) . '"]';
    }
}
