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

use App\Models\Migrasi;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;

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

        $migratedDatabase = Migrasi::pluck('versi_database', 'versi_database')->toArray();

        session_success();
        $versi        = (int) str_replace('.', '', $this->cekCurrentVersion());
        $minimumVersi = (int) str_replace('.', '', $this->minimumVersion);

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
                        $migrasiDb = Migrasi::firstOrCreate(['versi_database' => $migrateName]);
                        $migrasiDb->update(['premium' => ['Migrasi_' . $migrateName]]);
                    }
                }
            }
            // untuk mencegah kesalahan nama file migrasi, tambahkan record berdasarkan VERSI_DATABASE saat ini
            $migrasiDb = Migrasi::firstOrCreate(['versi_database' => VERSI_DATABASE]);
            $migrasiDb->update(['premium' => ['Migrasi_' . VERSI_DATABASE]]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            if ($this->getShowProgress()) {
                echo json_encode(['message' => $e->getMessage(), 'status' => 0]);
            }
        }

        // Migrasi dev
        $this->jalankan_migrasi('migrasi_dev');

        // Lengkapi folder desa
        folder_desa();
        kosongkanFolder(config_item('cache_blade'));
        // cache()->flush();

        SettingAplikasi::withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where('key', '=', 'current_version')->update(['value' => currentVersion()]);
        $this->load->model('track_model');
        $this->track_model->kirim_data();

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
        if (Migrasi::where('versi_database', '=', VERSI_DATABASE)->doesntExist()) {
            $this->migrasi_db_cri($install);
            // Kirim versi aplikasi ke layanan setelah migrasi selesai
            kirim_versi_opensid();
        }
    }

    public function impor_data_awal_analisis(): void
    {
        $this->load->model('analisis_import_model');

        // Tambahkan kembali Analisis DDK Profil Desa dan Analisis DAK Profil Desa
        $file_analisis = FCPATH . 'assets/import/analisis_DDK_Profil_Desa.xlsx';
        $this->analisis_import_model->impor_analisis($file_analisis, 'DDK02', 1);
        $file_analisis = FCPATH . 'assets/import/analisis_DAK_Profil_Desa.xlsx';
        $this->analisis_import_model->impor_analisis($file_analisis, 'DAK02', 1);
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
}
