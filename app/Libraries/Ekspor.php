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

namespace App\Libraries;

use App\Models\Config;
use Exception;
use Illuminate\Support\Facades\File;
use MySQLDump;
use mysqli;
use MySQLImport;

class Ekspor
{
    private readonly mysqli $db;
    private array $config;
    private ?int $configId = null;

    public function __construct()
    {
        $databaseConnection = new Database();
        $this->config       = $databaseConnection->getDatabaseOption();
        $this->db           = new mysqli($this->config['host'], $this->config['username'], $this->config['password'], $this->config['database'], $this->config['port']);
    }

    /**
     * Set config_id untuk backup per desa.
     *
     * @param int $configId ID dari tabel config (desa) yang akan di-backup
     */
    public function setConfigId(int $configId): self
    {
        $this->configId = $configId;

        return $this;
    }

    public function backup(): string
    {
        try {
            $dump = new MySQLDump($this->db, 'utf8mb4', $this->configId);

            // Daftarkan tabel config sebagai master table jika backup per desa
            if ($this->configId !== null) {
                $dump->addMasterTable('config', 'id');
            }

            // Save backup to file
            $backupDir = DESAPATH . '/backup';
            if (! is_dir($backupDir)) {
                if (! mkdir($backupDir, 0777, true)) {
                    throw new Exception("Gagal membuat direktori backup: {$backupDir}");
                }
            } else {
                File::cleanDirectory($backupDir);
            }

            // Naming untuk file backup
            $timestamp = date('Y-m-d-H-i-s');
            if ($this->configId !== null) {
                $desa     = identitas();
                $desaName = $desa ? str_slug($desa->nama_desa) : 'desa';
                $dbName   = "{$backupDir}/backup-desa-{$this->configId}-{$desaName}-{$timestamp}.sql.gz";
            } else {
                $dbName = "{$backupDir}/backup-on-{$timestamp}.sql.gz";
            }

            $dump->save($dbName);

            if (! file_exists($dbName)) {
                throw new Exception("File backup gagal dibuat di: {$dbName}");
            }

            return $dbName;
        } catch (Exception $e) {
            throw new Exception('Backup database gagal: ' . $e->getMessage(), 0, $e);
        }
    }

    public function restore(string $filename): bool
    {
        $import = new MySQLImport($this->db);
        $import->load($filename);
        // Clear cache and reset app key
        $this->clearCache();
        $this->resetAppKey();

        return true;
    }

    private function clearCache(): void
    {
        // reset cache blade
        kosongkanFolder(config_item('cache_blade'));
        cache()->flush();
        session_destroy();
    }

    private function resetAppKey(): void
    {
        $app_key = Config::first()->app_key;
        if (empty($app_key)) {
            $app_key = set_app_key();
            Config::first()->update(['app_key' => $app_key]);
        }

        file_put_contents(DESAPATH . 'app_key', $app_key);
        updateConfigFile('password', encrypt($this->config['password']));
    }
}
