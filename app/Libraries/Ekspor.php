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

use App\Models\Config;
use Illuminate\Support\Facades\File;
use MySQLDump;
use mysqli;
use MySQLImport;

class Ekspor
{
    private $db;
    private array $config;

    public function __construct()
    {
        $databaseConnection = new Database();
        $this->config       = $databaseConnection->getDatabaseOption();
        $this->db           = new mysqli($this->config['host'], $this->config['username'], $this->config['password'], $this->config['database'], $this->config['port']);
    }

    public function backup(): string
    {
        $dump = new MySQLDump($this->db);
        // Save backup to file
        $backupDir = DESAPATH . '/backup';
        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
        } else {
            File::cleanDirectory($backupDir);
        }
        $dbName = $backupDir . '/backup-on-' . date('Y-m-d-H-i-s') . '.sql.gz';
        $dump->save($dbName);

        return $dbName;
    }

    public function restore($filename)
    {
        $import = new MySQLImport($this->db);
        $import->load($filename);
        // Clear cache and reset app key
        $this->clearCache();
        $this->resetAppKey();

        return true;
    }

    private function clearCache()
    {
        // reset cache blade
        kosongkanFolder(config_item('cache_blade'));
        cache()->flush();
        session_destroy();
    }

    private function resetAppKey()
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
