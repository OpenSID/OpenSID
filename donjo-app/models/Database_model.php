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

use App\Models\Migrasi;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Database_model extends MY_Model
{
    private $engine           = 'InnoDB';
    private int $showProgress = 0;
    public string $minimumVersion;

    public function __construct()
    {
        parent::__construct();

        $this->load->dbutil();
        if (! $this->dbutil->database_exists($this->db->database)) {
            return;
        }

        $this->minimumVersion = MINIMUM_VERSI;
        $this->cek_engine_db();
        $this->load->dbforge();
        $this->load->helper('theme');
    }

    private function cek_engine_db(): void
    {
        $db_debug           = $this->db->db_debug;
        $this->db->db_debug = false; //disable debugging for queries

        $query = $this->db->query("SELECT `engine` FROM INFORMATION_SCHEMA.TABLES WHERE table_schema= '" . $this->db->database . "' AND table_name = 'user'");
        $error = $this->db->error();
        if ($error['code'] != 0) {
            $this->engine = $query->row()->engine;
        }

        $this->db->db_debug = $db_debug; //restore setting
    }

    private function cekCurrentVersion()
    {
        $version = setting('current_version');
        if ($version == null) {
            // versi tidak terdeteksi dari modul periksa.
            return SettingAplikasi::where('key', 'current_version')->first()->value;
        }

        return $version;
    }

    public function migrasi_db_cri($install = false): void
    {
        $this->load->helper('directory');
        // Tunggu restore selesai sebelum migrasi
        if (isset($this->session->sedang_restore) && $this->session->sedang_restore == 1) {
            return;
        }
        $doesntHaveMigrasiConfigId = ! Schema::hasColumn('migrasi', 'config_id');
        $migratedDatabase          = Migrasi::when($doesntHaveMigrasiConfigId, static fn ($q) => $q->withoutConfigId())->pluck('versi_database', 'versi_database')->toArray();

        session_success();
        $versi          = (int) str_replace('.', '', $this->cekCurrentVersion());
        $minimumVersi   = (int) str_replace('.', '', $this->minimumVersion);
        $currentVersion = currentVersion();

        if (! $install && $versi < $minimumVersi) {
            show_error('<h2>Silakan upgrade dulu ke OpenSID dengan minimal versi ' . $this->minimumVersion . '</h2>');
        }

        $migrations = directory_map('donjo-app/models/migrations', 1);
        // sort by name
        usort($migrations, static fn ($a, $b): int => strcmp($a, $b));

        try {
            foreach ($migrations as $migrate) {
                // Migrasi_2023102701.php contoh nama file yang valid
                preg_match('/\d+/', $migrate, $matches);
                if ($matches) {
                    $migrateName = $matches[0];
                    if (! isset($migratedDatabase[$migrateName])) {
                        $this->jalankan_migrasi('Migrasi_' . $migrateName);
                        // harus dicek ulang karena perubahan struktur tabel migrasi di dalam file migrasi 2025010171
                        $this->updateVersi($migrateName);
                    }
                }
            }
            // untuk mencegah kesalahan nama file migrasi, tambahkan record berdasarkan VERSI_DATABASE saat ini
            $this->updateVersi(VERSI_DATABASE);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            if ($this->getShowProgress()) {
                echo json_encode(['message' => $e->getMessage(), 'status' => 0]);
            }
        }

        // Migrasi Surat Bawaan
        $this->jalankan_migrasi('migrasi_surat_bawaan');

        // Migrasi beta
        $this->jalankan_migrasi('migrasi_beta');

        // Migrasi revisi
        $this->jalankan_migrasi('migrasi_rev');

        // Migrasi umum
        $this->jalankan_migrasi('migrasi_umum');

        // Lengkapi folder desa
        folder_desa();
        kosongkanFolder(config_item('cache_blade'));

        // delete cache modul_aktif dan siappakai
        cache()->forget('siappakai');
        cache()->forget('modul_aktif');

        SettingAplikasi::withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where('key', '=', 'current_version')->update(['value' => $currentVersion]);
        SettingAplikasi::where(['key' => 'compatible_version_general'])->update(['value' => null]);

        log_message('notice', 'Versi database sudah terbaru');
        if ($this->getShowProgress()) {
            // sleep(1.5);
            echo json_encode(['message' => 'Versi database sudah terbaru', 'status' => 0]);
        }

        if (strlen($this->db->password) < 80) {
            updateConfigFile('password', encrypt($this->db->password));
        }

        set_session('success', 'Migrasi berhasil dilakukan');
    }

    // Cek apakah migrasi perlu dijalankan
    public function cek_migrasi($install = true): void
    {
        // Paksa menjalankan migrasi kalau belum
        // Migrasi direkam di tabel migrasi
        $doesntHaveMigrasiConfigId = ! Schema::hasColumn('migrasi', 'config_id');
        if (Migrasi::when($doesntHaveMigrasiConfigId, static fn ($q) => $q->withoutConfigId())->where('versi_database', '=', VERSI_DATABASE)->doesntExist()) {
            $this->migrasi_db_cri($install);
        }
        theme_scan();
    }

    public function get_views()
    {
        $db    = $this->db->database;
        $views = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'VIEW' AND TABLE_SCHEMA = '{$db}'");

        return array_column($views, 'TABLE_NAME');
    }

    /**
     * Get the value of showProgress
     */
    public function getShowProgress()
    {
        return $this->showProgress;
    }

    /**
     * Set the value of showProgress
     *
     * @param mixed $showProgress
     *
     * @return self
     */
    public function setShowProgress($showProgress)
    {
        $this->showProgress = $showProgress;

        return $this;
    }

    public function jalankan_migrasi($migrasi)
    {
        $this->load->model('migrations/' . $migrasi);
        if ($this->getShowProgress()) {
            // sleep(1.5);
            echo json_encode(['message' => 'Jalankan ' . $migrasi, 'status' => 0]);
        }

        try {
            $this->{$migrasi}->up();
            log_message('notice', 'Berhasil Jalankan ' . $migrasi);

            return true;
        } catch (Exception $e) {
            log_message('error', 'Gagal Jalankan ' . $migrasi . ' dengan error ' . $e->getMessage());
            if ($this->getShowProgress()) {
                // sleep(1.5);
                echo json_encode(['message' => 'Gagal Jalankan ' . $migrasi . ' dengan error ' . $e->getMessage(), 'status' => 500]);
            }
        }

        return false;
    }

    private function updateVersi($migrateName)
    {
        $doesntHaveMigrasiConfigId = ! Schema::hasColumn('migrasi', 'config_id');
        if ($doesntHaveMigrasiConfigId) {
            $migrasiDb = DB::table('migrasi')->where(['versi_database' => $migrateName])->first();
            if ($migrasiDb) {
                DB::table('migrasi')->update(['premium' => ['Migrasi_' . $migrateName]]);
            } else {
                DB::table('migrasi')->insert(['versi_database' => $migrateName, 'premium' => ['Migrasi_' . $migrateName]]);
            }
        } else {
            $migrasiDb = Migrasi::firstOrCreate(['versi_database' => $migrateName]);
            $migrasiDb->update(['premium' => ['Migrasi_' . $migrateName]]);
        }
    }
}
