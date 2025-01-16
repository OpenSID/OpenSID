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

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class MultiDB extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'database';

    private array $tergantungDataPenduduk = [
        'tweb_keluarga'        => ['key' => 'nik_kepala', 'nik_kepala' => [], 'unique_record' => ['no_kk']],
        'tweb_rtm'             => ['key' => 'nik_kepala', 'nik_kepala' => [], 'unique_record' => ['no_kk']],
        'tweb_wil_clusterdesa' => ['key' => 'id_kepala', 'id_kepala' => [], 'unique_record' => ['rt', 'rw', 'dusun']],
    ];

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
        isCan('b', $this->sub_modul_ini);
    }

    // PROSES BACKUP DATA
    public function backup(): void
    {
        // $tables     = DB::select('SHOW TABLES');
        // $tableNames = collect($tables)->pluck('Tables_in_' . $this->db->database);
        // $tableNames = $tableNames->filter(static function ($tableName): bool {
        //     $table          = DB::select("SHOW CREATE TABLE {$tableName}");
        //     $createTableSQL = collect($table)->pluck('Create Table')->first();

        //     return preg_match_all('/CONSTRAINT/', $createTableSQL) || $tableName == 'config';
        // })->sort(static function ($a, $b): int {
        //     $a = DB::select("SHOW CREATE TABLE {$a}");
        //     $b = DB::select("SHOW CREATE TABLE {$b}");
        //     $a = collect($a)->pluck('Create Table')->first();
        //     $b = collect($b)->pluck('Create Table')->first();
        //     $a = preg_match_all('/CONSTRAINT/', $a);
        //     $b = preg_match_all('/CONSTRAINT/', $b);

        //     return $a <=> $b;
        // });

        // reorder tabel
        $tableNames = [
            'config',
            'tweb_wil_clusterdesa',
            'tweb_keluarga',
            'tweb_rtm',
            'tweb_penduduk',
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
            'log_notifikasi_mandiri',
            'log_notifikasi_admin',
            'analisis_parameter', // skip dlu, terjadi error

            // error saat restore
            'sys_traffic',
            'fcm_token_mandiri',
            'fcm_token',
        ];

        $tableNames = collect($tableNames)->filter(static fn ($tableName): bool => ! in_array($tableName, $kecuali));        
        // $rand       = mt_rand(100000, 999999);
        // ambil dari 6 digit terakhir kode desa + 999999 agar tidak duplikasi dengan data maksimal
        $kode_desa  = DB::table('config')->where('app_key', get_app_key())->value('kode_desa');
        $rand       = 999999 + (int) substr((string) $kode_desa, -6);
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
        // DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tableNames as $tableName) {            
            $backupData['tabel'][$tableName] = $this->fetchTableData($tableName, $rand);
        }
        // DB::statement('SET FOREIGN_KEY_CHECKS=1');
        // kita rollback lagi agar idnya kembali seperti semula
        $backupFile = 'backup_' . date('YmdHis') . $rand . '.sid';
        $this->load->helper('download');
        force_download($backupFile, json_encode($backupData, JSON_PRETTY_PRINT));
        DB::rollBack();
    }

    // Fungsi untuk mengambil data dari tabel dengan mempertimbangkan relasi
    private function fetchTableData($tableName, int $rand): array
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
        isCan('b', $this->sub_modul_ini, true);

        $this->load->library('upload');
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

        // DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $rand    = $backupData['info']['random'];
            $version = $backupData['info']['versi'];

            if (substr((string) $version, 0, 4) !== substr(VERSION, 0, 4)) {
                redirect_with('error', 'Proses restore dari backup gagal. <br>Versi opensid tidak sama', 'database');
            }
            // cek apakah data dari kolom ini sama dengan data yang ada di database
            // jika sama, maka lanjutkan
            $config_backup   = $backupData['tabel']['config']['data'][0];
            $config_database = DB::table('config')->where('app_key', get_app_key())->first();

            if ($config_backup['kode_desa'] != $config_database->kode_desa) {
                redirect_with('error', 'Proses restore dari backup gagal. <br>Data desa tidak sama dengan data yang ada di database.', ci_route('database'));

                return false;
            }

            // DB::table('config')->where('app_key', get_app_key())->delete();
            $configData = $backupData['tabel']['config']['data'];
            unset($configData['id'], $configData['app_key']);

            (new App\Models\Config())->update($configData);

            // write_file(DESAPATH . 'app_key', $backupData['tabel']['config']['data'][0]['app_key']);
            // delete dulu sebelum direstore
            foreach (array_keys(array_reverse($backupData['tabel'])) as $tableName) {
                if ($tableName == 'config') {
                    continue;
                }
                DB::table($tableName)->where(['config_id' => identitas('id')])->delete();
                // log_message('notice', 'hapus data tabel ' . $tableName);
            }

            foreach ($backupData['tabel'] as $tableName => $tableDetails) {
                log_message('notice', 'mulai restore table ' . $tableName);
                $this->restoreTableData($tableName, $tableDetails);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // susun ulang isi data yang memiliki primary key
            foreach ($backupData['tabel'] as $tableName => $tableDetails) {
                $this->reStrukturTableData($tableName, $tableDetails, $rand);
            }

            // isi kembali nik_kepala dan id_kepala yang diset null
            $mapPenduduk = collect($backupData['tabel']['tweb_penduduk']['data'])->keyBy('id');

            foreach ($this->tergantungDataPenduduk as $table => $item) {
                $key          = $item['key'];
                $uniqueRecord = $item['unique_record'];
                if ($item[$key]) {
                    foreach ($item[$key] as $idPenduduk => $record) {
                        // $idPenduduk pada file backup
                        $idPendudukBaru = (int) $idPenduduk + $rand;
                        $nik            = $mapPenduduk[$idPendudukBaru]['nik'];
                        // get id penduduk terbaru
                        // log_message('error', 'penduduk nik ' . $nik);
                        $penduduk    = DB::table('tweb_penduduk')->where(['nik' => $nik, 'config_id' => identitas('id')])->first();
                        $uniqueValue = explode('__', (string) $record);
                        if ($penduduk) {
                            // log_message('error', 'penduduk ' . $penduduk->id);
                            $condition              = array_combine($uniqueRecord, $uniqueValue);
                            $condition['config_id'] = identitas('id');
                            // log_message('error', 'kondisi ' . json_encode($condition));
                            DB::table($table)->where($condition)->update([$key => $penduduk->id]);
                            // log_message('error', 'tabel ' . $table);
                        }
                    }
                }
            }

            // DB::commit();
            hapus_cache('_cache_modul');
            // reset cache blade
            kosongkanFolder(config_item('cache_blade'));
            cache()->flush();
            redirect_with('success', 'Proses restore dari backup berhasil.', ci_route('database'));
        } catch (Exception $e) {
            // DB::rollback();
            log_message('error', 'gagal restore ' . $e->getMessage());
            redirect_with('error', 'Proses restore dari backup gagal. <br><br>' . $e->getMessage(), ci_route('database'));
        }

        $this->load->helper('theme');

        theme_scan();

        return null;
    }

    private function restoreTableData(string $tableName, array $tableDetails): void
    {
        if ($tableName !== 'config') {
            foreach ($tableDetails['data'] as $record) {
                if (isset($record['config_id'])) {
                    $record['config_id'] = identitas('id');
                }
                // set null dulu, ini saling ketergantungan
                if (isset($this->tergantungDataPenduduk[$tableName])) {
                    $tmpArray = $this->tergantungDataPenduduk[$tableName];
                    if ($record[$tmpArray['key']]) {
                        $uniqueRecord      = $tmpArray['unique_record'];
                        $uniqueRecordValue = [];

                        foreach ($uniqueRecord as $column) {
                            $uniqueRecordValue[] = $record[$column];
                        }
                        $uniqueRecordKey                                                                       = implode('__', $uniqueRecordValue);
                        $this->tergantungDataPenduduk[$tableName][$tmpArray['key']][$record[$tmpArray['key']]] = $uniqueRecordKey;
                        // log_message('error',$tableName .' asli '.$tmpArray['key'].' '.$record[$tmpArray['key']]);
                        $record[$tmpArray['key']] = null;
                        // log_message('error',$tableName .' setelah diubah '.$tmpArray['key'].' '.$record[$tmpArray['key']]);
                    }
                }
                if ($tableDetails['primary_key']) {
                    reset_auto_increment($tableName, $tableDetails['primary_key']);
                }

                try {
                    DB::table($tableName)->insert($record);
                    log_message('notice', 'Restore data ' . $tableName . ' id ' . $record[$tableDetails['primary_key']] . ' berhasil.');
                } catch (Exception $e) {
                    log_message('error', 'Restore data ' . $tableName . ' gagal dengan data ' . json_encode($record));
                    log_message('error', $e->getMessage());
                }
            }
        }
    }

    private function reStrukturTableData(string $tableName, array $tableDetails, string $rand): void
    {
        $primary_key = $tableDetails['primary_key'];
        log_message('notice', 'reStrukturTableData  ' . $tableName . ' ' . $primary_key . ' nilai random ' . $rand);
        $idIni = DB::table('config')->where('app_key', get_app_key())->value('id');
        if ($primary_key !== null) {
            if ($tableName === 'config') {
                // $id = DB::table($tableName)->where('id', '!=', $idIni)->orderBy('id', 'desc')->first()->id ?? 0;
                // DB::table($tableName)->where('id', $idIni)->update(['id' => $id + 1]);
            } else {
                // ada potensi gagal
                try {
                    $id = DB::table($tableName)->where('config_id', '!=', $idIni)->orderBy($primary_key, 'desc')->first()->{$primary_key} ?? 0;
                    $id -= $rand;

                    if (in_array($tableName, array_keys($this->tabelKhusus))) {
                        $child = $this->tabelKhusus[$tableName][1];
                        DB::table($tableName)->where('config_id', $idIni)->where($child, '!=', 0)->update([$child => DB::raw("`{$child}` + {$id}")]);
                    }

                    DB::table($tableName)->where('config_id', $idIni)->update([$primary_key => DB::raw("`{$primary_key}` + {$id}")]);
                } catch (Exception $e) {
                    log_message('error', 'reStrukturTableData  ' . $tableName . ' gagal ' . $e->getMessage());
                }
            }
        }
    }
    // END PROSES RESTORE DATA
}
