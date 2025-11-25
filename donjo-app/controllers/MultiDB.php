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

use App\Models\User;
use App\Traits\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

defined('BASEPATH') || exit('No direct script access allowed');

class MultiDB extends Admin_Controller
{
    use Upload;

    public $modul_ini    = 'pengaturan';
    public $sub_modul_in = 'database';

    /**
     * Daftar nama tabel yang digunakan dalam backup database.
     */
    private array $tableNames = [
        'config',
        'tweb_wil_clusterdesa',
        'tweb_keluarga',
        'tweb_penduduk',
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
        'keuangan_master',
        'inventaris_peralatan',
        'inventaris_kontruksi',
        'inventaris_jalan',
        'inventaris_gedung',
        'inventaris_asset',
        'inbox',
        'point',
        'keuangan_manual_rinci',
        'pemilihan',
        'polygon',
        'log_login',
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
        'login_attempts',
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
        'keuangan_ta_triwulan_rinci',
        'keuangan_ta_triwulan',
        'keuangan_ta_spp',
        'keuangan_ta_tbp_rinci',
        'keuangan_ta_tbp',
        'keuangan_ta_sts_rinci',
        'keuangan_ta_sts',
        'keuangan_ta_spppot',
        'keuangan_ta_sppbukti',
        'keuangan_ta_spp_rinci',
        'keuangan_ta_spjpot',
        'keuangan_ta_spj_sisa',
        'mutasi_inventaris_asset',
        'mutasi_inventaris_tanah',
        'pesan_mandiri',
        'keuangan_ta_spj_bukti',
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
        'keuangan_ta_spj_rinci',
        'keuangan_ta_spj',
        'keuangan_ref_rek2',
        'keuangan_ref_rek1',
        'keuangan_ref_potongan',
        'keuangan_ref_perangkat',
        'keuangan_ref_neraca_close',
        'keuangan_ref_korolari',
        'keuangan_ref_kegiatan',
        'keuangan_ref_kecamatan',
        'keuangan_ref_desa',
        'keuangan_ref_bunga',
        'keuangan_ta_saldo_awal',
        'keuangan_ref_bel_operasional',
        'keuangan_ref_bank_desa',
        'kader_pemberdayaan_masyarakat',
        'keuangan_ref_rek4',
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
        'keuangan_ref_rek3',
        'keuangan_ref_bidang',
        'keuangan_ref_sbu',
        'keuangan_ta_pajak',
        'keuangan_ta_rpjm_visi',
        'keuangan_ta_rpjm_tujuan',
        'keuangan_ta_rpjm_pagu_tahunan',
        'keuangan_ta_rpjm_pagu_indikatif',
        'keuangan_ta_rpjm_misi',
        'keuangan_ta_rpjm_kegiatan',
        'keuangan_ta_rpjm_bidang',
        'keuangan_ta_rab_sub',
        'keuangan_ta_rab_rinci',
        'keuangan_ta_rab',
        'keuangan_ta_perangkat',
        'keuangan_ta_pencairan',
        'keuangan_ta_pemda',
        'keuangan_ta_pajak_rinci',
        'keuangan_ta_rpjm_sasaran',
        'keuangan_ta_mutasi',
        'keuangan_ref_sumber',
        'keuangan_ta_anggaran',
        'keuangan_ta_jurnal_umum_rinci',
        'keuangan_ta_anggaran_log',
        'keuangan_ta_jurnal_umum',
        'keuangan_ta_anggaran_rinci',
        'keuangan_ta_kegiatan',
        'keuangan_ta_bidang',
        'keuangan_ta_desa',
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
        'analisis_partisipasi',    // Tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT (dihapus tidak digunakan)
        'analisis_respon',         // Tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT (dihapus tidak digunakan)
        'analisis_respon_bukti',   // Tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT (dihapus tidak digunakan)
        'dtks_ref_lampiran',       // Tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT (dihapus tidak digunakan)
        'tweb_penduduk_map',       // Tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT
        'tweb_penduduk_mandiri',   // Tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT
        'log_notifikasi_mandiri',  // Tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT
        'log_notifikasi_admin',    // Tidak perlu, karena tidak ada kolom `id` AUTO_INCREMENT

        // Error saat restore
        'fcm_token_mandiri',
        'fcm_token',
    ];

    /**
     * Daftar nama tabel yang berisi tergantung pada data penduduk.
     */
    private array $tergantungDataPenduduk = [
        'tweb_keluarga'        => ['key' => 'nik_kepala', 'nik_kepala' => [], 'unique_record' => ['no_kk']],
        'tweb_rtm'             => ['key' => 'nik_kepala', 'nik_kepala' => [], 'unique_record' => ['no_kk']],
        'tweb_wil_clusterdesa' => ['key' => 'id_kepala', 'id_kepala' => [], 'unique_record' => ['rt', 'rw', 'dusun']],
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
        $tableNames = collect($this->tableNames)->filter(fn ($tableName): bool => ! in_array($tableName, $this->excludeTableNames));

        // Ambil max ID untuk setiap tabel
        $maxIds = $this->getMaxIdForTables($tableNames->toArray());

        // Buat random ID berdasarkan max ID yang ada
        $randomIds = [];

        foreach ($tableNames as $tableName) {
            $randomIds[$tableName] = ($maxIds[$tableName] ?? 0) + 1; // +1 memastikan ID baru lebih besar dari ID sebelumnya
        }

        $backupData = [
            'info' => [
                'versi'    => VERSION,
                'premimum' => PREMIUM,
                'tanggal'  => date('Y-m-d H:i:s'),
                'random'   => $randomIds,
            ],
            'tabel' => [],
        ];

        DB::beginTransaction();

        try {
            foreach ($tableNames as $tableName) {
                $backupData['tabel'][$tableName] = $this->fetchTableData($tableName, $randomIds[$tableName]);
            }

            $backupFile = 'backup_' . date('YmdHis') . '.sid';

            $this->load->helper('download');
            force_download($backupFile, json_encode($backupData));
        } catch (Throwable $e) {
            Log::error($e);

            redirect_with('error', 'Proses backup seluruh database SID (.sid) gagal.', ci_route('database'));
        } finally {
            DB::rollBack();
        }
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
            'data'        => $tableData?->toArray(),
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
        $primaryKeys = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_NAME', $tableName)
            ->where('CONSTRAINT_NAME', 'PRIMARY')
            ->pluck('COLUMN_NAME');

        foreach ($primaryKeys as $column) {
            $columnType = DB::table('information_schema.COLUMNS')
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
        DB::table($tableName)
            ->where('config_id', $config_id)
            ->when(in_array($tableName, array_keys($this->tabelRelasiJson)), function ($query) use ($config_id, $tableName, $primary_key, $rand) {
                $rows = $query->get([$primary_key]);
                $query->update([$primary_key => DB::raw("`{$primary_key}` + {$rand}")]);

                foreach ($this->tabelRelasiJson[$tableName] as $relatedTable => $jsonColumn) {
                    foreach ($rows as $row) {
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
            }, static function ($query) use ($primary_key, $rand) {
                $query->update([$primary_key => DB::raw("`{$primary_key}` + {$rand}")]);
            });

        if (in_array($tableName, array_keys($this->tabelKhusus))) {
            $child = $this->tabelKhusus[$tableName][1];
            DB::table($tableName)->where('config_id', $config_id)->where($child, '!=', 0)->update([$child => DB::raw("`{$child}` + {$rand}")]);
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

        if (! $file) {
            return;
        }

        $backupFile = sys_get_temp_dir() . '/' . $file;
        $backupData = json_decode(file_get_contents($backupFile), true);

        $redirctType = 'success';
        $message     = 'Proses restore dari backup berhasil.';

        try {
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $this->validateBackupData($backupData);
            $this->restoreConfigData($backupData['tabel']['config']['data']);
            $this->deleteExistingData($backupData['tabel']);
            $this->restoreBackupData($backupData['tabel']);
            $this->updateDependentData($backupData['tabel']['tweb_penduduk']['data'], $backupData['info']['random']);
            $this->updateDataJsonTable($backupData['info']['random']);

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
            ->each(function ($chunk) use ($tableName, $configId) {
                $chunk = $chunk->map(function ($record) use ($tableName, $configId) {
                    if (isset($record['config_id'])) {
                        $record['config_id'] = $configId;
                    }

                    // Optimasi: Hindari manipulasi JSON jika tidak diperlukan
                    if (isset($this->tergantungDataPenduduk[$tableName])) {
                        $tmpArray = $this->tergantungDataPenduduk[$tableName];

                        if (! empty($record[$tmpArray['key']])) {
                            $uniqueRecordKey = implode('__', array_map(
                                static fn ($col) => $record[$col],
                                $tmpArray['unique_record']
                            ));

                            $foreignKey         = $tmpArray['key'];
                            $oldForeignKeyValue = $record[$foreignKey];

                            $this->tergantungDataPenduduk[$tableName][$foreignKey][$oldForeignKeyValue] = $uniqueRecordKey;

                            $record[$tmpArray['key']] = null;
                        }
                    }

                    return $record;
                });

                // Gunakan bulk insert untuk mempercepat proses
                DB::table($tableName)->insert($chunk->toArray());
            });

        log_message('notice', "Restore data {$tableName} berhasil, total: " . count($tableDetails['data']));
    }

    private function updateDependentData($pendudukData, $rand)
    {
        if (empty($this->tergantungDataPenduduk)) {
            return;
        }

        $configId    = identitas('id');
        $mapPenduduk = collect($pendudukData)->keyBy('id');

        foreach ($this->tergantungDataPenduduk as $table => $item) {
            $key          = $item['key'];
            $uniqueRecord = $item['unique_record'];

            if (! empty($item[$key])) {
                collect($item[$key])
                    ->chunk(500)
                    ->each(function ($chunk) use ($table, $key, $uniqueRecord, $mapPenduduk, $rand, $configId) {
                        foreach ($chunk as $idPenduduk => $record) {
                            $idPendudukBaru = (int) $idPenduduk + $rand[$table];
                            $nik            = $mapPenduduk[$idPendudukBaru]['nik'] ?? null;

                            if ($nik) {
                                $penduduk = DB::table('tweb_penduduk')
                                    ->where(['nik' => $nik, 'config_id' => $configId])
                                    ->first();

                                if ($penduduk) {
                                    $uniqueValue            = explode('__', (string) $record);
                                    $condition              = array_combine($uniqueRecord, $uniqueValue);
                                    $condition['config_id'] = $configId;

                                    DB::table($table)->where($condition)->update([$key => $penduduk->id]);
                                }
                            }
                        }
                    });
            }
        }
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
