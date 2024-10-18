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
    public $modul_ini                     = 'pengaturan';
    public $sub_modul_ini                 = 'aplikasi';
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
            27  => 'config',
            251 => 'tweb_wil_clusterdesa',
            229 => 'tweb_keluarga',
            245 => 'tweb_rtm',
            231 => 'tweb_penduduk',
            214 => 'suplemen',
            215 => 'suplemen_terdata',
            67  => 'kelompok_master',
            65  => 'kelompok',
            66  => 'kelompok_anggota',
            255 => 'widget',
            59  => 'kategori',
            176 => 'pendapat',
            178 => 'pengaduan',
            181 => 'pesan',
            62  => 'kehadiran_jam_kerja',
            61  => 'kehadiran_hari_libur',
            57  => 'inventaris_tanah',
            76  => 'keuangan_master',
            56  => 'inventaris_peralatan',
            55  => 'inventaris_kontruksi',
            54  => 'inventaris_jalan',
            53  => 'inventaris_gedung',
            52  => 'inventaris_asset',
            51  => 'inbox',
            184 => 'point',
            74  => 'keuangan_manual_rinci',
            175 => 'pemilihan',
            185 => 'polygon',
            146 => 'log_login',
            1   => 'alias_kodeisian',
            136 => 'klasifikasi_surat',
            138 => 'kontak',
            139 => 'kontak_grup',
            140 => 'laporan_sinkronisasi',
            141 => 'line',
            142 => 'log_backup',
            151 => 'log_restore_desa',
            170 => 'outbox',
            152 => 'log_sinkronisasi',
            // 154 => "log_surat_dinas",
            156 => 'log_tte',
            157 => 'login_attempts',
            160 => 'media_sosial',
            161 => 'menu',
            169 => 'notifikasi',
            49  => 'hubung_warga',
            60  => 'kehadiran_alasan_keluar',
            47  => 'gis_simbol',
            221 => 'tanah_kas_desa',
            219 => 'sys_traffic',
            186 => 'posyandu',
            222 => 'teks_berjalan',
            // 223 => "theme",
            20  => 'buku_keperluan',
            218 => 'surat_masuk',
            252 => 'urls',
            250 => 'tweb_surat_format',
            22  => 'buku_pertanyaan',
            23  => 'buku_tamu',
            217 => 'surat_keluar',
            25  => 'cdesa',
            16  => 'anjungan',
            // 216 => "surat_dinas",
            213 => 'statistics',
            254 => 'user_grup',
            45  => 'gambar_gallery',
            188 => 'produk_kategori',
            189 => 'program',
            193 => 'ref_jabatan',
            206 => 'ref_syarat_surat',
            209 => 'sentitems',
            210 => 'setting_aplikasi',
            211 => 'setting_modul',
            243 => 'tweb_penduduk_umur',
            36  => 'dokumen',
            237 => 'tweb_penduduk_map',
            // 212 => "shortcut",
            30  => 'covid19_vaksin',
            17  => 'anjungan_menu',
            134 => 'keuangan_ta_triwulan_rinci',
            133 => 'keuangan_ta_triwulan',
            125 => 'keuangan_ta_spp',
            132 => 'keuangan_ta_tbp_rinci',
            131 => 'keuangan_ta_tbp',
            130 => 'keuangan_ta_sts_rinci',
            129 => 'keuangan_ta_sts',
            128 => 'keuangan_ta_spppot',
            127 => 'keuangan_ta_sppbukti',
            126 => 'keuangan_ta_spp_rinci',
            124 => 'keuangan_ta_spjpot',
            123 => 'keuangan_ta_spj_sisa',
            164 => 'mutasi_inventaris_asset',
            168 => 'mutasi_inventaris_tanah',
            183 => 'pesan_mandiri',
            121 => 'keuangan_ta_spj_bukti',
            182 => 'pesan_detail',
            174 => 'pembangunan_ref_dokumentasi',
            173 => 'pembangunan',
            172 => 'pelapak',
            167 => 'mutasi_inventaris_peralatan',
            144 => 'log_hapus_penduduk',
            166 => 'mutasi_inventaris_jalan',
            165 => 'mutasi_inventaris_gedung',
            220 => 'tanah_desa',
            147 => 'log_notifikasi_admin',
            236 => 'tweb_penduduk_mandiri',
            148 => 'log_notifikasi_mandiri',
            122 => 'keuangan_ta_spj_rinci',
            120 => 'keuangan_ta_spj',
            89  => 'keuangan_ref_rek2',
            88  => 'keuangan_ref_rek1',
            87  => 'keuangan_ref_potongan',
            86  => 'keuangan_ref_perangkat',
            85  => 'keuangan_ref_neraca_close',
            84  => 'keuangan_ref_korolari',
            83  => 'keuangan_ref_kegiatan',
            82  => 'keuangan_ref_kecamatan',
            81  => 'keuangan_ref_desa',
            80  => 'keuangan_ref_bunga',
            119 => 'keuangan_ta_saldo_awal',
            78  => 'keuangan_ref_bel_operasional',
            77  => 'keuangan_ref_bank_desa',
            58  => 'kader_pemberdayaan_masyarakat',
            91  => 'keuangan_ref_rek4',
            46  => 'garis',
            42  => 'dtks_ref_lampiran',
            41  => 'dtks_pengaturan_program',
            40  => 'dtks_lampiran',
            29  => 'covid19_pemudik',
            28  => 'covid19_pantau',
            18  => 'area',
            6   => 'analisis_parameter',
            5   => 'analisis_master',
            4   => 'analisis_klasifikasi',
            3   => 'analisis_kategori_indikator',
            90  => 'keuangan_ref_rek3',
            79  => 'keuangan_ref_bidang',
            92  => 'keuangan_ref_sbu',
            103 => 'keuangan_ta_pajak',
            118 => 'keuangan_ta_rpjm_visi',
            117 => 'keuangan_ta_rpjm_tujuan',
            115 => 'keuangan_ta_rpjm_pagu_tahunan',
            114 => 'keuangan_ta_rpjm_pagu_indikatif',
            113 => 'keuangan_ta_rpjm_misi',
            112 => 'keuangan_ta_rpjm_kegiatan',
            111 => 'keuangan_ta_rpjm_bidang',
            110 => 'keuangan_ta_rab_sub',
            109 => 'keuangan_ta_rab_rinci',
            108 => 'keuangan_ta_rab',
            107 => 'keuangan_ta_perangkat',
            106 => 'keuangan_ta_pencairan',
            105 => 'keuangan_ta_pemda',
            104 => 'keuangan_ta_pajak_rinci',
            116 => 'keuangan_ta_rpjm_sasaran',
            102 => 'keuangan_ta_mutasi',
            93  => 'keuangan_ref_sumber',
            94  => 'keuangan_ta_anggaran',
            100 => 'keuangan_ta_jurnal_umum_rinci',
            95  => 'keuangan_ta_anggaran_log',
            99  => 'keuangan_ta_jurnal_umum',
            96  => 'keuangan_ta_anggaran_rinci',
            101 => 'keuangan_ta_kegiatan',
            97  => 'keuangan_ta_bidang',
            98  => 'keuangan_ta_desa',
            227 => 'tweb_desa_pamong',
            253 => 'user',
            19  => 'artikel',
            137 => 'komentar',
            0   => 'agenda',
            26  => 'cdesa_penduduk',
            24  => 'bulanan_anak',
            21  => 'buku_kepuasan',
            64  => 'kehadiran_perangkat_desa',
            150 => 'log_perubahan_penduduk',
            15  => 'anggota_grup_kontak',
            8   => 'analisis_periode',
            158 => 'lokasi',
            149 => 'log_penduduk',
            163 => 'mutasi_cdesa',
            135 => 'kia',
            179 => 'permohonan_surat',
            180 => 'persil',
            63  => 'kehadiran_pengaduan',
            50  => 'ibu_hamil',
            35  => 'disposisi_surat_masuk',
            48  => 'grup_akses',
            155 => 'log_tolak',
            187 => 'produk',
            208 => 'sasaran_paud',
            38  => 'dtks',
            190 => 'program_peserta',
            12  => 'analisis_respon_bukti',
            13  => 'analisis_respon_hasil',
            11  => 'analisis_respon',
            145 => 'log_keluarga',
            7   => 'analisis_partisipasi',
            39  => 'dtks_anggota',
            2   => 'analisis_indikator',
            153 => 'log_surat',
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
        $rand       = 999999 + (int) substr($kode_desa, -6);
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
    private function fetchTableData($tableName, int $rand)
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

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
                log_message('error', 'hapus data tabel ' . $tableName);
            }

            foreach ($backupData['tabel'] as $tableName => $tableDetails) {
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
                        log_message('error', 'penduduk nik ' . $nik);
                        $penduduk    = DB::table('tweb_penduduk')->where(['nik' => $nik, 'config_id' => identitas('id')])->first();
                        $uniqueValue = explode('__', $record);
                        if ($penduduk) {
                            log_message('error', 'penduduk ' . $penduduk->id);
                            $condition              = array_combine($uniqueRecord, $uniqueValue);
                            $condition['config_id'] = identitas('id');
                            log_message('error', 'kondisi ' . json_encode($condition));
                            DB::table($table)->where($condition)->update([$key => $penduduk->id]);
                            log_message('error', 'tabel ' . $table);
                        }
                    }
                }
            }

            DB::commit();
            hapus_cache('_cache_modul');
            redirect_with('success', 'Proses restore dari backup berhasil.', ci_route('database'));
        } catch (Exception $e) {
            DB::rollback();
            log_message('error', 'gagal restore ' . $e->getMessage() );
            redirect_with('error', 'Proses restore dari backup gagal. <br><br>' . $e->getMessage(), ci_route('database'));
        }
    }

    private function restoreTableData($tableName, $tableDetails): void
    {
        if ($tableName != 'config') {
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
                reset_auto_increment($tableName, $tableDetails['primary_key']);
                DB::table($tableName)->insert($record);
                log_message('notice', 'Restore data ' . $tableName . ' id ' . $record['id'] . ' berhasil.');
            }
        }
    }

    private function reStrukturTableData($tableName, $tableDetails, $rand): void
    {
        $primary_key = $tableDetails['primary_key'];
        log_message('notice', 'reStrukturTableData  ' . $tableName . ' id ');
        $idIni = DB::table('config')->where('app_key', get_app_key())->value('id');
        if ($primary_key !== null) {
            if ($tableName == 'config') {
                // $id = DB::table($tableName)->where('id', '!=', $idIni)->orderBy('id', 'desc')->first()->id ?? 0;
                // DB::table($tableName)->where('id', $idIni)->update(['id' => $id + 1]);
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
